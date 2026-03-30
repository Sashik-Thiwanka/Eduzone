<?php
session_start();

// 1. Database Connection
$conn = new mysqli("sql100.infinityfree.com", "if0_40714375", "LkE4u5SOS16CPC", "if0_40714375_eduzone_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: Please try again later.");
}

$message = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = "error";
        $message = "Please enter a valid email address.";
    } else {
        // 2. Check if email exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 3. Generate a secure token and 1-hour expiry
            $token = bin2hex(random_bytes(16)); 
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // 4. Update the user record
            $update = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
            $update->bind_param("sss", $token, $expiry, $email);
            
            if ($update->execute()) {
                // In a production environment, you would use a mailer here.
                // For localhost testing, we display the generated link.
                $resetLink = "reset_password.php?token=" . $token;
                
                $status = "success";
                $message = "A reset link has been generated!<br>
                            <a href='$resetLink' style='color:#facc15; font-weight:bold; text-decoration:underline;'>
                            Click here to Reset Password
                            </a>";
            }
        } else {
            // "Security Tip: On real sites, it's better to say 'If this email exists, a link was sent' 
            // to prevent people from guessing which emails are registered."
            $status = "error";
            $message = "No account found with that email address.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --blue: #4285f4; 
            --purple: #9b72cb;
            --navy: #030712; 
            --surface: #0f172a;
            --gold: #facc15; 
        }
        body { 
            background: var(--navy); 
            color: white; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .card { 
            background: var(--surface); 
            padding: 40px; 
            border-radius: 24px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.4); 
            width: 100%; 
            max-width: 400px; 
            text-align: center; 
            border: 1px solid rgba(255,255,255,0.1); 
        }
        .logo { 
            font-size: 1.6rem; 
            font-weight: 800; 
            background: linear-gradient(to right, var(--blue), var(--purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px; 
            display: block; 
        }
        input[type="email"] { 
            width: 100%; 
            padding: 14px; 
            margin: 20px 0; 
            border-radius: 12px; 
            border: 1px solid rgba(255,255,255,0.1); 
            background: #1e293b; 
            color: white; 
            box-sizing: border-box; 
            outline: none;
        }
        input:focus { border-color: var(--blue); }
        .btn { 
            background: linear-gradient(to right, var(--blue), var(--purple));
            color: white; 
            border: none; 
            padding: 14px; 
            border-radius: 12px; 
            font-weight: bold; 
            cursor: pointer; 
            width: 100%; 
            transition: 0.3s; 
        }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
        .alert { 
            padding: 15px; 
            border-radius: 12px; 
            margin-bottom: 20px; 
            font-size: 0.85rem; 
            line-height: 1.6; 
        }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #f87171; }
        .back-link { color: #64748b; text-decoration: none; font-size: 0.85rem; margin-top: 20px; display: inline-block; }
        .back-link:hover { color: white; }
    </style>
</head>
<body>
    <div class="card">
        <span class="logo">EDUZONE PRO</span>
        <h3>Recover Access</h3>
        <p style="color: #94a3b8; font-size: 0.9rem;">We'll send you a link to get back into your account.</p>

        <?php if($message): ?>
            <div class="alert alert-<?php echo $status; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
        
        <a href="login.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
    </div>
</body>
</html>