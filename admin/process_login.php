<?php
session_start();

// Include database configuration
require_once '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT AdminID, Username, Password FROM AdminCredentials WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['admin_id'] = $row['AdminID'];
            $_SESSION['admin_username'] = $row['Username'];
            header("Location: dashboard.php?success=" . urlencode("Login successful! Welcome " . $row['Username']));
            exit();
        } else {
            header("Location: admin_login.html?error=" . urlencode("Invalid password"));
            exit();
        }
    } else {
        header("Location: admin_login.html?error=" . urlencode("Invalid username"));
        exit();
    }
    
    $stmt->close();
}

// Close the database connection
closeConnection($conn);
?>
