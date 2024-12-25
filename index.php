<?php
include 'db_config.php';

$result = $conn->query("SELECT * FROM water_data ORDER BY id DESC LIMIT 1");
$data = $result ? $result->fetch_assoc() : null;

$status_result = $conn->query("SELECT * FROM system_status ORDER BY id DESC LIMIT 1");
$status_data = $status_result ? $status_result->fetch_assoc() : null;

$current_time = date("g:i:s A");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sistem Monitoring Level Air</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body>
    <div class="container">
        <header>
            <h1>Sistem Monitoring Level Air</h1>
            <p class="subtitle">Real-time water level monitoring system</p>
        </header>

        <section class="water-level-card">
            <div class="card-header">
                <div class="icon-water"></div>
                <h2>Water Level Status</h2>
            </div>

            <div class="water-tank-container">
                <div class="water-tank">
                    <div class="water-level" id="water-animation"></div>
                </div>
                <div class="measurement-marks">
                    <span class="mark">0cm</span>
                    <span class="mark">25cm</span>
                    <span class="mark">50cm</span>
                    <span class="mark">75cm</span>
                    <span class="mark">100cm</span>
                </div>
            </div>

            <div class="status-box" id="status-indicator">
                <div class="status-icon"></div>
                <div class="status-text">
                    <span class="status-label">Status: </span>
                    <span class="status-value">Aman</span>
                </div>
                <div class="water-level-text">Water level is at <?php echo $data ? $data['distance_cm'] : "0"; ?>cm
                </div>
            </div>
        </section>

        <section class="control-card">
            <div class="card-header">
                <span class="icon-control"></span>
                <h2>System Control</h2>
            </div>

            <div class="control-buttons">
                <form action="process.php" method="POST" class="control-form">
                    <button type="submit" name="action" value="ON" class="btn btn-on"
                        <?php echo ($status_data && $status_data['status'] == 'ON') ? 'disabled' : ''; ?>>
                        Turn ON
                    </button>
                    <button type="submit" name="action" value="OFF" class="btn btn-off"
                        <?php echo ($status_data && $status_data['status'] == 'OFF') ? 'disabled' : ''; ?>>
                        Turn OFF
                    </button>
                </form>
            </div>

            <div class="system-info">
                <h3>System Information</h3>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value"
                        data-status="<?php echo $status_data ? $status_data['status'] : 'Unknown'; ?>">
                        <?php echo $status_data ? $status_data['status'] : 'Unknown'; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value"><?php echo $current_time; ?></span>
                </div>
            </div>
        </section>
    </div>
    <script src="script.js"></script>
</body>

</html>