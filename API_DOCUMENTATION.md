# 🍽️ Restaurant Management API Documentation

**Base URL:** `http://127.0.0.1:8000/api`

---

## 🏥 Health Check

### Check Backend Status
```http
GET /api/health
```
**Response:**
```json
{
  "status": "ok",
  "message": "Backend is running",
  "timestamp": "2025-11-13 10:30:00"
}
```

---

## 🔐 Authentication Endpoints

### 1. Register New User
```http
POST /api/auth/register
```
**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```
**Response:**
```json
{
  "message": "User registered successfully",
  "user": {...},
  "access_token": "1|xxxxx...",
  "token_type": "Bearer"
}
```

### 2. Login
```http
POST /api/auth/login
```
**Body:**
```json
{
  "email": "admin@restaurant.com",
  "password": "admin123"
}
```
**Response:**
```json
{
  "message": "Login successful",
  "user": {...},
  "access_token": "2|xxxxx...",
  "token_type": "Bearer"
}
```

### 3. Logout
```http
POST /api/auth/logout
Headers: Authorization: Bearer {token}
```

### 4. Get Current User
```http
GET /api/user
Headers: Authorization: Bearer {token}
```

---

## 📂 Categories API

### Get All Categories
```http
GET /api/categories
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Appetizers",
      "image": "http://127.0.0.1:8000/Image/Appetizers.jpg",
      "created_at": "2025-10-29 07:52:32",
      "updated_at": "2025-10-29 07:52:32"
    }
  ]
}
```

### Get Single Category
```http
GET /api/categories/{id}
```

### Create Category
```http
POST /api/categories
```
**Body:**
```json
{
  "name": "New Category",
  "image": "Image/category.jpg"
}
```

### Update Category
```http
PUT /api/categories/{id}
```
**Body:**
```json
{
  "name": "Updated Category",
  "image": "Image/updated.jpg"
}
```

### Delete Category
```http
DELETE /api/categories/{id}
```

---

## 🍕 Products API

### Get All Products
```http
GET /api/products
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Spring Rolls",
      "description": "Fresh vegetables wrapped in rice paper",
      "price": "8.99",
      "category_id": 1,
      "image": "Image/spring-rolls.jpg",
      "created_at": "2025-10-29 07:52:32",
      "updated_at": "2025-10-29 07:52:32"
    }
  ]
}
```

### Get Single Product
```http
GET /api/products/{id}
```

### Create Product
```http
POST /api/products
```
**Body:**
```json
{
  "name": "New Product",
  "description": "Product description",
  "price": 15.99,
  "category_id": 1,
  "image": "Image/product.jpg"
}
```

### Update Product
```http
PUT /api/products/{id}
```

### Delete Product
```http
DELETE /api/products/{id}
```

---

## 🪑 Tables API

### Get All Tables
```http
GET /api/tables
```
**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "table_number": 1,
      "is_available": true,
      "created_at": "2025-10-29 07:52:32",
      "updated_at": "2025-10-29 07:52:32"
    }
  ]
}
```

### Get Single Table
```http
GET /api/tables/{id}
```

### Create Table
```http
POST /api/tables
```
**Body:**
```json
{
  "table_number": 21,
  "is_available": true
}
```

### Update Table
```http
PUT /api/tables/{id}
```

### Delete Table
```http
DELETE /api/tables/{id}
```

---

## 🛒 Cart API

### Get All Cart Items
```http
GET /api/carts
```

### Get Single Cart Item
```http
GET /api/carts/{id}
```

### Add to Cart
```http
POST /api/carts
```
**Body:**
```json
{
  "product_id": 1,
  "table_number_id": 1,
  "quantity": 2
}
```

### Update Cart Item
```http
PUT /api/carts/{id}
```
**Body:**
```json
{
  "quantity": 3
}
```

### Remove from Cart
```http
DELETE /api/carts/{id}
```

---

## 📝 Orders API

### Get All Orders
```http
GET /api/orders
```

### Get Orders by Status
```http
GET /api/orders/status?status={pending|preparing|ready|served}
```

### Get Single Order
```http
GET /api/orders/show/{id}
```

### Get Order by Table Number
```http
GET /api/orders/number/{table_number_id}
```

### Create Order (from cart)
```http
POST /api/orders
```
**Body:**
```json
{
  "table_number_id": 1,
  "order_list_id": 1,
  "total_amount": 45.50,
  "status": "pending"
}
```

### Update Order Status
```http
PUT /api/orders/{id}
```
**Body:**
```json
{
  "status": "preparing"
}
```

### Cancel Order
```http
PUT /api/orders/listorder/{id}
```

---

## 📊 Database Schema

### Users
- id, name, email, password, created_at, updated_at

### Categories
- id, name, image, created_at, updated_at

### Products
- id, name, description, price, category_id, image, created_at, updated_at

### Table Numbers
- id, table_number, is_available, created_at, updated_at

### Carts
- id, product_id, table_number_id, quantity, created_at, updated_at

### Order Lists
- id, product_id, quantity, price, created_at, updated_at

### Order Information
- id, order_list_id, table_number_id, total_amount, status, created_at, updated_at

---

## 🎯 Sample Data

### Test Users
```
Admin: admin@restaurant.com / admin123
Test User: test@example.com / password
```

### Categories (5)
- Appetizers
- Main Course
- Desserts
- Beverages
- Salads

### Products (15)
- Spring Rolls, Chicken Wings, Mozzarella Sticks (Appetizers)
- Grilled Salmon, Beef Steak, Chicken Alfredo, Vegetable Curry (Main Course)
- Chocolate Cake, Tiramisu, Cheesecake (Desserts)
- Orange Juice, Coffee, Iced Tea (Beverages)
- Caesar Salad, Greek Salad (Salads)

### Tables (20)
- Table 1-20, all available

---

## 🔑 Authentication

For protected routes, include the token in the header:
```
Authorization: Bearer {your_token_here}
```

---

## 📷 Images

Images are stored in `public/Image/` and accessible at:
```
http://127.0.0.1:8000/Image/{filename}.jpg
```

Current images:
- Appetizers.jpg
- Main_course.jpg
- Dessert.jpg
- Beverages.jpg
- Salads.jpg

---

## 🚀 Quick Start

1. **Start Server:**
   ```bash
   php artisan serve
   ```

2. **Test Connection:**
   ```bash
   curl http://127.0.0.1:8000/api/health
   ```

3. **Get Categories:**
   ```bash
   curl http://127.0.0.1:8000/api/categories
   ```

4. **Login:**
   ```bash
   curl -X POST http://127.0.0.1:8000/api/auth/login \
     -H "Content-Type: application/json" \
     -d '{"email":"admin@restaurant.com","password":"admin123"}'
   ```

---

## ⚠️ Important Notes

- **CORS:** Already configured for all origins
- **Database:** MySQL on port 3307 (XAMPP)
- **Session:** File-based sessions
- **API Version:** Laravel 12.x
- **Authentication:** Laravel Sanctum (token-based)

---

## 🛠️ Frontend Integration

```javascript
// Example fetch request
const API_BASE = 'http://127.0.0.1:8000/api';

// Get categories
fetch(`${API_BASE}/categories`)
  .then(res => res.json())
  .then(data => console.log(data));

// Login
fetch(`${API_BASE}/auth/login`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'admin@restaurant.com',
    password: 'admin123'
  })
})
.then(res => res.json())
.then(data => {
  localStorage.setItem('token', data.access_token);
});

// Authenticated request
fetch(`${API_BASE}/user`, {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`,
    'Accept': 'application/json'
  }
})
.then(res => res.json())
.then(data => console.log(data));
```

---

**Last Updated:** November 13, 2025