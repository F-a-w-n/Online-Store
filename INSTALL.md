# Shamazon Installation Guide

## Overview
Shamazon is an advanced e-commerce bookstore built with PHP and MySQL. This guide will help you install and configure the application on your own server.

---

## Installation Steps

### 1. Download the Project
Clone the repository or download the ZIP file:
```bash
git clone https://github.com/yourusername/shamazon.git
```

Or download and extract the ZIP file to your web root.

### 2. Upload to Your Server
Upload the `shamazon` folder to your web root: `public_html/`

### 3. Create a MySQL Database
1. Log into your hosting control panel (ex. DirectAdmin).
2. Navigate to **phpMyAdmin**.
3. Create a new database (e.g., `shamazon_db`).

### 4. Import the Database Schema
1. Open **phpMyAdmin**.
2. Select your newly created database from the left sidebar.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select `setup/database_schema.sql` from the project.
5. Click **Go** to run the import.

> **Note:** If you don't have the SQL file, you can export it from an existing installation.

### 5. Configure the Database Connection
1. Navigate to `includes/config.php`.
2. Update the database credentials with your information:
   ```php
   <?php
   $host = 'localhost';
   $dbname = 'your_database_name';
   $username = 'your_database_username';
   $password = 'your_database_password';
   ?>
   ```
3. Save the file.


### 7. Access the Site
Open your browser and go to:
```
http://yourdomain.com/shamazon/
```
or for MyWeb:
```
https://yourusername.myweb.cs.uwindsor.ca/shamazon/
```

### 8. Login Credentials
**Default Admin Account:**
- **Email:** `admin@shamazon.com`
- **Password:** `password`

> **IMPORTANT:** Change this password immediately after first login!

---

## Customization

### Changing the Theme
1. Log in as an admin (using the default credentials above).
2. Go to **Admin Dashboard** → **Switch Themes**.
3. Choose from three available themes:
   - **Daylight:** Bright, clean, and modern.
   - **Midnight:** Dark mode for late-night browsing.
   - **Vintage:** Warm, sepia-toned classic feel.
4. Click **Apply Theme** to save the change.

### Adding Products
1. Log in as an admin.
2. Go to **Admin Dashboard** → **Manage Products**.
3. Click **Add New Product**.
4. Fill in the product details:
   - Title, Author, Description, Category, Price, Stock
   - Image filename (must match file in `assets/images/products/`)
   - Product options (up to 3: e.g., Hardcover, Paperback, E-book)
5. Click **Add Product**.

### Adding Images
1. Upload product images to: `assets/images/products/`
2. Filename must match the `image_url` in the database.
3. Supported formats: `.jpg`, `.jpeg`, `.png`, `.gif`, `.webp`

### Adding Videos/Audio
1. Upload media files to: `assets/media/`
2. Supported formats: `.mp4`, `.webm`, `.ogg`, `.mp3`, `.wav`
3. Add video players to pages using the HTML5 `<video>` tag.

---

## Project Structure
```
shamazon/
├── admin/                 # Admin panel
│   ├── auth_check.php     # Authentication check
│   ├── dashboard.php      # Admin dashboard
│   ├── products.php       # Manage products
│   ├── product_add.php    # Add new product
│   ├── product_edit.php   # Edit product
│   ├── orders.php         # Manage orders
│   ├── users.php          # Manage users
│   ├── templates.php      # Switch themes
│   └── monitor.php        # Site monitoring
├── assets/                # Static assets
│   ├── css/               # Stylesheets
│   │   ├── style.css      # Main CSS
│   │   ├── theme-day.css  # Daylight theme
│   │   ├── theme-night.css # Midnight theme
│   │   └── theme-sepia.css # Vintage theme
│   ├── js/                # JavaScript
│   │   └── main.js        # Main JS file
│   ├── images/            # Images
│   │   ├── products/      # Product covers (20+)
│   └── media/             # Videos/Audio (3+)
├── help/                  # Help Wiki (5+ pages)
│   ├── index.php          # Wiki homepage
│   ├── how_to_shop.php    # Shopping guide
│   ├── how_to_track.php   # Order tracking
│   ├── how_to_rate.php    # Rating guide
│   ├── account_help.php   # Account management
│   └── admin_docs.php     # Admin documentation
├── includes/              # PHP includes
│   ├── config.php         # Database config (IGNORE in Git)
│   ├── db.php             # Database connection
│   ├── functions.php      # Helper functions
│   ├── auth.php           # Authentication
│   ├── header.php         # Page header
│   └── footer.php         # Page footer
├── setup/                 # Setup files
│   └── database_schema.sql # Database export
├── .gitignore             # Git ignore file
├── INSTALL.md             # This file
├── README.md              # Project README
├── index.php              # Homepage
├── shop.php               # Product catalog
├── product.php            # Product details + ratings
├── cart.php               # Shopping cart
├── checkout.php           # Checkout
├── order-confirmation.php # Order confirmation
├── order-history.php      # Order history
├── track-order.php        # Track order
├── login.php              # User login
├── register.php           # User registration
├── logout.php             # User logout
├── profile.php            # User profile
├── about.php              # About page
├── contact.php            # Contact page
└── faq.php                # FAQ page
```