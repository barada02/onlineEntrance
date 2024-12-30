<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['student_id']) || !isset($_SESSION['exam_completed'])) {
    header("Location: student_login.html");
    exit();
}

// Fetch student details
$student_id = $_SESSION['student_id'];
$query = "SELECT sr.*, r.EntranceMark, r.IsPass, r.Percentage 
          FROM StudentRegistration sr 
          JOIN Results r ON sr.StudentID = r.StudentID 
          WHERE sr.StudentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function handleLogout() {
            window.location.href = '../index.html';
        }
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .result-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .result-header {
            margin-bottom: 30px;
        }

        .result-header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .student-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }

        .info-item {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .result-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .result-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .result-card i {
            font-size: 24px;
            margin-bottom: 10px;
            color: #3498db;
        }

        .result-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 10px 0;
        }

        .result-card .label {
            color: #7f8c8d;
        }

        .status {
            font-size: 24px;
            font-weight: bold;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .status.pass {
            background: #d4edda;
            color: #155724;
        }

        .status.fail {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        @media (max-width: 480px) {
            .result-details {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="result-container">
        <div class="result-header">
            <h1>Exam Results</h1>
            <p>Here are your entrance exam results</p>
        </div>

        <div class="student-info">
            <div class="info-item">
                <strong>Name:</strong> <?php echo htmlspecialchars($student['Name']); ?>
            </div>
            <div class="info-item">
                <strong>Subject:</strong> <?php echo htmlspecialchars($student['ExamSubject']); ?>
            </div>
            <div class="info-item">
                <strong>Student ID:</strong> <?php echo htmlspecialchars($student['StudentID']); ?>
            </div>
        </div>

        <div class="result-details">
            <div class="result-card">
                <i class="fas fa-star"></i>
                <div class="value"><?php echo number_format($student['EntranceMark'], 1); ?></div>
                <div class="label">Score</div>
            </div>
            <div class="result-card">
                <i class="fas fa-percent"></i>
                <div class="value"><?php echo number_format($student['Percentage'], 1); ?>%</div>
                <div class="label">Percentage</div>
            </div>
        </div>

        <div class="status <?php echo $student['IsPass'] ? 'pass' : 'fail'; ?>">
            <?php if ($student['IsPass']): ?>
                <i class="fas fa-check-circle"></i> Congratulations! You Passed
            <?php else: ?>
                <i class="fas fa-times-circle"></i> Sorry, You Did Not Pass
            <?php endif; ?>
        </div>

        <div class="action-buttons">
            <button onclick="handleLogout()" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </div>
</body>
</html>
