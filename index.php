<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HireHub - Find Your Dream Job or Hire Top Talent</title>
    <link rel="stylesheet" href="./assets/index.css">
</head>
<body>
    <style>
        .testimonial-section {
            background: #0F172A;
            padding: 50px 0;
            text-align: center;
        }

        .testimonial-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .testimonial-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            text-align: center;
        }

        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .testimonial-card p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .testimonial-card h4 {
            font-size: 18px;
            color: #222;
            margin: 5px 0;
        }

        .testimonial-card span {
            font-size: 14px;
            color: #777;
        }
    </style>
    <!-- Navigation -->
    <nav>
        <div class="container nav-container">
            <a href="#hero" style="display: flex; align-items: center; text-decoration: none;">
                <img src="./assets/img/HireHub.png" alt="HireHub Logo" style="height: 40px; width: 40px; border-radius: 50%; margin-right: 10px;">
                <span style="font-size: 24px; color: white; font-weight: bold;">HireHub</span>
            </a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="#about" class="nav-link">About us</a></li>
                <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
                <li class="nav-item"><a href="#testimonials" class="nav-link">Blogs</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact Us</a></li>
                <style>
                    .nav-link {
                        position: relative;
                        text-decoration: none;
                        color: inherit;
                        transition: color 0.3s ease;
                    }

                    .nav-link::after {
                        content: '';
                        position: absolute;
                        left: 0;
                        bottom: -2px;
                        width: 0;
                        height: 2px;
                        background-color: #007BFF; /* Change to your desired color */
                        transition: width 0.3s ease;
                    }

                    .nav-link:hover::after {
                        width: 100%;
                    }
                </style>
                <li><a href="./views/login.php" class="cta-button" style="text-decoration: none;">Sign In</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1 class="brand-heading">Find Your Perfect Career Match</h1>
                <p class="hero-subtitle">Discover opportunities that align with your skills and aspirations through our AI-powered talent matching platform</p>
                <div class="hero-buttons">
                    <a href="./views/registration.php" class="btn btn-primary">Get Started</a>
                    <a href="howitworks.html" class="btn btn-secondary">How It Works</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="./assets/img/hiring.jpeg" alt="HireHub Platform Illustration" class="animated-image">
            </div>
            <style>
                .animated-image {
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-10px);
                    }
                }
            </style>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <h2 class="section-title">Reimagining Recruitment</h2>
            <p class="section-subtitle">Our platform connects talented individuals with forward-thinking companies through intelligent matching algorithms</p>
            <div class="about-content">
                <p>HireHub connects job seekers with employers using cutting-edge technology and AI-driven matching algorithms. Our mission is to revolutionize the hiring process by making it more efficient, transparent, and rewarding for everyone involved.</p>
                
                <p>We provide a user-friendly platform that enables candidates to create impressive profiles that showcase their skills and experience, while employers can post detailed job listings with ease. With advanced search filters and intelligent recommendations, finding the right job or candidate has never been simpler.</p>
                
                <p>HireHub ensures a smooth hiring experience with secure online interviews, real-time communication tools, and a transparent recruitment process. Our platform is designed to bridge the gap between talent and opportunity, making hiring faster and more efficient for everyone.</p>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="section" id="features">
        <div class="container">
            <h2 class="section-title">Cutting-Edge Features</h2>
            <p class="section-subtitle">Our platform offers innovative solutions for modern recruitment challenges</p>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🖥️</div>
                    <h3 class="feature-title">Screen Monitoring</h3>
                    <p class="feature-description">Advanced screen tracking technology ensures assessment integrity during remote interviews and tests.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">👁️</div>
                    <h3 class="feature-title">Face Detection</h3>
                    <p class="feature-description">AI-powered eye and facial movement tracking to maintain high standards during remote assessments.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">⚠️</div>
                    <h3 class="feature-title">Screen Out Counter</h3>
                    <p class="feature-description">Monitors and tracks any suspicious behavior during assessment sessions.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">⌨️</div>
                    <h3 class="feature-title">Online Compiler</h3>
                    <p class="feature-description">Built-in coding environment for technical assessments with support for multiple programming languages.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🖥️🖥️</div>
                    <h3 class="feature-title">Dual Monitor Detection</h3>
                    <p class="feature-description">Advanced technology to detect multiple monitor setups during secure assessment sessions.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🤖</div>
                    <h3 class="feature-title">AI-Matching</h3>
                    <p class="feature-description">Intelligent algorithms to connect the right candidates with their ideal employers.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonial-section" id="testimonials">
        <div class="container">
            <h2 class="section-title">What Our Users Say</h2>
            <p class="section-subtitle">The Testimonials from our happy clients</p>
            <div class="testimonial-container">
                <div class="feature-item" style="width: 350px;">
                    <img src="./assets/img/rohit.png" alt="User 1" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <p class="feature-description">"HireHub has completely revolutionized our interview process by streamlining every stage, from candidate screening to final selection. The advanced AI-powered features not only save us time but also enhance the accuracy of our hiring decisions."</p><br/>
                    <h4 class="feature-title">Rohit Debnath</h4>
                    <span>Ex Intern, Styflowne Finance Services Private Limited</span>
                </div>
                <div class="feature-item" style="width: 350px;">
                    <img src="./assets/img/Chirag.jpg" alt="User 2" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <p class="feature-description">"HireHub revolutionized our hiring process with its quick virtual interviews, seamless scheduling, and AI-driven insights. It saved us time, resources, and helped us find top talent effortlessly. A game-changer for smart recruitment!"</p><br/>
                    <h4 class="feature-title">Chirag Nahata</h4>
                    <span>CEO, Digidenone</span>
                </div>
                <div class="feature-item" style="width: 350px;">
                    <img src="./assets/img/Purba.jpg" alt="User 3" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <p class="feature-description">"HireHub is a modern recruitment platform designed to connect job seekers with employers efficiently. It streamlines the hiring process by offering AI-powered job matching, resume screening, and applicant tracking, with an intuitive interface and advanced analytics."</p><br/>
                    <h4 class="feature-title">Purba Saha</h4>
                    <span>Developer, Team Byte Gurus</span>
                </div>
                <div class="feature-item" style="width: 350px;">
                    <img src="./assets/img/Prinjal.jpg" alt="User 3" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                    <p class="feature-description">"Finding the right talent has never been easier! Hire Hub is a game-changer in recruitment, offering a seamless and efficient hiring process. The platform's built-in interview feature ensures that only the best candidates make it through, saving time and effort. The user-friendly interface and smart matching system make it a must-have for any employer looking to build a strong team. Highly recommended!"</p><br/>
                    <h4 class="feature-title">Prinjal Mistry</h4>
                    <span>Developer, Team Byte Gurus</span>
                </div>
            </div>
        </div>
    </section>
    
    
    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3 class="footer-heading">HireHub</h3>
                    <p>Revolutionizing the hiring process with technology and innovation.</p>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="jobs.html">Browse Jobs</a></li>
                        <li><a href="post-job.html">Post a Job</a></li>
                        <li><a href="./views/login.php">Login</a></li>
                        <li><a href="./views/registration.php">Sign Up</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Company</h3>
                    <ul class="footer-links">
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li><a href="privacy.html">Privacy Policy</a></li>
                        <li><a href="terms.html">Terms of Service</a></li>
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
                &copy; 2025 HireHub. All rights reserved.
            </div>
        </div>
    </footer>

<script src="./assets/index.js"></script>
</body>
</html>