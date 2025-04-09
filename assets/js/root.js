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
		menuToggle.classList.add("menu-toggle");
		menuToggle.innerHTML = "☰ Menu";
		menuToggle.style.display = "none";

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

	// Initialize responsive features
	handleResponsiveTables();
	initMobileMenu();

	// Initialize button handlers
	if (document.getElementById("edit_product_button")) {
		document.getElementById("edit_product_button").onclick =
			window.product_edit_button;
	}

	if (document.getElementById("product_cancel_edit")) {
		document.getElementById("product_cancel_edit").onclick =
			window.product_cancel_edit;
	}
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
	menuToggle.classList.add("menu-toggle");
	menuToggle.innerHTML = "☰ Menu";
	menuToggle.style.display = "none";

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

document.getElementById('rows_per_page').addEventListener('change', function() {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    loadProducts();
});
