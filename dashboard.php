<?php
session_start();
include 'db.php'; // Ensure db.php is included to fetch user data

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// TENSES
$tenses = $conn->query("SELECT * FROM tenses WHERE user_id = $user_id")->fetch_assoc();

// PUNCTUATION
$punctuation = $conn->query("SELECT * FROM punctuation WHERE user_id = $user_id")->fetch_assoc();

// ACTIVE PASSIVE
$activepassive = $conn->query("SELECT * FROM activepassive WHERE user_id = $user_id")->fetch_assoc();

// Fetch user data for the profile picture
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, profile_pic FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

// Set default image if none exists
$profile_img = !empty($user_data['profile_pic']) ? "uploads/" . $user_data['profile_pic'] : "uploads/default.png";
$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Grammar Quest</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            color: var(--text-main);
        }

        /* Modern Glass Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar h2 {
            margin: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.6rem;
            font-weight: 800;
        }

        .logout-btn {
            background: #F1F5F9;
            color: #64748B;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }

        .logout-btn:hover {
            background: #FF5757;
            color: white;
            border-color: #FF5757;
        }

        /* Layout Container */
        .dashboard-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 300px; /* Sidebar for Info */
            gap: 30px;
        }

        @media (max-width: 992px) {
            .dashboard-wrapper { grid-template-columns: 1fr; }
        }

        /* Welcome Header */
        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 30px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .welcome-msg h1 {
            margin: 0;
            font-size: 2.2rem;
            color: var(--text-main);
        }

        .welcome-msg span {
            color: var(--primary);
        }

        /* Course Card Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .module-card {
            background: white;
            padding: 40px 20px;
            border-radius: 35px;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 8px;
        }

        .tenses::before { background: var(--primary); }
        .punctuation::before { background: var(--secondary); }
        .voice::before { background: var(--accent); }

        .module-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border-color: var(--primary);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background: #F8FAFC;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .module-card:hover .icon-circle {
            background: var(--primary);
            color: white;
            transform: rotate(-10deg);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
        }

        .card-label {
            font-size: 0.85rem;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94A3B8;
            font-weight: bold;
        }

        /* Sidebar Info Box */
        .info-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
        }

        .info-card h3 {
            margin-top: 0;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
        }

        .stat-bar {
            background: #F1F5F9;
            height: 10px;
            border-radius: 5px;
            margin-top: 10px;
            overflow: hidden;
        }

        .stat-fill {
            background: var(--primary);
            height: 100%;
            width: 45%; /* Dummy progress */
        }

        /* Floating Animation */
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e2e8f0;
        }

        .navbar h2 {
            margin: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.6rem;
            font-weight: 800;
        }

        /* Profile & Dropdown Styling */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-profile-nav {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .gear-btn {
            background: #F1F5F9;
            border: none;
            color: #64748B;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gear-btn:hover {
            background: var(--primary);
            color: white;
            transform: rotate(45deg);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background-color: white;
            min-width: 180px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            z-index: 1;
        }

        .dropdown-content a {
            color: var(--text-main);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .dropdown-content a i {
            margin-right: 10px;
            width: 20px;
        }

        .dropdown-content a:hover {
            background-color: #F8FAFC;
            color: var(--primary);
        }

        .dropdown-content a.logout-item:hover {
            color: #EF4444;
            background-color: #FEF2F2;
        }

        .show { display: block; }

        /* Rest of your existing styles (Grid, Cards, Welcome Section, etc.) go here... */
        .dashboard-wrapper { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 300px; gap: 30px; }
        .welcome-section { background: white; padding: 30px; border-radius: 30px; margin-bottom: 30px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
        .module-card { background: white; padding: 40px 20px; border-radius: 35px; text-decoration: none; color: var(--text-main); transition: 0.3s; display: flex; flex-direction: column; align-items: center; border: 1px solid #e2e8f0; position: relative; overflow: hidden; }
        .module-card:hover { transform: translateY(-5px); border-color: var(--primary); }
        .icon-circle { width: 80px; height: 80px; background: #F8FAFC; border-radius: 25px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 20px; }
        .info-card { background: white; padding: 25px; border-radius: 30px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .stat-bar { background: #F1F5F9; height: 10px; border-radius: 5px; margin-top: 10px; overflow: hidden; }
        .stat-fill { background: var(--primary); height: 100%; width: 45%; }

/* Larger Progress Level Boxes */
.badge-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap; /* Allows boxes to wrap if they are too big */
}

.level-badge {
    flex: 1;
    min-width: 50px; /* Ensures they don't get too skinny */
    height: 55px;    /* Increased from 35px */
    background: #F1F5F9;
    border-radius: 15px; /* Rounder corners for larger boxes */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px solid #e2e8f0; /* Thicker border */
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Add a subtle hover effect even for the badges */
.level-badge:hover {
    transform: translateY(-3px);
}

.level-badge .lv-num {
    font-size: 0.7rem; /* Slightly larger */
    font-weight: 800;
    color: #94A3B8;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.level-badge .lv-score {
    font-size: 1.1rem; /* Much larger score */
    font-weight: 900;
    color: #64748B;
}

/* Enhanced Active States */
.badge-tenses.completed { 
    background: #EEF2FF; 
    border-color: var(--primary); 
    box-shadow: 0 4px 10px rgba(99, 102, 241, 0.1);
}
.badge-tenses.completed .lv-score { color: var(--primary); }

.badge-punct.completed { 
    background: #ECFEFF; 
    border-color: var(--secondary);
    box-shadow: 0 4px 10px rgba(6, 182, 212, 0.1);
}
.badge-punct.completed .lv-score { color: var(--secondary); }

.badge-voice.completed { 
    background: #FFFBEB; 
    border-color: var(--accent);
    box-shadow: 0 4px 10px rgba(250, 204, 21, 0.1);
}
.badge-voice.completed .lv-score { color: #B45309; }

}
    </style>
    <style>
    /* --- Footer Styling --- */
    .dashboard-footer {
        background-color: white;
        border-top: 1px solid #e2e8f0;
        margin-top: 60px;
        padding: 50px 20px 20px;
        color: var(--text-main);
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 40px;
    }

    .footer-section h4 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 1.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .footer-section p {
        color: #64748B;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        text-decoration: none;
        color: #64748B;
        font-weight: 600;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .footer-links a:hover {
        color: var(--primary);
    }

    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icons a {
        width: 35px;
        height: 35px;
        background: #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: var(--primary);
        transition: all 0.2s;
    }

    .social-icons a:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-3px);
    }

    .footer-bottom {
        max-width: 1200px;
        margin: 40px auto 0;
        padding-top: 20px;
        border-top: 1px solid #F1F5F9;
        text-align: center;
        font-size: 0.85rem;
        color: #94A3B8;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .social-icons {
            justify-content: center;
        }
    }
</style>


</head>
<body>

<div class="navbar">
    <h2><i class="fa-solid fa-graduation-cap"></i> FixIt Grammar</h2>
    
    <div class="nav-right">
        <img src="<?php echo $profile_img; ?>" alt="Profile" class="user-profile-nav">
        <div class="dropdown">
            <button onclick="toggleDropdown()" class="gear-btn">
                <i class="fa-solid fa-gear"></i>
            </button>
            <div id="myDropdown" class="dropdown-content">
                <a href="profile.php"><i class="fa-solid fa-user-gear"></i> Edit Profile</a>
                <a href="#"><i class="fa-solid fa-award"></i> My Badges</a>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 0;">
                <a href="logout.php" class="logout-item"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <main>
        <div class="welcome-section">
            <div class="welcome-msg">
                <h1>Hi, <span><?php echo $_SESSION['name']; ?></span>!</h1>
                <p>Choose a quest and start leveling up your English skills.</p>
            </div>
            <div class="floating">
                <i class="fa-solid fa-rocket" style="font-size: 3.5rem; color: var(--accent);"></i>
            </div>
        </div>

        <div class="grid">
            <a href="tenseslesson.php" class="module-card tenses">
                <div class="icon-circle">⏳</div>
                <p class="card-title">Verb Tenses</p>
                <span class="card-label">Master Time</span>
            </a>

            <a href="punctuationlesson.php" class="module-card punctuation">
                <div class="icon-circle">✍️</div>
                <p class="card-title">Punctuation</p>
                <span class="card-label">The Right Stops</span>
            </a>

            <a href="pasiveactivelesson.php" class="module-card voice">
                <div class="icon-circle">📢</div>
                <p class="card-title">Active & Passive</p>
                <span class="card-label">Voice Power</span>
            </a>
        </div>
            <div class="info-card" style="margin-top: 30px;">
            <h3><i class="fa-solid fa-medal"></i> Level Mastery</h3>

            <div class="level-progress-container">
                <div class="level-label-group">
                    <span>Tenses Adventure</span>
                </div>
                <div class="badge-row">
                    <?php for($i=1; $i<=5; $i++): 
                        $score = $tenses["lvl$i"] ?? 0;
                        $isCompleted = $score > 0 ? 'completed' : '';
                    ?>
                        <div class="level-badge badge-tenses <?= $isCompleted ?>">
                            <span class="lv-num">L<?= $i ?></span>
                            <span class="lv-score"><?= $score ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="level-progress-container">
                <div class="level-label-group">
                    <span>Punctuation Quest</span>
                </div>
                <div class="badge-row">
                    <?php for($i=1; $i<=3; $i++): 
                        $score = $punctuation["lvl$i"] ?? 0;
                        $isCompleted = $score > 0 ? 'completed' : '';
                    ?>
                        <div class="level-badge badge-punct <?= $isCompleted ?>">
                            <span class="lv-num">L<?= $i ?></span>
                            <span class="lv-score"><?= $score ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="level-progress-container">
                <div class="level-label-group">
                    <span>Voice Power</span>
                </div>
                <div class="badge-row">
                    <?php for($i=1; $i<=5; $i++): 
                        $score = $activepassive["lvl$i"] ?? 0;
                        $isCompleted = $score > 0 ? 'completed' : '';
                    ?>
                        <div class="level-badge badge-voice <?= $isCompleted ?>">
                            <span class="lv-num">L<?= $i ?></span>
                            <span class="lv-score"><?= $score ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </main>

    <aside class="info-sidebar">
        <div class="info-card">
            <h3><i class="fa-solid fa-bolt"></i> Quick Tip</h3>
            <p style="font-size: 0.9rem; color: #64748B; line-height: 1.5;">
                Did you know? Using <strong>Active Voice</strong> makes your writing stronger and easier to read!
            </p>
        </div>

        <div class="info-card" style="background: var(--primary); color: white; border: none;">
            <h3 style="color: white;"><i class="fa-solid fa-trophy"></i> Challenges</h3>
            <p style="font-size: 0.9rem; opacity: 0.9;">Take a test after every lesson to unlock the <strong>Grammar Wizard</strong> badge!</p>
        </div>
    </aside>
</div>
<script>
    /* Toggle between hiding and showing the dropdown content */
    function toggleDropdown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.gear-btn') && !event.target.matches('.fa-gear')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
<footer class="dashboard-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4>About FixIt Grammar</h4>
            <p>
                FixIt Grammar is an interactive learning platform designed to make English grammar fun and engaging. 
                Our mission is to help students master tenses, punctuation, and active/passive voice through 
                gamified lessons and real-time challenges. Level up your writing today!
            </p>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-section">
            <h4>Quick Quests</h4>
            <ul class="footer-links">
                <li><a href="tenseslesson.php"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i> Verb Tenses</a></li>
                <li><a href="punctuationlesson.php"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i> Punctuation</a></li>
                <li><a href="pasiveactivelesson.php"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i> Active & Passive</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Support</h4>
            <ul class="footer-links">
                <li><a href="#">FAQ Guide</a></li>
                <li><a href="#">Contact Mentor</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Adventure</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        &copy; 2026 FixIt Grammar. Designed for Future Grammarians. ✨
    </div>
</footer>
</body>
</html>