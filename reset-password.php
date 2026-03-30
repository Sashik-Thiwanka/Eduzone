<?php
session_start();
// Check if the token is present in the URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    // If no token, send them back to the forgot password page or show an error
    die("Error: Access denied. A valid security token is required to reset your password.");
}
$token = $_GET['token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gemini-blue: #4285f4;
            --gemini-purple: #9b72cb;
            --bg: #030712;
            --surface: #0f172a;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Ambient Background Glow */
        .glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            z-index: -1;
        }

        .reset-card {
            background: var(--surface);
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-font {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(to right, var(--gemini-blue), var(--gemini-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            display: block;
        }

        h2 { margin-bottom: 10px; font-size: 1.8rem; }
        p { color: #94a3b8; font-size: 0.9rem; margin-bottom: 30px; }

        .input-group {
            text-align: left;
            margin-bottom: 20px;
            position: relative;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 8px;
            margin-left: 5px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 38px;
            color: #475569;
        }

        .input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: var(--gemini-blue);
            box-shadow: 0 0 0 4px rgba(66, 133, 244, 0.1);
        }

        .btn-reset {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, var(--gemini-blue), var(--gemini-purple));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(155, 114, 203, 0.3);
        }

        .back-link {
            display: block;
            margin-top: 25px;
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .back-link:hover { color: white; }

        /* Strength Meter */
        .strength-meter {
            height: 4px;
            width: 100%;
            background: #334155;
            margin-top: 10px;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: 0.5s;
        }
    </style>
</head>
<body>

    <div class="glow"></div>

    <div class="reset-card">
        <span class="logo-font">EDUZONE PRO</span>
        <h2>Secure Reset</h2>
        <p>Enter a strong password to protect your study records.</p>

        <form action="update_password.php" method="POST" id="resetForm">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

            <div class="input-group">
                <label>New Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="pass" name="new_password" placeholder="••••••••" required onkeyup="checkStrength(this.value)">
                <div class="strength-meter">
                    <div id="strength-bar" class="strength-bar"></div>
                </div>
            </div>

            <div class="input-group">
                <label>Confirm Password</label>
                <i class="fas fa-shield-check"></i>
                <input type="password" name="confirm_password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-reset">Update Password</button>
        </form>

        <a href="login.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
    </div>

    <script>
        function checkStrength(password) {
            let bar = document.getElementById('strength-bar');
            let strength = 0;
            if (password.length > 5) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^A-Za-z0-9]/)) strength += 25;

            bar.style.width = strength + "%";
            
            if (strength <= 25) bar.style.backgroundColor = "#ef4444";
            else if (strength <= 50) bar.style.backgroundColor = "#f59e0b";
            else if (strength <= 75) bar.style.backgroundColor = "#3b82f6";
            else bar.style.backgroundColor = "#10b981";
        }
    </script>
</body>
</html>