<?php
/**
 * EDUZONE FORGE - V3.0 PREMIER
 * Includes Dynamic Auth Navigation & Advanced Software Logic
 */
session_start();

// Simulated session check - replace with your actual DB session logic
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduZone Forge | ICT Software Repository</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0ea5e9;
            --bg: #020617;
            --surface: #0f172a;
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --accent-grad: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        }

        * { box-sizing: border-box; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        body { background-color: var(--bg); color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; }

        /* --- DYNAMIC AUTH NAVIGATION --- */
        nav {
            background: rgba(2, 6, 23, 0.85); backdrop-filter: blur(20px);
            height: 85px; display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 2000;
        }
        .nav-brand { font-size: 1.6rem; font-weight: 800; text-decoration: none; color: white; letter-spacing: -1px; }
        .nav-brand span { color: var(--primary); }

        .nav-menu { display: flex; align-items: center; gap: 30px; }
        .nav-link { color: var(--text-dim); text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .nav-link:hover { color: var(--primary); }

        .auth-zone { display: flex; align-items: center; gap: 15px; border-left: 1px solid var(--border); padding-left: 30px; }
        
        /* Login/Register Buttons */
        .btn-login { color: white; text-decoration: none; font-weight: 700; font-size: 0.9rem; }
        .btn-register { 
            background: var(--accent-grad); color: white; padding: 10px 22px; 
            border-radius: 12px; text-decoration: none; font-weight: 800; font-size: 0.9rem;
            box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.4);
        }

        /* Logged In Profile UI */
        .user-profile { display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.05); padding: 6px 15px; border-radius: 50px; border: 1px solid var(--border); }
        .avatar { width: 32px; height: 32px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; }
        .welcome-msg { font-size: 0.85rem; font-weight: 600; color: var(--text-main); }
        .btn-logout { color: #ef4444; text-decoration: none; font-size: 0.8rem; font-weight: 700; margin-left: 5px; }

        /* --- FORGE CORE UI --- */
        .forge-main { display: flex; min-height: calc(100vh - 85px); }
        
        aside {
            width: 280px; background: rgba(15, 23, 42, 0.3); border-right: 1px solid var(--border);
            padding: 40px 20px; position: sticky; top: 85px; height: calc(100vh - 85px);
        }

        .cat-item {
            display: flex; align-items: center; gap: 15px; padding: 14px 20px;
            border-radius: 14px; cursor: pointer; color: var(--text-dim); font-weight: 600; margin-bottom: 8px;
        }
        .cat-item:hover { background: var(--surface); color: white; }
        .cat-item.active { background: var(--accent-grad); color: white; }

        section.content { flex: 1; padding: 50px 5%; }
        
        .hero { 
            background: var(--surface); border: 1px solid var(--border); padding: 50px; 
            border-radius: 35px; margin-bottom: 50px; position: relative; overflow: hidden;
        }
        .hero h1 { font-size: 3rem; margin: 0; font-weight: 800; }
        .hero p { color: var(--text-dim); font-size: 1.1rem; max-width: 600px; margin-top: 15px; }

        .software-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; }

        .soft-card {
            background: var(--surface); border: 1px solid var(--border); padding: 30px;
            border-radius: 28px; display: flex; flex-direction: column; position: relative;
        }
        .soft-card:hover { transform: translateY(-10px); border-color: var(--primary); box-shadow: 0 30px 60px -15px rgba(0,0,0,0.5); }
        
        .icon-box { width: 65px; height: 65px; background: var(--bg); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary); margin-bottom: 25px; border: 1px solid var(--border); }
        
        .dl-actions { display: flex; gap: 12px; margin-top: auto; padding-top: 30px; }
        .btn-main { flex: 1; background: var(--accent-grad); color: white; padding: 14px; border-radius: 12px; text-decoration: none; text-align: center; font-weight: 800; font-size: 0.9rem; }
        .btn-outline { flex: 1; border: 1px solid var(--border); color: white; padding: 14px; border-radius: 12px; text-decoration: none; text-align: center; font-weight: 700; font-size: 0.9rem; }

        /* --- SEARCH OVERLAY --- */
        .search-bar { background: var(--surface); border: 1px solid var(--border); padding: 15px 25px; border-radius: 15px; width: 100%; color: white; margin-bottom: 40px; font-family: inherit; }

        /* --- TERMINAL ANIMATION --- */
        #terminal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 5000; align-items: center; justify-content: center; }
        .term-box { width: 550px; background: #000; border: 1px solid var(--primary); padding: 30px; border-radius: 20px; font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="nav-brand">EDU<span>ZONE</span></a>
    
    <div class="nav-menu">
        <a href="home.php" class="nav-link">Home</a>
        <a href="e-books.php" class="nav-link">E-Books</a>
        <a href="videolessons.php" class="nav-link">Model Papers</a>
        <a href="modelpapers.php" class="nav-link">Model Papers</a>
        <a href="pastpapers.php" class="nav-link">Past Papers</a>
        <a href="ictsoft.php" class="nav-link active" style="color:var(--primary)">Software</a>
    </div>

    <div class="auth-zone">
        <?php if($isLoggedIn): ?>
            <div class="user-profile">
                <div class="avatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
                <div class="welcome-msg">Hi, <?php echo htmlspecialchars($userName); ?></div>
                <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn-login">Login</a>
            <a href="register.php" class="btn-register">Join EduZone</a>
        <?php endif; ?>
    </div>
</nav>

<div class="forge-main">
    <aside>
        <div class="cat-item active" onclick="filterCat('all', this)"><i class="fas fa-border-all"></i> All Tools</div>
        <div class="cat-item" onclick="filterCat('programming', this)"><i class="fas fa-code"></i> Programming</div>
        <div class="cat-item" onclick="filterCat('database', this)"><i class="fas fa-database"></i> Database</div>
        <div class="cat-item" onclick="filterCat('design', this)"><i class="fas fa-palette"></i> Graphic Design</div>
        <div class="cat-item" onclick="filterCat('office', this)"><i class="fas fa-briefcase"></i> Office Suite</div>
    </aside>

    <section class="content">
        <input type="text" id="softSearch" class="search-bar" placeholder="Search for compilers, IDEs, or design tools..." onkeyup="searchSoft()">

        <div class="hero">
            <div style="position:relative; z-index:2;">
                <h1>Forge Repository</h1>
                <p>Industry-standard software curated for the Advanced Level ICT curriculum. Secure, high-speed official mirrors.</p>
            </div>
            <i class="fas fa-terminal" style="position:absolute; right:30px; bottom:-20px; font-size:15rem; opacity:0.03; color:var(--primary);"></i>
        </div>

        <div class="software-grid" id="softwareGrid">
            </div>
    </section>
</div>

<div id="terminal">
    <div class="term-box">
        <div style="color:var(--primary); margin-bottom:20px;">[EDUZONE SECURE DOWNLOAD PROTOCOL]</div>
        <div id="termOutput" style="color:#10b981; font-size:0.9rem; line-height:1.6;"></div>
    </div>
</div>

<script>
const db = [
    { name: "Visual Studio Code", cat: "programming", icon: "fas fa-code", desc: "The world's leading code editor for Python, HTML, and JS.", dl: "https://code.visualstudio.com/sha/download?build=stable&os=win32-x64-user", site: "https://code.visualstudio.com" },
    { name: "WAMP Server 64", cat: "database", icon: "fas fa-server", desc: "Apache, PHP, and MySQL stack for local web development.", dl: "https://sourceforge.net/projects/wampserver/files/latest/download", site: "https://www.wampserver.com" },
    { name: "MySQL Workbench", cat: "database", icon: "fas fa-database", desc: "Visual design tool for database modeling and SQL management.", dl: "https://dev.mysql.com/get/Downloads/MySQLGUITools/mysql-workbench-community-8.0.34-winx64.msi", site: "https://www.mysql.com" },
    { name: "PyCharm Community", cat: "programming", icon: "fab fa-python", desc: "Dedicated Python IDE for complex data structures and logic.", dl: "https://download.jetbrains.com/python/pycharm-community-2023.2.1.exe", site: "https://www.jetbrains.com/pycharm" },
    { name: "Vectorian Giotto", cat: "design", icon: "fas fa-pen-nib", desc: "Powerful Flash-style vector animation tool for ICT modules.", dl: "https://vectorian-giotto.en.softonic.com/download", site: "http://vectorian.com" },
    { name: "GIMP 2.10", cat: "design", icon: "fas fa-images", desc: "Professional image manipulation tool for graphics modules.", dl: "https://download.gimp.org/gimp/v2.10/windows/gimp-2.10.34-setup.exe", site: "https://www.gimp.org" },
    { name: "LibreOffice", cat: "office", icon: "fas fa-file-alt", desc: "Full productivity suite compatible with Microsoft Office formats.", dl: "https://www.libreoffice.org/download/download-libreoffice/", site: "https://www.libreoffice.org" }
];

let activeCat = 'all';

function render(data) {
    const grid = document.getElementById('softwareGrid');
    grid.innerHTML = data.map(s => `
        <div class="soft-card">
            <div class="icon-box"><i class="${s.icon}"></i></div>
            <h3 style="margin:0 0 10px;">${s.name}</h3>
            <p style="color:var(--text-dim); font-size:0.9rem; margin-bottom:20px;">${s.desc}</p>
            <div class="dl-actions">
                <button onclick="triggerDL('${s.dl}', '${s.name}')" class="btn-main">Secure Download</button>
                <a href="${s.site}" target="_blank" class="btn-outline">Official Site</a>
            </div>
        </div>
    `).join('');
}

function filterCat(cat, el) {
    activeCat = cat;
    document.querySelectorAll('.cat-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    applyFilters();
}

function searchSoft() {
    applyFilters();
}

function applyFilters() {
    const query = document.getElementById('softSearch').value.toLowerCase();
    const filtered = db.filter(s => {
        const matchesCat = (activeCat === 'all' || s.cat === activeCat);
        const matchesSearch = s.name.toLowerCase().includes(query);
        return matchesCat && matchesSearch;
    });
    render(filtered);
}

function triggerDL(url, name) {
    const term = document.getElementById('terminal');
    const out = document.getElementById('termOutput');
    term.style.display = 'flex';
    out.innerHTML = '';

    const logs = [
        `> Init secure bridge to vendor mirror...`,
        `> Verifying binary integrity for ${name}...`,
        `> Routing through local EduZone proxy...`,
        `> Transfer handshake established.`,
        `> Downloading...`
    ];

    logs.forEach((log, i) => {
        setTimeout(() => {
            out.innerHTML += `<div>${log}</div>`;
            if(i === logs.length - 1) {
                setTimeout(() => {
                    term.style.display = 'none';
                    window.location.href = url;
                }, 1000);
            }
        }, i * 450);
    });
}

// Initial Boot
render(db);
</script>

</body>
</html>