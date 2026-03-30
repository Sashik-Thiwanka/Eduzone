<?php
session_start();
$isLoggedIn = isset($_SESSION['user_name']);
// Mock data for demonstration - in a real app, fetch these from your MySQL database
$downloadCount = 12; 
$streak = 5;
$style = "The Midnight Scholar"; // Logic: if most downloads happen after 8 PM
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learning Analytics | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --gemini-blue: #4285f4; --gemini-purple: #9b72cb; --bg: #030712; }
        body { background: var(--bg); color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }
        .container { padding: 40px 10%; }
        
        /* AUTH MOTIVATION OVERLAY */
        .blur-overlay {
            position: relative;
            <?php echo !$isLoggedIn ? 'filter: blur(8px); pointer-events: none;' : ''; ?>
        }
        
        .auth-card {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            z-index: 100; background: rgba(15, 23, 42, 0.95); padding: 40px;
            border-radius: 24px; text-align: center; border: 1px solid var(--gemini-purple);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5); width: 400px;
        }

        /* ANALYTICS CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 30px; }
        .stat-card { background: rgba(255,255,255,0.03); padding: 30px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); }
        .stat-card h1 { font-size: 3rem; margin: 10px 0; color: var(--gemini-blue); }
        
        .style-badge {
            background: linear-gradient(135deg, var(--gemini-blue), var(--gemini-purple));
            padding: 20px; border-radius: 20px; display: flex; align-items: center; gap: 20px; margin-top: 30px;
        }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
<div class="auth-card">
    <i class="fas fa-chart-line" style="font-size: 3rem; color: #fbbf24; margin-bottom: 20px;"></i>
    <h2>Unlock Your Persona</h2>
    <p>We analyze your download patterns to tell you if you're a "Strategic Planner" or a "Midnight Scholar."</p>
    <button onclick="window.location.href='register.html'" style="background:var(--gemini-blue); color:white; border:none; padding:15px 30px; border-radius:12px; font-weight:bold; cursor:pointer;">Join EduZone Pro Free</button>
</div>
<?php endif; ?>

<div class="container <?php echo !$isLoggedIn ? 'blur-overlay' : ''; ?>">
    <h1>Your Progress Dashboard</h1>
    
    <div class="style-badge">
        <i class="fas fa-brain fa-3x"></i>
        <div>
            <h2 style="margin:0">Learning Style: <?php echo $style; ?></h2>
            <p style="margin:5px 0 0 0">You are most productive between 8:00 PM and 11:00 PM. Keep it up!</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <p>Resources Mastered</p>
            <h1><?php echo $downloadCount; ?></h1>
            <small>Top 5% of Sri Lankan Students</small>
        </div>
        <div class="stat-card">
            <p>Study Streak</p>
            <h1><?php echo $streak; ?> Days</h1>
            <small>Don't let the flame go out!</small>
        </div>
        <div class="stat-card">
            <p>Estimated Readiness</p>
            <h1>84%</h1>
            <small>Based on Model Paper performance</small>
        </div>
    </div>

    <h2 style="margin-top:50px;">Download Activity Map</h2>
    
</div>
</body>
</html>