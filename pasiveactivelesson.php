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
<a href="active_passive.php" class="test-btn">
            Assessment <i class="fa-solid fa-vial"></i>
        </a>
        </div>
        <h1>ACTIVE PASSIVE VOICE LESSON 🧭</h1>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <p style="margin: 0px; font-style: bold;">Lessons</p>
        <a href="#welcome">Welcome</a>
        <a href="#activevoice">Active Voice</a>
        <a href="#passivevoice">Passive Voice</a>

        <br>
        <p style="margin: 0px; font-style: bold;">Settings</p>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
        <a href="tenseslesson.php" class="back-btn">Tenses Lesson</a>
        <a href="punctuationlesson.php" class="back-btn">Punctuation Lesson</a>
    </div>
 
<div class="lesson-card">

    <h2 style="margin-top: 0;">Active and Passive Voice 🎯</h2>

    <p>
        Welcome to this module on Active and Passive Voice!<br><br>
        In this lesson, you will explore how sentences are structured to show who is doing the action and who is receiving the action. 
        Understanding this concept is essential in improving your grammar skills and making your writing clearer, more effective, and more purposeful.
        <br><br>
        You will learn that in the active voice, the subject performs the action, making sentences more direct and engaging. 
        On the other hand, the passive voice emphasizes the action or the receiver of the action, which is often used in formal, academic, or objective writing.
    </p>

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin: 20px 0;">

        <div style="flex: 1; background: #f0f9ff; padding: 15px; border-radius: 20px;">
            <p style="margin: 0;">
                <strong>What you will learn:</strong><br>
                • Distinguish between active and passive voice<br>
                • Transform sentences from one form to another<br>
                • Apply both voices appropriately in different contexts
            </p>
        </div>

        <div style="flex: 1; background: #fff7ed; padding: 15px; border-radius: 20px;">
            <p style="margin: 0;">
                <strong>Goal:</strong><br>
                Improve sentence construction for clearer and more effective communication.
            </p>
        </div>

    </div>

    <p style="font-style: italic; color: #64748b; font-size: 0.95rem; border-top: 1px solid #eee; padding-top: 15px;">
        🚀 <strong>Reminder:</strong> The voice of a verb tells whether the subject performs or receives the action.
    </p>

</div>


<div class="lesson-card">

    <h2>🎯 VOICES OF VERBS</h2>

    <p>The voice of a verb tells whether the subject of the sentence performs or receives the action.</p>

    <p><strong>VOICES ARE OF TWO TYPES:</strong> Active Voice and Passive Voice</p>

</div>


<div id="activevoice" class="lesson-card">

    <h2>🔍 ACTIVE VOICE</h2>

    <p>
        The active voice is a sentence structure in which the subject performs the action expressed by the verb. 
        It is clear, direct, and commonly used in both spoken and written English.
    </p>

    <h3>Basic Pattern:</h3>
    <p><strong>Subject + Verb + Object</strong></p>

    <p><strong>Example:</strong></p>
    <p>Maria wrote the essay.</p>

    <div class="example-box">
        <p><strong>Structure Breakdown:</strong></p>
        <ul>
            <li>Subject – Maria (doer of the action)</li>
            <li>Verb – wrote (action)</li>
            <li>Object – the essay (receiver of the action)</li>
        </ul>
    </div>

</div>


<div class="lesson-card">

    <h2>🧠 WHY USE ACTIVE VOICE?</h2>

    <ul>
        <li>Makes sentences clear and straightforward</li>
        <li>Creates a strong and direct tone</li>
        <li>Improves readability and engagement</li>
        <li>Avoids unnecessary wordiness</li>
    </ul>

    <div class="example-box">
        <p><strong>Compare:</strong></p>
        <p>✔ Active: The teacher explained the lesson clearly.<br>
           ✖ Passive: The lesson was explained clearly by the teacher.</p>
    </div>

</div>


<div class="lesson-card">

    <h2>✏️ EXAMPLES OF ACTIVE VOICE</h2>

    <p><strong>Simple Sentences:</strong></p>
    <ul>
        <li>The dog chased the cat.</li>
        <li>She completed her assignment.</li>
        <li>The students answered the questions.</li>
    </ul>

    <p><strong>Questions:</strong></p>
    <ul>
        <li>Did the manager approve the request?</li>
        <li>Is she writing a report?</li>
    </ul>

    <p><strong>Commands:</strong></p>
    <ul>
        <li>Submit the report on time.</li>
        <li>Complete the activity carefully.</li>
    </ul>

    <div class="example-box">
        <p><strong>Note:</strong> In commands, the subject “you” is implied.</p>
    </div>

</div>


<div class="lesson-card">

    <h2>⚠️ COMMON MISTAKES</h2>

    <ul>
        <li>❌ The homework completed by the student.</li>
        <li>✔ The student completed the homework.</li>
        <li>❌ Overusing passive voice when active is clearer</li>
        <li>❌ Missing subjects causing unclear meaning</li>
    </ul>

</div>


<div class="lesson-card">

    <h2>🎯 WHEN TO USE ACTIVE VOICE</h2>

    <ul>
        <li>When emphasizing the doer of the action</li>
        <li>When clarity and brevity are needed</li>
        <li>In essays, reports, and instructions</li>
        <li>In direct and engaging communication</li>
    </ul>
    <div class="rule-box">
<h3>💡 Key Takeaway</h3>
&nbsp;&nbsp;&nbsp;Active voice strengthens your writing by making it clear, concise, and engaging. Mastering it will help you communicate your ideas more effectively in both academic and real-life contexts.
</div>

</div>
<style>
    :root {
        --secondary-color: #3B82F6; /* Added missing variable */
    }

    .activity-container {
        background: #F8FAFC;
        padding: 30px;
        border-radius: 30px;
        border: 3px solid var(--secondary-color);
        margin: 20px 0;
        font-family: sans-serif;
    }

    .activity-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: opacity 0.3s ease, transform 0.2s ease;
        position: relative;
    }

    .locked-card {
        opacity: 0.3; /* Increased transparency for better visual cue */
        pointer-events: none;
        filter: blur(2px) grayscale(1);
    }

    .question-text {
        font-size: 1.2rem;
        font-weight: bold;
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

    .choice-btn:hover:not(:disabled) {
        border-color: var(--secondary-color);
        background: #F0FDFA;
    }

    .correct-glow {
        background: #DBEAFE !important;
        border-color: #3B82F6 !important;
        color: #1E40AF !important;
    }

    .feedback-msg {
        font-weight: bold;
        margin-top: 10px;
        display: block;
        min-height: 24px;
    }
</style>

<div class="lesson-card">
    <div class="activity-container">
        <h2>🧩 Module Activity: Active Voice Identification</h2>
        <p>Choose whether each sentence is <strong>Active Voice</strong> or <strong>Not Active Voice</strong>.</p>

        <!-- Q1 through Q10 -->
        <div id="card-q1" class="activity-card">
            <p class="question-text">1. The teacher explains the lesson.</p>
            <button class="choice-btn" onclick="verify('q1', true, true, this)">Active Voice</button>
            <button class="choice-btn" onclick="verify('q1', false, false, this)">Not Active Voice</button>
            <span id="q1-feedback" class="feedback-msg"></span>
        </div>

        <div id="card-q2" class="activity-card locked-card">
            <p class="question-text">2. The homework was completed by the student.</p>
            <button class="choice-btn" onclick="verify('q2', false, true, this)">Active Voice</button>
            <button class="choice-btn" onclick="verify('q2', false, false, this)">Not Active Voice</button>
            <span id="q2-feedback" class="feedback-msg"></span>
        </div>


    <div id="card-q3" class="activity-card locked-card">
        <p class="question-text">3. Maria baked a cake.</p>
        <button class="choice-btn" onclick="verify('q3', true, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('q3', false, false, this)">Not Active Voice</button>
        <span id="q3-feedback" class="feedback-msg"></span>
    </div>

        <div id="card-q4" class="activity-card locked-card">
    <p class="question-text">4. The song was sung by the choir.</p>
    
    <button class="choice-btn" onclick="verify('q4', false, false, this)">Active Voice</button>
    <button class="choice-btn" onclick="verify('q4', false, true, this)">Not Active Voice</button>
    
    <span id="q4-feedback" class="feedback-msg"></span>
</div>

        <div id="card-q5" class="activity-card locked-card">
            <p class="question-text">5. The dog chased the cat.</p>
            <button class="choice-btn" onclick="verify('q5', true, true, this)">Active Voice</button>
            <button class="choice-btn" onclick="verify('q5', false, false, this)">Not Active Voice</button>
            <span id="q5-feedback" class="feedback-msg"></span>
        </div>

        <div id="card-q6" class="activity-card locked-card">
            <p class="question-text">6. The window was broken by the boy.</p>
            <button class="choice-btn" onclick="verify('q6', false, false, this)">Active Voice</button>
    <button class="choice-btn" onclick="verify('q6', false, true, this)">Not Active Voice</button>
            <span id="q6-feedback" class="feedback-msg"></span>
        </div>

        <div id="card-q7" class="activity-card locked-card">
            <p class="question-text">7. She writes a letter.</p>
            <button class="choice-btn" onclick="verify('q7', true, true, this)">Active Voice</button>
            <button class="choice-btn" onclick="verify('q7', false, false, this)">Not Active Voice</button>
            <span id="q7-feedback" class="feedback-msg"></span>
        </div>

        <div id="card-q8" class="activity-card locked-card">
            <p class="question-text">8. The project was finished on time.</p>
            <button class="choice-btn" onclick="verify('q8', false, false, this)">Active Voice</button>
    <button class="choice-btn" onclick="verify('q8', false, true, this)">Not Active Voice</button>
            <span id="q8-feedback" class="feedback-msg"></span>
        </div>

        <div id="card-q9" class="activity-card locked-card">
            <p class="question-text">9. The students answered the questions.</p>
            <button class="choice-btn" onclick="verify('q9', true, true, this)">Active Voice</button>
            <button class="choice-btn" onclick="verify('q9', false, false, this)">Not Active Voice</button>
            <span id="q9-feedback" class="feedback-msg"></span>
        </div>

        <div id="card-q10" class="activity-card locked-card">
            <p class="question-text">10. The meal is prepared by the chef.</p>
            <button class="choice-btn" onclick="verify('q10', false, false, this)">Active Voice</button>
    <button class="choice-btn" onclick="verify('q10', false, true, this)">Not Active Voice</button>
            <span id="q10-feedback" class="feedback-msg"></span>
        </div>
    </div>
</div>

<script>
function verify(qid, isCorrectAnswer, isCorrectChoice, btnElement) {

    const feedback = document.getElementById(qid + "-feedback");
    const card = document.getElementById("card-" + qid);

    const isCorrect = (isCorrectAnswer === isCorrectChoice);

    if (isCorrect) {

        feedback.innerHTML = "Correct! You may proceed.";
        feedback.style.color = "blue";

        // disable buttons
        const buttons = card.querySelectorAll(".choice-btn");
        buttons.forEach(b => b.disabled = true);

        btnElement.classList.add("correct-glow");

        // safe extraction of number
        const currentNum = Number(qid.replace("q", ""));
        const nextCard = document.getElementById("card-q" + (currentNum + 1));

        if (nextCard) {
            nextCard.classList.remove("locked-card");
            nextCard.style.opacity = "1";
            nextCard.style.filter = "none";
            nextCard.style.pointerEvents = "auto";

            setTimeout(() => {
                nextCard.scrollIntoView({ behavior: "smooth", block: "center" });
            }, 150);
        } else {
            feedback.innerHTML = "🎉 Activity Complete!";
        }

    } else {
        feedback.innerHTML = "Incorrect. Try again.";
        feedback.style.color = "red";

        card.style.transform = "translateX(10px)";
        setTimeout(() => card.style.transform = "translateX(0)", 100);
    }
}
function unlockCard(cardId) {
    const nextCard = document.getElementById(cardId);
    if (nextCard) {
        nextCard.classList.remove("locked-card");
        nextCard.style.opacity = "1";
        nextCard.style.filter = "none";
        nextCard.style.pointerEvents = "auto";
        
        // Timeout ensures the browser has rendered the removal of 'locked-card' 
        // before attempting to scroll to it
        setTimeout(() => {
            nextCard.scrollIntoView({ behavior: "smooth", block: "center" });
        }, 100);
    }
}
</script>

<div id="passivevoice" class="lesson-card">

    <h1>🔍 PASSIVE VOICE</h1>

    <h2>What is Passive Voice?</h2>

    <p>
        The passive voice is a sentence structure in which the subject receives the action of the verb rather than performing it. 
        In this form, the focus is placed on the action or the receiver of the action, not on the doer (agent).
    </p>

    <div class="rule-box">

        <h3>👉 Basic Structure:</h3>
        <p><strong>Subject + form of “be” + past participle + (by + doer)</strong></p>

        <p><strong>Example:</strong></p>
        <p>The letter was written by Maria.</p>
        <p>(The subject “letter” receives the action.)</p>

    </div>

    <div class="example-box">

        <h3>🧠 When Do We Use Passive Voice?</h3>

        <ol>
            <li>
                <strong>When the doer is unknown</strong><br>
                The window was broken.<br>
                <em>(We don’t know who broke it.)</em>
            </li>

            <li>
                <strong>When the doer is unimportant</strong><br>
                The documents were submitted yesterday.
            </li>

            <li>
                <strong>When the focus is on the action or result</strong><br>
                A new bridge was built last year.
            </li>

            <li>
                <strong>In formal, academic, or scientific writing</strong><br>
                The experiment was conducted under controlled conditions.
            </li>
        </ol>

    </div>

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

<div class="lesson-card">

    <h3>🔍 Structure Breakdown</h3>

    <p>Let’s analyze a sentence transformation:</p>

    <div class="example-box">
        <p><strong>Active Voice:</strong><br>
        👉 The teacher explained the lesson.</p>

        <p><strong>Passive Voice:</strong><br>
        👉 The lesson was explained by the teacher.</p>
    </div>

    <div class="kids-table-wrapper">

        <table class="kids-table">

            <thead>
                <tr>
                    <th>Element</th>
                    <th>Active Voice</th>
                    <th>Passive Voice</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>Subject</td>
                    <td>The teacher</td>
                    <td>The lesson</td>
                </tr>

                <tr>
                    <td>Verb</td>
                    <td>explained</td>
                    <td>was explained</td>
                </tr>

                <tr>
                    <td>Object</td>
                    <td>the lesson</td>
                    <td>—</td>
                </tr>

                <tr>
                    <td>Agent (optional)</td>
                    <td>—</td>
                    <td>by the teacher</td>
                </tr>
            </tbody>

        </table>

    </div>

</div>
<div class="lesson-card">

    <h2>📘 Forms of Passive Voice in Different Tenses</h2>

    <p>Here are examples across common verb tenses:</p>

    <div class="example-box">

        <p><strong>1. Simple Present</strong></p>
        <p>Active: The chef cooks the meal.<br>
        Passive: The meal is cooked by the chef.</p>

        <p><strong>2. Simple Past</strong></p>
        <p>Active: The student answered the question.<br>
        Passive: The question was answered by the student.</p>

        <p><strong>3. Simple Future</strong></p>
        <p>Active: The company will launch a product.<br>
        Passive: A product will be launched by the company.</p>

        <p><strong>4. Present Continuous</strong></p>
        <p>Active: The workers are building the house.<br>
        Passive: The house is being built by the workers.</p>

        <p><strong>5. Present Perfect</strong></p>
        <p>Active: She has completed the task.<br>
        Passive: The task has been completed by her.</p>

    </div>

    <hr>

    <h3>⚠️ Important Notes</h3>

    <ul>
        <li>The verb in passive voice always uses <strong>be + past participle</strong>.</li>
        <li>The “by + doer” phrase is optional and often omitted.</li>
        <li>Only transitive verbs (verbs with objects) can be changed into passive voice.</li>
    </ul>

    <hr>

    <h3>✏️ More Examples</h3>

    <ul>
        <li>The song was sung beautifully.</li>
        <li>The classroom is cleaned every day.</li>
        <li>The decision has been made.</li>
        <li>The project will be finished soon.</li>
    </ul>

    <hr>

    <h3>🎯 Key Takeaways</h3>

    <ul>
        <li>Passive voice emphasizes the receiver of the action.</li>
        <li>It is useful for formal writing, objectivity, and focusing on results.</li>
        <li><strong>Structure:</strong> Subject + be + past participle (+ by + doer)</li>
    </ul>

</div>
  
<style>
    .activity-container {
        background: #F8FAFC;
        padding: 30px;
        border-radius: 30px;
        border: 3px solid var(--secondary-color, #3B82F6);
        margin: 20px 0;
        font-family: sans-serif;
    }

    .activity-card {
        background: white;
        padding: 20px;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 0px rgba(0,0,0,0.05);
        transition: opacity 0.3s ease, transform 0.2s ease;
    }

    .locked-card {
        opacity: 0.3;
        pointer-events: none;
        filter: blur(2px) grayscale(1);
    }

    .question-text {
        font-size: 1.1rem;
        font-weight: bold;
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

    .choice-btn:hover:not(:disabled) {
        border-color: var(--secondary-color, #3B82F6);
        background: #F0FDFA;
    }

    .correct-glow {
        background: #DBEAFE !important;
        border-color: #3B82F6 !important;
        color: #1E40AF !important;
    }

    .feedback-msg {
        font-weight: bold;
        margin-top: 10px;
        min-height: 24px;
        display: block;
    }
</style>

<div class="lesson-card">

    <h2>🧩 Activity: Identifying Active and Passive Voice</h2>

    <p>
        📌 Read each sentence carefully. Identify whether it is written in <strong>Active Voice</strong> or <strong>Passive Voice</strong>.
    </p>


   <!-- Container for the 10 questions -->
<div class="activity-container">
    
    <!-- Q1 -->
    <div id="ap-card-q1" class="activity-card">
        <p class="question-text">1. The teacher explains the lesson clearly.</p>
        <button class="choice-btn" onclick="verify('ap', 'q1', true, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q1', true, false, this)">Passive Voice</button>
        <span id="ap-q1-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q2 -->
    <div id="ap-card-q2" class="activity-card locked-card">
        <p class="question-text">2. The chef prepared a delicious meal.</p>
        <button class="choice-btn" onclick="verify('ap', 'q2', true, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q2', true, false, this)">Passive Voice</button>
        <span id="ap-q2-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q3 -->
    <div id="ap-card-q3" class="activity-card locked-card">
        <p class="question-text">3. The homework was completed by the students.</p>
        <button class="choice-btn" onclick="verify('ap', 'q3', false, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q3', false, false, this)">Passive Voice</button>
        <span id="ap-q3-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q4 -->
    <div id="ap-card-q4" class="activity-card locked-card">
        <p class="question-text">4. The children played in the park.</p>
        <button class="choice-btn" onclick="verify('ap', 'q4', true, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q4', true, false, this)">Passive Voice</button>
        <span id="ap-q4-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q5 -->
    <div id="ap-card-q5" class="activity-card locked-card">
        <p class="question-text">5. The report is written by the researcher.</p>
        <button class="choice-btn" onclick="verify('ap', 'q5', false, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q5', false, false, this)">Passive Voice</button>
        <span id="ap-q5-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q6 -->
    <div id="ap-card-q6" class="activity-card locked-card">
        <p class="question-text">6. The window was broken during the storm.</p>
        <button class="choice-btn" onclick="verify('ap', 'q6', false, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q6', false, false, this)">Passive Voice</button>
        <span id="ap-q6-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q7 -->
    <div id="ap-card-q7" class="activity-card locked-card">
        <p class="question-text">7. The nurse checks the patient’s temperature.</p>
        <button class="choice-btn" onclick="verify('ap', 'q7', true, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q7', true, false, this)">Passive Voice</button>
        <span id="ap-q7-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q8 -->
    <div id="ap-card-q8" class="activity-card locked-card">
        <p class="question-text">8. The letter was sent yesterday.</p>
        <button class="choice-btn" onclick="verify('ap', 'q8', false, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q8', false, false, this)">Passive Voice</button>
        <span id="ap-q8-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q9 -->
    <div id="ap-card-q9" class="activity-card locked-card">
        <p class="question-text">9. The dog chased the cat.</p>
        <button class="choice-btn" onclick="verify('ap', 'q9', true, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q9', true, false, this)">Passive Voice</button>
        <span id="ap-q9-feedback" class="feedback-msg"></span>
    </div>

    <!-- Q10 -->
    <div id="ap-card-q10" class="activity-card locked-card">
        <p class="question-text">10. The classroom is cleaned every afternoon.</p>
        <button class="choice-btn" onclick="verify('ap', 'q10', false, true, this)">Active Voice</button>
        <button class="choice-btn" onclick="verify('ap', 'q10', false, false, this)">Passive Voice</button>
        <span id="ap-q10-feedback" class="feedback-msg"></span>
    </div>
</div>

<script>
/**
 * @param activity string - The prefix (e.g., 'ap')
 * @param qid string - The question ID (e.g., 'q1')
 * @param isCorrectAnswer bool - If the sentence is actually Active (true) or Passive (false)
 * @param userChoice bool - What the user clicked (Active=true, Passive=false)
 * @param btnElement object - The button clicked
 */
function verify(activity, qid, isCorrectAnswer, userChoice, btnElement) {
    const feedback = document.getElementById(activity + "-" + qid + "-feedback");
    const card = document.getElementById(activity + "-card-" + qid);

    // Check if user choice matches the correct answer logic
    if (isCorrectAnswer === userChoice) {
        feedback.innerHTML = "Correct! You may proceed.";
        feedback.style.color = "blue";

        // Lock current buttons
        const buttons = card.querySelectorAll(".choice-btn");
        buttons.forEach(b => b.disabled = true);
        btnElement.classList.add("correct-glow");

        // Unlock next card logic
        const currentNum = Number(qid.replace("q", ""));
        const nextId = activity + "-card-q" + (currentNum + 1);
        const nextCard = document.getElementById(nextId);

        if (nextCard) {
            nextCard.classList.remove("locked-card");
            nextCard.style.opacity = "1";
            nextCard.style.filter = "none";
            nextCard.style.pointerEvents = "auto";
            
            // Short delay to ensure browser paints before scrolling
            setTimeout(() => {
                nextCard.scrollIntoView({ behavior: "smooth", block: "center" });
            }, 100);
        } else {
            feedback.innerHTML = "🎉 Activity Complete! Well done.";
        }
    } else {
        feedback.innerHTML = "Incorrect. Try again.";
        feedback.style.color = "red";
        
        // Simple shake effect
        card.style.transform = "translateX(10px)";
        setTimeout(() => card.style.transform = "translateX(0)", 100);
    }
}
</script>
        <br>
        <center>
            <a href="pasiveactivelesson.php" class="back-btn" style="background: var(--accent-green); box-shadow: 0 5px 0px #166534;color: white;">Start Assessment🚀</a>
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
<script>
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const items = document.querySelectorAll('.floating-item');

        items.forEach(item => {
            // Get the speed from the data attribute
            const speed = item.getAttribute('data-speed');
            
            // Calculate movement (shifting Y axis)
            // The formula creates an upward movement effect
            const yPos = -(scrolled * speed / 10);
            
            // Apply the transformation
            item.style.transform = `translateY(${yPos}px)`;
        });
    });
</script>
</body>
</html>