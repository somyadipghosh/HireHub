<?php
session_start();
error_reporting(0);
require('../config/db.php');
include("../config/auth_session.php");
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['username']; ?>'s Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap');

        /* Base Styles and Variables */
        :root {
        /* Light Theme Colors */
        --primary: #6366f1;
        --primary-hover: #4f46e5;
        --accent: #f97316;
        --light-bg: #ffffff;
        --light-card-bg: #f1f5f9;
        --light-text: #000000; /* New darker text color for light mode */
        --dark-text: #0f172a; /* Kept for dark mode */
        --light-text-secondary: #64748b;
        --light-border-color: rgba(15, 23, 42, 0.1);
        --light-shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
        --light-shadow-md: 0 4px 10px rgba(0, 0, 0, 0.08);
        --light-shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
        
        /* Dark Theme Colors */
        --dark-primary: #6366f1;
        --dark-primary-hover: #4f46e5;
        --dark-accent: #f97316;
        --dark-bg: #0f172a;
        --dark-card-bg: #1e293b;
        --dark-text: #f8fafc;
        --dark-text-secondary: #94a3b8;
        --dark-border-color: rgba(255, 255, 255, 0.08);
        --dark-shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.2);
        --dark-shadow-md: 0 4px 10px rgba(0, 0, 0, 0.25);
        --dark-shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.3);
        
        /* Active Theme Colors (default to light) */
        --bg-color: var(--light-bg);
        --card-bg: var(--light-card-bg);
        --text-color: var(--light-text); /* Changed to use light-text for light mode */
        --text-secondary: var(--light-text-secondary);
        --border-color: var(--light-border-color);
        --shadow-sm: var(--light-shadow-sm);
        --shadow-md: var(--light-shadow-md);
        --shadow-lg: var(--light-shadow-lg);
        
        /* Shared Variables */
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --transition: all 0.3s ease;
        --section-padding: 5rem 0;
        }

        .dark-mode {
        --bg-color: var(--dark-bg);
        --card-bg: var(--dark-card-bg);
        --text-color: var(--dark-text);
        --text-secondary: var(--dark-text-secondary);
        --border-color: var(--dark-border-color);
        --shadow-sm: var(--dark-shadow-sm);
        --shadow-md: var(--dark-shadow-md);
        --shadow-lg: var(--dark-shadow-lg);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #1e293b;
            color: #f8fafc;
            line-height: 1.6;
        }

        .container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
        }

        a {
        text-decoration: none;
        color: var(--primary);
        transition: var(--transition);
        }

        a:hover {
        color: var(--primary-hover);
        }

        ul {
        list-style: none;
        }

        nav {
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        transition: var(--transition);
        backdrop-filter: blur(16px);
        background: rgba(15, 23, 42, 0.7);
        border-bottom: 1px solid var(--border-color);
        }

        .nav-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 24px;
        }

        .logo {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--text-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        }
        
        .logo::before {
        content: "";
        display: inline-block;
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        border-radius: 6px;
        }

        .nav-menu {
        display: flex;
        align-items: center;
        gap: 32px;
        margin-left: 460px;
        }

        .nav-link {
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 15px;
        padding: 6px 2px;
        transition: var(--transition);
        }

        .nav-link:hover {
        color: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 120px;
        }
        
        .profile-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #f1f5f9;
        }
        
        .profile-header p {
            color: #94a3b8;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #334155;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #e2e8f0;
        }
        
        .profile-card {
            background-color: #334155;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .profile-card h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #f1f5f9;
            border-bottom: 1px solid #475569;
            padding-bottom: 10px;
        }
        
        .info-group {
            margin-bottom: 25px;
        }
        
        .info-group:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            display: block;
            font-size: 14px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        
        .info-value {
            font-size: 18px;
            color: #e2e8f0;
            padding: 12px;
            background-color: #1e293b;
            border-radius: 6px;
        }
        
        form .info-label {
            margin-top: 15px;
        }
        
        input {
            width: 100%;
            padding: 12px;
            background-color: #1e293b;
            border: 1px solid #475569;
            border-radius: 6px;
            color: #e2e8f0;
            font-size: 16px;
        }
        
        input:focus {
            outline: none;
            border-color: #60a5fa;
        }
        
        button {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2563eb;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #f8fafc;
        }

        .success-message {
            background-color: #065f46;
            color: #d1fae5;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            display: none;
        }
    </style>
</head>
<?php
if (isset($_POST['submit'])) {
    $new_email = stripslashes($_REQUEST['new-email']);
    $new_email = mysqli_real_escape_string($con, $new_password);
    $confirm_email = stripslashes($_REQUEST['confirm-email']);
    $confirm_email = mysqli_real_escape_string($con, $repeat_new_password);
    if ($new_email !== $confirm_email) {
        echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Emails are not matched.</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../user/profile.php'>change email</a> again.</h3>
              </div>
            </div>";
      } else {
        $query = "SELECT * FROM `userdata` WHERE email='$new_email'";
        $result = mysqli_query($con, $query);
        $rows= mysqli_num_rows($result);
        if ($rows > 1) {
            echo "<script>alert('This email is already in use. Try another one.'); window.history.back();</script>";
            exit();
        } else {
            $query1= "UPDATE `userdata` SET email='$new_email' WHERE username='" . $_SESSION['username'] . "'";
            $result1 = mysqli_query($con, $query1);
            echo "<script>alert('Email updated successfully!'); window.location.href='../user/profile.php';</script>";
        }
      }
} else {
?>
<body>
    <nav>
        <div class="container nav-container">
            <a href="#" class="logo">HireHub</a>
            <ul class="nav-menu">
                <li><a href="../dashboard.php" class="nav-link">Home</a></li>
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

    <div class="container">
        <div class="profile-header">
            <h1>Profile Information</h1>
            <p>View and update your account details</p>
        </div>
        
        <div class="profile-card">
            <h2>Account Information</h2>
            
            <div class="info-group">
                <span class="info-label">Username</span>
                <div class="info-value"><?php echo $_SESSION['username']; ?></div>
            </div>
            
            <div class="info-group">
                <span class="info-label">Email Address</span>
                <div class="info-value"><?php echo $email; ?></div>
            </div>
        </div>
        
        <div class="profile-card">
            <h2>Update Email</h2>
            <form id="email-form">
                <div class="info-group">
                    <label class="info-label" for="current-email">Current Email Address</label>
                    <input type="email" name="current-email" id="current-email" value="<?php echo $email; ?>" disabled>
                </div>
                
                <div class="info-group">
                    <label class="info-label" for="new-email">New Email Address</label>
                    <input type="email" id="new-email" name="new-email" placeholder="Enter new email address" required>
                </div>
                
                <div class="info-group">
                    <label class="info-label" for="confirm-email">Confirm New Email Address</label>
                    <input type="email" id="confirm-email" name="confirm-email" placeholder="Confirm your new email address" required>
                </div>

                <div class="info-group">
                    <label class="info-label" for="otp">Enter OTP</label>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                        <input type="number" id="otp" placeholder="Enter your OTP" required style="flex: 1;">
                        <button type="button" id="send-otp" style="background-color: #3b82f6; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-size: 16px; flex-shrink: 0; transition: background-color 0.3s; margin-bottom:20px;">Send OTP</button>
                    </div>
                </div>
                
                <button type="submit">Update Email</button>
            </form>
        </div>
    </div>
    <script>
    document.getElementById("send-otp").addEventListener("click", function() {
    const newEmail = document.getElementById("new-email").value;

    if (!newEmail) {
        alert("Please enter a new email address before requesting OTP.");
        return;
    }

    fetch("../actions/sendotp_emailupdate.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `email=${encodeURIComponent(newEmail)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("OTP has been sent to your email.");
        } else {
            alert("Failed to send OTP. Please try again.");
        }
    })
    .catch(error => {
        console.error("Error sending OTP:", error);
        alert("Something went wrong. Try again later.");
    });
});
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
<?php } ?> 
</html>