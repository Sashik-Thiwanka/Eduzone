<?php
// submit_feedback.php

// Define the file to redirect to after processing (e.g., home page or a thank you page)
$redirect_page = 'home.php'; 

// Database configuration (Using your established credentials)
$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

// 1. Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- 2. Sanitize and Validate Input ---
    
    // Use the null coalescing operator (??) to safely access $_POST keys
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($comment)) {
        // Store an error message and redirect
        $_SESSION['feedback_message'] = "Error: All fields are required.";
        $_SESSION['feedback_status'] = "error";
        header("Location: " . $redirect_page);
        exit();
    }

    // --- 3. Database Connection and Insertion ---
    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // SQL using prepared statements for security
        $sql = "INSERT INTO feedback (name, email, comment) VALUES (?, ?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters (s = string, s = string, s = string)
        $stmt->bind_param("sss", $name, $email, $comment);
        
        // Execute the statement
        if ($stmt->execute()) {
            $message = "Thank you! Your feedback has been successfully submitted.";
            $status = "success";
        } else {
            // Log error for debugging, but show generic message to user
            error_log("Feedback Insertion Error: " . $stmt->error);
            $message = "Error submitting feedback. Please try again.";
            $status = "error";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        $message = "A system error occurred. Please try again later.";
        $status = "error";
        // Optionally, log the full error: error_log($e->getMessage());
    }
    
    // --- 4. Store message and Redirect ---
    
    // NOTE: You need to start the session on the page you redirect to (e.g., home.php) 
    // to display these messages.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['feedback_message'] = $message;
    $_SESSION['feedback_status'] = $status;
    
    header("Location: " . $redirect_page);
    exit();

} else {
    // If accessed directly without form submission, redirect
    header("Location: " . $redirect_page);
    exit();
}
?>