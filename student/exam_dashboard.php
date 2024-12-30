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
                <button id="submitExam" class="submit-btn" onclick="submitExam()">
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
                <button onclick="confirmSubmit()" class="confirm-btn">Yes, Submit</button>
                <button onclick="closeModal()" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Questions for Math
        const mathQuestions = [
            {
                question: "If 2x + 3 = 11, what is the value of x?",
                options: ["3", "4", "5", "6"],
                correct: 1
            },
            {
                question: "What is the area of a circle with radius 5 units?",
                options: ["25π", "10π", "15π", "20π"],
                correct: 0
            },
            {
                question: "Solve for y: 3y - 7 = 14",
                options: ["5", "7", "8", "9"],
                correct: 2
            },
            {
                question: "What is the square root of 144?",
                options: ["10", "11", "12", "13"],
                correct: 2
            }
        ];

        // Questions for Computer Science
        const csQuestions = [
            {
                question: "Which data structure uses LIFO?",
                options: ["Queue", "Stack", "Array", "Tree"],
                correct: 1
            },
            {
                question: "What does CPU stand for?",
                options: ["Central Processing Unit", "Central Program Utility", "Computer Personal Unit", "Central Protocol Unit"],
                correct: 0
            },
            {
                question: "Which is not a programming language?",
                options: ["Java", "Python", "HTML", "HTTP"],
                correct: 3
            },
            {
                question: "What is the binary of decimal 8?",
                options: ["1000", "1001", "1010", "1011"],
                correct: 0
            }
        ];

        // Get student's subject
        const examSubject = "<?php echo $student['ExamSubject']; ?>";
        const questions = examSubject === "Math" ? mathQuestions : csQuestions;

        // Load questions
        function loadQuestions() {
            const container = document.getElementById('questionsContainer');
            questions.forEach((q, index) => {
                const questionHTML = `
                    <div class="question-card">
                        <h3>Question ${index + 1}</h3>
                        <p>${q.question}</p>
                        <div class="options">
                            ${q.options.map((option, i) => `
                                <div class="option">
                                    <input type="radio" id="q${index}o${i}" name="q${index}" value="${i}">
                                    <label for="q${index}o${i}">${option}</label>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                container.innerHTML += questionHTML;
            });
        }

        // Timer functionality
        function startTimer(duration) {
            let timer = duration;
            const timerDisplay = document.getElementById('timer');
            
            const countdown = setInterval(() => {
                const minutes = parseInt(timer / 60, 10);
                const seconds = parseInt(timer % 60, 10);

                timerDisplay.textContent = `Time: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (--timer < 0) {
                    clearInterval(countdown);
                    submitExam(true);
                }
            }, 1000);
        }

        // Submit exam
        function submitExam(timeUp = false) {
            if (timeUp) {
                document.getElementById('confirmModal').style.display = 'block';
                document.querySelector('.modal-content h2').textContent = "Time's Up!";
                document.querySelector('.modal-content p').textContent = "Your exam will be submitted automatically.";
                setTimeout(confirmSubmit, 2000);
            } else {
                document.getElementById('confirmModal').style.display = 'block';
            }
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        function confirmSubmit() {
            const answers = [];
            questions.forEach((_, index) => {
                const selected = document.querySelector(`input[name="q${index}"]:checked`);
                answers.push(selected ? parseInt(selected.value) : -1);
            });

            // Calculate score
            let score = 0;
            answers.forEach((answer, index) => {
                if (answer === questions[index].correct) score++;
            });

            // Send to server
            const formData = new FormData();
            formData.append('answers', JSON.stringify(answers));
            formData.append('score', score);

            fetch('submit_exam.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'result.php';
                } else {
                    alert('Error submitting exam. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting exam. Please try again.');
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadQuestions();
            startTimer(600); // 10 minutes
        });
    </script>
</body>
</html>
