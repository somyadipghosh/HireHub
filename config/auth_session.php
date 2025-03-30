<?php
    session_start();
    if(!isset($_SESSION["username"])) {
        header("Location: ../binaryhackathon/views/login.php");
        exit();
    }
?>
