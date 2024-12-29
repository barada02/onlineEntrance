<?php
session_start();
require_once '../includes/config.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.html?error=" . urlencode("Please login to access the exam"));
    exit();
}

// Fetch student details
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM StudentRegistration WHERE StudentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header("Location: student_login.html?error=" . urlencode("Student not found"));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Entrance Exam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/exam_style.css">
</head>
<body>
    <div class="exam-container">
        <!-- Left Panel - Student Info -->
        <div class="student-panel">
            <div class="student-info">
                <div class="profile-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h2><?php echo htmlspecialchars($student['Name']); ?></h2>
                <div class="info-item">
                    <i class="fas fa-id-card"></i>
                    <span>ID: <?php echo htmlspecialchars($student['StudentID']); ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-book"></i>
                    <span>Subject: <?php echo htmlspecialchars($student['ExamSubject']); ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span id="timer">Time: 10:00</span>
                </div>
            </div>
            <div class="action-buttons">
                <button id="submitExam" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> Submit Exam
                </button>
                <a href="student_logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Main Panel - Questions -->
        <div class="main-panel">
            <div class="exam-header">
                <h1><?php echo htmlspecialchars($student['ExamSubject']); ?> Entrance Exam</h1>
                <p>Please select the correct answer for each question.</p>
            </div>

            <form id="examForm">
                <div id="questionsContainer">
                    <!-- Questions will be loaded here by JavaScript -->
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Submission</h2>
            <p>Are you sure you want to submit your exam?</p>
            <div class="modal-buttons">
                <button class="confirm-btn">Yes, Submit</button>
                <button class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Pass PHP variables to JavaScript -->
    <script>
        const examSubject = "<?php echo $student['ExamSubject']; ?>";
    </script>
    <script src="js/exam.js"></script>
</body>
</html>
