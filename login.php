<?php 
session_start();
include "db.php"; 

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
   <?php
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

           if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['first_name'];

    header("Location: dashboard.php");
    exit();
} else {
                echo "<p class='message'>Oops! Wrong password.</p>";
            }
        } else {
            echo "<p class='message'>User not found.</p>";
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Adventure Time!</title>
   <style>
    :root {
        /* Updated to Landing Page Colors */
        --bg-color: #F8FAFC;         /* Light Slate */
        --primary-gradient: linear-gradient(135deg, #6366F1, #06B6D4); /* Indigo to Cyan */
        --card-bg: #FFFFFF;
        --primary-color: #6366F1;    /* Royal Indigo */
        --secondary-color: #06B6D4;  /* Cyan */
        --accent-color: #FACC15;     /* Yellow (for the button) */
        --text-main: #1E293B;        /* Dark Slate */
    }

    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--bg-color);
        /* Subtle background pattern to look more modern */
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 20px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background-color: var(--card-bg);
        padding: 40px;
        border-radius: 40px; 
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05); 
        width: 100%;
        max-width: 380px;
        text-align: center;
        border: 1px solid #e2e8f0;
        margin: 10px;
    }

    h2 {
        /* Using the gradient text effect from the landing page */
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
        font-size: 0.95rem;
    }

    .input-group {
        margin-bottom: 20px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-main);
        margin-left: 15px;
        font-size: 0.9rem;
    }

    input[type="email"], 
    input[type="password"] {
        width: 100%;
        padding: 15px 20px;
        border-radius: 20px;
        border: 2px solid #F1F5F9;
        background-color: #F8FAFC;
        box-sizing: border-box;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s;
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
        /* Using the eye-catching Yellow CTA color from landing page */
        background-color: var(--accent-color);
        color: #854D0E;
        font-size: 1.1rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        box-shadow: 0 6px 0px #CA8A04;
        transition: all 0.2s;
        margin-top: 10px;
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

    .message {
        margin-top: 15px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #EF4444; /* Error red */
    }

    .footer-link {
        margin-top: 25px;
        display: block;
        color: #64748B;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.2s;
    }

    .footer-link span {
        color: var(--primary-color);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: 0.2s;
    }

    .footer-link:hover span {
        border-bottom-color: var(--primary-color);
    }
    /* --- Footer --- */
        footer {
            background: var(--dark);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
</style>
</head>
<body>

<div class="login-container">
    <h2>Hi there! 👋</h2>

    <form method="POST">
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Your email..." required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Top secret!" required>
        </div>
        <button type="submit" name="login">Let's Go!</button>
    </form>

 

    <!-- The new registration link -->
    <a href="register.php" class="footer-link">New here? <span>Register!</span></a>
    
</div>
   
</body>

</html>