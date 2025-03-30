<?php
session_start();
error_reporting(0);
require('../config/db.php');
?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  </head>
  <body class="bg-gray-100 dark:bg-gray-900 overflow-hidden">

  <?php

  require('../config/db.php');

  if (isset($_REQUEST['username'])) {
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con, $username);
    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($con, $email);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $repeat_password = stripslashes($_REQUEST['repeat_password']);
    $repeat_password = mysqli_real_escape_string($con, $repeat_password);

    if ($_POST['otp'] != $_SESSION['otp']) {
      echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Invalid OTP. Please try again.</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click here to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../views/registration.php'>register</a> again.</h3>
              </div>
            </div>";
      exit();
    }  
    
    if ($password !== $repeat_password) {
      echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Password and confirm password are not matched.</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click here to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../views/registration.php'>register</a> again.</h3>
              </div>
            </div>";
    } else {
      $query2="SELECT * FROM `userdata` WHERE username='$username' or email='$email'";
      $result2=mysqli_query($con, $query2);

      if (mysqli_num_rows($result2) > 0) {
        echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Username or email is already taken.</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click here to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../views/registration.php'>register</a> again.</h3>
              </div>
            </div>";
      } else {
        $create_date = date("Y-m-d");
        $create_time = date("H:i:s");

        $query    = "INSERT into `userdata` (username, email, password, create_date, create_time) VALUES ('$username', '$email', '" . md5($password) . "', '$create_date', '$create_time')";
        $result   = mysqli_query($con, $query);
        $_SESSION['email'] = $email;
        if($result){
          echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>You are registered successfully.</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click here to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../views/login.php'>Login.</a></h3>
              </div>
            </div>";
        } else {
          echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Username or email is incorrect</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click here to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../views/registration.php'>register</a> again</h3>
              </div>
            </div>";
        }
      }
    }
  } else {
  ?>

<div class="h-screen w-screen flex justify-center items-center">

  <form class="w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800" action="" method="post">

    <h1 class="text-2xl font-semibold text-center mb-5 dark:text-white">Register</h1>
    <div class="relative z-0 w-full mb-5 group">
        <input type="text" name="username" id="username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="username" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
        <input type="email" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
        <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
        <input type="password" name="repeat_password" id="repeat_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
        <label for="repeat_password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm password</label>
    </div>
    <div class="grid md:grid-cols-2 md:gap-6">
      <div class="relative z-0 w-full mb-5 group">
          <input type="number" name="otp" id="otp" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
          <label for="otp" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Enter OTP</label>
      </div>
      <button id="verify" name="verify" class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send Mail</button>
    </div>
    <button type="submit" class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Register Now</button>
    <p class="text-white mt-5">Already have an account? <a class="text-blue-600 hover:underline dark:text-blue-500" href="../views/login.php">Login</a></p>

  </form>

  <?php
   }
?>

</div>

<script>

  document.getElementById("verify").addEventListener("click", function (event) {
      event.preventDefault();
      
      let email = document.getElementById("email").value;
      if (!email) {
          alert("Please enter your email first.");
          return;
      }

      fetch("../actions/send_otp.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `email=${encodeURIComponent(email)}`
      })
      .then(response => response.json())
      .then(data => {
          alert(data.message);
      })
      .catch(error => {
          console.error("Error:", error);
          alert("OTP send successfully. Check your Gmail.");
      });
  });

</script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  </body>
</html>