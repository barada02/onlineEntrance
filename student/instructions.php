<?php
session_start();
require_once '../includes/config.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.html?error=" . urlencode("Please login to access the instructions"));
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
    <title>Exam Instructions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .instructions-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .instruction-list {
            margin-bottom: 30px;
        }
        .instruction-list li {
            margin-bottom: 15px;
            color: #34495e;
        }
        .ready-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .checkbox-container {
            margin-bottom: 20px;
        }
        #startExam {
            background-color: #2ecc71;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            display: none;
        }
        #startExam:hover {
            background-color: #27ae60;
        }
        #startExam:disabled {
            background-color: #bdc3c7;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="instructions-container">
        <h1>Exam Instructions</h1>
        
        <div class="instruction-list">
            <h3>Please read the following instructions carefully:</h3>
            <ol>
                <li>The exam consists of multiple-choice questions.</li>
                <li>Each question has only one correct answer.</li>
                <li>You cannot go back to previous questions once answered.</li>
                <li>The exam is time-bound. Make sure to complete within the allocated time.</li>
                <li>Do not refresh the page during the exam.</li>
                <li>Ensure you have a stable internet connection.</li>
                <li>Any form of malpractice will result in immediate disqualification.</li>
                <li>Click submit only when you have completed all questions.</li>
            </ol>
        </div>

        <div class="ready-section">
            <div class="checkbox-container">
                <input type="checkbox" id="readyCheck">
                <label for="readyCheck">I have read and understood all the instructions</label>
            </div>
            <button id="startExam" disabled>Start Exam</button>
        </div>
    </div>

    <script>
        const readyCheck = document.getElementById('readyCheck');
        const startExamBtn = document.getElementById('startExam');

        readyCheck.addEventListener('change', function() {
            startExamBtn.style.display = this.checked ? 'inline-block' : 'none';
            startExamBtn.disabled = !this.checked;
        });

        startExamBtn.addEventListener('click', function() {
            window.location.href = 'exam_dashboard.php';
        });
    </script>
</body>
</html>
