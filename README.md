# Shopera-API-V0.1

A modern, multi-tenant e-commerce backend API built with Laravel 12 and MongoDB.

## 🚀 Features

### Core E-Commerce Functionality
- **Product Management**: Full CRUD operations for products with variants, pricing, and stock management
- **Category Management**: Hierarchical category system with parent/child relationships
- **Order Management**: Complete order processing with status tracking and history
- **User Management**: Role-based access control (Admin/User) with Sanctum authentication

### Technical Highlights
- ✅ **36 API Endpoints** (100% tested and passing)
- ✅ **MongoDB Integration** via Laravel MongoDB package
- ✅ **RESTful API Design** with proper resource transformations
- ✅ **Authentication** using Laravel Sanctum
- ✅ **Comprehensive Validation** with Form Requests
- ✅ **API Documentation** via Scramble

## 📦 Product Types Supported

The API is designed to handle various product types, currently optimized for:
- **Paper Products**: Copy paper, cardstock, specialty paper, envelopes
- **Configurable Products**: Products with variants (size, color, GSM, etc.)
- **Bulk Products**: Support for different pack sizes and quantities

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Database**: MongoDB 5.x
- **Authentication**: Laravel Sanctum
- **PHP**: 8.3+
- **API Documentation**: Scramble

## 📋 API Endpoints Overview

### Authentication (8 endpoints)
- POST `/api/register` - User registration
- POST `/api/login` - User login
- POST `/api/logout` - User logout
- GET `/api/user` - Get authenticated user
- POST `/api/forgot-password` - Password reset request
- POST `/api/email/verification-notification` - Resend verification
- GET `/sanctum/csrf-cookie` - CSRF token
- GET `/health` - Health check

### User Management (6 endpoints)
- GET `/api/users` - List all users (Admin)
- POST `/api/users` - Create user (Admin)
- GET `/api/users/{id}` - Show user (Admin)
- PUT `/api/users/{id}` - Update user (Admin)
- DELETE `/api/users/{id}` - Delete user (Admin)
- PUT `/api/settings/profile` - Update own profile

### Categories (6 endpoints)
- GET `/api/categories` - List all categories (Admin)
- POST `/api/categories` - Create category (Admin)
- GET `/api/categories/{id}` - Show category (Admin)
- PUT `/api/categories/{id}` - Update category (Admin)
- DELETE `/api/categories/{id}` - Delete category (Admin)
- GET `/api/categories/express-shop` - Public category listing

### Products (7 endpoints)
- GET `/api/products` - List all products
- POST `/api/products` - Create product (Admin)
- GET `/api/products/{id}` - Show product details
- PUT `/api/products/{id}` - Update product (Admin)
- DELETE `/api/products/{id}` - Delete product (Admin)
- GET `/api/products/home` - Home page products
- GET `/api/products/express-shop` - Express shop products

### Orders (4 endpoints)
- GET `/api/orders` - List all orders (Admin)
- POST `/api/orders` - Create order
- GET `/api/orders/{id}` - Show order details
- PUT `/api/orders/{id}/status` - Update order status

### Media & Documentation (4 endpoints)
- POST `/api/media/upload` - Upload media files
- GET `/api/images/{path}` - Serve images
- GET `/docs/api` - API documentation UI
- GET `/docs/api.json` - API documentation JSON

## 🚦 Getting Started

### Prerequisites
- PHP 8.3 or higher
- MongoDB 5.x or higher
- Composer
- MongoDB PHP Extension

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/achrafhilaly/Shopera-API-V0.1.git
cd Shopera-API-V0.1
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure MongoDB connection in `.env`**
```env
DB_CONNECTION=mongodb
MONGODB_URI=your_mongodb_connection_string
MONGODB_DATABASE=your_database_name
```

5. **Start the development server**
```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

## 📝 Sample Data

### Seed Paper Products
Run the seeding script to add 20 sample paper products:
```bash
./seed-paper-products.sh
```

This will create:
- 4 categories (Copy Paper, Cardstock, Specialty Paper, Envelopes)
- 20 products with complete details and metadata

## 🧪 Testing

Run the comprehensive test suite:
```bash
./test-all-endpoints.sh
```

This will test all 36 endpoints with proper authentication and validation.

## 📖 API Documentation

Visit `/docs/api` when the server is running to access the interactive API documentation.

## 🔒 Authentication

The API uses Laravel Sanctum for authentication. To access protected endpoints:

1. **Login** to get a bearer token:
```bash
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}
```

2. **Include the token** in subsequent requests:
```bash
Authorization: Bearer your-token-here
```

## 🏗️ Architecture

- **Single Codebase, Multiple Deployments**: Designed for multi-tenant deployment
- **Separate Database per Tenant**: Each deployment connects to its own MongoDB database
- **RESTful Design**: Standard REST conventions with proper HTTP methods
- **Resource Transformers**: Consistent API response format
- **Request Validation**: All inputs validated via Form Requests
- **Role-Based Access**: Admin and User roles with appropriate permissions

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## 📧 Contact

For questions or support, please open an issue on GitHub.
