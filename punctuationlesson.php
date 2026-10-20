<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verb Tenses Lesson</title>
    <style>
    :root {
        /* Grammar Quest Official Palette */
        --primary: #6366F1;    /* Royal Indigo */
        --secondary: #06B6D4;  /* Cyan */
        --accent: #FACC15;     /* Yellow */
        --bg-color: #F8FAFC;   /* Slate 50 */
        --text-main: #1E293B;  /* Slate 900 */
        --text-muted: #64748B; /* Slate 500 */
        --card-bg: #FFFFFF;
        --accent-green: #10B981;
    }

    /* Smooth Scrolling and Responsive Font */
    html {
        scroll-behavior: smooth;
    }

    p, div, li {
        font-size: clamp(0.9rem, 1.2vw, 1.05rem);
        color: var(--text-main);
    }

    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background-color: var(--bg-color);
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 30px 30px;
        margin: 0;
        padding: 20px;
        padding-top: 100px; /* Space for Fixed Navbar */
        line-height: 1.7;
    }

    .container {
        width: 90%;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Fixed Navbar Header */
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 40px;
        box-sizing: border-box;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border-bottom: 1px solid #e2e8f0;
    }

    .navbar h1 {
        margin: 0;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: clamp(1.2rem, 3vw, 1.6rem);
        font-weight: 800;
    }

    /* Cards Styling */
    .header-card {
        text-align: center;
        background: white;
        padding: 40px;
        border-radius: 35px;
        margin-bottom: 30px;
        border-bottom: 8px solid var(--primary);
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .lesson-card {
        background: white;
        padding: 40px;
        border-radius: 35px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #F1F5F9;
    }

    h2 { 
        color: var(--primary); 
        font-weight: 800;
        border-bottom: 4px solid var(--secondary); 
        display: inline-block; 
        padding-bottom: 5px; 
        margin-bottom: 20px;
    }

    h3 { 
        color: var(--text-main); 
        font-weight: 700;
        margin-top: 30px; 
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Highlight Boxes */
    .objective-list {
        background: #EEF2FF; /* Light Indigo */
        padding: 25px;
        border-radius: 25px;
        border-left: 6px solid var(--primary);
    }

    .rule-box {
        background: #F0FDFA; /* Light Cyan */
        padding: 20px;
        border-radius: 20px;
        border: 2px dashed var(--secondary);
        margin: 20px 0;
    }

    .example-box {
        background: #F8FAFC;
        padding: 20px;
        border-radius: 20px;
        border-left: 6px solid var(--accent-green);
        margin: 15px 0;
        font-style: italic;
    }

    /* Table Design */
    .comparison-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 25px 0;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }

    .comparison-table th { 
        background: var(--primary); 
        color: white; 
        padding: 15px;
        font-weight: 600;
        text-align: left;
    }

    .comparison-table td { 
        padding: 15px; 
        background: white;
        border-bottom: 1px solid #F1F5F9;
    }

    /* Sidebar Styling */
    
    .sidebar {
        position: fixed;
        top: 85px;
        left: -400px;

        width: 280px;

        height: calc(100vh - 100px);
        overflow-y: auto;

        background: white;
        padding: 20px;

        border-radius: 0 30px 30px 0;
        box-shadow: 10px 0 30px rgba(0,0,0,0.1);

        z-index: 999;

        transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);

        opacity: 0;
    }

    .sidebar.active {
        left: 0;
        opacity: 1;
    }
    .sidebar::-webkit-scrollbar {
    width: 8px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 20px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: #F1F5F9;
    }
    .sidebar a {
        display: block;
        text-decoration: none;
        background: #F8FAFC;
        color: var(--text-main);
        padding: 12px 18px;
        margin-bottom: 12px;
        border-radius: 15px;
        font-weight: 600;
        transition: 0.3s;
        border: 1px solid #E2E8F0;
    }

    .sidebar a:hover {
        background: var(--primary);
        color: white;
        transform: translateX(8px);
        border-color: var(--primary);
    }

    /* Buttons & Interactions */
    #menu-toggle {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 15px;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    }

    #menu-toggle:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }

    .back-btn {
        background: #F1F5F9;
        color: var(--text-muted);
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .back-btn:hover {
        background: #EF4444; /* Red for emphasis on exit */
        color: white;
    }

    #fidgetBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border: none;
        border-radius: 50%;
        background: var(--accent);
        color: #854D0E;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 8px 0 #CA8A04;
        transition: 0.1s;
        z-index: 1001;
    }

    #fidgetBtn:active {
        transform: translateY(4px);
        box-shadow: 0 4px 0 #CA8A04;
    }

    .tooltip {
    position: relative;
    display: inline-block;
    color: var(--primary);
    font-weight: bold;
    border-bottom: 2px dotted var(--secondary);
    cursor: pointer;
}

.tooltip .tooltiptext {
    visibility: hidden;
    opacity: 0;

    position: absolute;
    bottom: 130%;
    left: 50%;
    transform: translateX(-50%);

    width: 260px;

    background: white;
    color: var(--text-main);
    border: 1px solid var(--secondary);
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);

    padding: 20px;

    transition: opacity 0.3s ease;
    z-index: 100;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

.highlight {
    color: var(--primary);
    font-weight: bold;
}
/* --- Go To Tests Button Styling --- */
.test-btn {
    text-decoration: none;
    background: var(--secondary); /* Cyan */
    color: white;
    padding: 10px 20px;
    border-radius: 15px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.2);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none;
    cursor: pointer;
}

.test-btn i {
    font-size: 1rem;
}

.test-btn:hover {
    background: var(--primary); /* Shifts to Indigo on hover */
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
    color: white;
}

.test-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustment for small screens */
@media (max-width: 600px) {
    .test-btn {
        padding: 8px 12px;
        font-size: 0.8rem;
    }
    .test-btn span {
        display: none; /* Only show icon on very small screens if needed */
    }
}
</style>
</head>
<body>

<div class="container" id="welcome">
    <!-- Fixed Navbar -->
    <div class="navbar">
        <div style="display: flex; align-items: center; gap: 15px;">
            
            <!-- The Toggle Button -->
            <button id="menu-toggle">
    Menu <span>☰</span>
</button>
<a href="punctuation.php" class="test-btn">
            Assessment <i class="fa-solid fa-vial"></i>
        </a>
        </div>
        <h1>PUNCTUATION LESSON 🧭</h1>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <p style="margin: 0px; font-style: bold;">Lessons</p>
        <a href="#welcome">Welcome</a>
        <a href="#objectives">Objectives</a>
        <a href="#lesson1">Capitalization</a>
        <a href="#lesson2">Punctuation</a>

        <br>
        <p style="margin: 0px; font-style: bold;">Settings</p>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        <a href="tenseslesson.php" class="back-btn">Tenses Lesson</a>
        <a href="pasiveactivelesson.php" class="back-btn">Active & Passive Lesson</a>
    </div>
 
    <div class="lesson-card" style="border-left: 8px solid var(--secondary-color); background: #fdfdfd;">
    
    <h2 style="margin-top: 0;">Capitalization and Punctuation for Clear Writing 🎯</h2>
    
    <p>
        This module helps learners understand and apply the correct use of capitalization and punctuation marks to improve clarity in writing. 
        These mechanics guide readers in interpreting meaning correctly and avoiding confusion.
    </p>

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin: 20px 0;">
        <div style="flex: 1; background: #f0f9ff; padding: 15px; border-radius: 20px;">
            <p style="margin: 0;">
                <strong>What this module is about:</strong> Learning how capitalization and punctuation improve clarity and structure in writing.
            </p>
        </div>
        
        <div id="objectives" style="flex: 1; background: #fff7ed; padding: 15px; border-radius: 20px;">
            <p style="margin: 0;">
                <strong>Focus:</strong> Applying correct writing mechanics to avoid confusion and improve readability.
            </p>
        </div>
    </div>

    <p style="font-style: italic; color: #64748b; font-size: 0.95rem; border-top: 1px solid #eee; padding-top: 15px;">
        🚀 <strong>Pro-tip:</strong> Practice regularly by writing short sentences and checking proper capitalization and punctuation.
    </p>
</div>

<div class="lesson-card">

    <h2>🎯 LEARNING OBJECTIVES</h2>

    <div class="objective-list">
        <p>At the end of this module, learners should be able to:</p>

        <ul>
            <li>Identify rules of capitalization</li>
            <li>Use basic punctuation marks correctly</li>
            <li>Apply punctuation and capitalization in sentences</li>
            <li>Recognize and correct common errors</li>
        </ul>
    </div>
</div>
<div  id="lesson1" class="lesson-card">

    <h1>LESSON 1: CAPITALIZATION 🔍</h1>

    <h2>What is Capitalization?</h2><br>
    Capitalization means writing the first letter of a word in uppercase (e.g., A, B, C). 
    It signals the beginning of sentences and highlights important words like names.</p>
    <div class="rule-box">
    <h3>RULES ON CAPITALIZATION:</h3>

    <ol>
        <li>
            Capitalize the first letter in a sentence.<br>
            <em>Ex. Research shows that there are certain blood types which are susceptible to Corona-virus infection.</em>
        </li>

        <li>
            Capitalize the first letter of the first word within quotation marks.<br>
            <em>Ex. President Duterte said, “If you don’t pass the 2021 budget, I will find ways to do it.”</em>
        </li>

        <li>
            Capitalize the first letter of a person’s job, title, or position when it precedes the name or used as direct address.<br>
            <em>Ex. I write this letter to President Rodrigo Roa Duterte.</em>
        </li>

        <li>
            Capitalize days of the week, months, and holidays.<br>
            <em>Ex. We are invited this Sunday for a party as a celebration of Christmas.</em>
        </li>

        <li>
            Always capitalize the pronoun <strong>I</strong>.<br>
            <em>Ex. My mother scolded my siblings and I intervened. I can’t stand seeing litter everywhere.</em>
        </li>

        <li>
            Capitalize proper nouns (specific names of persons, places, or things).<br>
            <em>Ex. Tourists are increasingly seen in Magellan’s Cross today.</em>
        </li>

        <li>
            Capitalize the first letter of farewell words or complimentary closes in letters.<br>
            <em>Ex. Wishing you all the best, Rowena</em>
        </li>

        <li>
            Capitalize the first letter of titles of creative works (books, movies, poems), including articles, prepositions, and conjunctions.<br>
            <em>Ex. Of Mice and Men, The Death of a Salesman</em>
        </li>
    </ol>
</div>
</div>

<div  id="lesson2" class="lesson-card">

    <h1>LESSON 2: PUNCTUATION 🔍</h1>

    <h2>What is Punctuation?</h2><br>
    Punctuation is a system of symbols used to organize sentences and make meaning clear.</p>

    <h3>PERIOD (.)</h3>
    <p>
        A period is a small dot-shaped punctuation mark used at the end of statements, requests, or commands. 
        It is also used in abbreviations and indirect quotations.
    </p>
    <div class="example-box">
    <p><strong>Examples:</strong></p>

    <p><em>Statements:</em></p>
    <ul>
        <li>My cat wakes me up every morning.</li>
        <li>There’s a new color for taxis plying on our streets today.</li>
    </ul>

    <p><em>Instruction/Command/Request:</em></p>
    <ul>
        <li>Lock all doors when you leave the house.</li>
        <li>Kindly tell the neighbors to clean their area.</li>
    </ul>

    <p><em>Indirect Question:</em></p>
    <ul>
        <li>Her aunt asked me if her niece could go with us.</li>
    </ul>

    <p><em>Abbreviations:</em></p>
    <ul>
        <li>Mr. Reyes, Gov. Gwen Garcia</li>
        <li>Jan., Feb.</li>
        <li>Phil.</li>
        <li>i.e., etc., vs.</li>
    </ul>

    <p><em>Quotation Marks:</em></p>
    <ul>
        <li>Marie said, “I want to fight for children’s rights.”</li>
        <li>Marie said that “fighting” for children’s rights is part of her advocacy.</li>
    </ul>
</div>
 

    <h3>COMMA (,)</h3>
    <p>A comma indicates a short pause and has several uses:</p>
    <div class="example-box">
    <ul>
        <li>
            Separate compound sentences joined by coordinating conjunctions.<br>
            <em>Jeepneys are not yet allowed on our streets these times, but traffic is still heavy.</em>
        </li>

        <li>
            Separate dependent and independent clauses.<br>
            <em>After the rain subsided, people gathered outside to look at the rainbow.</em>
        </li>

        <li>
            Set off non-essential words or clauses.<br>
            <em>The company, which developed the vaccine, applies for BFA approval.</em>
        </li>

        <li>
            After introductory words.<br>
            <em>Yes, I did see him an hour ago walking down the streets.</em>
        </li>

        <li>
            Direct address.<br>
            <em>Okay, dear students, let’s do it.</em>
        </li>

        <li>
            Dates (day, month, year).<br>
            <em>October 24, 2020, we are invited to attend the wedding.</em>
        </li>

        <li>
            Direct quotations.<br>
            <em>The teacher said, “Don’t be late in your classes.”</em><br>
            <em>“Are you sure,” I asked, “don’t you care?”</em>
        </li>

        <li>
            Separate statement from question.<br>
            <em>She does appear today, doesn’t she?</em>
        </li>

        <li>
            Titles or degrees.<br>
            <em>Leonor Briones, DepEd Secretary, is here.</em>
        </li>
    </ul>
</div>
 

    <h3>OTHER PUNCTUATION MARKS</h3>

    <p><strong>3. Colon (:)</strong><br>
    Introduces a list or explanation.<br>
    <em>Example: Bring the following: pen, paper, and book.</em></p>

    <p><strong>4. Semicolon (;)</strong><br>
    Connects closely related ideas.<br>
    <em>Example: She loves reading; he prefers writing.</em></p>

    <p><strong>5. Quotation Marks (“ ”)</strong><br>
    Show exact words spoken.<br>
    <em>Example: He said, “I will help you.”</em></p>

    <p><strong>6. Apostrophe (’)</strong><br>
    Shows possession or contraction.<br>
    <em>Example: Maria’s book, it’s raining.</em></p>

</div>


        <br>
        <center>
            <a href="punctuation.php" class="back-btn" style="background: var(--accent-green); box-shadow: 0 5px 0px #166534;color: white;">Start Assessment🚀</a>
        </center>
        <br><br><br>
    </div>
</div>
</div>
<script>
const btn = document.getElementById("fidgetBtn");

btn.addEventListener("click", () => {

    btn.classList.remove("spin");

    void btn.offsetWidth;

    btn.classList.add("spin");
});
</script>
<script>
    const menuBtn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    // Optional: Close sidebar when a link is clicked
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    });
</script>   
<script>
    const menuBtn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        menuBtn.classList.toggle('open'); // This triggers the 'open' styles if you add them
    });
</script>
</body>
</html>