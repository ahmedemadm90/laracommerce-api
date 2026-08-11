# 🚀 LaraCommerce API

An enterprise-grade, highly scalable RESTful API built with **Laravel 11**, designed for modern e-commerce applications. This project implements industry best practices including Clean Architecture principles, Laravel Sanctum authentication, API resource transformations, robust validation, and automated feature testing.

---

## 🛠️ Tech Stack & Architecture

- **Framework**: Laravel 11 (PHP 8.3)
- **Authentication**: Laravel Sanctum (Token-based API authentication)
- **Database**: MySQL / SQLite (with Eloquent ORM & Migrations)
- **API Documentation**: OpenAPI 3.0 / Postman Collection Ready
- **Architecture**: Service-Repository Pattern & Form Request Validation

---

## 📦 Core Features

1. **Authentication & Authorization**:
   - Secure User Registration & Login with Sanctum token generation.
   - Role-based access control (Customer vs Admin).

2. **Product Catalog Management**:
   - Paginated product listing with advanced filtering (by category, price range, search query).
   - Detailed product view with inventory tracking and stock alerts.

3. **Cart & Order Processing**:
   - Add/Update/Remove items from shopping cart.
   - Checkout system with order status workflow (Pending, Processing, Completed, Cancelled).

4. **Security & Performance**:
   - Rate limiting on sensitive endpoints (Login/Register).
   - Strict input validation using Form Requests.
   - Eloquent API Resources for clean JSON data formatting.

---

## ⚙️ Installation & Getting Started

1. **Clone the repository**:
   ```bash
   git clone https://github.com/ahmedemadm90/laracommerce-api.git
   cd laracommerce-api
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

5. **Start Development Server**:
   ```bash
   php artisan serve
   ```

---

## 📡 API Endpoints Overview

| Method | Endpoint | Description | Access |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/v1/auth/register` | Register new user | Public |
| `POST` | `/api/v1/auth/login` | Authenticate & get token | Public |
| `GET` | `/api/v1/products` | Get paginated products list | Public |
| `GET` | `/api/v1/products/{id}` | Get product details | Public |
| `POST` | `/api/v1/cart` | Add item to cart | Authenticated |
| `POST` | `/api/v1/orders` | Place new order | Authenticated |
| `GET` | `/api/v1/orders` | Get user order history | Authenticated |

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 👨‍💻 Author

Developed with ❤️ by **Ahmed Emad** (Backend & Full-Stack Developer).
