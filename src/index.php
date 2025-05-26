<?php
require_once 'functions.php';

session_start();

// Initialize session variables if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['verification_attempts'])) {
    $_SESSION['verification_attempts'] = 0;
}
if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = 0;
}

$message = '';
$showVerification = false;

// Rate limiting check
$current_time = time();
if ($_SESSION['verification_attempts'] >= 3 && ($current_time - $_SESSION['last_attempt_time']) < 300) {
    $message = 'Too many attempts. Please try again in 5 minutes.';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid request. Please try again.';
    } else if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            $_SESSION['verification_code'] = $code;
            $_SESSION['email'] = $email;
            $_SESSION['verification_attempts'] = 0;
            $_SESSION['last_attempt_time'] = $current_time;
            
            if (sendVerificationEmail($email, $code)) {
                $message = 'Verification code sent to your email!';
                $showVerification = true;
            } else {
                $message = 'Failed to send verification code. Please try again.';
            }
        } else {
            $message = 'Invalid email address.';
        }
    } elseif (isset($_POST['verification_code'])) {
        $code = $_POST['verification_code'];
        $email = $_SESSION['email'] ?? '';
        
        // Update attempt counter
        $_SESSION['verification_attempts']++;
        $_SESSION['last_attempt_time'] = $current_time;
        
        if ($code === $_SESSION['verification_code']) {
            if (registerEmail($email)) {
                $message = 'Email registered successfully! You will receive XKCD comics daily.';
                unset($_SESSION['verification_code']);
                unset($_SESSION['email']);
                unset($_SESSION['verification_attempts']);
                unset($_SESSION['last_attempt_time']);
            } else {
                $message = 'Failed to register email. Please try again.';
            }
        } else {
            $message = 'Invalid verification code.';
            $showVerification = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XKCD Comic Subscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="email"], input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h1>XKCD Comic Subscription</h1>
    
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <button type="submit" id="submit-email">Subscribe</button>
    </form>

    <?php if ($showVerification): ?>
        <form method="POST" action="" style="margin-top: 20px;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-group">
                <label for="verification_code">Verification Code:</label>
                <input type="text" name="verification_code" id="verification_code" maxlength="6" pattern="[0-9]*" inputmode="numeric" required>
            </div>
            <button type="submit" id="submit-verification">Verify</button>
        </form>
    <?php endif; ?>
</body>
</html>
