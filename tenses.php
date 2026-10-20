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

$stmt = $conn->prepare("SELECT * FROM Tenses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tenses = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenses Adventure | Grammar Quest</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366F1;    /* Royal Indigo */
            --secondary: #06B6D4;  /* Cyan */
            --accent: #FACC15;     /* Yellow */
            --bg-color: #F8FAFC;   /* Slate 50 */
            --text-main: #1E293B;  /* Slate 900 */
            --white: #FFFFFF;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 30px 30px;
            margin: 0;
            color: var(--text-main);
        }

        /* Modern Navbar Header */
        .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 25px 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        h2 {
            margin: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            font-weight: 800;
        }

        .map-container {
            max-width: 600px;
            margin: 60px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 0 20px;
        }

        /* Level Node Styling */
        .level-node {
            width: 130px;
            height: 130px;
            border-radius: 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: white;
            font-weight: 800;
            font-size: 1.1rem;
            border: 4px solid #fff;
            position: relative;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        /* Snake Pathing: Alternating sides */
        /* Snake Pathing */
.level-left {
    align-self: flex-start;
}

.level-right {
    align-self: flex-end;
}

.level-center {
    align-self: center;
}

        .unlocked {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .unlocked:hover {
            transform: scale(1.1) rotate(3deg);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3);
        }

        .locked {
            background: #CBD5E1;
            cursor: not-allowed;
            opacity: 0.7;
            box-shadow: none;
        }

        /* Score Badge */
        .score-badge {
            position: absolute;
            top: -15px;
            background: var(--accent);
            color: #854D0E;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .lock-icon {
            font-size: 2rem;
            margin-top: 5px;
            opacity: 0.5;
        }

        .path-line {
            width: 6px;
            height: 40px;
            background: #E2E8F0;
            border-radius: 10px;
        }

        .back-link {
            text-decoration: none;
            color: var(--primary);
            font-weight: bold;
            background: white;
            padding: 12px 30px;
            border-radius: 20px;
            margin-top: 40px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            display: inline-block;
        }

        .back-link:hover {
            background: var(--primary);
            color: white;
        }

        /* --- Footer Styles --- */
        .dashboard-footer {
            background-color: white;
            border-top: 1px solid #e2e8f0;
            margin-top: 80px;
            padding: 50px 20px 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .footer-content {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 40px;
        }

        .footer-section h4 {
            margin: 0 0 20px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { text-decoration: none; color: #64748B; font-size: 0.9rem; font-weight: 600; }
        .footer-links a:hover { color: var(--primary); }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 40px;
            border-top: 1px solid #F1F5F9;
            font-size: 0.85rem;
            color: #94A3B8;
        }
                /* Ensure the header can contain the absolute button */
        .header {
            position: relative; 
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 25px 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* Home Button Positioning and Styling */
        .home-link {
            position: absolute;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 45px;
            height: 45px;
            background: var(--white);
            color: var(--primary);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.2rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .home-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-55%) scale(1.05);
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.2);
        }

        .home-link:active {
            transform: translateY(-45%) scale(0.95);
        }

        /* Mobile tweak to prevent overlapping */
        @media (max-width: 600px) {
            .home-link {
                left: 15px;
                width: 35px;
                height: 35px;
            }
            h2 { font-size: 1.4rem; }
        }
        @media (max-width: 768px) {
            .footer-content { grid-template-columns: 1fr; text-align: center; }
            .level-node { align-self: center !important; }
        }
    </style>
</head>
<body>

<div class="header">
     <a href="dashboard.php" class="home-link">
        <i class="fa-solid fa-house"></i>
    </a>
    <h2>Tenses Adventure! ⏳</h2>
</div>

<div class="map-container">
    
    <a href="tenses_level.php?lvl=1" class="level-node unlocked">
        <div class="score-badge">Pts: <?php echo $tenses['lvl1']; ?></div>
        <i class="fa-solid fa-flag-checkered" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
        Level 1
    </a>

    <div class="path-line"></div>

    <?php if ($tenses['lvl1'] >= 15): ?>
        <a href="tenses_level.php?lvl=2" class="level-node unlocked">
            <div class="score-badge">Pts: <?php echo $tenses['lvl2']; ?></div>
            <i class="fa-solid fa-flag-checkered" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
            Level 2
        </a>
    <?php else: ?>
        <div class="level-node locked">
            Level 2
            <div class="lock-icon">🔒</div>
        </div>
    <?php endif; ?>

    <div class="path-line"></div>

    <?php if ($tenses['lvl2'] >= 15): ?>
        <a href="tenses_level.php?lvl=3" class="level-node unlocked">
            <div class="score-badge">Pts: <?php echo $tenses['lvl3']; ?></div>
            <i class="fa-solid fa-flag-checkered" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
            Level 3
        </a>
    <?php else: ?>
        <div class="level-node locked">
            Level 3
            <div class="lock-icon">🔒</div>
        </div>
    <?php endif; ?>

    <div class="path-line"></div>

    <?php if ($tenses['lvl3'] >= 15): ?>
        <a href="tenses_level.php?lvl=4" class="level-node unlocked">
            <div class="score-badge">Pts: <?php echo $tenses['lvl4']; ?></div>
            <i class="fa-solid fa-flag-checkered" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
            Level 4
        </a>
    <?php else: ?>
        <div class="level-node locked">
            Level 4
            <div class="lock-icon">🔒</div>
        </div>
    <?php endif; ?>

    <div class="path-line"></div>

    <?php if ($tenses['lvl4'] >= 15): ?>
        <a href="tenses_level.php?lvl=5" class="level-node unlocked" style="background: var(--accent); color: #854D0E;">
            <div class="score-badge" style="background: var(--primary); color: white;">FINAL</div>
            <i class="fa-solid fa-crown" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
            Level 5
        </a>
    <?php else: ?>
        <div class="level-node locked">
            Level 5
            <div class="lock-icon">🔒</div>
        </div>
    <?php endif; ?>

    <a href="dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
</div>

<footer class="dashboard-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4>FixIt Grammar</h4>
            <p style="color: #64748B; font-size: 0.9rem;">Level up your skills through our adventure-based learning modules. Master English one step at a time!</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul class="footer-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="tenses_lesson.php">Lessons</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Help</h4>
            <ul class="footer-links">
                <li><a href="#">Support</a></li>
                <li><a href="#">Privacy</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2026 FixIt Grammar. ✨
    </div>
</footer>

</body>
</html>