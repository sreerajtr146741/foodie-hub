# Food Court API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your_token}
```

---

## Authentication Endpoints

### Register
**POST** `/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "1234567890"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {...},
    "token": "1|abc123..."
  }
}
```

### Login
**POST** `/login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "2|xyz789..."
  }
}
```

### Get User Profile
**GET** `/user` (Protected)

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    ...
  }
}
```

### Update Profile
**PUT** `/user/profile` (Protected)

**Request Body:**
```json
{
  "name": "John Updated",
  "phone": "9876543210"
}
```

### Logout
**POST** `/logout` (Protected)

---

## Food Endpoints

### Get All Foods
**GET** `/foods`

**Query Parameters:**
- `search` - Search by name or description
- `category_id` - Filter by category
- `min_price` - Minimum price
- `max_price` - Maximum price
- `per_page` - Items per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [...],
    "total": 50
  }
}
```

### Get Single Food
**GET** `/foods/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "food": {...},
    "similar_foods": [...],
    "average_rating": 4.5,
    "total_reviews": 10
  }
}
```

### Get Categories
**GET** `/categories`

### Get Featured Foods
**GET** `/foods/featured`

---

## Cart Endpoints (Protected)

### Get Cart
**GET** `/cart`

**Response:**
```json
{
  "success": true,
  "data": {
    "cart": {...},
    "total": 500,
    "items_count": 3
  }
}
```

### Add to Cart
**POST** `/cart/add`

**Request Body:**
```json
{
  "food_id": 1,
  "quantity": 2
}
```

### Update Cart Item
**PUT** `/cart/{itemId}`

**Request Body:**
```json
{
  "quantity": 3
}
```

### Remove from Cart
**DELETE** `/cart/{itemId}`

### Clear Cart
**DELETE** `/cart`

---

## Order Endpoints (Protected)

### Get All Orders
**GET** `/orders`

**Query Parameters:**
- `per_page` - Items per page (default: 15)

### Get Single Order
**GET** `/orders/{id}`

### Place Order
**POST** `/orders`

**Request Body:**
```json
{
  "shipping_address": "123 Main St, City, State 12345"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order placed successfully",
  "data": {
    "id": 1,
    "status": "placed",
    "total_price": 500,
    ...
  }
}
```

### Cancel Order
**POST** `/orders/{id}/cancel`

---

## Booking Endpoints (Protected)

### Get All Bookings
**GET** `/bookings`

### Create Booking
**POST** `/bookings`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "guests": 4,
  "booking_date": "2025-01-15",
  "booking_time": "19:00",
  "table_type": "standard",
  "seating_preference": "indoor",
  "window_side": true,
  "occasion": "birthday",
  "special_requests": "Cake arrangement",
  "food_items": [
    {
      "food_id": 1,
      "quantity": 2,
      "cooking_note": "Extra spicy"
    }
  ]
}
```

### Cancel Booking
**POST** `/bookings/{id}/cancel`

---

## Review Endpoints

### Get Reviews for Food
**GET** `/foods/{foodId}/reviews`

### Add Review (Protected)
**POST** `/reviews`

**Request Body:**
```json
{
  "food_id": 1,
  "rating": 5,
  "comment": "Excellent food!"
}
```

---

## Contact Endpoints

### Submit Contact Message
**POST** `/contact`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "9876543210",
  "message": "I have a question about catering."
}
```

### Get Restaurant Info
**GET** `/contact/info`

**Response:**
```json
{
  "success": true,
  "data": {
    "restaurant_name": "Food Court",
    "address": "...",
    "phone": "...",
    "email": "...",
    "working_hours": {...},
    "social_media": {...}
  }
}
```

---

## Error Responses

All endpoints return errors in this format:
```json
{
  "success": false,
  "message": "Error description",
  "error": "Technical error details"
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `500` - Server Error

---

## Testing with Postman

1. **Register/Login** to get your token
2. **Set Authorization** header: `Bearer {token}`
3. **Make requests** to protected endpoints

## Testing with cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@test.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@test.com","password":"password123"}'

# Get Foods (with token)
curl -X GET http://localhost:8000/api/foods \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Rate Limiting
API requests are limited to 60 requests per minute per user.

## Pagination
All list endpoints support pagination with `per_page` parameter (max: 100).

## CORS
CORS is enabled for all origins in development. Configure for production in `config/cors.php`.
