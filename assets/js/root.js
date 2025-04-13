//Responsive code needs refactoring, organizing. and remove duplicate code.

document.addEventListener("DOMContentLoaded", function () {
	/*buttons*/
	const managePro_button = document.getElementById("managePro_button");
	const Orders_button = document.getElementById("Orders_button");
	const transaction_button = document.getElementById("transactions_button");
	const review_button = document.getElementById("Reviews_button");
	const logout_button = document.getElementById("Logout_button");
	const search_button = document.getElementById("search_button");

	/* div or displays*/
	const EditProduct = document.getElementById("EditProduct");
	const orders_display = document.getElementById("orders_display");
	const Transaction_display = document.getElementById("Transactions_display");
	const Reviews_display = document.getElementById("Reviews_Display");

	/* first time loading page */
	EditProduct.style.display = "block";
	orders_display.style.display = "none";
	Transaction_display.style.display = "none"; // Initially hide
	Reviews_display.style.display = "none"; // Initially hide
	managePro_button.classList.add("active"); /*for button styles*/

	/* second on click some styling*/
	managePro_button.addEventListener("click", function () {
		EditProduct.style.display = "block";
		orders_display.style.display = "none";
		Transaction_display.style.display = "none";
		Reviews_display.style.display = "none";
		managePro_button.classList.add("active");
		Orders_button.classList.remove("active");
		transaction_button.classList.remove("active");
		review_button.classList.remove("active");
	});

	Orders_button.addEventListener("click", function () {
		EditProduct.style.display = "none";
		orders_display.style.display = "block";
		Transaction_display.style.display = "none";
		Reviews_display.style.display = "none";
		managePro_button.classList.remove("active");
		Orders_button.classList.add("active");
		transaction_button.classList.remove("active");
		review_button.classList.remove("active");
	});

	transaction_button.addEventListener("click", function () {
		EditProduct.style.display = "none";
		orders_display.style.display = "none";
		Transaction_display.style.display = "block";
		Reviews_display.style.display = "none";
		managePro_button.classList.remove("active");
		Orders_button.classList.remove("active");
		transaction_button.classList.add("active");
		review_button.classList.remove("active");
	});

	review_button.addEventListener("click", function () {
		EditProduct.style.display = "none";
		orders_display.style.display = "none";
		Transaction_display.style.display = "none";
		Reviews_display.style.display = "block";
		managePro_button.classList.remove("active");
		Orders_button.classList.remove("active");
		transaction_button.classList.remove("active");
		review_button.classList.add("active");
	});

	search_button.addEventListener("click", function () {});

	logout_button.addEventListener("click", function () {
		const confirmLogout = confirm("Are you sure you want to log out?");
		if (confirmLogout) {
			window.location.href = "/includes/cls.php";
		}
	});

	/*
======================================
== Responsive design code           ==
== Needs refactoring and organizing ==
======================================
*/

	// Add responsive table handling
	function handleResponsiveTables() {
		const tables = document.querySelectorAll("table");
		tables.forEach((table) => {
			const wrapper = document.createElement("div");
			wrapper.classList.add("table-responsive");
			table.parentNode.insertBefore(wrapper, table);
			wrapper.appendChild(table);
		});
	}

	// Handle mobile menu toggle
	function initMobileMenu() {
		const toolbar = document.querySelector(".toolbar");
		const menuToggle = document.createElement("button");

		toolbar.parentNode.insertBefore(menuToggle, toolbar);

		menuToggle.addEventListener("click", () => {
			toolbar.classList.toggle("show-mobile-menu");
		});

		// Handle responsive menu visibility
		function handleMobileMenu() {
			if (window.innerWidth <= 768) {
				menuToggle.style.display = "block";
				toolbar.classList.add("mobile");
			} else {
				menuToggle.style.display = "none";
				toolbar.classList.remove("mobile", "show-mobile-menu");
			}
		}

		window.addEventListener("resize", handleMobileMenu);
		handleMobileMenu();
	}

	// // Remove the nested DOMContentLoaded event and move tableConfigs and functions here
	// const tableConfigs = {
	// 	products: {
	// 		titleField: "Name",
	// 		idField: "ID", // This matches your table header
	// 		excludeFields: ["Actions"],
	// 		buttonConfig: {
	// 			edit: true,
	// 			remove: true,
	// 			custom: [] // Empty array for no custom buttons
	// 		}
	// 	},
	// 	orders: {
	// 		titleField: "Order ID",
	// 		idField: "Customer",
	// 		excludeFields: ["Actions"],
	// 		buttonConfig: {
	// 			edit: false,
	// 			remove: false,
	// 			// Add custom buttons if needed
	// 			custom: [
	// 				{
	// 					text: "Process",
	// 					class: "process-order-button",
	// 					icon: "📦",
	// 				},
	// 			],
	// 		},
	// 	},
	// 	transactions: {
	// 		titleField: "Transaction ID",
	// 		idField: "Date",
	// 		excludeFields: ["Actions"],
	// 		buttonConfig: {
	// 			edit: false,
	// 			remove: false,
	// 		},
	// 	},
	// 	reviews: {
	// 		titleField: "Product",
	// 		idField: "Customer",
	// 		excludeFields: ["Actions"],
	// 		buttonConfig: {
	// 			edit: false,
	// 			remove: true,
	// 		},
	// 	},
	// };

	// function initCardView() {
	// 	const tables = document.querySelectorAll("table[data-table-type]");

	// 	tables.forEach((table) => {
	// 		const tableType = table.getAttribute('data-table-type');
	// 		const config = tableConfigs[tableType];

	// 		if (!config) return;

	// 		// Create card container
	// 		const cardView = document.createElement("div");
	// 		cardView.className = "card-view";
	// 		cardView.setAttribute('data-table-type', tableType);

	// 		// Get table headers and data
	// 		const headers = Array.from(table.querySelectorAll("th")).map(th => {
	// 			const headerText = th.textContent.replace('↕', '').trim();
	// 			return headerText;
	// 		});

	// 		const rows = table.querySelectorAll("tbody tr");

	// 		if (rows.length === 0) {
	// 			const noDataCard = document.createElement("div");
	// 			noDataCard.className = "data-card";
	// 			noDataCard.innerHTML = `<div class="card-header">
	// 				<h3>No data available</h3>
	// 			</div>`;
	// 			cardView.appendChild(noDataCard);
	// 		} else {
	// 			rows.forEach((row) => {
	// 				const card = createCard(row, headers, config);
	// 				cardView.appendChild(card);
	// 			});
	// 		}

	// 		// Insert card view after table
	// 		table.parentNode.insertBefore(cardView, table.nextSibling);
	// 	});
	// }

	// Update createCard function to handle order-specific formatting
	// Add this near the tableConfigs object
	const tableConfigs = {
	    products: {
	        titleField: "Name",
	        idField: "ID", // This matches your table header
	        excludeFields: ["Actions"],
	        buttonConfig: {
	            edit: true,
	            remove: true,
	            custom: [], // Empty array for no custom buttons
	        },
	    },
	    orders: {
	        displayFields: [
	            { field: 'Order_ID', label: 'Order ID' },
	            { field: 'Order_Date', label: 'Order Date' },
	            { field: 'Product_ID', label: 'Product ID' },
	            { field: 'Product_Name', label: 'Product Name' },
	            { field: 'Quantity', label: 'Quantity' },
	            { field: 'Price_per_Unit', label: 'Price per Unit' },
	            { field: 'Total_Price', label: 'Total Price' }
	        ],
	        actions: [
	            { label: 'View', class: 'edit_product_button_style', action: 'viewOrder' },
	            { label: 'Process', class: 'product_confirm_edit_style', action: 'processOrder' }
	        ]
	    }
	};

	function initCardView() {
		const tables = document.querySelectorAll("table[data-table-type]");

		tables.forEach((table) => {
			const tableType = table.getAttribute("data-table-type");
			const config = tableConfigs[tableType];

			if (!config) return;

			// Create card container
			const cardView = document.createElement("div");
			cardView.className = "card-view";
			cardView.setAttribute("data-table-type", tableType);

			// Get table headers and data
			const headers = Array.from(table.querySelectorAll("th")).map((th) => {
				const headerText = th.textContent.replace("↕", "").trim();
				return headerText;
			});

			const rows = table.querySelectorAll("tbody tr");

			if (rows.length === 0) {
				const noDataCard = document.createElement("div");
				noDataCard.className = "data-card";
				noDataCard.innerHTML = `<div class="card-header">
					<h3>No data available</h3>
				</div>`;
				cardView.appendChild(noDataCard);
			} else {
				rows.forEach((row) => {
					const card = createCard(row, headers, config);
					cardView.appendChild(card);
				});
			}

			// Insert card view after table
			table.parentNode.insertBefore(cardView, table.nextSibling);
		});
	}

	// Update createCard function to properly handle buttons
	// Update createCard function to fix ID display and button handling
	// Update the createCard function's button section
	function createCard(row, headers, config) {
		const card = document.createElement("div");
		card.className = "data-card";

		const cells = row.querySelectorAll("td");
		const cellData = Array.from(cells).map((cell) => cell.textContent.trim());
		const productId = cellData[0]; // Get ID from first column

		// Add data-id attribute to the card
		card.setAttribute("data-id", productId);

		// Create card header
		const cardHeader = document.createElement("div");
		cardHeader.className = "card-header";
		cardHeader.innerHTML = `
	        <div>
	            <h3>${cellData[1] || "N/A"}</h3>
	            <div class="card-id">ID: ${productId}</div>
	        </div>
	        <span class="card-toggle">▼</span>
	    `;

		// Create card body
		const cardBody = document.createElement("div");
		cardBody.className = "card-body";

		// Add data rows (skip Name since it's in header)
		headers.forEach((header, index) => {
			if (!config.excludeFields.includes(header) && header !== "Name") {
				const cardRow = document.createElement("div");
				cardRow.className = "card-row";
				cardRow.innerHTML = `
	                <div class="card-label">${header}</div>
	                <div class="card-value" data-field="${header.toLowerCase()}">${
					cellData[index] || "N/A"
				}</div>
	            `;
				cardBody.appendChild(cardRow);
			}
		});

		// Add action buttons
		const cardActions = document.createElement("div");
		cardActions.className = "card-actions";

		// Regular buttons
		const buttonsTable = document.createElement("div");
		buttonsTable.className = "buttons_table";
		buttonsTable.id = `buttons_table_${productId}`; // Unique ID for each product
		buttonsTable.innerHTML = `
	        <button type="button" class="edit_product_button_style" onclick="product_edit_button('${productId}')">Edit</button>
	        <button type="button" class="remove_product_button_style" onclick="confirmationPopup('${productId}')">Remove</button>
	    `;

		// Edit buttons (initially hidden)
		const editButtons = document.createElement("div");
		editButtons.className = "buttons_table";
		editButtons.id = `product_edit_${productId}`; // Unique ID for each product
		editButtons.style.display = "none";
		editButtons.innerHTML = `
	        <button type="button" class="product_cancel_edit_style" onclick="product_cancel_edit('${productId}')">Cancel</button>
	        <button type="button" class="product_confirm_edit_style" onclick="saveProductChanges('${productId}')">Save</button>
	    `;

		cardActions.appendChild(buttonsTable);
		cardActions.appendChild(editButtons);
		cardBody.appendChild(cardActions);

		card.appendChild(cardHeader);
		card.appendChild(cardBody);

		// Add click handler for expanding/collapsing
		cardHeader.addEventListener("click", () => {
			cardBody.classList.toggle("active");
			cardHeader.querySelector(".card-toggle").classList.toggle("active");
		});

		return card;
	}

	// Update editProduct function to handle card view
	function editProduct(productId) {
		// Handle both table and card view
		const row = document.querySelector(`tr[data-id="${productId}"]`);
		const card = document.querySelector(
			`.card-view .data-card[data-id="${productId}"]`
		);

		if (row) {
			// Table view editing
			editingRow = productId;
			document.getElementById(`buttons_table_${productId}`).style.display =
				"none";
			document.getElementById(`edit_buttons_${productId}`).style.display =
				"flex";

			const editableCells = row.querySelectorAll(".editable-cell");
			editableCells.forEach((cell) => {
				const field = cell.getAttribute("data-field");
				const value = cell.textContent;
				cell.innerHTML = `<input type="text" name="${field}" value="${value}" />`;
				cell.classList.add("editable");
			});
		}

		if (card) {
			// Card view editing
			editingRow = productId;
			card.querySelector(`#buttons_table_${productId}`).style.display = "none";
			card.querySelector(`#edit_buttons_${productId}`).style.display = "flex";

			const editableValues = card.querySelectorAll(".card-value[data-field]");
			editableValues.forEach((value) => {
				const field = value.getAttribute("data-field");
				const currentValue = value.textContent;
				value.innerHTML = `<input type="text" name="${field}" value="${currentValue}" />`;
				value.classList.add("editable");
			});
		}
	}

	// Initialize responsive features
	handleResponsiveTables();
	initMobileMenu();

	// Initialize card view after data is loaded
	function initializeTableView() {
		// First clear any existing card views
		document.querySelectorAll(".card-view").forEach((view) => view.remove());
		// Then initialize new card view
		initCardView();
	}

	// Call initializeTableView after switching tabs
	managePro_button.addEventListener("click", function () {
		EditProduct.style.display = "block";
		orders_display.style.display = "none";
		Transaction_display.style.display = "none";
		Reviews_display.style.display = "none";
		managePro_button.classList.add("active");
		Orders_button.classList.remove("active");
		transaction_button.classList.remove("active");
		review_button.classList.remove("active");
		initializeTableView();
	});

	Orders_button.addEventListener("click", function () {
		EditProduct.style.display = "none";
		orders_display.style.display = "block";
		Transaction_display.style.display = "none";
		Reviews_display.style.display = "none";
		managePro_button.classList.remove("active");
		Orders_button.classList.add("active");
		transaction_button.classList.remove("active");
		review_button.classList.remove("active");
		initializeTableView();
	});

	transaction_button.addEventListener("click", function () {
		EditProduct.style.display = "none";
		orders_display.style.display = "none";
		Transaction_display.style.display = "block";
		Reviews_display.style.display = "none";
		managePro_button.classList.remove("active");
		Orders_button.classList.remove("active");
		transaction_button.classList.add("active");
		review_button.classList.remove("active");
		initializeTableView();
	});

	review_button.addEventListener("click", function () {
		EditProduct.style.display = "none";
		orders_display.style.display = "none";
		Transaction_display.style.display = "none";
		Reviews_display.style.display = "block";
		managePro_button.classList.remove("active");
		Orders_button.classList.remove("active");
		transaction_button.classList.remove("active");
		review_button.classList.add("active");
		initializeTableView();
	});

	// Initialize card view for orders
	initCardView();
	
	// Add click handler for Orders button
	document.getElementById('Orders_button').addEventListener('click', function() {
	    hideAllContent();
	    document.getElementById('orders_display').style.display = 'block';
	    loadOrders();
	});
});

// Responsive handling functions
function handleResponsiveTables() {
	const tables = document.querySelectorAll("table");
	tables.forEach((table) => {
		const wrapper = document.createElement("div");
		wrapper.classList.add("table-responsive");
		table.parentNode.insertBefore(wrapper, table);
		wrapper.appendChild(table);
	});
}

function initMobileMenu() {
	const toolbar = document.querySelector(".toolbar");
	const menuToggle = document.createElement("button");

	toolbar.parentNode.insertBefore(menuToggle, toolbar);

	menuToggle.addEventListener("click", () => {
		toolbar.classList.toggle("show-mobile-menu");
	});

	function handleMobileMenu() {
		if (window.innerWidth <= 1024) {
			menuToggle.style.display = "block";
			toolbar.classList.add("mobile");
		} else {
			menuToggle.style.display = "none";
			toolbar.classList.remove("mobile", "show-mobile-menu");
		}
	}

	window.addEventListener("resize", handleMobileMenu);
	handleMobileMenu();
}

// Existing utility functions
function closeaddProPopup() {
	document.getElementById("addProPopup_display").style.display = "none";
}

function confirmationPopup() {
	document.getElementById("confirmationPopup_display").style.display = "block";
}

function closeconfirmationPopup() {
	document.getElementById("confirmationPopup_display").style.display = "none";
}

// Global scope functions
window.product_edit_button = function () {
	document.getElementById("product_edit_display").style.display = "block";
	document.getElementById("buttons_table_display").style.display = "none";
};

window.product_cancel_edit = function () {
	document.getElementById("product_edit_display").style.display = "none";
	document.getElementById("buttons_table_display").style.display = "block";
};

document
	.getElementById("rows_per_page")
	.addEventListener("change", function () {
		rowsPerPage = parseInt(this.value);
		currentPage = 1;
		loadProducts();
	});

// Remove all previous declarations of these functions and add these at the bottom of your file:

// Global edit functions
window.product_edit_button = function (productId) {
	const card = document.querySelector(`.data-card[data-id="${productId}"]`);
	if (card) {
		const buttonsTable = card.querySelector(`#buttons_table_${productId}`);
		const editButtons = card.querySelector(`#product_edit_${productId}`);

		// Toggle button visibility
		buttonsTable.style.display = "none";
		editButtons.style.display = "flex";

		// Make fields editable
		const editableFields = card.querySelectorAll(".card-value[data-field]");
		editableFields.forEach((field) => {
			const currentValue = field.textContent.trim();
			field.innerHTML = `<input type="text" value="${currentValue}" class="editable-input" />`;
			field.classList.add("editable");
		});
	}
};


window.product_cancel_edit = function (productId) {
	const card = document.querySelector(`.data-card[data-id="${productId}"]`);
	if (card) {
		const buttonsTable = card.querySelector(`#buttons_table_${productId}`);
		const editButtons = card.querySelector(`#product_edit_${productId}`);
		const editableFields = card.querySelectorAll(".card-value.editable");

		// Restore original text content without inputs
		editableFields.forEach((field) => {
			const input = field.querySelector(".editable-input");
			if (input) {
				field.textContent = input.defaultValue; // Use original value
				field.classList.remove("editable");
			}
		});

		// Toggle buttons
		buttonsTable.style.display = "flex";
		editButtons.style.display = "none";
	}
};

window.saveProductChanges = function (productId) {
	const card = document.querySelector(`.data-card[data-id="${productId}"]`);
	if (card) {
		const editedValues = {};
		const editableFields = card.querySelectorAll(".card-value.editable");

		// Save the values and restore display
		editableFields.forEach((field) => {
			const input = field.querySelector(".editable-input");
			if (input) {
				field.textContent = input.value;
				field.classList.remove("editable");
			}
		});

		// Toggle buttons back
		const buttonsTable = card.querySelector(`#buttons_table_${productId}`);
		const editButtons = card.querySelector(`#product_edit_${productId}`);

		buttonsTable.style.display = "flex";
		editButtons.style.display = "none";
	}
};

function viewOrder(orderId) {
    // Implement view order details functionality
    console.log(`Viewing order ${orderId}`);
}

function processOrder(orderId) {
    // Show confirmation modal
    const modal = document.getElementById('confirmation-modal');
    modal.style.display = 'block';
    modal.setAttribute('data-order-id', orderId);
}

function confirmProcess() {
    const modal = document.getElementById('confirmation-modal');
    const orderId = modal.getAttribute('data-order-id');
    
    // Implement order processing logic here
    console.log(`Processing order ${orderId}`);
    
    closeConfirmationModal();
}

function closeConfirmationModal() {
    const modal = document.getElementById('confirmation-modal');
    modal.style.display = 'none';
    modal.removeAttribute('data-order-id');
}
