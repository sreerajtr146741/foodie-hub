# 🍽️ FOOD COURT - TABLE BOOKING WITH DINE-IN PRE-ORDER SYSTEM
## Complete Setup & User Guide

---

## ✅ **SYSTEM COMPLETE - ALL FEATURES IMPLEMENTED!**

### **📋 What's Been Built:**

#### **CUSTOMER SIDE:**
✅ Complete table booking form with:
- Customer information (Name, Phone, Email)
- Booking details (Date, Time, Guests)
- Table preferences (Indoor/Outdoor, AC/Non-AC, Window side)
- Occasion selection (Birthday, Anniversary, etc.)
- Special requests field
- **FOOD PRE-ORDER SECTION** (DINE-IN ONLY)
  - Browse available food items
  - Add multiple items
  - Specify quantity
  - Add cooking notes (Less spicy, No onion, Extra sauce)

✅ My Bookings page showing:
- All user bookings
- Booking status with badges
- Pre-ordered food items
- Food preparation status
- Cancel booking option

#### **ADMIN SIDE:**
✅ Bookings Management Panel:
- View all table bookings
- Assign table numbers
- Update booking status (Pending/Confirmed/Cancelled/Completed)
- View pre-ordered food items per booking
- Update food order status (Waiting/Preparing/Served)
- Add admin notes
- Delete bookings

✅ Dashboard with statistics:
- Total/Today's orders
- Total revenue
- Total customers
- **Total bookings**
- **Pending bookings**

#### **EMAIL SYSTEM:**
✅ Professional email templates:
- Booking confirmation email
- Shows all booking details
- Lists pre-ordered food items
- Total amount calculation
- Restaurant branding

#### **DATABASE:**
✅ Complete schema:
- `bookings` table (with all preferences)
- `booking_food_orders` junction table
- `food` table (existing)
- Proper foreign keys and relationships

---

## 🚀 **GETTING STARTED:**

### **1. Make a User Admin:**

**Option A - Using phpMyAdmin (RECOMMENDED):**
1. Open: `http://localhost/phpmyadmin`
2. Select `food_court` database
3. Click `users` table
4. Find your user
5. Click **Edit**
6. Change `role` from `customer` to `admin`
7. Click **Go**
8. Logout and login again

**Option B - Using SQL:**
```sql
UPDATE users 
SET role = 'admin' 
WHERE email = 'your-email@example.com';
```

### **2. Access Points:**

**Customer:**
- Book Table: `http://127.0.0.1:8000/book-table`
- My Bookings: `http://127.0.0.1:8000/my-bookings`

**Admin:**
- Admin Panel: `http://127.0.0.1:8000/admin/dashboard`
- Bookings Management: `http://127.0.0.1:8000/admin/bookings`

---

## 📖 **USER GUIDE:**

### **HOW TO BOOK A TABLE (Customer):**

1. **Click "Book Table" in navigation**
2. **Fill Customer Information:**
   - Full Name
   - Mobile Number
   - Email Address

3. **Select Booking Details:**
   - Choose Date (cannot be in the past)
   - Choose Time
   - Number of Guests (1-20)

4. **Choose Table Preferences (Optional):**
   - Table Type: Indoor/Outdoor
   - Seating: AC/Non-AC
   - Window Side: Yes/No
   - Occasion: Birthday, Anniversary, etc.
   - Special Requests

5. **Pre-Order Food (Optional - DINE-IN ONLY):**
   - Click "Add Food Item"
   - Select food from dropdown
   - Enter quantity
   - Add cooking notes if needed
   - Add more items as needed
   - Remove items with delete button

6. **Submit Booking:**
   - Click "Reserve Table"
   - Confirmation email sent automatically
   - Redirected to "My Bookings"

### **HOW TO MANAGE BOOKINGS (Admin):**

1. **Go to Admin Panel → Table Bookings**

2. **Assign Table Number:**
   - Enter table number in input field
   - Click "Assign"

3. **Update Booking Status:**
   - Use dropdown to change status
   - Automatically saves on change
   - **Confirmed** status sends email to customer

4. **View Food Pre-Orders:**
   - Click on "X items" link
   - Expands to show all food items
   - Shows cooking notes

5. **Update Food Status:**
   - Change status from dropdown
   - Waiting → Preparing → Served

6. **Add Admin Notes:**
   - Type notes in textarea
   - Click "Save Notes"

7. **Delete Booking:**
   - Click "Delete" button
   - Confirm deletion

---

## 🔧 **TECHNICAL DETAILS:**

### **Routes:**
```php
// Customer
GET  /book-table          → booking.create
POST /book-table          → booking.store
GET  /my-bookings         → my.bookings
POST /bookings/{id}/cancel → booking.cancel

// Admin
GET    /admin/bookings              → admin.bookings.index
PATCH  /admin/bookings/{id}/status  → admin.bookings.updateStatus
DELETE /admin/bookings/{id}         → admin.bookings.destroy
```

### **Models & Relationships:**
```
Booking
  - hasMany BookingFoodOrder
  - belongsTo User

BookingFoodOrder
  - belongsTo Booking
  - belongsTo Food

Food
  - hasMany BookingFoodOrder
```

### **Email Configuration:**
Already configured in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=trsreeraj07@gmail.com
```

---

## 📧 **EMAIL FEATURES:**

Emails are sent when:
1. ✅ Customer creates booking (immediately)
2. ✅ Admin confirms booking (status change to "confirmed")

Email includes:
- Booking date & time
- Number of guests
- Table number (if assigned)
- Preferences
- Occasion
- Special requests
- **Pre-ordered food list with prices**
- **Total amount**
- Contact information

---

## 🎯 **BOOKING FLOW:**

1. Customer visits website
2. Clicks "Book Table" in navigation
3. Fills booking form + optionally selects food
4. Submits booking
5. System saves booking + food orders
6. **Confirmation email sent to customer**
7. Booking shows in "My Bookings"
8. Admin sees booking in admin panel
9. Admin assigns table number
10. Admin confirms booking
11. **Confirmation email sent again**
12. Kitchen prepares food (updates status)
13. Customer arrives
14. Food served at table
15. Admin marks as completed

---

## ✨ **KEY FEATURES:**

✅ **NOT a parcel system** - Food is for DINE-IN only
✅ **Optional food pre-order** - Can book without food
✅ **Real-time status tracking** - Both booking & food
✅ **Email notifications** - Professional templates
✅ **Admin management** - Full control over bookings
✅ **Table assignment** - Admin assigns table numbers
✅ **Cooking notes** - Special instructions supported
✅ **Mobile responsive** - Works on all devices
✅ **Secure** - Middleware protected
✅ **Validated** - Form validation on both sides

---

## 🔒 **SECURITY:**

- ✅ Admin routes protected by middleware
- ✅ CSRF protection on all forms
- ✅ User authentication required
- ✅ Form validation (client & server)
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade templating)

---

## 🧪 **TESTING CHECKLIST:**

### **Customer Side:**
- [ ] Register new account
- [ ] Book table without food
- [ ] Book table with food pre-order
- [ ] Add multiple food items
- [ ] Add cooking notes
- [ ] View My Bookings
- [ ] Cancel booking
- [ ] Receive emails

### **Admin Side:**
- [ ] View all bookings
- [ ] Assign table number
- [ ] Change booking status
- [ ] View food pre-orders
- [ ] Change food status
- [ ] Add admin notes
- [ ] Delete booking
- [ ] Check dashboard statistics

---

## 📱 **NAVIGATION:**

**Customer Navigation:**
- Home
- Menu
- **Book Table** ← NEW!
- Cart (when logged in)
- My Orders
- **My Bookings** ← NEW!
- Profile dropdown

**Admin Sidebar:**
- Dashboard
- Categories
- Food Items
- Orders
- **Table Bookings** ← NEW!

---

## 🎨 **UI/UX FEATURES:**

✅ Color-coded status badges
✅ Expandable food order sections
✅ Inline editing (table numbers)
✅ Dropdown status updates
✅ Professional email design
✅ Responsive tables
✅ Icons for visual clarity
✅ Loading indicators
✅ Success/error messages
✅ Form validation feedback

---

## 📊 **DATABASE SCHEMA:**

### **bookings table:**
- id
- user_id (FK)
- name
- email
- phone
- guests
- booking_date
- booking_time
- special_requests
- status (pending/confirmed/cancelled/completed)
- table_type
- seating_preference
- window_side
- occasion
- table_number
- admin_notes
- timestamps

### **booking_food_orders table:**
- id
- booking_id (FK)
- food_id (FK)
- quantity
- cooking_note
- food_status (waiting/preparing/served)
- timestamps

---

## 🎉 **YOU'RE ALL SET!**

The **COMPLETE RESTAURANT TABLE BOOKING WITH DINE-IN FOOD PRE-ORDER SYSTEM** is fully operational!

### **Start Testing:**
1. Make yourself admin (see instructions above)
2. Visit: `http://127.0.0.1:8000`
3. Click "Book Table"
4. Create a test booking with food
5. Check your email
6. Go to Admin Panel
7. Manage the booking

**Need Help?** Check the sections above or review the code!

---

**Built with ❤️ using Laravel, MySQL, Blade Templates & Tailwind CSS**
