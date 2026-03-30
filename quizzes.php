<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

// Database Connection
$conn = new mysqli("sql100.infinityfree.com", "if0_40714375", "LkE4u5SOS16CPC", "if0_40714375_eduzone_db");
if ($conn->connect_error) { die("Connection failed"); }

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Student';

// FIX: Check if table exists before querying to avoid Fatal Error
$table_check = $conn->query("SHOW TABLES LIKE 'user_results'");
$completed_count = 0;
if ($table_check->num_rows > 0) {
    $stats_query = "SELECT COUNT(*) as completed FROM user_results WHERE user_id = '$user_id'";
    $stats_res = $conn->query($stats_query);
    $completed_count = $stats_res->fetch_assoc()['completed'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzes Arena | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --gemini-blue: #4285f4;
            --gemini-purple: #9b72cb;
            --bg: #030712;
            --surface: #0f172a;
            --text-dim: #94a3b8;
            --accent-green: #10b981;
        }

        body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg); color: white; display: flex; overflow-x: hidden; }

        /* Sidebar Style */
        .sidebar { width: 260px; background: var(--surface); height: 100vh; padding: 30px 20px; position: fixed; border-right: 1px solid rgba(255,255,255,0.05); z-index: 100; }
        .logo-area { margin-bottom: 40px; }
        .logo-area h2 { background: linear-gradient(to right, var(--gemini-blue), var(--gemini-purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0; }
        
        .nav-item { 
            display: flex; align-items: center; padding: 14px; color: var(--text-dim); 
            text-decoration: none; border-radius: 12px; transition: 0.3s; margin-bottom: 8px; font-weight: 500;
        }
        .nav-item:hover, .nav-item.active { background: rgba(66, 133, 244, 0.1); color: var(--gemini-blue); }
        .nav-item i { margin-right: 12px; width: 20px; font-size: 1.1rem; }

        /* Main Content Area */
        .main-content { margin-left: 280px; padding: 40px; width: calc(100% - 280px); min-height: 100vh; }

        .user-welcome-card {
            background: linear-gradient(135deg, rgba(66, 133, 244, 0.15), rgba(155, 114, 203, 0.15));
            padding: 25px; border-radius: 20px; margin-bottom: 40px; border: 1px solid rgba(255,255,255,0.1);
            display: flex; justify-content: space-between; align-items: center;
        }

        .search-bar {
            position: relative; width: 100%; max-width: 400px;
        }
        .search-bar input {
            width: 100%; background: #1e293b; border: 1px solid rgba(255,255,255,0.1);
            padding: 12px 15px 12px 45px; border-radius: 10px; color: white;
        }
        .search-bar i { position: absolute; left: 15px; top: 15px; color: var(--text-dim); }

        /* Quiz Grid */
        .quiz-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-top: 30px; }
        
        .quiz-card {
            background: var(--surface); border-radius: 20px; padding: 25px;
            border: 1px solid rgba(255,255,255,0.05); transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; overflow: hidden;
        }
        .quiz-card:hover { transform: translateY(-10px); border-color: var(--gemini-blue); box-shadow: 0 15px 30px rgba(0,0,0,0.4); }

        .diff-badge { font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; margin-bottom: 15px; display: inline-block; }
        .diff-easy { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .diff-medium { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
        .diff-hard { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

        .subject-tag { color: var(--gemini-blue); font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; display: block; }

        .quiz-meta { display: flex; gap: 15px; color: var(--text-dim); font-size: 0.85rem; margin: 20px 0; }
        
        .btn-start {
            width: 100%; padding: 12px; background: rgba(66, 133, 244, 0.1); color: var(--gemini-blue);
            border: 1px solid var(--gemini-blue); border-radius: 10px; cursor: pointer; font-weight: 600; transition: 0.3s;
        }
        .btn-start:hover { background: var(--gemini-blue); color: white; }

        .filter-nav { margin-bottom: 30px; display: flex; gap: 10px; }
        .filter-btn { 
            background: transparent; border: 1px solid rgba(255,255,255,0.1); color: var(--text-dim);
            padding: 8px 20px; border-radius: 20px; cursor: pointer; transition: 0.3s;
        }
        .filter-btn.active { background: var(--gemini-blue); color: white; border-color: var(--gemini-blue); }

    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-area">
            <h2>EDUZONE PRO</h2>
            <p style="color: var(--text-dim); font-size: 0.75rem;">AI-Powered Learning</p>
        </div>
        <nav>
            <a href="home.php" class="nav-item"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="quizzes.php" class="nav-item active"><i class="fas fa-brain"></i> Quiz Arena</a>
            <a href="materials.php" class="nav-item"><i class="fas fa-book"></i> Library</a>
            <a href="leaderboard.php" class="nav-item"><i class="fas fa-award"></i> Rankings</a>
            <a href="logout.php" class="nav-item" style="margin-top: 50px; color: #f87171;"><i class="fas fa-power-off"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="user-welcome-card">
            <div>
                <h2 style="margin: 0;">Hello, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
                <p style="color: var(--text-dim); margin: 5px 0 0 0;">You've mastered <b><?php echo $completed_count; ?></b> challenges this week. Keep it up!</p>
            </div>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="quizSearch" placeholder="Search subjects..." onkeyup="searchQuizzes()">
            </div>
        </div>

        <div class="filter-nav">
            <button class="filter-btn active" onclick="filterQuizzes('all', this)">All Quizzes</button>
            <button class="filter-btn" onclick="filterQuizzes('OL', this)">O/Level</button>
            <button class="filter-btn" onclick="filterQuizzes('AL', this)">A/Level</button>
        </div>

        <div class="quiz-grid" id="quizContainer">
            <?php
            $sql = "SELECT * FROM quizzes ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            ?>
                <div class="quiz-card" 
                     data-level="<?php echo $row['education_level']; ?>" 
                     data-search="<?php echo strtolower($row['title'] . ' ' . $row['subject']); ?>">
                    
                    <span class="diff-badge diff-<?php echo strtolower($row['difficulty']); ?>">
                        <?php echo $row['difficulty']; ?>
                    </span>
                    <span class="subject-tag"><?php echo strtoupper($row['subject']); ?> • <?php echo $row['education_level']; ?></span>
                    <h3 style="margin: 10px 0;"><?php echo $row['title']; ?></h3>
                    
                    <div class="quiz-meta">
                        <span><i class="far fa-clock"></i> <?php echo $row['time_limit']; ?>m</span>
                        <span><i class="far fa-list-alt"></i> <?php echo $row['question_count']; ?> Questions</span>
                    </div>

                    <button class="btn-start" onclick="startQuiz(<?php echo $row['id']; ?>)">
                        Start Attempt <i class="fas fa-arrow-right" style="margin-left: 8px; font-size: 0.8rem;"></i>
                    </button>
                </div>
            <?php endwhile; else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 50px; color: var(--text-dim);">
                    <i class="fas fa-ghost" style="font-size: 3rem; margin-bottom: 20px;"></i>
                    <p>No quizzes found. Admin is uploading new content!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function searchQuizzes() {
            let val = document.getElementById('quizSearch').value.toLowerCase();
            document.querySelectorAll('.quiz-card').forEach(card => {
                card.style.display = card.dataset.search.includes(val) ? "block" : "none";
            });
        }

        function filterQuizzes(level, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            document.querySelectorAll('.quiz-card').forEach(card => {
                if (level === 'all' || card.dataset.level === level) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        }

        function startQuiz(id) {
            Swal.fire({
                title: 'Start Quiz?',
                text: "The timer will begin once you confirm.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4285f4',
                confirmButtonText: 'Yes, Start!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `play_quiz.php?id=${id}`;
                }
            })
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>