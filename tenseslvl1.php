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

/* =========================
   SUBMIT LOGIC
========================= */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $score = 0;

    foreach ($_POST['answers'] as $qid => $answer) {

        $stmt = $conn->prepare("SELECT correct_answer FROM tenseslvl1 WHERE id = ?");
        $stmt->bind_param("i", $qid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $answer == $row['correct_answer']) {
            $score++;
        }
    }

    // Get current score
$stmt = $conn->prepare("SELECT lvl1 FROM Tenses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$current_score = $row['lvl1'] ?? 0;

if ($score > $current_score) {
    $update = $conn->prepare("UPDATE Tenses SET lvl1 = ? WHERE user_id = ?");
    $update->bind_param("ii", $score, $user_id);
    $update->execute();
}

$stmt = $conn->prepare("SELECT lvl1 FROM Tenses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$best_score = $row['lvl1'] ?? 0;

$unlocked_level2 = ($best_score >= 10);
echo "<h2>Your Score: $score / 15</h2>";

// check permanent unlock (NOT current score)
if ($best_score >= 10) {

    echo "<p style='color:green; font-weight:bold;'>Level 2 Unlocked ✔</p>";

    echo "<a href='tenseslvl2.php'>
            <button style='padding:10px 20px; background:#4CAF50; color:white; border:none;'>
                Go to Level 2
            </button>
          </a>";

} else {

    echo "<p style='color:red; font-weight:bold;'>Need 10+ to unlock Level 2</p>";

    echo "<a href='tenseslvl1.php'>
            <button style='padding:10px 20px; background:#f44336; color:white; border:none;'>
                Retry Level 1
            </button>
          </a>";
}

echo "<br><br><a href='tenses.php'>Back</a>";
exit(); 
}

/* =========================
   FETCH RANDOM QUESTIONS
========================= */
$questions = [];

$result = $conn->query("SELECT * FROM tenseslvl1 ORDER BY RAND() LIMIT 15");

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tenses Level 1</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: auto; }
        .question { margin-bottom: 20px; }
        .btn {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Tenses Level 1 Quiz</h2>

<form method="POST">

<?php foreach ($questions as $index => $q): ?>
    <div class="question">
        <p><b><?php echo ($index + 1) . ". " . htmlspecialchars($q['question']); ?></b></p>

        <label><input type="radio" name="answers[<?php echo $q['id']; ?>]" value="a" required>
            <?php echo htmlspecialchars($q['option_a']); ?></label><br>

        <label><input type="radio" name="answers[<?php echo $q['id']; ?>]" value="b">
            <?php echo htmlspecialchars($q['option_b']); ?></label><br>

        <label><input type="radio" name="answers[<?php echo $q['id']; ?>]" value="c">
            <?php echo htmlspecialchars($q['option_c']); ?></label><br>

        <label><input type="radio" name="answers[<?php echo $q['id']; ?>]" value="d">
            <?php echo htmlspecialchars($q['option_d']); ?></label><br>
    </div>
<?php endforeach; ?>

<button type="submit" class="btn">Submit</button>

</form>

</body>
</html>