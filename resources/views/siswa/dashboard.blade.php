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
    <video id="preview"></video>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const videoElement = document.getElementById('preview');

        const html5QrCode = new Html5Qrcode("preview");

        Html5Qrcode.getCameras().then(cameras => {
            if (cameras.length > 0) {
                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 200
                    },
                    qrCodeMessage => {
                        html5QrCode.stop();
                        sendBarcode(qrCodeMessage);
                    }
                );
            }
        });

        function sendBarcode(barcode) {
            fetch("{{ route('siswa.absen') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        barcode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(() => alert("Gagal mencatat absensi"));
        }
    </script>
</body>

</html>
