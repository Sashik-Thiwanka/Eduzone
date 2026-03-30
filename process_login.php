<?php
/**
 * EDUZONE PRO - Professional Authentication Handler
 * Logic: Verifies credentials using BCRYPT hashing and manages user session.
 */

// 1. SESSION & SECURITY HEADERS
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// 2. DATABASE CONFIGURATION
$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

/**
 * Redirects user back to login with a sanitized error message stored in session.
 */
function redirectWithError($message) {
    $_SESSION['login_error'] = $message;
    header('Location: login.html');
    exit();
}

// 3. VALIDATE REQUEST & INPUTS
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectWithError("Invalid request method.");
}

if (empty($_POST['email']) || empty($_POST['password'])) {
    redirectWithError("Please fill in all fields.");
}

$email             = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$submittedPassword = $_POST['password'];

try {
    // 4. DATABASE CONNECTION
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        // Log error internally, show generic message to user
        error_log("Connection failed: " . $conn->connect_error);
        throw new Exception("Server error. Please try again later.");
    }

    // 5. PREPARED STATEMENT
    // We fetch user_id, user_name, and the password_hash. 
    // We also fetch reset_token to ensure logic consistency.
    $sql = "SELECT user_id, user_name, password_hash, reset_token FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("System error: Failed to prepare statement.");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 6. PASSWORD VERIFICATION
        // password_verify() works with the BCRYPT hashes created in your reset/register scripts.
        if (password_verify($submittedPassword, $user['password_hash'])) {
            
            // REGENERATE SESSION ID (Prevents Session Fixation attacks)
            session_regenerate_id(true);

            // 7. SET SESSION VARIABLES
            $_SESSION['user_id']   = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['logged_in'] = true;

            // OPTIONAL: If a user logs in successfully, you may want to clear any existing reset tokens
            if (!empty($user['reset_token'])) {
                $clearTokenSql = "UPDATE users SET reset_token = NULL, token_expiry = NULL WHERE user_id = ?";
                $clearStmt = $conn->prepare($clearTokenSql);
                $clearStmt->bind_param("i", $user['user_id']);
                $clearStmt->execute();
            }

            // 8. SUCCESS REDIRECT
            header('Location: home.php');
            exit();

        } else {
            // Delay slightly to prevent brute-force timing attacks
            usleep(100000); 
            throw new Exception("Invalid email or password.");
        }
    } else {
        throw new Exception("Invalid email or password.");
    }

} catch (Exception $e) {
    redirectWithError($e->getMessage());
} finally {
    // 9. CLEANUP
    if (isset($stmt)) { $stmt->close(); }
    if (isset($conn)) { $conn->close(); }
}
?>