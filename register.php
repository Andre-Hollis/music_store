<?php 
session_start();

require_once("./config.php");

$firstName = $firstNameErr  = "";
$lastName = $lastNameErr = "";
$email = $emailErr = "";
$phoneNum = $phoneErr = "";
$password = $passwordErr = "";
$passwordConfirm = $passwordConfirmErr = "";
$isAdmin = FALSE;
$isAdminErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (empty(trim($_POST['firstName']))) {
        $firstNameErr = "Please enter your first name.";
    } else {
       $firstName = trim($_POST['firstName']);
    }

    if (empty(trim($_POST['lastName']))) {
        $lastNameErr = "Please enter your last name.";
    } else {
       $lastName = trim($_POST['lastName']);
    }

    if (strlen(trim($_POST['phoneNum'])) !== 9 && !preg_match("(/^[0-9]+$/)", trim($_POST['phoneNum']))) {
        $phoneNumErr = "Please enter a phone number with at exactly 9 numbers.";
    } else {
       $phoneNum = trim($_POST['phoneNum']);
    }

    if (empty(trim($_POST['password']))) {
        $passwordErr = "Please enter password.";
    } else {
        if (strlen(trim($_POST['password'])) < 8) {
            $passwordErr = "Password needs to be at least 8 characters.";
        } else {
            $password = trim($_POST['password']);
        }
    }

    if (empty(trim($_POST['passwordConfirm']))) {
        $passwordConfirmErr = "Please confirm password.";
    } else {
        if (strlen(trim($_POST['passwordConfirm'])) < 8) {
            $passwordConfirmErr = "Password needs to be at least 8 characters.";
        } else {
            $passwordConfirm = trim($_POST['passwordConfirm']);
            if (empty($passwordErr) && ($password != $passwordConfirm)) {
                $passwordConfirmErr = "Password did not match.";
            }
        }
    }

    if (empty(trim($_POST['email']))) {
        $emailErr = "Enter am email.";
    } else {
        $sql = "SELECT userID FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            
            $stmt->bind_param('s', $emailParam);
            $emailParam = trim($_POST['email']);

            if($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $emailErr = "This email address is already in use.";
                } else {
                    $email = trim($_POST['email']);
                }
            } else {
                echo "Error: {$conn->errno} \n {$conn->error}";
            }
            $stmt->close();
        } else {
            echo "Error: {$conn->errno} \n {$conn->error}";
        }
    }

    if (empty($_POST['isAdmin'])) {
        $isAdmin = FALSE;
    } else if (!empty($_POST['isAdmin']) && $_POST['isAdmin'] != "AMgJc2ylJXrKIvVv1zBy7WSGKtRYefKB") {
        $isAdminErr = "Wrong code entered.";
    } else {
       $isAdmin = TRUE;
    }

    if (empty($firstNameErr) && empty($lastNameErr) && empty($emailErr) && empty($passwordErr) && empty($passwordConfirmErr) && empty($phoneNumErr) && empty($isAdminErr)) {
        $sql = "INSERT INTO users (firstName, lastName, email, phoneNumber, userPassword, isAdmin) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('sssssi', $firstNameParam, $lastNameParam, $emailParam, $phoneNumParam, $passwordParam, $isAdminParam);

            $firstNameParam = $firstName;
            $lastNameParam = $lastName;
            $emailParam = $email;
            $phoneNumParam = $phoneNum;
            $passwordParam = SHA1($password);
            $isAdminParam = intval($isAdmin);

            if($stmt->execute()) {
                header("location: ./login.php");
            } else {
                echo "Error: {$conn->errno} \n {$conn->error}";
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
    <title>Register</title>
</head>
<body>
    <div class="container pt-4">
        <h2>Register</h2>
        <form action="./register.php" method="post">
            <div class="mb-3">
                <label for="exampleInputFirstName1" class="form-label">First Name</label>
                <input type="text" class="form-control <?php echo (!empty($firstNameErr)) ? 'is-invalid' : ''; ?>" name="firstName" id="fName" value="<?php echo $firstName; ?>">
                <div class=invalid-feedback><?php echo $firstNameErr; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputLastName1" class="form-label">Last Name</label>
                <input type="text" class="form-control <?php echo (!empty($lastNameErr)) ? 'is-invalid' : ''; ?>" name="lastName" id="lName" value="<?php echo $lastName; ?>">
                <div class=invalid-feedback><?php echo $lastNameErr; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" name="email" id="email" aria-describedby="emailHelp" value="<?php echo $email; ?>">
                <div class=invalid-feedback><?php echo $emailErr; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPhone1" class="form-label">Phone number (9 numbers)</label>
                <input type="text" class="form-control <?php echo (!empty($phoneNumErr)) ? 'is-invalid' : ''; ?>" name="phoneNum" id="phone" value="<?php echo $phoneNum; ?>">
                <div class=invalid-feedback><?php echo $phoneNumErr; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>" name="password" id="password">
                <div class=invalid-feedback><?php echo $passwordErr; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleInputConfrimPassword1" class="form-label">Confirm Password</label>
                <input type="password" class="form-control <?php echo (!empty($passwordConfirmErr)) ? 'is-invalid' : ''; ?>" name="passwordConfirm" id="passwordConfirm">
                <div class=invalid-feedback><?php echo $passwordConfirmErr; ?></div>
            </div>
            <div class="mb-3">
                <label for="exampleAdmin1" class="form-label">Enter Admin code</label>
                <input type="text" class="form-control <?php echo (!empty($isAdminErr)) ? 'is-invalid' : ''; ?>" name="isAdmin" id="isAdmin">
                <div class=invalid-feedback><?php echo $isAdminErr; ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-primary ml-2">Reset</button>
            <p>Already have an account? <a href="./login.php">Login here.</a></p>
        </form>
    </div>
</body>
</html>