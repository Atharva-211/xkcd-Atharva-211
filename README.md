# xkcd-Atharva-211
![Screenshot from 2025-05-26 20-09-32](https://github.com/user-attachments/assets/211b209b-c919-4c79-9f2f-e6b90215a4e1)

![Screenshot from 2025-05-26 20-10-59](https://github.com/user-attachments/assets/38b41687-486b-4234-978a-41f898ca78e1)

![Screenshot from 2025-05-26 20-11-11](https://github.com/user-attachments/assets/7583ef7c-979e-492b-9ef7-ebd502dbb1f9)

![Screenshot from 2025-05-26 20-11-24](https://github.com/user-attachments/assets/b3fbd6c7-30e7-4bc8-9c29-5747e53d43a1)

![Screenshot from 2025-05-26 20-11-38](https://github.com/user-attachments/assets/0fdf27c3-7f57-4517-a0db-7e6ef773a96d)

![Screenshot from 2025-05-26 20-12-04](https://github.com/user-attachments/assets/7a60e127-cd59-4d9f-bcd3-37612a502e4f)

![Screenshot from 2025-05-26 20-12-28](https://github.com/user-attachments/assets/246320ef-c9b3-40b8-a319-6ce5d822ec0b)

![Screenshot from 2025-05-26 20-12-44](https://github.com/user-attachments/assets/79ffc4e7-b70e-4649-8516-4c0e6bc2164f)

![Screenshot from 2025-05-26 20-13-06](https://github.com/user-attachments/assets/2bce36d5-8c74-455d-b9cd-d33d7abb732f)

![Screenshot from 2025-05-26 20-13-25](https://github.com/user-attachments/assets/19ff3217-26f7-4860-8727-da280ddd8c4e)

![Screenshot from 2025-05-26 20-13-32](https://github.com/user-attachments/assets/9dbbaf15-30e6-43c8-9e89-324435c299fd)

![Screenshot from 2025-05-26 20-14-12](https://github.com/user-attachments/assets/1d86801f-7e25-4b53-8dac-01172bb69ae9)

![Screenshot from 2025-05-26 20-14-20](https://github.com/user-attachments/assets/a2bab020-6ca7-4709-88aa-dd4da506b4a8)

![Screenshot from 2025-05-26 20-14-42](https://github.com/user-attachments/assets/ca02c072-e3bd-4de9-a8e7-84be2e0a4ed9)

![Screenshot from 2025-05-26 20-15-30](https://github.com/user-attachments/assets/a33a3d69-0440-4ff8-a979-d952fdf497f3)



# XKCD

This project is a PHP-based email verification system where users register using their email, receive a verification code, and subscribe to get a random XKCD comic every day. A CRON job fetches a random XKCD comic and sends it to all registered users every 24 hours.

---

## 🚀 Your Task

Your objective is to implement the functionality in the **src/** directory while following these rules:

✅ **DO NOT** change function names or modify the file structure.

✅ **DO NOT** modify anything outside the **src/** folder. You can add additional files if required inside **src** folder.

✅ **DO NOT** hardcode emails; use `registered_emails.txt` as the database.

✅ Implement all required functions in `functions.php`.

✅ Implement a form in `index.php` to take email input and verify via code.

✅ Implement a CRON job to send XKCD comics to registered users every 24 hours.

✅ Implement an unsubscribe feature where users can opt out via email verification.

✅ Implement `unsubscribe.php` to handle email unsubscription.

---

## 📝 Submission Steps [ Non adherence to this will cause disqualification ]
1. **Clone** the repository to your local machine.  
2. **Create a new branch** from the `main` branch. **Do not** push code directly to `main`.  
3. **Implement** the required features inside the `src/` directory.  
4. **Push** your code to your **branch** (not `main`).  
5. **Raise a Pull Request (PR) only once** against the `main` branch when all your code is finalized.  
   - **Do not raise multiple PRs.**  
   - **Do not add multiple commits to a PR after submission.**  
6. **Failure to follow these instructions will result in disqualification.**  
7. **Wait** for your submission to be reviewed. Do not merge the PR.

---

## ⚠️ Important Notes

All form elements should always be visible on the page and should not be conditionally rendered. This ensures the assignment can be tested properly at the appropriate steps.

Please ensure that if the base repository shows the original template repo, update it so that your repo's main branch is set as the base branch.

**Recommended PHP version: 8.3**

---

## 📌 Features to Implement

### 1️⃣ **Email Verification**
- Users enter their email in a form.
- A **6-digit numeric code** is generated and emailed to them.
- Users enter the code in the form to verify and register.
- Store the verified email in `registered_emails.txt`.

### 2️⃣ **Unsubscribe Mechanism**
- Emails should include an **unsubscribe link**.
- Clicking it will take user to the unsubscribe page.
- Users enter their email in a form.
- A **6-digit numeric code** is generated and emailed to them.
- Users enter the code to confirm unsubscription.

### 3️⃣ **XKCD Comic Subscription**
- Every 24 hours, cron job should:
  - Fetch data from `https://xkcd.com/[randomComicID]/info.0.json`.
  - Format it as **HTML (not JSON)**.
  - Send it via email to all registered users.

---

## 📜 File Details & Function Stubs

You **must** implement the following functions inside `functions.php`:

```php
function generateVerificationCode() {
    // Generate and return a 6-digit numeric code
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    // Save verified email to registered_emails.txt
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    // Remove email from registered_emails.txt
}

function sendVerificationEmail($email, $code) {
    // Send an email containing the verification code
}

function verifyCode($email, $code) {
    // Check if the provided code matches the sent one
}

function fetchAndFormatXKCDData() {
    // Fetch latest data from XKCD API and format as HTML
}

function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    // Send formatted XKCD data to all registered emails
}
```
## 🔄 CRON Job Implementation

📌 You must implement a **CRON job** that runs `cron.php` every 24 hours.

📌 **Do not just write instructions**—provide an actual **setup_cron.sh** script inside `src/`.

📌 **Your script should automatically configure the CRON job on execution.**

---

### 🛠 Required Files

- **`setup_cron.sh`** (Must configure the CRON job)
- **`cron.php`** (Must handle sending XKCD comics)

---

### 🚀 How It Should Work

- The `setup_cron.sh` script should register a **CRON job** that executes `cron.php` every 24 hours.
- The CRON job **must be automatically added** when the script runs.
- The `cron.php` file should actually **fetch random XKCD comic** and **send emails** to registered users.

---

## 📩 Email Handling

✅ The email content must be in **HTML format** (not JSON).

✅ Use **PHP's `mail()` function** for sending emails.

✅ Each email should include an **unsubscribe link**.

✅ Unsubscribing should trigger a **confirmation code** before removal.

✅ Store emails in `registered_emails.txt` (**Do not use a database**).

---

## ❌ Disqualification Criteria

🚫 **Hardcoding** verification codes.

🚫 **Using a database** (use `registered_emails.txt`).

🚫 **Modifying anything outside** the `src/` directory.

🚫 **Changing function names**.

🚫 **Not implementing a working CRON job**.

🚫 **Not formatting emails as HTML**.

---
## 📌 Input & Button Formatting Guidelines

### 📧 Email Input & Submission Button:
- The email input field must have `name="email"`.
- The submit button must have `id="submit-email"`.

#### ✅ Example:
```html
<input type="email" name="email" required>
<button id="submit-email">Submit</button>
```
---
### 🔢 Verification Code Input & Submission Button:

- The verification input field must have `name="verification_code"`.
- The submit button must have `id="submit-verification"`.

#### ✅ Example:
```html
<input type="text" name="verification_code" maxlength="6" required>
<button id="submit-verification">Verify</button>
```
---
### 🚫 Unsubscribe Email & Submission Button
- The unsubscribe input field must have `name="unsubscribe_email"`.
- The submit button must have `id="submit-unsubscribe"`.
#### ✅ Example:
```html
<input type="email" name="unsubscribe_email" required>
<button id="submit-unsubscribe">Unsubscribe</button>
```
---
### 🚫 Unsubscribe Code Input & Submission Button
- The unsubscribe code input field must have `name="verification_code"`.
- The submit button must have `id="submit-verification"`.
#### ✅ Example:
```html
<input type="text" name="verification_code" maxlength="6" required>
<button id="submit-verification">Verify</button>
```
---

## 📩 Email Content Guidelines

#### ✅ Verification Email:
- **Subject:** `Your Verification Code`
- **Body Format:**
```html
<p>Your verification code is: <strong>123456</strong></p>
```
- Sender: no-reply@example.com
---

### 📩 Email Content Guidelines

⚠️ Note: The Subject and Body of the email must strictly follow the formats below, including the exact HTML structure.

#### ✅ XKCD Comic Email:
- **Subject:** `Your XKCD Comic`
- **Body Format:**
```html
<h2>XKCD Comic</h2>
<img src="image_url_here" alt="XKCD Comic">
<p><a href="#" id="unsubscribe-button">Unsubscribe</a></p>
```
---
### ✅ Unsubscribe Confirmation Email:
- **Subject:** `Confirm Un-subscription`
- **Body Format:**
```html
<p>To confirm un-subscription, use this code: <strong>654321</strong></p>
```
---
