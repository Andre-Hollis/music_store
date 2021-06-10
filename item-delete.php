<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

//require("./config.php");

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

if(isset($_POST['itemID']) && !empty($_POST['itemID'])){
    
    require_once("./config.php");

    $sql = "DELETE FROM items WHERE itemID = {$_POST['itemID']}";
    
    if($stmt = $conn->prepare($sql)) {
        
        $stmt->bind_param('i', $paramItemID);
        
        $paramItemID = intval(trim($_POST['itemID']));
        
        if($stmt->execute()){
            
            header("location: home.php");
            exit();
        } else{
            echo "Error: {$conn->errno}  {$conn->error}";
        }
    }
    
    $stmt->close();
    
    $conn->close();
} else {

    if(empty(trim($_GET['itemID']))){
        
        echo '<a href="./home.php">Please select an item.</a>';
        exit();
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <title>Delete Item</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-3">Delete Item</h2>
                    <form action="./item-delete.php" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="itemID" value="<?php echo trim($_GET['itemID']); ?>"/>
                            <p>Are you sure you want to delete this item: ID<b><?php echo $_GET['itemID']; ?></b>?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
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