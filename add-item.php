<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();
require("./config.php");

if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== TRUE) {
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

$category = $categoryErr = "";
$brand = $brandErr = "";
$year = $yearErr = "";
$features = "";
$price = $priceErr = "";
$overduePrice = $overduePriceErr = "";
$available = "";
$userID = $userIDErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty(trim($_POST['category']))) {
        $categoryErr = "Please enter a catgory.";
    } else {
        $category = trim($_POST['category']);
    }

    if (empty(trim($_POST['brand']))) {
        $brandErr = "Please enter a brand.";
    } else {
        $brand = trim($_POST['brand']);
    }

    if (empty(trim($_POST['year'])) || intval(trim($_POST['year'])) < 1900 || intval(trim($_POST['year'])) > 2021) {
        $yearErr = "Please enter a year (1900 - 2021).";
    } else {
        $year = trim($_POST['year']);
    }

    if (empty(trim($_POST['features']))) {
        $featuresErr = "Please enter a description.";
    } else {
        $features = trim($_POST['features']);
    }

    if (empty(trim($_POST['price'])) || trim($_POST['price']) < 1 || !is_numeric(trim($_POST['price'])) || !preg_match('/^[0-9]+$/', trim($_POST['price']))) {
        $priceErr = "Please enter a number greater than $1.";
    } else {
        $price = trim($_POST['price']);
    }

    if (empty(trim($_POST['overduePrice'])) || trim($_POST['overduePrice']) < 1 || !is_numeric($_POST['overduePrice']) || !preg_match('/^[0-9]+$/', $_POST['overduePrice'])) {
        $overduePriceErr = "Please enter a number greater than $1.";
    } else {
        $overduePrice = trim($_POST['overduePrice']);
    }

    if (!empty(trim($_POST['userID'])) && preg_match('/^[0-9]+$/', $_POST['userID']) && intval(trim($_POST['userID'])) >= 1) {
        $userID = trim($_POST['userID']);
        $available = FALSE;
    } else if (!empty(trim($_POST['userID'])) && !preg_match('/^[1-9]+$/', $_POST['userID']) && intval(trim($_POST['userID'])) < 1) {
        $userIDErr = "Enter an ID.";
    } else {
        $available = TRUE;
        $userID = NULL;

    }

    if(empty($categoryErr) && empty($brandErr) && empty($yearErr) && empty($priceErr) && empty($overduePriceErr) && empty($userIDErr)){
    
        $sql = "INSERT INTO items (category, brand, yearMade, features, pricePerDay, overduePrice, available, userID)
        VALUES (?,?,?,?,?,?,?,?)";
        
        if($stmt = $conn->prepare($sql)) {
            
            $stmt->bind_param('ssssiiii', $paramCategory, $paramBrand, $paramYear, $paramFeatures, $paramPrice, $paramOP, $paramAvailable, $paramUserID);
            
            $paramCategory = $category;
            $paramBrand = $brand;
            $paramYear = $year;
            $paramFeatures = $features;
            $paramPrice = $price;
            $paramOP = $overduePrice;
            $paramAvailable = $available;
            $paramUserID = $userID;
            
            if($stmt->execute()){
                
                header("location: home.php");
                exit();
            } else{
                echo "Error: {$conn->errno}  {$conn->error}";
            }
            $stmt->close();
        }
    } 
} else {
    $sql = "SELECT * FROM items WHERE itemID = {$_GET['itemID']}";
    $result = $conn->query($sql);
    if ($result !== FALSE) {
        $row = $result->fetch_assoc();
        
        $category = $row['category'];
        $brand = $row['brand'];
        $year = $row['yearMade'];
        $features = $row['features'];
        $price = $row['pricePerDay'];
        $overduePrice = $row['overduePrice'];
        $available = $row['available'];
        $userID = $row['userID'];

    } else {
        echo "Error: {$conn->errno}  {$conn->error}";
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
    <title>Add Item</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-3">Add Item</h2>
                    <form action="./add-item.php" method="post">
                        <div class="alert alert-secondary">
                            <input type="hidden" name="itemID" value="<?php echo trim($_GET['itemID']); ?>"/>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">Category</label>
                                <input type="text" class="form-control <?php echo (!empty($categoryErr)) ? 'is-invalid' : ''; ?>" name="category" id="lName" value="<?php echo $category; ?>">
                                <div class=invalid-feedback><?php echo $categoryErr; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">Brand</label>
                                <input type="text" class="form-control <?php echo (!empty($brandErr)) ? 'is-invalid' : ''; ?>" name="brand" id="lName" value="<?php echo $brand; ?>">
                                <div class=invalid-feedback><?php echo $brandErr; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">Year</label>
                                <input type="text" class="form-control <?php echo (!empty($yearErr)) ? 'is-invalid' : ''; ?>" name="year" id="lName" value="<?php echo $year; ?>">
                                <div class=invalid-feedback><?php echo $yearErr; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">Features</label>
                                <input type="text" class="form-control <?php echo (!empty($featuresErr)) ? 'is-invalid' : ''; ?>" name="features" id="lName" value="<?php echo $features; ?>">
                                <div class=invalid-feedback><?php echo $featuresErr; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">Price per day ($1 or more)</label>
                                <input type="text" class="form-control <?php echo (!empty($priceErr)) ? 'is-invalid' : ''; ?>" name="price" id="lName" value="<?php echo $price; ?>">
                                <div class=invalid-feedback><?php echo $priceErr; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">Over-due price per day ($1 or more)</label>
                                <input type="text" class="form-control <?php echo (!empty($overduePriceErr)) ? 'is-invalid' : ''; ?>" name="overduePrice" id="lName" value="<?php echo $overduePrice; ?>">
                                <div class=invalid-feedback><?php echo $overduePriceErr; ?></div>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputLastName1" class="form-label">User renting</label>
                                <input type="text" class="form-control <?php echo (!empty($userIDErr)) ? 'is-invalid' : ''; ?>" name="userID" id="lName" value="<?php echo $userID; ?>">
                                <div class=invalid-feedback><?php echo $userIDErr; ?></div>
                            </div>
                            <p>Are you sure you want to make this item?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-success">
                                <a href="./home.php" class="btn btn-secondary ml-2">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>