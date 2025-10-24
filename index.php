<?php
/**
 * Random Strip
 *
 * This script generates a random date between October 2, 1950 and February
 * 13, 2000. It also displays a random image from the 'images/long/' directory.
 * The images are assumed to be named in a sequential manner (1.jpg, 2.jpg, etc.).
 *
 * @package RandomStrip
 * @author  Sean Courtney
 * @license none
 */
session_start();
require_once 'configs.php';

$_SESSION = array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Strip</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/randomstrip.css">
</head>
<body>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <div class="container bodyText pt-5">
        <div class="row text-center">
            <h1 class="text-center">Random Strip of the Month</h1>
            <h4>(as of episode 126 and later)</h4>
        </div>
        <div class="row">
            <?php
                $directory = getcwd() . '/images/long/';
                $files = count(glob($directory . '*'));
                    echo '<img src="images/long/' . rand(1, $files) . '.jpg" alt="Random Strip" class="img-fluid"><br>';
            ?>
        </div>
    </div>
    <div class="container text-center bodyText pt-5">
        <div class="row">
            <form method="POST" action="random_strip.php" class="form-inline">
                <div class="form-group">
                    <div class="col">
                        <input type="checkbox" class="form-check-input" id="include_used" name="include_used">
                        <label class="form-check-label" for="include_used">Include used strips</label>
                    </div>
                    <div class="col pt-3">
                        <button id="generate" type="button" class="btn btn-primary float-right">Generate Random Date</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container pt-5" id="random_date_container">
        <div class="row">
            <div class="text-center bodyText" id="the_date"></div>
        </div>
        <div class="row submitRow">
            <form method="POST" action="record_strip.php">
                <div class="col text-center bodyText">
                    <button id="date_store" type="button" class="btn btn-warning">Click here to use the date!</button>
                </div>
                <input type="hidden" id="store_date" name="store_date" value="">
            </form>
        </div>
    </div>
    <div id="success_modal" class="modal" tabindex="-1" role="dialog" id="dateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bodyText">Date succesfully recorded!</h5>
                </div>
                <div class="modal-body snoopyGray text-center">
                        <img src="images/snoopydance.gif" alt="Snoopy Dance" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button id="yip" type="button" class="btn btn-secondary bodyText" data-dismiss="modal">yippee!</button>
                </div>
            </div>
        </div>
    </div>
    <div id="error_modal" class="modal" tabindex="-1" role="dialog" id="dateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bodyText">There was an error entering the date.</h5>
                </div>
                <div class="modal-body text-center">
                    <img src="images/angry-snoopy.gif" alt="Snoopy Dance" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button id="augh" type="button" class="btn bodyText btn-secondary" data-dismiss="modal">AAUGH!</button>
                </div>
            </div>
        </div>
    </div>
    <script src="js/randomstrip.js"></script>
    <?php
        // Database connectivity stuff here.
        $_SESSION['used_rows'] = array();
        $conn = new mysqli($db_host, $db_user, $db_password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Select all the rows from the 'used' table and store them in
        // Query everything from the 'used' table and store each row in
        // the $used_rows array.
        $sql = "SELECT * FROM rs_used";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['used_rows'][] = $row;
            }
        }
        $conn->close();
    ?>
</body>
</html>