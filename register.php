<?php 
include "db.php"; 
$showModal = false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join the Adventure!</title>
    <style>
    :root {
        /* Matching the Landing Page / Login Theme */
        --bg-color: #F8FAFC;         /* Light Slate */
        --primary-gradient: linear-gradient(135deg, #6366F1, #06B6D4); /* Indigo to Cyan */
        --card-bg: #FFFFFF;
        --primary-color: #6366F1;    /* Royal Indigo */
        --secondary-color: #06B6D4;  /* Cyan */
        --accent-color: #FACC15;     /* Yellow (Hero CTA color) */
        --text-main: #1E293B;        /* Dark Slate */
    }

    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--bg-color);
        /* Subtle pattern from the Login page */
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 20px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .reg-container {
        background-color: var(--card-bg);
        padding: 40px;
        border-radius: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 480px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }

    h2 {
        /* Gradient Text Effect */
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 2.2rem;
        margin-bottom: 10px;
        font-weight: 800;
    }

    p.subtitle {
        color: #64748B;
        margin-bottom: 30px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .input-row {
        display: flex;
        gap: 15px;
        margin-bottom: 5px;
    }

    .input-group {
        margin-bottom: 20px;
        text-align: left;
        flex: 1;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-main);
        margin-left: 15px;
        font-size: 0.85rem;
    }

    input {
        width: 100%;
        padding: 14px 20px;
        border-radius: 20px;
        border: 2px solid #F1F5F9;
        background-color: #F8FAFC;
        box-sizing: border-box;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.3s ease;
        color: var(--text-main);
    }

    input:focus {
        border-color: var(--primary-color);
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    button {
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 20px;
        /* High contrast yellow from landing page */
        background-color: var(--accent-color);
        color: #854D0E;
        font-size: 1.1rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        box-shadow: 0 6px 0px #CA8A04;
        transition: all 0.2s;
        margin-top: 15px;
    }

    button:hover {
        background-color: #fde047;
        transform: translateY(-2px);
        box-shadow: 0 8px 0px #CA8A04;
    }

    button:active {
        box-shadow: 0 2px 0px #CA8A04;
        transform: translateY(4px);
    }

    .footer-link {
        margin-top: 25px;
        display: block;
        color: #64748B;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .footer-link span {
        color: var(--primary-color);
        transition: 0.2s;
    }

    .footer-link:hover span {
        text-decoration: underline;
    }

    .error-msg {
        color: #EF4444; /* Standard modern error red */
        font-weight: 600;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    /* Mobile responsiveness for the input row */
    @media (max-width: 400px) {
        .input-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>
</head>
<body>

<div class="reg-container">
    <h2>Create Your Hero! 🦸‍♂️</h2>
    <p class="subtitle">Join us for some learning fun!</p>

    <form method="POST">
        <div class="input-row">
            <div class="input-group">
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="Super" required>
            </div>
            <div class="input-group">
                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="Star">
            </div>
        </div>

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="hello@coolkids.com" required>
        </div>

        <div class="input-group">
            <label>Choose a Password</label>
            <input type="password" name="password" placeholder="Make it strong!" required>
        </div>

        <button type="submit" name="register">Start My Journey!</button>
    </form>

    <a href="login.php" class="footer-link">Already a hero? <span>Login here!</span></a>

    <?php
    if (isset($_POST['register'])) {
        $first = $_POST['first_name'];
        $last = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name, last_name, email, password)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $first, $last, $email, $password);

        if ($stmt->execute()) {
            $new_user_id = $stmt->insert_id;

            // Setup default progress modules
            $tables = ['Tenses', 'Punctuation', 'ActivePassive'];
            foreach ($tables as $table) {
                $p_sql = "INSERT INTO $table (user_id) VALUES (?)";
                $p_stmt = $conn->prepare($p_sql);
                $p_stmt->bind_param("i", $new_user_id);
                $p_stmt->execute();
            }

            $showModal = true;
        } else {
            echo "<p class='error-msg'>Oh no! Something went wrong: " . $stmt->error . "</p>";
        }
    }
    ?>
</div>
<div id="successModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:#fff; padding:30px; border-radius:20px; text-align:center; max-width:300px;">
        <h3 style="color:#28a745;">Registration Complete 🎉</h3>
        <p>You can now login to your account.</p>
        <button onclick="goToLogin()">Go to Login</button>
    </div>
</div>

<script>
function goToLogin() {
    window.location.href = "login.php";
}

window.onload = function() {
    <?php if ($showModal): ?>
        document.getElementById('successModal').style.display = 'flex';
    <?php endif; ?>
};
</script>
</body>
</html>