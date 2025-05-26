<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    return str_pad((string)rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
    $subject = 'Your Verification Code';
    $message = "<p>Your verification code is: <strong>{$code}</strong></p>";
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: no-reply@example.com'
    ];
    
    return mail($email, $subject, $message, implode("\r\n", $headers));
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    if (in_array($email, $emails)) {
        return false;
    }
    
    return file_put_contents($file, $email . PHP_EOL, FILE_APPEND) !== false;
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return false;
    }
    
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $emails = array_filter($emails, fn($e) => $e !== $email);
    
    return file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL) !== false;
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
    try {
        // Create context with timeout and user agent
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'XKCD-Comic-Bot/1.0',
                'follow_location' => true
            ]
        ]);
        
        // Get the latest comic number first
        $latestResponse = file_get_contents('https://xkcd.com/info.0.json', false, $context);
        if ($latestResponse === false) {
            throw new Exception('Failed to fetch latest comic info');
        }
        
        $latest = json_decode($latestResponse, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from XKCD API');
        }
        
        $max = $latest['num'];
        
        // Get a random comic
        $randomNum = rand(1, $max);
        $comicResponse = file_get_contents("https://xkcd.com/{$randomNum}/info.0.json", false, $context);
        if ($comicResponse === false) {
            throw new Exception('Failed to fetch random comic');
        }
        
        $comic = json_decode($comicResponse, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response for random comic');
        }
        
        return "<h2>XKCD Comic</h2>
                <img src=\"{$comic['img']}\" alt=\"XKCD Comic\">
                <p><a href=\"http://localhost:8000/unsubscribe.php\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
    } catch (Exception $e) {
        error_log("XKCD API Error: " . $e->getMessage());
        return "<p>Sorry, we couldn't fetch a comic at this time. Please try again later.</p>";
    }
}
/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers(): void {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return;
    }
    
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $xkcdData = fetchAndFormatXKCDData();
    
    $subject = 'Your XKCD Comic';
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: no-reply@example.com'
    ];
    
    foreach ($emails as $email) {
        $personalizedData = str_replace('unsubscribe.php', 'unsubscribe.php?email=' . urlencode($email), $xkcdData);
        mail($email, $subject, $personalizedData, implode("\r\n", $headers));
    }
}
