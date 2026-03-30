<?php
// Top of every page: session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Guest';
?>
<head>
    <style>
        /* --- EDUZONE PRO NAV & AUTH STYLES --- */

        /* 1. Pro Navigation Buttons */
        .pro-btn {
            border: 1px solid #fbbf24 !important; /* Gold Border */
            background: rgba(251, 191, 36, 0.05) !important;
            color: #fbbf24 !important;
            transition: all 0.4s ease;
        }

        .pro-btn:hover {
            background: #fbbf24 !important;
            color: #000 !important;
            box-shadow: 0 0 15px rgba(251, 191, 36, 0.4);
        }

        /* 2. Registration Highlight (The "Call to Action") */
        .register-highlight {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%) !important;
            color: white !important;
            border: none !important;
            font-weight: 800 !important;
            padding: 10px 25px !important;
        }

        /* 3. User Profile Pill (Next to Logout) */
        .user-display {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 5px 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-left: 15px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary, #6366f1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: white;
            font-size: 0.8rem;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
        }

        /* 4. Dropdown "Pro" Indicator */
        .dropdown-content a i.fa-crown {
            color: #fbbf24;
            margin-right: 8px;
        }

        /* 5. Locked Resource Overlay (For Guests) */
        .resource-card-locked {
            position: relative;
            overflow: hidden;
        }

        .pro-lock-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            color: #fbbf24;
            text-align: center;
            padding: 20px;
        }

        .pro-lock-overlay i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        /* 6. Theme Specific Adjustments */
        body.light-mode .user-display {
            background: rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.1);
        }

        body.light-mode .user-name {
            color: #333;
        }
        /* --- PRO UI ENHANCEMENTS --- */
        .pro-tag {
            background: linear-gradient(to right, #f59e0b, #fbbf24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }

        .user-pill-container {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            padding: 2px 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            background: #6366f1;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }

        .register-pro {
            background: #6366f1 !important;
            color: white !important;
            font-weight: bold !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        }

        .pro-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .resume-btn {
            background: #fbbf24;
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .card-locked {
            filter: grayscale(0.8);
            opacity: 0.7;
            cursor: not-allowed !important;
        }

        .lock-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ef4444;
            color: white;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
        }

        .pro-check {
            color: #22c55e;
            font-size: 0.9rem;
            margin-left: 5px;
        }
    </style>
</head>
<div class="headercont">
    <div class="title">
        <h1>
            <img src="images/menu2.png" class="menu-icon" onclick="toggleMenu()">
            EDUZONE <span>PRO</span>
        </h1>
    </div>

    <div class="menuoption" id="menuoption">
        <div class="dropdown" id="materialsDropdown">
            <button class="but dropbtn" onclick="toggleDropdown(event)">LEARNING MATERIALS</button>
            <div class="dropdown-content" id="dropdownContent">
                <a href="pastpapers.php">Past Papers</a>
                <a href="e-books.php">E-BOOKS</a>
                <a href="modelpapers.php">Model Papers</a>
                <a href="videolessons.php">Lesson Videos</a>
                <a href="ictsoft.php">Software Forge</a>
            </div>
        </div>

        <button onclick="window.location.href='home.php'" class="but">HOME</button>

        <?php if($isLoggedIn): ?>
            <div class="dropdown pro-feature">
                <button class="but dropbtn pro-btn" style="color: #fbbf24; font-weight: 800;">
                    <i class="fas fa-crown"></i> PRO PANEL
                </button>
                <div class="dropdown-content">
                    <a href="account.php">My Profile</a>
                    <a href="my-downloads.php">Saved Resources</a>
                    <a href="progress-tracker.php">Study Progress</a>
                    <a href="forum.php">Student Forum</a>
                    <hr style="border: 0.5px solid #333;">
                    <a href="logout.php" style="color: #ff4d4d;">Logout</a>
                </div>
            </div>
            
            <div class="user-display">
                <div class="user-avatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
                <span class="user-name">Hi, <?php echo htmlspecialchars($userName); ?></span>
            </div>

        <?php else: ?>
            <button onclick="window.location.href='login.html'" class="but">Login</button>
            <button onclick="window.location.href='register.html'" class="but register-highlight">Join Free Pro</button>
        <?php endif; ?>

        <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle Dark Mode">
            <span class="icon"></span>
        </button>
    </div>
</div>