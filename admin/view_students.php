<?php
session_start();
require_once '../includes/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

// Fetch all students with their exam results
$query = "SELECT sr.*, 
          COALESCE(r.EntranceMark, 'Not Attempted') as Score,
          COALESCE(r.Percentage, 'N/A') as Percentage,
          CASE WHEN r.IsPass = 1 THEN 'Pass' 
               WHEN r.IsPass = 0 THEN 'Fail'
               ELSE 'Not Attempted' END as Status
          FROM StudentRegistration sr
          LEFT JOIN Results r ON sr.StudentID = r.StudentID
          ORDER BY sr.StudentID DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header h1 {
            color: #2c3e50;
        }

        .back-btn {
            padding: 8px 15px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-btn:hover {
            background: #2980b9;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .students-table th,
        .students-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .students-table th {
            background: #f8f9fa;
            color: #2c3e50;
        }

        .students-table tr:hover {
            background: #f8f9fa;
        }

        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
        }

        .status.pass {
            background: #d4edda;
            color: #155724;
        }

        .status.fail {
            background: #f8d7da;
            color: #721c24;
        }

        .status.not-attempted {
            background: #e2e3e5;
            color: #383d41;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .students-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-users"></i> Student Records</h1>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by name, ID, or subject..." onkeyup="searchTable()">
        </div>

        <table class="students-table" id="studentsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Father's Name</th>
                    <th>DOB</th>
                    <th>Qualification</th>
                    <th>Subject</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $statusClass = '';
                        if ($row['Status'] === 'Pass') {
                            $statusClass = 'pass';
                        } elseif ($row['Status'] === 'Fail') {
                            $statusClass = 'fail';
                        } else {
                            $statusClass = 'not-attempted';
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['StudentID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['FatherName']); ?></td>
                            <td><?php echo htmlspecialchars($row['DOB']); ?></td>
                            <td><?php echo htmlspecialchars($row['Qualification']); ?></td>
                            <td><?php echo htmlspecialchars($row['ExamSubject']); ?></td>
                            <td><?php echo htmlspecialchars($row['Score']); ?></td>
                            <td><?php echo htmlspecialchars($row['Percentage']); ?>%</td>
                            <td><span class="status <?php echo $statusClass; ?>"><?php echo $row['Status']; ?></span></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No students found</td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('studentsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let showRow = false;
                const cells = rows[i].getElementsByTagName('td');
                
                for (let cell of cells) {
                    const text = cell.textContent || cell.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        showRow = true;
                        break;
                    }
                }
                
                rows[i].style.display = showRow ? '' : 'none';
            }
        }
    </script>
</body>
</html>
