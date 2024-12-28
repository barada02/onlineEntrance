<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html?error=" . urlencode("Please login to access the dashboard"));
    exit();
}

require_once '../includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Entrance Exam</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="css/dashboard_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="admin-info">
                <i class="fas fa-user-shield"></i>
                <h2>Admin Panel</h2>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
            </div>
            <nav class="dashboard-nav">
                <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="student_registration.html"><i class="fas fa-user-plus"></i> Register Student</a>
                <a href="view_students.php"><i class="fas fa-users"></i> View Students</a>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div id="notification" class="notification" style="display: none;">
                <i class="notification-icon"></i>
                <span class="notification-message"></span>
            </div>

            <div class="dashboard-header">
                <h1>Dashboard Overview</h1>
                <div class="date-time"><?php echo date('l, F j, Y'); ?></div>
            </div>

            <div class="action-cards">
                <div class="action-card">
                    <i class="fas fa-user-plus"></i>
                    <h2>Register New Student</h2>
                    <p>Add a new student to the entrance exam system</p>
                    <a href="student_registration.html" class="action-btn">Register Student</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-users"></i>
                    <h2>View Students</h2>
                    <p>View and manage registered students</p>
                    <a href="view_students.php" class="action-btn">View Students</a>
                </div>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <i class="fas fa-user-graduate"></i>
                    <div class="stat-info">
                        <h3>Total Students</h3>
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM StudentRegistration");
                        $row = $result->fetch_assoc();
                        ?>
                        <p><?php echo $row['count']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const icon = notification.querySelector('.notification-icon');
            const messageSpan = notification.querySelector('.notification-message');
            
            if (type === 'success') {
                icon.className = 'notification-icon fas fa-check-circle';
                notification.className = 'notification success';
            } else if (type === 'error') {
                icon.className = 'notification-icon fas fa-exclamation-circle';
                notification.className = 'notification error';
            }
            
            messageSpan.textContent = message;
            notification.style.display = 'flex';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        const success = urlParams.get('success');
        
        if (error) {
            showNotification(decodeURIComponent(error), 'error');
        } else if (success) {
            showNotification(decodeURIComponent(success), 'success');
        }
    });
    </script>
</body>
</html>
