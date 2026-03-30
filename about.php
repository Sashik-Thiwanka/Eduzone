<?php
session_start();
$isLoggedIn = isset($_SESSION['user_name']);
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['user_name']) : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | EduZone Elite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --gemini-gradient: linear-gradient(135deg, #4285f4 0%, #9b72cb 50%, #d96570 100%);
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.1);
            --bg: #050505;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* --- BACKGROUND ANIMATION --- */
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, #1a1a2e 0%, #050505 100%);
            z-index: -1;
        }

        /* --- HERO SECTION --- */
        .about-hero {
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 10%;
            background: radial-gradient(circle at top, rgba(66, 133, 244, 0.15), transparent);
        }

        .gemini-badge {
            background: var(--glass);
            border: 1px solid var(--border);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            color: #9b72cb;
        }

        .about-hero h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(3rem, 8vw, 5rem);
            margin: 0;
            background: var(--gemini-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- LEADERSHIP SECTION --- */
        .leader-container {
            padding: 100px 10%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .leader-image {
            position: relative;
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .leader-image img {
            width: 100%;
            display: block;
            filter: grayscale(20%);
            transition: 0.5s;
        }

        .leader-image:hover img { filter: grayscale(0%); transform: scale(1.05); }

        .leader-info h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-family: 'Space Grotesk';
        }

        .leader-info .title-dev {
            color: #4285f4;
            font-weight: 700;
            text-transform: uppercase;
            display: block;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        .leader-info p {
            line-height: 1.8;
            color: #ccc;
            font-size: 1.1rem;
        }

        /* --- INTERACTIVE CARDS --- */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 50px 10%;
        }

        .v-card {
            background: var(--glass);
            border: 1px solid var(--border);
            padding: 40px;
            border-radius: 25px;
            transition: 0.3s;
        }

        .v-card:hover {
            border-color: #9b72cb;
            background: rgba(155, 114, 203, 0.05);
            transform: translateY(-10px);
        }

        .v-card i {
            font-size: 2.5rem;
            background: var(--gemini-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        /* --- FUNCTIONAL FOOTER NAVIGATION --- */
        .action-area {
            text-align: center;
            padding: 100px 10%;
            background: linear-gradient(to bottom, transparent, #0a0a0a);
        }

        .btn-home {
            padding: 20px 50px;
            font-size: 1.2rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            background: var(--gemini-gradient);
            border-radius: 50px;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(66, 133, 244, 0.3);
            transition: 0.3s;
        }

        .btn-home:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(155, 114, 203, 0.5);
        }

        .pro-links {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        .pro-links a {
            color: #666;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .pro-links a:hover { color: #fff; }

        @media (max-width: 900px) {
            .leader-container, .values-grid { grid-template-columns: 1fr; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>

    <section class="about-hero">
        <div class="gemini-badge">Powered by Intelligence. Driven by Vision.</div>
        <h1>Future of Learning.</h1>
        <p style="max-width: 600px; color: #999; font-size: 1.2rem; margin-top: 20px;">
            Inspired by <strong>Google Gemini</strong>'s revolutionary AI, EduZone is re-engineering how Sri Lankan students conquer A/L exams through data, speed, and precision.
        </p>
    </section>

    <section class="leader-container">
        <div class="leader-image">
            <img src="images/profpic.jpg" alt="Sashik Thiwanka" onerror="this.src='https://via.placeholder.com/600x800/111/fff?text=Sashik+Thiwanka'">
        </div>
        <div class="leader-info">
            <span class="title-dev">Founder & Lead Architect</span>
            <h2>Sashik Thiwanka</h2>
            <p>
                As the visionary behind EduZone, Sashik Thiwanka combined his passion for technology and education to build a platform that fills the gap between traditional classrooms and the digital future. 
            </p>
            <p>
                Inspired by the seamless intelligence of Google’s ecosystems, Sashik designed EduZone to be more than a website—it is a digital mentor for every ICT, Maths, and Science student in the nation.
            </p>
            <div style="margin-top: 30px; display: flex; gap: 20px;">
                <a href="#" style="color: #4285f4; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                <a href="#" style="color: #fff; font-size: 1.5rem;"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </section>

    <div class="values-grid">
        <div class="v-card">
            <i class="fas fa-bolt"></i>
            <h3>Instant Access</h3>
            <p>Speed is everything. Our servers ensure that model papers and e-books are in your hands in milliseconds.</p>
        </div>
        <div class="v-card">
            <i class="fas fa-brain"></i>
            <h3>Gemini Inspired</h3>
            <p>Utilizing structured data and AI logic to recommend the exact resources you need to improve your rank.</p>
        </div>
        <div class="v-card">
            <i class="fas fa-shield-halved"></i>
            <h3>Elite Security</h3>
            <p>Your progress is yours alone. Our Pro-encryption keeps your data safe as you climb the leaderboard.</p>
        </div>
    </div>

    <section class="action-area">
        <h2>Experience the New Standard</h2>
        <p style="margin-bottom: 40px; color: #666;">Step into the most advanced educational hub in Sri Lanka.</p>
        
        <a href="home.php" class="btn-home">Return to Dashboard &nbsp; <i class="fas fa-arrow-right"></i></a>

        <div class="pro-links">
            <a href="ictsoft.php">Software Forge</a>
            <a href="videolessons.php">Video Lessons</a>
            <a href="modelpapers.php">Model Papers</a>
            <?php if($isLoggedIn): ?>
                <a href="account.php" style="color: #fbbf24;">My Pro Account</a>
            <?php endif; ?>
        </div>
    </section>

</body>
</html>