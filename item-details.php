<?php
session_start();

require("./config.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
    header("location: login.php");
    exit;
} else {
    $now = time();

    if ($now > $_SESSION['expire']) {
        session_destroy();

        echo '<a href="./login.php">Your session expired. Click here to log in.</a>';
        exit;
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
</head>
<body>
    
</body>
</html>