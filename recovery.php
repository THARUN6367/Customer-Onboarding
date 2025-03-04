<?php
require 'vendor/autoload.php'; // Add this line if you used Composer to install PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "Pass123";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function handlePasswordRecovery($conn, $email) {
    // Check if the email exists
    $checkEmailSql = "SELECT email FROM personal_info WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows === 0) {
        echo "Email not found.";
        return;
    }

    // Generate a unique token and set expiry date
    $token = bin2hex(random_bytes(16));
    $expiry = date("Y-m-d H:i:s", strtotime('+2 days')); // 2 days expiry

    // Save or update the token and expiry date
    $updateSql = "UPDATE personal_info SET reset_token = ?, token_expiry = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sss", $token, $expiry, $email);
    if ($updateStmt->execute()) {
        // Create the recovery link
        $recoveryLink = "http://localhost/login/recovery.php?token=" . urlencode($token);

        // Send recovery email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set your SMTP server
            $mail->SMTPAuth = true;
            $config = include('config.php');
            $mail->Username = $config['smtp_username']; // SMTP username
            $mail->Password = $config['smtp_password']; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@accessminds.com', 'Accezz Minds');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Recovery';
            $mail->Body    = "Click the following link to reset your password: <a href='$recoveryLink'>$recoveryLink</a>";

            $mail->send();
            echo "Password recovery link has been sent to your email.";
        } catch (Exception $e) {
            echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error updating token: " . $updateStmt->error;
    }

    $updateStmt->close();
}

function handlePasswordReset($conn, $token, $new_password) {
    // Verify token and expiry
    $verifySql = "SELECT email FROM personal_info WHERE reset_token = ? AND token_expiry > NOW()";
    $verifyStmt = $conn->prepare($verifySql);
    $verifyStmt->bind_param("s", $token);
    $verifyStmt->execute();
    $verifyStmt->store_result();

    if ($verifyStmt->num_rows > 0) {
        $verifyStmt->bind_result($email);
        $verifyStmt->fetch();

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password
        $updateSql = "UPDATE personal_info SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $hashed_password, $email);
        if ($updateStmt->execute()) {
            // Display success message with a button to go to the login page
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Password Reset Successful</title>
                <style>
                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        font-family: Arial, sans-serif;
                        background-color: #f0f0f0;
                    }
                    .container {
                        text-align: center;
                        background: white;
                        padding: 20px;
                        border-radius: 5px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    .container h2 {
                        margin-bottom: 20px;
                    }
                    .container a {
                        display: inline-block;
                        padding: 10px 20px;
                        margin-top: 20px;
                        background-color: #007bff;
                        color: white;
                        text-decoration: none;
                        border-radius: 5px;
                    }
                    .container a:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2>Password Reset Successfully</h2>
                    <p>Your password has been reset successfully. You can now log in with your new password.</p>
                    <a href="login.html">Go to Login</a>
                </div>
            </body>
            </html>
            ';
        } else {
            echo "Error updating password: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "Invalid or expired token.";
        // Log additional details for debugging
        error_log("Token verification failed for token: $token");
        $error = $verifyStmt->error;
        if ($error) {
            error_log("Database error: $error");
        }
    }

    $verifyStmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            handlePasswordRecovery($conn, $email);
        } else {
            echo "Invalid email format.";
        }
    } elseif (isset($_POST['token']) && isset($_POST['new_password'])) {
        $token = trim($_POST['token']);
        $new_password = trim($_POST['new_password']);
        if (strlen($new_password) >= 8) {
            handlePasswordReset($conn, $token, $new_password);
        } else {
            echo "Password must be at least 8 characters long.";
        }
    }
} else {
    if (isset($_GET['token'])) {
        $token = htmlspecialchars($_GET['token']);
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <link rel="stylesheet" href="css/login.css">
        </head>
        <body>
            <section>
                <div class="box">
                    <div class="form">
                        <h2>Reset Password</h2>
                        <form id="resetForm" method="post" action="recovery.php">
                            <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                            <div class="inputBx">
                                <label for="new_password">New Password:</label>
                                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                            </div>
                            <div class="inputBx">
                                <button type="submit">Reset Password</button>
                            </div>
                        </form>
                        <p>Remember your password? <a href="login.html">Login</a></p>
                        <p>Don\'t have an account? <a href="index.html">Register</a></p>
                    </div>
                </div>
            </section>
        </body>
        </html>
        ';
    } else {
        echo "Invalid request.";
    }
}

$conn->close();
?>
