<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT id, name, email, year FROM students ORDER BY year ASC, name ASC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container dashboard-container">
        <div class="glass-card">
            <div class="header-action">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></h2>
                <form action="logout.php" method="POST" style="display:inline;">
                    <button type="submit" style="padding: 0.5rem 1rem;">Logout</button>
                </form>
            </div>
            
            <p>Here is the directory of all registered students:</p>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Year/Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['year']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No students found in the database.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
