<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}


$conn = new mysqli("sql100.infinityfree.com", "if0_40714375", "LkE4u5SOS16CPC", "if0_40714375_eduzone_db");
if ($conn->connect_error) { die("Connection failed"); }

/**
 * SQL LOGIC:
 * 1. JOIN users and user_results.
 * 2. SUM the total scores.
 * 3. AVG the percentages to see accuracy.
 * 4. COUNT total quizzes attempted.
 */
$sql = "SELECT 
            u.user_name, 
            SUM(r.score) as total_score, 
            COUNT(r.id) as quizzes_played,
            AVG(r.percentage) as avg_accuracy
        FROM users u
        JOIN user_results r ON u.user_id = r.user_id
        GROUP BY u.user_id
        ORDER BY total_score DESC, avg_accuracy DESC
        LIMIT 10";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Leaderboard | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gemini-blue: #4285f4;
            --gemini-purple: #9b72cb;
            --bg: #030712;
            --surface: #0f172a;
            --gold: #fbbf24;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: white;
            display: flex;
        }

        /* Sidebar Sidebar (Inherited) */
        .sidebar { width: 260px; background: var(--surface); height: 100vh; padding: 30px 20px; position: fixed; border-right: 1px solid rgba(255,255,255,0.05); }
        .nav-item { display: flex; align-items: center; padding: 12px; color: #94a3b8; text-decoration: none; border-radius: 10px; margin-bottom: 5px; }
        .nav-item.active { background: rgba(66, 133, 244, 0.1); color: var(--gemini-blue); }

        .main-content { margin-left: 280px; padding: 50px; width: 100%; }

        header h1 { font-size: 2.5rem; margin-bottom: 10px; }
        header p { color: #94a3b8; margin-bottom: 40px; }

        /* Leaderboard Table */
        .leaderboard-container {
            background: var(--surface);
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 20px; background: rgba(255,255,255,0.02); color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.02); }

        .rank-cell { font-weight: 800; width: 50px; text-align: center; }
        .user-cell { display: flex; align-items: center; gap: 15px; }
        .avatar { width: 35px; height: 35px; background: var(--gemini-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
        .score-pill { 
            background: rgba(16, 185, 129, 0.1); color: #10b981; 
            padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 0.9rem;
        }

        .rank-1 { color: var(--gold); }
        .rank-1 .avatar { background: var(--gold); color: black; }

        /* Animation */
        tr { transition: 0.3s; }
        tr:hover { background: rgba(255,255,255,0.02); cursor: default; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="color: var(--gemini-blue);">EDUZONE</h2>
        <nav style="margin-top: 30px;">
            <a href="home.php" class="nav-item"><i class="fas fa-home"></i> Dashboard</a>
            <a href="quizzes.php" class="nav-item"><i class="fas fa-brain"></i> Quizzes</a>
            <a href="leaderboard.php" class="nav-item active"><i class="fas fa-trophy"></i> Leaderboard</a>
            <a href="logout.php" class="nav-item" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <header>
            <h1>Global Rankings</h1>
            <p>The smartest minds on EduZone Pro, ranked by total points earned.</p>
        </header>

        <div class="leaderboard-container">
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Student</th>
                        <th>Quizzes</th>
                        <th>Avg Accuracy</th>
                        <th>Total Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    if ($result && $result->num_rows > 0):
                        while($row = $result->fetch_assoc()): 
                            $class = ($rank == 1) ? 'rank-1' : '';
                    ?>
                        <tr class="<?php echo $class; ?>">
                            <td class="rank-cell">#<?php echo $rank; ?></td>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar"><?php echo substr($row['user_name'], 0, 1); ?></div>
                                    <span><?php echo htmlspecialchars($row['user_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo $row['quizzes_played']; ?> Attempts</td>
                            <td><?php echo number_format($row['avg_accuracy'], 1); ?>%</td>
                            <td><span class="score-pill"><?php echo number_format($row['total_score']); ?> XP</span></td>
                        </tr>
                    <?php 
                        $rank++;
                        endwhile; 
                    else:
                    ?>
                        <tr><td colspan="5" style="text-align:center; padding: 40px;">No rankings available yet. Be the first!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>