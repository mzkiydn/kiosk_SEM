<?php

?>

<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #preview {
            width: 70%;
            max-width: 800px;
            display: none;
        }
    </style>
</head>
<body>
    <video id="preview"></video>

    <script>
        let scanner = null;

        function startScanner() {
            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

            scanner.addListener('scan', function (content) {

                // Create a form dynamically to post the scanned content
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = 'add-to-cart.php';

                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'data';
                input.value = content;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            });

            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]); // Use the first camera available
                    document.getElementById('preview').style.display = 'block';
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        }

        // Call startScanner() when the page loads
        document.addEventListener('DOMContentLoaded', startScanner);
    </script>
</body>
</html>

