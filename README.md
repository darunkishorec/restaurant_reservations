# Restaurant Reservation System

A modern, responsive restaurant reservation system with an elegant user interface and comprehensive admin dashboard.

## Features

### 🎨 **Modern User Interface**
- **Responsive Design**: Works perfectly on all devices (desktop, tablet, mobile)
- **Beautiful Hero Section**: Eye-catching landing page with call-to-action
- **Professional Navigation**: Fixed navigation bar with smooth scrolling
- **Enhanced Forms**: Two-column layout with icons and improved styling
- **Font Awesome Icons**: Visual elements throughout the interface
- **Modern Color Scheme**: Professional red and blue gradient theme

### 📱 **User Experience**
- **Smooth Scrolling**: Navigation links smoothly scroll to sections
- **Form Validation**: Client-side and server-side validation
- **Success Messages**: Beautiful confirmation screens after booking
- **Responsive Layout**: Adapts to different screen sizes
- **Interactive Elements**: Hover effects and smooth transitions

### 🔐 **Admin Dashboard**
- **Complete Reservation Management**: View all bookings in a table format
- **Real-time Statistics**: Total reservations, today's bookings, and pending count
- **Advanced Filtering**: Filter by status and date
- **Status Management**: Change reservation status (confirmed, pending, cancelled, completed)
- **Delete Reservations**: Remove unwanted bookings with confirmation
- **Responsive Table**: Works on all devices with horizontal scrolling

### 🗄️ **Database Features**
- **Reservation Tracking**: Unique reservation IDs for each booking
- **Status Management**: Multiple status options for reservations
- **Date Validation**: Prevents booking in the past
- **Data Security**: SQL injection protection with prepared statements

## File Structure

```
restaurant-reservation-system/
├── index.html          # Main reservation page with modern UI
├── admin.php           # Admin dashboard for managing reservations
├── process_reservation.php  # Backend processing for reservations
├── styles.css          # Comprehensive styling for all pages
├── restaurant_db.sql   # Database schema
└── README.md           # This documentation
```

## Setup Instructions

### 1. **Database Setup**
```sql
-- Create the database
CREATE DATABASE restaurant_reservations;

-- Use the database
USE restaurant_reservations;

-- Import the schema
SOURCE restaurant_db.sql;
```

### 2. **Server Requirements**
- PHP 7.0 or higher
- MySQL/MariaDB
- Web server (Apache/Nginx)

### 3. **Configuration**
Update database connection details in `process_reservation.php` and `admin.php`:
```php
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "restaurant_reservations";
```

### 4. **Access the System**
- **Main Page**: `index.html` - Customer reservation form
- **Admin Panel**: `admin.php` - Management dashboard

## Admin Features

### **Dashboard Overview**
- **Statistics Cards**: Quick overview of reservation counts
- **Filter System**: Filter reservations by status and date
- **Responsive Table**: View all reservation details
- **Action Buttons**: Update status and delete reservations

### **Reservation Management**
- **View Details**: Guest information, contact details, preferences
- **Status Updates**: Change reservation status instantly
- **Delete Reservations**: Remove unwanted bookings
- **Filter & Search**: Find specific reservations quickly

### **Status Options**
- **Confirmed**: Reservation is confirmed
- **Pending**: Awaiting confirmation
- **Cancelled**: Reservation cancelled
- **Completed**: Reservation fulfilled

## UI Improvements

### **Design Enhancements**
- **Modern Typography**: Segoe UI font family for better readability
- **Color Palette**: Professional red (#e74c3c) and blue (#667eea) theme
- **Shadow Effects**: Subtle shadows for depth and modern feel
- **Border Radius**: Rounded corners for contemporary look
- **Hover Effects**: Interactive elements with smooth transitions

### **Layout Improvements**
- **Grid System**: Two-column form layout for better organization
- **Spacing**: Consistent padding and margins throughout
- **Visual Hierarchy**: Clear distinction between sections
- **Mobile Optimization**: Responsive design for all screen sizes

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers

## Security Features

- **SQL Injection Protection**: Prepared statements and escaping
- **Input Validation**: Server-side validation of all inputs
- **XSS Prevention**: HTML escaping for user-generated content
- **CSRF Protection**: Form-based security measures

## Future Enhancements

- [ ] Email confirmation system
- [ ] SMS notifications
- [ ] Calendar integration
- [ ] Payment processing
- [ ] Customer accounts
- [ ] Reservation reminders
- [ ] Analytics dashboard
- [ ] Multi-language support

## Contributing

Feel free to submit issues, feature requests, or pull requests to improve the system.

## License

This project is open source and available under the MIT License. 