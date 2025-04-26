CREATE DATABASE IF NOT EXISTS techsavvies;
USE techsavvies;
-- Keep the Insert into Products and Insert into Categories change any thing else


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
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
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
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    picture VARCHAR(255) COMMENT 'File name for product image, stored in assets/images/products as [PNG]',
    description TEXT,
    color VARCHAR(50),
    size VARCHAR(50),
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

INSERT INTO roots (email, password, username, created_at) VALUES
('root1@example.com', '$2y$10$A9a3nM6xFXXG1T6sXlXo1OB5jFNE9xwNjD1PXvKtOlKnB/72Y1LJe', 'Root1', NOW()),
('root2@example.com', '$2y$10$P8Dme59A2YJwK3JhXyY2sO7QEMvN59kU2I1NWp5mIuT5zDL4mkhQ2', 'Root2', NOW()),
('root3@example.com', '$2y$10$Y7Lke9rXWjVR7t6qBMHZBeBwBLy0rK3F4/Vq50WkBqT2X3smv3m5S', 'Root3', NOW()),
('root4@example.com', '$2y$10$Z5Lk23bWQVR9t6qBNH4ZBsAdL2yT3XG4/Mq20VhBqT2X9ym5p5T6', 'Root4', NOW()),
('root5@example.com', '$2y$10$C8Dme12A2YJwK3JhXyY2sO3QEMvL89kU2I1NWp5mIuT5zDL4mkhQ4', 'Root5', NOW());

-- Insert into customers (Regular users)
INSERT INTO customers (email, username, created_at) VALUES
('customer1@example.com', 'savvyclient123456', NOW()),
('customer2@example.com', 'savvyclient654321', NOW()),
('customer3@example.com', 'savvyclient987654', NOW()),
('customer4@example.com', 'techieexplorer', NOW()),
('customer5@example.com', 'geekmaster999', NOW());


-- Insert into addresses (Linked to customers)
INSERT INTO addresses (customer_id, address_line1, city, state, postal_code, country, created_at) VALUES
(1, '506 Tech Street', 'Qassim', '01', '011001', 'KSA', NOW()),
(2, '456 Cyber Avenue', 'Alkhobar', 'SA', '90001', 'KSA', NOW()),
(3, '789 Code Lane', 'San Francisco', 'CA', '94105', 'USA', NOW()),
(4, '321 Silicon Valley', 'Riyadh', '02', '11564', 'KSA', NOW()),
(5, '258 Innovation Drive', 'Dubai', 'DU', '50010', 'UAE', NOW());

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
(1, 'Brand T-shirt White', '../assets/images/Products/T-shirts/Brand_T-shirt_White.png', 'White branded T-shirt made from 100% cotton. Lightweight and comfortable.', 'White', 'M', 49.99, 4.2, 100, 1),
(1, 'Brand T-shirt Black', '../assets/images/Products/T-shirts/Brand_T-shirt_Black.png', 'Black branded T-shirt made from 100% cotton. Lightweight and comfortable.', 'Black', 'M', 59.99, 4.2, 100, 1),
(1, 'CHATGPT T-shirt', '../assets/images/Products/T-shirts/CHATGPT_T-shirt.png', 'CHATGPT T-shirt made from 100% cotton. Lightweight and comfortable.', 'White', 'S', 30.00, 2.75, 500, 1),
(1, 'CTRL+PURR T-shirt Mauve', '../assets/images/Products/T-shirts/CTRL+PURR_T-shirt_Mauve.png', 'CTRL+PURR T-shirt made from 100% cotton. Lightweight and comfortable.', 'Mauve', 'L', 42.50, 4.0, 80, 1),
(1, 'CTRL+PURR T-shirt White', '../assets/images/Products/T-shirts/CTRL+PURR_T-shirt_White.png', 'CTRL+PURR T-shirt made from 100% cotton. Lightweight and comfortable.', 'White', 'M', 39.99, 3.9, 120, 1),
(1, 'I Are Programmer T-shirt', '../assets/images/Products/T-shirts/I_Are_Programmer_T-shirt.png', 'Funny programmer-themed T-shirt made from soft cotton.', 'Black', 'XL', 35.00, 4.5, 90, 1),
(1, 'I need br T-shirt', '../assets/images/Products/T-shirts/I_need_br_T-shirt.png', 'Humorous coding reference T-shirt made from premium cotton.', 'White', 'XXL', 37.49, 4.3, 70, 1),
(1, 'Just put a ticket in T-shirt', '../assets/images/Products/T-shirts/Just put a ticket in T-shirt Dark Gray.png', 'Support humor T-shirt made from 100% cotton.', 'Dark Gray', 'M', 40.00, 3.8, 60, 1),
(1, 'Just put a ticket in T-shirt', '../assets/images/Products/T-shirts/Just put a ticket in T-shirt Light Gray.png', 'Support humor T-shirt made from 100% cotton.', 'Light Gray', 'L', 40.00, 4.1, 110, 1),
(1, 'Just put a ticket in T-shirt', '../assets/images/Products/T-shirts/Just put a ticket in T-shirt White.png', 'Support humor T-shirt made from 100% cotton.', 'White', 'S', 40.00, 4.0, 95, 1),
(1, 'Tech Wizard T-shirt', '../assets/images/Products/T-shirts/Tech Wizard T-shirt.png', 'Tech Wizard themed T-shirt made from soft, breathable cotton.', 'Black', 'XL', 44.95, 4.6, 100, 1),
(1, 'Weapon T-shirt', '../assets/images/Products/T-shirts/Weapon T-shirt.png', 'Cool tech-themed T-shirt labeled “Weapon” – for coders.', 'Black', 'XXL', 41.00, 3.7, 85, 1);

INSERT INTO products (category_id, product_name, picture, description, color,  price, rating, stock, created_by) VALUES
(2, 'Brand Backpack Black', '../assets/images/Products/Backpacks/Brand_Backpack_Black.png', 'Black branded Backpack made from 100% cotton. Lightweight and comfortable.', 'Black',  45.29, 4.75, 250, 1),
(2, 'Brand Backpack White', '../assets/images/Products/Backpacks/Brand Backpack White.png', 'White branded Backpack made from 100% cotton. Lightweight and comfortable.', 'White',  45.29, 4.75, 250, 1),
(2, 'Delrin Led Backpack', '../assets/images/Products/Backpacks/Delrin Led Backpack.png', 'High-tech LED display backpack with customizable messages.', 'Black',  89.99, 4.60, 120, 1),
(2, 'Divoom Pixoo Backpack', '../assets/images/Products/Backpacks/Divoom Pixoo Pixel Art Laptop Backpack.png', 'Pixel art backpack with smart LED display. Eye-catching.', 'Dark Gray', 99.49, 4.80, 100, 1),
(2, 'Hacker Man Backpack', '../assets/images/Products/Backpacks/Hacker Man Backpack.png', 'Stylish backpack inspired by hacker culture. Have a Laptop compartment.', 'Dark blue', 15.00, 4.50, 75, 1),
(2, 'Led Backpack', '../assets/images/Products/Backpacks/Led Backpack.png', 'LED Backpack with smart lighting system. Perfect for night travel and events.', 'Black',  82.30, 4.65, 90, 1),
(2, 'XDDesign Bobby Tech', '../assets/images/Products/Backpacks/XDDesign Bobby Tech.png', 'Anti-theft backpack with USB charging port and water-repellent fabric.', 'Dark Gray',  110.00, 4.90, 60, 1);
select * from products;


INSERT INTO products (category_id, product_name, picture, description,  price, rating, stock, created_by) VALUES
(3, 'AI Engineering', '../assets/images/Products/Books/AI Engineering.png', 'Black branded Backpack made from 100% cotton. Lightweight and comfortable.',  30.00, 5.00, 50, 1),
(3, 'Algorithms to Live By', '../assets/images/Products/Books/Algorithms to Live By.png', 'A guide to understanding algorithms in everyday life.',  13.00, 4.50, 60, 1),
(3, 'Clean Code', '../assets/images/Products/Books/Clean Code.png', 'A book that offers advice on writing clean, maintainable code.',  49.99, 4.80, 40, 1),
(3, 'Fluent Python', '../assets/images/Products/Books/Fluent Python.png', 'A practical guide to Python programming and mastering the language.',  39.99, 4.70, 45, 1),
(3, 'Grokking Algorithms', '../assets/images/Products/Books/Grokking Algorithms.png', 'An illustrated guide to understanding algorithms with examples.',  29.99, 4.60, 55, 1),
(3, 'Life 3.0', '../assets/images/Products/Books/Life 3.0.png', 'A book about artificial intelligence and its impact on the future.', 29.99, 4.40, 50, 1),
(3, 'Microserfs', '../assets/images/Products/Books/Microserfs.png', 'A novel about tech geeks trying to make a life at Microsoft.', 24.99, 4.30, 30, 1),
(3, 'Operating Systems', '../assets/images/Products/Books/Operating Systems.png', 'An in-depth exploration of the concepts and design of modern operating systems.',  49.99, 4.75, 25, 1),
(3, 'Why Machines Learn', '../assets/images/Products/Books/Why Machines Learn.png', 'A book explaining the science behind machine learning in simple terms.',  29.99, 4.60, 65, 1);

INSERT INTO products (category_id, product_name, picture, description, price, rating, stock, created_by) VALUES
(4, 'TS1 Laptop Silver', '../assets/images/Products/Laptops/laptop1_logo.png', 'Silver branded Laptop with Intel i7-14700K.',  4599.99, 5.00, 12, 1),
(4, 'TS1 Laptop White', '../assets/images/Products/Laptops/laptop_case_logo.png', 'White branded Laptop with Intel i7-14700K.',  4599.99, 3.8, 11, 1),
(4, 'Apple MacBook Pro 16-Inch', '../assets/images/Products/Laptops/Apple MacBook Pro 16-Inch.png', 'Apple MacBook Pro 16-Inch with M2 Max chip, delivering exceptional performance.', 3499.99, 4.9, 15, 1),
(4, 'Lenovo ThinkPad P16 Gen 2', '../assets/images/Products/Laptops/Lenovo ThinkPad P16 Gen 2.png', 'Lenovo ThinkPad P16 Gen 2, a powerful workstation for professionals.', 2899.99, 4.8, 10, 1),
(4, 'Lenovo Legion Pro 7i Gen 9', '../assets/images/Products/Laptops/Lenovo Legion Pro 7i Gen 9 16.png', 'Lenovo Legion Pro 7i Gen 9 16, a gaming laptop with cutting-edge performance.', 2499.99, 4.7, 20, 1),
(4, 'HP ZBook Fury 16 G11', '../assets/images/Products/Laptops/HP ZBook Fury 16 G11.png', 'HP ZBook Fury 16 G11, a high-performance laptop for creative professionals.', 3199.99, 4.8, 12, 1),
(4, 'HP EliteBook 1040 G11', '../assets/images/Products/Laptops/HP EliteBook 1040 G11.png', 'HP EliteBook 1040 G11, a sleek and powerful business laptop.', 1999.99, 4.6, 18, 1),
(4, 'Asus ROG Zephyrus G14', '../assets/images/Products/Laptops/Asus ROG Zephyrus G14.png', 'Asus ROG Zephyrus G14, a compact gaming laptop with impressive performance.', 1799.99, 4.9, 25, 1),
(4, 'Black USB', '../assets/images/Products/Laptops/Black_usb_logo.png', '128GB USB 3.0',  32.99, 4.20, 36, 1),
(4, 'Laptop Case', '../assets/images/Products/Laptops/laptop_bag.png', 'Laptop Case Bag For storing Laptops',  90.99, 4.57, 6, 1),
(4, 'External Hard Desk', '../assets/images/Products/Laptops/external_storage_logo.png', 'Increase your device capacity with this 1TB external harD Desk.',  49.99, 3.8, 11, 1);


INSERT INTO products (category_id, product_name, picture, description, price, rating, stock, created_by) VALUES
(5, 'debugging Sticker', '../assets/images/Products/Stickers/debugging Sticker.png', 'Debugging Sticker. Shows Debugging meaning.', 5.00, 4.00, 50, 1),
(5, 'First Rule Sticker', '../assets/images/Products/Stickers/First Rule Sticker.png', 'Sticker that represents the first rule of debugging: "It works on my machine."', 5.00, 4.20, 60, 1),
(5, 'Full Snack Sticker', '../assets/images/Products/Stickers/Full Snack Sticker.png', 'A sticker that features the phrase "Full Snack" for the true snack enthusiasts.', 5.00, 4.10, 45, 1),
(5, 'git commit Sticker', '../assets/images/Products/Stickers/git commit Sticker.png', 'Sticker for developers, featuring the phrase "git commit -m" for all your coding commits.', 5.00, 4.50, 55, 1),
(5, 'google dino Sticker', '../assets/images/Products/Stickers/google dino Sticker.png', 'Sticker of the Google Chrome Dino game when offline, for a fun throwback.', 5.00, 4.30, 50, 1),
(5, 'Im not a robot Sticker', '../assets/images/Products/Stickers/Im not a robot Sticker.png', 'Sticker referencing the famous CAPTCHA phrase "I am not a robot."', 5.00, 4.00, 40, 1),
(5, 'import pandas Sticker', '../assets/images/Products/Stickers/import pandas Sticker.png', 'Sticker for data scientists with the classic "import pandas" statement.', 5.00, 4.60, 65, 1),
(5, 'Line 32 Sticker', '../assets/images/Products/Stickers/Line 32 Sticker.png', 'Roses are Red, violets are blue, Error in line 32.', 5.00, 4.40, 60, 1),
(5, 'No cloud Sticker', '../assets/images/Products/Stickers/No cloud Sticker.png', 'Sticker that says "No cloud" for those who prefer to keep things local.', 5.00, 4.10, 50, 1),
(5, 'Open Sourcerer Sticker', '../assets/images/Products/Stickers/Open Sourcerer Sticker.png', 'Sticker for the open-source enthusiasts featuring the term "Open Sourcerer."', 5.00, 4.70, 55, 1),
(5, 'Sudo rm -rf Sticker', '../assets/images/Products/Stickers/Sudo rm -rf Sticker.png', 'Sticker for sysadmins with the dangerous "sudo rm -rf" command.', 5.00, 4.80, 50, 1);

INSERT INTO products (category_id, product_name, picture, description, price, rating, stock, created_by) VALUES
(6, 'TS CPU', '../assets/images/Products/Hardware Tools/cpu_logo.png', 'A processor with 8-cores and 16 threads(3.5GB-4.2GB).', 799.99, 5.00, 25, 1),
(6, 'Arduino NANO', '../assets/images/Products/Hardware Tools/Arduino NANO.png', 'Compact and versatile microcontroller board for small projects.', 19.99, 4.8, 50, 1),
(6, 'Arduino UNO R3', '../assets/images/Products/Hardware Tools/Arduino UNO R3.png', 'Popular microcontroller board for beginners and advanced users.', 24.99, 4.9, 60, 1),
(6, 'Breadboard', '../assets/images/Products/Hardware Tools/Breadboard.png', 'Solderless breadboard for prototyping electronic circuits.', 9.99, 4.7, 100, 1),
(6, 'ESP32', '../assets/images/Products/Hardware Tools/ESP32.png', 'Wi-Fi and Bluetooth-enabled microcontroller for IoT projects.', 12.99, 4.9, 75, 1),
(6, 'ESP8266', '../assets/images/Products/Hardware Tools/ESP8266.png', 'Low-cost Wi-Fi microcontroller for IoT applications.', 8.99, 4.8, 80, 1),
(6, 'HC-05 Bluetooth', '../assets/images/Products/Hardware Tools/HC-05 Bluetooth.png', 'Bluetooth module for wireless communication in embedded systems.', 14.99, 4.7, 70, 1),
(6, 'Jump Wire', '../assets/images/Products/Hardware Tools/Jump Wire.png', 'Set of jumper wires for connecting components on a breadboard.', 5.99, 4.6, 150, 1),
(6, 'Development Board', '../assets/images/Products/Hardware Tools/Microcontroller Development Board.png', 'Versatile development board for prototyping and testing.', 29.99, 4.8, 40, 1),
(6, 'Particle Photon', '../assets/images/Products/Hardware Tools/Particle Photon.png', 'IoT development board with Wi-Fi connectivity.', 19.99, 4.9, 30, 1),
(6, 'Raspberry Pi Pico', '../assets/images/Products/Hardware Tools/Raspberry Pi Pico.png', 'Compact and affordable microcontroller board from Raspberry Pi.', 6.99, 4.9, 90, 1),
(6, 'RFID Reader', '../assets/images/Products/Hardware Tools/RFID Reader.png', 'RFID reader module for contactless identification and authentication.', 15.99, 4.8, 50, 1),
(6, 'STM32 Blue Pill', '../assets/images/Products/Hardware Tools/STM32 Blue Pill.png', 'Affordable ARM Cortex-M3 microcontroller board.', 12.99, 4.7, 60, 1),
(6, 'Teensy', '../assets/images/Products/Hardware Tools/Teensy.png', 'Powerful and compact microcontroller board for advanced projects.', 23.99, 4.9, 40, 1),
(6, 'Arduino MEGA', '../assets/images/Products/Hardware Tools/Arduino MEGA.png', 'High-performance microcontroller board with extended I/O capabilities.', 34.99, 4.8, 35, 1),
(6, 'Table Light', '../assets/images/Products/Hardware Tools/Table_Light.png', 'LED work light for clear visibility during device repairs.', 89.99, 3.32, 7, 1),
(6, 'Arctic MX-6', '../assets/images/Products/Hardware Tools/Thermal_Paste.png', 'High-performance thermal paste for efficient heat transfer in CPUs and GPUs.', 35.99, 5.00, 113, 1),
(6, 'TS CPU', '../assets/images/Products/Hardware Tools/Electric_Gloves.png', ' Insulated gloves for protection against electric shocks.' ,119.99, 4.70, 20, 1),
(6, 'Motherboard', '../assets/images/Products/Hardware Tools/motherBoard.png', 'High-performance motherboard for reliable computing power.', 599.99, 5.00, 12, 1);

INSERT INTO products (category_id, product_name, picture, description, price, rating, stock, created_by) VALUES
(7, 'Mat Lab', '../assets/images/Products/Software Tools/Matlab.png', ' Advanced computing software for data analysis, algorithms, and simulations.', 39.99, 5.00, 25, 1),
(7, 'Office 365', '../assets/images/Products/Software Tools/microsoft_old.png', 'Essential productivity suite with Word, Excel, PowerPoint, and cloud access.', 59.99, 5.00, 2, 1),
(7, 'Parallels Desktop', '../assets/images/Products/Software Tools/Parallels_desktop.png', 'Run Windows on Mac effortlessly with seamless virtualization.', 89.99, 5.00, 5, 1),
(7, 'AutoCad', '../assets/images/Products/Software Tools/AutoCad.png', 'Professional 2D/3D design and drafting tool for engineers and architects.', 1599.99, 5.00, 19, 1),
(7, 'JetBrains', '../assets/images/Products/Software Tools/JetBrains.png','Powerful IDE subscriptions for smarter coding in multiple languages.', 199.99, 5.00, 1, 1),
(7, 'Windows 11', '../assets/images/Products/Software Tools/windows_11.png','Latest OS with enhanced productivity, security, and seamless updates.', 89.99, 5.00, 53, 1),
(7, 'Cursor', '../assets/images/Products/Software Tools/cursor.png', 'A powerful code editor with AI-assisted features for developers.', 0.00, 4.8, 100, 1),
(7, 'Dify', '../assets/images/Products/Software Tools/Dify.png', 'A no-code platform for building AI-powered applications.', 49.99, 4.7, 50, 1),
(7, 'Evernote', '../assets/images/Products/Software Tools/evernote.png', 'A note-taking and task management application for productivity.', 7.99, 4.6, 200, 1),
(7, 'n8n', '../assets/images/Products/Software Tools/n8n.png', 'A workflow automation tool for connecting apps and services.', 0.00, 4.9, 150, 1),
(7, 'Notion', '../assets/images/Products/Software Tools/Notion.png', 'An all-in-one workspace for notes, tasks, and collaboration.', 10.00, 4.8, 120, 1),
(7, 'Trello', '../assets/images/Products/Software Tools/trello.png', 'A visual collaboration tool for organizing tasks and projects.', 5.00, 4.7, 180, 1),
(7, 'Workato', '../assets/images/Products/Software Tools/workato.png', 'An enterprise automation platform for integrating apps and workflows.', 99.99, 4.9, 30, 1),
(7, 'Zapier', '../assets/images/Products/Software Tools/Zapier.png', 'An automation tool for connecting apps and automating workflows.', 19.99, 4.8, 70, 1);

INSERT INTO products (category_id, product_name, picture, description, color, price, rating, stock, created_by) VALUES
(8, 'Brand Mug Black', '../assets/images/Products/Mugs/Brand Mug Black.png', 'Black branded Mug.', 'Black', 35.00, 4.92, 150, 1),
(8, 'Brand Mug White', '../assets/images/Products/Mugs/Brand Mug White.png', 'White branded Mug.', 'White', 35.00, 4.85, 140, 1),
(8, 'Brand Tumber Black', '../assets/images/Products/Mugs/Brand Tumber Black.png', 'Black branded Tumbler for your beverages.', 'Black', 30.00, 4.80, 120, 1),
(8, 'Brand Tumber White', '../assets/images/Products/Mugs/Brand Tumber White.png', 'White branded Tumbler for your beverages.', 'White', 30.00, 4.75, 110, 1),
(8, 'Documentation Enough Mug', '../assets/images/Products/Mugs/Documentation Enough Mug.png','"Documentation is Enough." Mug', 'White', 25.00, 4.60, 95, 1),
(8, 'Go Away Im coding Mug', '../assets/images/Products/Mugs/Go Away Im coding Mug.png', 'Mug with the phrase "Go Away, I’m coding."', 'White', 20.00, 4.80, 100, 1),
(8, 'HTML Coffee Mug', '../assets/images/Products/Mugs/HTML Coffee Mug.png', 'Mug with HTML tags.', 'White', 22.00, 4.70, 90, 1),
(8, 'I HATE CODING Mug', '../assets/images/Products/Mugs/I HATE CODING Mug.png', '"I HATE CODING" Mug.', 'White', 18.00, 4.50, 80, 1),
(8, 'I R Programmer Tumbler', '../assets/images/Products/Mugs/I R Programmer Tumbler.png', '"I R Programmer." Tumbler.', 'Black', 25.00, 4.85, 120, 1),
(8, 'Jake\'s Code Mug', '../assets/images/Products/Mugs/Jake\'s Code Mug.png', 'Jake\'s Code did that Mug.', 'Black', 28.00, 4.60, 70, 1),
(8, 'Linux Mug', '../assets/images/Products/Mugs/Linux Mug.png', 'Linux is user friendly mug.', 'White', 30.00, 4.90, 150, 1),
(8, 'Refill Mug', '../assets/images/Products/Mugs/Refill Mug.png', 'if(coffe.empty){coffe.refill()} Mug.', 'White', 20.00, 4.65, 100, 1);

INSERT INTO products (category_id, product_name, picture, description, color, price, rating, stock, created_by) VALUES
(9, 'Brand Phone Case Black', '../assets/images/Products/Phone Cases/Brand Phone Case Black.png', 'Black branded Phone Case.', 'Black', 35.00, 4.92, 150, 1),
(9, 'Brand Phone Case White', '../assets/images/Products/Phone Cases/Brand Phone Case White.png', 'White branded Phone Case.', 'White', 35.00, 4.72, 150, 1),
(9, 'CSS Phone Case', '../assets/images/Products/Phone Cases/CSS Phone Case.png', 'Phone Case featuring a CSS-related graphic.', 'Yellow', 30.00, 4.50, 130, 1),
(9, 'JS Phone Case', '../assets/images/Products/Phone Cases/JS Phone Case.png', 'Phone Case with JavaScript-related design.', 'Yellow', 30.00, 4.60, 120, 1),
(9, 'Love Languages Phone Case', '../assets/images/Products/Phone Cases/Love Languages Phone Case.png', 'Phone Case featuring the "Love Languages".', 'White', 28.00, 4.40, 100, 1),
(9, 'O(n)2 Phone Case', '../assets/images/Products/Phone Cases/O(n)2 Phone Case.png', 'Phone Case featuring the algorithm complexity "O(n)²".', 'Black', 32.00, 4.75, 110, 1),
(9, 'React Phone Case', '../assets/images/Products/Phone Cases/React Phone Case.png', 'Phone Case featuring the React framework logo.', 'Black', 32.00, 4.85, 140, 1),
(9, 'Robotic Coding Phone Case', '../assets/images/Products/Phone Cases/Robotic Coding Phone Case.png', 'Robotic Coding Phone Case.', 'Gray', 35.00, 4.80, 150, 1),
(9, 'Monkey Phone Case', '../assets/images/Products/Phone Cases/Monkey Phone Case.png', 'Phone Case featuring a fun monkey programming.', 'Black', 25.00, 4.60, 120, 1),
(9, 'Node JS Phone Case', '../assets/images/Products/Phone Cases/Node JS Phone Case.png', 'Phone Case featuring the Node.js logo.', 'Black', 30.00, 4.70, 130, 1);

INSERT INTO products (category_id, product_name, picture, description, price, rating, stock, created_by) VALUES
(10, 'Rubic’s Cube', '../assets/images/Products/Games/Rubik_cube.png', 'Iconic 3x3 puzzle cube to challenge your problem-solving skills.', 55.00, 4.1, 15, 1),
(10, 'Circuit Maze', '../assets/images/Products/Games/Circuit Maze.png', 'Electric current logic game to enhance problem-solving skills.', 29.99, 4.8, 20, 1),
(10, 'Cluebox', '../assets/images/Products/Games/Cluebox.png', 'Interactive puzzle box with riddles and challenges to solve.', 39.99, 4.7, 15, 1),
(10, 'Codenames', '../assets/images/Products/Games/Codenames.png', 'A fun word association game for teams to uncover secret agents.', 19.99, 4.6, 25, 1),
(10, 'Decrypto', '../assets/images/Products/Games/Decrypto.png', 'A team-based game of code-breaking and communication.', 24.99, 4.5, 18, 1),
(10, 'Enigma Viking Box', '../assets/images/Products/Games/Enigma  Viking Box.png', 'A Viking-themed puzzle box with intricate riddles to solve.', 49.99, 4.9, 10, 1),
(10, 'Hack Forward', '../assets/images/Products/Games/Hack Forward.png', 'A hacking-themed escape room game for tech enthusiasts.', 34.99, 4.8, 12, 1),
(10, 'LittleBits', '../assets/images/Products/Games/LittleBits.png', 'Electronic building blocks for creating innovative projects.', 89.99, 4.9, 30, 1),
(10, 'Makey Makey', '../assets/images/Products/Games/Makey Makey.png', 'An invention kit for turning everyday objects into touchpads.', 49.99, 4.7, 20, 1),
(10, 'The Cypher Files', '../assets/images/Products/Games/The Cypher Files.png', 'A spy-themed puzzle game with cryptographic challenges.', 29.99, 4.6, 15, 1),
(10, 'The Initiative', '../assets/images/Products/Games/The Initiative.png', 'A cooperative board game with puzzles and a compelling storyline.', 39.99, 4.8, 18, 1),
(10, 'Bloxels', '../assets/images/Products/Games/bloxels.png', 'A hands-on platform for creating video games with physical blocks.', 59.99, 4.9, 25, 1),
(10, 'Jenga!', '../assets/images/Products/Games/Jenga_game.png', 'Nerve-wracking stacking game where steady hands win the tower battle.', 64.00, 4.00, 10, 1),
(10, 'Desktop Ball', '../assets/images/Products/Games/DesktopBall_Game.png', 'Smooth, palm-sized ball for fidgeting and stress relief at work.', 45.99, 5.00, 13, 1),
(10, 'Classic Carem', '../assets/images/Products/Games/Carem.png', 'Classic wooden board game for fast-paced striker action and family fun.', 129.00, 5.00, 4, 1),
(10, '3 Mini X/O Game', '../assets/images/Products/Games/XO_game.png', 'Compact 3D tic-tac-toe set for quick strategy games on the go.', 67.99, 4.3, 15, 1);




-- Insert into orders (Only 3 orders)
INSERT INTO orders (customer_id, order_date, status, total_amount) VALUES
(1, NOW(), 'pending', 49.99),
(2, NOW(), 'paid', 39.99),
(3, NOW(), 'shipped', 1499.99),
(4, NOW(), 'processing', 199.99),
(5, NOW(), 'delivered', 79.99);

-- Insert into order_items (Only 3 rows)
INSERT INTO order_items (order_id, product_id, quantity, price_per_unit) VALUES
(1, 1, 1, 49.99),
(2, 2, 1, 39.99),
(3, 3, 1, 1499.99),
(4, 4, 1, 199.99),
(5, 5, 1, 79.99);

-- Insert into payments (Only 3 payments)
INSERT INTO payments (order_id, customer_id, payment_method, payment_status, transaction_id, amount, created_at) VALUES
(1, 1, 'credit_card', 'pending', 'TXN123456', 49.99, NOW()),
(2, 2, 'paypal', 'completed', 'TXN654321', 39.99, NOW()),
(3, 3, 'cash_on_delivery', 'pending', NULL, 1499.99, NOW()),
(4, 4, 'bank_transfer', 'completed', 'TXN987654', 199.99, NOW()),
(5, 5, 'apple_pay', 'completed', 'TXN777777', 79.99, NOW());

-- Insert into reviews (Only 3 reviews, updated for Black Hat Python)
INSERT INTO reviews (customer_id, product_id, rating, review_text, created_at) VALUES
(1, 1, 5, 'Great hardware kit :)', NOW()),
(2, 2, 5, 'Black Hat Python is a must-read for hackers!', NOW()),
(3, 3, 5, 'Amazing laptop, super fast and great for tech people!', NOW()),
(4, 4, 4, 'Windows 11 Pro License worked perfectly.', NOW()),
(5, 5, 5, 'This mechanical keyboard is awesome for gaming!', NOW());

