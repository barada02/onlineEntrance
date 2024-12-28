# Online Entrance Exam Application

A comprehensive web-based entrance examination system built using HTML, CSS, JavaScript, PHP, and MySQL with XAMPP server.

## Features

- Admin and Student login portals
- Student registration system
- Exam instructions page with confirmation
- Online examination platform
- Result management system
- Subject-wise exam categories (Math and Computer Science)
- Real-time exam progress tracking
- Secure session management

## Project Structure
```
onlineEntrance/
├── admin/                 # Admin portal files
├── includes/             # Configuration and common files
├── student/              # Student portal files
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   ├── student_login.html
│   ├── process_login.php
│   ├── instructions.php
│   ├── exam_dashboard.php
│   └── result.php
└── README.md
```

## Prerequisites

- XAMPP (with PHP 7.4 or higher)
- MySQL 5.7 or higher
- Web browser (Chrome/Firefox recommended)
- Git (for cloning the repository)

## Database Schema
database: onlineentrance

### AdminCredentials Table
```sql
CREATE TABLE AdminCredentials (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
);
```

### StudentRegistration Table
```sql
CREATE TABLE StudentRegistration (
    StudentID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    FatherName VARCHAR(100) NOT NULL,
    DOB DATE NOT NULL,
    Qualification VARCHAR(50) NOT NULL,
    ExamSubject ENUM('Math', 'CS') NOT NULL
);
```

### StudentCredentials Table
```sql
CREATE TABLE StudentCredentials (
    StudentCredentialID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT NOT NULL,
    Username VARCHAR(50) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    FOREIGN KEY (StudentID) REFERENCES StudentRegistration(StudentID)
);
```

### Results Table
```sql
CREATE TABLE Results (
    ResultID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT NOT NULL,
    EntranceMark FLOAT NOT NULL,
    IsPass BOOLEAN NOT NULL,
    Percentage FLOAT NOT NULL,
    FOREIGN KEY (StudentID) REFERENCES StudentRegistration(StudentID)
);
```

### Sample Data Queries
```sql
-- Insert admin users
INSERT INTO AdminCredentials (Username, Password) 
VALUES 
('Roopa.rani', '$2y$10$6WQtfiPZHpW6j9/gWmYiAOBEHu8EI6ADTDJOsk0vclBElO7t5ML1W'), 
('Admin.user', '$2y$10$0oZHSUYwheT3/K/k/zW6suUw.oq.R0TcjEIUxF.TPGQjiv4asTJCS');

-- Insert sample students
INSERT INTO StudentRegistration (Name, FatherName, DOB, Qualification, ExamSubject)
VALUES 
('Rahul Sharma', 'Amit Sharma', '2000-05-15', 'B.Sc', 'Math'),
('Priya Nair', 'Rajesh Nair', '1998-12-10', 'B.Tech', 'CS');

-- Insert student credentials
INSERT INTO StudentCredentials (StudentID, Username, Password)
VALUES 
(1, 'rahul.sharma', '$2y$10$YOZp6QU87eIIK9sThZKu2.f7kDTp6JkEqxWFiBKHtQlxBMfXyadbC'),
(2, 'priya.nair', '$2y$10$YOZp6QU87eIIK9sThZKu2.f7kDTp6JkEqxWFiBKHtQlxBMfXyadbC');
```

## Installation Guide

1. **Clone the Repository**
   ```bash
   git clone https://github.com/barada02/onlineEntrance.git
   
   ```

2. **Setup XAMPP**
   - Install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Start Apache and MySQL services from XAMPP Control Panel

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named 'onlineentrance'
   - Import the SQL schema provided above
   - Configure database connection in `includes/config.php`

4. **Project Setup**
   - Move the project folder to `xampp/htdocs/`
   - Ensure proper file permissions
   - Configure any environment-specific settings

5. **Access the Application**
   - Admin Portal: `http://localhost/onlineEntrance/admin/`
   - Student Portal: `http://localhost/onlineEntrance/student/student_login.html`

## Default Credentials

### Admin Users
```
Username: Roopa.rani
Password: Roopa@rani    

Username: Admin.user
Password: Admin123
```

### Student Users
```
Username: rahul.sharma
Password: Student

Username: priya.nair
Password: Student
```

## User Flow

1. **Student Registration**
   - New students register through the registration portal
   - System generates unique credentials

2. **Student Login**
   - Students login using their credentials
   - Redirected to instructions page

3. **Exam Instructions**
   - Students must read and accept instructions
   - Click checkbox to confirm
   - Start exam button appears

4. **Exam Process**
   - Timer starts when exam begins
   - Questions displayed one at a time
   - Auto-submission when time expires

5. **Results**
   - Immediate result display after submission
   - Results stored in database

## Security Features

- Password hashing using bcrypt
- Session management
- SQL injection prevention
- XSS protection
- CSRF protection
- Input validation and sanitization

## Development Guidelines

1. **Code Structure**
   - Follow MVC-like pattern
   - Keep configuration in separate files
   - Use prepared statements for database queries

2. **Security**
   - Always validate and sanitize user inputs
   - Use password hashing for storing credentials
   - Implement proper session management

3. **Database**
   - Use foreign key constraints
   - Index frequently queried columns
   - Regular backups recommended

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Troubleshooting

1. **Database Connection Issues**
   - Check MySQL service status in XAMPP
   - Verify database credentials in config.php
   - Ensure proper database permissions

2. **Session Issues**
   - Check PHP session configuration
   - Clear browser cache and cookies
   - Verify session storage permissions

3. **File Permission Issues**
   - Set appropriate read/write permissions
   - Check XAMPP user permissions

## License

This project is licensed under the MIT License - see the LICENSE file for details

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.
