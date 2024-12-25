<?php
include 'db_config.php';

$result = $conn->query("SELECT * FROM water_data ORDER BY id DESC LIMIT 1");
$data = $result ? $result->fetch_assoc() : null;

$status_result = $conn->query("SELECT * FROM system_status ORDER BY id DESC LIMIT 1");
$status_data = $status_result ? $status_result->fetch_assoc() : null;

$response = [
    'distance_cm' => $data ? $data['distance_cm'] : 0,
    'status' => $data ? $data['status'] : 'Unknown',
    'system_status' => $status_data ? $status_data['status'] : 'Unknown',
    'last_updated' => date("g:i:s A")
];

header('Content-Type: application/json');
echo json_encode($response);
?>