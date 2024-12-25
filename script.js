// Fungsi untuk mengupdate tampilan air dan status
function updateWaterLevel(level) {
    const waterElement = document.getElementById('water-animation');
    const statusBox = document.getElementById('status-indicator');
    const statusValue = statusBox.querySelector('.status-value');
    const waterLevelText = document.querySelector('.water-level-text');

    // Update water level text
    waterLevelText.textContent = `Water level is at ${level}cm`;

    // Persentase ketinggian air berdasarkan logika terbalik
    // Karena sensor ultrasonik mengukur jarak dari atas
    // 100cm (kosong) = 0% air
    // 0cm (penuh) = 100% air
    const percentage = Math.max(0, Math.min(100, level));
    waterElement.style.height = `${100 - percentage}%`;

    // Update status indikator berdasarkan logika baru
    let statusClass = '';
    let statusText = '';

    if (level >= 51 && level <= 100) {
        statusClass = 'status-safe';
        statusText = 'Aman';
    } else if (level >= 21 && level <= 50) {
        statusClass = 'status-warning';
        statusText = 'Siaga';
    } else if (level >= 1 && level <= 20) {
        statusClass = 'status-danger';
        statusText = 'Bahaya';
    }

    // Hapus semua kelas status sebelumnya
    statusBox.classList.remove('status-safe', 'status-warning', 'status-danger');
    // Tambahkan kelas status baru
    statusBox.classList.add(statusClass);
    // Update text status
    statusValue.textContent = statusText;
}

// Fungsi untuk memperbarui status sistem
function updateSystemStatus(systemStatus) {
    const statusValue = document.querySelector('.system-info .info-value');
    statusValue.textContent = systemStatus;
    statusValue.setAttribute('data-status', systemStatus);

    // Update status tombol
    const btnOn = document.querySelector('.btn-on');
    const btnOff = document.querySelector('.btn-off');

    btnOn.disabled = (systemStatus === 'ON');
    btnOff.disabled = (systemStatus === 'OFF');

    // Tambahkan efek visual
    if (systemStatus === 'ON') {
        btnOn.style.transform = 'scale(1.02)';
        setTimeout(() => btnOn.style.transform = '', 200);
    } else {
        btnOff.style.transform = 'scale(1.02)';
        setTimeout(() => btnOff.style.transform = '', 200);
    }
}

// Tambahkan event listeners untuk efek hover pada tombol
document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('mouseenter', () => {
        if (!button.disabled) {
            button.style.transform = 'translateY(-2px)';
        }
    });

    button.addEventListener('mouseleave', () => {
        if (!button.disabled) {
            button.style.transform = '';
        }
    });
});

// Fungsi untuk memperbarui waktu
function updateLastTime(time) {
    document.querySelector('.info-item:last-child .info-value').textContent = time;
}

// Fungsi untuk mengambil data terbaru
function fetchLatestData() {
    fetch('get_data.php')
        .then(response => response.json())
        .then(data => {
            updateWaterLevel(data.distance_cm);
            updateSystemStatus(data.system_status);
            updateLastTime(data.last_updated);
        })
        .catch(error => console.error('Error:', error));
}

// Inisialisasi update pertama kali
fetchLatestData();

// Update data setiap 1 detik
setInterval(fetchLatestData, 1000);