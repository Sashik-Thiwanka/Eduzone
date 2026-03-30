<?php
session_start();
$isLoggedIn = isset($_SESSION['user_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learning Center | EduZone Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #6366f1; --accent: #a855f7; --bg: #030712; }
        body { background: var(--bg); color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
        
        .hub-container { padding: 60px 8%; }
        .hub-header { text-align: center; margin-bottom: 80px; }
        .hub-header h1 { font-size: 3.5rem; background: linear-gradient(to right, #fff, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        /* GRID SYSTEM */
        .learning-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        
        .resource-node {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px; padding: 40px; transition: 0.4s; text-decoration: none; color: inherit;
            position: relative; overflow: hidden;
        }
        .resource-node:hover { 
            background: rgba(99, 102, 241, 0.05); border-color: var(--primary); transform: translateY(-10px);
        }
        .resource-node i { font-size: 2.5rem; color: var(--primary); margin-bottom: 20px; display: block; }
        .resource-node h3 { font-size: 1.5rem; margin: 10px 0; }
        .resource-node p { color: #94a3b8; font-size: 0.95rem; line-height: 1.6; }
        
        .node-footer { margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; font-weight: 700; font-size: 0.8rem; color: var(--primary); }

        /* PRO BADGE */
        .pro-only { position: absolute; top: 20px; right: 20px; background: #fbbf24; color: #000; padding: 4px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: 900; }
    </style>
</head>
<body>

<div class="hub-container">
    <div class="hub-header">
        <h1>Learning Command Center</h1>
        <p>Your centralized gateway to A/L excellence.</p>
    </div>

    <div class="learning-grid">
        <a href="pastpapers.php" class="resource-node">
            <i class="fas fa-file-invoice"></i>
            <h3>Past Papers</h3>
            <p>Official G.C.E. A/L archive from 2010 to 2024. Categorized by stream and year.</p>
            <div class="node-footer"><span>OPEN REPOSITORY</span> <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="modelpapers.php" class="resource-node">
            <span class="pro-only">GOV LINK</span>
            <i class="fas fa-pen-nib"></i>
            <h3>Model Papers</h3>
            <p>High-probability questions and provincial mock exams with marking schemes.</p>
            <div class="node-footer"><span>PRACTICE NOW</span> <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="quizzes.php" class="resource-node">
            <span class="pro-only" style="background: linear-gradient(to right, #4285f4, #9b72cb);">LIVE ARENA</span>
            <i class="fas fa-brain"></i>
            <h3>Quiz Arena</h3>
            <p>Timed MCQ challenges with instant scoring and global leaderboard ranking.</p>
            <div class="node-footer"><span>ATTEMPT NOW</span> <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="videolessons.php" class="resource-node">
            <i class="fas fa-play-circle"></i>
            <h3>Video Lessons</h3>
            <p>Master complex theories in ICT and Physics through our curated video playlists.</p>
            <div class="node-footer"><span>START WATCHING</span> <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="e-books.php" class="resource-node">
            <i class="fas fa-book-open"></i>
            <h3>Digital E-Books</h3>
            <p>Download official textbooks, short notes, and revision guides in PDF format.</p>
            <div class="node-footer"><span>BROWSE LIBRARY</span> <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="ictsoft.php" class="resource-node">
            <i class="fas fa-code"></i>
            <h3>Software Forge</h3>
            <p>Essential tools for ICT students: VS Code, Python, MySQL, and Graphic suites.</p>
            <div class="node-footer"><span>DOWNLOAD TOOLS</span> <i class="fas fa-arrow-right"></i></div>
        </a>
    </div>

    <div style="text-align: center; margin-top: 80px;">
        <a href="home.php" style="color: #64748b; text-decoration: none; font-weight: 600;"><i class="fas fa-home"></i> Return to Main Dashboard</a>
    </div>
</div>

</body>
</html>