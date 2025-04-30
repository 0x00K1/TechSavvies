// root.js – Enhanced Admin Dashboard Logic (Schema-Agnostic Version)
// ------------------------------------------------------------
// This file implements a database-agnostic version of the admin dashboard
// that dynamically discovers table structure from the backend API.
// ------------------------------------------------------------

// ===== 1. Bootstrapping  ====================================
document.addEventListener('DOMContentLoaded', () => {
    setupNavigation();
    initializeTables();
    setupModalHandlers();
    setupFormHandlers();
  });
  
  // ===== 2. Toolbar Navigation  ===============================
  function setupNavigation() {
    const buttons = {
      roots: document.getElementById('manageRootButton'),
      products: document.getElementById('manageProButton'),
      users: document.getElementById('manageUserButton'),
      orders: document.getElementById('OrdersButton'),
      payments: document.getElementById('transactionsButton'),
      reviews: document.getElementById('ReviewsButton'),
      logout: document.getElementById('LogoutButton'),
    };
  
    const sections = {
      roots: document.getElementById('rootsDisplay'),
      products: document.getElementById('editProduct'),
      users: document.getElementById('usersDisplay'),
      orders: document.getElementById('ordersDisplay'),
      payments: document.getElementById('transactionsDisplay'),
      reviews: document.getElementById('reviewsDisplay'),
    };
  
    const hideAllSections = () => Object.values(sections).forEach(s => s && (s.style.display = 'none'));
    const resetAllButtons = () => Object.values(buttons).forEach(b => b && b.classList.remove('active'));
  
    const activate = key => {
      hideAllSections();
      resetAllButtons();
      sections[key].style.display = 'block';
      buttons[key].classList.add('active');
    };
  
    // initial view
    activate('roots');
  
    // button listeners
    buttons.roots && buttons.roots.addEventListener('click', () => activate('roots'));
    buttons.products && buttons.products.addEventListener('click', () => activate('products'));
    buttons.users && buttons.users.addEventListener('click', () => activate('users'));
    buttons.orders && buttons.orders.addEventListener('click', () => activate('orders'));
    buttons.payments && buttons.payments.addEventListener('click', () => activate('payments'));
    buttons.reviews && buttons.reviews.addEventListener('click', () => activate('reviews'));
  
    // logout
    buttons.logout && buttons.logout.addEventListener('click', () => {
      if (confirm('Are you sure you want to log out?')) {
        window.location.href = '/includes/cls.php';
      }
    });
  }
  
  // ===== 3. Table Class ===============================
  class Table {
    constructor(opt) {
      // core
      this.url = opt.url;
      this.tableName = opt.tableName; // Just for identifying which table we're working with
      this.idNamingSuffix = opt.idNamingSuffix;
      this.categoryFilter = null; 
  
      // pagination/search/sort state
      this.page = 1;
      this.rowsPerPage = opt.rowsPerPage || 25;
      this.totalRecords = 0;
      this.sortCol = null;
      this.sortDir = 'asc';
      this.searchQuery = '';
  
      // structure fetched from backend (initially empty)
      this.columns = [];
      this.pk = '';
      this.editable = [];
      this.csrfToken = '';
  
      // caching keyed by compound key
      this.cache = {};
  
      // initialization
      this.initControls();
      this.fetchStructure().then(() => this.fetchData());
    }
  
    // ---------- Initial Structure Fetch -------------------------
    async fetchStructure() {
      try {
        this.showLoading();
        const response = await fetch(`${this.url}?action=getStructure&tableName=${this.tableName}`);
        if (!response.ok) throw new Error(`Failed to fetch structure: ${response.statusText}`);
        
        const structure = await response.json();
        
        // Store the structure information
        this.columns = structure.displayColumns;
        if (!this.columns.includes('Action') && (structure.editableColumns.length > 0 || structure.deletable)) {
          this.columns.push('Action'); // Add Action column if editable columns exist
        }
        this.pk = structure.primaryKey;
        this.editable = structure.editableColumns;
        this.deletable = !!structure.deletable;
        this.csrfToken = structure.csrf_token;
        
        return structure;
      } catch (error) {
        this.showError(error);
        return null;
      } finally {
        this.hideLoading();
      }
    }
  
    // ---------- Control Wiring --------------------------------
    initControls() {
      // pagination buttons
      this.btnPrev = document.getElementById(`${this.idNamingSuffix}PrevPage`);
      this.btnNext = document.getElementById(`${this.idNamingSuffix}NextPage`);
      this.lblPage = document.getElementById(`${this.idNamingSuffix}CurrentPage`);
      this.selRows = document.getElementById(`${this.idNamingSuffix}RowsPerPage`);
      this.infoElm = document.getElementById(`${this.idNamingSuffix}PaginationInfo`);
      this.fieldSearch = document.getElementById(`${this.idNamingSuffix}SearchField`);
      this.btnSearch = document.getElementById(`${this.idNamingSuffix}SearchButton`);
  
      // event listeners
      this.btnPrev && this.btnPrev.addEventListener('click', () => { if (this.page > 1) { this.page--; this.fetchData(); } });
      this.btnNext && this.btnNext.addEventListener('click', () => { if (this.page < this.totalPages) { this.page++; this.fetchData(); } });
      this.selRows && this.selRows.addEventListener('change', () => { this.rowsPerPage = parseInt(this.selRows.value); this.page = 1; this.cache = {}; this.fetchData(); });
      if (this.btnSearch && this.fieldSearch) {
        // ensure the button never submits the page
        this.btnSearch.setAttribute('type', 'button');
    
        // shared handler:
        const doSearch = e => {
          // stop any real form submission
          e && e.preventDefault();
          this.searchQuery = this.fieldSearch.value.trim();
          this.page        = 1;
          this.cache       = {};
          this.fetchData();
        };
    
        // click → AJAX search
        this.btnSearch.addEventListener('click', doSearch);
    
        // pressing “Enter” in the field -> AJAX only
        this.fieldSearch.addEventListener('keypress', e => {
          if (e.key === 'Enter') {
            e.preventDefault();   // stop real submit
            doSearch(e);
          }
        });
      }
    }
  
    // ---------- Fetching --------------------------------------
    cacheKey() {
      return `${this.page}_${this.rowsPerPage}_${this.sortCol || ''}_${this.sortDir}_${this.searchQuery}`;
    }
  
    async fetchData() {
      const key = this.cacheKey();
      if (this.cache[key]) {
        this.render(this.cache[key]);
        return;
      }
      
      // build URL
      let q = `?action=fetch&tableName=${this.tableName}&rowsPerPage=${this.rowsPerPage}&page=${this.page}`;
      if (this.sortCol) q += `&sortBy=${this.sortCol}&sortDirection=${this.sortDir}`;
      if (this.searchQuery) q += `&search=${encodeURIComponent(this.searchQuery)}`;
      if (this.categoryFilter) q += `&filterCategory=${this.categoryFilter}`;
      q += `&csrf_token=${this.csrfToken}`;
  
      // loading state
      this.showLoading();
  
      try {
        const response = await fetch(this.url + q);
        if (!response.ok) throw new Error(response.statusText);
        
        const res = await response.json();
        
        // Update CSRF token in case it's rotated
        if (res.csrf_token) {
          this.csrfToken = res.csrf_token;
        }
        
        this.totalRecords = res.totalRecords;
        this.totalPages = Math.ceil(this.totalRecords / this.rowsPerPage) || 1;
        this.cache[key] = res.records;
        this.render(res.records);
      } catch (err) {
        this.showError(err);
      } finally {
        this.hideLoading();
      }
    }
  
    // ---------- Rendering -------------------------------------
    render(rows) {
      const container = document.getElementById(`${this.idNamingSuffix}TableDisplay`);
      if (!container) return;
      container.innerHTML = '';
  
      const table = document.createElement('table');
      table.className = 'data-table';
  
      // head
      const thead = document.createElement('thead');
      const hr = document.createElement('tr');
      this.columns.forEach(col => {
        const th = document.createElement('th');
        if (col === 'Action') {
          th.textContent = 'Action';
        } else {
          th.innerHTML = `${col} <span class="sort-icon">${this.sortCol === col ? (this.sortDir === 'asc' ? '↑' : '↓') : '↕'}</span>`;
          th.style.cursor = 'pointer';
          th.addEventListener('click', () => { this.toggleSort(col); });
        }
        hr.appendChild(th);
      });
      thead.appendChild(hr);
      table.appendChild(thead);
  
      // body
      const tbody = document.createElement('tbody');
      if (!rows || rows.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = this.columns.length;
        td.className = 'no-data';
        td.textContent = 'No data found.';
        tr.appendChild(td);
        tbody.appendChild(tr);
      } else {
        rows.forEach(rec => {
          const tr = document.createElement('tr');
          tr.dataset.id = rec[this.pk];
          this.columns.forEach(col => {
            const td = document.createElement('td');
            if (col === 'Action') {
              this.renderActionCell(td, rec);
            } else if (col === 'picture' && this.tableName === 'products') {
              const img = document.createElement('img');
              const fileName = rec[col];
              const urls = [];
            
              if (fileName) {
                // 1) try the raw path
                urls.push(fileName);
                // 2) then the “assets/images” path
                urls.push(`/assets/images/products/${fileName}`);
              }
              // 3) finally the hard-coded default
              urls.push('/assets/images/products/default.png');
            
              let attempt = 0;
              img.onerror = () => {
                attempt++;
                if (attempt < urls.length) {
                  img.src = urls[attempt];
                } else {
                  // give up — clear the handler so we don’t loop forever
                  img.onerror = null;
                }
              };
            
              // kick off the first load
              img.src = urls[0];
              img.alt = rec.product_name || '';
              img.className = 'product-thumb';
              td.appendChild(img);
            } else {
              td.textContent = rec[col] == null ? '-' : rec[col];
            }
            tr.appendChild(td);
          });
          tbody.appendChild(tr);
        });
      }
      table.appendChild(tbody);
      container.appendChild(table);
  
      this.updatePagerUI();
    }
  
    renderActionCell(td, rec) {
      const wrap = document.createElement('div');
      wrap.className = 'action-container';
    
      // ===== Roots table: =====
      if (this.tableName === 'roots') {
        // If this row is *not* the currently-logged in root, show Delete:
        if (rec[this.pk] !== window.currentRootId) {
          const btnDel = document.createElement('button');
          btnDel.textContent = 'Delete';
          btnDel.className   = 'action-button delete-button';
          btnDel.addEventListener('click', () => this.confirmDeleteRoot(rec[this.pk]));
          wrap.appendChild(btnDel);
        } else {
          // If it *is* you, show a little placeholder instead of Delete
          const span = document.createElement('span');
          span.textContent = 'You :)';
          wrap.appendChild(span);
        }
    
      // ===== All other tables: =====
      } else {
        // 1) Edit button if editable
        if (this.editable.length > 0) {
          const btnEdit = document.createElement('button');
          btnEdit.textContent = 'Edit';
          btnEdit.className   = 'action-button edit-button';
          btnEdit.addEventListener('click', () => this.startEdit(rec[this.pk]));
          wrap.appendChild(btnEdit);
        }
    
        // 2) Delete button if deletable
        if (this.deletable) {
          const btnDel = document.createElement('button');
          btnDel.textContent = 'Delete';
          btnDel.className   = 'action-button delete-button';
          btnDel.addEventListener('click', () => this.confirmDelete(rec[this.pk]));
          wrap.appendChild(btnDel);
        }
    
        // 3) Grant Root (only in customers view)
        if (this.tableName === 'customers') {
          const btnRoot = document.createElement('button');
          btnRoot.textContent = 'Grant Root';
          btnRoot.className   = 'action-button process-button';
          btnRoot.addEventListener('click', () => this.confirmGrantRoot(rec[this.pk]));
          wrap.appendChild(btnRoot);
        }
      }
    
      // clear & append
      td.innerHTML = '';
      td.appendChild(wrap);
    }    
  
    // ---------- Sorting ---------------------------------------
    toggleSort(col) {
      if (this.sortCol === col) {
        this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortCol = col;
        this.sortDir = 'asc';
      }
      this.page = 1;
      this.cache = {};
      this.fetchData();
    }
  
    // ---------- Pagination UI --------------------------------
    updatePagerUI() {
      this.lblPage && (this.lblPage.textContent = this.page);
      if (this.btnPrev) this.btnPrev.disabled = this.page <= 1;
      if (this.btnNext) this.btnNext.disabled = this.page >= this.totalPages;
  
      if (this.infoElm) {
        const start = this.totalRecords === 0 ? 0 : ((this.page - 1) * this.rowsPerPage) + 1;
        const end = Math.min(this.page * this.rowsPerPage, this.totalRecords);
        this.infoElm.innerHTML = `Showing <span>${start}</span> to <span>${end}</span> of <span>${this.totalRecords}</span> items`;
      }
    }
  
    // ---------- Loading / Error UI ---------------------------
    showLoading() {
      const cont = document.getElementById(`${this.idNamingSuffix}TableDisplay`);
      if (cont) {
        cont.classList.add('loading');
        cont.innerHTML = '<div class="loading-spinner">Loading…</div>';
      }
    }
    
    hideLoading() {
      const cont = document.getElementById(`${this.idNamingSuffix}TableDisplay`);
      cont && cont.classList.remove('loading');
    }
    
    showError(err) {
      const cont = document.getElementById(`${this.idNamingSuffix}TableDisplay`);
      if (cont) {
        cont.innerHTML = `<div class="error-message">${err.message}</div>`;
      }
    }
  
    // ========== Editing =======================================
    startEdit(id) {
      if (this.editRow) {
          this.cancelEdit();
      }

      // find the <tr> and the underlying record
      const tr = document.querySelector(`#${this.idNamingSuffix}TableDisplay tr[data-id='${id}']`);
      if (!tr) return;
      this.editRow = tr;

      // grab the JS object for this row from cache
      const key = this.cacheKey();
      const rec = (this.cache[key] || []).find(r => String(r[this.pk]) === String(id));

      this.backup = {};

      tr.querySelectorAll('td').forEach((td, i) => {
          const col = this.columns[i];

          // —— special “category” cell: swap in a <select>
          if (col === 'category') {
              this.backup['category']    = td.innerHTML;
              this.backup['category_id'] = rec.category_id;

              const sel = document.createElement('select');
              window.categoryList.forEach(c => {
                  const opt = document.createElement('option');
                  opt.value = c.category_id;
                  opt.textContent = c.category_name;
                  if (c.category_id === rec.category_id) opt.selected = true;
                  sel.appendChild(opt);
              });

              td.innerHTML = '';
              td.appendChild(sel);
              return;
          }

          // —— “Action” cell: replace with Save/Cancel buttons
          if (col === 'Action') {
              td.innerHTML = '';
              const btnSave   = document.createElement('button');
              const btnCancel = document.createElement('button');
              btnSave.textContent   = 'Save';
              btnSave.className     = 'action-button save-button';
              btnCancel.textContent = 'Cancel';
              btnCancel.className   = 'action-button cancel-button';
              td.appendChild(btnSave);
              td.appendChild(btnCancel);

              btnSave.addEventListener('click',   () => this.saveEdit(id));
              btnCancel.addEventListener('click', () => this.cancelEdit());
              return;
          }

          // —— orders.status dropdown
          if (this.tableName === 'orders' && col === 'status') {
              this.backup['status'] = td.textContent.trim();
              const sel = document.createElement('select');
              ['pending','paid','shipped','completed','cancelled'].forEach(optVal => {
                  const opt = document.createElement('option');
                  opt.value = optVal;
                  opt.textContent = optVal.charAt(0).toUpperCase() + optVal.slice(1);
                  if (optVal === rec.status) opt.selected = true;
                  sel.appendChild(opt);
              });
              td.innerHTML = '';
              td.appendChild(sel);
              return;
          }

          // —— payments.payment_status dropdown
          if (this.tableName === 'payments' && col === 'payment_status') {
              this.backup['payment_status'] = td.textContent.trim();
              const sel = document.createElement('select');
              ['pending','completed','failed'].forEach(optVal => {
                  const opt = document.createElement('option');
                  opt.value = optVal;
                  opt.textContent = optVal.charAt(0).toUpperCase() + optVal.slice(1);
                  if (optVal === rec.payment_status) opt.selected = true;
                  sel.appendChild(opt);
              });
              td.innerHTML = '';
              td.appendChild(sel);
              return;
          }

          // —— normal editable columns (text, picture, etc.)
          if (this.editable.includes(col)) {
              let initialValue = '';
              if (col === 'picture') {
                  this.backup[col] = td.innerHTML;
                  const img = td.querySelector('img');
                  if (img) initialValue = img.getAttribute('src');
              } else {
                  initialValue      = td.textContent;
                  this.backup[col] = initialValue;
              }

              const input = document.createElement('input');
              input.value = initialValue;
              if (col === 'price') {
                  input.type = 'number';
                  input.step = '0.01';
              } else if (col === 'stock') {
                  input.type = 'number';
                  input.step = '1';
              }

              td.innerHTML = '';
              td.appendChild(input);
          }
          // non-editable & non‐category columns left alone
      });
    }
    
    cancelEdit() {
        if (!this.editRow) return;
      
        this.editRow.querySelectorAll('td').forEach((td, i) => {
          const col = this.columns[i];
          if (col==='category') {
            td.innerHTML = this.backup['category'];
          }
          else if (col === 'Action') {
            const recId = this.editRow.dataset.id;
            td.innerHTML = '';
            this.renderActionCell(
              td,
              this.cache[this.cacheKey()].find(r => String(r[this.pk]) === String(recId))
            );
          }
          else if (this.editable.includes(col)) {
            if (col === 'picture') {
              // Restore the full <img> HTML
              td.innerHTML = this.backup[col];
            } else {
              // Restore text
              td.textContent = this.backup[col];
            }
          }
        });
      
        this.editRow = null;
        this.backup = {};
    }
  
    async saveEdit(id) {
      if (!this.editRow) return;
      const updated = {};

      // gather inputs & selects
      this.editRow.querySelectorAll('td').forEach((td, i) => {
        const col = this.columns[i];

        // category dropdown
        if (col === 'category') {
          const sel = td.querySelector('select');
          const val = sel && sel.value;
          if (val != this.backup['category_id']) {
            updated['category_id'] = val;
          }
          return;
        }

        // orders.status dropdown
        if (this.tableName === 'orders' && col === 'status') {
          const sel = td.querySelector('select');
          const val = sel && sel.value;
          if (val && val !== this.backup['status']) {
            updated['status'] = val;
          }
          return;
        }

        // payments.payment_status dropdown
        if (this.tableName === 'payments' && col === 'payment_status') {
          const sel = td.querySelector('select');
          const val = sel && sel.value;
          if (val && val !== this.backup['payment_status']) {
            updated['payment_status'] = val;
          }
          return;
        }

        // normal editable columns => <input>
        if (this.editable.includes(col)) {
          const inp = td.querySelector('input');
          if (!inp) return;
          const val = inp.value;
          if (val !== this.backup[col]) {
            updated[col] = val;
          }
        }
      });

      // nothing changed?
      if (Object.keys(updated).length === 0) {
        this.cancelEdit();
        return;
      }

      // build the POST body
      const body = new FormData();
      body.append('action',      'update');
      body.append('id',          id);
      body.append('csrf_token', this.csrfToken);
      Object.entries(updated).forEach(([k, v]) => body.append(k, v));

      try {
        const response = await fetch(`${this.url}?action=update&tableName=${this.tableName}&id=${id}`, {
          method: 'POST',
          body
        });
        if (!response.ok) throw new Error(response.statusText);
        const res = await response.json();

        if (res.success) {
          if (res.csrf_token) this.csrfToken = res.csrf_token;
          this.showToast('Record updated', 'success');
          this.cache = {};
          this.fetchData();
        } else {
          throw new Error(res.error || 'Update failed');
        }
      } catch (e) {
        this.showToast(e.message, 'error');
      }
    }
  
    // ========== Delete ========================================
    confirmDelete(id) {
      const modal = document.getElementById('confirmationModal');
      if (!modal) return alert('Modal missing!');

      // map tableNames to user-friendly nouns
      const labels = {
        products:  'product',
        customers: 'user',
        orders:    'order',
        payments:  'transaction',
        reviews:   'review'
      };
      const noun = labels[this.tableName] || 'item';

      // inject a custom prompt
      modal.querySelector('.modal-content p').textContent =
        `Are you sure you want to delete this ${noun}? This action cannot be undone.`;

      modal.style.display = 'block';
      const btnOK     = modal.querySelector('.modal-button-confirm');
      const btnCancel = modal.querySelector('.modal-button-cancel');

      const close = () => {
        modal.style.display = 'none';
        // remove old listeners
        btnOK.replaceWith(btnOK.cloneNode(true));
        btnCancel.replaceWith(btnCancel.cloneNode(true));
      };

      btnOK.addEventListener('click', () => {
        close();
        this.deleteRecord(id);
      });
      btnCancel.addEventListener('click', close);

      // click outside to cancel
      window.onclick = e => {
        if (e.target === modal) close();
      };
    }
  
    async deleteRecord(id) {
      try {
        const formData = new FormData();
        formData.append('csrf_token', this.csrfToken);
  
        const response = await fetch(`${this.url}?action=delete&tableName=${this.tableName}&id=${id}`, {
          method: 'POST',
          body: formData
        });
        
        if (!response.ok) throw new Error(response.statusText);
        const res = await response.json();
        
        if (res.success) {
          this.showToast('Record deleted', 'success');
          // Update CSRF token if provided
          if (res.csrf_token) this.csrfToken = res.csrf_token;
          this.cache = {};
          if (this.page > 1 && this.cacheKey() === '') this.page--;
          this.fetchData();
        } else {
          throw new Error(res.error || 'Delete failed');
        }
      } catch (e) {
        this.showToast(e.message, 'error');
      }
    }
  
    // ---------- Toast Notifications ---------------------------
    showToast(msg, type = 'info') {
      let t = document.getElementById('dashboard-toast');
      if (!t) {
        t = document.createElement('div');
        t.id = 'dashboard-toast';
        document.body.appendChild(t);
      }
      t.className = `toast ${type}`;
      t.textContent = msg;
      t.style.display = 'block';
      clearTimeout(this.toastTO);
      this.toastTO = setTimeout(() => {
        t.style.display = 'none';
      }, 3000);
    }


    /**
     * Prompt for a password, call the new grantroot endpoint,
     * then toast success or error.
     */
    confirmGrantRoot(id) {
      const pwd = prompt('Enter a new root password for this user:');
      if (!pwd) return;
    
      const body = new FormData();
      body.append('action',   'grantroot');
      body.append('id',       id);
      body.append('password', pwd);
      body.append('csrf_token', this.csrfToken);
    
      fetch(`${this.url}?action=grantRoot&tableName=${this.tableName}`, {
        method: 'POST',
        body
      })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          this.csrfToken = res.csrf_token;
          this.showToast('User is now a Root', 'success');
        } else {
          throw new Error(res.error || 'Failed to grant root');
        }
      })
      .catch(err => this.showToast(err.message, 'error'));
    }

    /**
     * Prompt for the current root’s password, then call
     * our new deleteRoot endpoint.
     */
    confirmDeleteRoot(id) {
      const pwd = prompt('Enter **that user’s** root password to confirm deletion:');
      if (!pwd) return;
    
      const body = new FormData();
      body.append('action',     'deleteRoot');
      body.append('id',         id);
      body.append('password',   pwd);
      body.append('csrf_token', this.csrfToken);
    
      fetch(`${this.url}?action=deleteRoot&tableName=${this.tableName}`, {
        method: 'POST',
        body
      })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            this.csrfToken = res.csrf_token;
            this.showToast('Root account deleted', 'success');
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          } else {
            throw new Error(res.error || 'Delete failed');
          }
        })
        .catch(err => this.showToast(err.message, 'error'));
    }    
  }
  
  // ===== 4. Table Instances  ==================================
  async function initializeTables() {
    const baseUrl = '/assets/php/root_php/fetchTable.php';
    
    window.rootsTable = new Table({
      url: baseUrl,
      tableName: 'roots',
      rowsPerPage: 10,
      idNamingSuffix: 'roots',
    });

    window.productsTable = new Table({
      url: baseUrl,
      tableName: 'products',
      rowsPerPage: 10,
      idNamingSuffix: 'products',
    });
  
    window.usersTable = new Table({
      url: baseUrl,
      tableName: 'customers',
      rowsPerPage: 10,
      idNamingSuffix: 'users',
    });
  
    window.ordersTable = new Table({
      url: baseUrl,
      tableName: 'orders',
      rowsPerPage: 10,
      idNamingSuffix: 'orders',
    });
  
    window.paymentsTable = new Table({
      url: baseUrl,
      tableName: 'payments',
      rowsPerPage: 10,
      idNamingSuffix: 'transactions',
    });
  
    window.reviewsTable = new Table({
      url: baseUrl,
      tableName: 'reviews',
      rowsPerPage: 10,
      idNamingSuffix: 'reviews',
    });

    // Load category list once for both filter and edit dropdowns
    await loadProductCategories();
  }

  async function loadProductCategories() {
    const res = await fetch('../assets/php/root_php/fetchTable.php?action=fetch&tableName=categories');
    const json = await res.json();
    // stash globally
    window.categoryList = json.records;  // [ {category_id, category_name}, … ]
  
    // populate filter dropdown
    const sel = document.getElementById('productsCategoryFilter');
    sel.innerHTML = `<option value="">All</option>` +
        window.categoryList.map(c =>
        `<option value="${c.category_id}">${c.category_name}</option>`
        ).join('');

    sel.addEventListener('change', () => {
        window.productsTable.categoryFilter = sel.value || null;
        window.productsTable.page = 1;
        window.productsTable.cache = {};
        window.productsTable.fetchData();
    });

    // — populate the **Add-Product** popup dropdown
    const addSel = document.getElementById('addProductCategory');
    addSel.innerHTML = `<option value="">–– Select a category ––</option>`;
    window.categoryList.forEach(c => {
        const opt = document.createElement('option');
        opt.value       = c.category_id;
        opt.textContent = c.category_name;
        addSel.appendChild(opt);
    });
  }
  
  // ===== 5. Modal + Form Handlers  =============================
  function setupModalHandlers() {
    // Add‑product popup
    const btnOpen = document.getElementById('addProPopupButton');
    const popup = document.getElementById('addProPopupDisplay');
    const btnClose = document.getElementById('closeProductPopUpButton');
    btnOpen && btnOpen.addEventListener('click', () => popup.style.display = 'block');
    btnClose && btnClose.addEventListener('click', () => popup.style.display = 'none');
    window.onclick = e => { if (e.target === popup) popup.style.display = 'none'; };
    
    // Close confirmation modal on outside click
    window.closeConfirmationModal = function() {
      const modal = document.getElementById('confirmationModal');
      if (modal) modal.style.display = 'none';
    };
    
    // Confirmation modal is handled by the Table class confirm/delete methods
    window.confirmDelete = function() {
      // This is populated dynamically by the Table class
      console.warn('confirmDelete called but no handler is attached');
    };
  }
  
  function setupFormHandlers() {
    // add‑product form
    const form = document.getElementById('addProductForm');
    if (!form) return;
  
    form.addEventListener('submit', async e => {
      e.preventDefault();
      
      // First ensure we have the CSRF token
      if (!window.productsTable || !window.productsTable.csrfToken) {
        productsTable.showToast('Missing CSRF token, please try again', 'error');
        return;
      }
      
      const fd = new FormData(form);
      fd.append('action', 'create');
      fd.append('csrf_token', window.productsTable.csrfToken);
      
      try {
        const response = await fetch('../assets/php/root_php/fetchTable.php?action=create&tableName=products', {
          method: 'POST',
          body: fd
        });
        
        if (!response.ok) throw new Error(response.statusText);
        const res = await response.json();
        
        if (res.success) {
          form.reset();
          document.getElementById('addProPopupDisplay').style.display = 'none';
          
          // Update CSRF token if provided
          if (res.csrf_token) window.productsTable.csrfToken = res.csrf_token;
          
          productsTable.cache = {};
          productsTable.page = 1;
          productsTable.fetchData();
          productsTable.showToast('Product added', 'success');
        } else {
          throw new Error(res.error || 'Add failed');
        }
      } catch (err) {
        productsTable.showToast(err.message, 'error');
      }
    });
  }
  
  // ===== 6. Minimal CSS (auto‑injected) =======================
  (function injectStyles() {
    if (document.getElementById('dashboard-css')) return;
    const css = `
    .data-table{width:100%;border-collapse:collapse;font-family:inherit}
    .data-table th,.data-table td{padding:8px 6px;border-bottom:1px solid #ddd;}
    .data-table th{background:#f7f7f7;user-select:none}
    .data-table tr:hover{background:#fafafa}
    .product-thumb{width:36px;height:36px;object-fit:cover;border-radius:4px}
    .action-container{display:flex;gap:6px;justify-content:center}
    .action-button{padding:4px 8px;font-size:0.8rem;border:none;border-radius:4px;cursor:pointer}
    .edit-button{background:#0078d4;color:#fff}.delete-button{background:#e81123;color:#fff}
    .save-button{background:#107c10;color:#fff}.cancel-button{background:#f3f2f1}
    .loading-spinner{padding:20px;text-align:center}
    .toast{position:fixed;bottom:20px;right:20px;padding:10px 14px;border-radius:4px;color:#fff;display:none;z-index:9999}
    .toast.info{background:#0078d4}.toast.success{background:#107c10}.toast.error{background:#e81123}
    `;
    const st = document.createElement('style');
    st.id = 'dashboard-css';
    st.textContent = css;
    document.head.appendChild(st);
  })();