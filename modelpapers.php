<?php
/**
 * EDUZONE MODEL PAPERS PRO - V5.0
 * The ultimate examination resource hub
 */
session_start();

// 1. AUTHENTICATION & SESSION MANAGEMENT
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Guest';
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// 2. ANALYTICS SIMULATION (For UI Depth)
$totalPapers = 1250;
$downloadsToday = 432;
$activeUsers = 128;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Papers | EduZone Professional</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --bg: #030712;
            --surface: #0f172a;
            --surface-hover: #1e293b;
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #22c55e;
            --warning: #f59e0b;
        }

        /* --- SMOOTH BASE SCROLL --- */
        html { scroll-behavior: smooth; }
        * { box-sizing: border-box; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        body { 
            background-color: var(--bg); 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0; 
            overflow-x: hidden;
        }

        /* --- CUSTOM SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--surface-hover); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* --- ADVANCED NAVIGATION --- */
        nav {
            background: rgba(3, 7, 18, 0.85);
            backdrop-filter: blur(25px);
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 2000;
        }
        .nav-logo { 
            font-size: 1.8rem; 
            font-weight: 800; 
            text-decoration: none; 
            color: white; 
            letter-spacing: -1.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-logo i { color: var(--primary); font-size: 2.2rem; }
        .nav-logo span { background: linear-gradient(to right, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .nav-center { display: flex; gap: 30px; }
        .nav-center a { color: var(--text-muted); text-decoration: none; font-weight: 600; font-size: 0.95rem; padding: 10px 0; position: relative; }
        .nav-center a:hover, .nav-center a.active { color: white; }
        .nav-center a.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--primary); border-radius: 10px; }

        .auth-widget { display: flex; align-items: center; gap: 20px; border-left: 1px solid var(--border); padding-left: 30px; }
        .user-pill { 
            display: flex; align-items: center; gap: 12px; background: var(--surface); padding: 8px 20px; 
            border-radius: 50px; border: 1px solid var(--border); cursor: pointer;
        }
        .user-pill:hover { border-color: var(--primary); background: var(--surface-hover); }
        .avatar { width: 32px; height: 32px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; }

        /* --- LAYOUT STRUCTURE --- */
        .master-container { display: flex; min-height: calc(100vh - 90px); }

        /* --- ENHANCED SIDEBAR --- */
        aside {
            width: 320px;
            padding: 40px 25px;
            background: rgba(15, 23, 42, 0.3);
            border-right: 1px solid var(--border);
            position: sticky;
            top: 90px;
            height: calc(100vh - 90px);
            overflow-y: auto;
        }
        .side-title { font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px; display: block; }
        
        .subject-group { margin-bottom: 30px; }
        .sub-btn {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; padding: 14px 18px; border-radius: 14px; border: none;
            background: transparent; color: var(--text-muted); font-weight: 600; 
            cursor: pointer; margin-bottom: 5px; text-align: left;
        }
        .sub-btn i { width: 20px; }
        .sub-btn:hover, .sub-btn.active { background: var(--surface); color: white; transform: translateX(5px); }
        .sub-btn.active { color: var(--primary); border-left: 4px solid var(--primary); border-radius: 0 14px 14px 0; }
        .badge { font-size: 0.7rem; background: rgba(255,255,255,0.05); padding: 4px 10px; border-radius: 20px; }

        /* --- MAIN CONTENT --- */
        main { flex: 1; padding: 40px 4%; }

        /* PROCTOR HEADER */
        .pro-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
            border-radius: 35px; padding: 50px; margin-bottom: 40px; position: relative;
            overflow: hidden; border: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .pro-header::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            animation: pulse 10s infinite alternate;
        }
        @keyframes pulse { from { transform: scale(1); } to { transform: scale(1.1); } }

        .header-content h1 { font-size: 3rem; margin: 0; font-weight: 900; letter-spacing: -2px; }
        .header-content p { color: var(--text-muted); font-size: 1.1rem; margin-top: 15px; max-width: 500px; line-height: 1.6; }

        /* EXAM COUNTDOWN */
        .exam-timer {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 25px; border-radius: 25px; border: 1px solid var(--border);
            text-align: center; min-width: 250px;
        }
        .timer-grid { display: flex; gap: 15px; justify-content: center; margin-top: 10px; }
        .t-unit { display: flex; flex-direction: column; align-items: center; }
        .t-val { font-family: 'JetBrains Mono', monospace; font-size: 1.8rem; font-weight: 800; color: var(--primary); }
        .t-lbl { font-size: 0.65rem; text-transform: uppercase; color: var(--text-muted); }

        /* SEARCH BAR */
        .search-engine {
            background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
            padding: 20px; display: flex; gap: 20px; margin-bottom: 50px; align-items: center;
        }
        .search-engine input { 
            flex: 1; background: transparent; border: none; color: white; 
            font-size: 1.1rem; font-family: inherit; outline: none; 
        }
        .search-engine select {
            background: var(--bg); color: var(--text-muted); border: 1px solid var(--border);
            padding: 10px 20px; border-radius: 12px; font-weight: 600; cursor: pointer;
        }

        /* PAPER CARDS GRID */
        .papers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
        .paper-card {
            background: var(--surface); border-radius: 30px; padding: 30px; border: 1px solid var(--border);
            position: relative; overflow: hidden; display: flex; flex-direction: column;
        }
        .paper-card:hover { transform: translateY(-10px); border-color: var(--primary); box-shadow: 0 30px 60px -20px rgba(0,0,0,0.6); }
        
        .tag-row { display: flex; gap: 10px; margin-bottom: 20px; }
        .tag { font-size: 0.7rem; font-weight: 800; padding: 6px 12px; border-radius: 10px; text-transform: uppercase; }
        .tag-blue { background: rgba(14, 165, 233, 0.1); color: var(--secondary); }
        .tag-purple { background: rgba(99, 102, 241, 0.1); color: var(--primary); }

        .paper-title { font-size: 1.3rem; font-weight: 800; line-height: 1.4; margin-bottom: 15px; color: #fff; }
        .paper-meta { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 25px; display: flex; gap: 15px; }
        .paper-meta i { color: var(--primary); }

        .card-actions { display: flex; gap: 12px; margin-top: auto; }
        .btn-view { flex: 1.5; background: var(--primary); color: white; text-decoration: none; padding: 15px; border-radius: 15px; font-weight: 800; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-mirror { flex: 1; background: var(--bg); color: white; text-decoration: none; padding: 15px; border-radius: 15px; font-weight: 700; text-align: center; border: 1px solid var(--border); font-size: 0.85rem; }
        .btn-view:hover { background: var(--primary-light); box-shadow: 0 10px 20px -5px var(--primary); }

        /* --- STATS OVERLAY --- */
        .stats-strip { display: flex; gap: 40px; margin-top: 60px; padding-top: 40px; border-top: 1px solid var(--border); }
        .stat-item { display: flex; flex-direction: column; }
        .stat-val { font-size: 2rem; font-weight: 900; color: white; }
        .stat-lbl { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }

        /* --- REDIRECT MODAL --- */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 5000; align-items: center; justify-content: center; backdrop-filter: blur(10px); }
        .modal-box { width: 500px; background: var(--surface); border: 1px solid var(--primary); border-radius: 30px; padding: 40px; text-align: center; }
        .spinner { width: 60px; height: 60px; border: 5px solid rgba(255,255,255,0.1); border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 30px; }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 1100px) { aside { display: none; } }
    </style>
</head>
<body>

<nav>
    <a href="home.php" class="nav-logo">
        <i class="fas fa-graduation-cap"></i>
        <span>EDUZONE</span>
    </a>
    
    <div class="nav-center">
        <a href="home.php">Dashboard</a>
        <a href="e-books.php">E-Library</a>
        <a href="ictsoft.php">Software Forge</a>
        <a href="modelpapers.php" class="active">Model Papers</a>
        <a href="videolessons.php">Video Lessons</a>
    </div>

    <div class="auth-widget">
        <?php if($isLoggedIn): ?>
            <div class="user-pill" onclick="window.location.href='account.php'">
                <div class="avatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
                <div style="font-size: 0.9rem; font-weight: 700; color:white;"><?php echo htmlspecialchars($userName); ?></div>
            </div>
            <a href="logout.php" style="color:var(--text-muted); font-size:1.2rem;"><i class="fas fa-power-off"></i></a>
        <?php else: ?>
            <a href="login.html" style="color:white; text-decoration:none; font-weight:700;">Login</a>
            <a href="register.html" style="background:var(--primary); color:white; padding:10px 20px; border-radius:12px; text-decoration:none; font-weight:800; font-size:0.9rem;">Join Pro</a>
        <?php endif; ?>
    </div>
</nav>

<div class="master-container">
    <aside>
        <span class="side-title">Academic Streams</span>
        <div class="subject-group">
            <button class="sub-btn active" onclick="filterPapers('all', this)">
                <span><i class="fas fa-globe"></i> All Papers</span>
                <span class="badge"><?php echo $totalPapers; ?></span>
            </button>
            <button class="sub-btn" onclick="filterPapers('ict', this)">
                <span><i class="fas fa-microchip"></i> ICT & Tech</span>
                <span class="badge">45</span>
            </button>
            <button class="sub-btn" onclick="filterPapers('science', this)">
                <span><i class="fas fa-atom"></i> Science</span>
                <span class="badge">120</span>
            </button>
            <button class="sub-btn" onclick="filterPapers('maths', this)">
                <span><i class="fas fa-square-root-variable"></i> Mathematics</span>
                <span class="badge">88</span>
            </button>
            <button class="sub-btn" onclick="filterPapers('commerce', this)">
                <span><i class="fas fa-chart-line"></i> Commerce</span>
                <span class="badge">62</span>
            </button>
        </div>

        <span class="side-title">Resource Type</span>
        <div class="subject-group">
            <label class="sub-btn"><input type="checkbox" checked> Provincial Papers</label>
            <label class="sub-btn"><input type="checkbox" checked> School Term Tests</label>
            <label class="sub-btn"><input type="checkbox"> Marking Schemes</label>
        </div>

        <div style="margin-top:50px; padding:25px; background:rgba(99, 102, 241, 0.1); border:1px solid var(--primary); border-radius:25px;">
            <p style="font-weight:800; font-size:0.9rem; margin-bottom:10px;">GovDoc Integration</p>
            <p style="font-size:0.75rem; color:var(--text-muted); line-height:1.5;">Direct link to GovDoc.lk official repository for authenticated government model papers.</p>
        </div>
    </aside>

    <main>
        <header class="pro-header">
            <div class="header-content">
                <h1>Model Paper <span style="color:var(--primary)">Depot</span></h1>
                <p>Access high-quality mock examinations and provincial model papers with detailed marking schemes for the 2024/2025 session.</p>
            </div>
            
            <div class="exam-timer">
                <div class="t-lbl" style="color:var(--primary); font-weight:800; margin-bottom:10px;">A/L Exam Countdown</div>
                <div class="timer-grid">
                    <div class="t-unit"><span class="t-val" id="days">00</span><span class="t-lbl">Days</span></div>
                    <div class="t-unit"><span class="t-val" id="hours">00</span><span class="t-lbl">Hrs</span></div>
                    <div class="t-unit"><span class="t-val" id="mins">00</span><span class="t-lbl">Min</span></div>
                </div>
            </div>
        </header>

        <div class="search-engine">
            <i class="fas fa-search" style="font-size:1.2rem;"></i>
            <input type="text" id="paperSearch" placeholder="Search by paper title, year, or province..." onkeyup="searchEngine()">
            <select id="yearFilter" onchange="searchEngine()">
                <option value="">Any Year</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
            </select>
        </div>

        <div class="papers-grid" id="papersGrid">
            </div>

        <div class="stats-strip">
            <div class="stat-item">
                <span class="stat-val"><?php echo number_format($totalPapers); ?>+</span>
                <span class="stat-lbl">Total Resources</span>
            </div>
            <div class="stat-item">
                <span class="stat-val"><?php echo $downloadsToday; ?></span>
                <span class="stat-lbl">Downloads Today</span>
            </div>
            <div class="stat-item">
                <span class="stat-val" style="color:var(--success);"><?php echo $activeUsers; ?></span>
                <span class="stat-lbl">Live Learners</span>
            </div>
        </div>
    </main>
</div>

<div class="modal" id="redirectModal">
    <div class="modal-box">
        <div class="spinner"></div>
        <h2 style="margin-bottom:10px;">Leaving EduZone Secure</h2>
        <p style="color:var(--text-muted); line-height:1.6;">Redirecting you to the government resource repository (GovDoc.lk). Please ensure your connection is stable.</p>
        <p style="color:var(--primary); font-family:'JetBrains Mono'; margin-top:20px;" id="redirectTimer">Establishing link in 3s...</p>
    </div>
</div>

<script>
/**
 * MODEL PAPER ENGINE V5.0
 */
const papersDB = [
    { title: "Western Province A/L ICT Model Paper 2024", cat: "ict", year: "2024", type: "Provincial", provider: "DoE" },
    { title: "Combined Maths Structure & Essay - Full Mock", cat: "maths", year: "2024", type: "EduZone Special", provider: "Elite Panel" },
    { title: "G.C.E A/L Biology Provincial Paper - Southern", cat: "science", year: "2023", type: "Provincial", provider: "DoE" },
    { title: "Business Studies Paper II - Expected Qs", cat: "commerce", year: "2024", type: "Model", provider: "N.I.E" },
    { title: "Central Province Physics Term Test III", cat: "science", year: "2023", type: "Term Test", provider: "CP-DoE" },
    { title: "ICT Python & DB Programming Special Model", cat: "ict", year: "2024", type: "Expert", provider: "EduZone" },
    { title: "Accounting Part I - Model Practice Set", cat: "commerce", year: "2022", type: "Practice", provider: "N.I.E" }
];

function renderPapers(data) {
    const grid = document.getElementById('papersGrid');
    grid.innerHTML = data.map(p => `
        <div class="paper-card">
            <div class="tag-row">
                <span class="tag tag-blue">${p.cat}</span>
                <span class="tag tag-purple">${p.year}</span>
            </div>
            <div class="paper-title">${p.title}</div>
            <div class="paper-meta">
                <span><i class="fas fa-university"></i> ${p.provider}</span>
                <span><i class="fas fa-file-pdf"></i> PDF (2.4MB)</span>
            </div>
            <div class="card-actions">
                <button onclick="govRedirect()" class="btn-view">
                    <i class="fas fa-external-link-alt"></i> GovDoc Link
                </button>
                <button onclick="alert('Local Mirror Coming Soon!')" class="btn-mirror">Mirror</button>
            </div>
        </div>
    `).join('');
}

// 1. DYNAMIC SEARCH & FILTER
let currentCat = 'all';

function filterPapers(cat, el) {
    currentCat = cat;
    document.querySelectorAll('.sub-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    searchEngine();
}

function searchEngine() {
    const query = document.getElementById('paperSearch').value.toLowerCase();
    const year = document.getElementById('yearFilter').value;

    const filtered = papersDB.filter(p => {
        const matchesCat = (currentCat === 'all' || p.cat === currentCat);
        const matchesQuery = p.title.toLowerCase().includes(query);
        const matchesYear = (year === '' || p.year === year);
        return matchesCat && matchesQuery && matchesYear;
    });
    renderPapers(filtered);
}

// 2. EXAM COUNTDOWN
function startCountdown() {
    const target = new Date("November 25, 2025 08:30:00").getTime();
    
    setInterval(() => {
        const now = new Date().getTime();
        const diff = target - now;

        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

        document.getElementById('days').innerText = d < 10 ? '0' + d : d;
        document.getElementById('hours').innerText = h < 10 ? '0' + h : h;
        document.getElementById('mins').innerText = m < 10 ? '0' + m : m;
    }, 1000);
}

// 3. SECURE REDIRECT LOGIC
function govRedirect() {
    const modal = document.getElementById('redirectModal');
    const timerText = document.getElementById('redirectTimer');
    modal.style.display = 'flex';
    
    let count = 3;
    const interval = setInterval(() => {
        count--;
        timerText.innerText = `Establishing link in ${count}s...`;
        if(count === 0) {
            clearInterval(interval);
            window.open('https://govdoc.lk/category/model-papers/', '_blank');
            modal.style.display = 'none';
        }
    }, 1000);
}

// BOOT ENGINE
renderPapers(papersDB);
startCountdown();
</script>

</body>
</html>