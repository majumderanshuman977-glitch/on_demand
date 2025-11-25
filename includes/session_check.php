<?php 

// Check if user is already logged in with persistent cookie
if (isset($_COOKIE['admin_remember_me_token']) && !isset($_SESSION['admin_name'])) {
    $token = $_COOKIE['admin_remember_me_token'];

    // Use prepared statements to prevent SQL injection
    $qry = $mysqli->prepare("SELECT * FROM tbl_admin_logs WHERE remember_me_token = ?");
    $qry->bind_param("s", $token);
    $qry->execute();
    $result = $qry->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $_SESSION['id'] = $row['admin_id'];
        $_SESSION['admin_name'] = $row['admin_username'];
        // Redirect to the same page to refresh
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

else if (!isset($_SESSION["admin_name"])) {
    session_destroy();
    $_SESSION['msg'] = "session_destroyed";
    header("Location: index.php");
    die;
}

?>