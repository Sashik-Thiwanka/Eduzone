<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }

$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";

$conn = new mysqli("sql100.infinityfree.com", "if0_40714375", "LkE4u5SOS16CPC", "if0_40714375_eduzone_db");
$quiz_id = $_GET['id'] ?? 1;

// Fetch Quiz Details
$quiz_query = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$quiz_query->bind_param("i", $quiz_id);
$quiz_query->execute();
$quiz = $quiz_query->get_result()->fetch_assoc();

// Fetch Questions
$questions_query = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$questions_query->bind_param("i", $quiz_id);
$questions_query->execute();
$questions = $questions_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $quiz['title']; ?> | EduZone</title>
    <style>
        body { background: #030712; color: white; font-family: sans-serif; padding: 40px; }
        .quiz-container { max-width: 800px; margin: auto; background: #0f172a; padding: 30px; border-radius: 20px; border: 1px solid #1e293b; }
        .q-block { margin-bottom: 30px; padding: 20px; border-bottom: 1px solid #1e293b; }
        .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
        label { background: #1e293b; padding: 15px; border-radius: 10px; cursor: pointer; transition: 0.3s; display: block; }
        label:hover { background: #334155; }
        input[type="radio"] { margin-right: 10px; }
        .submit-btn { background: #4285f4; color: white; border: none; padding: 15px 30px; border-radius: 10px; cursor: pointer; font-weight: bold; width: 100%; font-size: 1.1rem; }
    </style>
</head>
<body>

<div class="quiz-container">
    <h1><?php echo $quiz['title']; ?></h1>
    <p style="color: #94a3b8;"><?php echo $quiz['subject']; ?> • <?php echo $quiz['time_limit']; ?> Minutes</p>

    <form action="submit_results.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        
        <?php $i = 1; while($q = $questions->fetch_assoc()): ?>
            <div class="q-block">
                <h3><?php echo $i++; ?>. <?php echo $q['question_text']; ?></h3>
                <div class="options-grid">
                    <label><input type="radio" name="q<?php echo $q['id']; ?>" value="A" required> <?php echo $q['option_a']; ?></label>
                    <label><input type="radio" name="q<?php echo $q['id']; ?>" value="B"> <?php echo $q['option_b']; ?></label>
                    <label><input type="radio" name="q<?php echo $q['id']; ?>" value="C"> <?php echo $q['option_c']; ?></label>
                    <label><input type="radio" name="q<?php echo $q['id']; ?>" value="D"> <?php echo $q['option_d']; ?></label>
                </div>
            </div>
        <?php endwhile; ?>

        <button type="submit" class="submit-btn">Complete Quiz</button>
    </form>
</div>

</body>
</html>