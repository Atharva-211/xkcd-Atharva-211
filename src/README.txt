XKCD Comic Subscription System Setup

This system allows users to subscribe to daily XKCD comics via email. Here's how to set it up:

1. Requirements:
   - PHP 8.3 or later
   - Web server (Apache/Nginx)
   - Mail server configured for PHP

2. Installation:
   - Place all files in your web server's document root
   - Ensure the web server has write permissions to registered_emails.txt
   - Configure your PHP mail settings in php.ini

3. Setting up the daily comic delivery:

   On Windows:
   - Run setup_cron.bat as administrator
   - This will create a Windows Task Scheduler job

   On Linux/Unix:
   - Run setup_cron.sh with sudo
   - This will create a cron job

4. Testing:
   - Visit index.php in your web browser
   - Try subscribing with an email
   - Verify you receive the verification code
   - Complete the verification process
   - You should receive a comic the next day

5. Troubleshooting:
   - Check PHP error logs for any issues
   - Verify mail server configuration
   - Ensure file permissions are correct
   - Check cron.log for delivery issues

6. Security Notes:
   - The system includes CSRF protection
   - Rate limiting is implemented
   - Input validation is in place
   - XSS prevention is implemented

For any issues, check the error logs in your web server and PHP configuration. 