<?php
session_start();

// Include database configuration
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT sc.StudentCredentialID, sc.StudentID, sc.Username, sc.Password, sr.Name 
            FROM StudentCredentials sc 
            JOIN StudentRegistration sr ON sc.StudentID = sr.StudentID 
            WHERE sc.Username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['student_id'] = $row['StudentID'];
            $_SESSION['student_username'] = $row['Username'];
            $_SESSION['student_name'] = $row['Name'];
            header("Location: instructions.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
            header("Location: student_login.html");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid username";
        header("Location: student_login.html");
        exit();
    }
    
    $stmt->close();
}

// Close the database connection
closeConnection($conn);
?>
