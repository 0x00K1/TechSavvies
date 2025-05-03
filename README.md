<div align="center">
  <img src="assets/images/LogoName.webp" alt="Project Logo" width="200">
</div>
<br>
TechSavvies is a clean, modern e-commerce website designed for tech enthusiasts. Built using HTML, CSS, JavaScript, PHP, and MySQL, it allows users to browse and purchase products by category, manage their profiles and addresses, and track their orders with ease. A dedicated root panel offers control management over the website.


## 🚀 Features

- **Customer-Facing**  
  - Browse products by category (t-shirts, laptops, accessories...)  
  - View product details, (color, size), add to cart  
  - Persistent cart via cookies; quick "Pay Now" checkout flow  
  - User profiles: view/edit personal info, manage multiple addresses  
  - Order confirmation, email notifications & tracking timeline  

- **Administrator (Root) Dashboard**  
  - Secure 2FA + password-protected access  
  - CRUD on products, categories, customers, orders, payments, reviews  
  - Real-time stats: popular products, transactions, user reviews  
  - Role management, CSRF protection & audit trail  

- **General**  
  - Fully responsive (mobile & desktop)  
  - Clean UI using brand palette: `#FFFFFF`, `#0117FF`, `#8D07CC`, `#D42D2D`, `#000000`  
  - Robust session & cookie security, input sanitization, CSRF tokens  
  - Modular PHP with prepared statements and transaction handling  


## 🗄️ Database ER Diagram

<div align="center">
  <img src="database/ER.svg" alt="Database ER Diagram" width="600">
</div>


## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript  
- **Backend**: PHP 8+, PDO (MySQL)  
- **Database**: MySQL  
- **Email**: PHPMailer with SMTP (2FA & contact form)  
- **Version Control**: Git, GitHub (feature branches → pull requests → main)  


## ⚙️ Getting Started

1. **Clone the repo**  
   ```bash
   git clone https://github.com/0x00K1/TechSavvies.git
   cd TechSavvies
   ```

2. **Install dependencies**
   - Ensure PHP, MySQL are installed
   - ```bash
     composer install
     ```
     (for PHPMailer)

3. **Configure environment**
   - Create → .env
   - Set DB credentials `(DB_HOST, DB_NAME, DB_USERNAME, DB_PASSWORD)`
   - Set SMTP credentials `(SMTP_HOST, SMTP_PORT, SMTP_USERNAME, SMTP_PASSWORD, MAIL_FROM_ADDRESS, MAIL_FROM_NAME)`
  
4. **Initialize database**
   ```sql
   -- run the provided `database/schema.sql` to create tables & seed data
   ```

5. **Serve the app**
   ```bash
   php -S localhost:8000 -t public
   ```

6. **Visit** http://localhost:8000


## 🔧 Folder Structure

```
/
├─ assets/
│  ├─ css/         # Stylesheets
│  ├─ images/      # Product & dev images
|  ├─ icons/       # UI and system icons
│  ├─ js/          # Frontend logic
|  └─ php/         # Layout & helper PHP components
├─ database/
│  ├─ schema.sql   # DB
│  └─ ER.svg       # ER diagram
├─ includes/       # Core backend logic
├─ root/           # Admin dashboard (Root)
├─ categories/     # Product catalog & checkout flow
├─ profile/        # User profile & addresses
├─ contact.php     # Contact form endpoint
├─ index.php       # Home & routing
└─ README.md       # You are here :)
```


## 📫 Contact

For questions or feedback, open an issue or reach out to the TechSavvies team via our About Us page.