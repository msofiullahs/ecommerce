# Ecommerce

A full-featured e-commerce application built with Laravel 12, Inertia.js, Vue 3, and TailwindCSS.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Admin Dashboard**: TailAdmin + Inertia.js + Vue 3 + TailwindCSS
- **Customer Frontend**: Inertia.js + Vue 3 + TailwindCSS
- **Database**: MySQL
- **API**: RESTful with Laravel Sanctum
- **Translations**: English & Indonesian (UI + product content)

## Features

- **Admin Dashboard** - Graphic dashboard with revenue charts, order stats, top products
- **Product Management** - CRUD with translatable fields, image uploads, variant management
- **Category Management** - Hierarchical categories with translations
- **Stock Management** - Stock adjustments, movement history, low stock alerts
- **Order Management** - Order lifecycle with status transitions, order timeline
- **Promo Management** - Percentage/fixed discount codes with usage limits
- **Report Management** - Sales & product reports with Excel/PDF export
- **User Management** - Role-based access control (Super Admin, Admin, Warehouse Staff, Customer Service, Customer)
- **Settings Management** - General, SEO, social media, banner settings
- **Email Templates** - Customizable notification templates with translations
- **Payment Gateways** - Stripe, Midtrans, DOKU integration with admin configuration
- **Translation Support** - Full i18n for admin, frontend, and API (EN/ID)
- **Mobile API** - RESTful API with Sanctum token authentication
- **Customer Frontend** - Product browsing, cart, checkout, order history, profile

## Requirements

- PHP 8.3+
- MySQL 8.0+
- Node.js 18+
- Composer

## Installation

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file and configure
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_DATABASE=ecommerce
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate --seed

# Create storage symlink
php artisan storage:link

# Build assets
npm run build
```

## Development

```bash
# Start Vite dev server (HMR)
npm run dev

# With Laravel Valet
# App available at http://ecommerce.test

# With artisan serve
php artisan serve
# App available at http://localhost:8000
```

## Default Admin Login

- **Email**: admin@example.com
- **Password**: password

## User Roles

| Role | Access |
|------|--------|
| Super Admin | Full system access |
| Admin | Product, category, stock, order, promo, report, settings management |
| Warehouse Staff | Stock management, view orders |
| Customer Service | Order management, view products/customers |
| Customer | Browse, cart, checkout, order history, profile |

## Project Structure

```
app/
  Http/Controllers/
    Admin/          # Admin panel controllers
    Api/V1/         # Mobile API controllers
    Auth/           # Authentication controllers
    Frontend/       # Customer frontend controllers
  Models/           # Eloquent models
  Services/         # Business logic (Order, Cart, Stock, Promo, Payment, Report)
  Enums/            # OrderStatus, PaymentStatus, PromoType, StockMovementType
  Events/           # OrderPlaced, OrderStatusChanged, StockLow
  Listeners/        # Email notification listeners
  Mail/             # Mailable classes

resources/js/
  Pages/
    Admin/          # Admin Vue pages (24 pages)
    Frontend/       # Customer Vue pages (11 pages)
    Auth/           # Login & Register pages
  Components/
    Admin/          # Admin components (Layout, UI, Charts, Forms, Data)
    Frontend/       # Customer components (Layout)
  Composables/      # useCart, useLocale, useAuth
  i18n/             # Translation JSON files (en/id)
  types/            # TypeScript type definitions

routes/
  web.php           # Customer routes
  admin.php         # Admin routes (/admin)
  api.php           # API routes (/api/v1)
```

## API Endpoints

All API routes are prefixed with `/api/v1`.

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | /auth/register | No | Register |
| POST | /auth/login | No | Login |
| POST | /auth/logout | Yes | Logout |
| GET | /products | No | List products |
| GET | /products/{id} | No | Product detail |
| GET | /categories | No | List categories |
| GET | /settings | No | Public settings |
| GET | /cart | Yes | View cart |
| POST | /cart/items | Yes | Add to cart |
| PUT | /cart/items/{id} | Yes | Update cart item |
| DELETE | /cart/items/{id} | Yes | Remove cart item |
| POST | /cart/apply-promo | Yes | Apply promo code |
| GET | /orders | Yes | List orders |
| POST | /orders | Yes | Place order |
| GET | /orders/{id} | Yes | Order detail |
| GET | /profile | Yes | View profile |
| PUT | /profile | Yes | Update profile |

## Payment Gateways

Configure payment gateways in the admin panel under **Settings > Payment Gateways**. Supported gateways:

- **Stripe** - Credit card payments via Checkout Sessions
- **Midtrans** - Indonesian payment gateway via Snap
- **DOKU** - Indonesian payment gateway

## License

[MIT](https://opensource.org/licenses/MIT)
