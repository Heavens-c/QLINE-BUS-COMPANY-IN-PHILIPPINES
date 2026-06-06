# QLine Bus Company - Modernized Bus Reservation System

This is the modernized, refactored, and secured web-based reservation system for **QLine Bus Company** (operating as **Dimple Star Transport** in the Philippines).

---

## 🌟 Key Features & International Standards Implemented

### 1. Visual Bus Seat Selection Map
Replaced simple text input fields with an interactive, visual 40-seat bus layout (rows of 2x2 with aisle spacing and driver cockpit).
- Fetches already booked seats dynamically for the selected route and departure date.
- Real-time click selection in the UI with strict seat count checks enforced via JavaScript based on traveler count.

### 2. Multi-Step Unified Booking Flow
Consolidated the fragmented, unstyled booking process (`book.php` -> `view.php` -> `buslist.php`) into a single unified step-by-step wizard within `book.php`:
- **Step 1 (Search):** Query available routes.
- **Step 2 (Results):** Render matching routes in a clean, styled table.
- **Step 3 (Traveler Info & Seat Map):** Input traveler information and pick seats using the visual seat map.
- **Step 4 (OTP Verification):** Verify reservation request via secure OTP code.
- **Step 5 (Checkout Redirect):** Redirect to secure simulated payment wall.

### 3. OTP Verification System (Email & SMS APIs)
Integrates a secure verification layer using the new table `otp_verifications` and `php_includes/otp_helper.php`:
- Generates 6-digit OTP codes valid for 5 minutes.
- Includes integration placeholders for SMS gateways (e.g., Semaphore, Twilio) and Email delivery (e.g., PHPMailer, SendGrid).
- Dev Mode Helper: Renders the generated OTP code in a badge for easier developer testing.

### 4. Payments Integration Wall (Xendit & Paymongo Simulation)
- **`payment_checkout.php`:** Simulates payment gateway checkout redirects supporting GCash, Maya, and Visa/Mastercard credit cards.
- **`payment_callback.php`:** Mock Webhook handler to receive asynchronous webhook callbacks from payment gateways to update transaction states.
- Tickets are marked as `pending` at booking and updated to `paid` with a transaction reference upon payment completion.

---

## 🔒 Security & Optimization Audits

### 1. SQL Injection Protection
All database query points in the booking flow, contact forms, and admin modules now use **MySQLi Prepared Statements** with bound parameters to prevent SQL injection.

### 2. Variable Mismatches Resolved
Standardized all database queries to use the established `$con` database connection resource instead of the legacy `$conn`.

### 3. Secure Configuration & Redirection
- Credentials are moved to a `.env` file in the root folder, and a `.gitignore` file was added to prevent committing credentials to git.
- Dynamic relative redirects used in `login.php` to prevent absolute redirection errors when hosted in subdirectories.

### 4. Dynamic Pages & Database Migrations
- **`about.php`:** Content is now rendered dynamically from the database (`about_page` and `about_values` tables) using clean CSS grids instead of static files.
- **`contact.php`:** Message submissions are handled locally via POST-to-self, log events in audit trailing, and show clean alert boxes.
- **`php_includes/migration.php`:** Runs automatically upon connection to add missing columns (`travel_date`, `payment_status`, `payment_ref`) and create required logging/verification tables automatically.

---

## 🛠️ Installation & Setup

1. **Clone the Repository** to your local web server root directory (e.g., `htdocs` or `/var/www/html`).
2. **Environment Configuration:** Create a `.env` file in the project root:
   ```env
   DB_HOST=localhost
   DB_USER=root
   DB_PASS=your_password
   DB_NAME=dimplestar
   OTP_EXPIRY_MINUTES=5
   ```
3. **Database Setup:** Import the SQL schema file `database/dimplestar.sql` to your MySQL server. (The system will automatically migrate and adjust columns on the first load).
4. **Access the Site:** Open your browser and navigate to `http://localhost/QLINE-BUS-COMPANY-IN-PHILIPPINES/`.

---

## 📋 Completed Refactoring Checklist

- [x] Configure `.env` and `php_includes/connection.php`
- [x] Merge `about_withsql.php` logic into `about.php` and delete `about_withsql.php`
- [x] Create `php_includes/otp_helper.php` (OTP structures, mock SMS & email APIs)
- [x] Implement unified booking flow in `book.php` with OTP step and delete obsolete booking scripts (`view.php`, `php_includes/buslist.php`, `php_includes/book.php`)
- [x] Create payment simulation pages (`payment_checkout.php` & `payment_callback.php`)
- [x] Refactor contact form processing directly inside `contact.php`
- [x] Fix subdirectory redirection bug in `login.php`
- [x] Align admin audit logs in `admin/audit_trail.php` with `audit_log` table
- [x] Remove dead "Slide Bar" links in `admin/_header.php` and `admin/dashboard.php`
- [x] Update database schema in `database/dimplestar.sql`
- [x] Remove useless files (`info.php` and `php_includes/fblike.php`)
- [x] Verify syntax and functionality

---

## 🧪 Syntax Verification Status

All files passed syntax validation checks:
- `No syntax errors detected in php_includes/connection.php`
- `No syntax errors detected in php_includes/migration.php`
- `No syntax errors detected in php_includes/otp_helper.php`
- `No syntax errors detected in about.php`
- `No syntax errors detected in contact.php`
- `No syntax errors detected in book.php`
- `No syntax errors detected in payment_checkout.php`
- `No syntax errors detected in payment_callback.php`
- `No syntax errors detected in login.php`
- `No syntax errors detected in admin/_header.php`
- `No syntax errors detected in admin/dashboard.php`
- `No syntax errors detected in admin/audit_trail.php`
