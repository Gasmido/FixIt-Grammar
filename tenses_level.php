<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Erika");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$level = isset($_GET['lvl']) ? (int)$_GET['lvl'] : 1;

$tables = [
    1 => "tenseslvl1",
    2 => "tenseslvl2",
    3 => "tenseslvl3",
    4 => "tenseslvl4",
    5 => "tenseslvl5"
];
$allowed_cols = [
    1 => 'lvl1',
    2 => 'lvl2',
    3 => 'lvl3',
    4 => 'lvl4',
    5 => 'lvl5'
];

$col = $allowed_cols[$level] ?? 'lvl1';

$table = $tables[$level] ?? "tenseslvl1";

$show_results = false;
$score = 0;
$best_score = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $show_results = true;
    $answers = $_POST['answers'] ?? [];


    // 1. Define Column and Table Mapping
    $allowed_cols = [
        1 => 'lvl1',
        2 => 'lvl2',
        3 => 'lvl3',
        4 => 'lvl4',
        5 => 'lvl5'
    ];

    $tables = [
        1 => "tenseslvl1",
        2 => "tenseslvl2",
        3 => "tenseslvl3",
        4 => "tenseslvl4",
        5 => "tenseslvl5"
    ];

    $col = $allowed_cols[$level] ?? 'lvl1';
    $current_table = $tables[$level] ?? 'tenseslvl1';

    // 2. Calculate the Score
    if ($level == 5) {

    $q_id = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;

    $stmt = $conn->prepare("SELECT correct_answers FROM tenseslvl5 WHERE id = ?");
    $stmt->bind_param("i", $q_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $correct_answers = json_decode($row['correct_answers'], true);

    foreach ($answers as $i => $value) {

        $user = strtoupper(trim($value ?? ''));
        $correct = strtoupper(trim($correct_answers[$i] ?? ''));

        if ($user === $correct) {
            $score++;
        }
    }
} else {
        // Standard logic for Levels 1-4
        if (!empty($answers)) {
            $ids = implode(',', array_map('intval', array_keys($answers)));
            // Use the $current_table variable here to ensure we hit the right level
            $query = "SELECT id, correct_answer FROM $current_table WHERE id IN ($ids)";
            $result = $conn->query($query);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $qid = $row['id'];
                    $user_answer = strtoupper(trim($answers[$qid] ?? ''));
                    $correct_answer = strtoupper(trim($row['correct_answer']));
                    if ($user_answer === $correct_answer) { $score++; }
                }
            }
        }
    }

    $col = $allowed_cols[$level];
    $stmt = $conn->prepare("SELECT $col FROM tenses WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $current_best = $stmt->get_result()->fetch_assoc()[$col] ?? 0;

    if ($score > $current_best) {
        $update = $conn->prepare("UPDATE tenses SET $col = ? WHERE user_id = ?");
        $update->bind_param("ii", $score, $user_id);
        $update->execute();
        $best_score = $score;
    } else {
        $best_score = $current_best;
    }
}
$questions = [];

if ($level == 5) {
    // If we just submitted (show_results is true), we might want to 
    // show the SAME paragraph they just answered so they see their mistakes.
    // If not, we fetch a random one.
    $target_id = ($show_results && isset($_POST['question_id'])) ? (int)$_POST['question_id'] : null;

    if ($target_id) {
        $result = $conn->query("SELECT * FROM tenseslvl5 WHERE id = $target_id");
    } else {
        $result = $conn->query("SELECT * FROM tenseslvl5 ORDER BY RAND() LIMIT 1");
    }
    
    if ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    // Logic for Levels 1-4 stays the same
    $result = $conn->query("SELECT * FROM $table ORDER BY RAND() LIMIT 20");
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenses Quiz - Level <?php echo $level; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    :root {
        --primary: #6366F1;    /* Royal Indigo */
        --secondary: #06B6D4;  /* Cyan */
        --accent: #FACC15;     /* Yellow */
        --bg-color: #F8FAFC;   /* Slate 50 */
        --text-main: #1E293B;  /* Slate 900 */
        --card-bg: #FFFFFF;
    }

    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--bg-color);
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 30px 30px;
        margin: 0;
        padding: 40px 20px;
        color: var(--text-main);
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .header {
        text-align: center;
        background: white;
        padding: 30px;
        border-radius: 35px;
        margin-bottom: 40px;
        border: 1px solid #e2e8f0;
        border-bottom: 6px solid var(--secondary);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    h2 { 
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0; 
        font-size: 2.2rem; 
        font-weight: 800;
    }

    /* Question Cards - Hover Animation Removed */
    .question-card {
        background: white;
        padding: 35px;
        border-radius: 35px;
        margin-bottom: 30px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(0,0,0,0.03);
        /* transition removed from here */
    }

    .question-card p {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 0;
        line-height: 1.4;
        color: var(--text-main);
    }

    /* Option Items - Hover and Click added */
    .option-item {
        display: flex;
        align-items: center;
        background: #F8FAFC;
        padding: 18px 25px;
        border-radius: 20px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 600;
    }

    .option-item:hover {
        background: white;
        border-color: var(--secondary);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(6, 182, 212, 0.15);
    }

    .option-item:active {
        transform: translateY(1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    input[type="radio"] {
        margin-right: 15px;
        transform: scale(1.3);
        accent-color: var(--primary);
    }

    /* Text Inputs (Level 4 & 5) - Hover and Click added */
    input[type="text"] {
        width: 100%;
        padding: 15px 20px;
        border-radius: 15px;
        border: 2px solid #F1F5F9;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    input[type="text"]:hover {
        border-color: var(--secondary);
        background: white;
    }

    input[type="text"]:focus {
        outline: none;
        border-color: var(--primary);
        transform: scale(1.01);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* Submit Button - Enhanced Animations */
    .submit-btn {
        display: block;
        width: 100%;
        padding: 22px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        font-size: 1.4rem;
        font-weight: 800;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        margin: 50px 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .submit-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
        filter: brightness(1.05);
    }

    .submit-btn:active {
        transform: translateY(2px);
        box-shadow: 0 5px 10px rgba(99, 102, 241, 0.2);
    }
     /* Results Modal Overlay */
    .results-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.9); /* Darker Slate blur */
        backdrop-filter: blur(8px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .results-card {
        background: white;
        padding: 50px;
        border-radius: 40px;
        text-align: center;
        max-width: 450px;
        width: 90%;
        border: 1px solid #e2e8f0;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .score-circle {
        width: 130px; height: 130px;
        background: var(--bg-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0 auto 25px;
        color: var(--primary);
        border: 4px solid var(--secondary);
    }

    .status-msg { font-size: 1.7rem; font-weight: 800; margin-bottom: 25px; }
    .pass { color: #10B981; }
    .fail { color: #EF4444; }
    /* Results Navigation Buttons */
    .nav-btn {
        display: inline-block;
        padding: 14px 30px;
        text-decoration: none;
        border-radius: 20px;
        font-weight: 700;
        margin: 8px;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        transform: translateY(-2px);
    }

    .nav-btn:active {
        transform: translateY(1px);
    }

    .btn-next { background: var(--primary); color: white; }
    .btn-retry { background: #F1F5F9; color: var(--text-main); }
    .btn-back { background: transparent; color: #64748B; border: 1px solid #e2e8f0; }
/* Header Layout Fix */
.header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* Spreads back button and title */
    position: relative;
    text-align: center;
    background: white;
    padding: 20px 30px; /* Adjusted padding */
    border-radius: 35px;
    margin-bottom: 40px;
    border: 1px solid #e2e8f0;
    border-bottom: 6px solid var(--secondary);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

/* Back Button Styling */
.back-link {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F1F5F9;
    color: #64748B;
    border-radius: 15px;
    text-decoration: none;
    font-size: 1.2rem;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
}

.back-link:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-2px); /* Subtle nudge to the left */
}

/* Ensure the H2 takes up the remaining space but stays centered */
.header h2 {
    flex-grow: 1;
    margin: 0;
}
</style>
</head>
<body>

<div class="container">
  <div class="header">
    <a href="tenses.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <h2>Tenses Adventure: Level <?php echo $level; ?> 🚀</h2>

    <div style="width: 40px;"></div> </div>
<?php if ($level == 1): ?>

<form method="POST">

<?php foreach ($questions as $index => $q): ?>

<div class="question-card">

    <p>
        <?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?>
    </p>

    <div class="options-list">

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="PAST TENSE"
                   required>
            PAST TENSE
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="PRESENT TENSE">
            PRESENT TENSE
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="FUTURE TENSE">
            FUTURE TENSE
        </label>

    </div>

</div>

<?php endforeach; ?>

<button type="submit" class="submit-btn">
    Finish My Mission! ✨
</button>

</form>

<?php endif; ?>


<?php if ($level == 2): ?>

<form method="POST">

<?php foreach ($questions as $index => $q): ?>

<div class="question-card">

    <p>
        <?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?>
    </p>

    <div class="options-list">

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="a"
                   required>A. 
            <?php echo htmlspecialchars($q['option_a']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="b">B. 
            <?php echo htmlspecialchars($q['option_b']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="c">C. 
            <?php echo htmlspecialchars($q['option_c']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="d">D. 
            <?php echo htmlspecialchars($q['option_d']); ?>
        </label>

    </div>

</div>

<?php endforeach; ?>

<button type="submit" class="submit-btn">
    Finish My Mission! ✨
</button>

</form>

<?php endif; ?>
    

<?php if ($level == 3): ?>

<form method="POST">

<?php foreach ($questions as $index => $q): ?>

<div class="question-card">

    <p>
        <?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?>
    </p>

    <div class="options-list">

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="a"
                   required>A. 
            <?php echo htmlspecialchars($q['option_a']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="b">B. 
            <?php echo htmlspecialchars($q['option_b']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="c">C. 
            <?php echo htmlspecialchars($q['option_c']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="d">D. 
            <?php echo htmlspecialchars($q['option_d']); ?>
        </label>

    </div>

</div>

<?php endforeach; ?>

<button type="submit" class="submit-btn">
    Finish My Mission! ✨
</button>

</form>

<?php endif; ?>    

<?php if ($level == 4): ?>

<form method="POST">

<?php foreach ($questions as $index => $q): ?>

<div class="question-card">

    <p>
        <?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?>
    </p>

    <input type="text"
           name="answers[<?php echo $q['id']; ?>]"
           placeholder="Type your answer..."
           required
           style="padding:10px; width:100%; border-radius:10px; border:2px solid #ccc;">

</div>

<?php endforeach; ?>

<button type="submit" class="submit-btn">
    Finish My Mission! ✨
</button>

</form>

<?php endif; ?>

<?php if ($level == 5): ?>

<form method="POST">
<input type="hidden" name="question_id" value="<?php echo $questions[0]['id']; ?>">
<div class="question-card">

    <p style="font-weight:bold;">
        Fill in the blanks:
    </p>

    <p style="line-height:2.2; font-size:16px;">

        <?php
        // split paragraph by blanks
        $parts = preg_split('/__________/', $questions[0]['paragraph']);
        $answersCount = count(json_decode($questions[0]['correct_answers'], true));
        ?>

        <?php for ($i = 0; $i < $answersCount; $i++): ?>

            <?php echo htmlspecialchars($parts[$i]); ?>

            <input type="text"
                   name="answers[<?php echo $i; ?>]"
                   style="width:140px;"
                   required>

        <?php endfor; ?>

        <?php echo htmlspecialchars($parts[$answersCount] ?? ''); ?>

    </p>

</div>

<button type="submit" class="submit-btn">
    Finish My Mission! ✨
</button>

</form>

<?php endif; ?>
</div>

<?php 
// 1. Fetch the best score from the database for the CURRENT level
$stmt = $conn->prepare("SELECT $col FROM tenses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_progress = $stmt->get_result()->fetch_assoc();
$stored_score = $user_progress[$col] ?? 0;

if ($show_results): 
    // Calculate total items
    $total_items = ($level >= 1 && $level <= 4) ? 20 : 0;
    if ($level == 5) {
        $q_id = $_POST['question_id'] ?? null;
        if ($q_id) {
            $stmt = $conn->prepare("SELECT correct_answers FROM tenseslvl5 WHERE id = ?");
            $stmt->bind_param("i", $q_id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $correct_answers = json_decode($row['correct_answers'] ?? '[]', true);
            $total_items = is_array($correct_answers) ? count($correct_answers) : 0;
        }
    }

    // Pass logic flags
    $passed_this_attempt = ($score >= 15);
    $has_passed_before = ($stored_score >= 15);
?>

<div class="results-overlay">
    <div class="results-card">
        <div class="score-circle">
            <?php echo $score . '/' . $total_items; ?>
        </div>

        <?php if ($passed_this_attempt): ?>
            <div class="status-msg pass">Amazing Job! 🌟</div>

            <p>You've mastered this quest!</p>
            <div style="margin-top: 15px;">
                <a href="activepassive_level.php?lvl=<?php echo $level; ?>" class="nav-btn btn-retry">Retry 🔄</a>
            </div>
            
            <?php if ($level < 5): ?>
                <a href="tenses_level.php?lvl=<?php echo $level + 1; ?>" class="nav-btn btn-next">Next Level ➡️</a>
            <?php else: ?>
                <p class="status-msg pass">You are a Tense Master! 🎉</p>
            <?php endif; ?>

        <?php elseif ($has_passed_before): ?>
            <div class="status-msg pass">Level Already Cleared! 🏆</div>
            <p>You already unlocked the next part of the map.</p>
            
            <?php if ($level < 5): ?>
                <a href="tenses_level.php?lvl=<?php echo $level + 1; ?>" class="nav-btn btn-next">Go to Next Level ➡️</a>
            <?php endif; ?>

        <?php else: ?>
            <div class="status-msg fail">Keep Trying! 💪</div>
            
            <?php if ($level == 5): ?>
                <p>You need <strong>15 points</strong> to complete your final quest and become a Master!</p>
            <?php else: ?>
                <p>You need <strong>15 points</strong> to unlock the next level.</p>
            <?php endif; ?>
        <?php endif; ?>

        <div style="margin-top: 25px;">
            <a href="tenses_level.php?lvl=<?php echo $level; ?>" class="nav-btn btn-retry">
                <?php echo ($passed_this_attempt || $has_passed_before) ? 'Retry for High Score 🔄' : 'Retry Level 🔄'; ?>
            </a>
            
            <br><br>
            <a href="tenses.php" class="nav-btn btn-back">Back to Map</a>
        </div>
    </div>
</div>
<?php endif; ?>
</body>
</html>