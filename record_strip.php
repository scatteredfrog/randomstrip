<?php
require_once 'configs.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Now let's see if there's a "fun fact" for this date, from the "rs_funfacts" table.
    $conn = new mysqli($db_host, $db_user, $db_password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $date = date('Y-m-d', strtotime($_POST['store_date']));

    $reason = "Chosen on " . date('F d, Y');
    $stmt = $conn->prepare("INSERT INTO rs_used (date, reason) VALUES (?, ?)");
    $stmt->bind_param("ss", $date, $reason);
    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Date recorded successfully.');
    } else {
        $response = array('status' => 'error', 'message' => 'Error recording date: ' . $stmt->error);
    }
    $stmt->close();
    $conn->close();
    echo json_encode($response);
}
