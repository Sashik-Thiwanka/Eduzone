<?php
session_start(); 

// Define the variable so the rest of the page knows if the user is in or out
$isLoggedIn = isset($_SESSION['user_name']); 

// Optional: Define userName for later use
$userName = $isLoggedIn ? htmlspecialchars($_SESSION['user_name']) : 'Guest';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduzone Home Page</title>
<style>
    /* ---------------------------------------------------- */
    /* 1. THEME VARIABLES (Default is Light) */
    /* ---------------------------------------------------- */
    :root {
        /* Light Theme */
        --color-bg-main: #f0f8ff; /* Light Academic Blue base */
        --color-bg-secondary: #ffffff; /* Card/Container white */
        --color-text-primary: #343a40; /* Dark text */
        --color-text-secondary: #6c757d;
        --color-accent: #007bff; /* Primary Blue */
        --color-header-bg: #ffffff;
        --color-header-shadow: rgba(0, 0, 0, 0.1);
        --color-footer-bg: #343a40;
        --color-footer-text: #f8f9fa;
        --color-promo-bg: #ffc107; /* Bright Yellow for Promo (Top Banner) */
        --color-promo-text: #343a40;
        --color-callout-bg: #28a745; /* Success Green for Final Callout */
        --color-callout-text: #ffffff;
    }

    /* ---------------------------------------------------- */
    /* 2. DARK THEME OVERRIDES (Deep Navy & Royal Gold) */
    /* ---------------------------------------------------- */
    .dark-theme {
        /* Dark Theme Colors */
        --color-bg-main: #0f172a; /* Deep Navy Blue */
        --color-bg-secondary: #1e293b; /* Slightly lighter Navy for cards */
        --color-text-primary: #f1f5f9; /* Off-white text */
        --color-text-secondary: #94a3b8; /* Muted gray for secondary text */
        --color-accent: #facc15; /* Royal Gold/Yellow */
        --color-header-bg: #1e293b;
        --color-header-shadow: rgba(0, 0, 0, 0.5); /* Stronger shadow in dark mode */
        --color-footer-bg: #1e293b;
        --color-footer-text: #f1f5f9;
        --color-promo-bg: #facc15; /* Gold for Promo */
        --color-promo-text: #0f172a;
        --color-callout-bg: #15803d; /* Darker Green for Callout */
        --color-callout-text: #ffffff;
    }
    
    /* Base Styling */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: var(--color-bg-main); 
        color: var(--color-text-primary); 
        transition: background-color 0.5s ease, color 0.5s ease;
    }
    
    /* HEADER/NAV STYLES */
    .menuoption{
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        padding: 10px 0;
        flex: 1;
    }

    /* THEME TOGGLE BUTTON STYLES */
    .theme-toggle {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.5em; 
        padding: 5px 10px;
        color: var(--color-text-primary); 
        transition: color 0.3s ease, transform 0.2s ease;
        outline: none;
        line-height: 1; 
    }

    .theme-toggle:hover {
        color: var(--color-accent);
        transform: scale(1.1);
    }

    .theme-toggle .icon {
        display: inline-block; 
        transition: transform 0.5s ease;
    }

    body:not(.dark-theme) .theme-toggle .icon::before {
        content: '☀️'; 
        transform: rotate(0deg);
    }
    .dark-theme .theme-toggle .icon::before {
        content: '🌙'; 
        transform: rotate(180deg);
    }

    /* ---------------------------------------------------- */
    /* NAVIGATION BUTTONS & PERSISTENT DROPDOWN STYLES */
    /* ---------------------------------------------------- */
    .but{
        padding: 10px 18px; 
        margin: 0 5px;
        border: none;
        cursor: pointer;
        text-decoration: none; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 1.1em; 
        display: inline-block;
        transition: color 0.3s ease, background-color 0.3s ease; 
        
        background-color: transparent; 
        color: var(--color-text-primary); 
        position: relative; 
        overflow: hidden; 
    }

    .but::after {
        content: '';
        position: absolute;
        bottom: 0; 
        left: 50%; 
        transform: translateX(-50%);
        width: 0; 
        height: 4px; 
        background-color: var(--color-accent); 
        transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), background-color 0.3s ease; 
        border-radius: 2px; 
    }

    /* Highlight button when hovered or when dropdown is active */
    .but:hover, .dropdown.active .dropbtn {
        background-color: rgba(148, 163, 184, 0.1); 
        color: var(--color-accent); 
        transform: none; 
        box-shadow: none; 
    }

    .but:hover::after, .dropdown.active .dropbtn::after {
        width: calc(100% - 10px); 
    }

    /* Dropdown specific CSS */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        /* Managed by JavaScript - default is hidden */
        display: none; 
        position: absolute;
        background-color: var(--color-header-bg); 
        min-width: 180px;
        box-shadow: 0px 8px 16px 0px var(--color-header-shadow);
        z-index: 100; 
        border-radius: 6px;
        border-top: 3px solid var(--color-accent);
        right: 0; 
        /* Added transition for smoother display */
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        pointer-events: none; /* Block clicks when hidden */
    }
    
    /* Dropdown Visible State (Toggled by JS) */
    .dropdown-content.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto; /* Allow clicks when shown */
    }

    .dropdown-content a {
        color: var(--color-text-primary);
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 1em;
        transition: background-color 0.3s, color 0.3s;
    }

    .dropdown-content a:hover {
        background-color: var(--color-accent);
        color: var(--color-bg-secondary); 
    }
    /* ---------------------------------------------------- */
    
    .headercont{
        align-items: center;
        padding: 10px 20px;
        display: flex;
        margin: 0;
        box-shadow: 0 2px 4px var(--color-header-shadow); 
        background-color: var(--color-header-bg); 
        transition: box-shadow 0.3s ease-in-out, background-color 0.5s ease;
        animation: HeaderDrop 1s ease-out;
        z-index: 50; 
        position: sticky;
        top: 0;
    }
    .headercont:hover {
        box-shadow: 0 4px 8px var(--color-header-shadow); 
    }
    .title{
        flex: 1;
        text-align: left; 
        margin: 0;
    }
    h1 {
        font-size: 1.8em;
        margin: 0;
        animation: pulse 4s infinite ease-in-out;
    }
    @keyframes pulse {
        0% { color: var(--color-accent); text-shadow: 0 0 5px rgba(0, 123, 255, 0.5); }
        50% { color: var(--color-accent); text-shadow: 0 0 15px rgba(0, 123, 255, 0.8); }
        100% { color: var(--color-accent); text-shadow: 0 0 5px rgba(0, 123, 255, 0.5); }
    }
    .menu-icon {
        width: 30px;
        height: 30px;
        vertical-align: middle;
        margin-right: 10px;
        display: inline-block;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    /* SIDE MENU STYLES */
    .side-menu {
        height: 100%; 
        width: 0;
        position: fixed; 
        z-index: 1000;
        top: 0;
        left: 0;
        background-color: var(--color-footer-bg); 
        overflow-x: hidden; 
        transition: 0.5s;
        padding-top: 60px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
    }
    .side-menu.open {
        width: 250px;
    }
    .side-menu a {
        padding: 15px 25px 15px 35px;
        text-decoration: none;
        font-size: 18px;
        color: var(--color-footer-text); 
        display: block;
        transition: 0.3s;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .side-menu a:hover {
        color: var(--color-header-bg); 
        background-color: var(--color-accent);
    }
    .side-menu .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
        padding: 0; 
        border-bottom: none;
    }

    /* ---------------------------------------------------- */
    /* PROMOTIONAL BANNER STYLES (Top) */
    /* ---------------------------------------------------- */
    .promo-banner {
        background-color: var(--color-promo-bg);
        color: var(--color-promo-text);
        text-align: center;
        padding: 15px 20px;
        font-size: 1.1em;
        font-weight: 700;
        letter-spacing: 0.5px;
        border-bottom: 3px solid var(--color-accent);
        transition: background-color 0.5s ease, border-color 0.5s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .promo-banner strong {
        color: #d9534f; /* Red/Danger color */
        transition: color 0.3s ease;
    }

    .dark-theme .promo-banner strong {
        color: #8b0000; 
    }

    .promo-banner .register-link {
        text-decoration: none;
        color: var(--color-accent);
        margin-left: 15px;
        padding: 5px 10px;
        border: 2px solid var(--color-accent);
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
        font-weight: 800;
    }

    .promo-banner .register-link:hover {
        background-color: var(--color-accent);
        color: var(--color-promo-text);
    }
    /* ---------------------------------------------------- */

    /* VISION/PURPOSE STYLES (Themed) */
    .boxnext{
        display: flex;
        background-color: var(--color-accent); 
        margin: 40px auto;
        max-width: 1200px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); 
        position: relative;
        z-index: 10; 
        animation: FlyInFromLeft 1.2s ease-out;
    }
    
    .dark-theme .boxnext {
        background-color: var(--color-bg-secondary); 
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7); 
        border: 1px solid var(--color-accent); 
    }

    .purpose, .vision{
        flex:1 1 50%;
        gap:10px;
        padding: 20px;
        text-align:center;
        color: white; 
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        transition: background-color 0.3s ease;
    }
    
    .dark-theme .purpose, .dark-theme .vision {
        color: var(--color-text-primary); 
        border-right: 1px solid rgba(255, 255, 255, 0.05);
    }
    .dark-theme .boxnext h2 {
        color: var(--color-accent); 
        text-shadow: none; 
    }
    .dark-theme .boxnext p {
        color: var(--color-text-secondary); 
    }

    .purpose:hover, .vision:hover {
        background-color: #0056b3; 
    }
    .dark-theme .purpose:hover, .dark-theme .vision:hover {
        background-color: #334155; 
    }

    /* MAIN BOX - AMBIENT BACKGROUND */
    .mainbox {
        position: relative;
        overflow: hidden;
        min-height: 800px;
        
        background: var(--color-bg-main); 
        background-size: 300% 300%;
        animation: FormalColorShift 20s infinite ease-in-out alternate;
    }
    
    /* Keyframes for the Ambient Color Shift */
    @keyframes FormalColorShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* --- NEW FORMAL ANIMATIONS --- */
    @keyframes HeaderDrop {
        0% { transform: translateY(-50px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    @keyframes FlyInFromLeft {
        0% { transform: translateX(-100%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    @keyframes PopIn {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animated-entry {
        animation: PopIn 0.8s ease-out forwards;
        opacity: 0; 
    }
    
    /* ------------------ RESOURCES CARD STYLES ------------------ */

    .resource-section {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        text-align: center;
    }

    .resource-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px; 
        margin-top: 30px;
    }

    .card {
        background-color: var(--color-bg-secondary); 
        color: var(--color-text-primary); 
        border-radius: 12px;
        padding: 25px 20px; 
        text-align: center;
        box-shadow: 0 4px 15px var(--color-header-shadow); 
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.5s ease;
        cursor: pointer;
        border-top: 5px solid; 
    }

    .card:hover {
        transform: translateY(-8px); 
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); 
    }

    .card h3 {
        margin-top: 15px;
        font-size: 1.3em; 
        color: var(--color-text-primary); 
    }

    .card p {
        font-size: 0.9em; 
        color: var(--color-text-secondary); 
        min-height: 40px; 
    }

    .card-icon {
        font-size: 2.5em; 
        display: block;
        margin-bottom: 10px;
    }

    .card a {
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        margin-top: 10px;
        transition: color 0.3s;
    }

    /* Specific Card Color Coding (Kept fixed for visual pop) */
    #papers-card { border-color: #dc3545; }
    #papers-card .card-icon { color: #dc3545; }
    #papers-card a { color: #dc3545; }

    #videos-card { border-color: #ffc107; }
    #videos-card .card-icon { color: #ffc107; }
    #videos-card a { color: #ffc107; }

    #books-card { border-color: #17a2b8; }
    #books-card .card-icon { color: #17a2b8; }
    #books-card a { color: #17a2b8; }

    #models-card { border-color: #6f42c1; }
    #models-card .card-icon { color: #6f42c1; }
    #models-card a { color: #6f42c1; }
    
    #software-card { border-color: #28a745; } 
    #software-card .card-icon { color: #28a745; } 
    #software-card a { color: #28a745; }
    
    /* ------------------ SITE STATISTICS STYLES ------------------ */

    .stats-section {
        max-width: 1200px;
        margin: 60px auto; 
        padding: 20px 20px 40px 20px;
        text-align: center;
    }

    .stats-grid {
        display: flex;
        justify-content: space-around;
        gap: 30px;
        margin-top: 30px;
    }

    .stat-box {
        flex: 1; 
        padding: 20px;
        border-radius: 10px;
        background: var(--color-bg-secondary); 
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: background-color 0.5s ease;
    }

    .stat-box:hover {
        background-color: var(--color-bg-main); 
    }

    .stat-box h2 {
        font-size: 3em;
        margin: 0;
        color: var(--color-accent); 
        font-weight: 800;
    }

    .stat-box p {
        font-size: 1em;
        margin-top: 5px;
        color: var(--color-text-secondary); 
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* ------------------ COMMENTS SECTION STYLES ------------------ */

    .comments-section {
        max-width: 800px; 
        margin: 60px auto;
        padding: 30px 20px;
        background-color: var(--color-bg-secondary); 
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-top: 3px solid var(--color-accent); 
        transition: background-color 0.5s ease;
    }

    .comments-section h3 {
        text-align: center;
        color: var(--color-accent); 
        margin-bottom: 30px;
        font-size: 1.8em;
    }

    .comment-form label {
        color: var(--color-text-primary); 
    }

    .comment-form input[type="text"],
    .comment-form input[type="email"],
    .comment-form textarea {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid var(--color-text-secondary);
        border-radius: 6px;
        box-sizing: border-box;
        background-color: var(--color-bg-main); 
        color: var(--color-text-primary); 
        transition: border-color 0.3s ease, box-shadow 0.3s ease, background-color 0.5s ease;
    }

    .comment-form input:focus,
    .comment-form textarea:focus {
        border-color: var(--color-accent);
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none;
    }

    .comment-form textarea {
        resize: vertical;
        min-height: 100px;
    }

    .submit-button {
        background-color: #28a745; 
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1.1em;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
        width: 100%;
    }

    .submit-button:hover {
        background-color: #1e7e34;
        transform: translateY(-1px);
    }
    
    /* ---------------------------------------------------- */
    /* CREATIVE FINAL CALLOUT BOX STYLES */
    /* ---------------------------------------------------- */
    .final-callout {
        max-width: 1000px;
        margin: 60px auto 40px auto;
        padding: 40px;
        background-color: var(--color-callout-bg);
        color: var(--color-callout-text);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: PopIn 1s ease-out;
        border: 5px solid var(--color-accent);
    }

    .final-callout::before {
        content: '✨';
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 3em;
        opacity: 0.7;
        transform: rotate(-10deg);
    }
    
    .final-callout h2 {
        font-size: 2.2em;
        margin-bottom: 15px;
        font-weight: 900;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        color: var(--color-text-primary); 
    }
    
    .dark-theme .final-callout h2 {
        color: var(--color-accent);
    }

    .final-callout p {
        font-size: 1.2em;
        margin-bottom: 30px;
    }

    .callout-button {
        display: inline-block;
        background-color: yellow;
        color: var(--color-callout-bg); 
        padding: 15px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1em;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s ease, background-color 0.3s ease;
    }
    
    .callout-button:hover {
        transform: scale(1.05);
        background-color: white; 
        color: var(--color-callout-bg);
    }
    /* ---------------------------------------------------- */
    
    /* ------------------ FOOTER STYLES ------------------ */

    .footer {
        background-color: var(--color-footer-bg); 
        color: var(--color-footer-text); 
        padding: 40px 20px;
        margin-top: 50px; 
        border-top: 5px solid var(--color-accent); 
        font-size: 0.9em;
        transition: background-color 0.5s ease;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap; 
        gap: 30px;
    }

    .footer-column {
        flex: 1 1 200px; 
    }

    .footer h4 {
        color: var(--color-footer-text); 
        font-size: 1.1em;
        margin-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 5px;
    }

    .footer ul {
        list-style: none;
        padding: 0;
    }

    .footer ul li {
        margin-bottom: 8px;
    }

    .footer ul li a {
        color: var(--color-text-secondary); 
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer ul li a:hover {
        color: #ffffff;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--color-text-secondary);
    }
    .feedback-alert {
    padding: 15px;
    margin: 20px auto;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    max-width: 600px;
    animation: fadeIn 0.5s ease-out;
}
.feedback-alert.success {
    background-color: #d1fae5; /* Light green */
    color: #059669; /* Dark green */
    border: 1px solid #059669;
}
.feedback-alert.error {
    background-color: #fee2e2; /* Light red */
    color: #dc2626; /* Dark red */
    border: 1px solid #dc2626;
}

    /* ---------------------------------------------------- */
    /* RESPONSIVE DESIGN (Mobile & Tablet Adjustments) */
    /* ---------------------------------------------------- */
    @media (max-width: 768px) {
        .headercont {
            flex-direction: column;
            padding: 10px 10px 5px 10px;
        }
        .title {
            text-align: center;
            margin-bottom: 10px;
            width: 100%;
        }
        h1 {
            font-size: 1.4em;
            animation: none;
        }
        .menuoption {
            flex-direction: row;
            justify-content: space-around;
            flex-wrap: wrap;
            width: 100%;
            padding: 5px 0 10px 0;
            gap: 5px;
        }
        .but {
            font-size: 0.9em;
            padding: 8px 10px;
            margin: 5px 2px;
        }
        .but:hover {
            transform: none;
            box-shadow: none;
        }
        
        /* Responsive Dropdown Fix */
        .dropdown {
            width: 50%; /* Makes the button take up half the top nav space */
            min-width: 140px;
            order: 1; 
        }
        .dropdown-content {
            /* Adjust positioning for better mobile visibility */
            right: auto;
            left: 0;
            min-width: 100%;
        }
        .theme-toggle {
            order: 3; 
        }
        
        .boxnext {
            flex-direction: column;
            margin: 20px 10px;
        }
        .purpose {
            border-right: none;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        .resource-section {
            margin: 20px auto;
        }
        .resource-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }
        .card {
            padding: 20px 10px;
        }
        
        /* Promo Banner Adjustments */
        .promo-banner {
            font-size: 0.9em;
        }
        .promo-banner .register-link {
            display: block;
            margin: 10px auto 0 auto;
            width: 90%;
        }

        /* Stats Grid Adjustment */
        .stats-grid {
            flex-direction: column;
            gap: 15px;
        }
        .stat-box h2 {
            font-size: 2.5em; 
        }
        
        /* Final Callout Adjustments */
        .final-callout {
            margin: 40px 10px 30px 10px;
            padding: 25px 15px;
        }
        .final-callout h2 {
            font-size: 1.6em;
        }
        .final-callout p {
            font-size: 1em;
        }
    }
    /* --- FIXED NAVIGATION ALIGNMENT --- */
.menuoption {
    display: flex;
    align-items: center; /* This centers everything vertically */
    gap: 12px;
    justify-content: flex-end;
}

.user-pill-container {
    display: flex;
    align-items: center; /* Aligns Avatar and Text button */
    background: rgba(148, 163, 184, 0.1);
    padding: 4px 12px;
    border-radius: 50px; /* Modern pill shape */
    border: 1px solid var(--color-accent);
    margin: 0 5px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    background-color: var(--color-accent);
    color: var(--color-bg-secondary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.9em;
    margin-right: 8px;
}

.but.welcome {
    padding: 5px 10px;
    margin: 0;
    font-size: 0.95em;
    /* Remove underline animation for the name pill to keep it clean */
}

.but.welcome::after {
    display: none; 
}

.logout-btn {
    color: #ef4444 !important; /* Professional Red */
    padding: 10px !important;
}

/* Adjust the dropdown button to match height */
.dropbtn {
    display: flex;
    align-items: center;
}

/* --- 1. ENTRANCE WRAPPER (Handles the "Coming In") --- */

:root {
    --primary-cyan: #00f2ff;
    --neon-purple: #bc13fe;
    --glass-bg: rgba(255, 255, 255, 0.03);
    --glass-border: rgba(255, 255, 255, 0.1);
    --text-main: #ffffff;
    --text-dim: #94a3b8;
}

/* 1. The Wrapper: Handles Centering & Entrance */
.tech-wrapper {
    display: flex;
    justify-content: center; /* Horizontal centering */
    align-items: center;     /* Vertical centering */
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    opacity: 0;
    transform: translateY(20px);
    animation: techEntry 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* 2. The Glass Banner: The Main Box */
.glass-banner {
    position: relative;
    width: 100%;
    max-width: 850px; /* Professional wide-box look */
    min-height: 160px;
    background: var(--glass-bg);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1px solid var(--glass-border);
    border-radius: 30px;
    padding: 40px;
    display: flex;
    justify-content: space-between; /* Spaces content and button */
    align-items: center;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    /* Stable floating animation */
    animation: techFloat 6s ease-in-out infinite alternate;
}

/* 3. Layout Sections */
.content-layer {
    display: flex;
    align-items: center;
    gap: 30px;
    z-index: 2;
}

.text-section {
    text-align: left;
}

/* 4. High-Tech Elements */
.system-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.65rem;
    letter-spacing: 2px;
    color: var(--primary-cyan);
    margin-bottom: 12px;
    font-weight: 700;
}

.system-status::before {
    content: '';
    width: 6px;
    height: 6px;
    background: var(--primary-cyan);
    border-radius: 50%;
    box-shadow: 0 0 10px var(--primary-cyan);
    animation: blink 1.5s infinite;
}

.text-section h2 {
    margin: 0;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.2;
}

.glint-text {
    background: linear-gradient(90deg, #fff, var(--primary-cyan), var(--neon-purple), #fff);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: glint 4s linear infinite;
}

.text-section p {
    color: var(--text-dim);
    margin: 10px 0 0 0;
    font-size: 1.1rem;
}

/* 5. Modern Button (The Cypro-Btn) */
.cypro-btn {
    position: relative;
    padding: 16px 32px;
    background: transparent;
    border: 1px solid var(--primary-cyan);
    color: var(--primary-cyan);
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: 1px;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 2;
    overflow: hidden;
}

.cypro-btn:hover {
    background: var(--primary-cyan);
    color: #000;
    box-shadow: 0 0 30px rgba(0, 242, 255, 0.4);
    transform: scale(1.05);
}

/* 6. Animations */
@keyframes techEntry {
    to { opacity: 1; transform: translateY(0); }
}

@keyframes techFloat {
    0% { transform: translateY(0); }
    100% { transform: translateY(-10px); }
}

@keyframes glint {
    to { background-position: 200% center; }
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Background mesh for that "High Tech" feel */
.mesh-gradient {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(circle at 10% 20%, rgba(0, 242, 255, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(188, 19, 254, 0.05) 0%, transparent 40%);
    z-index: 1;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .glass-banner {
        flex-direction: column;
        text-align: center;
        padding: 30px;
        gap: 25px;
    }
    .content-layer { flex-direction: column; gap: 15px; }
    .text-section { text-align: center; }
    .text-section h2 { font-size: 1.5rem; }
}

</style>
</head>
<body id="body">
    <div class="headercont">
        <div class="title">
            <h1><img src="images/menu2.png" class="menu-icon" onclick="toggleMenu()" alt="Menu">
                EDUZONE EDUCATIONALS <span class="pro-tag">PRO</span></h1>
        </div>
        <div class="menuoption" id="menuoption">
    <div class="dropdown" id="materialsDropdown" onclick="toggleDropdown(event)">
        <button class="but dropbtn">LEARNING MATERIALS <i class="fas fa-chevron-down" style="font-size: 0.7em; margin-left: 5px;"></i></button>
        <div class="dropdown-content" id="dropdownContent">
            <a href="pastpapers.php">Past Papers</a>
            <a href="e-books.php">E-BOOKS</a>
            <a href="modelpapers.php">Model Papers</a>
            <a href="videolessons.php">Lesson Videos</a>
            <a href="ictsoft.php">Softwares</a>
        </div>
    </div>

    <button onclick="window.location.href='home.php'" class="but active">HOME</button>
    
    <?php if ($isLoggedIn): ?>
        <div class="user-pill-container">
            <div class="user-avatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
            <button onclick="window.location.href='account.php'" class="but welcome">
                Hi, <?php echo $userName; ?>
            </button>
        </div>
        <button onclick="window.location.href='logout.php'" class="but logout-btn" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    <?php else: ?>
        <button onclick="window.location.href='login.html'" class="but">Login</button>
        <button onclick="window.location.href='register.html'" class="but register-pro">Join Pro</button>
    <?php endif; ?>
    
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle Dark Mode">
        <span class="icon" role="img" aria-label="Theme Icon"></span>
    </button>
</div>
    </div>
    
    <nav class="side-menu" id="sideMenu">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleMenu()"> &times; </a>
        <?php if($isLoggedIn): ?>
            <div class="side-pro-box">
                <p>Welcome, <strong><?php echo $userName; ?></strong></p>
                <small>Pro Member since 2025</small>
            </div>
            <a href="progress.php"><i class="fas fa-chart-line"></i> Study Tracker</a>
            <a href="saved.php"><i class="fas fa-bookmark"></i> Saved Items</a>
        <?php endif; ?>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
    </nav>
    
    <div class="mainbox">
        
                <?php if($isLoggedIn): ?>
        <div class="tech-wrapper">
    <div class="glass-banner">
        <div class="mesh-gradient"></div>
        
        <div class="content-layer">
            <div class="icon-section">
                <div class="pulse-ring"></div>
                <i class="fas fa-microchip"></i>
            </div>
            
            <div class="text-section">
                <span class="system-status">SYSTEM READY • ONLINE</span>
                <h2>Welcome, <span class="glint-text"><?php echo $userName; ?></span></h2>
                <p>3 modules in <b style="color: #91ff00ff;">ICT Systems</b> require your attention.</p>
            </div>
        </div>

        <div class="action-section">
            <button onclick="window.location.href='videolessons.php'" class="cypro-btn">
                <span class="btn-content">RESUME SESSION</span>
                <span class="btn-glitch"></span>
            </button>
        </div>
    </div>
</div>
        <?php else: ?>
            <section class="final-callout">
                <h2>🔥 Ready to Conquer Your A/L Exam?</h2>
                <p>Join the thousands of successful students who trust Eduzone. Don't leave your exam preparation to chance—get the structure and resources you need today!</p>
                <a href="register.html" class="callout-button">Start Your Free Registration!</a>
            </section>
        <?php endif; ?>

        <div class="resource-section">
            <h2>📚 Explore Our Core Resources</h2>
            <div class="resource-grid">
                
                <div class="card animated-entry" id="papers-card" style="animation-delay: 0.3s;" onclick="window.location.href='pastpapers.php'">
                    <span class="card-icon">📂</span>
                    <h3>Past Papers</h3>
                    <p>Download categorized official A/L past papers (2015-Current) for practice.</p>
                    <a href="pastpapers.php">View Papers &rarr;</a>
                </div>

                <div class="card animated-entry" id="videos-card" style="animation-delay: 0.4s;" onclick="window.location.href='videolessons.php'">
                    <span class="card-icon">▶️</span>
                    <h3>Video Lessons</h3>
                    <p>Access curated video tutorials covering challenging theories and concepts.</p>
                    <a href="videolessons.php">Watch Videos &rarr;</a>
                </div>

                <div class="card animated-entry" id="books-card" style="animation-delay: 0.5s;" onclick="window.location.href='e-books.php'">
                    <span class="card-icon">📘</span>
                    <h3>E-Books</h3>
                    <p>Download official textbooks, manuals, and condensed study guides.</p>
                    <a href="e-books.php">Get E-Books &rarr;</a>
                </div>

                <div class="card animated-entry" id="models-card" style="animation-delay: 0.6s;" onclick="window.location.href='modelpapers.php'">
                    <span class="card-icon">📝</span>
                    <h3>Model Papers</h3>
                    <p>Test your preparedness with our annual series of timed mock exams.</p>
                    <a href="modelpapers.php">Attempt Models &rarr;</a>
                </div>

                <div class="card animated-entry" id="quizzes-card" style="animation-delay: 0.4s;" onclick="window.location.href='quizzes.php'">
                    <span class="card-icon">🧠</span>
                    <h3>Quiz Arena</h3>
                    <p>Test your knowledge with timed, subject-specific MCQ challenges and track scores.</p>
                    <a href="quizzes.php">Start Attempt &rarr;</a>
                </div>
                
                <div class="card animated-entry" id="software-card" style="animation-delay: 0.7s;" onclick="window.location.href='ictsoft.php'">
                    <span class="card-icon">💻</span>
                    <h3>ICT Software</h3>
                    <p>Access links to essential coding, graphics, and productivity software tools.</p>
                    <a href="ictsoft.php">Explore Tools &rarr;</a>
                </div>

            </div>
        </div>
        <div class="stats-section">
            <h2>Global Eduzone Stats</h2>
            <div class="stats-grid">
                <div class="stat-box animated-entry">
                    <h2>12,500+</h2>
                    <p>Total Learners</p>
                </div>
                <div class="stat-box animated-entry">
                    <h2>850+</h2>
                    <p>Pro Resources</p>
                </div>
                <div class="stat-box animated-entry pro-stat">
                    <h2><?php echo $isLoggedIn ? '1st' : 'Rank'; ?></h2>
                    <p><?php echo $isLoggedIn ? 'Your Island Rank' : 'Sign in to see Rank'; ?></p>
                </div>
            </div>
        </div>
        </div>

    <section class="comments-section">
        <h3>Share Your Feedback</h3>
        <form class="comment-form" action="submit_feedback.php" method="POST">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name">

            <label for="email">Your Email (Will not be published)</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email address">

            <label for="comment">Comment</label>
            <textarea id="comment" name="comment" required placeholder="Your feedback helps us improve..."></textarea>

            <button type="submit" class="submit-button">Submit Feedback</button>
        </form>
    </section>

    <?php
        if (isset($_SESSION['feedback_message'])) {
            $message = $_SESSION['feedback_message'];
            $status = $_SESSION['feedback_status'];
            
            // Clear the session variables immediately
            unset($_SESSION['feedback_message']);
            unset($_SESSION['feedback_status']);
            
            // Output the message with dynamic styling
            echo "<div class='feedback-alert $status'>$message</div>";
        }
        ?>

    <section class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h4>Eduzone Centre</h4>
                <p>The definitive resource for A/L students across ICT, Maths, and Science streams. Turning complex exams into achievable goals.</p>
            </div>
            
            <div class="footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="pastpapers.php">Past Papers</a></li>
                    <li><a href="e-books.php">E-Books</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Contact Info</h4>
                <p>Email: support@eduzone.lk</p>
                <p>Phone: +94 11 234 5678</p>
                <p>Location: Colombo, Sri Lanka</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            &copy; 2025 Eduzone Education Centre. All Rights Reserved.
        </div>
    </section>

    <script>
        function toggleMenu() {
            const sideMenu = document.getElementById("sideMenu");
            sideMenu.classList.toggle("open");
        }
        
        /* ---------------------------------------------------- */
        /* DROPDOWN JAVASCRIPT (Toggle on click, close on outside click) */
        /* ---------------------------------------------------- */
        function toggleDropdown(event) {
            event.stopPropagation(); // Prevents click from immediately reaching window listener
            const dropdown = document.getElementById("materialsDropdown");
            const content = document.getElementById("dropdownContent");
            
            const isShowing = content.classList.contains('show');

            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-content').forEach(c => {
                c.classList.remove('show');
                c.closest('.dropdown').classList.remove('active');
            });

            // Toggle the clicked one
            if (!isShowing) {
                content.classList.add('show');
                dropdown.classList.add('active');
            }
        }

        // Close the dropdown if the user clicks anywhere outside of it
        window.onclick = function(event) {
            if (!event.target.closest('.dropdown')) {
                const dropdowns = document.querySelectorAll(".dropdown-content");
                dropdowns.forEach(content => {
                    if (content.classList.contains('show')) {
                        content.classList.remove('show');
                        content.closest('.dropdown').classList.remove('active');
                    }
                });
            }
        }
        
        /* ---------------------------------------------------- */
        /* THEME TOGGLE JAVASCRIPT */
        /* ---------------------------------------------------- */
        const body = document.getElementById('body');
        const darkThemeClass = 'dark-theme';

        function setTheme(isDark) {
            if (isDark) {
                body.classList.add(darkThemeClass);
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove(darkThemeClass);
                localStorage.setItem('theme', 'light');
            }
        }

        function toggleTheme() {
            const isDark = body.classList.contains(darkThemeClass);
            setTheme(!isDark);
        }

        (function() {
            const savedTheme = localStorage.getItem('theme');
            let isDark = false;

            if (savedTheme === 'dark') {
                isDark = true;
            } else if (savedTheme === 'light') {
                isDark = false;
            } else {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    isDark = true;
                }
            }
            
            if (isDark) {
                body.classList.add(darkThemeClass);
            }
        })();
    </script>
</body>
</html>