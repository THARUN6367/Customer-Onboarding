# Customer Onboarding System

## Overview
The **Customer Onboarding System** is a web-based application designed to simplify the process of registering and managing customer information. It provides an intuitive interface for users and administrators to handle customer onboarding efficiently.

## Features
- **User Registration & Login**: Secure authentication using PHP & MySQL.
- **Admin Dashboard**: Manage user accounts, view customer data, and perform administrative tasks.
- **Customer Dashboard**: Users can track their onboarding status.
- **Document Upload**: Customers can upload necessary verification documents.
- **Email Notifications**: Automated email alerts for onboarding updates.
- **Secure Authentication**: Implements encrypted passwords for data protection.

## Project Structure
```
📂 Customer-Onboarding
│-- 📂 css                # Stylesheets for the frontend
│-- 📂 js                 # JavaScript files for frontend functionalities
│-- 📂 vendor             # External libraries and dependencies
│-- 📜 README.md          # Project documentation
│-- 📜 SQL COMMAND.txt    # SQL queries for database setup
│-- 📜 admin_dashboard.php # Admin panel for managing users
│-- 📜 config.php         # Database configuration
│-- 📜 dashboard.html     # User dashboard
│-- 📜 dashboard.php      # Backend logic for user dashboard
│-- 📜 fetchusers.php     # Fetch users' data
│-- 📜 index.html         # Homepage
│-- 📜 login.html         # Login page
│-- 📜 login.php          # Login backend logic
│-- 📜 recovery.html      # Password recovery page
│-- 📜 recovery.php       # Password recovery backend logic
│-- 📜 register.php       # User registration logic
│-- 📜 logout.php         # User logout script
│-- 📜 login_bg.png       # Background image for login page
│-- 📜 logo-transparent.png # Project logo
│-- 📜 pos-pay-logo.jpeg  # Payment system logo
```

## Technology Stack
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Libraries**: Bootstrap (for styling), External Vendor Libraries

## Installation
### Prerequisites
- XAMPP/WAMP installed (for local development)
- MySQL database setup
- PHP 7.x or later installed

### Steps
1. **Clone the Repository**
   ```sh
   git clone https://github.com/THARUN6367/Customer-Onboarding.git
   cd Customer-Onboarding
   ```
2. **Set Up the Database**
   - Open `SQL COMMAND.txt`
   - Import the SQL file into MySQL using phpMyAdmin or MySQL CLI.
3. **Configure Database Connection**
   - Update `config.php` with your database credentials.
4. **Run the Project**
   - Place the project files inside the `htdocs` folder (for XAMPP) or `www` (for WAMP).
   - Start Apache and MySQL from the control panel.
   - Open `http://localhost/Customer-Onboarding/index.html` in your browser.

## API Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | /login.php | Authenticate user |
| POST   | /register.php | Register a new user |
| GET    | /fetchusers.php | Retrieve user details |
| GET    | /dashboard.php | Load user dashboard |
| GET    | /admin_dashboard.php | Load admin dashboard |

## Contributing
1. Fork the repository.
2. Create a new branch (`feature-xyz`).
3. Commit your changes.
4. Push to the branch and open a pull request.

## Contact
For queries or contributions, reach out via [tharun6367@gmail.com](mailto:tharun6367@gmail.com).
