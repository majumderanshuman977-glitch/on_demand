<?php
include "includes/connection.php";
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

$activation_key = '';
$error_message = '';
$is_activated = false;

$sql = "SELECT activation_key FROM tbl_settings";
$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $activation_key = $row["activation_key"];
    $current_url = $_SERVER["HTTP_HOST"];
    $normalized_current_url = normalize_website_url($current_url);

    $verify_activation_url = "https://verify.wowcodes.in/verifyPurchase.php?activation_key={$activation_key}&website={$normalized_current_url}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verify_activation_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $verify_response = curl_exec($ch);
    curl_close($ch);

    $verify_result = json_decode($verify_response, true);

    if ($verify_result && $verify_result["success"] == 1) {
        $is_activated = true;
    } else {
        $sql_clear = "UPDATE tbl_settings SET activation_key = NULL";
        if ($mysqli->query($sql_clear) === TRUE) {
            $is_activated = false;
            $activation_key = '';
        }
    }
} else {
    header("Location: verifyPurchase.php");
    die;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["purchase_key"])) {
    $purchase_key = $_POST["purchase_key"];
    $current_url = isset($_SERVER["HTTP_REFERER"]) ? parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST) : '';
    $normalized_current_url = normalize_website_url($current_url);

    $verify_url = "https://verify.wowcodes.in/verifyPurchase.php?purchase_key={$purchase_key}&website={$normalized_current_url}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verify_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result && $result["success"] == 1 && isset($result["activation_key"])) {
        $activation_key = mysqli_real_escape_string($mysqli, $result["activation_key"]);
        $sql = "UPDATE tbl_settings SET activation_key = '{$activation_key}'";
        
        if ($mysqli->query($sql) === TRUE) {
            $_SESSION["msg"] = "32";
            header("Location: home.php");
            die;
        } else {
            echo "Error updating activation key: " . $mysqli->error;
            header("Location: verifyPurchase.php?failure");
            die;
        }
    } else {
        $error_message = isset($result["message"]) ? $result["message"] : "Unknown error occurred.";
    }
}

$mysqli->close();

function normalize_website_url($url) {
    return preg_replace("/^www\./i", '', $url);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Purchase Key</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }
        .container {
            display: flex;
            justify-content: center; /* Center aligns the content horizontally */
            align-items: center; /* Center aligns the content vertically */
            margin-top:75px;
            min-height: 100vh; /* Ensure container takes at least the full viewport height */
            padding: 20px; /* Add padding to the container */
        }
        .content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center; /* Center aligns content inside this container */
            width: 400px;
        }
        .remember {
            text-align: left; /* Aligns the "Remember" list to the left */
        }
        h2 {
            color: #333;
        }
        form {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%; /* Ensure form takes full width of its container */
            max-width: 380px; /* Limit form width */
            box-sizing: border-box; /* Include padding and border in width calculation */
            text-align: center; /* Center aligns content inside this container */
        }
        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .activated {
            background-color: #fafafa;
            color: #28a745;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: default;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <hr>
            <?php if ($is_activated) : ?>
                <h2>Your License is Active!</h2>
            <?php else : ?>
                <h2>Enter Purchase Key</h2>
            <?php endif; ?>
            <form id="verifyForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <?php if ($is_activated) : ?>
                    <label for="activation_key">Activation Key:</label>
                    <input type="text" id="activation_key" name="activation_key" readonly value="<?php echo htmlspecialchars($activation_key); ?>" class="activated">
                <?php else : ?>
                    <label for="purchase_key">Purchase Key:</label>
                    <input type="text" id="purchase_key" name="purchase_key" required>
                <?php endif; ?>
                <?php if (!empty($error_message)) : ?>
                    <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <?php if ($is_activated) : ?>
                    <button class="btn-success" disabled>Activated</button>
                <?php else : ?>
                    <button type="submit">Verify</button>
                <?php endif; ?>
            </form>
            <div class="remember">
        <h3>&nbsp;Points to remember:</h3>
        <ul>
            <li>Each Purchase Key can only be used once to activate the software.</li>
            <li>Each Activation Key is valid for 1 Year.</li>
            <li>Keep your Purchase Key secure and do not share it publicly.</li>
            <li>Contact support at <a href="mailto:hello@wowcodes.in">hello@wowcodes.in</a> for assistance with activation issues.</li>
            <li>Refer to our terms of service for more information about software activation.</li>
        </ul>
    </div>
        </div>
    </div>
    </div>

    <script>
        // JavaScript to normalize the URL before form submission
        document.getElementById('verifyForm').addEventListener('submit', function(event) {
            var currentUrl = window.location.hostname;
            var normalizedUrl = currentUrl.replace(/^www\./i, '');

            // Update the value of the 'website' input field before submitting the form
            document.getElementById('website').value = normalizedUrl;
        });
    </script>
</body>
</html>