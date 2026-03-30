<?php
session_start();
$isLoggedIn = isset($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | EduZone Support</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6366f1; --bg: #030712; --surface: #0f172a; }
        body { background: var(--bg); color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
        
        .contact-wrapper { display: grid; grid-template-columns: 1fr 1.5fr; min-height: 100vh; }
        
        .info-panel { 
            background: linear-gradient(135deg, #1e1b4b 0%, #030712 100%); 
            padding: 80px; display: flex; flex-direction: column; justify-content: center;
        }
        
        .contact-form-area { padding: 80px; background: var(--bg); }
        
        .input-group { margin-bottom: 25px; }
        .input-group label { display: block; margin-bottom: 8px; color: #94a3b8; font-size: 0.9rem; }
        .input-group input, .input-group textarea { 
            width: 100%; padding: 15px; background: var(--surface); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px; color: white; outline: none; transition: 0.3s;
        }
        .input-group input:focus { border-color: var(--primary); box-shadow: 0 0 15px rgba(99, 102, 241, 0.2); }
        
        .btn-send { 
            background: var(--primary); color: white; border: none; padding: 18px 40px; 
            border-radius: 12px; font-weight: 800; cursor: pointer; width: 100%;
        }
        
        .direct-link { color: #818cf8; text-decoration: none; font-weight: 700; margin-top: 10px; display: block; }
    </style>
</head>
<body>
    <div class="contact-wrapper">
        <div class="info-panel">
            <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Let’s Connect.</h1>
            <p style="color: #94a3b8; line-height: 1.6;">Have a technical issue or a question about Pro features? Our team (and Sashik Thiwanka) are here to help.</p>
            
            <div style="margin-top: 50px;">
                <p><i class="fas fa-envelope" style="color:var(--primary)"></i> &nbsp; sashikthiwanka1975@gmail.com</p>
                <p><i class="fas fa-location-dot" style="color:var(--primary)"></i> &nbsp; Colombo, Sri Lanka</p>
            </div>
            
            <a href="home.php" class="direct-link"><i class="fas fa-arrow-left"></i> Back to Homepage</a>
        </div>

        <div class="contact-form-area">
            <form action="process_contact.php" method="POST">
                <div class="input-group">
                    <label>Your Full Name</label>
                    <input type="text" name="name" placeholder="Sashik Thiwanka" required>
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="name@example.com" required>
                </div>
                <div class="input-group">
                    <label>Subject</label>
                    <select name="subject" style="width: 100%; padding: 15px; background: var(--surface); color:white; border-radius:12px; border:1px solid rgba(255,255,255,0.1);">
                        <option>General Inquiry</option>
                        <option>Technical Issue</option>
                        <option>Pro Membership Question</option>
                        <option>Content Request</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Your Message</label>
                    <textarea name="message" rows="6" placeholder="How can we help you today?" required></textarea>
                </div>
                <button type="submit" class="btn-send">Send Priority Message</button>
            </form>
        </div>
    </div>
</body>
</html>