<?php
    error_reporting(0);
    ob_start();
    session_start();
    header("Content-Type: text/html;charset=UTF-8");
	
    // Include the main connection file
    include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/connection.php');
	
    //Settings
    $setting_qry = "SELECT * FROM tbl_settings where id='1'";
    $setting_result = mysqli_query($mysqli, $setting_qry);
    $settings_details = mysqli_fetch_assoc($setting_result);

    define("APP_API_KEY", 'pXtZkdKQei4hiSgEfLG0pFW2yhAAPDCJCV8x3viuAoSuB');
    define("ONESIGNAL_APP_ID", $settings_details['onesignal_app_id']);
    define("ONESIGNAL_REST_KEY", $settings_details['onesignal_rest_key']);

    define("APP_NAME", $settings_details['app_name']);
    define("APP_LOGO", $settings_details['app_logo']);
    define("APP_FROM_EMAIL", $settings_details['email_from']);

    define("API_LATEST_LIMIT", $settings_details['api_latest_limit']);
    define("API_CAT_ORDER_BY", $settings_details['api_cat_order_by']);
    define("API_CAT_POST_ORDER_BY", $settings_details['api_cat_post_order_by']);
    define("API_ALL_VIDEO_ORDER_BY", $settings_details['api_all_order_by']);

    //Profile
    if (isset($_SESSION['id'])) {
        $admin_profile_qry = "SELECT * FROM tbl_admin where id='" . $_SESSION['id'] . "'";
        $admin_profile_result = mysqli_query($mysqli, $admin_profile_qry);
        $admin_profile_details = mysqli_fetch_assoc($admin_profile_result);

        define("PROFILE_IMG", $admin_profile_details['image']);
    }
?>
