<?php
// CRITICAL: Start the session at the very beginning
session_start(); 

// 1. DATABASE CONFIGURATION
$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

// Function to safely redirect on failure
function redirectWithError($message) {
    // Store the error message in a session variable
    $_SESSION['registration_error'] = $message;
    // Redirect back to the registration page (assuming registration form is in register.html or register.php)
    header('Location: register.html'); 
    exit();
}

// 2. CHECK REQUEST METHOD
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirectWithError("Access denied. Please use the registration form.");
}

try {
    // 3. RETRIEVE, SANITIZE AND VALIDATE INPUT
    
    // Check if fields exist and are not empty
    if (empty($_POST['first-name']) || empty($_POST['last-name']) || empty($_POST['email']) || empty($_POST['password'])) {
         throw new Exception("All required fields (Name, Email, Password) must be filled.");
    }
    
    // Sanitize and derive values
    $firstName = htmlspecialchars(trim($_POST['first-name']));
    $lastName = htmlspecialchars(trim($_POST['last-name']));
    $fullName = $firstName . " " . $lastName;
    $userName = $firstName; // Using first name for the 'user_name' column (for greeting)
    
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone'] ?? '')); // Phone is optional
    $rawPassword = $_POST['password'];
    
    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("The provided email address is invalid.");
    }
    if (strlen($rawPassword) < 8) {
         throw new Exception("Password must be at least 8 characters long.");
    }

    // Hash the password securely
    $passwordHash = password_hash($rawPassword, PASSWORD_DEFAULT);
    
    // 4. DATABASE CONNECTION
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("We are currently experiencing technical difficulties. Please try again later.");
    }

    // 5. CHECK FOR EXISTING EMAIL
    $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows > 0) {
        $checkStmt->close();
        throw new Exception("This email is already registered. Please use the login page.");
    }
    $checkStmt->close();

    // 6. PREPARE AND EXECUTE INSERT STATEMENT
    // Columns match eduzone_db schema: (user_name, full_name, email, phone_number, password_hash)
    $sql = "INSERT INTO users (user_name, full_name, email, phone_number, password_hash) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Could not prepare database statement.");
    }
    
    // Bind parameters: 5 strings (s)
    $stmt->bind_param("sssss", $userName, $fullName, $email, $phone, $passwordHash);

    if ($stmt->execute()) {
        
        // 7. SUCCESS: SET SESSION AND REDIRECT
        
        // Log the user in immediately after successful registration
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $userName; 
        
        $stmt->close();
        $conn->close();

        // Redirect to Home Page
        header('Location: home.php'); 
        exit();

    } else {
        throw new Exception("Database execution error: A server issue prevented registration.");
    }

} catch (Exception $e) {
    // 8. GLOBAL FAILURE HANDLER
    // All errors redirect the user with a clean message
    
    // Log the actual technical error internally for debugging (optional, but recommended)
    // error_log("Registration Error: " . $e->getMessage()); 
    
    redirectWithError($e->getMessage());

} finally {
    // Ensure the database connection is closed
    if (isset($conn) && $conn->ping()) {
        $conn->close();
    }
}
?>