<?php
require_once 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $year = intval($_POST["year"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $security_question = trim($_POST["security_question"]);
    $security_answer = trim($_POST["security_answer"]);

    if (empty($name) || empty($email) || empty($year) || empty($password) || empty($confirm_password) || empty($security_question) || empty($security_answer)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 12) {
        $error = "Password must be at least 12 characters long!";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered!";
        } else {
            // Hash the password and security answer (answer in lower case for consistency during matching)
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $hashed_answer = password_hash(strtolower($security_answer), PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO students (name, email, year, password, security_question, security_answer) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $year, $hashed_password, $security_question, $hashed_answer])) {
                $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Portal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .weak-label { color: #fca5a5; font-size: 0.8rem; margin-top: 4px; display: none; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <div class="glass-card">
            <h2>Create an Account</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php else: ?>

            <form action="register.php" method="POST" id="regForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email address" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="year">Academic Year</label>
                    <input type="number" id="year" name="year" required min="1" max="6" placeholder="E.g., 1 for Freshmen" value="<?php echo isset($_POST['year']) ? htmlspecialchars($_POST['year']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Must be at least 12 characters">
                    <div id="pwdCheck" class="weak-label">Password must be at least 12 characters.</div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter your password">
                </div>

                <div class="form-group">
                    <label for="security_question">Security Question</label>
                    <select id="security_question" name="security_question" required>
                        <option value="">Select a security question</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What was the model of your first car?">What was the model of your first car?</option>
                        <option value="In what city were you born?">In what city were you born?</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="security_answer">Security Answer</label>
                    <input type="text" id="security_answer" name="security_answer" required placeholder="Your answer (will be securely encrypted)">
                </div>
                
                <button type="submit">Complete Registration</button>
            </form>
            
            <?php endif; ?>
            <div class="links">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('password').addEventListener('input', function(e) {
            const pwdAlert = document.getElementById('pwdCheck');
            if(e.target.value.length > 0 && e.target.value.length < 12) {
                pwdAlert.style.display = 'block';
            } else {
                pwdAlert.style.display = 'none';
            }
        });
    </script>
</body>
</html>
