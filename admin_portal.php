<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: dashboard.php");
    exit();
}

$stmt = $pdo->query("SELECT id, name, email, year, is_admin FROM students ORDER BY year ASC, name ASC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Student Registration</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .badge { background: var(--primary); padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-left: 10px; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="glass-card">
            <div class="header-action">
                <h2>Admin Portal Central</h2>
                <div>
                    <form action="dashboard.php" method="GET" style="display:inline;">
                        <button type="submit" style="padding: 0.5rem 1rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3);">Back to Profile</button>
                    </form>
                    <form action="logout.php" method="POST" style="display:inline;">
                        <button type="submit" style="padding: 0.5rem 1rem;">Logout</button>
                    </form>
                </div>
            </div>
            
            <p>Here is the directory of all registered students:</p>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Academic Year</th>
                            <th>User Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['year']); ?></td>
                            <td><?php echo $student['is_admin'] ? '<b>Admin</b>' : 'Student'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>
