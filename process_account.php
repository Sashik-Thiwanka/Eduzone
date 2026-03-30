<?php
// process_account.php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Database configuration
$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";
$user_id = $_SESSION['user_id'];

// Check for action
$action = $_POST['action'] ?? 'update';
$message = 'Operation failed.';

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection error. Try again later.");
    }

    if ($action === 'update') {
        // --- UPDATE LOGIC ---
        
        // FIX: Use null coalescing operator (??) to safely access $_POST data.
        // If the key is undefined, it defaults to an empty string, preventing errors.
        $fullName = htmlspecialchars(trim($_POST['full_name'] ?? ''));
        $userName = htmlspecialchars(trim($_POST['user_name'] ?? ''));
        $phone = htmlspecialchars(trim($_POST['phone_number'] ?? ''));

        // Basic validation check to ensure key fields aren't empty
        if (empty($fullName) || empty($userName)) {
             throw new Exception("Full Name and Username are required fields.");
        }
        
        // Prepare update statement
        $sql = "UPDATE users SET full_name = ?, user_name = ?, phone_number = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $fullName, $userName, $phone, $user_id);

        if ($stmt->execute()) {
            // Update session variables immediately
            $_SESSION['user_name'] = $userName;
            $message = "Account details updated successfully!";
        } else {
            throw new Exception("Database update failed. Check if the username is already taken.");
        }
        $stmt->close();
        
    } elseif ($action === 'delete') {
        // --- DELETE LOGIC ---
        
        // Prepare delete statement
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            // Destroy session and redirect to the index page
            session_destroy();
            header('Location: index.html'); // Redirect to a generic public page
            exit();
        } else {
            throw new Exception("Account deletion failed.");
        }
        $stmt->close();
    }

} catch (Exception $e) {
    // Catch any exception and set error message
    $message = "Error: " . $e->getMessage();
} finally {
    if (isset($conn) && $conn->ping()) {
        $conn->close();
    }
}

// Store message in session for display on account.php
$_SESSION['account_message'] = $message;
header('Location: account.php');
exit();