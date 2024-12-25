<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    $stmt = $conn->prepare("UPDATE system_status SET status = ? WHERE id = 1");
    $stmt->bind_param("s", $action);
    $stmt->execute();
    
    header("Location: index.php");
    exit();
}
?>