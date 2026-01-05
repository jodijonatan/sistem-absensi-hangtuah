<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Scan Kehadiran</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: #0d1117;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: sans-serif;
        }

        video {
            width: 320px;
            height: 240px;
            border: 3px solid #00ff99;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <h2>Scan Barcode Kehadiran</h2>
    <p id="status_lokasi">Mencari lokasi...</p> <video id="preview"></video>
    <p id="status_scan">Menunggu Barcode...</p>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const statusLokasiElement = document.getElementById('status_lokasi');
        const statusScanElement = document.getElementById('status_scan');
        const videoElement = document.getElementById('preview');

        const html5QrCode = new Html5Qrcode("preview");
        let userLatitude = null;
        let userLongitude = null;

        // --- 1. Minta Lokasi (Geolocation) ---
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    showPosition,
                    showError,
                    // Opsi untuk akurasi tinggi
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                statusLokasiElement.textContent = "Geolocation tidak didukung oleh browser Anda.";
            }
        }

        function showPosition(position) {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;
            statusLokasiElement.textContent =
                `Lokasi didapatkan: Lat ${userLatitude.toFixed(4)}, Lon ${userLongitude.toFixed(4)}`;
            // Setelah lokasi didapat, baru mulai scan kamera
            startScan();
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    statusLokasiElement.textContent = "Izin lokasi ditolak. Absensi tidak dapat dilakukan.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    statusLokasiElement.textContent = "Informasi lokasi tidak tersedia.";
                    break;
                case error.TIMEOUT:
                    statusLokasiElement.textContent = "Waktu permintaan lokasi habis.";
                    break;
                default:
                    statusLokasiElement.textContent = "Terjadi kesalahan yang tidak diketahui saat mendapatkan lokasi.";
            }
        }

        // --- 2. Mulai Scan Barcode ---
        function startScan() {
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras.length === 0) {
                    statusScanElement.textContent = "Tidak ada kamera yang ditemukan.";
                    return;
                }

                statusScanElement.textContent = "Kamera aktif. Silakan scan barcode!";

                // Gunakan kamera pertama, biasanya kamera belakang (environment) di HP
                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 200
                    },
                    qrCodeMessage => {
                        // Barcode berhasil di-scan
                        html5QrCode.stop(); // Hentikan kamera setelah scan berhasil
                        statusScanElement.textContent = "Barcode berhasil di-scan. Mengirim data...";
                        sendAbsensi(qrCodeMessage);
                    },
                    errorMessage => {
                        // Error saat scan (bukan error izin kamera)
                        // console.log(errorMessage); 
                    }
                ).catch(err => {
                    // Error izin kamera
                    statusScanElement.textContent = `Gagal membuka kamera: ${err}`;
                });
            });
        }

        // --- 3. Kirim Data (AJAX) ---
        function sendAbsensi(barcode) {
            if (!userLatitude || !userLongitude) {
                alert("Gagal mengirim absensi: Lokasi belum didapatkan.");
                return;
            }

            fetch("{{ route('siswa.absen') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        barcode: barcode,
                        latitude: userLatitude, // Kirim lokasi
                        longitude: userLongitude // Kirim lokasi
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    // Redirect ke dashboard utama siswa setelah sukses
                    window.location.href = '{{ route('siswa.dashboard') }}';
                })
                .catch(error => {
                    alert("Gagal mencatat absensi. Coba lagi.");
                    // Jika gagal, mungkin alihkan kembali ke halaman scan untuk mencoba lagi
                    location.reload();
                });
        }

        // Mulai alur saat DOM siap
        getLocation();
    </script>
</body>

</html>
