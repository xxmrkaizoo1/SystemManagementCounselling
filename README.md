
# System Management Counselling

System Management Counselling is a Laravel 12 web application for campus counselling operations. It supports role-based access for students, teachers, counsellors, and admins, including signup/login, OTP verification, booking workflows, internal chat, inbox notifications, and admin account management.

## Tech Stack

- **Backend:** PHP 8.2, Laravel 12
- **Frontend:** Blade templates, Vite, Tailwind CSS, Alpine.js
- **Database:** SQLite by default (configurable to MySQL/PostgreSQL)
- **Testing:** Pest + Laravel testing tools

## Core Features

- **Role-based experience:** redirects users to role-specific dashboards (`student`, `teacher`, `counsellor`, `admin`).
- **Secure auth flows:** login, password reset, signup with OTP verification.
- **Teacher-only registration gate:** lecturer access code validation for teacher signup.
- **Phone OTP delivery support:** OTP can be delivered through Telegram service integration.
- **Counselling booking lifecycle:** create, review, and track booking requests with status handling.
- **Chat and inbox modules:** internal communication and notification records.
- **Admin tools:** overview dashboard, account management, and counsellor management flows.

## Getting Started

### 1) Prerequisites

Install these first:

- PHP **8.2+**
- Composer
- Node.js **18+** and npm
- SQLite (or another DB engine you configure)

### 2) Clone and install dependencies

```bash
git clone <your-repo-url>
cd SystemManagementCounselling
composer install
npm install
```

### 3) Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` for your local setup:

- `APP_URL`
- DB settings (`DB_CONNECTION`, `DB_DATABASE`, etc.)
- Mail settings (`MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, ...)
- Optional Telegram OTP settings:
  - `TELEGRAM_BOT_TOKEN`
  - `TELEGRAM_CHAT_ID`
  - `TELEGRAM_OTP_ENDPOINT`
  - `TELEGRAM_OTP_AUTH_TOKEN`
  - `TELEGRAM_DEFAULT_COUNTRY_CODE`
- Teacher gate code:
  - `TEACHER_ACCESS_CODE`

> The default teacher access code fallback in config is `KVTEACHER2026`. Set your own secure value in `.env` for production.

### 4) Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

Seeder creates role records and sample users (including an admin account) for development/testing.

### 5) Run the app

Use the all-in-one dev script:

```bash
composer run dev
```

Or run services separately:

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
npm run dev
```

## Default Seeded Accounts (Development)

After running `php artisan db:seed`, sample users include:

- `thehas322@gmail.com` (admin)
- `test@example.com` (test user)

Use your local seeded password configuration and rotate credentials before any shared/staging deployment.

## Testing

```bash
php artisan test
```

## Useful Commands

```bash
# Format PHP code
./vendor/bin/pint

# Build frontend assets for production
npm run build

# Run dependency repair helpers (rollup/tailwind binaries)
npm run repair:deps
```

## Project Structure (High Level)

- `app/Http/Controllers` – application controllers for auth, admin, profile, chat
- `app/Models` – Eloquent models (`User`, `Role`, `BookingRequest`, etc.)
- `routes/web.php` – web routes and role-based dashboard logic
- `resources/views` – Blade views for pages and components
- `database/migrations` – database schema changes
- `database/seeders` – initial roles and sample user seeding

## Security Notes

- Replace all local/dev defaults before deployment.
- Use strong, unique secrets for `APP_KEY`, mail, and Telegram credentials.
- Store secrets only in environment variables, never in committed source.
- Ensure HTTPS, secure session configuration, and proper queue/mail drivers in production.

## License

This project is based on Laravel and follows the MIT license conventions used by the Laravel ecosystem unless your organization specifies otherwise.
