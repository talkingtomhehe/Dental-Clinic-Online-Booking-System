# TechCare - Dental Clinic Online Booking System

A modern hospital management system with online appointment booking, electronic medical records, and intelligent scheduling features.

## 📋 Overview

TechCare is a comprehensive dental clinic management system designed to streamline healthcare delivery through:

- **Smart Appointment Scheduling**: real-time availability tracking
- **Role-Based Access Control**: Separate interfaces for Patients, Receptionists, and Doctors
- **Electronic Medical Records**: Comprehensive patient data management with HIPAA compliance considerations
- **Real-Time Patient Flow**: Live monitoring and analytics dashboard
- **Responsive Design**: Modern UI built with TailwindCSS and Alpine.js

## Key Features

### For Patients
- Online appointment booking with real-time slot availability
- View and manage appointment history
- Cancel/reschedule appointments
- Personal profile management
- Insurance information tracking

### For Receptionists
- Patient management dashboard
- Doctor schedule management
- System configuration controls
- Appointment oversight and management

### For Doctors
- Patient appointment viewing
- Medical record access
- Schedule management

## Technology Stack

- **Backend**: PHP 8.x with MVC architecture
- **Database**: MySQL/MariaDB
- **Frontend**: TailwindCSS, Alpine.js
- **Icons**: Tabler Icons
- **Server**: Apache with mod_rewrite

## Project Structure

```
dental/
├── app/
│   ├── Controllers/        # Application controllers
│   ├── Models/            # Database models
│   ├── Views/             # View templates
│   └── Services/          # Business logic services
├── config/
│   ├── config.php         # Application configuration
│   └── database.php       # Database connection
├── public/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── index.php          # Application entry point
├── .htaccess              # Root Apache configuration
└── dental_clinic.sql      # Database schema
```

## Installation & Setup

### Prerequisites

- PHP 8.0 or higher
- MySQL/MariaDB 5.7 or higher
- Apache server with mod_rewrite enabled
- Composer (optional, for dependencies)
- Node.js & npm (for TailwindCSS development)

### Step 1: Clone the Repository

```bash
git clone <repository-url>
cd dental
```

### Step 2: Configure Apache

Place the project in your web server directory (e.g., `C:/xampp/htdocs/dental` or `/var/www/html/dental`)

Ensure Apache `mod_rewrite` is enabled:
```bash
# For Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Step 3: Database Setup

1. Create a new database:
```sql
CREATE DATABASE dental_clinic CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

2. Import the database schema:
```bash
mysql -u root -p dental_clinic < dental_clinic.sql
```

Or use phpMyAdmin to import `dental_clinic.sql`

### Step 4: Configure Application

Edit `config/config.php`:
```php
define('BASE_URL', 'http://localhost/dental');
define('BASE_PATH', 'C:/xampp/htdocs/dental'); // Adjust path
```

Edit `config/database.php`:
```php
private $host = 'localhost';
private $db_name = 'dental_clinic';
private $username = 'root';
private $password = ''; // Your MySQL password
```

### Step 5: Set Permissions (Linux/Mac)

```bash
chmod -R 755 dental/
chmod -R 777 dental/public/
```

### Step 6: Access the Application

Open your browser and navigate to:
```
http://localhost/dental/public
```

## Default Login Credentials

### Patient Account
- **Username**: `pat_an`
- **Password**: `password` (default hash)

### Receptionist Account
- **Username**: `admin_sarah`
- **Password**: `password` (default hash)

### Doctor Account
- **Username**: `dr_nghia`
- **Password**: `password` (default hash)

> **Note**: All default passwords use bcrypt hashing with the value `password`. In production, change these immediately!

## Development

### Compile TailwindCSS

If you make CSS changes:

```bash
# Install dependencies
npm install

# Build CSS
npx tailwindcss -i ./public/css/style.css -o ./public/css/output.css --watch
```

### Project Configuration

Key configuration files:
- `config/config.php` - Base URL and paths
- `config/database.php` - Database credentials
- `tailwind.config.js` - TailwindCSS settings
- `.htaccess` - Apache rewrite rules

## Troubleshooting

### Common Issues

**404 Errors**
- Check that mod_rewrite is enabled
- Verify .htaccess files are present
- Check BASE_URL in config.php

**Database Connection Failed**
- Verify MySQL is running
- Check credentials in config/database.php
- Ensure database exists and is imported

**CSS Not Loading**
- Run TailwindCSS build command
- Check file permissions
- Verify output.css is generated

**Session Issues**
- Check PHP session configuration
- Verify write permissions on session directory
- Clear browser cookies