<?php
require_once 'db.php';

$error = '';
$success = '';
$step = 1;
$email = '';
$question = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['step_1'])) {
        $email = trim($_POST["email"]);
        if (empty($email)) {
            $error = "Please enter your email.";
        } else {
            $stmt = $pdo->prepare("SELECT security_question FROM students WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && !empty($user['security_question'])) {
                $step = 2;
                $question = $user['security_question'];
            } else {
                $error = "No account found with that email, or no security question set.";
            }
        }
    } elseif (isset($_POST['step_2'])) {
        $step = 2; // Keep in step 2 by default if validation fails
        $email = trim($_POST["email"]);
        $question = $_POST["question"];
        $answer = trim($_POST["answer"]);
        $new_password = $_POST["new_password"];

        if (empty($answer) || empty($new_password)) {
            $error = "Please provide an answer and a new password.";
        } elseif (strlen($new_password) < 12) {
            $error = "New password must be at least 12 characters long.";
        } else {
            // Verify the answer
            $stmt = $pdo->prepare("SELECT id, security_answer FROM students WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify(strtolower($answer), $user['security_answer'])) {
                // Correct answer, update password
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_stmt = $pdo->prepare("UPDATE students SET password = ? WHERE id = ?");
                if ($update_stmt->execute([$hashed_password, $user['id']])) {
                    $success = "Password successfully reset! You can now <a href='login.php'>log in</a>.";
                    $step = 3; // Finished step
                } else {
                    $error = "Error updating password.";
                }
            } else {
                $error = "Incorrect security answer.";
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
    <title>Recover Password - Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="glass-card">
            <h2>Account Recovery</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($step == 1): ?>
            <!-- Phase 1: Provide Email -->
            <form action="recover.php" method="POST">
                <input type="hidden" name="step_1" value="1">
                <div class="form-group">
                    <label for="email">Enter your registered email address</label>
                    <input type="email" id="email" name="email" required placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <button type="submit">Verify Email</button>
            </form>
            <div class="links">
                Remember your password? <a href="login.php">Log in</a>
            </div>

            <?php elseif ($step == 2): ?>
            <!-- Phase 2: Security Question & Reset Password -->
            <form action="recover.php" method="POST">
                <input type="hidden" name="step_2" value="1">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="question" value="<?php echo htmlspecialchars($question); ?>">
                
                <div class="form-group">
                    <label>Security Question</label>
                    <p style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($question); ?>
                    </p>
                </div>

                <div class="form-group">
                    <label for="answer">Your Answer</label>
                    <input type="text" id="answer" name="answer" required placeholder="Enter your secret answer">
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required placeholder="At least 12 characters long">
                </div>

                <button type="submit">Reset Password</button>
            </form>
            <div class="links">
                <a href="recover.php">Start Over</a>
            </div>

            <?php elseif ($step == 3): ?>
            <!-- Step 3: Success placeholder just for styling consistency -->
            <?php endif; ?>

        </div>
    </div>
</body>
</html>
