<?php
session_start();
if (!isset($_SESSION['user_name'])) { header("Location: login.html"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Saved Library | EduZone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg: #030712; --card: #111827; }
        body { background: var(--bg); color: white; font-family: 'Plus Jakarta Sans', sans-serif; padding: 50px 10%; }
        .saved-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .saved-item { 
            background: var(--card); border-radius: 15px; padding: 25px; 
            border: 1px solid rgba(255,255,255,0.05); transition: 0.3s;
        }
        .saved-item:hover { transform: scale(1.02); border-color: #6366f1; }
        .type-tag { font-size: 0.7rem; text-transform: uppercase; color: #6366f1; font-weight: 800; }
        .action-btn { background: #1f2937; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; margin-top: 15px; width: 100%; }
    </style>
</head>
<body>
    <div class="saved-header">
        <h1>My Knowledge Library</h1>
        <a href="learning.php" style="color:#94a3b8; text-decoration:none;"><i class="fas fa-plus"></i> Add More</a>
    </div>

    <div class="grid">
        <div class="saved-item">
            <span class="type-tag">Past Paper</span>
            <h3>ICT 2023 Paper I & II</h3>
            <p style="color:#6b7280; font-size:0.9rem;">Saved on Oct 12, 2025</p>
            <button class="action-btn" onclick="location.href='pastpapers.php'"><i class="fas fa-download"></i> Download Again</button>
            <button class="action-btn" style="color:#ef4444;"><i class="fas fa-trash"></i> Remove</button>
        </div>

        <div class="saved-item">
            <span class="type-tag">E-Book</span>
            <h3>Python for A/L Beginners</h3>
            <p style="color:#6b7280; font-size:0.9rem;">Saved on Oct 14, 2025</p>
            <button class="action-btn" onclick="location.href='e-books.php'"><i class="fas fa-book-open"></i> Read Now</button>
            <button class="action-btn" style="color:#ef4444;"><i class="fas fa-trash"></i> Remove</button>
        </div>
    </div>

    
</body>
</html>