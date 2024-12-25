<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true); // Decode JSON menjadi array

    if (isset($data['level_air']) && isset($data['status_air'])) {
        $level_air = $data['level_air'];
        $status_air = $data['status_air'];

        $stmt = $conn->prepare("INSERT INTO water_data (distance_cm, status) VALUES (?, ?)");
        $stmt->bind_param("is", $level_air, $status_air);

        if ($stmt->execute()) {
            echo "Data berhasil disimpan.";
        } else {
            echo "Gagal menyimpan data.";
        }
    } else {
        echo "Data tidak lengkap.";
    }

    $system_status = $data['system_status'] ?? 'OFF'; // Ambil status sistem dari data JSON
    $stmt_status = $conn->prepare("UPDATE system_status SET status = ? WHERE id = 1");
    $stmt_status->bind_param("s", $system_status);
    $stmt_status->execute();
} else {
    $result_status = $conn->query("SELECT * FROM system_status ORDER BY id DESC LIMIT 1");
    $status_data = $result_status->fetch_assoc();
    $system_status = isset($status_data['status']) ? $status_data['status'] : "OFF"; // Default ke 'OFF' jika tidak ada data

    $result_water = $conn->query("SELECT * FROM water_data ORDER BY id DESC LIMIT 1");
    $water_data = $result_water->fetch_assoc();
    $water_data_response = array(
        "distance_cm" => isset($water_data['distance_cm']) ? $water_data['distance_cm'] : null,
        "status" => isset($water_data['status']) ? $water_data['status'] : null,
        "timestamp" => isset($water_data['timestamp']) ? $water_data['timestamp'] : null
    );

    $response = array(
        "system_status" => $system_status,
        "water_data" => $water_data_response
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>