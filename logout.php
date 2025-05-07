<?php
    session_start();
    unset($_SESSION["User"]); 
    unset($_SESSION["Role"]); 
    session_destroy();
    header("Location:login.php");
?>