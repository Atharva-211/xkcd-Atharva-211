<?php
require_once 'functions.php';
// This script should send XKCD updates to all registered emails.
// You need to implement this functionality.

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/cron.log');

try {
    // Send XKCD comics to all registered subscribers
    sendXKCDUpdatesToSubscribers();
    error_log("Successfully sent XKCD comics to subscribers");
} catch (Exception $e) {
    error_log("Error in cron job: " . $e->getMessage());
    exit(1);
}
