<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$days = $daysErr = "";

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

if(isset($_POST['itemID']) && !empty($_POST['itemID']) && isset($_POST['userID']) && !empty($_POST['userID'])){
    
    require_once("./config.php");

    $sql = "UPDATE items SET available = FALSE, userID = ?  WHERE itemID = ?";
    
    if($stmt = $conn->prepare($sql)) {
        
        $stmt->bind_param('ii', $paramUserID, $paramItemID);
        
        $paramUserID = intval(trim($_POST['userID']));
        $paramItemID = intval(trim($_POST['itemID']));
        
        
        if($stmt->execute()){

            $sql = "INSERT INTO itemTransactions (userID, itemID, startDate, endDate, active, daysOverdue, totalCost) 
            VALUES (?,?,?,?,?,?,?)";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('iissiii', $paramTransUserID, $paramTransItemID, $paramTransSD, $paramTransED, 
                $paramTransActive, $paramTransDaysOver, $paramTransCost);

                $paramTransUserID = intval(trim($_POST['userID']));
                $paramTransItemID = intval(trim($_POST['itemID']));
                
                //create date
                $paramTransSD = date("Y-m-d");
                $paramTransED = date("Y-m-d", strtotime("+{$_POST['days']}days"));
                $paramTransActive = TRUE;
                $paramTransDaysOver = 0;
                $paramTransCost = 0;

                //getting difference in dates then multiplying it by the cost of the item per day
                $date1 = new DateTime($paramTransSD);
                $date2 = new DateTime($paramTransED);
                $interval = $date1->diff($date2);
                $difference = intval($interval->format('%d'));
                
                //calculating the cost of hire
                $sql = "SELECT * FROM items WHERE itemID = {$_POST['itemID']}";
                $result = $conn->query($sql);
                if ($result != FALSE) {
                    $row = $result->fetch_assoc();
                    $paramTransCost = $difference * intval(trim($row['pricePerDay']));
                } else {
                    echo "Error: {$conn->errno}  {$conn->error} \n Couldnt find item.";
                    exit();
                }
                
                if ($stmt->execute()) {
                    header("location: home.php");
                    exit();
                } else {
                    echo "Error: {$conn->errno}  {$conn->error}";
                }
            }
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
    <title>Hire Confirmation</title>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-3">Hire Confirmation</h2>
                    <form action="./item-hire.php" method="post">
                        <div class="alert alert-success">
                            <input type="hidden" name="itemID" value="<?php echo trim($_GET['itemID']); ?>"/>
                            <input type="hidden" name="userID" value="<?php echo trim($_SESSION['userID']); ?>"/>
                            <div class="mb-3">
                                <label for="exampleInputDays" class="form-label">Number of days:</label>
                                <input type="text" class="form-control <?php echo (!empty($daysErr)) ? 'is-invalid' : ''; ?>" name="days" id="days" value="<?php echo $days; ?>">
                                <div class=invalid-feedback><?php echo $daysErr; ?></div>
                            </div>
                            <p>Are you sure you want to hire this item: ID<b><?php echo $_GET['itemID']; ?></b>?</p>
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