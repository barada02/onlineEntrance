<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

try {
    $student_id = $_SESSION['student_id'];
    $answers = json_decode($_POST['answers'], true);
    $score = intval($_POST['score']);
    
    // Calculate percentage (4 questions, each worth 25%)
    $percentage = ($score / 4) * 100;
    
    // Determine if passed (passing mark 50%)
    $is_pass = $percentage >= 50 ? 1 : 0;
    
    // Insert exam result
    $query = "INSERT INTO Results (StudentID, EntranceMark, IsPass, Percentage) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iddd", $student_id, $score, $is_pass, $percentage);
    
    if ($stmt->execute()) {
        $_SESSION['exam_completed'] = true;
        $_SESSION['exam_score'] = $score;
        $_SESSION['exam_percentage'] = $percentage;
        $_SESSION['exam_pass'] = $is_pass;
        
        echo json_encode([
            'success' => true,
            'score' => $score,
            'percentage' => $percentage,
            'passed' => $is_pass == 1
        ]);
    } else {
        throw new Exception("Failed to save exam results");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
