<?php
// pastpapers.php - Final Complete Version
session_start();

// Simulated data for demonstration (Replace with your actual authentication logic)
$is_logged_in = isset($_SESSION['user_id']);
$displayName = $is_logged_in ? htmlspecialchars($_SESSION['user_name'] ?? 'Scholar') : 'Guest';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EDUZONE Paper Vault</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ---------------------------------------------------- */
        /* 1. THEME & VARIABLES (SaaS/Dashboard Look) */
        /* ---------------------------------------------------- */
        :root {
            --color-primary: #0f172a;       /* Deep Slate/Navy for Header/Borders */
            --color-secondary: #1e293b;     
            --color-accent: #0f76e6;        /* Primary Blue for actions/focus */
            --color-accent-light: #38bdf8;
            --color-bg-main: #f1f5f9;       /* Light Gray background */
            --color-bg-card: #ffffff;
            --color-text-dark: #1e293b;
            --color-text-light: #f8fafc;
            --color-subtext: #64748b;
            --color-success: #10b981;
            --color-danger: #ef4444;
            --shadow-lg: 0 15px 30px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.05);
            --header-height: 64px;
        }

        /* ---------------------------------------------------- */
        /* 2. BASE STYLING & NAVIGATION */
        /* ---------------------------------------------------- */
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg-main);
            color: var(--color-text-dark);
            margin: 0;
            padding-top: var(--header-height);
            transition: all 0.3s;
        }
        .headercont {
            position: fixed; top: 0; left: 0; width: 100%; height: var(--header-height); 
            display: flex; justify-content: space-between; align-items: center; padding: 0 25px;
            background-color: var(--color-primary); 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); z-index: 1000;
        }
        .title h1 { font-size: 1.5em; margin: 0; color: var(--color-accent-light); font-weight: 700; }
        .menuoption { display: flex; align-items: center; }
        
        .but, .dropbtn, .welcome, .logout {
            padding: 8px 12px; margin-left: 10px; border: 1px solid transparent;
            color: var(--color-text-light); cursor: pointer; border-radius: 6px; font-weight: 500;
            transition: all 0.2s ease-in-out; text-decoration: none; display: inline-flex; align-items: center;
            background-color: transparent;
        }
        .but:hover, .dropbtn:hover { background-color: var(--color-secondary); border-color: var(--color-accent); }

        .dropdown { position: relative; }
        .dropdown-content {
            display: none; position: absolute; background-color: var(--color-bg-card);
            min-width: 220px; box-shadow: var(--shadow-lg); z-index: 10;
            border-radius: 8px; padding: 10px 0; top: calc(100% + 10px); 
            opacity: 0; transform: translateY(-10px); transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .dropdown-content a { color: var(--color-text-dark); padding: 12px 18px; text-decoration: none; display: block; font-weight: 500;}
        .dropdown-content a:hover { background-color: #f1f5f9; color: var(--color-accent); }
        .dropdown-content.show { opacity: 1; transform: translateY(0); display: block; }
        
        /* ---------------------------------------------------- */
        /* 3. MAIN CONTENT: Tabs & Cards */
        /* ---------------------------------------------------- */
        .paper-container { max-width: 1400px; margin: 40px auto; padding: 0 30px; }
        h2 { color: var(--color-text-dark); text-align: left; margin-bottom: 30px; font-size: 2em; font-weight: 800; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px;}

        .filter-tabs { display: flex; gap: 10px; margin-bottom: 30px; }
        .filter-tab {
            background-color: var(--color-bg-card); padding: 12px 20px; border-radius: 8px;
            cursor: pointer; font-weight: 600; color: var(--color-subtext); border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        .filter-tab.active, .filter-tab:hover {
            background-color: var(--color-accent);
            color: var(--color-text-light);
            border-color: var(--color-accent);
            box-shadow: 0 5px 15px rgba(15, 118, 230, 0.3);
            transform: translateY(-2px);
        }

        .filter-bar {
            background-color: var(--color-bg-card); padding: 25px; border-radius: 12px;
            box-shadow: var(--shadow-md); margin-bottom: 40px;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; align-items: flex-end;
        }
        
        .filter-bar select, .filter-bar button, .filter-bar input[type="text"] {
            width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; 
            font-size: 1em; background-color: var(--color-bg-main); color: var(--color-text-dark);
            transition: border-color 0.2s; 
        }

        /* CLEAR FILTERS BUTTON STYLING */
        .clear-filters-btn {
            background-color: #fca5a5; /* Light Red */
            color: var(--color-text-dark);
            border: 1px solid #f87171;
            font-weight: 600;
        }
        .clear-filters-btn:hover {
            background-color: #f87171; /* Darker Red on hover */
            color: var(--color-text-light);
        }

        .results-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); 
            gap: 25px;
        }

        .paper-card {
            background-color: var(--color-bg-card);
            border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px;
            display: flex; flex-direction: column; 
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: var(--shadow-md);
        }
        .paper-card:hover { 
            box-shadow: var(--shadow-lg); 
            transform: translateY(-5px); 
            border-color: var(--color-accent);
        }
        .paper-actions {
            display: flex; justify-content: flex-end; margin-top: auto; padding-top: 15px; border-top: 1px solid #f1f5f9;
        }
        .paper-actions a {
            padding: 10px 18px; margin-left: 10px; border-radius: 6px; text-decoration: none; font-weight: 600;
        }
        .paper-actions .view-btn { background-color: var(--color-primary); color: var(--color-text-light); }
        .paper-actions .download-btn { background-color: var(--color-accent); color: var(--color-text-light); }
        
        /* New Marking Scheme Button Style */
        .paper-actions .scheme-btn { 
            background-color: var(--color-success); /* Green for marking scheme */
            color: var(--color-text-light); 
        }
        
        /* Side Menu Styles */
        .side-menu {
            height: 100%; width: 0; position: fixed; z-index: 1001; top: 0; left: 0;
            background-color: var(--color-bg-card); overflow-x: hidden;
            padding-top: var(--header-height); transition: 0.4s ease-in-out; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.2);
        }

        .side-menu a {
            padding: 15px 25px; text-decoration: none; font-size: 1.1em;
            color: var(--color-text-dark); display: block; transition: 0.3s;
            border-bottom: 1px solid #f1f5f9;
        }

        .side-menu .closebtn {
            position: absolute; top: 0; right: 25px; font-size: 36px;
            margin-left: 50px; padding: 10px; border-bottom: none;
        }

        .side-menu a:hover { color: var(--color-text-light); background-color: var(--color-accent); }
        .side-menu a.active-link { color: var(--color-accent); font-weight: 700; background-color: #e0f2fe; }

        /* Smart Suggestion Box Styles */
        #smartSuggestion {
            background-color: #fff3cd; /* Light warning yellow */
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1em;
            font-weight: 500;
        }
        #smartSuggestion.hidden {
            display: none;
        }
        #suggestDownloadBtn {
            background-color: #0f76e6;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
            margin-left: 20px;
            white-space: nowrap;
        }
        #suggestDownloadBtn:hover {
            background-color: #0c63e4;
        }
    </style>
</head>
<body id="body">

    <div class="headercont">
        <div class="title">
            <h1>
                <img src="images/menu2.png" class="menu-icon" onclick="toggleMenu()" alt="Menu Icon" style="width: 24px; height: 24px; margin-right: 15px;">
                PAPER VAULT
            </h1>
        </div>
        
        <div class="menuoption" id="menuoption">
            <div class="dropdown" id="materialsDropdown">
                <button class="but dropbtn" onclick="toggleDropdown(event)">LEARNING MATERIALS</button>
                <div class="dropdown-content" id="dropdownContent">
                    <a href="pastpapers.php" style="font-weight: 700; color: var(--color-accent);">Past Papers</a>
                    <a href="e-books.php">E-BOOKS</a>
                    <a href="modelpapers.php">Model Papers</a>
                    <a href="lessonvideoes.php">Lesson Videos</a>
                </div>
            </div>

            <a href="home.php" class="but">HOME</a>
            
            <?php
                if ($is_logged_in) {
                    echo '<a href="account.php" class="but welcome">Welcome, ' . $displayName . '</a>';
                    echo '<button onclick="window.location.href=\'logout.php\'" class="but logout">Logout</button>';
                } else {
                    echo '<a href="login.html" class="but">Login</a>';
                    echo '<a href="register.html" class="but">Register</a>';
                }
            ?>
        </div>
    </div>
    
<nav class="side-menu" id="sideMenu">
    <a href="javascript:void(0)" class="closebtn" onclick="toggleMenu()"> &times; </a>
    <a href="home.php">Home</a>
    <a href="pastpapers.php" style="color: var(--color-accent); font-weight: bold;">Past Papers</a>
    
    <a href="e-books.php">📚 E-Books & Notes</a>
    <a href="modelpapers.php">📝 Model Papers</a>
    <a href="lessonvideos.php">▶️ Lesson Videos</a>
    <a href="quizzes.html">✍️ Practice Quizzes</a>
    
    <div style="height: 1px; background-color: #e2e8f0; margin: 15px 10px;"></div>

    <?php if ($is_logged_in): ?>
        <a href="account.php">My Account</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    <?php endif; ?>
</nav>
    
    <div class="paper-container">
        <h2>📄 Past Paper and Model Paper Search</h2>

        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all" onclick="quickFilter('all')">📚 All Papers</div>
            <div class="filter-tab" data-filter="latest" onclick="quickFilter('latest')">⭐ Latest Papers (2025)</div>
            <div class="filter-tab" data-filter="popular" onclick="quickFilter('popular')">📈 Most Popular</div>
            <div class="filter-tab" data-filter="solved" onclick="quickFilter('solved')">✅ Solved Guides</div>
        </div>

        <div class="filter-bar">
            <div class="filter-group">
                <label for="level">Level</label>
                <select id="level" onchange="updateFilters()">
                    <option value="">Select Level</option>
                    <option value="al">Advanced Level (A/L)</option>
                    <option value="ol">Ordinary Level (O/L)</option>
                </select>
            </div>

            <div class="filter-group" id="streamGroup" style="display: none;">
                <label for="stream">Stream</label>
                <select id="stream" onchange="updateFilters()">
                    <option value="">All Streams</option>
                    <option value="maths">Physical Science (Maths)</option>
                    <option value="bio">Biological Science</option>
                    <option value="commerce">Commerce</option>
                    <option value="arts">Arts</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="subject">Subject</label>
                <select id="subject" onchange="handlePaperSelection()">
                    <option value="">All Subjects</option>
                </select>
            </div>
            <div class="filter-group"><label for="year">Year</label><select id="year" onchange="handlePaperSelection()"><option value="">All Years</option></select></div>
            <div class="filter-group"><label for="paperType">Paper Type</label><select id="paperType" onchange="handlePaperSelection()"><option value="">All Types</option><option value="1">Paper I</option><option value="2">Paper II</option><option value="model">Model</option></select></div>
            
            <div class="filter-group search-input" style="grid-column: span 2;">
                <label for="keyword">Keyword Search (Topic, Chapter, Exam)</label>
                <input type="text" id="keyword" list="keyword-suggestions" placeholder="e.g., Electricity, Chemistry 2023" oninput="applyFilters()">
                <datalist id="keyword-suggestions"></datalist>
            </div>

            <div class="filter-group" style="grid-column: span 1;">
                <button onclick="handlePaperSelection()">🚀 Apply Filters</button>
            </div>
            
             <div class="filter-group" style="grid-column: span 1;">
                <button class="clear-filters-btn" onclick="clearAllFilters()">❌ Clear Filters</button>
            </div>
        </div>

        <div class="paper-results">
            
            <div id="smartSuggestion" class="hidden">
                <p>🤖 **Contextual Match:** You've selected Paper I for this set. Download the corresponding **Paper II** now to complete the set.</p>
                <button id="suggestDownloadBtn" onclick="suggestDownloadPaper2()">⬇️ Download Paper II</button>
            </div>
            
            <div id="resultsList" class="results-grid">
                </div>
            
        </div>
    </div>


    <script>
        // --- DATA SETUP ---
        const DRIVE_BASE_LINK_DOWNLOAD = "https://drive.google.com/uc?export=download&id=";
        const DRIVE_BASE_LINK_VIEW = "https://drive.google.com/file/d/"; 

        const FILE_ID_PHYSICS_2000_P1 = '1hlRu9OjrbLhJ-Nc2SabMcT_CaO2FrZf5';
        const FILE_ID_PHYSICS_2000_P2 = '1UlbEfxY9Zjj1ZvLHF3vfTMeH55sn-VFS';
        const FILE_ID_PHYSICS_2000_MS = '1oUXE_-HcyG1e5EcE7F-r_4eNpjME3rON';

        const FILE_ID_PHYSICS_2001_P1 = '1xYRJw93_0yTrANz3d9BLsItOXasJrOF9';
        const FILE_ID_PHYSICS_2001_P2 = '1by2ldihBIwOzTJslGkuuJuF_Q1AZe2y4';
        const FILE_ID_PHYSICS_2001_MS = '1nUfy7OZ0NAT_iVrwLEOPThekipHJrAKt';

        const FILE_ID_PHYSICS_2002_P1 = '1gPZQ5XLrcfC-BDoZz6vWAO-9XCDOESmp';
        const FILE_ID_PHYSICS_2002_P2 = '1DnmdDWnFBx9Nvmsz9lxLDFg497SPFnwb';
        const FILE_ID_PHYSICS_2002_MS = '1WPhFOHcPApHTkAHk28pE_Cy6KfOf1QnE';

        const FILE_ID_PHYSICS_2003_P1 = '1p6hS2VXTdOBggtPgvllhhX1XD2Bk6Oek';
        const FILE_ID_PHYSICS_2003_P2 = '1aOfewCO5iWV-7XK9VzxD6hij19CJZePR';
        const FILE_ID_PHYSICS_2003_MS = '1lV1IIB5uzZkFSZyQvvROMkJ3snY2gCR2';

        const FILE_ID_PHYSICS_2004_P1 = '1YT-uvTnBpF7HeJ-lMM2kCBuzD5eGrEOP';
        const FILE_ID_PHYSICS_2004_P2 = '1EQMm4ADX_AhYWGGvwbzDPJQVKa9Kz1Gb';
        const FILE_ID_PHYSICS_2004_MS = '1vK2qU6Wl4D0pjTk16kDso_0JIwpEaqaF';

        const FILE_ID_PHYSICS_2005_P1 = '1ynhhgSVFj4Re0eaYcLYiBIZzZfYbWKgQ';
        const FILE_ID_PHYSICS_2005_P2 = '1usUmhaNewvudMGiEExCp3GNDIxJOVBWJ';
        const FILE_ID_PHYSICS_2005_MS = '1FFwCN2mFp-g1rwwfO2E7WA6ZaswYA5h9';

        const FILE_ID_PHYSICS_2006_P1 = '1uYJ1YPq6QoA-pnuiBisaIzPfJevCeXQt';
        const FILE_ID_PHYSICS_2006_P2 = '1Aq1bgPUfAsEh9eHeqlvQzJdQ8FzwLAMb';
        const FILE_ID_PHYSICS_2006_MS = '1SqnyIALACBM8AgXukA_gdreM4Sd3nchI';

        const FILE_ID_PHYSICS_2007_P1 = '1ZdKMb2vSEjPu7cqo4OU2ZRiRAzyDeZPp';
        const FILE_ID_PHYSICS_2007_P2 = '1W2_aRB4Wwgs9Ax8N02u3UL3lCgn923F3';
        const FILE_ID_PHYSICS_2007_MS = '1XhPxJceRJLVkHCdjS9B_YZAjjvxd_smc';

        const FILE_ID_PHYSICS_2008_P1 = '1Cc4rpNXI94TJ6lk0Tt-_X_6Ki9WyZSlh';
        const FILE_ID_PHYSICS_2008_P2 = '1WGkAzx91kb9EwiAJunZURiH_JCjpPaEa';
        const FILE_ID_PHYSICS_2008_MS = '1joqG7zbMFayHR49LnweiLirBG7T1MAyR';

        const FILE_ID_PHYSICS_2009_P1 = '1f15hHhx_kZLVuNXklyRtFiYcGzB5zZ7Q';
        const FILE_ID_PHYSICS_2009_P2 = '1FByhaZsVXsWZfBtJeFfiMwLrDZlGUTGy';
        const FILE_ID_PHYSICS_2009_MS = '1FDGjvFvcRFm4Fu5UC_lu3uygDNtLvoXL';

        const FILE_ID_PHYSICS_2010_P1 = '1pMuFHOQW83Vh608VEfesz8Jgnjdx_08s';
        const FILE_ID_PHYSICS_2010_P2 = '1Fp-MTcEilECkUNzBObvuZxX1NMeIaUmq';
        const FILE_ID_PHYSICS_2010_MS = '1qEd1uY2xQdMoGno79Vu9IEPpaL2hIMBa';

        const FILE_ID_PHYSICS_2011_P1 = '1N_66leRL1AcG6qbxTM94ZEGiuOgWV7U8';
        const FILE_ID_PHYSICS_2011_P2 = '1F8tQLqt3oOCJun-1g74yIjWZuLo-XmfQ';
        const FILE_ID_PHYSICS_2011_MS = '1pFJrQwcV0QiiXhHIooaIJrweN9sK9oDV';

        const FILE_ID_PHYSICS_2012_P1 = '1EiG-eB2wsiEUe-QpkjMlGgw6TmmdFx4o';
        const FILE_ID_PHYSICS_2012_P2 = '1ViLxIxM8nWrORd2f_hbDc-GispuN0pMQ';
        const FILE_ID_PHYSICS_2012_MS = '1MJYNtkEzu2auwXe-vBEGVL70FxKHb_S8';

        const FILE_ID_PHYSICS_2013_P1 = '1iqaeqKsjkG2Wma9rjcLwrGed31s4ZIpk';
        const FILE_ID_PHYSICS_2013_P2 = '1t4VQnDv-pQthfIBhCtfO-CpL4FXDS16K';
        const FILE_ID_PHYSICS_2013_MS = '1gjQ1viEHb0TJbO6E14ARcJu3vb6Pj1Tq';

        const FILE_ID_PHYSICS_2014_P1 = '1cnYoYvslwdGtNGqAHTz3at0mLKAsoTbK';
        const FILE_ID_PHYSICS_2014_P2 = '1yINNzEXiDt8-XH1cYzHJC0XZTR3tx_Th';
        const FILE_ID_PHYSICS_2014_MS = '1SvItCYIjvTKwEcLsUsBjuJ-mF9Fs-G6o';

        const FILE_ID_PHYSICS_2015_P1 = '1zn7DznzQthzTf-p166a98JC8jAzSugsQ';
        const FILE_ID_PHYSICS_2015_P2 = '1ubyEPzyFBNuOjYclJdqB5m0bIuV2beO8';
        const FILE_ID_PHYSICS_2015_MS = '1aHhqiakZ4s1ULKv4PmiSCbMaF1kK4OZt';

        const FILE_ID_PHYSICS_2016_P1 = '1-v4u8rcacAwbiZtbH5LwA-pLEQqkD2NP';
        const FILE_ID_PHYSICS_2016_P2 = '1oDGJWUnkjPoeUuA9J-ul_uGEzHoVUHDL';
        const FILE_ID_PHYSICS_2016_MS = '1zLylCiNYN1qX1RRtRoiPAR-2UtPvPr7D';

        const FILE_ID_PHYSICS_2017_P1 = '1txQlrLy4nLGYKIBZOzfEbwi0wApIsBZZ';
        const FILE_ID_PHYSICS_2017_P2 = '1RyJs5N8q5rj_8vr3H-2eImrqIB1DDGB6';
        const FILE_ID_PHYSICS_2017_MS = '13T6zwswvPtQWupcbGhoNk5F9NNs9-kCZ';

        const FILE_ID_PHYSICS_2018_P1 = '1Oxv-zxUTj7DR4U0sGORjxJcnRsImyb_M';
        const FILE_ID_PHYSICS_2018_P2 = '1vVeHp3NJKt-ZxBL6tEDpzWJt8t7I_d3t';
        const FILE_ID_PHYSICS_2018_MS = '1KDmAerafbI6aFKI8A92Gfp9eIdQMUfLF';

        const FILE_ID_PHYSICS_2019_P1 = '1gKzzXhR1jvxkwWT1y9xDJVYzZdJ4F3v5';
        const FILE_ID_PHYSICS_2019_P2 = '1326U5asHZT65UmvoB47hHD56rzvhPCkc';
        const FILE_ID_PHYSICS_2019_MS = '1NhDJcCBcIlfk6hM4RWvrBE_EP7qwbN0g';

        const FILE_ID_PHYSICS_2020_P1 = '1NEYT-Iq6IfacaY-SmVXKlzmv88a_ot6c';
        const FILE_ID_PHYSICS_2020_P2 = '1lqRwaTIoAEBAIBxD_5IEirQ1KfG5xCBc';
        const FILE_ID_PHYSICS_2020_MS = '11mYjtmiV19u8vJUW-ilyWpKyR50RnoHn';

        const FILE_ID_PHYSICS_2021_P1 = '1GzmJBJvd8o5BVJVcY5gaLRwVTihDKd7m';
        const FILE_ID_PHYSICS_2021_P2 = '17ebU6vpGdj40S0DHvdbZ-cAkic2oBCyP';
        const FILE_ID_PHYSICS_2021_MS = '1BRFawhcORIXpSncD64SvGoLYHqjnVPfX';

        const FILE_ID_PHYSICS_2022_P1 = '1zcKMxIG4Ax8ZdSBxaXImoPRREDOKDYmd';
        const FILE_ID_PHYSICS_2022_P2 = '1MDStHvtlTSdbem_cg761j8ZwU7a-m35g';
        const FILE_ID_PHYSICS_2022_MS = '11xOwiKwyysxiEcCVohoE_D-p8c8MM44j';

        const FILE_ID_PHYSICS_2023_P1 = '1bRZ05NJ8zPLmZ8Mq-vn8DRwDY7tSXmCE';
        const FILE_ID_PHYSICS_2023_P2 = '1cF8lTQ-GyQNLsgpkEkVSBe-CdkmLbgEt';
        const FILE_ID_PHYSICS_2023_MS = '1OV8m-bNG_7atk5vjdzNNHKKLi-8r_MPe';

        const FILE_ID_PHYSICS_2024_P1 = '1R6GixF7kc1t3RogJ49-_jm2GtRGwMz5h';
        const FILE_ID_PHYSICS_2024_P2 = '14U5uYJA1u1raRRH_r3fgdR5UUNJXullo';
        const FILE_ID_PHYSICS_2024_MS = '1UlY5601CTFBEcaLDRnHn_X0DfLNQm7BV';

        // --- ICT PAST PAPER FILE IDS ---
        const ICT_2011_P1 = '1UYvxUSfasqBblJf9Hpb3RjIdvYNa-Lfq';
        const ICT_2011_P2 = '12t0kJZmVGPaRc04CdGYZFDJdKpgQo6_d';
        const ICT_2011_MS = '18558iqyRS5qPyR6mNcDxWl3W8hDdUHSO';

        const ICT_2012_P1 = '1WWezZdiLgbSlScTmaw2T2IOc3KGJPws-';
        const ICT_2012_P2 = '185kwwj_QoJFV80ou0t_2mOM8WOeNVjne';
        const ICT_2012_MS = '1vdF8k85oOTYgR2YJqs7SMBlCe3Lnh7oG';

        const ICT_2013_P1 = '1w-ux3UEtI_bnwWKCMAvSjoNs7nQ_yxmM';
        const ICT_2013_P2 = '1erjKccjCw1OhCNYBmtFIok5DuFqTMY13';
        const ICT_2013_MS = '1xBDDpOl-BOQOv4-u-JOShoFWX8ojBs99';

        const ICT_2014_P1 = '1aZSgH-IDc3uvHJ3_Cz7UdG43fBFxjhL5';
        const ICT_2014_P2 = '1htpik9u5YHNb-_1Jw9Xi_EEBaKMi5C8n';
        const ICT_2014_MS = '1gFljR7T8uI0GsUNtLaSkqxxDklw03Nks';

        const ICT_2015_P1 = '1v683sfdWfdcRprtVgNHWuDg2v0GrEu25';
        const ICT_2015_P2 = '1vW-qUOdpM7lyWURfr-DDD1MgkJVs4MHQ';
        const ICT_2015_MS = '148BhUdUVv1ReuzEucovtrIGndZ5O4g9x';

        const ICT_2016_P1 = '1GIraPJB6g3Ip-y3csBn1ZDgzPPyQ-Q2k';
        const ICT_2016_P2 = '1JSyLb-sVornZXT2L1Xo9EiEHQiqBel_t';
        const ICT_2016_MS = '1AmsVYepgQqvfRRQVfMCTORXuFJdKIwBG';

        const ICT_2017_P1 = '1GMPGZ_Y05aYWNauOXSPN1YOD4O13vCh-';
        const ICT_2017_P2 = '1WSwI7lJUgu8WJUf-VZhzdP7cJrjpjsdY';
        const ICT_2017_MS = '1k9RWUHJSE1UpT7-FOY9T6_Kc3bTbxBGN';

        const ICT_2018_P1 = '1ALo3rzN5YIPa0aX_sR8qG--z8Aif8eSV';
        const ICT_2018_P2 = '1TGHuClGVcOtRyxHxT39_BWR1pY168nJk';
        const ICT_2018_MS = '1vrAKyWq2-l1KD7u4z0PEDKeMIXoypbWt';

        const ICT_2019_P1 = '1rOqSD9EFZPXYzw8myXmO1ZzNixW2abCZ';
        const ICT_2019_P2 = '1Q0B-0EyCEMwH74PFQw05G278J-EyBDkW';
        const ICT_2019_MS = '1ObX1CB4_k_eSfeVY2-mUfldkXiD7TRMX';

        const ICT_2020_P1 = '1NGbFKSrARwSt3biCwz87b8RRVWN-acQL';
        const ICT_2020_P2 = '1B-vfzrVnaDFlycOvGftbbOm1ofbQvuql';
        const ICT_2020_MS = '1mwMUWPLii9jDIp-svFCL744nQmMk99X1';

        const ICT_2021_P1 = '1O34bICfvCCNYIWZvx5AfjNo8Kal9NBog';
        const ICT_2021_P2 = '1beJpqk8B-PX1vz1AakLflN59G7el5891';
        const ICT_2021_MS = '1TmVvFxd1RrUJQJLn1Zqgo2TXZMXflN0E';

        const ICT_2022_P1 = '1beJpqk8B-PX1vz1AakLflN59G7el5891';
        const ICT_2022_P2 = '1x2U4k5_JzZJ5c03kGlHgx_HHIY4ioKuT';
        const ICT_2022_MS = '1DQgUa1KbjveDO5E4EhtZc-vYNqEc3zMG';

        const ICT_2023_P1 = '1FuGu2Hs8K_5ikbFIoYtG6UppgJbHsyE_';
        const ICT_2023_P2 = '10ENq_KK97SCIlddoURySTM3OhwrHMudp';
        const ICT_2023_MS = '1iRlYSxy0ObtOsjW-6a02inHXnevhOKdI';

        const ICT_2024_P1 = '1C4KvCDW7btGfrNsba9_AwUBHHi3xPH-7';
        const ICT_2024_P2 = '1QFpl0GnIQcZ3tU2XKNxCziHlGERcBTZD';
        const ICT_2024_MS = '1of6fDTAvyufTeiKMN4ps5TTUPqBW_eDc';

        // --- PURE MATHS (2000-2010) ---
        const PURE_2010_P2 = '1QZanIlp89CaAYknaPXCq3ivDy8Ce3Bx5'; const PURE_2010_MS = '1m6wwVeRqGA1dWjofRiReX5g_5le5gEho';
        const PURE_2009_P2 = '1wxFrlp7EAaP0Sc7lhqbYUJVGOPz6th5B'; const PURE_2009_MS = '1Bp-9yGABN04nwELqitsvv4LvPNcIz8y9';
        const PURE_2008_P2 = '1ewk0fJA67weipveRaQ8OG31ZYxq7BdS5'; const PURE_2008_MS = '1XUHPgP7kVCR_eUe_zbiUqCFJl00NluE_';
        const PURE_2007_P2 = '1vGu-NyZQTLcc7RyMPzc8K27cmw9_IvvQ'; const PURE_2007_MS = '1WinICsv0w8YuXCJ4U1dCPkmnpHvJKJn7';
        const PURE_2006_P2 = '1ziionO_PCmzy9f9seckYEZbLYC-E_LTq'; const PURE_2006_MS = '1_PGjFc3QShlHkSmGA_RhEUub7GNGHb33';
        const PURE_2005_P2 = '1bsXsuGLWiDxC3UsWuAKQaz-FKBbXEsqM'; const PURE_2005_MS = '1bqkv_anL-ROFARiC1CeyDBn8uWOshvIp';
        const PURE_2004_P2 = '1rxGJ8Pks8m1p0UXBNiqclr8bPR6-xFlo'; const PURE_2004_MS = '1GzupowSrHMI7_Yi7q3Bf7KbXqKl3ix1u';
        const PURE_2003_P2 = '1JH17r6N1U8-svKfCWeePG9CRDVsCvN1j'; const PURE_2003_MS = '1_P8WeoOFw81uSrWkli05g7AoUnF6XvSs';
        const PURE_2002_P2 = '1_JYC5bcplhkkC3m_w8GEZfAbC-TGUqbN'; const PURE_2002_MS = '1eWFvtMFcb60Dj2cmtVNwsgpvhRE-Sfoo';
        const PURE_2001_P2 = '1s-wOsTuCm-0Tk3yr15PG89jwvozjhRJt'; const PURE_2001_MS = '1U2a1ocjlRyYIEm10hMqKv61EgXJXD9J6';
        const PURE_2000_P2 = '1c_9voJ7rdjiiwh2mKJ_fmI5MTdtO9Pv4'; const PURE_2000_MS = '1o2KsuNFOqMiSUHD-4rZ4ncdS4-Jzyozs';

        // --- APPLIED MATHS (2000-2010) ---
        const APPLIED_2010_P2 = '1T1OvuIyg-w24pnx3nm-UkH64lX1UgaMc'; const APPLIED_2010_MS = '1YlhRL5uQ3coFjCcjBcbIU5XM0xNMxg21';
        const APPLIED_2009_P2 = '1aVcAxu_pAr-5P9tv2-oGy0P2h2zHcA8m'; const APPLIED_2009_MS = '1qDJ8XrWS1jCFs09ZNml1vmJn8xsKAq5-';
        const APPLIED_2008_P2 = '1p1gHpckUmlKLcePZ2ev5jnnG8aQ64Cxw'; const APPLIED_2008_MS = '1iaXsZkuaDFxWcC24swkfXW1MIlpY2EKw';
        const APPLIED_2007_P2 = '184AmchhVaMgWPGs_bqlyFE8knXLHJ-aB'; const APPLIED_2007_MS = '1pS8bv8JXTr2C4BTgC3J9Un-Gt73MdrW9';
        const APPLIED_2006_P2 = '1prrh79QLnIWLvFkI5TDoPaNk_Bb0uuEI'; const APPLIED_2006_MS = '1bqkv_anL-ROFARiC1CeyDBn8uWOshvIp';
        const APPLIED_2005_P2 = '10Mj3m0md1q6QwnxJtiAyAZgw8lOHCy_V'; const APPLIED_2005_MS = '1VKY8tM1Cr33gWUxe7woOB3G19E-cV4ai';
        const APPLIED_2004_P2 = '1xIH6tJBq_dLVccNXXT57nqMQmuUQ7zI9'; const APPLIED_2004_MS = '1gObM4qrklx25nwwS10M3oNWW0yk9ifwg';
        const APPLIED_2003_P2 = '1DwwTy4uhRTq7fwbnMJzDrmpps1MIp6sL'; const APPLIED_2003_MS = '1pTcYiPdT0ouHAYXZVsHqoouQKRyAcbZc';
        const APPLIED_2002_P2 = '1KlOZc_hwrQSv4K2c2c9JiIG3V1-kCgEp'; const APPLIED_2002_MS = '1Xlx3VxybT_y33S4MSLrg2-qAl-vh0Gc4';
        const APPLIED_2001_P2 = '1KlOZc_hwrQSv4K2c2c9JiIG3V1-kCgEp'; const APPLIED_2001_MS = '1nOkcubxvAQat0N6tSPxDIgglkOjyIRv2';
        const APPLIED_2000_P2 = '1A7Ltd38Kk95jYwAvxcOh0MBqKquK_ntH'; const APPLIED_2000_MS = '1jJwc8iTN_HA9UUemJPtu4MLJi8pYB3uf';

        // --- MATHS CONSTANTS (2011-2024) ---
        const PM_24_1 = '1y0BY0ouWgHms8pSv8dUnd_TqoAjDONFd'; const PM_24_2 = '1mpYAHjTaRmYS9svnTeeA0PdcoW5uUM1s';
        const AM_24_1 = '1oo14r5jUy-0APiBBGapKz1xyypjGkMvZ'; const AM_24_2 = '18a1aFnmIUzP9WxyWyxxk4-UZDpk3fujp';
        const MS_24 = '11iMukF8qso_-ahalvpMuhyfBBT-ecYjD';

        const PM_23_1 = '1XwD61OfVdjFTGCFUSyoTYzcKcfY3jySr'; const PM_23_2 = '1CUXsuVJjb4x-rvgvLDUs8xqxrXYRw3QB';
        const AM_23_1 = '1-Knb3R63AI4Vh8hyxR4vG3IKJ4lK4OUq'; const AM_23_2 = '1GLcTv0f0nkySOxtPaBGMSOG3NvYn3EfM';
        const MS_23 = '1nE9GlcXRtuM5edaUtUw-1p941PM8-CH-';

        const PM_22_1 = '1ZVsI_pfHlpVuK03xIdCXjmH3q4PvNYjK'; const PM_22_2 = '1MWGKzfJOTuyyIKRe6YEf-TlVvbRkmvJe';
        const AM_22_1 = '1zSed3uescjJUeuqjdY5Iry5l1Ax08RjV'; const AM_22_2 = '1oheCdA6aZoKZc2HZ8t9Zpq6A1-raCb0o';
        const MS_22 = '1YsUZjxJio5Tqizt7JSoi-1tpev2KDKW0';

        const PM_21_1 = '1s1k1cOL1dr9wu4i2umdkV5SNGnASnRlm'; const PM_21_2 = '12-vRAM3Um29ckou_QNOE8eqw_3DC7DfM';
        const AM_21_1 = '1SgZk3dtt6ougijDgWR4rMxcGJfAdm0Gm'; const AM_21_2 = '1INdUifgVFL7B3dW6dY5dY8EIpHkER4kl';
        const MS_21 = '1e_TVXfEAcRx2XsaudJIr3OtGNMPfAzWh';

        const PM_20_1 = '15n6DaY0uQkp0BJjtfL8Boyr8IRPTSMb_'; const PM_20_2 = '1CBT9HHW_R9p-nk-vQUYOPFFhISOCzpnh';
        const AM_20_1 = '1frbkvQMHbIcFH8uvdFd3ef3ramNFf-Ur'; const AM_20_2 = '1e6MSR8ugDdX21botpOBfrIz8oHkf70ez';
        const MS_20 = '18elzRGyRhwqBim6-8cLWq4Gs9iTrEbf8';

        const PM_19_1 = '1n8UyEK66cU4PXJ2o3PaR0dst4kla1KCg'; const PM_19_2 = '1fu2lcbCDN2f4GlbrAFvcF4t2fqutAXBS';
        const AM_19_1 = '16iMc6-t_Rm5_ubonpPCaRX_Qu3mdb_E3'; const AM_19_2 = '1-zk93I6krkHfE7AYtAFb-TUnjZ_UWSQC';
        const MS_19 = '16NKAAJM3c9iwi4FoWShfpqdX5UEVm0l0';

        const PM_18_1 = '1zWFiEIYcECcYf7zF0D0Yga5bIOyk2tcG'; const PM_18_2 = '1FmGOJtscjrd4QvtNzc3I72CseBFtk448';
        const AM_18_1 = '1FvfrR6DI6DjNtm8yl48-oGhrftXwTCc8'; const AM_18_2 = '1FRAGOsfhEHdklB7hpuiWkOep0rFb0P9P';
        const MS_18 = '1uAY90x_MlBiCGjy4c5Ai5PDNo6W-kEVr';

        const PM_17_1 = '1DACU0PE5obwazoeUSYtYb4M0BpuAEAxf'; const PM_17_2 = '14jaJ-_V0bRPtg8qsxvVv-Q1_kNTk05Mo';
        const AM_17_1 = '19dN7UzJIYBg94MUyQ5n4NP9ANDBY1dg3'; const AM_17_2 = '1UjqenKXx_2NQDLdv0m_TEWL8WpbLd8qs';
        const MS_17 = '1Crcthmx0pIHo4tCMIOYlbbzuFOo5FZ73';

        const PM_16_1 = '1Gc0BVrnFcsy429tDEodgvMp70DcWpi9H'; const PM_16_2 = '1fRE5eXl2OQIc5575orL0uxbtxjcRysXb';
        const AM_16_1 = '18uvlcXDZI6icnTOlnOwI8jjwcyFmerX9'; const AM_16_2 = '17KFe97-RIvc4FzNooXanRbJ4HL1UHQxk';
        const MS_16 = '1wStkhsV9EEWFtLGxiL3OygsHevVeWzUw';

        const PM_15_1 = '1F_-waSvdtc8-xtF1t80x3ApLQvYZPXCm'; const PM_15_2 = '1CShH7TJS71Z_ff67coxUwO_qSrB924pd';
        const AM_15_1 = '1z2ykjjLaQYcn8HqEypvFi7cqCpyQQtQg'; const AM_15_2 = '1PgAkbt-nTM4TXuH_MUemd5Yd4Fv-8on9';
        const MS_15 = '1BUQST4-Z9m9rwphAE5sqxBbGsGHt19Ba';

        const PM_14_1 = '1yWr_0Uez3IOpPbXuZQnJsUXCnM2o3tNt'; const PM_14_2 = '16DpTDizwnLL3WChswtwbieTNAMF5U8Wi';
        const AM_14_1 = '1wdKTRaK9V7W0fcJwz5Oin4MV6tQYohM_'; const AM_14_2 = '1s-aaEAEO5nQaEfCxkyk2SU8jL77FvlmC';
        const MS_14 = '1l2mRYJraDOcHSWDkZw6UaNRH6d6Tnbkh';

        const PM_13_1 = '1Rkinc0_KQ856nBzeiQIEXGPg9TigG8RR'; const PM_13_2 = '1iUg6mHRrNRhcEhLN2IyegE6XNucHxZAe';
        const AM_13_1 = '1QMC8Byn95xM6RrA4avhUX2X1AqmqISJc'; const AM_13_2 = '1egko9aMgPFeZkvPxgFohgdTJLnWji8f6';
        const MS_13 = '10Xv1OQUD-8rKnGLWXlyp99C65fkuaRkg';

        const PM_12_1 = '1W6qA8q_6zNSa2AKvQ9vqJ9ui-4csPBxn'; const PM_12_2 = '1zSmY13erCQ-cdNmVNRPQYecU2-iQ4krC';
        const AM_12_1 = '1Ta9JYtqMnE4k7qSg70tvBEDlAsz8Vnt4'; const AM_12_2 = '1bU0YqDUBJ64EQbqYtyPMhPVZZdxw-FGx';
        const MS_12 = '1IEHgzXjooD1F__T8RBhNcP_gI5SUyKdJ';

        const PM_11_1 = '1PJZ2xBDOftn-MNfXb8Dr0BkRuXLW9QdX'; const PM_11_2 = '1QlN_PgEc5wAFV-NdDJFHNuvTNW4Td5M3';
        const AM_11_1 = '1Z1QKuCpXi9ON-bYJ5a2pNsp6aH2GLgZa'; const AM_11_2 = '1Js6TX4uUcf6nYu11SDRDtI7PdW-VmM4v';
        const MS_11 = '1NT1Iog8uK0uXL9s5YgCt0EkZ7fUkA9DJ';

        const paperData = [
            // A/L Physics 2001 - The Test Case
            { 
                id: 104, 
                level: 'al', 
                subject: 'physics', 
                year: 2000, 
                type: 1, 
                title: 'A/L Physics 2000 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2000_P1, 
                paper2id: 105, 
                solved: false, 
                popular: 30 ,
                markingSchemeId: FILE_ID_PHYSICS_2000_MS
            },
            { 
                id: 105, 
                level: 'al', 
                subject: 'physics', 
                year: 2000, 
                type: 2, 
                title: 'A/L Physics 2000 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2000_P2, 
                paper2id: null, 
                solved: false, 
                popular: 30 ,
                markingSchemeId: FILE_ID_PHYSICS_2000_MS
            },
            { 
                id: 106, 
                level: 'al', 
                subject: 'physics', 
                year: 2001, 
                type: 1, 
                title: 'A/L Physics 2001 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2001_P1, 
                paper2id: 107, 
                solved: false, 
                popular: 30 ,
                markingSchemeId: FILE_ID_PHYSICS_2001_MS
            },
            { 
                id: 107, 
                level: 'al', 
                subject: 'physics', 
                year: 2001, 
                type: 2, 
                title: 'A/L Physics 2001 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2001_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2001_MS
            },
            { 
                id: 108, 
                level: 'al', 
                subject: 'physics', 
                year: 2002, 
                type: 1, 
                title: 'A/L Physics 2002 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2002_P1, 
                paper2id: 109, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2001_MS
            },
            { 
                id: 109, 
                level: 'al', 
                subject: 'physics', 
                year: 2002, 
                type: 2, 
                title: 'A/L Physics 2002 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2002_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2002_MS
            },
            { 
                id: 110, 
                level: 'al', 
                subject: 'physics', 
                year: 2003, 
                type: 1, 
                title: 'A/L Physics 2003 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2003_P1, 
                paper2id: 111, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2003_MS
            },
            { 
                id: 111, 
                level: 'al', 
                subject: 'physics', 
                year: 2003, 
                type: 2, 
                title: 'A/L Physics 2003 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2003_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2003_MS
            },
            { 
                id: 112, 
                level: 'al', 
                subject: 'physics', 
                year: 2004, 
                type: 1, 
                title: 'A/L Physics 2004 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2004_P1, 
                paper2id: 113, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2004_MS
            },
            { 
                id: 113, 
                level: 'al', 
                subject: 'physics', 
                year: 2004, 
                type: 2, 
                title: 'A/L Physics 2004 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2004_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2004_MS
            },
            { 
                id: 114, 
                level: 'al', 
                subject: 'physics', 
                year: 2005, 
                type: 1, 
                title: 'A/L Physics 2005 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2005_P1, 
                paper2id: 115, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2005_MS
            },
            { 
                id: 115, 
                level: 'al', 
                subject: 'physics', 
                year: 2005, 
                type: 2, 
                title: 'A/L Physics 2005 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2005_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2005_MS
            },
            { 
                id: 116, 
                level: 'al', 
                subject: 'physics', 
                year: 2006, 
                type: 1, 
                title: 'A/L Physics 2006 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2006_P1, 
                paper2id: 117, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2006_MS
            },
            { 
                id: 117, 
                level: 'al', 
                subject: 'physics', 
                year: 2006, 
                type: 2, 
                title: 'A/L Physics 2006 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2006_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2006_MS
            },
                        { 
                id: 118, 
                level: 'al', 
                subject: 'physics', 
                year: 2007, 
                type: 1, 
                title: 'A/L Physics 2007 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2007_P1, 
                paper2id: 119, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2007_MS
            },
                        { 
                id: 119, 
                level: 'al', 
                subject: 'physics', 
                year: 2007, 
                type: 2, 
                title: 'A/L Physics 2007 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2007_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2007_MS
            },
            { 
                id: 120, 
                level: 'al', 
                subject: 'physics', 
                year: 2008, 
                type: 1, 
                title: 'A/L Physics 2008 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2008_P1, 
                paper2id: 121, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2008_MS
            },
                        { 
                id: 121, 
                level: 'al', 
                subject: 'physics', 
                year: 2008, 
                type: 2, 
                title: 'A/L Physics 2008 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2008_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2008_MS
            },
                        { 
                id: 122, 
                level: 'al', 
                subject: 'physics', 
                year: 2009, 
                type: 1, 
                title: 'A/L Physics 2009 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2009_P1, 
                paper2id: 123, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2009_MS
            },
                        { 
                id: 123, 
                level: 'al', 
                subject: 'physics', 
                year: 2009, 
                type: 2, 
                title: 'A/L Physics 2009 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2009_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2009_MS
            },
                        { 
                id: 124, 
                level: 'al', 
                subject: 'physics', 
                year: 2010, 
                type: 1, 
                title: 'A/L Physics 2010 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2010_P1, 
                paper2id: 125, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2010_MS
            },
            { 
                id: 125, 
                level: 'al', 
                subject: 'physics', 
                year: 2010, 
                type: 2, 
                title: 'A/L Physics 2010 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2010_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2010_MS
            },
            { 
                id: 126, 
                level: 'al', 
                subject: 'physics', 
                year: 2011, 
                type: 1, 
                title: 'A/L Physics 2011 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2011_P1, 
                paper2id: 127, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2011_MS
            },
            { 
                id: 127, 
                level: 'al', 
                subject: 'physics', 
                year: 2011, 
                type: 2, 
                title: 'A/L Physics 2011 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2011_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2011_MS
            },
            { 
                id: 128, 
                level: 'al', 
                subject: 'physics', 
                year: 2012, 
                type: 1, 
                title: 'A/L Physics 2012 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2012_P1, 
                paper2id: 129, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2012_MS
            },
            { 
                id: 129, 
                level: 'al', 
                subject: 'physics', 
                year: 2012, 
                type: 2, 
                title: 'A/L Physics 2012 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2012_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2012_MS
            },
            { 
                id: 130, 
                level: 'al', 
                subject: 'physics', 
                year: 2013, 
                type: 1, 
                title: 'A/L Physics 2013 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2013_P1, 
                paper2id: 131, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2013_MS
            },
            { 
                id: 131, 
                level: 'al', 
                subject: 'physics', 
                year: 2013, 
                type: 2, 
                title: 'A/L Physics 2013 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2013_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2013_MS
            },
            { 
                id: 132, 
                level: 'al', 
                subject: 'physics', 
                year: 2014, 
                type: 1, 
                title: 'A/L Physics 2014 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2014_P1, 
                paper2id: 133, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2014_MS
            },
            { 
                id: 133, 
                level: 'al', 
                subject: 'physics', 
                year: 2014, 
                type: 2, 
                title: 'A/L Physics 2014 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2014_P2, 
                paper2id: null, 
                solved: false, 
                popular: 25 ,
                markingSchemeId: FILE_ID_PHYSICS_2014_MS
            },
            { 
                id: 134, level: 'al', subject: 'physics', year: 2015, type: 1, 
                title: 'A/L Physics 2015 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2015_P1, paper2id: 135, solved: false, popular: 30,
                markingSchemeId: FILE_ID_PHYSICS_2015_MS
            },
            { 
                id: 135, level: 'al', subject: 'physics', year: 2015, type: 2, 
                title: 'A/L Physics 2015 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2015_P2, paper2id: null, solved: false, popular: 30,
                markingSchemeId: FILE_ID_PHYSICS_2015_MS
            },

            // A/L Physics 2016
            { 
                id: 136, level: 'al', subject: 'physics', year: 2016, type: 1, 
                title: 'A/L Physics 2016 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2016_P1, paper2id: 137, solved: false, popular: 45,
                markingSchemeId: FILE_ID_PHYSICS_2016_MS
            },
            { 
                id: 137, level: 'al', subject: 'physics', year: 2016, type: 2, 
                title: 'A/L Physics 2016 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2016_P2, paper2id: null, solved: false, popular: 45,
                markingSchemeId: FILE_ID_PHYSICS_2016_MS
            },

            // A/L Physics 2017
            { 
                id: 138, level: 'al', subject: 'physics', year: 2017, type: 1, 
                title: 'A/L Physics 2017 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2017_P1, paper2id: 139, solved: false, popular: 55,
                markingSchemeId: FILE_ID_PHYSICS_2017_MS
            },
            { 
                id: 139, level: 'al', subject: 'physics', year: 2017, type: 2, 
                title: 'A/L Physics 2017 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2017_P2, paper2id: null, solved: false, popular: 55,
                markingSchemeId: FILE_ID_PHYSICS_2017_MS
            },

            // A/L Physics 2018
            { 
                id: 140, level: 'al', subject: 'physics', year: 2018, type: 1, 
                title: 'A/L Physics 2018 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2018_P1, paper2id: 141, solved: false, popular: 60,
                markingSchemeId: FILE_ID_PHYSICS_2018_MS
            },
            { 
                id: 141, level: 'al', subject: 'physics', year: 2018, type: 2, 
                title: 'A/L Physics 2018 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2018_P2, paper2id: null, solved: false, popular: 60,
                markingSchemeId: FILE_ID_PHYSICS_2018_MS
            },

            // A/L Physics 2019
            { 
                id: 142, level: 'al', subject: 'physics', year: 2019, type: 1, 
                title: 'A/L Physics 2019 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2019_P1, paper2id: 143, solved: false, popular: 75,
                markingSchemeId: FILE_ID_PHYSICS_2019_MS
            },
            { 
                id: 143, level: 'al', subject: 'physics', year: 2019, type: 2, 
                title: 'A/L Physics 2019 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2019_P2, paper2id: null, solved: false, popular: 75,
                markingSchemeId: FILE_ID_PHYSICS_2019_MS
            },

            // A/L Physics 2020
            { 
                id: 144, level: 'al', subject: 'physics', year: 2020, type: 1, 
                title: 'A/L Physics 2020 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2020_P1, paper2id: 145, solved: false, popular: 80,
                markingSchemeId: FILE_ID_PHYSICS_2020_MS
            },
            { 
                id: 145, level: 'al', subject: 'physics', year: 2020, type: 2, 
                title: 'A/L Physics 2020 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2020_P2, paper2id: null, solved: false, popular: 80,
                markingSchemeId: FILE_ID_PHYSICS_2020_MS
            },

            // A/L Physics 2021
            { 
                id: 146, level: 'al', subject: 'physics', year: 2021, type: 1, 
                title: 'A/L Physics 2021 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2021_P1, paper2id: 147, solved: false, popular: 90,
                markingSchemeId: FILE_ID_PHYSICS_2021_MS
            },
            { 
                id: 147, level: 'al', subject: 'physics', year: 2021, type: 2, 
                title: 'A/L Physics 2021 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2021_P2, paper2id: null, solved: false, popular: 90,
                markingSchemeId: FILE_ID_PHYSICS_2021_MS
            },
            
            // A/L Physics 2022
            { 
                id: 148, level: 'al', subject: 'physics', year: 2022, type: 1, 
                title: 'A/L Physics 2022 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2022_P1, paper2id: 149, solved: false, popular: 105,
                markingSchemeId: FILE_ID_PHYSICS_2022_MS
            },
            { 
                id: 149, level: 'al', subject: 'physics', year: 2022, type: 2, 
                title: 'A/L Physics 2022 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2022_P2, paper2id: null, solved: false, popular: 105,
                markingSchemeId: FILE_ID_PHYSICS_2022_MS
            },

            // A/L Physics 2023
            { 
                id: 150, level: 'al', subject: 'physics', year: 2023, type: 1, 
                title: 'A/L Physics 2023 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2023_P1, paper2id: 151, solved: false, popular: 120,
                markingSchemeId: FILE_ID_PHYSICS_2023_MS
            },
            { 
                id: 151, level: 'al', subject: 'physics', year: 2023, type: 2, 
                title: 'A/L Physics 2023 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2023_P2, paper2id: null, solved: false, popular: 120,
                markingSchemeId: FILE_ID_PHYSICS_2023_MS
            },

            // A/L Physics 2024 (Latest)
            { 
                id: 152, level: 'al', subject: 'physics', year: 2024, type: 1, 
                title: 'A/L Physics 2024 - Paper I (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2024_P1, paper2id: 153, solved: false, popular: 150,
                markingSchemeId: FILE_ID_PHYSICS_2024_MS
            },
            { 
                id: 153, level: 'al', subject: 'physics', year: 2024, type: 2, 
                title: 'A/L Physics 2024 - Paper II (Past Exam)', 
                fileId: FILE_ID_PHYSICS_2024_P2, paper2id: null, solved: false, popular: 150,
                markingSchemeId: FILE_ID_PHYSICS_2024_MS
            },
            // --- PURE MATHS 2000-2010 ---
            { id: 3010, level: 'al', subject: 'pure_maths', year: 2010, type: 2, title: 'A/L Pure Maths 2010 - Paper II', fileId: PURE_2010_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2010_MS },
            { id: 3009, level: 'al', subject: 'pure_maths', year: 2009, type: 2, title: 'A/L Pure Maths 2009 - Paper II', fileId: PURE_2009_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2009_MS },
            { id: 3008, level: 'al', subject: 'pure_maths', year: 2008, type: 2, title: 'A/L Pure Maths 2008 - Paper II', fileId: PURE_2008_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2008_MS },
            { id: 3007, level: 'al', subject: 'pure_maths', year: 2007, type: 2, title: 'A/L Pure Maths 2007 - Paper II', fileId: PURE_2007_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2007_MS },
            { id: 3006, level: 'al', subject: 'pure_maths', year: 2006, type: 2, title: 'A/L Pure Maths 2006 - Paper II', fileId: PURE_2006_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2006_MS },
            { id: 3005, level: 'al', subject: 'pure_maths', year: 2005, type: 2, title: 'A/L Pure Maths 2005 - Paper II', fileId: PURE_2005_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2005_MS },
            { id: 3004, level: 'al', subject: 'pure_maths', year: 2004, type: 2, title: 'A/L Pure Maths 2004 - Paper II', fileId: PURE_2004_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2004_MS },
            { id: 3003, level: 'al', subject: 'pure_maths', year: 2003, type: 2, title: 'A/L Pure Maths 2003 - Paper II', fileId: PURE_2003_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2003_MS },
            { id: 3002, level: 'al', subject: 'pure_maths', year: 2002, type: 2, title: 'A/L Pure Maths 2002 - Paper II', fileId: PURE_2002_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2002_MS },
            { id: 3001, level: 'al', subject: 'pure_maths', year: 2001, type: 2, title: 'A/L Pure Maths 2001 - Paper II', fileId: PURE_2001_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2001_MS },
            { id: 3000, level: 'al', subject: 'pure_maths', year: 2000, type: 2, title: 'A/L Pure Maths 2000 - Paper II', fileId: PURE_2000_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: PURE_2000_MS },

            // --- APPLIED MATHS 2000-2010 ---
            { id: 4010, level: 'al', subject: 'applied_maths', year: 2010, type: 2, title: 'A/L Applied Maths 2010 - Paper II', fileId: APPLIED_2010_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2010_MS },
            { id: 4009, level: 'al', subject: 'applied_maths', year: 2009, type: 2, title: 'A/L Applied Maths 2009 - Paper II', fileId: APPLIED_2009_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2009_MS },
            { id: 4008, level: 'al', subject: 'applied_maths', year: 2008, type: 2, title: 'A/L Applied Maths 2008 - Paper II', fileId: APPLIED_2008_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2008_MS },
            { id: 4007, level: 'al', subject: 'applied_maths', year: 2007, type: 2, title: 'A/L Applied Maths 2007 - Paper II', fileId: APPLIED_2007_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2007_MS },
            { id: 4006, level: 'al', subject: 'applied_maths', year: 2006, type: 2, title: 'A/L Applied Maths 2006 - Paper II', fileId: APPLIED_2006_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2006_MS },
            { id: 4005, level: 'al', subject: 'applied_maths', year: 2005, type: 2, title: 'A/L Applied Maths 2005 - Paper II', fileId: APPLIED_2005_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2005_MS },
            { id: 4004, level: 'al', subject: 'applied_maths', year: 2004, type: 2, title: 'A/L Applied Maths 2004 - Paper II', fileId: APPLIED_2004_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2004_MS },
            { id: 4003, level: 'al', subject: 'applied_maths', year: 2003, type: 2, title: 'A/L Applied Maths 2003 - Paper II', fileId: APPLIED_2003_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2003_MS },
            { id: 4002, level: 'al', subject: 'applied_maths', year: 2002, type: 2, title: 'A/L Applied Maths 2002 - Paper II', fileId: APPLIED_2002_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2002_MS },
            { id: 4001, level: 'al', subject: 'applied_maths', year: 2001, type: 2, title: 'A/L Applied Maths 2001 - Paper II', fileId: APPLIED_2001_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2001_MS },
            { id: 4000, level: 'al', subject: 'applied_maths', year: 2000, type: 2, title: 'A/L Applied Maths 2000 - Paper II', fileId: APPLIED_2000_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: APPLIED_2000_MS },
            
            // --- A/L ICT DATA (2011 - 2024) ---
            { id: 200, level: 'al', subject: 'ict', year: 2011, type: 1, title: 'A/L ICT 2011 - Paper I', fileId: ICT_2011_P1, paper2id: 201, solved: false, popular: 40, markingSchemeId: ICT_2011_MS },
            { id: 201, level: 'al', subject: 'ict', year: 2011, type: 2, title: 'A/L ICT 2011 - Paper II', fileId: ICT_2011_P2, paper2id: null, solved: false, popular: 40, markingSchemeId: ICT_2011_MS },

            { id: 202, level: 'al', subject: 'ict', year: 2012, type: 1, title: 'A/L ICT 2012 - Paper I', fileId: ICT_2012_P1, paper2id: 203, solved: false, popular: 45, markingSchemeId: ICT_2012_MS },
            { id: 203, level: 'al', subject: 'ict', year: 2012, type: 2, title: 'A/L ICT 2012 - Paper II', fileId: ICT_2012_P2, paper2id: null, solved: false, popular: 45, markingSchemeId: ICT_2012_MS },

            { id: 204, level: 'al', subject: 'ict', year: 2013, type: 1, title: 'A/L ICT 2013 - Paper I', fileId: ICT_2013_P1, paper2id: 205, solved: false, popular: 50, markingSchemeId: ICT_2013_MS },
            { id: 205, level: 'al', subject: 'ict', year: 2013, type: 2, title: 'A/L ICT 2013 - Paper II', fileId: ICT_2013_P2, paper2id: null, solved: false, popular: 50, markingSchemeId: ICT_2013_MS },

            { id: 206, level: 'al', subject: 'ict', year: 2014, type: 1, title: 'A/L ICT 2014 - Paper I', fileId: ICT_2014_P1, paper2id: 207, solved: false, popular: 55, markingSchemeId: ICT_2014_MS },
            { id: 207, level: 'al', subject: 'ict', year: 2014, type: 2, title: 'A/L ICT 2014 - Paper II', fileId: ICT_2014_P2, paper2id: null, solved: false, popular: 55, markingSchemeId: ICT_2014_MS },

            { id: 208, level: 'al', subject: 'ict', year: 2015, type: 1, title: 'A/L ICT 2015 - Paper I', fileId: ICT_2015_P1, paper2id: 209, solved: false, popular: 60, markingSchemeId: ICT_2015_MS },
            { id: 209, level: 'al', subject: 'ict', year: 2015, type: 2, title: 'A/L ICT 2015 - Paper II', fileId: ICT_2015_P2, paper2id: null, solved: false, popular: 60, markingSchemeId: ICT_2015_MS },

            { id: 210, level: 'al', subject: 'ict', year: 2016, type: 1, title: 'A/L ICT 2016 - Paper I', fileId: ICT_2016_P1, paper2id: 211, solved: false, popular: 70, markingSchemeId: ICT_2016_MS },
            { id: 211, level: 'al', subject: 'ict', year: 2016, type: 2, title: 'A/L ICT 2016 - Paper II', fileId: ICT_2016_P2, paper2id: null, solved: false, popular: 70, markingSchemeId: ICT_2016_MS },

            { id: 212, level: 'al', subject: 'ict', year: 2017, type: 1, title: 'A/L ICT 2017 - Paper I', fileId: ICT_2017_P1, paper2id: 213, solved: false, popular: 80, markingSchemeId: ICT_2017_MS },
            { id: 213, level: 'al', subject: 'ict', year: 2017, type: 2, title: 'A/L ICT 2017 - Paper II', fileId: ICT_2017_P2, paper2id: null, solved: false, popular: 80, markingSchemeId: ICT_2017_MS },

            { id: 214, level: 'al', subject: 'ict', year: 2018, type: 1, title: 'A/L ICT 2018 - Paper I', fileId: ICT_2018_P1, paper2id: 215, solved: false, popular: 85, markingSchemeId: ICT_2018_MS },
            { id: 215, level: 'al', subject: 'ict', year: 2018, type: 2, title: 'A/L ICT 2018 - Paper II', fileId: ICT_2018_P2, paper2id: null, solved: false, popular: 85, markingSchemeId: ICT_2018_MS },

            { id: 216, level: 'al', subject: 'ict', year: 2019, type: 1, title: 'A/L ICT 2019 - Paper I', fileId: ICT_2019_P1, paper2id: 217, solved: false, popular: 95, markingSchemeId: ICT_2019_MS },
            { id: 217, level: 'al', subject: 'ict', year: 2019, type: 2, title: 'A/L ICT 2019 - Paper II', fileId: ICT_2019_P2, paper2id: null, solved: false, popular: 95, markingSchemeId: ICT_2019_MS },

            { id: 218, level: 'al', subject: 'ict', year: 2020, type: 1, title: 'A/L ICT 2020 - Paper I', fileId: ICT_2020_P1, paper2id: 219, solved: false, popular: 110, markingSchemeId: ICT_2020_MS },
            { id: 219, level: 'al', subject: 'ict', year: 2020, type: 2, title: 'A/L ICT 2020 - Paper II', fileId: ICT_2020_P2, paper2id: null, solved: false, popular: 110, markingSchemeId: ICT_2020_MS },

            { id: 220, level: 'al', subject: 'ict', year: 2021, type: 1, title: 'A/L ICT 2021 - Paper I', fileId: ICT_2021_P1, paper2id: 221, solved: false, popular: 120, markingSchemeId: ICT_2021_MS },
            { id: 221, level: 'al', subject: 'ict', year: 2021, type: 2, title: 'A/L ICT 2021 - Paper II', fileId: ICT_2021_P2, paper2id: null, solved: false, popular: 120, markingSchemeId: ICT_2021_MS },

            { id: 222, level: 'al', subject: 'ict', year: 2022, type: 1, title: 'A/L ICT 2022 - Paper I', fileId: ICT_2022_P1, paper2id: 223, solved: false, popular: 130, markingSchemeId: ICT_2022_MS },
            { id: 223, level: 'al', subject: 'ict', year: 2022, type: 2, title: 'A/L ICT 2022 - Paper II', fileId: ICT_2022_P2, paper2id: null, solved: false, popular: 130, markingSchemeId: ICT_2022_MS },

            { id: 224, level: 'al', subject: 'ict', year: 2023, type: 1, title: 'A/L ICT 2023 - Paper I', fileId: ICT_2023_P1, paper2id: 225, solved: false, popular: 150, markingSchemeId: ICT_2023_MS },
            { id: 225, level: 'al', subject: 'ict', year: 2023, type: 2, title: 'A/L ICT 2023 - Paper II', fileId: ICT_2023_P2, paper2id: null, solved: false, popular: 150, markingSchemeId: ICT_2023_MS },

            { id: 226, level: 'al', subject: 'ict', year: 2024, type: 1, title: 'A/L ICT 2024 - Paper I', fileId: ICT_2024_P1, paper2id: 227, solved: false, popular: 200, markingSchemeId: ICT_2024_MS },
            { id: 227, level: 'al', subject: 'ict', year: 2024, type: 2, title: 'A/L ICT 2024 - Paper II', fileId: ICT_2024_P2, paper2id: null, solved: false, popular: 200, markingSchemeId: ICT_2024_MS },
            // --- PURE MATHS (2011-2024) ---
            { id: 3124, level: 'al', subject: 'pure_maths', year: 2024, type: 1, title: 'A/L Pure Maths 2024 - Paper I', fileId: PM_24_1, paper2id: 3224, solved: false, popular: 100, markingSchemeId: MS_24 },
            { id: 3224, level: 'al', subject: 'pure_maths', year: 2024, type: 2, title: 'A/L Pure Maths 2024 - Paper II', fileId: PM_24_2, paper2id: null, solved: false, popular: 100, markingSchemeId: MS_24 },
            { id: 3123, level: 'al', subject: 'pure_maths', year: 2023, type: 1, title: 'A/L Pure Maths 2023 - Paper I', fileId: PM_23_1, paper2id: 3223, solved: false, popular: 90, markingSchemeId: MS_23 },
            { id: 3223, level: 'al', subject: 'pure_maths', year: 2023, type: 2, title: 'A/L Pure Maths 2023 - Paper II', fileId: PM_23_2, paper2id: null, solved: false, popular: 90, markingSchemeId: MS_23 },
            { id: 3122, level: 'al', subject: 'pure_maths', year: 2022, type: 1, title: 'A/L Pure Maths 2022 - Paper I', fileId: PM_22_1, paper2id: 3222, solved: false, popular: 85, markingSchemeId: MS_22 },
            { id: 3222, level: 'al', subject: 'pure_maths', year: 2022, type: 2, title: 'A/L Pure Maths 2022 - Paper II', fileId: PM_22_2, paper2id: null, solved: false, popular: 85, markingSchemeId: MS_22 },
            { id: 3121, level: 'al', subject: 'pure_maths', year: 2021, type: 1, title: 'A/L Pure Maths 2021 - Paper I', fileId: PM_21_1, paper2id: 3221, solved: false, popular: 80, markingSchemeId: MS_21 },
            { id: 3221, level: 'al', subject: 'pure_maths', year: 2021, type: 2, title: 'A/L Pure Maths 2021 - Paper II', fileId: PM_21_2, paper2id: null, solved: false, popular: 80, markingSchemeId: MS_21 },
            { id: 3120, level: 'al', subject: 'pure_maths', year: 2020, type: 1, title: 'A/L Pure Maths 2020 - Paper I', fileId: PM_20_1, paper2id: 3220, solved: false, popular: 75, markingSchemeId: MS_20 },
            { id: 3220, level: 'al', subject: 'pure_maths', year: 2020, type: 2, title: 'A/L Pure Maths 2020 - Paper II', fileId: PM_20_2, paper2id: null, solved: false, popular: 75, markingSchemeId: MS_20 },
            { id: 3119, level: 'al', subject: 'pure_maths', year: 2019, type: 1, title: 'A/L Pure Maths 2019 - Paper I', fileId: PM_19_1, paper2id: 3219, solved: false, popular: 70, markingSchemeId: MS_19 },
            { id: 3219, level: 'al', subject: 'pure_maths', year: 2019, type: 2, title: 'A/L Pure Maths 2019 - Paper II', fileId: PM_19_2, paper2id: null, solved: false, popular: 70, markingSchemeId: MS_19 },
            { id: 3118, level: 'al', subject: 'pure_maths', year: 2018, type: 1, title: 'A/L Pure Maths 2018 - Paper I', fileId: PM_18_1, paper2id: 3218, solved: false, popular: 65, markingSchemeId: MS_18 },
            { id: 3218, level: 'al', subject: 'pure_maths', year: 2018, type: 2, title: 'A/L Pure Maths 2018 - Paper II', fileId: PM_18_2, paper2id: null, solved: false, popular: 65, markingSchemeId: MS_18 },
            { id: 3117, level: 'al', subject: 'pure_maths', year: 2017, type: 1, title: 'A/L Pure Maths 2017 - Paper I', fileId: PM_17_1, paper2id: 3217, solved: false, popular: 60, markingSchemeId: MS_17 },
            { id: 3217, level: 'al', subject: 'pure_maths', year: 2017, type: 2, title: 'A/L Pure Maths 2017 - Paper II', fileId: PM_17_2, paper2id: null, solved: false, popular: 60, markingSchemeId: MS_17 },
            { id: 3116, level: 'al', subject: 'pure_maths', year: 2016, type: 1, title: 'A/L Pure Maths 2016 - Paper I', fileId: PM_16_1, paper2id: 3216, solved: false, popular: 55, markingSchemeId: MS_16 },
            { id: 3216, level: 'al', subject: 'pure_maths', year: 2016, type: 2, title: 'A/L Pure Maths 2016 - Paper II', fileId: PM_16_2, paper2id: null, solved: false, popular: 55, markingSchemeId: MS_16 },
            { id: 3115, level: 'al', subject: 'pure_maths', year: 2015, type: 1, title: 'A/L Pure Maths 2015 - Paper I', fileId: PM_15_1, paper2id: 3215, solved: false, popular: 50, markingSchemeId: MS_15 },
            { id: 3215, level: 'al', subject: 'pure_maths', year: 2015, type: 2, title: 'A/L Pure Maths 2015 - Paper II', fileId: PM_15_2, paper2id: null, solved: false, popular: 50, markingSchemeId: MS_15 },
            { id: 3114, level: 'al', subject: 'pure_maths', year: 2014, type: 1, title: 'A/L Pure Maths 2014 - Paper I', fileId: PM_14_1, paper2id: 3214, solved: false, popular: 45, markingSchemeId: MS_14 },
            { id: 3214, level: 'al', subject: 'pure_maths', year: 2014, type: 2, title: 'A/L Pure Maths 2014 - Paper II', fileId: PM_14_2, paper2id: null, solved: false, popular: 45, markingSchemeId: MS_14 },
            { id: 3113, level: 'al', subject: 'pure_maths', year: 2013, type: 1, title: 'A/L Pure Maths 2013 - Paper I', fileId: PM_13_1, paper2id: 3213, solved: false, popular: 40, markingSchemeId: MS_13 },
            { id: 3213, level: 'al', subject: 'pure_maths', year: 2013, type: 2, title: 'A/L Pure Maths 2013 - Paper II', fileId: PM_13_2, paper2id: null, solved: false, popular: 40, markingSchemeId: MS_13 },
            { id: 3112, level: 'al', subject: 'pure_maths', year: 2012, type: 1, title: 'A/L Pure Maths 2012 - Paper I', fileId: PM_12_1, paper2id: 3212, solved: false, popular: 35, markingSchemeId: MS_12 },
            { id: 3212, level: 'al', subject: 'pure_maths', year: 2012, type: 2, title: 'A/L Pure Maths 2012 - Paper II', fileId: PM_12_2, paper2id: null, solved: false, popular: 35, markingSchemeId: MS_12 },
            { id: 3111, level: 'al', subject: 'pure_maths', year: 2011, type: 1, title: 'A/L Pure Maths 2011 - Paper I', fileId: PM_11_1, paper2id: 3211, solved: false, popular: 30, markingSchemeId: MS_11 },
            { id: 3211, level: 'al', subject: 'pure_maths', year: 2011, type: 2, title: 'A/L Pure Maths 2011 - Paper II', fileId: PM_11_2, paper2id: null, solved: false, popular: 30, markingSchemeId: MS_11 },

            // --- APPLIED MATHS (2011-2024) ---
            { id: 4124, level: 'al', subject: 'applied_maths', year: 2024, type: 1, title: 'A/L Applied Maths 2024 - Paper I', fileId: AM_24_1, paper2id: 4224, solved: false, popular: 100, markingSchemeId: MS_24 },
            { id: 4224, level: 'al', subject: 'applied_maths', year: 2024, type: 2, title: 'A/L Applied Maths 2024 - Paper II', fileId: AM_24_2, paper2id: null, solved: false, popular: 100, markingSchemeId: MS_24 },
            { id: 4123, level: 'al', subject: 'applied_maths', year: 2023, type: 1, title: 'A/L Applied Maths 2023 - Paper I', fileId: AM_23_1, paper2id: 4223, solved: false, popular: 90, markingSchemeId: MS_23 },
            { id: 4223, level: 'al', subject: 'applied_maths', year: 2023, type: 2, title: 'A/L Applied Maths 2023 - Paper II', fileId: AM_23_2, paper2id: null, solved: false, popular: 90, markingSchemeId: MS_23 },
            { id: 4122, level: 'al', subject: 'applied_maths', year: 2022, type: 1, title: 'A/L Applied Maths 2022 - Paper I', fileId: AM_22_1, paper2id: 4222, solved: false, popular: 85, markingSchemeId: MS_22 },
            { id: 4222, level: 'al', subject: 'applied_maths', year: 2022, type: 2, title: 'A/L Applied Maths 2022 - Paper II', fileId: AM_22_2, paper2id: null, solved: false, popular: 85, markingSchemeId: MS_22 },
            { id: 4121, level: 'al', subject: 'applied_maths', year: 2021, type: 1, title: 'A/L Applied Maths 2021 - Paper I', fileId: AM_21_1, paper2id: 4221, solved: false, popular: 80, markingSchemeId: MS_21 },
            { id: 4221, level: 'al', subject: 'applied_maths', year: 2021, type: 2, title: 'A/L Applied Maths 2021 - Paper II', fileId: AM_21_2, paper2id: null, solved: false, popular: 80, markingSchemeId: MS_21 },
            { id: 4120, level: 'al', subject: 'applied_maths', year: 2020, type: 1, title: 'A/L Applied Maths 2020 - Paper I', fileId: AM_20_1, paper2id: 4220, solved: false, popular: 75, markingSchemeId: MS_20 },
            { id: 4220, level: 'al', subject: 'applied_maths', year: 2020, type: 2, title: 'A/L Applied Maths 2020 - Paper II', fileId: AM_20_2, paper2id: null, solved: false, popular: 75, markingSchemeId: MS_20 },
            { id: 4119, level: 'al', subject: 'applied_maths', year: 2019, type: 1, title: 'A/L Applied Maths 2019 - Paper I', fileId: AM_19_1, paper2id: 4219, solved: false, popular: 70, markingSchemeId: MS_19 },
            { id: 4219, level: 'al', subject: 'applied_maths', year: 2019, type: 2, title: 'A/L Applied Maths 2019 - Paper II', fileId: AM_19_2, paper2id: null, solved: false, popular: 70, markingSchemeId: MS_19 },
            { id: 4118, level: 'al', subject: 'applied_maths', year: 2018, type: 1, title: 'A/L Applied Maths 2018 - Paper I', fileId: AM_18_1, paper2id: 4218, solved: false, popular: 65, markingSchemeId: MS_18 },
            { id: 4218, level: 'al', subject: 'applied_maths', year: 2018, type: 2, title: 'A/L Applied Maths 2018 - Paper II', fileId: AM_18_2, paper2id: null, solved: false, popular: 65, markingSchemeId: MS_18 },
            { id: 4117, level: 'al', subject: 'applied_maths', year: 2017, type: 1, title: 'A/L Applied Maths 2017 - Paper I', fileId: AM_17_1, paper2id: 4217, solved: false, popular: 60, markingSchemeId: MS_17 },
            { id: 4217, level: 'al', subject: 'applied_maths', year: 2017, type: 2, title: 'A/L Applied Maths 2017 - Paper II', fileId: AM_17_2, paper2id: null, solved: false, popular: 60, markingSchemeId: MS_17 },
            { id: 4116, level: 'al', subject: 'applied_maths', year: 2016, type: 1, title: 'A/L Applied Maths 2016 - Paper I', fileId: AM_16_1, paper2id: 4216, solved: false, popular: 55, markingSchemeId: MS_16 },
            { id: 4216, level: 'al', subject: 'applied_maths', year: 2016, type: 2, title: 'A/L Applied Maths 2016 - Paper II', fileId: AM_16_2, paper2id: null, solved: false, popular: 55, markingSchemeId: MS_16 },
            { id: 4115, level: 'al', subject: 'applied_maths', year: 2015, type: 1, title: 'A/L Applied Maths 2015 - Paper I', fileId: AM_15_1, paper2id: 4215, solved: false, popular: 50, markingSchemeId: MS_15 },
            { id: 4215, level: 'al', subject: 'applied_maths', year: 2015, type: 2, title: 'A/L Applied Maths 2015 - Paper II', fileId: AM_15_2, paper2id: null, solved: false, popular: 50, markingSchemeId: MS_15 },
            { id: 4114, level: 'al', subject: 'applied_maths', year: 2014, type: 1, title: 'A/L Applied Maths 2014 - Paper I', fileId: AM_14_1, paper2id: 4214, solved: false, popular: 45, markingSchemeId: MS_14 },
            { id: 4214, level: 'al', subject: 'applied_maths', year: 2014, type: 2, title: 'A/L Applied Maths 2014 - Paper II', fileId: AM_14_2, paper2id: null, solved: false, popular: 45, markingSchemeId: MS_14 },
            { id: 4113, level: 'al', subject: 'applied_maths', year: 2013, type: 1, title: 'A/L Applied Maths 2013 - Paper I', fileId: AM_13_1, paper2id: 4213, solved: false, popular: 40, markingSchemeId: MS_13 },
            { id: 4213, level: 'al', subject: 'applied_maths', year: 2013, type: 2, title: 'A/L Applied Maths 2013 - Paper II', fileId: AM_13_2, paper2id: null, solved: false, popular: 40, markingSchemeId: MS_13 },
            { id: 4112, level: 'al', subject: 'applied_maths', year: 2012, type: 1, title: 'A/L Applied Maths 2012 - Paper I', fileId: AM_12_1, paper2id: 4212, solved: false, popular: 35, markingSchemeId: MS_12 },
            { id: 4212, level: 'al', subject: 'applied_maths', year: 2012, type: 2, title: 'A/L Applied Maths 2012 - Paper II', fileId: AM_12_2, paper2id: null, solved: false, popular: 35, markingSchemeId: MS_12 },
            { id: 4111, level: 'al', subject: 'applied_maths', year: 2011, type: 1, title: 'A/L Applied Maths 2011 - Paper I', fileId: AM_11_1, paper2id: 4211, solved: false, popular: 30, markingSchemeId: MS_11 },
            { id: 4211, level: 'al', subject: 'applied_maths', year: 2011, type: 2, title: 'A/L Applied Maths 2011 - Paper II', fileId: AM_11_2, paper2id: null, solved: false, popular: 30, markingSchemeId: MS_11 },



            { id: 901, level: 'sch', subject: 'general', year: 2024, type: 'model', title: 'Grade 5 Scholarship Model Test (2024)', fileId: 'DRIVE_ID_SCH_2024_MODEL', solved: false, popular: 50, markingSchemeId: 'DRIVE_ID_SCH_2024_MODEL_MS' },
            { id: 401, level: 'al', subject: 'chemistry', year: 2023, type: 1, title: 'A/L Chemistry 2023 - P I (Organic)', fileId: 'CHEM_2023_P1', solved: true, popular: 180, markingSchemeId: 'DRIVE_ID_CHEM_2023_MS' },
            { id: 402, level: 'al', subject: 'chemistry', year: 2023, type: 2, title: 'A/L Chemistry 2023 - P II (Inorganic)', fileId: 'CHEM_2023_P2', paper2id: null, solved: true, popular: 170, markingSchemeId: 'DRIVE_ID_CHEM_2023_MS' },
            { id: 501, level: 'ol', subject: 'science', year: 2022, type: 'model', title: 'O/L Science Model Paper - Heat & Light', fileId: 'SCIENCE_MODEL_2022', solved: false, popular: 80, markingSchemeId: '' },
        ];
        
        const subjectMap = {
            'al': {
                'maths': ['Pure Maths', 'Applied Maths', 'Physics', 'Chemistry'],
                'bio': ['Biology', 'Physics', 'Chemistry'],
                'commerce': ['Economics', 'Business Studies', 'Accounting'],
                'arts': ['History', 'Geography', 'Logic'],
                'all': ['Pure Maths', 'Applied Maths', 'Physics', 'Chemistry', 'Biology', 'Economics', 'Business Studies', 'Accounting']
            },
            'ol': ['Maths', 'Science', 'History', 'English'],
            'sch': ['General'],
            '': []
        };
        
        let selectedPaper2FileId = ''; 
        let currentQuickFilter = 'all'; 

        // --- 1. NAVIGATION JAVASCRIPT ---

        function toggleMenu() {
            document.getElementById("sideMenu").style.width = document.getElementById("sideMenu").style.width === '250px' ? '0' : '250px';
        }
        
        function toggleDropdown(event) {
            event.stopPropagation();
            const content = event.currentTarget.parentNode.querySelector('.dropdown-content');

            document.querySelectorAll('.dropdown-content.show').forEach(openContent => {
                if (openContent !== content) openContent.classList.remove('show');
            });
            
            content.classList.toggle('show');
        }

        window.onclick = function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-content.show').forEach(content => content.classList.remove('show'));
            }
            const sideMenu = document.getElementById("sideMenu");
            if (sideMenu && sideMenu.style.width === '250px' && !e.target.closest('.side-menu') && !e.target.closest('.menu-icon') && !e.target.closest('.title')) {
                sideMenu.style.width = '0';
            }
        }

        // --- 2. FILTER INITIALIZATION & DYNAMIC UPDATES ---

        function initializeYears() {
            const yearSelect = document.getElementById('year');
            const currentYear = new Date().getFullYear() + 1; 
            for (let i = currentYear; i >= 2000; i--) { 
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                yearSelect.appendChild(option);
            }
        }

        function generateSuggestions() {
            const suggestionsList = document.getElementById('keyword-suggestions');
            suggestionsList.innerHTML = '';
            
            const keywords = new Set();
            paperData.forEach(paper => {
                keywords.add(paper.title);
                keywords.add(paper.subject.charAt(0).toUpperCase() + paper.subject.slice(1));
                keywords.add(paper.year.toString());
            });

            Array.from(keywords).sort().forEach(keyword => {
                const option = document.createElement('option');
                option.value = keyword;
                suggestionsList.appendChild(option);
            });
        }
        
        function updateFilters() {
            const levelSelect = document.getElementById('level');
            const streamGroup = document.getElementById('streamGroup');
            const streamSelect = document.getElementById('stream');
            const subjectSelect = document.getElementById('subject');
            
            const level = levelSelect.value;
            const stream = streamSelect.value;
            const lastSelectedSubject = subjectSelect.value;

            // 1. Manage Stream Visibility
            if (level === 'al') {
                streamGroup.style.display = 'block';
            } else {
                streamGroup.style.display = 'none';
                streamSelect.value = ''; 
            }

            // 2. Clear Subject Dropdown
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';
            
            let subjects = [];

            // 3. Populate logic
            if (level === 'al') {
                const selectedStream = stream || 'all'; 
                subjects = [...(subjectMap['al'][selectedStream] || [])]; 
                subjects.sort();

                // Add ICT as it's common for all streams
                if (!subjects.includes('ICT')) {
                    subjects.push('ICT');
                }
            } else if (level !== '') {
                subjects = [...(subjectMap[level] || [])];
                subjects.sort();
            }

            // 4. Create Option Elements
            subjects.forEach(sub => {
                const option = document.createElement('option');
                option.value = sub.toLowerCase().replace(/\s+/g, '_'); 
                option.textContent = sub;
                subjectSelect.appendChild(option);
            });

            // 5. Restore previous selection if still valid
            subjectSelect.value = lastSelectedSubject;

            // 6. Reset UI filter tabs
            currentQuickFilter = 'all'; 
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            const allTab = document.querySelector('.filter-tab[data-filter="all"]');
            if (allTab) allTab.classList.add('active');

            // Trigger the actual filtering logic
            applyFilters();
        }
        
        function quickFilter(type) {
            currentQuickFilter = type;
            
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector(`.filter-tab[data-filter="${type}"]`).classList.add('active');

            if (type !== 'all') {
                document.getElementById('level').value = '';
                document.getElementById('subject').value = '';
                document.getElementById('year').value = '';
                document.getElementById('paperType').value = '';
                updateFilters(); 
            }
            
            applyFilters();
        }
        
        function clearFilterInputs(runFilter = true) {
            document.getElementById('level').value = '';
            document.getElementById('subject').value = '';
            document.getElementById('year').value = '';
            document.getElementById('paperType').value = '';
            document.getElementById('keyword').value = '';
            
            updateFilters(); 
            
            if (runFilter) applyFilters();
        }
        
        function clearAllFilters() {
            clearFilterInputs(false); 
            currentQuickFilter = 'all';
            document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelector('.filter-tab[data-filter="all"]').classList.add('active');
            
            handlePaperSelection(); 
        }


        // --- 3. CORE LOGIC: FILTERING & LOADING ---

        function applyFilters() {
            const resultsList = document.getElementById('resultsList');
            resultsList.innerHTML = ''; 

            resultsList.innerHTML = '<div id="initialLoading" style="grid-column: 1 / -1; text-align:center;"><p class="loading-text" style="color: var(--color-subtext); padding: 50px;">Filtering data and retrieving links...</p></div>';

            setTimeout(() => {
                const loadingElement = document.getElementById('initialLoading');
                if(loadingElement) loadingElement.remove();

                const level = document.getElementById('level').value;
                const stream = document.getElementById('stream').value; // Get Stream value
                const subject = document.getElementById('subject').value;
                const year = document.getElementById('year').value;
                const paperType = document.getElementById('paperType').value;
                const keyword = document.getElementById('keyword').value.toLowerCase();
                
                let filteredPapers = paperData.filter(paper => {
                    
                    // 1. Strict Dropdown Filters (AND logic)
                    if (level && paper.level !== level) return false;
                    if (subject && paper.subject !== subject) return false; 
                    if (year && paper.year != year) return false;
                    if (paperType && paper.type != paperType) return false;

                    // --- 1.5 Stream Validation ---
                    // If Level is AL, a Stream is picked, but NO specific Subject is picked:
                    if (level === 'al' && stream && !subject) {
                        // Get the list of subjects belonging to this stream from our map
                        const allowedSubjects = (subjectMap['al'][stream] || []).map(s => 
                            s.toLowerCase().replace(/\s+/g, '_')
                        );
                        
                        // ICT is always allowed in any stream
                        allowedSubjects.push('ict');

                        // If the paper's subject isn't in this stream's list, hide it
                        if (!allowedSubjects.includes(paper.subject)) {
                            return false;
                        }
                    }
                    
                    // 2. Keyword Search
                    if (keyword) {
                        const paperText = (paper.title + ' ' + paper.subject + ' ' + paper.year).toLowerCase();
                        if (!paperText.includes(keyword)) {
                            return false;
                        }
                    }

                    // 3. Quick Filters
                    const currentYear = new Date().getFullYear();
                    if (currentQuickFilter === 'latest' && paper.year < currentYear - 1) return false;
                    if (currentQuickFilter === 'popular' && paper.popular < 100) return false;
                    if (currentQuickFilter === 'solved' && !paper.solved) return false;
                    
                    return true;
                });

                if (currentQuickFilter === 'popular') {
                    filteredPapers.sort((a, b) => b.popular - a.popular);
                }
                
                // --- Rendering Results ---
                if (filteredPapers.length > 0) {
                    filteredPapers.forEach(paper => {
                        resultsList.appendChild(createPaperCard(paper));
                    });
                } else {
                    resultsList.innerHTML = '<p class="no-results" style="grid-column: 1 / -1; text-align:center; padding: 50px; color: var(--color-subtext); border: 1px dashed #e2e8f0; border-radius: 8px;">😔 No papers found matching the current selection criteria. Please try different filters.</p>';
                }

            }, 300); 
        }

        // --- 4. SMART SUGGESTION FEATURE ---
        
        function handlePaperSelection() {
            const paperType = document.getElementById('paperType').value;
            const level = document.getElementById('level').value;
            const subject = document.getElementById('subject').value;
            const year = document.getElementById('year').value;
            
            const smartSuggestionBox = document.getElementById('smartSuggestion');
            
            selectedPaper2FileId = ''; 
            smartSuggestionBox.classList.add('hidden'); 

            if (paperType === '1' && level !== '' && subject !== '' && year !== '') {
                
                const currentPaper = paperData.find(p => 
                    p.level === level && 
                    p.subject === subject && 
                    p.year == year && 
                    p.type === 1
                );
                
                if (currentPaper && currentPaper.paper2id) {
                    const paper2 = paperData.find(p => p.id === currentPaper.paper2id);
                    if (paper2) {
                        selectedPaper2FileId = paper2.fileId; 
                        smartSuggestionBox.classList.remove('hidden');
                    }
                }
            } 
            
            applyFilters();
        }
        
        function suggestDownloadPaper2() {
            if (selectedPaper2FileId) {
                window.open(DRIVE_BASE_LINK_DOWNLOAD + selectedPaper2FileId, '_blank'); 
                alert("Downloading Paper II now from Google Drive!");
            }
        }


        // --- 5. UTILITY: RENDERING CARDS (Includes Marking Scheme Button) ---

        function createPaperCard(paper) {
            const div = document.createElement('div');
            div.className = 'paper-card';
            
            const infoDiv = document.createElement('div');
            infoDiv.className = 'paper-info';
            
            const title = document.createElement('p');
            title.className = 'paper-title';
            title.textContent = paper.title;
            
            const meta = document.createElement('p');
            meta.className = 'paper-meta';
            let metaText = `Level: ${paper.level.toUpperCase()} | Subject: ${paper.subject.charAt(0).toUpperCase() + paper.subject.slice(1)} | Year: ${paper.year}`;
            if (paper.solved) {
                metaText += ' | <span style="color: var(--color-success); font-weight: 600;">✅ Solved</span>';
            }
            meta.innerHTML = metaText;
            
            infoDiv.appendChild(title);
            infoDiv.appendChild(meta);
            
            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'paper-actions';
            
            // 1. MARKING SCHEME BUTTON
            if (paper.markingSchemeId && paper.markingSchemeId.length > 5) { // Check for valid ID length
                const schemeLink = document.createElement('a');
                schemeLink.href = DRIVE_BASE_LINK_DOWNLOAD + paper.markingSchemeId;
                schemeLink.target = '_blank';
                schemeLink.className = 'scheme-btn';
                schemeLink.textContent = '📝 Get Marking';
                actionsDiv.appendChild(schemeLink);
            }

            // 2. VIEW PAPER BUTTON
            const viewLink = document.createElement('a');
            viewLink.href = DRIVE_BASE_LINK_VIEW + paper.fileId + '/view?usp=sharing'; 
            viewLink.target = '_blank';
            viewLink.className = 'view-btn';
            viewLink.textContent = '👁️ View';
            
            // 3. DOWNLOAD PAPER BUTTON
            const downloadLink = document.createElement('a');
            downloadLink.href = DRIVE_BASE_LINK_DOWNLOAD + paper.fileId;
            downloadLink.download = paper.title.replace(/ /g, '_') + '.pdf';
            downloadLink.className = 'download-btn';
            downloadLink.textContent = '⬇️ Get PDF';

            actionsDiv.appendChild(viewLink);
            actionsDiv.appendChild(downloadLink);
            
            div.appendChild(infoDiv);
            div.appendChild(actionsDiv);
            
            return div;
        }

        // --- 6. INITIALIZATION ---
        window.onload = () => {
            initializeYears();
            generateSuggestions();
            
            // Set initial state (All filters clear, All papers shown)
            document.getElementById('level').value = ''; 
            updateFilters();
        };

        function updateStreamSubjects() {
            const streamSelect = document.getElementById('stream');
            const subjectSelect = document.getElementById('subject');
            const selectedStream = streamSelect.value;
            
            // Clear current subjects
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';
            
            const subjects = streamSubjectMap[selectedStream] || [];
            
            subjects.forEach(sub => {
                const option = document.createElement('option');
                // Convert "Combined Maths" to "combined_maths" for data matching
                option.value = sub.toLowerCase().replace(/\s+/g, '_'); 
                option.textContent = sub;
                subjectSelect.appendChild(option);
            });
            
            // Trigger the paper results to refresh
            applyFilters();
        }

    </script>
</body>
</html>