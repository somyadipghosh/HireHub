<?php
session_start();
error_reporting(0);
require('./config/db.php');
include("./config/auth_session.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to HireHub</title>
    <link rel="stylesheet" href="dashboard.css"
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="container nav-container">
        <a href="#hero" style="display: flex; align-items: center; text-decoration: none;">
            <img src="./assets/img/HireHub.png" alt="HireHub Logo" style="height: 40px; width: 40px; border-radius: 50%; margin-right: 10px;">
            <span style="font-size: 24px; color: white; font-weight: bold;">HireHub</span>
        </a>
            <ul class="nav-menu">
                <li><a href="#resources" class="nav-link">Resources</a></li>
                <li><a href="https://outlook.office365.com/book/MeetHub@chiragnahata.onmicrosoft.com/" class="nav-link">Book an Interview</a></li>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle">Settings</a>
                    <ul class="dropdown-menu">
                        <li><a href="./user/profile.php" class="dropdown-item">Profile</a></li>
                        <li><a href="./user/verifypassword.php" class="dropdown-item">Update Password</a></li>
                    </ul>
                </li>
                <style>
                .dropdown {
                position: relative;
                display: inline-block;
                }

                .dropdown-toggle {
                text-decoration: none;
                padding: 10px 15px;
                display: flex;
                align-items: center;
                background-color: transparent;
                border: none;
                cursor: pointer;
                transition: color 0.3s;
                }

                /* Add a caret icon after dropdown toggle */
                .dropdown-toggle::after {
                content: '';
                display: inline-block;
                margin-left: 8px;
                border-top: 5px solid #333;
                border-right: 5px solid transparent;
                border-left: 5px solid transparent;
                vertical-align: middle;
                }


                /* Styling for the dropdown menu container */
                .dropdown-menu {
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 1000;
                display: none;
                min-width: 180px;
                padding: 8px 0;
                margin: 0;
                list-style: none;
                background-color: #1e293b;
                border: 1px solid rgba(0, 0, 0, 0.15);
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }

                /* Show dropdown menu when parent is hovered or has .show class */
                .dropdown:hover .dropdown-menu,
                .dropdown.show .dropdown-menu {
                display: block;
                }

                /* Styling for dropdown menu items */
                .dropdown-item {
                display: block;
                width: 100%;
                padding: 8px 16px;
                clear: both;
                font-weight: normal;
                color:#DCF8C6;
                text-decoration: none;
                white-space: nowrap;
                background-color: transparent;
                border: 0;
                transition: background-color 0.2s, color 0.2s;
                }

                /* Hover and focus states for dropdown items */
                .dropdown-item:hover,
                .dropdown-item:focus {
                color:rgb(255, 255, 255);
                text-decoration: none;
                background-color:rgb(26, 35, 51);
                }

                /* Active state for dropdown items */
                .dropdown-item.active,
                .dropdown-item:active {
                color: #fff;
                text-decoration: none;
                background-color: #007bff;
                }

                /* Disabled state for dropdown items */
                .dropdown-item.disabled,
                .dropdown-item:disabled {
                color: #6c757d;
                pointer-events: none;
                background-color: transparent;
                }
                </style>
                <li>
                    <label class="switch">
                        <span class="sun"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="#ffd43b"><circle r="5" cy="12" cx="12"></circle><path d="m21 13h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm-17 0h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 0 2zm13.66-5.66a1 1 0 0 1 -.66-.29 1 1 0 0 1 0-1.41l.71-.71a1 1 0 1 1 1.41 1.41l-.71.71a1 1 0 0 1 -.75.29zm-12.02 12.02a1 1 0 0 1 -.71-.29 1 1 0 0 1 0-1.41l.71-.66a1 1 0 0 1 1.41 1.41l-.71.71a1 1 0 0 1 -.7.24zm6.36-14.36a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm0 17a1 1 0 0 1 -1-1v-1a1 1 0 0 1 2 0v1a1 1 0 0 1 -1 1zm-5.66-14.66a1 1 0 0 1 -.7-.29l-.71-.71a1 1 0 0 1 1.41-1.41l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.29zm12.02 12.02a1 1 0 0 1 -.7-.29l-.66-.71a1 1 0 0 1 1.36-1.36l.71.71a1 1 0 0 1 0 1.41 1 1 0 0 1 -.71.24z"></path></g></svg></span>
                        <span class="moon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="m223.5 32c-123.5 0-223.5 100.3-223.5 224s100 224 223.5 224c60.6 0 115.5-24.2 155.8-63.4 5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6-96.9 0-175.5-78.8-175.5-176 0-65.8 36-123.1 89.3-153.3 6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"></path></svg></span>   
                        <input type="checkbox" class="input">
                        <span class="slider"></span>
                    </label>
                </li>
                <li>
                    <a href="./views/logout.php" class="nav-link" id="logout-btn" style="background-color: red; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Logout</a>
                </li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-container">
            <div class="hero-content">
                <h3 class="brand-heading"><?php echo $_SESSION['username']; ?>,</h3>
                <h1 class="brand-heading">Welcome to HireHub!</h1>
            </div>
        </div>
    </section>

    <!-- Image Gallery Section -->
    <section class="image-gallery" id="resources">
        <div class="container">
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="./assets/img/online_interview.jpg" alt="Online Interview">
                    <div class="overlay">
                        <h3>Job Interviews</h3>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="./assets/img/ai_monitoring.jpg" alt="AI Monitoring">
                    <div class="overlay">
                        <h3>AI-Powered Face Tracking</h3>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="./assets/img/live_coding.jpg" alt="Live Coding">
                    <div class="overlay">
                         <a href="./user/onlinecompiler.php"><h3>Live Coding Tests</h3></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <h2 class="section-title">About</h2>
            <div class="about-content">
                <p>In today's fast-paced hiring landscape, conducting online interviews comes with its own challenges—ensuring integrity, preventing distractions, and maintaining focus. HireHub is designed to revolutionize the virtual interview process by tracking attendee behavior, monitoring screen activity, and providing a secure, seamless experience for recruiters and hiring managers.</p>
                
                <p>With cutting-edge AI-driven face and screen tracking, along with an integrated online compiler, HireHub ensures fair and transparent interview assessments.</p>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <h2 class="section-title">Features</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🖥️</div>
                    <h3 class="feature-title">Screen Monitor</h3>
                    <p class="feature-description">Tracks screen activity to detect distractions or unauthorized actions.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">👁️</div>
                    <h3 class="feature-title">Face Detection</h3>
                    <p class="feature-description">Monitors eye movement and facial expressions to ensure attentiveness.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">⚠️</div>
                    <h3 class="feature-title">Screen Out Counter</h3>
                    <p class="feature-description">Logs the number of times an attendee moves away from the interview screen.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">⌨️</div>
                    <h3 class="feature-title">Inbuilt Online Compiler</h3>
                    <p class="feature-description">Enables live coding tests within the platform.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🖥️🖥️</div>
                    <h3 class="feature-title">Dual Monitor Check</h3>
                    <p class="feature-description">Detects additional screens to prevent unfair practices.</p>
                </div>
            </div>
            
            <div class="cta-content">
                <p class="cta-text">Get started today and redefine the future of online interviews with HireHub!</p>
            </div>
        </div>
    </section>
    
    <a href="./chatbot/chatbot.html"><button class="chatbot-button">💬</button></a>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3 class="footer-heading">HireHub</h3>
                    <p>Empowering interviews with AI-driven tracking and monitoring.</p>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="../binaryhackathon/index.php">Home</a></li>
                        <li><a href="#features">Features</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Contact</h3>
                    <ul class="footer-links">
                    <li>Email: <span onclick="window.location.href='mailto:hirehubbusiness@gmail.com'" style="cursor: pointer;">hirehubbusiness@gmail.com</span></li>
                        <li>Phone: <span onclick="window.location.href='tel:+918100463033'" style="cursor: pointer;">+91 8100463033</span></li>
                        <li>Address: JIS College,<br>Kalyani, Nadia</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                &copy; 2025 HireHub. All Rights Reserved.
            </div>
        </div>
    </footer>
    

    <script src="dashboard.js"></script>
</body>
</html>
