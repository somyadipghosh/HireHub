<?php
session_start();
error_reporting(0);
require('../config/db.php');
$username=$_SESSION['username'];
$old_password=$_SESSION['password'];
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
        $new_password = stripslashes($_REQUEST['new_password']);
        $new_password = mysqli_real_escape_string($con, $new_password);
        $repeat_new_password = stripslashes($_REQUEST['repeat_new_password']);
        $repeat_new_password = mysqli_real_escape_string($con, $repeat_new_password);
        if ($new_password !== $repeat_new_password) {
          echo "<div class='h-screen w-screen flex justify-center items-center'>
                <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                  <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>New password and confirm new password are not matched.</h1><br/>
                  <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../user/resetpassword.php'>reset password</a> again.</h3>
                </div>
              </div>";
        } else {
          $query = "UPDATE `userdata` SET password='" . md5($new_password) . "' WHERE username='$username' AND password='" . md5($old_password) . "'";
          $result = mysqli_query($con, $query);
          if ($result) {
            echo "<div class='h-screen w-screen flex justify-center items-center'>
                  <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                    <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Password changed successfully.</h1><br/>
                    <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../views/login.php'>login</a>.</h3>
                  </div>
                </div>";
          } else {
            echo "<div class='h-screen w-screen flex justify-center items-center'>
                  <div class='w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800'>
                    <h1 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Password not changed.</h1><br/>
                    <h3 class='text-2xl font-semibold text-center mb-5 dark:text-white'>Click to <a class='text-blue-600 hover:underline dark:text-blue-500' href='../user/resetpassword.php'>reset password</a> again.</h3>
                  </div>
                </div>";
          }
        }
      }
      else {
    ?>
  <div class="h-screen w-screen flex justify-center items-center">

<form class="w-120 mx-auto mt-10 rounded-lg bg-grey-700 p-10 dark:bg-gray-800" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

  <h1 class="text-2xl font-semibold text-center mb-5 dark:text-white">Credentials has been verified, please change your password.</h1>
  <div class="relative z-0 w-full mb-5 group">
      <input type="password" name="new_password" id="new_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="new_password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">New Password</label>
  </div>
  <div class="relative z-0 w-full mb-5 group">
      <input type="password" name="repeat_new_password" id="repeat_new_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
      <label for="repeat_new_password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm New Password</label>
  </div>
  <input type="submit" value="Change your password" name="submit" class="mt-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

</form>

</div>

<?php
  }
?>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>