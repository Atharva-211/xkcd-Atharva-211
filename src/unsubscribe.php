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
$email = '';

// Check if email is provided in URL
if (isset($_GET['email'])) {
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
}

// Rate limiting check
$current_time = time();
if ($_SESSION['verification_attempts'] >= 3 && ($current_time - $_SESSION['last_attempt_time']) < 300) {
    $message = 'Too many attempts. Please try again in 5 minutes.';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid request. Please try again.';
    } else if (isset($_POST['unsubscribe_email'])) {
        $email = filter_var($_POST['unsubscribe_email'], FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            $_SESSION['unsubscribe_code'] = $code;
            $_SESSION['unsubscribe_email'] = $email;
            $_SESSION['verification_attempts'] = 0;
            $_SESSION['last_attempt_time'] = $current_time;
            
            $subject = 'Confirm Un-subscription';
            $message = "<p>To confirm un-subscription, use this code: <strong>{$code}</strong></p>";
            $headers = [
                'MIME-Version: 1.0',
                'Content-type: text/html; charset=UTF-8',
                'From: no-reply@example.com'
            ];
            
            if (mail($email, $subject, $message, implode("\r\n", $headers))) {
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
        $email = $_SESSION['unsubscribe_email'] ?? '';
        
        // Update attempt counter
        $_SESSION['verification_attempts']++;
        $_SESSION['last_attempt_time'] = $current_time;
        
        if ($code === $_SESSION['unsubscribe_code']) {
            if (unsubscribeEmail($email)) {
                $message = 'Successfully unsubscribed from XKCD comics.';
                unset($_SESSION['unsubscribe_code']);
                unset($_SESSION['unsubscribe_email']);
                unset($_SESSION['verification_attempts']);
                unset($_SESSION['last_attempt_time']);
            } else {
                $message = 'Failed to unsubscribe. Please try again.';
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
    <title>Unsubscribe from XKCD Comics</title>
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
            background-color: #dc3545;
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
    <h1>Unsubscribe from XKCD Comics</h1>
    
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'Successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="form-group">
            <label for="unsubscribe_email">Email Address:</label>
            <input type="email" name="unsubscribe_email" id="unsubscribe_email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <button type="submit" id="submit-unsubscribe">Unsubscribe</button>
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
