<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) {
    header("location: ./home.php");
    exit;
}

require_once("./config.php");

$email = $emailErr = "";
$password = $passwordErr = "";
$loginErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    //check if email field is empty, raise warning if it is
    if (empty(trim($_POST['email']))) {
        $emailErr = "Please enter your email.";
    } else {
        $email = $_POST['email'];
    }

    //check if password field is empty, raise warning if it is
    if (empty(trim($_POST['password']))) {
        $passwordErr = "Please enter your password.";
    } else {
        $password = $_POST['password'];
    }

    //check for no errors with email and password field
    if (empty($emailErr) && empty($passwordErr)) {
        
        $sql = "SELECT * FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            
            $stmt->bind_param('s', $emailParam);
            $emailParam = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows() == 1) {

                    $stmt->bind_result($_id, $_firstName, $_lastName, $_email, $_phoneNum, $_password, $_isAdmin);

                    if ($stmt->fetch()) {
                        if (SHA1($password) == $_password) {
                            
                            session_start();

                            //set session data
                            $_SESSION['loggedin'] = TRUE;
                            $_SESSION['userID'] = $_id;
                            $_SESSION['firstName'] = $_firstName;
                            $_SESSION['lastName'] = $_lastName;
                            $_SESSION['email'] = $_email;
                            $_SESSION['phoneNum'] = $_phoneNum;
                            $_SESSION['isAdmin'] = $_isAdmin;
                            
                            
                            //set timeout limit to 15 minutes
                            $_SESSION['start'] = time();
                            $_SESSION['expire'] = $_SESSION['start'] + (20 * 60);
                            
                            header("location: home.php");
                            
                        } else {
                            $loginErr = "Invalid username or password.";
                        }
                    }
                } else {
                    $loginErr = "Invalid username or password.";
                }
            } else {
                echo "Something went wrong.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<body>
    <div class="container pt-4">
        <h2>Login Portal</h2>
        <form action="./login.php" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-primary ml-2">Reset</button>
            <p>Don't have an account? <a href="./register.php">Register here.</a></p>
        </form>
    </div>
</body>
</html>