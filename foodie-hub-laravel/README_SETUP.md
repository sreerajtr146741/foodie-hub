# Restaurant Management System Setup Guide

## 1. Prerequisites
- PHP 8.2+
- Composer
- MySQL
- Node.js (Optional, as we used CDN for Tailwind)

## 2. Installation
1.  **Clone/Download** the project.
2.  **Install Dependencies**:
    ```bash
    composer install
    ```
3.  **Environment Setup**:
    - Copy `.env.example` to `.env`:
    ```bash
    cp .env.example .env
    ```
    - Update Database credentials in `.env`:
    ```env
    DB_DATABASE=foodie_hub
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    - Update Mail Configuration (for emails to work):
    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=587
    MAIL_USERNAME=your-email@gmail.com
    MAIL_PASSWORD=your-app-password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="no-reply@foodiehub.com"
    ```

## 3. Database Setup
Run migrations to create tables:
```bash
php artisan migrate
```

## 4. Admin Account
Register a new user via the website registration page, then manually update their role to `admin` in the database:
```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```
Or use tinker:
```bash
php artisan tinker
> $user = App\Models\User::first();
> $user->role = 'admin';
> $user->save();
```

## 5. Running the Application
Start the development server:
```bash
php artisan serve
```
Visit `http://localhost:8000`

## 6. Features
- **Admin Panel**: `/admin/dashboard` (Manage Foods, Categories, Orders)
- **Buyer Side**: Browse Menu, Cart, Checkout (COD), My Orders.
- **Email Notifications**: Sent on Order Placement and Status Update.

## 7. Troubleshooting
- **Images not showing?** Run `php artisan storage:link`.
- **Emails not sending?** Check internet connection and SMTP credentials.
