<?php
session_start();

$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

// 1. Database Connection
$conn = new mysqli("sql100.infinityfree.com", "if0_40714375", "LkE4u5SOS16CPC", "if0_40714375_eduzone_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $token = $_POST['token'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    // 2. Basic Validation
    if (empty($token) || empty($new_pass)) {
        die("Invalid request.");
    }

    if ($new_pass !== $confirm_pass) {
        die("Passwords do not match. Please go back.");
    }

    // 3. Security Check: Verify token and check if it has expired
    // This assumes you added the 'token_expiry' column earlier
    $check_sql = "SELECT user_id FROM users WHERE reset_token = ? AND token_expiry > NOW()";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Error: The reset link is invalid or has expired.");
    }

    // 4. Hash the new password
    $hashed_password = password_hash($new_pass, PASSWORD_BCRYPT);

    // 5. Update the password and CLEAR the token
    // We clear the token so it cannot be used a second time (Single Use Only)
    $update_sql = "UPDATE users SET password_hash = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $hashed_password, $token);

    if ($update_stmt->execute()) {
        // Success: Redirect to login with a success message
        header("Location: login.html?status=reset_success");
        exit();
    } else {
        echo "A server error occurred. Please try again later.";
    }

    $stmt->close();
    $update_stmt->close();
}

$conn->close();
?>