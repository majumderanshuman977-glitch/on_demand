<?php

error_reporting(0);
if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $path = "uploads/"; // Set your folder path
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif'); // Allowed image file extensions

    $video_local_name = str_replace(" ", "-", $_FILES['video_local']['name']);
    $video_local_extension = strtolower(pathinfo($video_local_name, PATHINFO_EXTENSION));

    // Check if the uploaded file has an allowed image extension
    if (in_array($video_local_extension, $allowedExtensions)) {
        $video_local_new_name = rand(0, 99999) . "_" . $video_local_name;
        $tmp = $_FILES['video_local']['tmp_name'];

        if (move_uploaded_file($tmp, $path . $video_local_new_name)) {
            echo $video_local_new_name;
        } else {
            echo "failed";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
    exit;
}
