<?php
session_start();
error_reporting(0);
require('../config/db.php');
$username=$_SESSION['username'];
$password=$_SESSION['password'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
?>
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Forgot Password?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  </head>
  <body class="bg-gray-100 dark:bg-gray-900 overflow-hidden">
  <?php
  if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, stripslashes($_REQUEST['username']));
    $email = mysqli_real_escape_string($con, stripslashes($_REQUEST['email']));
    
    $query = "SELECT * FROM `userdata` WHERE username='$username' AND email='$email'";
    $result = mysqli_query($con, $query);
    $rows = mysqli_num_rows($result);
    
    if ($rows == 1) {
        $_SESSION['username'] = $username;
        
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp-relay.brevo.com';
            $mail->SMTPAuth = true;
            $mail->Username = '88a560001@smtp-brevo.com';
            $mail->Password = 'JWdmswArvkxBPH9a';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom('hirehubbusiness@gmail.com', 'HireHub');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "Your OTP for password reset is: $otp";
            
            $mail->send();
            header("Location: ../user/verifyotp.php");
        } catch (Exception $e) {
            echo "Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "<div class='h-screen w-screen flex justify-center items-center'>
              <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Username or email is incorrect.</h1><br/>
                <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'><a class='text-blue-600 hover:underline dark:text-blue-500' href='../user/verifypassword.php'>Click here</a> to try again.</h3>
              </div>
            </div>";
    }
  } else {
  ?>
  <div class="h-screen w-screen flex justify-center items-center">

<form class="w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

  <h1 class="text-2xl font-semibold text-center mb-5 dark:text-white">Verify your credentials</h1>
  <div class="relative z-0 w-full mb-5 group">
      <input type="username" name="username" id="username" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="username" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Username</label>
  </div>
  <div class="relative z-0 w-full mb-5 group">
      <input type="email" name="email" id="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email</label>
  </div>
  <input type="submit" value="Send OTP" name="submit" class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

</form>

</div>

<?php
  }
?>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>