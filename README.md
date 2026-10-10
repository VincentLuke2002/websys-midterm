# Vertex POS — CodeIgniter 4 Midterm Project

Vertex POS is a complete, database-backed point-of-sale system built with CodeIgniter 4. It combines protected staff access, full management pages, safe image uploads, inventory tracking, and a transactional sales workflow in a responsive interface.

## Features

- Secure staff login and logout using sessions and hashed passwords
- Authentication filter protecting every management route
- CSRF protection on all forms
- Dashboard with sales, product, customer, and low-stock summaries
- Product CRUD with price, stock, and prepared product images
- Customer CRUD with validated contact information
- Staff CRUD with unique usernames, hashed passwords, and prepared avatars
- Record Sale workflow with an optional customer
- Atomic stock deduction inside a database transaction
- Clear rejection when requested quantity exceeds available stock
- Sales history showing product, customer, staff, quantity, total, and date
- Responsive sidebar and mobile navigation

## Requirements

- PHP 8.2 or newer with `fileinfo`, `gd`, `intl`, `mbstring`, and `mysqli`
- Composer
- MySQL or MariaDB

## Setup with migrations

1. Clone the repository and open the folder in VS Code.
2. Install packages:

   ```powershell
   composer install
   ```

3. Copy `env` to `.env`, then update these values:

   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8082/'
   app.indexPage = ''

   database.default.hostname = 127.0.0.1
   database.default.database = pos_midterm
   database.default.username = root
   database.default.password = ''
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

4. Create an empty database named `pos_midterm`.
5. Build and seed the tables:

   ```powershell
   php spark migrate
   php spark db:seed PosSeeder
   ```

6. Start the application:

   ```powershell
   php spark serve --port 8082
   ```

7. Open `http://localhost:8082`.

## Setup with the database export

Instead of migrations, import `database/pos_midterm.sql`. It includes the complete relational schema and sample data.

```powershell
Get-Content database\pos_midterm.sql | C:\xampp\mysql\bin\mysql.exe -u root
```

## Demo login

- Username: `admin01`
- Password: `Password123!`

All seeded staff accounts use the same demonstration password. Only password hashes are stored in the database.

## Main routes

| Route | Purpose |
| --- | --- |
| `/` | Public landing page |
| `/about` | Project overview |
| `/login` | Staff authentication |
| `/dashboard` | Protected store summary |
| `/products` | Product management |
| `/customers` | Customer management |
| `/users` | Staff account management |
| `/sales/new` | Record a sale |
| `/sales` | Sales history |

## Project structure

- `app/Controllers` contains authentication, management, dashboard, and sales workflows.
- `app/Models` maps products, customers, users, and sales.
- `app/Views` contains the responsive interface and validated forms.
- `app/Filters/AuthFilter.php` protects all management pages.
- `app/Database/Migrations` creates the complete schema and its foreign keys.
- `app/Database/Seeds/PosSeeder.php` inserts sample products, customers, and staff.
- `database/pos_midterm.sql` is the submission-ready database export.
- Uploaded product images and avatars use random filenames and are prepared for display with CodeIgniter's image service.

## Sales integrity

The Record Sale action validates all IDs and quantities, begins a database transaction, updates stock only when enough units remain, records the sale, and commits both changes together. Invalid or oversized orders do not change inventory.
