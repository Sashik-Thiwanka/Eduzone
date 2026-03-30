<?php
/**
 * EDUZONE NEXUS - PRO VERSION
 * Optimized for 2025 Professional Standards
 */
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Guest Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduZone Nexus | The Ultimate Digital Library</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --accent: #6366f1;
            --accent-light: #818cf8;
            --accent-dark: #4f46e5;
            --bg: #020617;
            --surface: #0f172a;
            --surface-light: #1e293b;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-hover: rgba(255, 255, 255, 0.07);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; outline: none; }
        
        body {
            background-color: var(--bg);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* --- CUSTOM SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--surface-light); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* --- ANIMATIONS --- */
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }

        /* --- NAVIGATION --- */
        nav {
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(20px);
            height: 85px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 2000;
        }

        .logo { 
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.8rem; 
            font-weight: 700; 
            color: #fff; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 12px;
            letter-spacing: -1px;
        }
        .logo span { color: var(--accent); }

        .nav-links { display: flex; gap: 30px; }
        .nav-links a { 
            color: var(--text-muted); 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 0.95rem; 
            transition: 0.3s;
            position: relative;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--accent); }
        .nav-links a.active::after {
            content: ''; position: absolute; bottom: -10px; left: 0; width: 100%; height: 2px; background: var(--accent);
        }

        /* --- LAYOUT STRUCTURE --- */
        .nexus-wrapper { display: flex; min-height: calc(100vh - 85px); }

        /* --- SIDEBAR TOOLS --- */
        .side-panel {
            width: 280px;
            border-right: 1px solid var(--border);
            padding: 30px;
            background: rgba(15, 23, 42, 0.5);
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .side-group h4 { 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 1.5px; 
            color: var(--accent); 
            margin-bottom: 20px;
        }

        .tool-link {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            transition: 0.3s;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .tool-link:hover { background: var(--glass-hover); color: #fff; }
        .tool-link i { font-size: 1.1rem; width: 20px; }

        /* --- MAIN CONTENT --- */
        .content-area { flex: 1; padding: 40px 5%; overflow-y: auto; }

        /* --- HERO --- */
        .hero-banner {
            background: linear-gradient(135deg, var(--accent) 0%, #312e81 100%);
            border-radius: 30px;
            padding: 60px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(99, 102, 241, 0.3);
        }
        .hero-banner::after {
            content: '\f5da'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            position: absolute; right: -50px; top: -50px; font-size: 20rem; opacity: 0.1;
        }
        .hero-banner h1 { font-size: 3.5rem; margin: 0; font-weight: 800; letter-spacing: -2px; }
        .hero-banner p { font-size: 1.2rem; opacity: 0.9; max-width: 500px; }

        /* --- ADVANCED SEARCH & FILTER --- */
        .master-filter {
            background: var(--surface);
            padding: 25px;
            border-radius: 24px;
            border: 1px solid var(--border);
            display: grid;
            grid-template-columns: repeat(4, 1fr) auto;
            gap: 15px;
            margin-bottom: 60px;
            backdrop-filter: blur(10px);
        }

        .filter-group label { display: block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px; font-weight: 600; }
        .filter-group select, .filter-group input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            color: white;
            padding: 12px 15px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-search {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            height: 48px;
            align-self: end;
            transition: 0.3s;
        }
        .btn-search:hover { background: var(--accent-dark); transform: translateY(-2px); }

        /* --- STRIPLINE CATEGORIES --- */
        .section-label { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 25px; 
            padding-left: 10px;
            border-left: 4px solid var(--accent);
        }
        .section-label h2 { margin: 0; font-size: 1.8rem; font-weight: 800; }

        .stripline {
            display: flex;
            gap: 25px;
            overflow-x: auto;
            padding: 15px 5px 40px;
            margin: 0 -10px;
            scrollbar-width: none;
        }
        .stripline::-webkit-scrollbar { display: none; }

        /* --- PREMIUM BOOK CARD --- */
        .book-card {
            min-width: 240px;
            max-width: 240px;
            background: var(--surface);
            border-radius: 24px;
            padding: 20px;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
        }
        .book-card:hover {
            transform: translateY(-12px) scale(1.02);
            border-color: var(--accent);
            box-shadow: 0 25px 50px -15px rgba(0,0,0,0.6);
        }

        .book-cover {
            width: 100%;
            aspect-ratio: 3/4;
            background: linear-gradient(45deg, var(--bg), var(--surface-light));
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .book-cover i { font-size: 3.5rem; color: var(--accent); opacity: 0.5; transition: 0.3s; }
        .book-card:hover .book-cover i { transform: scale(1.2); opacity: 1; color: #fff; }

        .book-badge {
            position: absolute; top: 12px; left: 12px;
            background: rgba(99, 102, 241, 0.9);
            color: white; font-size: 0.65rem; font-weight: 800;
            padding: 4px 10px; border-radius: 6px; text-transform: uppercase;
        }

        .book-title { font-size: 1.05rem; font-weight: 700; margin: 0 0 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8rem; }
        .book-meta { font-size: 0.8rem; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center; }

        /* --- STATS GRID --- */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 60px; }
        .stat-card { background: var(--glass); padding: 25px; border-radius: 20px; border: 1px solid var(--border); text-align: center; }
        .stat-card h3 { font-size: 1.8rem; margin: 0; color: var(--accent); }
        .stat-card p { margin: 5px 0 0; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; }

        /* --- GOV PORTAL MODAL --- */
        .modal {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.9);
            backdrop-filter: blur(10px); z-index: 9999; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-box {
            background: var(--surface); border: 1px solid var(--accent);
            max-width: 600px; width: 100%; border-radius: 30px; padding: 40px;
            text-align: center; position: relative;
            animation: slideUp 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .close-modal { position: absolute; top: 20px; right: 25px; font-size: 1.8rem; cursor: pointer; color: var(--text-muted); }

        /* --- FOOTER --- */
        footer { border-top: 1px solid var(--border); padding: 60px 5%; margin-top: 100px; text-align: center; }
        .footer-logo { font-size: 1.5rem; font-weight: 800; margin-bottom: 20px; display: block; text-decoration: none; color: #fff; }

        @media (max-width: 1100px) {
            .side-panel { display: none; }
            .master-filter { grid-template-columns: 1fr 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<nav>
    <a href="home.php" class="logo">EDU<span>ZONE</span></a>
    <div class="nav-links">
        <a href="home.php">Dashboard</a>
        <a href="quizzes.php">Quizzes</a>
        <a href="videolessons.php">Video Library</a>
        <a href="e-books.php" class="active">E-Library</a>
        <a href="pastpapers.php">Exam Portal</a>
    </div>
    <div style="display:flex; align-items:center; gap:15px;">
        <i class="fas fa-search" style="color:var(--text-muted); cursor:pointer;"></i>
        <div style="width:40px; height:40px; border-radius:12px; background:var(--accent); display:flex; align-items:center; justify-content:center; font-weight:800;"><?php echo substr($userName, 0, 1); ?></div>
    </div>
</nav>

<div class="nexus-wrapper">
    <aside class="side-panel">
        <div class="side-group">
            <h4>General Navigation</h4>
            <a href="#" class="tool-link"><i class="fas fa-home"></i> Dashboard</a>
            <a href="#" class="tool-link"><i class="fas fa-bookmark"></i> My Bookmarks</a>
            <a href="#" class="tool-link"><i class="fas fa-history"></i> Recent Activity</a>
        </div>
        
        <div class="side-group">
            <h4>External Nodes</h4>
            <a href="http://www.edupub.gov.lk" target="_blank" class="tool-link"><i class="fas fa-university"></i> EduPub Portal</a>
            <a href="http://www.nie.lk" target="_blank" class="tool-link"><i class="fas fa-graduation-cap"></i> NIE Resources</a>
            <a href="http://www.doenets.lk" target="_blank" class="tool-link"><i class="fas fa-file-signature"></i> Exams Dept</a>
        </div>

        <div class="side-group">
            <h4>Library Cloud</h4>
            <div style="background: var(--glass); padding: 15px; border-radius: 12px; border: 1px solid var(--border);">
                <p style="font-size: 0.75rem; margin: 0 0 10px; color: var(--text-muted);">Storage Used</p>
                <div style="height:6px; background:var(--bg); border-radius:10px; margin-bottom:10px;">
                    <div style="width:65%; height:100%; background:var(--accent); border-radius:10px;"></div>
                </div>
                <p style="font-size: 0.7rem; margin: 0; color: var(--text-main); font-weight: 700;">6.5 GB / 10 GB</p>
            </div>
        </div>
    </aside>

    <main class="content-area">
        
        <header class="hero-banner">
            <h1>Nexus E-Library</h1>
            <p>Access the most comprehensive collection of official textbooks, reference guides, and revision materials in Sri Lanka.</p>
            <div style="margin-top: 30px; display: flex; gap: 15px;">
                <button onclick="openGovModal()" style="background:#fff; color:var(--bg); padding:12px 25px; border-radius:12px; font-weight:800; border:none; cursor:pointer;">Official Gov Portal</button>
                <button style="background:rgba(0,0,0,0.2); color:#fff; padding:12px 25px; border-radius:12px; font-weight:800; border:1px solid rgba(255,255,255,0.3); cursor:pointer;">Join Student Library</button>
            </div>
        </header>

        <section class="stats-grid">
            <div class="stat-card"><h3>1,200+</h3><p>Official Textbooks</p></div>
            <div class="stat-card"><h3>450+</h3><p>Teacher Guides</p></div>
            <div class="stat-card"><h3>15k+</h3><p>Study Notes</p></div>
            <div class="stat-card"><h3>24/7</h3><p>Cloud Access</p></div>
        </section>

        <section class="master-filter">
            <div class="filter-group">
                <label>Medium / Language</label>
                <select id="fLang">
                    <option value="E">English Medium</option>
                    <option value="S">Sinhala Medium</option>
                    <option value="T">Tamil Medium</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Grade Level</label>
                <select id="fGrade">
                    <option value="13">Grade 13</option>
                    <option value="12">Grade 12</option>
                    <option value="11">Grade 11</option>
                    <option value="10">Grade 10</option>
                    <option value="9">Grade 9</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Content Type</label>
                <select id="fType">
                    <option value="TEXTBOOK">Textbook</option>
                    <option value="PASTPAPER">Past Papers</option>
                    <option value="SHORTNOTE">Short Notes</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Keywords</label>
                <input type="text" id="fSearch" placeholder="Search Biology, Physics...">
            </div>
            <button class="btn-search" onclick="performFilter()">Nexus Search</button>
        </section>

        <section class="section-label">
            <h2>Official Govt Collections</h2>
            <a href="#" style="color:var(--accent); font-weight:700; text-decoration:none; font-size:0.9rem;">View All</a>
        </section>
        <div class="stripline" id="govtStrip">
            </div>

        <section class="section-label" style="margin-top: 40px;">
            <h2>Revision & Mastery Kits</h2>
            <p style="color:var(--text-muted); font-size:0.8rem; margin:0;">Handpicked from external high-speed servers</p>
        </section>
        <div class="stripline" id="premiumStrip">
            </div>

        <section class="section-label" style="margin-top: 40px;">
            <h2>Global Reference Nodes</h2>
        </section>
        <div class="stripline" id="globalStrip">
            </div>

    </main>
</div>

<div id="govModal" class="modal">
    <div class="modal-box">
        <span class="close-modal" onclick="closeGovModal()">&times;</span>
        <i class="fas fa-university fa-4x" style="color:var(--accent); margin-bottom:20px;"></i>
        <h2 style="font-size: 2rem; margin-bottom: 10px;">EduPub Redirect Engine</h2>
        <p style="color:var(--text-muted); margin-bottom:30px;">You are about to be redirected to the official Government E-Books download site (edupub.gov.lk). Please ensure you have a stable connection for large PDF downloads.</p>
        
        <div style="background:var(--bg); padding:20px; border-radius:20px; text-align:left; margin-bottom:30px; border:1px solid var(--border);">
            <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                <span>Source Node:</span>
                <span style="color:var(--success); font-weight:700;">UP-ONLINE</span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>Target Node:</span>
                <span style="color:var(--accent); font-weight:700;">EDUPUB.GOV.LK</span>
            </div>
        </div>

        <button onclick="window.open('http://www.edupub.gov.lk/BooksDownload.php', '_blank')" class="btn-search" style="width:100%; height:60px; font-size:1.1rem;">Secure Redirect Now</button>
    </div>
</div>

<footer>
    <a href="#" class="footer-logo">EDU<span>ZONE</span> NEXUS</a>
    <p style="color:var(--text-muted); font-size:0.85rem;">&copy; 2025 EduZone Professional Academic Platform. All rights reserved.</p>
    <div style="display:flex; justify-content:center; gap:20px; margin-top:20px;">
        <i class="fab fa-facebook-f" style="color:var(--text-muted);"></i>
        <i class="fab fa-twitter" style="color:var(--text-muted);"></i>
        <i class="fab fa-linkedin-in" style="color:var(--text-muted);"></i>
    </div>
</footer>

<script>
/**
 * NEXUS LIBRARY ENGINE
 * Contains logic for dynamic content rendering and filtering
 */

const govtBooks = [
    { title: "Combined Maths Part I", grade: "13", medium: "E", site: "EduPub" },
    { title: "Biology Resource Book", grade: "13", medium: "E", site: "EduPub" },
    { title: "Chemistry Part I", grade: "12", medium: "E", site: "EduPub" },
    { title: "Physics Theory", grade: "12", medium: "E", site: "EduPub" },
    { title: "Science Grade 11", grade: "11", medium: "E", site: "EduPub" },
    { title: "Mathematics Grade 11", grade: "11", medium: "E", site: "EduPub" },
    { title: "ICT Mastery G10", grade: "10", medium: "E", site: "EduPub" }
];

const premiumResources = [
    { title: "AL Physics Past Papers (20yrs)", tag: "PDF", provider: "Fat.lk", url: "http://www.fat.lk" },
    { title: "Chemistry Revision Pack", tag: "Revision", provider: "GuruPaara", url: "http://www.gurupaara.lk" },
    { title: "Maths Marking Schemes", tag: "Exams", provider: "ExamPastPapers", url: "https://exampastpapers.lk" },
    { title: "Bio Diagrams Handout", tag: "Notes", provider: "Sarasavi", url: "https://www.sarasavi.lk" },
    { title: "O/L History Quick Guide", tag: "Summary", provider: "EduLanka", url: "http://edulanka.lk" }
];

const globalNodes = [
    { title: "Open Library", provider: "Archive.org", url: "https://archive.org" },
    { title: "Gutenberg Project", provider: "Global", url: "https://www.gutenberg.org" },
    { title: "MIT Courseware", provider: "USA", url: "https://ocw.mit.edu" },
    { title: "Khan Academy Docs", provider: "India", url: "https://www.khanacademy.org" }
];

function initNexus() {
    renderGovt();
    renderPremium();
    renderGlobal();
}

function renderGovt() {
    const strip = document.getElementById('govtStrip');
    strip.innerHTML = govtBooks.map(book => `
        <div class="book-card" onclick="openGovModal()">
            <div class="book-badge">${book.medium}</div>
            <div class="book-cover"><i class="fas fa-book-bookmark"></i></div>
            <div class="book-title">${book.title}</div>
            <div class="book-meta">
                <span>Grade ${book.grade}</span>
                <span style="color:var(--success)"><i class="fas fa-check-double"></i> Official</span>
            </div>
        </div>
    `).join('');
}

function renderPremium() {
    const strip = document.getElementById('premiumStrip');
    strip.innerHTML = premiumResources.map(res => `
        <div class="book-card" onclick="window.open('${res.url}', '_blank')">
            <div class="book-badge" style="background:var(--warning)">${res.tag}</div>
            <div class="book-cover" style="background:linear-gradient(45deg, #1e1b4b, #312e81)"><i class="fas fa-star"></i></div>
            <div class="book-title">${res.title}</div>
            <div class="book-meta">
                <span>${res.provider}</span>
                <i class="fas fa-external-link-alt"></i>
            </div>
        </div>
    `).join('');
}

function renderGlobal() {
    const strip = document.getElementById('globalStrip');
    strip.innerHTML = globalNodes.map(node => `
        <div class="book-card" style="min-width:200px;" onclick="window.open('${node.url}', '_blank')">
            <div class="book-cover" style="aspect-ratio:1/1; border-radius:50%;"><i class="fas fa-globe-americas"></i></div>
            <div class="book-title" style="text-align:center; height:auto;">${node.title}</div>
            <p style="text-align:center; font-size:0.7rem; color:var(--text-muted);">${node.provider}</p>
        </div>
    `).join('');
}

function openGovModal() {
    document.getElementById('govModal').style.display = 'flex';
}

function closeGovModal() {
    document.getElementById('govModal').style.display = 'none';
}

function performFilter() {
    const grade = document.getElementById('fGrade').value;
    const lang = document.getElementById('fLang').value;
    const search = document.getElementById('fSearch').value.toLowerCase();
    
    // Simulate API fetch delay
    const btn = document.querySelector('.btn-search');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Linking...';
    
    setTimeout(() => {
        btn.innerHTML = 'Nexus Search';
        // Logic to highlight filtered content would go here
        alert(`Nexus Filter Applied: Grade ${grade}, Medium ${lang}. Scouring external servers for "${search}"...`);
    }, 800);
}

// Start Nexus
initNexus();

// Close modal on click outside
window.onclick = function(event) {
    if (event.target == document.getElementById('govModal')) {
        closeGovModal();
    }
}
</script>

</body>
</html>