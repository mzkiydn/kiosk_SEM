<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
        session_start();
        if (!isset($_SESSION["User"])) {
            header("Location:login.php");
        }

        include 'includes/connect.php';
?>
    <p>Success In</p>
</body>
</html>