<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduZone | Professional Academic Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.4);
            --bg: #020617;
            --surface: #0f172a;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* --- PROFESSIONAL NAV (Matches Home) --- */
        nav {
            background: rgba(2, 6, 23, 0.95);
            backdrop-filter: blur(20px);
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 2000;
        }

        .logo { font-size: 1.6rem; font-weight: 800; color: #fff; text-decoration: none; letter-spacing: -0.5px; }
        .logo span { color: var(--accent); }

        .nav-links { display: flex; gap: 30px; }
        .nav-links a { 
            color: var(--text-muted); 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 0.9rem; 
            transition: 0.3s;
            position: relative;
        }
        .nav-links a.active { color: var(--accent); }
        .nav-links a.active::after {
            content: ''; position: absolute; bottom: -28px; left: 0; width: 100%; height: 2px; background: var(--accent);
        }

        .auth-area { display: flex; gap: 15px; align-items: center; }
        .user-pill {
            background: var(--glass);
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* --- ACADEMIC HERO SECTION --- */
        .hero {
            padding: 60px 6% 40px;
            text-align: center;
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.12) 0%, transparent 60%);
        }

        .hero h1 { font-size: 2.5rem; font-weight: 800; margin-bottom: 15px; letter-spacing: -1px; }
        .hero p { color: var(--text-muted); max-width: 600px; margin: 0 auto 40px; }

        /* --- SMART SEARCH CONTROLS --- */
        .search-engine {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr 2fr;
            gap: 12px;
            background: var(--surface);
            padding: 12px;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .search-engine select, .search-engine input {
            background: #1e293b;
            border: 1px solid transparent;
            color: white;
            padding: 16px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
            outline: none;
        }

        .search-engine select:focus, .search-engine input:focus {
            background: #0f172a;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }

        /* --- GRID SYSTEM --- */
        .main-container { max-width: 1400px; margin: 40px auto; padding: 0 6% 100px; }
        
        .section-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        /* --- PROFESSIONAL VIDEO CARD --- */
        .lesson-card {
            background: var(--surface);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.2, 1, 0.3, 1);
            cursor: pointer;
            position: relative;
        }

        .lesson-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        .thumb-box { position: relative; aspect-ratio: 16/9; background: #000; overflow: hidden; }
        .thumb-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .lesson-card:hover .thumb-box img { transform: scale(1.05); }

        .play-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,0.4);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: 0.3s;
        }
        .lesson-card:hover .play-overlay { opacity: 1; }

        .card-content { padding: 20px; }
        .card-content h3 { 
            margin: 0; font-size: 1rem; line-height: 1.5; font-weight: 700; 
            height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        }

        .meta-tags { display: flex; gap: 8px; margin-top: 15px; }
        .tag-edu { 
            background: rgba(99, 102, 241, 0.1); 
            color: var(--accent); 
            padding: 4px 10px; 
            border-radius: 6px; 
            font-size: 0.7rem; 
            font-weight: 800; 
            text-transform: uppercase; 
        }

        /* --- THEATER MODE PLAYER --- */
        #playerModal { 
            display: none; position: fixed; inset: 0; 
            background: rgba(2, 6, 23, 0.98); 
            z-index: 3000; align-items: center; justify-content: center; padding: 40px;
            backdrop-filter: blur(10px);
        }
        .modal-body { width: 100%; max-width: 1100px; position: relative; }
        .close-btn { 
            position: absolute; top: -50px; right: 0; 
            color: #fff; font-size: 2.5rem; cursor: pointer; transition: 0.3s;
        }
        .close-btn:hover { transform: rotate(90deg); color: var(--accent); }

        /* --- BUTTONS & SPINNER --- */
        .load-more-btn {
            display: block; margin: 60px auto; padding: 16px 40px; 
            background: var(--accent); color: white; border: none; 
            border-radius: 14px; font-weight: 800; cursor: pointer; transition: 0.3s;
        }
        .load-more-btn:hover { background: var(--accent-dark); transform: scale(1.02); box-shadow: 0 10px 20px var(--accent-glow); }

        .spinner { 
            width: 40px; height: 40px; border: 4px solid var(--border); 
            border-top-color: var(--accent); border-radius: 50%; 
            animation: spin 0.8s linear infinite; margin: 40px auto; 
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 850px) { .search-engine { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<nav>
    <a href="home.php" class="logo">EDU<span>ZONE</span></a>
    <div class="nav-links">
        <a href="home.php">Dashboard</a>
        <a href="videolessons.php" class="active">Video Lessons</a>
        <a href="notes.php">Resources</a>
        <a href="community.php">Discussion</a>
    </div>
    <div class="auth-area">
        <?php if($isLoggedIn): ?>
            <div class="user-pill">
                <i class="fas fa-graduation-cap"></i>
                <span><?php echo htmlspecialchars($userName); ?></span>
            </div>
        <?php else: ?>
            <a href="login.php" style="color:var(--text-muted); text-decoration:none; font-weight:600;">Sign In</a>
            <a href="signup.php" style="background:var(--accent); color:white; padding:10px 20px; border-radius:12px; text-decoration:none; font-weight:700;">Get Started</a>
        <?php endif; ?>
    </div>
</nav>

<header class="hero">
    <h1>Learn from the Best</h1>
    <p>Access high-fidelity academic lessons specifically curated for your curriculum. Only professional teaching content, zero distractions.</p>
    
    <div class="search-engine">
        <select id="level" onchange="initSubjects()">
            <option value="">Choose Your Level</option>
            <option value="A/L">Advanced Level (A/L)</option>
            <option value="O/L">Ordinary Level (O/L)</option>
        </select>
        <select id="subject" onchange="triggerNewSearch()" disabled>
            <option value="">Select Subject</option>
        </select>
        <input type="text" id="keywords" placeholder="What lesson are you looking for?" onkeyup="debounce()">
    </div>
</header>

<main class="main-container">
    <div class="section-header" id="resultsHeader" style="display:none;">
        <h2 style="font-size: 1.2rem; margin:0;">Recommended Lessons</h2>
        <span style="color:var(--text-muted); font-size: 0.9rem;"><i class="fas fa-check-circle" style="color:var(--accent)"></i> Academic Verified Only</span>
    </div>

    <div class="video-grid" id="lessonGrid">
        <div style="grid-column: 1/-1; text-align:center; padding:80px; color:var(--text-muted);">
            <i class="fas fa-search fa-4x" style="margin-bottom:20px; opacity:0.1;"></i>
            <h3>Start Your Learning Journey</h3>
            <p>Select your curriculum level and subject to fetch specialized videos.</p>
        </div>
    </div>
    
    <button id="loadMoreBtn" class="load-more-btn" style="display:none;" onclick="loadMore()">See More Professional Content</button>
</main>

<div id="playerModal">
    <div class="modal-body">
        <span class="close-btn" onclick="closePlayer()">&times;</span>
        <div style="aspect-ratio: 16/9;">
            <iframe id="eduIframe" width="100%" height="100%" src="" frameborder="0" allow="autoplay; fullscreen" style="border-radius:24px; border: 1px solid var(--border);"></iframe>
        </div>
    </div>
</div>

<script>
/**
 * PROFESSIONAL SEARCH ARCHITECTURE
 */
const API_KEY = 'AIzaSyBe8ypl24eh_txqhZD00qxxPdaOHTPULMY'; 
let pageToken = '';
const subjects = {
    "A/L": ["Combined Maths", "Physics", "Chemistry", "Biology", "ICT", "Economics", "Accounting"],
    "O/L": ["Mathematics", "Science", "English", "History", "Commerce", "ICT"]
};

function initSubjects() {
    const level = document.getElementById('level').value;
    const subSel = document.getElementById('subject');
    subSel.innerHTML = '<option value="">All Subjects</option>';
    if(level) {
        subSel.disabled = false;
        subjects[level].forEach(s => {
            let o = document.createElement('option');
            o.value = s; o.textContent = s;
            subSel.appendChild(o);
        });
        triggerNewSearch();
    }
}

let timer;
function debounce() {
    clearTimeout(timer);
    timer = setTimeout(triggerNewSearch, 800);
}

function triggerNewSearch() {
    pageToken = '';
    document.getElementById('lessonGrid').innerHTML = '';
    document.getElementById('resultsHeader').style.display = 'flex';
    loadMore();
}

async function loadMore() {
    const grid = document.getElementById('lessonGrid');
    const btn = document.getElementById('loadMoreBtn');
    const level = document.getElementById('level').value;
    const sub = document.getElementById('subject').value;
    const kw = document.getElementById('keywords').value;

    if(!level) return;

    btn.style.display = 'none';
    const loader = document.createElement('div');
    loader.className = 'spinner';
    grid.parentNode.insertBefore(loader, btn);

    /**
     * ADVANCED QUERY PURIFICATION
     * Using negative parameters (-shorts -funny) ensures only full lessons appear.
     */
    const query = `DP Education ${level} ${sub} ${kw} teaching full lesson -shorts -funny -comedy -vlog -motivation -tiktok -entertainment`.trim();
    const url = `https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=12&q=${encodeURIComponent(query)}&type=video&videoCategoryId=27&pageToken=${pageToken}&key=${API_KEY}`;

    try {
        const response = await fetch(url);
        const data = await response.json();
        loader.remove();

        if(data.items && data.items.length > 0) {
            data.items.forEach(item => {
                const vidId = item.id.videoId;
                const snippet = item.snippet;
                
                grid.innerHTML += `
                    <div class="lesson-card" onclick="openVideo('${vidId}')">
                        <div class="thumb-box">
                            <img src="${snippet.thumbnails.high.url}" alt="lesson">
                            <div class="play-overlay"><i class="fas fa-play-circle fa-4x" style="color:#fff;"></i></div>
                        </div>
                        <div class="card-content">
                            <div class="tag-edu">Educational Lesson</div>
                            <h3>${snippet.title}</h3>
                            <div class="meta-tags">
                                <span style="font-size:0.75rem; color:var(--text-muted);"><i class="fas fa-user-tie"></i> Verified Teacher</span>
                            </div>
                        </div>
                    </div>`;
            });
            pageToken = data.nextPageToken || '';
            btn.style.display = pageToken ? 'block' : 'none';
        } else if(!pageToken) {
            grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:50px;">No academic lessons matching your criteria.</div>';
        }
    } catch(e) {
        loader.remove();
        console.error("API Error", e);
    }
}

function openVideo(id) {
    document.getElementById('eduIframe').src = `https://www.youtube.com/embed/${id}?autoplay=1&rel=0&modestbranding=1`;
    document.getElementById('playerModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closePlayer() {
    document.getElementById('playerModal').style.display = 'none';
    document.getElementById('eduIframe').src = '';
    document.body.style.overflow = 'auto';
}
</script>

</body>
</html>