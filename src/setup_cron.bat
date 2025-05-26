@echo off
setlocal enabledelayedexpansion

:: Get the absolute path of the cron.php file
set "SCRIPT_PATH=%~dp0cron.php"

:: Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% neq 0 (
    echo Error: PHP is not installed or not in PATH
    exit /b 1
)

:: Check if the script exists
if not exist "%SCRIPT_PATH%" (
    echo Error: cron.php not found at %SCRIPT_PATH%
    exit /b 1
)

:: Create a batch file to run the PHP script
set "BATCH_FILE=%~dp0run_cron.bat"
echo @echo off > "%BATCH_FILE%"
echo php "%SCRIPT_PATH%" >> "%BATCH_FILE%"

:: Create the scheduled task
schtasks /create /sc daily /tn "XKCDComicSender" /tr "%BATCH_FILE%" /st 00:00 /f

if %ERRORLEVEL% equ 0 (
    echo Success: Windows Task Scheduler job has been set up to run every 24 hours at midnight
    echo The job will execute: php %SCRIPT_PATH%
) else (
    echo Error: Failed to set up Windows Task Scheduler job
    exit /b 1
) 