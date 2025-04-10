CREATE DATABASE techsavvies;
USE techsavvies;

CREATE TABLE roots (
    root_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(180),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL UNIQUE,
    username VARCHAR(180) COMMENT 'Default savvyclient[-----]',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY, 
    category_name VARCHAR(255) NOT NULL,
    description TEXT,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES roots(root_id) ON DELETE SET NULL
)AUTO_INCREMENT = 1; -- Auto increment Before starts from 10 so made default 1

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    picture VARCHAR(255) COMMENT 'File name for product image, stored in assets/images/products as [PNG]',
    description TEXT,
    color VARCHAR(50),
    size VARCHAR(50),
    material VARCHAR(50),           -- Added for categories like T-shirts, Mugs, Phone Cases, etc.
    release_year INT,               -- Added for Books
    brand VARCHAR(50),              -- Added for Laptops, Hardware Tools, etc.
    hard_drive_capacity VARCHAR(50),-- Added for Laptops
    type VARCHAR(50),               -- Added for Hardware Tools (e.g., Microcontroller, Sensor, etc.)
    platform VARCHAR(50),           -- Added for Software Tools, Games
    license_type VARCHAR(50),       -- Added for Software Tools
    price DECIMAL(10,2) NOT NULL,
    rating DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES roots(root_id) ON DELETE SET NULL
); 

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'paid', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price_per_unit DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE COMMENT 'Each payment is associated with one order',
    customer_id INT NOT NULL,
    payment_method ENUM('credit_card', 'paypal', 'cash_on_delivery') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(180) UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5) COMMENT 'Rating from 1 to 5',
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

insert into roots (email,password,username) values
('abdulrahmanqht@gmail.com','123','Abdulrahman');


INSERT INTO categories (category_name, description, created_by) VALUES
('T-shirts', 'Cool tech-inspired apparel', 1),
('Backpacks', 'Stylish and functional bags.', 1),
('Books', 'Learn and master tech skills.', 1),
('Laptops', 'High performance laptops for every need.', 1),
('Stickers', 'Show off your passion with our stickers.', 1),
('Hardware Tools', 'Essential tools for every tech enthusiast.', 1),
('Software Tools', 'Tools to boost your productivity.', 1),
('Mugs', 'Perfect for your daily caffeine fix.', 1),
('Phone Cases', 'Protect your device with style.', 1),
('Games', 'Explore the latest tech games.', 1);



select * from roots;


select * from categories;
SHOW TABLE STATUS LIKE 'categories';
SHOW CREATE TABLE categories;


-- Insert into 'products' table
INSERT INTO products (category_id, product_name, picture, description, color, size, price, rating, stock, created_by) VALUES
(1, 'Brand T-shirt White', '../assets/images/Products/T-shirts/Brand_T-shirt_White.png', 'White branded T-shirt made from 100% cotton. Lightweight and comfortable.', 'White', 'M', 49.99, 4.2, 100, 1);
INSERT INTO products (category_id, product_name, picture, description, color, size, price, rating, stock, created_by) VALUES
(1, 'Brand T-shirt Black', '../assets/images/Products/T-shirts/Brand_T-shirt_Black.png', 'Black branded T-shirt made from 100% cotton. Lightweight and comfortable.', 'Black', 'M', 59.99, 4.2, 100, 1);
INSERT INTO products (category_id, product_name, picture, description, color, size, price, rating, stock, created_by) VALUES
(1, 'CHATGPT T-shirt', '../assets/images/Products/T-shirts/CHATGPT_T-shirt.png', 'CHATGPT T-shirt made from 100% cotton. Lightweight and comfortable.', 'White', 'S', 30.00, 2.75, 500, 1);




select * from products;
