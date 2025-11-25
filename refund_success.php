<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .success-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        .success-message {
            font-size: 24px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .home-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .home-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://lottie.host/b7161616-50c3-428f-9c15-440e635fe708/BLnvGUZ90k.json" background="#ffffff" speed="1" style="width: 300px; height: 300px" loop autoplay direction="1" mode="normal" controls="false"></lottie-player>
        <div class="success-message">Refund Successful</div>
        <a href="home.php" class="home-link">Back to Home</a>
    </div>
</body>
</html>
