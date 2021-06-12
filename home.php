<?php
session_start();
//this is a test
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>  
    <title>Home</title>
</head>
<body>
    <div class="container pt-4">
        <h2>Welcome, <?php echo $_SESSION['firstName'] . " " . $_SESSION['lastName']; echo ($_SESSION['isAdmin'] == TRUE) ? ' (Admin)' : ''; ?>.</h2>
        <label class="pt-4">All products currently available:</label>
        <table class="table table-stripped">
            <thead>
                <tr>
                    <th scope="col">Item ID</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Item Price Per Day</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM items WHERE available = TRUE";
                $result = $conn->query($sql);
                if($result !== FALSE) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<th scope="row">' . $row['itemID'] . '</th>';
                        echo "<td>{$row['brand']} {$row['category']}</td>";
                        echo '<td>$' . $row['pricePerDay'] . '</td>';
                        echo '<td>';
                        echo '<a href="./item-details.php?itemID=' . $row['itemID'] . '">Details</a>';
                        echo ' | <a href="./item-hire.php?itemID=' . $row['itemID'] . '">Hire</a>';
                        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == TRUE) {
                            echo ' | <a href="./item-update.php?itemID=' . $row['itemID'] . '">Update</a>';
                            echo ' | <a href="./item-delete.php?itemID=' . $row['itemID'] . '">Delete</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo "Error: {$conn->errno}  {$conn->error}";
                }
                ?>
            </tbody>
        </table>
        <label class="pt-4">All products you are currently hiring:</label>
        <table class="table table-stripped">
            <thead>
                <tr>
                    <th scope="col">Item ID</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Days Left</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT startDate, endDate FROM itemTransactions WHERE ";

                $sql = "SELECT * FROM items WHERE userID = {$_SESSION['userID']}";
                $result = $conn->query($sql);
                if($result !== FALSE) {
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<th scope="row">' . $row['itemID'] . '</th>';
                        echo "<td>{$row['brand']} {$row['category']}</td>";
                        
                        //get days left on hire
                        $newSql = "SELECT startDate, endDate FROM itemTransactions WHERE userID = {$_SESSION['userID']} 
                        AND itemID = {$row['itemID']}";
                        $newResult = $conn->query($newSql);
                        $data = $newResult->fetch_assoc();
                        $start = new DateTime(date("Y-m-d"));
                        $end = new DateTime(date($data['endDate']));
                        $interval = $start->diff($end);
                        $daysLeft = intval($interval->format('%d'));
                        echo "<td>{$daysLeft}</td>";
                        echo '<td>';
                        echo '<a href="./item-details.php?itemID=' . $row['itemID'] . '">Details</a> | ';
                        echo '<a href="./item-return.php?itemID=' . $row['itemID'] . '">Return</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo "Error: {$conn->errno}  {$conn->error}";
                }
                ?>
            </tbody>
        </table>
        <?php 
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == TRUE) {
            echo '<a href="./add-item.php" class="btn btn-success ml-2">Add another Item</a>';
        }
        ?>
        <div><p><a href="./logout.php">Log out</a></p></div>
    </div>
</body>
</html>