# Online Entrance Exam Application

A simple web-based entrance examination system built using HTML, CSS, JavaScript, and MySQL with XAMPP server.

## Features

- Admin and Student login portals
- Student registration system
- Online examination platform
- Result management system
- Subject-wise exam categories (Math and Computer Science)

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
'''

### Default Admin and Student Credentials
```sql
INSERT INTO AdminCredentials (Username, Password) VALUES ('Roopa.rani', '$2y$10$6WQtfiPZHpW6j9/gWmYiAOBEHu8EI6ADTDJOsk0vclBElO7t5ML1W'), 
('Admin.user', '$2y$10$0oZHSUYwheT3/K/k/zW6suUw.oq.R0TcjEIUxF.TPGQjiv4asTJCS');
'''
username: Roopa.rani
password: Roopa@rani    

username: Admin.user
password: Admin123

-- Insert rows into StudentRegistration
INSERT INTO StudentRegistration (Name, FatherName, DOB, Qualification, ExamSubject)
VALUES 
('Rahul Sharma', 'Amit Sharma', '2000-05-15', 'B.Sc', 'Math'),
('Priya Nair', 'Rajesh Nair', '1998-12-10', 'B.Tech', 'CS');


-- Insert rows into StudentCredentials
INSERT INTO StudentCredentials (StudentID, Username, Password)
VALUES 
(1, 'rahul.sharma', '$2y$10$YOZp6QU87eIIK9sThZKu2.f7kDTp6JkEqxWFiBKHtQlxBMfXyadbC'),
(2, 'priya.nair', '$2y$10$YOZp6QU87eIIK9sThZKu2.f7kDTp6JkEqxWFiBKHtQlxBMfXyadbC');

username: rahul.sharma
password: Student

username: priya.nair
password: Student

## Setup Instructions

1. Install XAMPP server on your system
2. Clone this repository to your htdocs folder
3. Import the database schema into MySQL
4. Access the application through your web browser at localhost/onlineEntrance

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- XAMPP Server
