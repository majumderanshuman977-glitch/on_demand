<?php 
include("includes/connection.php");
include("assets/js/app.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

if ($username == "") {
    $_SESSION['msg'] = "1"; 
    header("Location:index.php");
    exit;
} else if ($password == "") {
    $_SESSION['msg'] = "2"; 
    header("Location:index.php");
    exit;		 
} else {
    $stmt = $mysqli->prepare("SELECT * FROM tbl_admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['admin_name'] = $row['username'];
        
         // Generate remember_me_token
        $remember_me_token = bin2hex(random_bytes(32));
        
        // Update remember_me_token in database
        $update_token_query = "INSERT INTO tbl_admin_logs (admin_id, admin_username, remember_me_token, log_date, log_ip) VALUES (?, ?, ?, NOW(), ?)";
        $stmt_token = $mysqli->prepare($update_token_query);
        $admin_id = $row['id'];
        $admin_username = $row['username'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $stmt_token->bind_param("isss", $admin_id, $admin_username, $remember_me_token, $ip_address);
        $stmt_token->execute();
        
        // Set remember_me_token as a cookie (valid for 30 days)
        setcookie('admin_remember_me_token', $remember_me_token, time() + (30 * 24 * 60 * 60), '/', '', isset($_SERVER["HTTPS"]), true);
        
        header("Location:home.php");
        exit;
    } else {
        $_SESSION['msg'] = "4"; 
        header("Location:index.php");
        exit;
    }

    $stmt->close();
}
?>
