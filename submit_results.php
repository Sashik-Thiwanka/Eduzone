<?php
session_start();

// 1. SECURITY & SESSION CHECK
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

$conn = new mysqli("sql100.infinityfree.com", "if0_40714375", "LkE4u5SOS16CPC", "if0_40714375_eduzone_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$score = 0;
$total_questions = 0;
$percentage = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_id'])) {
    $user_id = $_SESSION['user_id'];
    $quiz_id = intval($_POST['quiz_id']);

    // 2. FETCH CORRECT ANSWERS
    $stmt = $conn->prepare("SELECT id, correct_option FROM questions WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // 3. SCORE CALCULATION ENGINE
    while ($row = $result->fetch_assoc()) {
        $total_questions++;
        $q_id = $row['id'];
        $correct = $row['correct_option'];
        
        // Match the radio button name "q123" to the question ID
        if (isset($_POST["q$q_id"]) && $_POST["q$q_id"] === $correct) {
            $score++;
        }
    }

    // Prevent division by zero if a quiz has no questions
    if ($total_questions > 0) {
        $percentage = ($score / $total_questions) * 100;
    }

    // 4. SECURE DATA PERSISTENCE
    $save_stmt = $conn->prepare("INSERT INTO user_results (user_id, quiz_id, score, total_questions, percentage) VALUES (?, ?, ?, ?, ?)");
    $save_stmt->bind_param("iiiid", $user_id, $quiz_id, $score, $total_questions, $percentage);
    
    if (!$save_stmt->execute()) {
        die("Error saving results: " . $conn->error);
    }
} else {
    header("Location: quizzes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Completed | EduZone Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --gemini-blue: #4285f4;
            --gemini-purple: #9b72cb;
            --bg: #030712;
            --surface: #0f172a;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .result-container {
            background: var(--surface);
            padding: 50px;
            border-radius: 24px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 90%;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .percentage-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid var(--gemini-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            background: rgba(66, 133, 244, 0.1);
        }

        .percentage-circle h2 { font-size: 2rem; margin: 0; color: var(--gemini-blue); }

        .stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-item span { display: block; color: #94a3b8; font-size: 0.8rem; margin-bottom: 5px; }
        .stat-item b { font-size: 1.2rem; }

        .action-btn {
            display: block;
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .btn-home { background: linear-gradient(to right, var(--gemini-blue), var(--gemini-purple)); color: white; }
        .btn-retry { background: transparent; border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .action-btn:hover { opacity: 0.9; transform: scale(1.02); }

    </style>
</head>
<body>

    <div class="result-container">
        <div class="percentage-circle">
            <h2><?php echo round($percentage); ?>%</h2>
        </div>

        <h1 style="margin-bottom: 10px;">
            <?php 
                if ($percentage >= 75) echo "Excellent!";
                elseif ($percentage >= 40) echo "Well Done!";
                else echo "Keep Learning!";
            ?>
        </h1>
        <p style="color: #94a3b8; margin-bottom: 30px;">Every attempt makes you smarter. Here's your performance breakdown:</p>

        <div class="stat-grid">
            <div class="stat-item">
                <span>SCORE</span>
                <b><?php echo $score; ?></b>
            </div>
            <div class="stat-item">
                <span>TOTAL QS</span>
                <b><?php echo $total_questions; ?></b>
            </div>
        </div>

        <a href="home.php" class="action-btn btn-home">Back to Dashboard</a>
        <a href="quizzes.php" class="action-btn btn-retry">Try Another Subject</a>
    </div>

</body>
</html>