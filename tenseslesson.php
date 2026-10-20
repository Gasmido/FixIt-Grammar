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
        <a href="tenses.php" class="test-btn">
            Assessment <i class="fa-solid fa-vial"></i>
        </a>
        </div>
        <h1>VERB TENSES ADVENTURE 🧭</h1>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <p style="margin: 0px; font-style: bold;">Lessons</p>
        <a href="#welcome">Welcome</a>
        <a href="#objectives">Objectives</a>
        <a href="#lesson1">Present Tense</a>
        <a href="#lesson2">Past Tense</a>
        <a href="#lesson3">Future Tense</a>
        <a href="#lesson4">Using Verb Tenses In Varied Contexts</a>
        <a href="#lesson5">Tense Consistency</a>
        <a href="#lesson6">Common Errors</a>
        <br>
        <p style="margin: 0px; font-style: bold;">Settings</p>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        <a href="punctuationlesson.php" class="back-btn">Punctuation Lesson</a>
        <a href="pasiveactivelesson.php" class="back-btn">Active & Passive Lesson</a>
    </div>
 
    <div class="lesson-card" style="border-left: 8px solid var(--secondary-color); background: #fdfdfd;" >
    <h2 style="margin-top: 0;">Welcome to the Verb Tenses Module! 👋</h2>
    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;Effective communication in English requires not only a strong vocabulary but also a clear understanding of how actions relate to time. 
        Verb tenses play a crucial role in expressing whether an action has already occurred, is happening at present, or will take place in the future. In this module, you will explore the different verb tenses and learn how to use them appropriately in varied situations. Through guided explanations, examples, and practice activities, you will develop the ability to choose the correct verb tense and maintain consistency in your sentences. This will enable you to express ideas more clearly, avoid common grammatical errors, and improve both your written and spoken communication skills.
    </p>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin: 20px 0;">
        <div style="flex: 1; background: #f0f9ff; padding: 15px; border-radius: 20px;">
            <p style="margin: 0;"><strong>Why it matters:</strong> Tenses help convey meaning accurately across different contexts such as conversations, narratives, and academic writing.</p>
        </div>
        <div  id="objectives" style="flex: 1; background: #fff7ed; padding: 15px; border-radius: 20px;">
           <p style="margin: 0;"><strong>What you'll gain:</strong> You will develop the ability to choose the correct verb tense and maintain consistency, avoiding common errors.</p>
        </div>
    </div>

    <p style="font-style: italic; color: #64748b; font-size: 0.95rem; border-top: 1px solid #eee; padding-top: 15px;">
        🚀 <strong>Pro-tip:</strong> This is a self-paced module! Move at your own speed, review examples carefully, and complete all activities to reinforce your understanding.
    </p>
</div>
    <div class="lesson-card" >
        <h2>🎯 LESSON OBJECTIVES</h2>

<div class="objective-list">
    <p>At the end of this module, you should be able to:</p>

    <ul>
        <li>Identify the different verb tenses (present, past, and future) and their forms.</li>

        <li>Explain the use of each verb tense in expressing time, duration, and sequence of actions.</li>

        <li>Differentiate between simple, continuous, and perfect tenses in various contexts.</li>

        <li>Apply the correct verb tense in sentences based on given situations or time expressions.</li>

        <li>Use appropriate verb tenses in varied contexts such as narratives, conversations, and academic writing.</li>

        <li>Demonstrate consistency in verb tense within sentences and paragraphs.</li>

        <li>Correct common errors related to verb tense usage.</li>
    </ul>
</div>

        <h2>🔍 I. What Are Verb Tenses?</h2>

<p>
    <span class="tooltip">
        Verb

        <span class="tooltiptext">
            <strong>Verb</strong> - a word used to describe an action, occurrence, or state of being.

            <br><br>

            Examples:
            <br>&nbsp;&nbsp;• run<br>&nbsp;&nbsp;• eat<br>&nbsp;&nbsp;• study<br>&nbsp;&nbsp;• is
        </span>
    </span>

    tenses are grammatical forms that indicate the 
    <strong>time</strong> of an action or event. They allow speakers and writers 
    to clearly express whether something has already happened, is happening now, 
    or will happen in the future.
</p>

<p>
    However, verb tenses are not just about time. They also express:
</p>

<ul id="lesson1">
    <li><strong>Duration</strong> (how long an action happens)</li>

    <li><strong>Completion</strong> (whether an action is finished)</li>

    <li><strong>Sequence</strong> (order of events)</li>
</ul>
</div>

<div class="lesson-card">
    <div style="text-align: ;">
       <h2>🧩 II. THE THREE MAIN TIME FRAMES</h2><br><br>
    </div>
<h1>1. Present Tense (Now / General Time)</h1>
<p>
    The present tense is used to express:
</p>
<ul>
    <li>Habits and routines</li>
    <li>General truths</li>
    <li>Current actions</li>
    <li>Actions that started in the past and continue today</li>
</ul>

<p>
    Used for habits, routines, general truths, and current actions.
</p>

<div class="rule-box">
    <strong>A. Simple Present:</strong> Used for daily activities and facts.<br><br>

    <strong>🔑 Rule:</strong><br>
    Add -s/-es for third person singular (he, she, it).
</div>

<div class="example-box">
    Example/s:<br>
    ✔ She <strong>studies</strong> every night. (Habit)<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The phrase “every night” shows a repeated action (habit)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Therefore, we use simple present tense<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “She” is third person singular → add -s → studies<br><br>

    ✔ Water <strong>boils</strong> at 100°C. (Fact)<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• This is a scientific fact (always true)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Facts always use simple present tense<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Water” is singular → use base verb “boils”
</div>
<style>
    .activity-container {
        background: #F8FAFC;
        padding: 30px;
        border-radius: 30px;
        border: 3px solid var(--secondary-color);
        margin: 20px 0;
    }

    .activity-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: opacity 0.3s ease;
    }

    .locked-card {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .question-text {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--text-color);
        margin-bottom: 15px;
    }

    .choice-btn {
        padding: 10px 25px;
        margin: 5px;
        font-size: 1rem;
        font-weight: bold;
        border: 3px solid #E2E8F0;
        border-radius: 15px;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .choice-btn:hover {
        border-color: var(--secondary-color);
        background: #F0FDFA;
    }

    .choice-btn.correct-glow {
         background: #DBEAFE; /* Blue highlight for the 'Blue' correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .feedback-msg {
        font-weight: bold;
        margin-top: 10px;
        display: block;
        min-height: 24px;
    }
</style>

<div class="activity-container">
    <h2>🧩 Practice Time!</h2>
    <p>Read each sentence carefully. Choose the correct verb tense to proceed!</p>

    <div id="card-q1" class="activity-card">
        <p class="question-text">1. She _______ every night.</p>
        <button class="choice-btn" onclick="verify('q1', 'study', false, this)">study</button>
        <button class="choice-btn" onclick="verify('q1', 'studies', true, this)">studies</button>
        <span id="q1-feedback" class="feedback-msg"></span>
    </div>

    <div id="card-q2" class="activity-card locked-card">
        <p class="question-text">2. The teacher _______ our work daily. </p>
        <button class="choice-btn" onclick="verify('q2', 'checks', true, this)">checks</button>
        <button class="choice-btn" onclick="verify('q2', 'check', false, this)">check</button>
        
        <span id="q2-feedback" class="feedback-msg"></span>
    </div>

    <div id="card-q3" class="activity-card locked-card">
        <p class="question-text">3. Water _______ at 100°C. </p>
        <button class="choice-btn" onclick="verify('q3', 'boil', false, this)">boil</button>
        <button class="choice-btn" onclick="verify('q3', 'boils', true, this)">boils</button>
        <span id="q3-feedback" class="feedback-msg"></span>
    </div>
</div>

<script>
function verify(qid, answer, isCorrect, btnElement) {
    const feedback = document.getElementById(qid + "-feedback");
    const currentCard = document.getElementById("card-" + qid);

    if (isCorrect) {
        // Style changes for correct answer
        feedback.innerHTML = "Correct! You may proceed. ";
        feedback.style.color = "blue";
        
        // Disable buttons in this card so they can't change answer
        const buttons = currentCard.querySelectorAll('.choice-btn');
        buttons.forEach(btn => btn.disabled = true);
        btnElement.classList.add('correct-glow');

        // Unlock the next card
        if (qid === 'q1') {
            unlockCard('card-q2');
        } else if (qid === 'q2') {
            unlockCard('card-q3');
        } else {
            feedback.innerHTML = "🎉 All items complete! Keep reading below.";
        }
    } else {
        // Style changes for incorrect answer
        feedback.innerHTML = "Incorrect. Review the sentence and try again. ";
        feedback.style.color = "red";
        
        // Shake effect for kid-friendly feedback
        currentCard.style.transform = "translateX(10px)";
        setTimeout(() => currentCard.style.transform = "translateX(0)", 100);
    }
}

function unlockCard(cardId) {
    const nextCard = document.getElementById(cardId);
    nextCard.classList.remove('locked-card');
    nextCard.style.filter = "none";
    nextCard.style.opacity = "1";
    nextCard.style.pointerEvents = "auto";
    // Smooth scroll to the next question
    nextCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

      <div class="rule-box">
    <strong>B. Present Continuous (Present Progressive):</strong> Used for actions happening <strong>now</strong>.<br><br>

    <strong>🔑 Structure:</strong><br>
    am/is/are + verb + -ing
</div>

<div class="example-box">
    Example/s:<br>
    ✔ She <strong>is studying</strong> now.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The word “now” indicates the action is happening at this moment<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Present continuous is used for actions in progress<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Structure: is + studying<br><br>

    ⚖️ Compare:<br>

    ✔ She studies every night.<br>
    &nbsp;&nbsp;👉 Habit (repeated action)<br><br>

    ✔ She is studying now.<br>
    &nbsp;&nbsp;👉 Action happening right now<br><br>

    👉 Key Understanding:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• If action is routine → simple present<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• If action is happening now → present continuous
</div>
<style>
    /* Reusing the styling from the previous activity to ensure consistency */
    .present-continuous-container {
        background: #F0FDFA; /* Light Mint background for this specific section */
        padding: 30px;
        border-radius: 30px;
        border: 3px solid #5CE1E6;
        margin: 20px 0;
    }

    .pc-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .pc-locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .pc-btn {
        padding: 10px 20px;
        margin: 5px;
        font-weight: bold;
        border: 2px solid #CBD5E1;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: 0.2s;
    }

    .pc-btn:hover {
        background: #F1F5F9;
        border-color: #5CE1E6;
    }

    .pc-btn.success {
         background: #DBEAFE; /* Blue highlight for the 'Blue' correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .pc-feedback {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 0.95rem;
    }
</style>

<div class="present-continuous-container">
    <h2>🎯 Present Continuous Challenge</h2>
    <p>Focus on words like <strong>"now"</strong> and <strong>"at the moment"</strong> to find the actions in progress!</p>

    <div id="pc-q1" class="pc-card">
        <p class="question-text">1. She _______ now.</p>
        <button class="pc-btn" onclick="checkPC('q1', 'is studying', true, this)">is studying</button>
        <button class="pc-btn" onclick="checkPC('q1', 'studies', false, this)">studies</button>
        
        <span id="pc-q1-msg" class="pc-feedback"></span>
    </div>

    <div id="pc-q2" class="pc-card pc-locked">
        <p class="question-text">2. They _______ outside at the moment.</p>
        <button class="pc-btn" onclick="checkPC('q2', 'play', false, this)">play</button>
        <button class="pc-btn" onclick="checkPC('q2', 'are playing', true, this)">are playing</button>
        <span id="pc-q2-msg" class="pc-feedback"></span>
    </div>

    <div id="pc-q3" class="pc-card pc-locked">
        <p class="question-text">3. I _______ a book right now.</p>
        <button class="pc-btn" onclick="checkPC('q3', 'am reading', true, this)">am reading</button>
        <button class="pc-btn" onclick="checkPC('q3', 'read', false, this)">read</button>
        
        <span id="pc-q3-msg" class="pc-feedback"></span>
    </div>
</div>

<script>
function checkPC(qid, answer, isCorrect, btn) {
    const feedback = document.getElementById("pc-" + qid + "-msg");
    const card = document.getElementById("pc-" + qid);

    if (isCorrect) {
        feedback.innerHTML = "Correct! Actions happening now use am/is/are + ing!";
        feedback.style.color = "blue";
        
        // Lock this card
        const btns = card.querySelectorAll('.pc-btn');
        btns.forEach(b => b.disabled = true);
        btn.classList.add('success');

        // Unlock logic
        if (qid === 'q1') {
            unlockNext('pc-q2');
        } else if (qid === 'q2') {
            unlockNext('pc-q3');
        } else {
            feedback.innerHTML = "🎉 Great job! You've mastered the Present Continuous items!";
        }
    } else {
        feedback.innerHTML = "Incorrect. Remember, 'now' means the action is in progress!";
        feedback.style.color = "red";
        
        // Subtle shake
        card.style.transform = "scale(0.98)";
        setTimeout(() => card.style.transform = "scale(1)", 100);
    }
}

function unlockNext(id) {
    const next = document.getElementById(id);
    next.classList.remove('pc-locked');
    next.style.filter = "none";
    next.style.opacity = "1";
    next.style.pointerEvents = "auto";
    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
        <div class="rule-box">
    <strong>C. Present Perfect:</strong> Actions at an unspecified time or continuing to the present.<br><br>

    Used for:
    <ul>
        <li>Actions that happened at an unspecified time in the past</li>
        <li>Actions that continue up to the present</li>
    </ul>

    <strong>🔑 Structure:</strong><br>
    has/have + past participle
</div>

<div class="example-box">
    Example/s:<br>
    ✔ She <strong>has finished</strong> her homework.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The action is already completed, but the time is not mentioned<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The result matters now (she is free)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Structure: has + finished (past participle)<br><br>

    ✔ I <strong>have lived</strong> here for five years.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The action started in the past and continues until now<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The phrase “for five years” shows duration<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Present perfect connects past + present<br><br>

    ⚖️ Compare:<br>

    ✔ I lived here for five years.<br>
    &nbsp;&nbsp;👉 Action is finished (I no longer live here)<br><br>

    ✔ I have lived here for five years.<br>
    &nbsp;&nbsp;👉 I still live here
</div>
<style>
    /* Consistently using the theme from previous activities */
    .present-perfect-container {
        background: #FFFBEB; /* Light Gold background for this section */
        padding: 30px;
        border-radius: 30px;
        border: 3px solid #FACC15;
        margin: 20px 0;
    }

    .pp-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .pp-locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .pp-btn {
        padding: 10px 20px;
        margin: 5px;
        font-weight: bold;
        border: 2px solid #CBD5E1;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: 0.2s;
    }

    .pp-btn:hover {
        background: #F1F5F9;
        border-color: #FACC15;
    }

    .pp-btn.success {
        background: #DBEAFE; /* Blue highlight for the 'Blue' correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .pp-feedback {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 0.95rem;
    }
</style>

<div class="present-perfect-container">
    <h2>✨ Present Perfect Quest</h2>
    <p>Look for actions that connect the <strong>past to the present</strong> or have no specific time mentioned!</p>

    <div id="pp-q1" class="pp-card">
        <p class="question-text">1. I _______ my homework already.</p>
        <button class="pp-btn" onclick="checkPP('q1', 'have finished', true, this)">have finished</button>
        <button class="pp-btn" onclick="checkPP('q1', 'finished', false, this)">finished</button>
                <span id="pp-q1-msg" class="pp-feedback"></span>
    </div>

    <div id="pp-q2" class="pp-card pp-locked">
        <p class="question-text">2. She _______ Manila.</p>
        <button class="pp-btn" onclick="checkPP('q2', 'visited', false, this)">visited</button>
        <button class="pp-btn" onclick="checkPP('q2', 'has visited', true, this)">has visited</button>
        <span id="pp-q2-msg" class="pp-feedback"></span>
    </div>

    <div id="pp-q3" class="pp-card pp-locked">
        <p class="question-text">3. They _______ here for five years.</p>
        <button class="pp-btn" onclick="checkPP('q3', 'have lived', true, this)">have lived</button>
        <button class="pp-btn" onclick="checkPP('q3', 'lived', false, this)">lived</button>
        
        <span id="pp-q3-msg" class="pp-feedback"></span>
    </div>
    <div id="lesson2"> </div>
</div>

<script>
function checkPP(qid, answer, isCorrect, btn) {
    const feedback = document.getElementById("pp-" + qid + "-msg");
    const card = document.getElementById("pp-" + qid);

    if (isCorrect) {
        // [cite: 101] Blue means correct
        feedback.innerHTML = "Correct! Use has/have + past participle for this tense!";
        feedback.style.color = "blue";
        
        // Disable buttons
        const btns = card.querySelectorAll('.pp-btn');
        btns.forEach(b => b.disabled = true);
        btn.classList.add('success');

        // Unlock logic
        if (qid === 'q1') {
            unlockPP('pp-q2');
        } else if (qid === 'q2') {
            unlockPP('pp-q3');
        } else {
            feedback.innerHTML = "🎉 Excellent! You've completed the Present Perfect activity!";
        }
    } else {
        // [cite: 100] Red means incorrect
        feedback.innerHTML = "Incorrect. This action connects the past to now or has no specific time!";
        feedback.style.color = "red";
        
        // Shake animation
        card.style.transform = "translateX(-5px)";
        setTimeout(() => card.style.transform = "translateX(5px)", 80);
        setTimeout(() => card.style.transform = "translateX(0)", 160);
    }
}

function unlockPP(id) {
    const next = document.getElementById(id);
    next.classList.remove('pp-locked');
    next.style.filter = "none";
    next.style.opacity = "1";
    next.style.pointerEvents = "auto";
    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
<br><br><br>
</div>  
<div class="lesson-card">
        <h1>2. Past Tense (Completed Actions)</h1>
<p>Expresses actions that are already finished.</p>

<div class="rule-box">
    <strong>A. Simple Past:</strong> Used for completed actions at a specific time (yesterday, last night, ago).<br><br>

    The past tense expresses actions that are already finished.<br><br>

    <strong>🔑 Signal words:</strong><br>
    yesterday, last night, ago
</div>

<div class="example-box">
    Example/s:<br>
    ✔ She <strong>visited</strong> her grandmother yesterday.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The word “yesterday” clearly shows a finished past action<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Therefore, use simple past tense<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Visit” → “visited”
</div>
<style>
    /* Styling consistent with previous Simple Past theme */
    .simple-past-container {
        background: #FEF2F2; /* Light Pink/Red background for Past Tense */
        padding: 30px;
        border-radius: 30px;
        border: 3px solid #F87171;
        margin: 20px 0;
    }

    .sp-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .sp-locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .sp-btn {
        padding: 10px 20px;
        margin: 5px;
        font-weight: bold;
        border: 2px solid #CBD5E1;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: 0.2s;
    }

    .sp-btn:hover {
        background: #F1F5F9;
        border-color: #F87171;
    }

    .sp-btn.success {
        background: #DBEAFE; /* Consistent blue for correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .sp-feedback {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 0.95rem;
    }
</style>

<div class="simple-past-container">
    <h2>⏳ Simple Past Quest</h2>
    <p>Look for finished actions and signal words like <strong>"yesterday"</strong> and <strong>"last night"</strong>!</p>

    <div id="sp-q1" class="sp-card">
        <p class="question-text">1. She _______ her grandmother yesterday.</p>
        <button class="sp-btn" onclick="checkSP('q1', 'visited', true, this)">visited</button>
        <button class="sp-btn" onclick="checkSP('q1', 'visits', false, this)">visits</button>
        
        <span id="sp-q1-msg" class="sp-feedback"></span>
    </div>

    <div id="sp-q2" class="sp-card sp-locked">
        <p class="question-text">2. I _______ a movie last night.</p>
        <button class="sp-btn" onclick="checkSP('q2', 'watched', true, this)">watched</button>
        <button class="sp-btn" onclick="checkSP('q2', 'watch', false, this)">watch</button>
        
        <span id="sp-q2-msg" class="sp-feedback"></span>
    </div>

    <div id="sp-q3" class="sp-card sp-locked">
        <p class="question-text">3. They _______ basketball earlier.</p>
        <button class="sp-btn" onclick="checkSP('q3', 'play', false, this)">play</button>
        <button class="sp-btn" onclick="checkSP('q3', 'played', true, this)">played</button>
        <span id="sp-q3-msg" class="sp-feedback"></span>
    </div>
</div>

<script>
function checkSP(qid, answer, isCorrect, btn) {
    const feedback = document.getElementById("sp-" + qid + "-msg");
    const card = document.getElementById("sp-" + qid);

    if (isCorrect) {
        // Blue indicates correct [cite: 123]
        feedback.innerHTML = "Correct! This action is finished and happened at a specific time.";
        feedback.style.color = "blue";
        
        const btns = card.querySelectorAll('.sp-btn');
        btns.forEach(b => b.disabled = true);
        btn.classList.add('success');

        if (qid === 'q1') {
            unlockSP('sp-q2');
        } else if (qid === 'q2') {
            unlockSP('sp-q3');
        } else {
            feedback.innerHTML = "🎉 Fantastic! You have completed the Simple Past activities!";
        }
    } else {
        // Red indicates incorrect [cite: 122]
        feedback.innerHTML = "Incorrect. Look for the past form of the verb for finished actions!";
        feedback.style.color = "red";
        
        card.style.transform = "scale(0.95)";
        setTimeout(() => card.style.transform = "scale(1)", 100);
    }
}

function unlockSP(id) {
    const next = document.getElementById(id);
    next.classList.remove('sp-locked');
    next.style.filter = "none";
    next.style.opacity = "1";
    next.style.pointerEvents = "auto";
    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
        <div class="rule-box">
    <strong>B. Past Continuous:</strong> Ongoing actions in the past or background actions.<br><br>

    Used for:
    <ul>
        <li>Actions that were ongoing in the past</li>
        <li>Background actions in stories</li>
    </ul>

    <strong>🔑 Structure:</strong><br>
    was/were + verb + -ing
</div>

<div class="example-box">
    Example/s:<br>
    ✔ She <strong>was studying</strong> when I called.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Two actions happened:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. was studying (ongoing action)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. called (short action that interrupted)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Past continuous shows background action in progress<br><br>

    &nbsp;&nbsp;👉 Pattern to remember:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Ongoing action → past continuous<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Interrupting action → simple past
</div>
<style>
    /* Styling consistent with Past Continuous theme */
    .past-continuous-container {
        background: #F5F3FF; /* Light Lavender background */
        padding: 30px;
        border-radius: 30px;
        border: 3px solid #A78BFA;
        margin: 20px 0;
    }

    .pcon-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .pcon-locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .pcon-btn {
        padding: 10px 20px;
        margin: 5px;
        font-weight: bold;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: 0.2s;
    }

    .pcon-btn:hover {
        background: #F5F3FF;
        border-color: #A78BFA;
    }

    .pcon-btn.success {
        background: #DBEAFE; /* Blue highlight for correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .pcon-feedback {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 0.95rem;
    }
</style>

<div class="past-continuous-container">
    <h2>🎬 Past Continuous Storyteller</h2>
    <p>Find the <strong>ongoing background action</strong> that was happening when something else interrupted!</p>

    <div id="pcon-q1" class="pcon-card">
        <p class="question-text">1. She _______ when I called.</p>
        <button class="pcon-btn" onclick="checkPCon('q1', 'studied', false, this)">studied</button>
        <button class="pcon-btn" onclick="checkPCon('q1', 'was studying', true, this)">was studying</button>
        <span id="pcon-q1-msg" class="pcon-feedback"></span>
    </div>

    <div id="pcon-q2" class="pcon-card pcon-locked">
        <p class="question-text">2. They _______ when it rained.</p>
        <button class="pcon-btn" onclick="checkPCon('q2', 'played', false, this)">played</button>
        <button class="pcon-btn" onclick="checkPCon('q2', 'were playing', true, this)">were playing</button>
        <span id="pcon-q2-msg" class="pcon-feedback"></span>
    </div>

    <div id="pcon-q3" class="pcon-card pcon-locked">
        <p class="question-text">3. I _______ when the phone rang.</p>
        <button class="pcon-btn" onclick="checkPCon('q3', 'slept', false, this)">slept</button>
        <button class="pcon-btn" onclick="checkPCon('q3', 'was sleeping', true, this)">was sleeping</button>
        <span id="pcon-q3-msg" class="pcon-feedback"></span>
    </div>
</div>

<script>
function checkPCon(qid, answer, isCorrect, btn) {
    const feedback = document.getElementById("pcon-" + qid + "-msg");
    const card = document.getElementById("pcon-" + qid);

    if (isCorrect) {
        // Blue indicates correct [cite: 144]
        feedback.innerHTML = "Correct! This ongoing action uses was/were + verb + -ing!";
        feedback.style.color = "blue";
        
        const btns = card.querySelectorAll('.pcon-btn');
        btns.forEach(b => b.disabled = true);
        btn.classList.add('success');

        if (qid === 'q1') {
            unlockPCon('pcon-q2');
        } else if (qid === 'q2') {
            unlockPCon('pcon-q3');
        } else {
            feedback.innerHTML = "🎉 Fantastic! You've mastered background actions in the past!";
        }
    } else {
        // Red indicates incorrect [cite: 143]
        feedback.innerHTML = "Incorrect. Remember, ongoing background actions need the -ing form!";
        feedback.style.color = "red";
        
        card.style.transform = "translateY(-5px)";
        setTimeout(() => card.style.transform = "translateY(0)", 100);
    }
}

function unlockPCon(id) {
    const next = document.getElementById(id);
    next.classList.remove('pcon-locked');
    next.style.filter = "none";
    next.style.opacity = "1";
    next.style.pointerEvents = "auto";
    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

       <div class="rule-box">
    <strong>C. Past Perfect:</strong> "Past before past"—an action completed before another past action.<br><br>

    Used for:
    <ul>
        <li>Actions completed before another past action</li>
    </ul>
    &nbsp;&nbsp;👉 Think of it as:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;“past before past”
</div>

<div class="example-box">
    Example/s:<br>
    ✔ She <strong>had studied</strong> before the exam started.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Two past actions:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. studying (happened first)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. exam started (happened later)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Past perfect shows the earlier action<br><br>

    &nbsp;&nbsp;👉 Key Idea:<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “had studied” = past before past
</div>
<style>
    /* Styling consistent with Past Perfect theme */
    .past-perfect-container {
        background: #F1F5F9; /* Slate background */
        padding: 30px;
        border-radius: 30px;
        border: 3px solid #475569;
        margin: 20px 0;
    }

    .pperf-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .pperf-locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .pperf-btn {
        padding: 10px 20px;
        margin: 5px;
        font-weight: bold;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: 0.2s;
    }

    .pperf-btn:hover {
        background: #F1F5F9;
        border-color: #475569;
    }

    .pperf-btn.success {
        background: #DBEAFE; /* Blue highlight for correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .pperf-feedback {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 0.95rem;
    }
</style>

<div class="past-perfect-container">
    <h2>⏮️ Past Perfect Time Traveler</h2>
    <p>Find the action that happened <strong>FIRST</strong> before another action in the past!</p>

    <div id="pperf-q1" class="pperf-card">
        <p class="question-text">1. She _______ her homework before dinner.</p>
        <button class="pperf-btn" onclick="checkPPerf('q1', 'finished', false, this)">finished</button>
        <button class="pperf-btn" onclick="checkPPerf('q1', 'had finished', true, this)">had finished</button>
        <span id="pperf-q1-msg" class="pperf-feedback"></span>
    </div>

    <div id="pperf-q2" class="pperf-card pperf-locked">
        <p class="question-text">2. They _______ when I arrived.</p>
        <button class="pperf-btn" onclick="checkPPerf('q2', 'left', false, this)">left</button>
        <button class="pperf-btn" onclick="checkPPerf('q2', 'had left', true, this)">had left</button>
        <span id="pperf-q2-msg" class="pperf-feedback"></span>
    </div>

    <div id="pperf-q3" class="pperf-card pperf-locked">
        <p class="question-text">3. I _______ before the meeting started.</p>
        <button class="pperf-btn" onclick="checkPPerf('q3', 'ate', false, this)">ate</button>
        <button class="pperf-btn" onclick="checkPPerf('q3', 'had eaten', true, this)">had eaten</button>
        <span id="pperf-q3-msg" class="pperf-feedback"></span>
    </div>
    <div id="lesson3"></div>
</div>

<script>
function checkPPerf(qid, answer, isCorrect, btn) {
    const feedback = document.getElementById("pperf-" + qid + "-msg");
    const card = document.getElementById("pperf-" + qid);

    if (isCorrect) {
        // Blue indicates correct
        feedback.innerHTML = "Correct! Use 'had' + past participle for the earlier action.";
        feedback.style.color = "blue";
        
        const btns = card.querySelectorAll('.pperf-btn');
        btns.forEach(b => b.disabled = true);
        btn.classList.add('success');

        if (qid === 'q1') {
            unlockPPerf('pperf-q2');
        } else if (qid === 'q2') {
            unlockPPerf('pperf-q3');
        } else {
            feedback.innerHTML = "🎉 Incredible! You've mastered the 'Past before the Past'!";
        }
    } else {
        // Red indicates incorrect
        feedback.innerHTML = "Incorrect. This action happened FIRST, so it needs 'had'.";
        feedback.style.color = "red";
        
        card.style.transform = "rotate(-1deg)";
        setTimeout(() => card.style.transform = "rotate(1deg)", 100);
        setTimeout(() => card.style.transform = "rotate(0deg)", 200);
    }
}

function unlockPPerf(id) {
    const next = document.getElementById(id);
    next.classList.remove('pperf-locked');
    next.style.filter = "none";
    next.style.opacity = "1";
    next.style.pointerEvents = "auto";
    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
<br><br>
</div>
<div class="lesson-card">
        <h1>3. Future Tense (Upcoming Actions)</h1>

<div class="rule-box">
    <strong>A. Simple Future (will):</strong> Spontaneous decisions, promises, or predictions.<br><br>

    Future tense expresses actions that have not yet happened.<br><br>

    Used for:
    <ul>
        <li>Spontaneous decisions</li>
        <li>Promises</li>
        <li>Predictions</li>
    </ul>
</div>

<div class="example-box">
    Example/s:<br>
    ✔ I <strong>will help</strong> you.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The decision is made at the moment of speaking<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Will” is used for instant decisions or promises
</div>
<style>
    /* Styling consistent with Simple Future theme */
    .simple-future-container {
        background: #FFF7ED; /* Light Orange background */
        padding: 30px;
        border-radius: 30px;
        border: 3px solid #FB923C;
        margin: 20px 0;
    }

    .sfut-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .sfut-locked {
        opacity: 0.5;
        pointer-events: none;
        filter: grayscale(1);
        filter: blur(1px);
    }

    .sfut-btn {
        padding: 10px 20px;
        margin: 5px;
        font-weight: bold;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: 0.2s;
    }

    .sfut-btn:hover {
        background: #FFF7ED;
        border-color: #FB923C;
    }

    .sfut-btn.success {
        background: #DBEAFE; /* Blue highlight for correct feedback */
        border-color: #3B82F6;
        color: #1E40AF;
    }

    .sfut-feedback {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        font-size: 0.95rem;
    }
</style>

<div class="simple-future-container">
    <h2>🚀 Simple Future Voyage</h2>
    <p>Look into the crystal ball for actions that haven't happened yet! Watch for words like <strong>"tomorrow"</strong> and <strong>"next week"</strong>.</p>

    <div id="sfut-q1" class="sfut-card">
        <p class="question-text">1. I _______ you later.</p>
        <button class="sfut-btn" onclick="checkSFut('q1', 'will help', true, this)">will help</button>
        <button class="sfut-btn" onclick="checkSFut('q1', 'help', false, this)">help</button>
        
        <span id="sfut-q1-msg" class="sfut-feedback"></span>
    </div>

    <div id="sfut-q2" class="sfut-card sfut-locked">
        <p class="question-text">2. She _______ to school tomorrow.</p>
        <button class="sfut-btn" onclick="checkSFut('q2', 'will go', true, this)">will go</button>
        <button class="sfut-btn" onclick="checkSFut('q2', 'goes', false, this)">goes</button>
        
        <span id="sfut-q2-msg" class="sfut-feedback"></span>
    </div>

    <div id="sfut-q3" class="sfut-card sfut-locked">
        <p class="question-text">3. They _______ next week.</p>
        <button class="sfut-btn" onclick="checkSFut('q3', 'will travel', true, this)">will travel</button>
        <button class="sfut-btn" onclick="checkSFut('q3', 'travel', false, this)">travel</button>
        
        <span id="sfut-q3-msg" class="sfut-feedback"></span>
    </div>
</div>

<script>
function checkSFut(qid, answer, isCorrect, btn) {
    const feedback = document.getElementById("sfut-" + qid + "-msg");
    const card = document.getElementById("sfut-" + qid);

    if (isCorrect) {
        // Blue indicates correct
        feedback.innerHTML = "Correct! For future actions, we use 'will' + the base verb.";
        feedback.style.color = "blue";
        
        const btns = card.querySelectorAll('.sfut-btn');
        btns.forEach(b => b.disabled = true);
        btn.classList.add('success');

        if (qid === 'q1') {
            unlockSFut('sfut-q2');
        } else if (qid === 'q2') {
            unlockSFut('sfut-q3');
        } else {
            feedback.innerHTML = "🎉 Zoom! You've mastered the Future Tense items!";
        }
    } else {
        // Red indicates incorrect
        feedback.innerHTML = "Incorrect. This hasn't happened yet, so it needs 'will'!";
        feedback.style.color = "red";
        
        // "Bounce" effect for errors
        card.style.transform = "translateY(5px)";
        setTimeout(() => card.style.transform = "translateY(0)", 100);
    }
}

function unlockSFut(id) {
    const next = document.getElementById(id);
    next.classList.remove('sfut-locked');
    next.style.filter = "none";
    next.style.opacity = "1";
    next.style.pointerEvents = "auto";
    next.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

        <div class="rule-box">
    <strong>B. “Going to” Future:</strong> Planned actions or intentions.<br><br>

    Used for:
    <ul>
        <li>Planned actions</li>
        <li>Intentions</li>
    </ul>
</div>

<div class="example-box">
    Example/s:<br>
    ✔ I <strong>am going to study</strong> tonight.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The plan was made before speaking<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Going to” shows intention or prior plan<br><br>

    ⚖️ Compare:<br>

    I will study. → decision now<br>
    I am going to study. → planned earlier
</div>

<div class="rule-box">
    <strong>C. Future Continuous:</strong> Used for actions that will be ongoing at a specific future time.<br>
</div>

<div class="example-box" id="lesson4">
    Example/s:<br>
    ✔ I <strong>will be studying</strong> at 8 PM.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The action will be in progress at a specific future time<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Emphasizes duration in the future
</div>
</div>
<div class="lesson-card">
       <h2>⚖️ VI. USING VERB TENSES IN VARIED CONTEXTS</h2>

<p>Correct tense is not just about grammar—it depends on context.</p>

<div class="rule-box">
    <strong>📖 1. Narrative Context (Storytelling)</strong><br><br>

    Stories usually use past tense because events already happened.<br><br>

    ✔ She walked into the room and saw a shadow.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Stories describe completed events<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Therefore, use simple past tense<br><br>

    &nbsp;&nbsp;👉 Past continuous can describe background:<br><br>

    ✔ She was walking when she heard a noise.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “was walking” → ongoing action<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “heard” → interrupting action<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• This creates a clear sequence
</div>

<div class="rule-box">
    <strong>📰 2. Informative / Academic Context</strong><br><br>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• General truths → Present<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Research findings → Past<br><br>

    ✔ The study shows that students learn better with practice.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• This is a general truth or conclusion<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Use present tense<br><br>

    ✔ The researcher found significant results.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The research was already completed<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Use past tense
</div>

<div class="rule-box">
    <strong>💬 3. Conversational Context</strong><br><br>

    Tenses can shift depending on meaning.<br><br>

    ✔ I was walking when I saw her.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Shows two actions with clear timing<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Past continuous + simple past<br><br>

    ✔ I have seen that movie.<br>
    &nbsp;&nbsp;👉 Why is this correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• The exact time is not important<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Focus is on experience
</div>

<style>
    .kids-table-wrapper {
        background: white;
        padding: 20px;
        border-radius: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        margin: 20px 0;
        border: 4px solid #F1F5F9;
        overflow: hidden; /* Keeps the rounded corners clean */
    }

    .kids-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 1.05rem;
    }

    /* Header Styling */
    .kids-table th {
        background-color: var(--secondary-color, #5CE1E6);
        color: white;
        padding: 20px;
        text-align: left;
        font-size: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .kids-table th:first-child { border-top-left-radius: 20px; }
    .kids-table th:last-child { border-top-right-radius: 20px; }

    /* Rows and Cells */
    .kids-table td {
        padding: 18px 20px;
        border-bottom: 2px solid #F1F5F9;
        transition: background 0.2s;
    }

    /* Left Column Icons/Status */
    .kids-table td:first-child {
        font-weight: bold;
        color: var(--primary-color, #FF5757);
        width: 40%;
    }

    /* Right Column Examples */
    .kids-table td:last-child {
        font-family: 'Courier New', Courier, monospace; /* Makes the code/example stand out */
        color: #334155;
    }

    /* Alternating Row Colors */
    .kids-table tr:nth-child(even) td {
        background-color: #F8FAFC;
    }

    /* Hover effect to help focus */
    .kids-table tr:hover td {
        background-color: #F0FDFA;
    }

    .situation-icon {
        margin-right: 10px;
        font-size: 1.3rem;
        vertical-align: middle;
    }

    .tense-tag {
        background: #FFE4E6;
        color: #E11D48;
        padding: 4px 3px;
        border-radius: 10px;
        font-size: 0.8rem;
        margin-left: 10px;
        display: inline-block;
    }
</style>

<div class="kids-table-wrapper">
    <h3 style="text-align: center; color: var(--primary-color); margin-bottom: 20px;">📅 Real-Life Situation Guide</h3>
    
    <table class="kids-table">
        <thead>
            <tr>
                <th>When is it?</th>
                <th>How to say it:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="situation-icon">🔄</span> Habit <span class="tense-tag">Simple Present</span></td>
                <td>"I <strong>wake up</strong> early."</td>
            </tr>
            <tr>
                <td><span class="situation-icon">⚡</span> Happening Now <span class="tense-tag">Continuous</span></td>
                <td>"I <strong>am waking</strong> up."</td>
            </tr>
            <tr>
                <td><span class="situation-icon">✅</span> Completed <span class="tense-tag">Simple Past</span></td>
                <td>"I <strong>woke up</strong>."</td>
            </tr>
            <tr>
                <td><span class="situation-icon">📝</span> Planned <span class="tense-tag">Future</span></td>
                <td>"I <strong>am going to wake</strong> up early."</td>
            </tr>
        </tbody>
    </table>
    
    <p style="text-align: center; font-size: 0.9rem; color: #64748b; margin-top: 15px;">
        💡 <em>Notice how the verb "wake" changes its shape based on the time!</em>
    </p>
    <div id="lesson5"></div>
</div>
</div>
<div class="lesson-card">
<h2>VII. TENSE CONSISTENCY</h2>

<p>
One of the most important skills is maintaining consistent verb tense within a sentence or paragraph.
</p>

<div class="rule-box">

    ❌ Incorrect:<br>
    She went to school and eats lunch.<br>
    &nbsp;&nbsp;👉 Why incorrect?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “went” = past<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “eats” = present<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Mixed time → confusing<br><br>

    ✔ Correct:<br>
    She went to school and ate lunch.<br>
    &nbsp;&nbsp;👉 Why correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Both verbs are in past tense<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Clear and consistent time frame<br>
</div>

<div class="rule-box">

    ❌ Incorrect:<br>
    He is studying when she arrived.<br>
    &nbsp;&nbsp;👉 Why incorrect?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “is studying” = present<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “arrived” = past<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Time mismatch<br><br>

    ✔ Correct:<br>
    He was studying when she arrived.<br>
    &nbsp;&nbsp;👉 Why correct?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Both actions are in the past timeline<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Correct sequence: ongoing + interruption<br><br>

    🔑 Rule:<br>
    Keep the same time frame unless there is a clear reason to shift.
    <div id="lesson6"></div>
</div>
</div>
<div class="lesson-card">
<h2>🚫 VIII. COMMON ERRORS</h2>

<div class="rule-box">

    <strong>1. Wrong verb form</strong><br><br>

    ❌ I have saw that movie.<br>
    &nbsp;&nbsp;👉 Why incorrect?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Have” requires past participle (V3)<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Saw” is simple past, not past participle<br><br>

    ✔ I have seen that movie.<br>
    &nbsp;&nbsp;👉 Past participle must be used in perfect tenses.
</div>

<div class="rule-box">

    <strong>2. Mixing tenses incorrectly</strong><br><br>

    ❌ She is going to school yesterday.<br>
    &nbsp;&nbsp;👉 Why incorrect?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “Yesterday” = past<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “is going” = present continuous<br><br>

    ✔ She went to school yesterday.
</div>

<div class="rule-box">

    <strong>3. Subject-verb agreement confusion</strong><br><br>

    ❌ She go to school.<br>
    &nbsp;&nbsp;👉 Why incorrect?<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• “She” = third person singular<br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Needs -s in simple present<br><br>

    ✔ She goes to school.
</div>

<div class="rule-box">

    🧠 FINAL SELF-PACED STRATEGY<br><br>

    Before choosing a verb tense, always ask:<br>
    1. When did the action happen?<br>
    2. Is it finished or ongoing?<br>
    3. Is there a time clue?<br>
    4. Is it connected to the present?
</div>

        <br>
        <center>
            <a href="tenses.php" class="back-btn" style="background: var(--accent-green); box-shadow: 0 5px 0px #166534;color: white;">Start Assessment 🚀</a>
        </center>

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