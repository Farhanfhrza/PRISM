<div>
    <style>
        .scan-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            margin-bottom: 12px;
        }

        .scan-button:hover {
            background-color: #45a049;
        }

        .scan-button:active {
            background-color: #3e8e41;
        }

        #reader {
            width: 100%;
            max-width: 400px;
            margin-top: 10px;
            border: 1px solid #ccc;
        }
    </style>

    <!-- Tombol Scan -->
    <button type="button" class="scan-button" onclick="startScan()">Scan Barcode</button>

    <!-- Kamera Container -->
    <div id="reader" style="display: none;"></div>

    <!-- Html5-qrcode -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let html5QrCode;
        let isScanning = false;

        function startScan() {
            const reader = document.getElementById("reader");
            reader.style.display = "block";

            if (isScanning) return;

            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { facingMode: "environment" }, // kamera belakang
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    const inputElement = document.getElementById("barcode-input");
                    if (inputElement) {
                        inputElement.value = decodedText;
                        inputElement.dispatchEvent(new Event("input", { bubbles: true }));

                        html5QrCode.stop().then(() => {
                            reader.style.display = "none";
                            isScanning = false;
                        });
                    } else {
                        alert("Element dengan id 'barcode-input' tidak ditemukan.");
                    }
                },
                (errorMessage) => {
                    // Optional: console.log("Scan error:", errorMessage);
                }
            ).then(() => {
                isScanning = true;
            }).catch(err => {
                alert("Tidak dapat mengakses kamera. Pastikan izin kamera diizinkan dan browser HTTPS/localhost.");
                console.error(err);
            });
        }
    </script>
</div>
