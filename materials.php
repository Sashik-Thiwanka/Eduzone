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

$conn = new mysqli("sql100.infinityfree.com","if0_40714375","LkE4u5SOS16CPC","if0_40714375_eduzone_db");
if ($conn->connect_error) { die("Database Connection Failed"); }

// Fetch Materials
$sql = "SELECT * FROM study_materials ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Study Materials | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gemini-blue: #4285f4;
            --gemini-purple: #9b72cb;
            --bg: #030712;
            --surface: #0f172a;
            --text-dim: #94a3b8;
        }

        body { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: white; display: flex; }

        /* Sidebar (Same as Quizzes) */
        .sidebar { width: 260px; background: var(--surface); height: 100vh; padding: 30px 20px; position: fixed; border-right: 1px solid rgba(255,255,255,0.05); }
        .nav-item { display: flex; align-items: center; padding: 12px; color: var(--text-dim); text-decoration: none; border-radius: 10px; margin-bottom: 5px; transition: 0.3s; }
        .nav-item:hover, .nav-item.active { background: rgba(66, 133, 244, 0.1); color: var(--gemini-blue); }
        .nav-item i { margin-right: 12px; width: 20px; }

        .main-content { margin-left: 280px; padding: 50px; width: calc(100% - 280px); }

        /* Material Cards */
        .materials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        
        .material-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: 0.3s;
            position: relative;
        }

        .material-card:hover { border-color: var(--gemini-blue); transform: translateY(-5px); }

        .file-icon {
            font-size: 2.5rem;
            color: var(--gemini-blue);
            margin-bottom: 20px;
            display: block;
        }

        .badge {
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(155, 114, 203, 0.1);
            color: var(--gemini-purple);
            text-transform: uppercase;
            font-weight: bold;
        }

        .download-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--gemini-blue);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .subject-label { color: var(--text-dim); font-size: 0.8rem; display: block; margin-top: 10px; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <h2 style="color: var(--gemini-blue);">EDUZONE</h2>
        <nav style="margin-top: 30px;">
            <a href="home.php" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
            <a href="quizzes.php" class="nav-item"><i class="fas fa-edit"></i> Quizzes Arena</a>
            <a href="materials.php" class="nav-item active"><i class="fas fa-file-pdf"></i> Study Materials</a>
            <a href="leaderboard.php" class="nav-item"><i class="fas fa-trophy"></i> Leaderboard</a>
            <a href="logout.php" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <header style="margin-bottom: 40px;">
            <h1>Library & Resources</h1>
            <p style="color: var(--text-dim);">Download PDF notes and guides curated by experts.</p>
        </header>

        <div class="materials-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="material-card">
                        <i class="fas fa-file-pdf file-icon"></i>
                        <span class="badge"><?php echo $row['education_level']; ?></span>
                        <h3 style="margin: 15px 0 5px 0;"><?php echo $row['title']; ?></h3>
                        <span class="subject-label"><?php echo $row['subject']; ?></span>
                        
                        <a href="<?php echo $row['file_path']; ?>" class="download-btn" download>
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No study materials uploaded yet.</p>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>