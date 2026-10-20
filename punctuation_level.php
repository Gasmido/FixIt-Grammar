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
    1 => "punctuationlvl1",
    2 => "punctuationlvl2",
    3 => "punctuationlvl3",
    4 => "punctuationlvl4",
    5 => "punctuationlvl5"
];

$table = $tables[$level] ?? "punctuationlvl1";

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
        3 => 'lvl3'
    ];

    $tables = [
    1 => "punctuationlvl1",
    2 => "punctuationlvl2",
    3 => "punctuationlvl3",
    4 => "punctuationlvl4",
    5 => "punctuationlvl5"
];

    $col = $allowed_cols[$level] ?? 'lvl1';
    $current_table = $tables[$level] ?? 'punctuationlvl1';

    // 2. Calculate the Score
    if ($level == 3 && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $show_results = true;

    $q_id = (int)$_POST['question_id'];

    $stmt = $conn->prepare("
        SELECT conversation, correct_answer
        FROM punctuationlvl3
        WHERE id = ?
    ");

    $stmt->bind_param("i", $q_id);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();

    $correct_lines = explode("\n", trim($row['correct_answer']));
    $original_lines = explode("\n", trim($row['conversation']));
    $user_lines = $_POST['answers'] ?? [];

    $score = 0;
    $total_items = 0;

    foreach ($correct_lines as $i => $correct_line) {

        $original_line = $original_lines[$i] ?? '';
        $user_line = $user_lines[$i] ?? '';

        // --- extract differences between ORIGINAL and CORRECT ---
        preg_match_all('/[A-Z]/', $correct_line, $correct_caps);
        preg_match_all('/[A-Z]/', $original_line, $original_caps);

        preg_match_all('/[.,!?":;]/', $correct_line, $correct_punc);
        preg_match_all('/[.,!?":;]/', $original_line, $original_punc);

        $cap_diff = abs(count($correct_caps[0]) - count($original_caps[0]));
        $punc_diff = abs(count($correct_punc[0]) - count($original_punc[0]));

        $line_max = $cap_diff + $punc_diff;

        $total_items += $line_max;

        // --- user evaluation ---
        preg_match_all('/[A-Z]/', $user_line, $user_caps);
        preg_match_all('/[.,!?":;]/', $user_line, $user_punc);

        $user_cap_score = min(count($user_caps[0]), $cap_diff);
        $user_punc_score = min(count($user_punc[0]), $punc_diff);

        $score += ($user_cap_score + $user_punc_score);
    }

    // save best score
    $stmt = $conn->prepare("
        SELECT lvl3
        FROM Punctuation
        WHERE user_id = ?
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $current_best = $stmt->get_result()->fetch_assoc()['lvl3'] ?? 0;

    if ($score > $current_best) {

        $update = $conn->prepare("
            UPDATE Punctuation
            SET lvl3 = ?
            WHERE user_id = ?
        ");

        $update->bind_param("ii", $score, $user_id);
        $update->execute();
    }

    $best_score = max($score, $current_best);
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
    $stmt = $conn->prepare("SELECT $col FROM punctuation WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $current_best = $stmt->get_result()->fetch_assoc()[$col] ?? 0;

    if ($score > $current_best) {
        $update = $conn->prepare("UPDATE punctuation SET $col = ? WHERE user_id = ?");
        $update->bind_param("ii", $score, $user_id);
        $update->execute();
        $best_score = $score;
    } else {
        $best_score = $current_best;
    }
}
$questions = [];

if ($level == 3) {
    // If results are being shown, we need to fetch the specific question the user just answered
    $target_id = ($show_results && isset($_POST['question_id'])) ? (int)$_POST['question_id'] : null;

    if ($target_id) {
        $stmt = $conn->prepare("SELECT * FROM punctuationlvl3 WHERE id = ?");
        $stmt->bind_param("i", $target_id);
        $stmt->execute();
        $question = $stmt->get_result()->fetch_assoc();
    } else {
        $result = $conn->query("SELECT * FROM punctuationlvl3 ORDER BY RAND() LIMIT 1");
        $question = $result->fetch_assoc();
    }

    // This creates the array of "wrong" lines to display
    $lines = explode("\n", trim($question['conversation']));
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
    <title>Punctuation Quiz - Level <?php echo $level; ?></title>
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
        <a href="punctuation.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
        <h2>Punctuation Adventure: Level <?php echo $level; ?> 🚀</h2>
    </div>
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
                   value="a"
                   required>
            A. <?php echo htmlspecialchars($q['option_a']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="b">
            B. <?php echo htmlspecialchars($q['option_b']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="c">
            C. <?php echo htmlspecialchars($q['option_c']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="d">
            D. <?php echo htmlspecialchars($q['option_d']); ?>
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
        <?php echo ($index + 1) . ". Choose the correct sentence."; ?>
    </p>

    <div class="options-list">

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="a"
                   required>
            A. <?php echo htmlspecialchars($q['option_a']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="b">
            B. <?php echo htmlspecialchars($q['option_b']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="c">
            C. <?php echo htmlspecialchars($q['option_c']); ?>
        </label>

        <label class="option-item">
            <input type="radio"
                   name="answers[<?php echo $q['id']; ?>]"
                   value="d">
            D. <?php echo htmlspecialchars($q['option_d']); ?>
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
<form method="POST" action="?lvl=3">
    <!-- Hidden ID so the server knows which question to grade against -->
    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">

    <div class="question-card">
        <p style="font-weight:bold; color: #555; margin-bottom: 20px;">
            <i class="fas fa-edit"></i> Directions: Look at the dialogues below. Rewrite them in the text boxes with the correct capitalization and punctuation.
        </p>

        <?php foreach ($lines as $index => $line): ?>
            <div class="dialogue-group" style="margin-bottom:25px; padding: 15px; background: #f9f9f9; border-left: 5px solid #007bff; border-radius: 5px;">
                
                <!-- 1. DISPLAY THE WRONG DIALOGUE -->
                <label style="display:block; font-weight:bold; color: #d9534f; margin-bottom: 5px;">
                    Incorrect Dialogue <?php echo $index + 1; ?>:
                </label>
                <p style="font-style: italic; font-size: 1.1rem; margin-bottom: 10px; color: #333;">
                    "<?php echo htmlspecialchars(trim($line)); ?>"
                </p>

                <!-- 2. INPUT FOR THE USER'S FIX -->
                <label style="display:block; font-weight:bold; color: #28a745; margin-bottom: 5px;">
                    Your Correction:
                </label>
                <input 
                    type="text" 
                    name="answers[<?php echo $index; ?>]" 
                    placeholder="Type the corrected sentence here..."
                    required
                    style="
                        width:100%; 
                        padding:12px; 
                        border-radius:8px; 
                        border:2px solid #28a745; 
                        font-size:16px;
                        outline: none;
                    "
                >
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="submit-btn">
        Submit My Corrections! ✨
    </button>
</form>
<?php endif; ?> 

</div>

<?php if ($show_results): ?>
    <?php
    // Fetch best score
    $stmt = $conn->prepare("SELECT $col FROM punctuation WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_progress = $stmt->get_result()->fetch_assoc();
    $stored_score = $user_progress[$col] ?? 0;

    // Determine total items (Updated to include level 3)
    if ($level >= 1 && $level <= 2) {

        $total_items = 20;

    }

    // Logic for passing
    $passing_score = ($level >= 3) ? floor($total_items * 0.75) : 15;
    $passed_now = ($score >= $passing_score);
    $has_passed_before = ($stored_score >= $passing_score);
    ?>

    <div class="results-overlay">
        <div class="results-card">
            <div class="score-circle"><?php echo $score . '/' . $total_items; ?></div>
            
            <?php if ($passed_now): ?>
                <div class="status-msg pass">Amazing Job! 🌟</div>
                <p>You've mastered this punctuation quest!</p>
                 <div style="margin-top: 15px;">
                    <a href="punctuation_level.php?lvl=<?php echo $level; ?>" class="nav-btn btn-retry">Retry 🔄</a>
                </div>
                <?php if ($level < 3): ?>
                    <a href="punctuation_level.php?lvl=<?php echo $level + 1; ?>" class="nav-btn btn-next">Next Level ➡️</a>
                <?php else: ?>
                    <p class="status-msg pass">You are a Punctuation Master! 🎉</p>
                <?php endif; ?>

            <?php elseif ($has_passed_before): ?>
                <div class="status-msg pass">Level Already Cleared! 🏆</div>
                <p>You’ve already completed this stage.</p>
                
                <?php if ($level < 3): ?>
                    <a href="punctuation_level.php?lvl=<?php echo $level + 1; ?>" class="nav-btn btn-next">Go to Next Level ➡️</a>
                <?php endif; ?>
                
                <div style="margin-top: 15px;">
                    <a href="punctuation_level.php?lvl=<?php echo $level; ?>" class="nav-btn btn-retry">Retry for High Score 🔄</a>
                </div>

            <?php else: ?>
                <div class="status-msg fail">Keep Trying! 💪</div>

                <?php if ($level == 3): ?>
                    <p>You need <strong><?php echo $passing_score; ?> points</strong> to finish your Punctuation Quest!</p>
                <?php else: ?>
                    <p>You need <strong><?php echo $passing_score; ?> points</strong> to unlock the next level.</p>
                <?php endif; ?>
                
                <a href="punctuation_level.php?lvl=<?php echo $level; ?>" class="nav-btn btn-retry">Retry Level 🔄</a>
            <?php endif; ?>
            
            <br><br>
            <a href="punctuation.php" class="nav-btn btn-back">Back to Map</a>
        </div>
    </div>
<?php endif; ?>


</body>
</html>