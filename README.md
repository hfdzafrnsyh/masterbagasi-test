# MasterBagasi API

A RESTful e-commerce API built with **Laravel 11** and **PHP 8.2+**, designed to demonstrate backend engineering practices including authentication, role-based authorization, product management, shopping cart workflows, checkout, order management, and voucher administration.

This project was developed as a backend engineering assessment and focuses on building a structured API with Laravel's modern application architecture.

---

## Overview

MasterBagasi API provides the backend services required for a basic e-commerce workflow:

```text
Client
  │
  ├── Authentication
  │     ├── Register
  │     ├── Login
  │     └── Logout
  │
  ├── Product
  │     ├── Browse products
  │     └── Product details
  │
  ├── Shopping Cart
  │     ├── Add product
  │     ├── Update quantity
  │     ├── Remove product
  │     └── Checkout
  │
  ├── Order
  │     ├── Create order
  │     └── View orders
  │
  └── Administration
        ├── Manage products
        └── Manage vouchers
```

The API uses **Laravel Sanctum** for authentication and middleware-based role authorization to separate regular user and administrator capabilities.

---

## Tech Stack

### Backend

| Technology           | Purpose                           |
| -------------------- | --------------------------------- |
| PHP 8.2+             | Backend programming language      |
| Laravel 11           | Web application framework         |
| Laravel Sanctum      | API authentication                |
| Laravel Eloquent ORM | Database access and relationships |
| Laravel Middleware   | Authentication and authorization  |
| PHPUnit              | Automated testing                 |
| Laravel Pint         | Code style and formatting         |

### Frontend / Build Tooling

| Technology | Purpose                 |
| ---------- | ----------------------- |
| Vite       | Frontend asset bundling |
| Axios      | HTTP client             |

### Development Tools

| Tool     | Purpose                          |
| -------- | -------------------------------- |
| Composer | PHP dependency management        |
| NPM      | JavaScript dependency management |
| Git      | Version control                  |

---

## Architecture

The application follows Laravel's conventional layered architecture:

```text
HTTP Request
     │
     ▼
Routes
     │
     ▼
Middleware
 ┌───────────────┐
 │ Authentication │
 │ Authorization  │
 └───────────────┘
     │
     ▼
Controllers
     │
     ▼
Models / Eloquent
     │
     ▼
Database
```

Application responsibilities are separated into dedicated areas such as:

```text
app/
├── Http/
│   └── Controllers/
│       ├── Auth/
│       ├── Cart/
│       ├── Order/
│       ├── Product/
│       └── Voucher/
│
├── Models/
│
└── ...
```

This structure keeps authentication, product, cart, order, and voucher functionality separated and easier to maintain.

---

## Core Features

### Authentication

* User registration
* User login
* Authenticated user endpoint
* Logout
* Token-based authentication using Laravel Sanctum

### Authorization

The API separates access using role-based middleware:

```text
User
├── Products
├── Cart
├── Checkout
├── Orders
└── Logout

Admin
├── Product management
├── Voucher management
└── Logout
```

Protected routes use Laravel's authentication middleware together with application-level role authorization.

---

### Product Management

Authenticated users can:

* Browse products
* View product details

Administrators can:

* Add products

---

### Shopping Cart

The cart workflow supports:

* Add products to cart
* View cart
* Increase quantity
* Decrease quantity
* Remove cart items
* Update checked status
* Prepare cart for checkout

---

### Checkout & Orders

The application provides an order workflow that allows users to:

1. Select products from the cart
2. Prepare checkout data
3. Create an order
4. Create an order directly from products
5. Retrieve order history

---

### Voucher Management

Administrators can create vouchers through a protected API endpoint.

---

## API Endpoints

### Authentication

| Method | Endpoint        | Access        |
| ------ | --------------- | ------------- |
| `POST` | `/api/login`    | Public        |
| `POST` | `/api/register` | Public        |
| `GET`  | `/api/user`     | Authenticated |
| `POST` | `/api/logout`   | Authenticated |

### Products

| Method | Endpoint                   | Access |
| ------ | -------------------------- | ------ |
| `GET`  | `/api/product`             | User   |
| `GET`  | `/api/product/{id}/detail` | User   |
| `POST` | `/api/product/add`         | Admin  |

### Cart

| Method   | Endpoint                          | Access |
| -------- | --------------------------------- | ------ |
| `POST`   | `/api/cart/add`                   | User   |
| `GET`    | `/api/cart`                       | User   |
| `GET`    | `/api/cart/{id}/quantity/added`   | User   |
| `GET`    | `/api/cart/{id}/quantity/reduced` | User   |
| `GET`    | `/api/cart/{id}/checked`          | User   |
| `DELETE` | `/api/cart/{id}/delete`           | User   |
| `GET`    | `/api/cart/checkout`              | User   |

### Checkout & Orders

| Method | Endpoint                | Access |
| ------ | ----------------------- | ------ |
| `GET`  | `/api/checkout/product` | User   |
| `POST` | `/api/order/cart`       | User   |
| `POST` | `/api/order/add`        | User   |
| `GET`  | `/api/order`            | User   |

### Vouchers

| Method | Endpoint           | Access |
| ------ | ------------------ | ------ |
| `POST` | `/api/voucher/add` | Admin  |

> Endpoint definitions are based on the current API route configuration in the repository.

---

## Authentication Flow

The API uses Laravel Sanctum for authenticated API requests.

```text
Client
  │
  │ POST /api/login
  ▼
AuthController
  │
  ▼
Laravel Sanctum Token
  │
  ▼
Client stores token
  │
  │ Authorization: Bearer <token>
  ▼
Protected API
  │
  ▼
auth:sanctum
  │
  ▼
Role middleware
  │
  ▼
Controller
```

---

## Role-Based Access Control

The application currently defines two primary API access levels:

### User

Regular authenticated users can access:

* Product browsing
* Product details
* Shopping cart
* Checkout
* Orders
* Logout

### Admin

Administrators have additional access to:

* Product creation
* Voucher creation

Authorization is enforced through middleware rather than relying only on frontend restrictions.

---




## Development Notes

This repository was created as a backend engineering assessment project.

The implementation focuses on demonstrating the ability to design and implement a structured Laravel API covering authentication, authorization, product management, shopping cart workflows, checkout, orders, and administrative operations.

---

