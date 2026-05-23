# URides - University Transportation Management System

A comprehensive web-based transportation management system designed to streamline commuting between Notunbazar and university campus. URides provides sustainable, affordable, and efficient transportation solutions for students through ride-sharing, shuttle services, cycle rentals, and emergency assistance.

## 🚀 Features

### Core Services
- **Ride Sharing**: Connect students for cost-effective rickshaw and bike rides
- **Shuttle Service**: Real-time GPS tracking of university shuttle buses
- **Cycle Rental**: Eco-friendly bike rentals with student ID verification
- **Emergency Assistance**: Quick help for vehicle breakdowns with nearby garage locator

### User Management
- **Authentication System**: Secure login/registration with role-based access
- **Profile Management**: User profiles with photo uploads and contact information
- **User Types**: Riders and requesters with different permissions
- **Password Recovery**: Forgot password functionality with email verification

### Communication
- **Real-time Messaging**: In-app chat system for user communication
- **Contact Forms**: Direct contact with support team
- **Request Management**: Track and manage ride requests

### Additional Features
- **Responsive Design**: Mobile-friendly interface using Bootstrap
- **Real-time Updates**: Live status tracking for rides and shuttles
- **Review System**: Rate and review ride experiences
- **Garage Database**: Comprehensive garage information for emergency assistance

## 🛠️ Technology Stack

### Backend
- **PHP 8.0+**: Server-side scripting and business logic
- **MySQL 10.4+**: Database management system
- **Apache/XAMPP**: Web server environment

### Frontend
- **HTML5**: Semantic markup structure
- **CSS3**: Styling with custom and Bootstrap frameworks
- **JavaScript**: Interactive features and client-side validation
- **Bootstrap 4.4**: Responsive UI framework
- **Font Awesome 5.15**: Icon library

### Libraries & Dependencies
- **jQuery 3.4.1**: JavaScript library for DOM manipulation
- **Owl Carousel**: Image/content slider
- **Tempus Dominus**: Date/time picker component
- **Google Fonts**: Typography (Oswald & Rubik families)

## 📁 Project Structure

```
DBMS Project/
├── 📄 Core PHP Files
│   ├── index.php          # Landing page
│   ├── home.php           # Dashboard (authenticated users)
│   ├── login.php          # User login
│   ├── signIn.php         # User registration
│   ├── connect.php        # Database connection
│   └── logout.php         # Session termination
│
├── 🚗 Service Modules
│   ├── rideShare.php      # Ride sharing functionality
│   ├── shuttle.php        # Shuttle service tracking
│   ├── booking.php        # Cycle rental system
│   └── requests.php       # Request management
│
├── 💬 Communication
│   ├── chat_inbox.php     # Messaging interface
│   ├── chat.php           # Chat functionality
│   ├── send_message.php   # Message processing
│   └── get-chat.php       # Chat data retrieval
│
├── 👤 User Management
│   ├── profileDropdown.php # Profile menu
│   ├── update_profile.php  # Profile updates
│   ├── changePassword.php  # Password modification
│   ├── forgotPassword.php  # Password recovery
│   ├── reset-code.php      # Reset code verification
│   └── resetPassword.php   # Password reset
│
├── 📧 Support
│   ├── contact.php         # Contact form
│   ├── about.php           # About page
│   └── data.php            # Data handling
│
├── 🎨 Assets
│   ├── css/               # Stylesheets
│   │   ├── bootstrap.min.css
│   │   ├── style.css
│   │   ├── loginStyle.css
│   │   ├── profileStyle.css
│   │   └── regStyle.css
│   ├── js/                # JavaScript files
│   │   ├── main.js
│   │   └── script.js
│   ├── lib/               # Third-party libraries
│   ├── scss/              # SCSS source files
│   ├── img/               # Images
│   ├── uploads/           # User uploads
│   └── mail/              # Email templates
│
├── 🗄️ Database
│   └── urides.sql         # Database schema and data
│
└── 📋 Components
    ├── topbar.php         # Header navigation
    └── footer.php         # Footer content
```

## 🗄️ Database Schema

### Core Tables

#### `users`
User authentication and profile information
- `user_id` (Primary Key)
- `name`, `email`, `password`
- `student_id`, `phone`, `user_type`
- `profile_photo`, `bike_number`

#### `rides`
Ride sharing requests and management
- `ride_id` (Primary Key)
- `user_id`, `ride_type` (rickshaw/bike)
- `start_location`, `end_location`
- `ride_date`, `ride_time`, `status`

#### `cycles`
Cycle rental inventory
- `cycle_id` (Primary Key)
- `location`, `is_available`

#### `cycle_rentals`
Rental transaction records
- `rental_id` (Primary Key)
- `user_id`, `cycle_id`
- `pickup_time`, `return_time`, `status`

#### `shuttles` & `shuttle_tracking`
Shuttle service management with GPS tracking
- Real-time location tracking
- Schedule management
- Driver information

#### `messages`
In-app communication system
- `sender_id`, `receiver_id`
- `message_content`, `timestamp`

#### `garages`
Emergency assistance database
- `garage_id`, `name`, `address`
- `phone`, `services_offered`

### Supporting Tables
- `contacts`: Support form submissions
- `ride_reviews`: User feedback system
- `declined_requests`: Request rejection tracking
- `shuttle_schedules`: Shuttle timing management

## 🚀 Installation & Setup

### Prerequisites
- XAMPP/WAMP/MAMP (Apache + MySQL + PHP)
- PHP 8.0 or higher
- MySQL 10.4 or higher
- Modern web browser

### Step-by-Step Installation

1. **Download & Extract**
   ```bash
   # Clone or download the project
   # Extract to your web server directory (e.g., htdocs/)
   ```

2. **Database Setup**
   ```sql
   # Create database
   CREATE DATABASE urides;
   
   # Import the database schema
   # Use phpMyAdmin to import urides.sql
   ```

3. **Configure Database Connection**
   ```php
   # Edit connect.php with your database credentials
   $server = "localhost";
   $username = "root";
   $password = "";
   $database = "urides";
   ```

4. **Set File Permissions**
   ```bash
   # Ensure write permissions for uploads directory
   chmod 755 uploads/
   ```

5. **Start Server**
   ```bash
   # Start Apache and MySQL via XAMPP control panel
   # Access: http://localhost/DBMS%20Project/
   ```

### Default Credentials
- **Email**: arnab0574@gmail.com
- **Password**: 12345

## 🎯 Usage Guide

### For Students
1. **Registration**: Create account with student ID verification
2. **Profile Setup**: Upload photo and add contact information
3. **Request Rides**: Book rickshaw/bike rides with pickup/drop locations
4. **Track Shuttles**: Monitor real-time shuttle locations
5. **Rent Cycles**: Borrow bikes using student ID
6. **Emergency Help**: Access nearby garage information

### For Admin
1. **User Management**: Monitor user registrations and activities
2. **Request Processing**: Approve/decline ride requests
3. **Fleet Management**: Update shuttle and cycle availability
4. **Support Handling**: Respond to user inquiries

## 🔧 Configuration

### Database Settings
Update `connect.php` with your database credentials:
```php
$server = "localhost";        // Database server
$username = "root";           // Database username
$password = "";               // Database password
$database = "urides";         // Database name
```

### Email Configuration
Configure SMTP settings in mail/ directory for password recovery:
- Update email templates
- Set SMTP credentials
- Configure email routing

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Verify MySQL service is running
   - Check database credentials in `connect.php`
   - Ensure database `urides` exists

2. **File Upload Issues**
   - Check `uploads/` directory permissions
   - Verify PHP upload limits in `php.ini`
   - Ensure sufficient disk space

3. **Session Problems**
   - Check session save path permissions
   - Verify session_start() is called on required pages
   - Clear browser cookies if needed

4. **CSS/JS Not Loading**
   - Verify file paths in HTML headers
   - Check web server configuration
   - Clear browser cache

### Debug Mode
Enable error reporting for development:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 🔒 Security Considerations

### Implemented Security Measures
- **SQL Injection Prevention**: Using prepared statements
- **Session Management**: Secure session handling
- **Input Validation**: Form data sanitization
- **File Upload Security**: File type and size restrictions

### Recommended Enhancements
- Implement HTTPS/SSL certificates
- Add CSRF protection tokens
- Enhance password hashing (bcrypt)
- Implement rate limiting for login attempts
- Add input validation on client and server side

## 🤝 Contributing

### Development Guidelines
1. Follow PHP coding standards (PSR-12)
2. Use semantic HTML5 markup
3. Implement responsive design principles
4. Write clean, commented code
5. Test thoroughly before deployment

### Git Workflow
```bash
# Create feature branch
git checkout -b feature/new-feature

# Commit changes
git add .
git commit -m "Add new feature description"

# Push and create pull request
git push origin feature/new-feature
```

## 📝 License

This project is developed for educational purposes. Please refer to the license file for usage terms and conditions.

## 📞 Support & Contact

### Project Team
- **Mubasshir Ahmed** - Lead Developer
- **Ahasan Arafath** - Backend Developer
- **Noman Hossain** - Frontend Developer


## 🔄 Version History

### Version 1.0.0 (Current)
- Initial release with core functionality
- User authentication and profile management
- Ride sharing and shuttle services
- Cycle rental system
- Emergency assistance features
- Real-time messaging system

### Planned Features
- Mobile app development
- Advanced analytics dashboard
- Payment gateway integration
- Multi-language support
- Enhanced security features

---

## 🙏 Acknowledgments

- **Bootstrap Team** - For the responsive UI framework
- **Font Awesome** - For the icon library
- **jQuery Foundation** - For JavaScript utilities
- **Google Fonts** - For typography resources
- **XAMPP Team** - For the development environment

---

**URides** - Making university commuting smarter, greener, and more connected! 🚲🚌🚗
