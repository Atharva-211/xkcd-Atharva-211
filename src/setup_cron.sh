#!/bin/bash
# This script should set up a CRON job to run cron.php every 24 hours.
# You need to implement the CRON setup logic here.

# Get the absolute path of the cron.php file
SCRIPT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/cron.php"

# Check if running on Windows
if [[ "$OSTYPE" == "msys" || "$OSTYPE" == "win32" ]]; then
    # Create a batch file to run the PHP script
    BATCH_FILE="$(dirname "$SCRIPT_PATH")/run_cron.bat"
    echo "@echo off" > "$BATCH_FILE"
    echo "php \"$SCRIPT_PATH\"" >> "$BATCH_FILE"
    
    # Create the scheduled task
    SCHTASKS /CREATE /SC DAILY /TN "XKCDComicSender" /TR "$BATCH_FILE" /ST 00:00 /F
    
    if [ $? -eq 0 ]; then
        echo "Success: Windows Task Scheduler job has been set up to run every 24 hours at midnight"
        echo "The job will execute: php $SCRIPT_PATH"
    else
        echo "Error: Failed to set up Windows Task Scheduler job"
        exit 1
    fi
else
    # Check if running as root
    if [ "$EUID" -ne 0 ]; then 
        echo "Please run as root (use sudo)"
        exit 1
    fi

    # Check if PHP is installed
    if ! command -v php &> /dev/null; then
        echo "Error: PHP is not installed"
        exit 1
    fi

    # Check if the script exists
    if [ ! -f "$SCRIPT_PATH" ]; then
        echo "Error: cron.php not found at $SCRIPT_PATH"
        exit 1
    fi

    # Make sure the script is executable
    chmod +x "$SCRIPT_PATH"

    # Create the cron job to run every 24 hours
    (crontab -l 2>/dev/null | grep -v "$SCRIPT_PATH"; echo "0 0 * * * php $SCRIPT_PATH") | crontab -

    if [ $? -eq 0 ]; then
        echo "Success: Cron job has been set up to run every 24 hours at midnight"
        echo "The job will execute: php $SCRIPT_PATH"
    else
        echo "Error: Failed to set up cron job"
        exit 1
    fi
fi
