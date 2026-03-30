<?php
session_start();
$userName = $_SESSION['user_name'] ?? "Explorer";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Notes | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Tech variables */
        :root {
            --bg: #030712; --surface: #0f172a; --accent: #00f2ff;
            --glass: rgba(255, 255, 255, 0.03); --border: rgba(255, 255, 255, 0.1);
        }

        body { background: var(--bg); color: #fff; font-family: 'Inter', sans-serif; margin: 0; display: flex; }

        /* Sidebar Styling (High-Tech) */
        .sidebar { width: 280px; height: 100vh; background: var(--surface); border-right: 1px solid var(--border); position: fixed; padding: 20px; }
        .brand { display: flex; align-items: center; gap: 15px; font-weight: 800; color: var(--accent); margin-bottom: 40px; }
        .logo-hex { width: 40px; height: 40px; background: var(--accent); color: #000; display: flex; align-items: center; justify-content: center; clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%); }
        
        .nav-item { display: flex; align-items: center; padding: 14px; text-decoration: none; color: #94a3b8; border-radius: 12px; transition: 0.3s; margin-bottom: 5px; }
        .nav-item:hover, .nav-item.active { background: rgba(0, 242, 255, 0.1); color: var(--accent); }
        .nav-item i { margin-right: 15px; width: 20px; }

        /* Main Content */
        .main-content { margin-left: 280px; width: 100%; padding: 40px; }
        
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .search-box { background: var(--glass); border: 1px solid var(--border); padding: 12px 20px; border-radius: 15px; width: 400px; display: flex; align-items: center; }
        .search-box input { background: transparent; border: none; color: #fff; width: 100%; margin-left: 10px; outline: none; }

        /* Notes Grid */
        .notes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .note-card { background: var(--glass); border: 1px solid var(--border); border-radius: 20px; padding: 25px; transition: 0.3s; position: relative; overflow: hidden; }
        .note-card:hover { transform: translateY(-10px); border-color: var(--accent); box-shadow: 0 15px 30px rgba(0, 242, 255, 0.1); }
        
        .tag { font-size: 0.7rem; background: rgba(0, 242, 255, 0.1); color: var(--accent); padding: 5px 10px; border-radius: 20px; font-weight: 700; }
        .note-card h3 { margin: 15px 0 10px; font-size: 1.2rem; }
        .note-footer { display: flex; justify-content: space-between; margin-top: 20px; font-size: 0.8rem; color: #64748b; }
        
        .btn-download { background: var(--accent); color: #000; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <main class="main-content">
        <header class="page-header">
            <div>
                <h1>Smart Study Notes</h1>
                <p style="color: #64748b;">Premium curriculum-aligned resources for A/L & O/L.</p>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search by subject or topic...">
            </div>
        </header>

        <div class="notes-grid">
            <div class="note-card">
                <span class="tag">PHYSICS A/L</span>
                <h3>Quantum Mechanics Basics</h3>
                <p style="color: #94a3b8; font-size: 0.9rem;">Comprehensive notes covering wave-particle duality and Schrödinger's equation.</p>
                <div class="note-footer">
                    <span><i class="fas fa-file-pdf"></i> 4.2 MB</span>
                    <button class="btn-download">Download</button>
                </div>
            </div>

            <div class="note-card">
                <span class="tag">ICT O/L</span>
                <h3>Networking Essentials</h3>
                <p style="color: #94a3b8; font-size: 0.9rem;">Visual guide to network topologies and IP addressing for O/L students.</p>
                <div class="note-footer">
                    <span><i class="fas fa-file-pdf"></i> 2.8 MB</span>
                    <button class="btn-download">Download</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>