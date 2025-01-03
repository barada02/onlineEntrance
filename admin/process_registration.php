<?php
session_start();
require_once '../includes/config.php';

// Set JSON header
header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to access this page']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Start transaction
        $conn->begin_transaction();

        // Get form data
        $name = mysqli_real_escape_string($conn, $_POST['fullname']);
        $fatherName = mysqli_real_escape_string($conn, $_POST['fathername']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
        $subject = mysqli_real_escape_string($conn, $_POST['subject']);
        
        // Validate required fields
        if (empty($name) || empty($fatherName) || empty($dob) || empty($qualification) || empty($subject) || empty($email)) {
            throw new Exception("All fields are required");
        }

        // Insert student registration
        $insert_query = "INSERT INTO StudentRegistration (Name, FatherName, DOB, Email, Qualification, ExamSubject) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        $stmt->bind_param("ssssss", $name, $fatherName, $dob, $email, $qualification, $subject);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to register student: " . $stmt->error);
        }

        $studentId = $conn->insert_id;
        $stmt->close();
        $stmt = null; // Clear the statement after closing

        // Get credentials
        $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : null;
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : null;

        if ($username && $password) {
            // Insert credentials
            $insert_cred = "INSERT INTO StudentCredentials (StudentID, Username, Password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_cred);
            if (!$stmt) {
                throw new Exception("Database error: " . $conn->error);
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("iss", $studentId, $username, $hashedPassword);

            if (!$stmt->execute()) {
                throw new Exception("Failed to create credentials: " . $stmt->error);
            }
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Student registered successfully',
            'studentId' => $studentId,
            'email' => $email
        ]);

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }

    // Only close if statement exists and hasn't been closed
    if (isset($stmt) && $stmt !== null) {
        $stmt->close();
    }
    if ($conn) {
        $conn->close();
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
