<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grammar Quest | Master Your English Adventure</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366F1;    /* Royal Blue/Purple */
            --secondary: #06B6D4;  /* Cyan */
            --accent: #FACC15;     /* Yellow */
            --success: #22C55E;    /* Green */
            --dark: #1E293B;
            --light: #F8FAFC;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* --- Hero Section --- */
        .hero {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 100px 20px;
            text-align: center;
            border-bottom-left-radius: 50% 20%;
            border-bottom-right-radius: 50% 20%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
        }

        .hero p {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 40px;
            opacity: 0.9;
        }

        .cta-main {
            background: var(--accent);
            color: #854D0E;
            padding: 18px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 8px 0px #CA8A04;
            transition: all 0.2s;
            display: inline-block;
        }

        .cta-main:hover {
            transform: translateY(-3px);
            box-shadow: 0 11px 0px #CA8A04;
        }

        /* --- Path Selection --- */
        .paths {
            max-width: 1100px;
            margin: -50px auto 80px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 0 20px;
        }

        .path-card {
            background: white;
            padding: 40px 30px;
            border-radius: 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .path-card:hover {
            border-color: var(--primary);
            transform: translateY(-10px);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--light);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .path-card.tenses .icon-box { color: #FF5757; background: #FFE4E6; }
        .path-card.punct .icon-box { color: #0284C7; background: #E0F2FE; }
        .path-card.voice .icon-box { color: #7C3AED; background: #F5F3FF; }

        .btn-start {
            display: block;
            margin-top: 25px;
            padding: 12px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: bold;
            background: var(--light);
            color: var(--dark);
            transition: 0.3s;
        }

        .path-card:hover .btn-start {
            background: var(--primary);
            color: white;
        }

        /* --- Stats Section --- */
        .stats {
            background: white;
            padding: 80px 20px;
            text-align: center;
        }

        .stats-grid {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 40px;
        }

        .stat-item h3 { font-size: 2.5rem; color: var(--primary); margin: 0; }
        .stat-item p { color: #64748B; font-weight: bold; }

        /* --- Footer --- */
        footer {
            background: var(--dark);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <header class="hero">
        <div class="floating">
            <i class="fa-solid fa-rocket" style="font-size: 4rem; margin-bottom: 20px; color: var(--accent);"></i>
        </div>
        <h1>Unlock Your Grammar Powers!</h1>
        <p>The ultimate adventure to mastering English tenses, punctuation, and active-passive voice. Learn through lessons, play through tests, and level up your skills!</p>
        <a href="login.php" class="cta-main">START MY QUEST 🚀</a>
    </header>

    <section class="paths" id="explore">
        <div class="path-card tenses">
            <div class="icon-box"><i class="fa-solid fa-clock"></i></div>
            <h2>Verb Tenses</h2>
            <p>Master the timeline of actions! From daily habits to future dreams, learn how to express time perfectly.</p>
            <a href="tenseslesson.php" class="btn-start">Go to Lesson</a>
            <a href="tenses.php" class="btn-start" style="border: 2px solid var(--primary);">Take the Test 🏆</a>
        </div>

        <div class="path-card punct">
            <div class="icon-box"><i class="fa-solid fa-quote-right"></i></div>
            <h2>Punctuation</h2>
            <p>Commas, periods, and exclamation points! Learn how to stop, pause, and shout in your writing.</p>
            <a href="punctuation_lesson.php" class="btn-start">Go to Lesson</a>
            <a href="punctuation.php" class="btn-start" style="border: 2px solid var(--primary);">Take the Test 🏆</a>
        </div>

        <div class="path-card voice">
            <div class="icon-box"><i class="fa-solid fa-bullhorn"></i></div>
            <h2>Active & Passive</h2>
            <p>Discover who is doing the action! Flip your sentences and master the flow of professional English.</p>
            <a href="activepassivelesson.php" class="btn-start">Go to Lesson</a>
            <a href="activepassive.php" class="btn-start" style="border: 2px solid var(--primary);">Take the Test 🏆</a>
        </div>
    </section>

    <section class="stats">
        <h2 style="font-size: 2rem; margin-bottom: 40px;">Why Join the Quest?</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <h3>15+</h3>
                <p>Interactive Levels</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Self-Paced Learning</p>
            </div>
            <div class="stat-item">
                <h3>Free</h3>
                <p>Forever for Students</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 Grammar Quest Academy. Let's make learning fun!</p>
        <div style="margin-top: 10px;">
            <i class="fa-brands fa-facebook" style="margin: 0 10px; cursor: pointer;"></i>
            <i class="fa-brands fa-twitter" style="margin: 0 10px; cursor: pointer;"></i>
            <i class="fa-brands fa-instagram" style="margin: 0 10px; cursor: pointer;"></i>
        </div>
    </footer>

</body>
</html>