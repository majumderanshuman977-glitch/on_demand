<?php

// Use prepared statements to prevent SQL injection
$settingsQry = $mysqli->prepare("SELECT demo_access FROM tbl_settings");
$settingsQry->execute();
$settingsResult = $settingsQry->get_result();
$settingsRow = $settingsResult->fetch_assoc();
$demo_mode = $settingsRow['demo_access'];

if($demo_mode == 2)
{
    if (function_exists('ini_set')) {
        echo 'Caution! Debug Mode is turned on. Not done by you? Contact WowCodes';
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        
    } else {
        echo 'Contact WowCodes! <br> Can not enable debug mode as ini_set function is disabled';
        error_log('Can not enable WowCodes debug mode, ini_set function is disabled');
    }
}
else if ($demo_mode == 1) {
    // Output the Google Analytics script
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6NNR2ME2RD"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-6NNR2ME2RD');
    </script>
    <?php
}
else
{
    ini_set('display_errors', '0'); // Hide errors from users in production
}


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

        // No redirection, just set the session variables and stay on the same page
    }
}

if (!isset($_SESSION["admin_name"])) {
    session_destroy();
    $_SESSION['msg'] = "session_destroyed";
    header("Location: index.php");
    die;
}

function get_vendor_info($vendor_id,$field_name) {
	global $mysqli;

	$qry_user = "SELECT * FROM tbl_vendor WHERE id='".$vendor_id."'";
	$query1 = mysqli_query($mysqli,$qry_user);
	$row_user = mysqli_fetch_array($query1);

	$num_rows1 = mysqli_num_rows($query1);
	
	if ($num_rows1 > 0) {		 	
		return $row_user[$field_name];
	} else {
		return "";
	}
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input ?? '')), ENT_QUOTES, 'UTF-8');
}


// Function to validate image files
function isValidImage($file) {
    $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_mime_type = mime_content_type($file['tmp_name']);
    return in_array($file_mime_type, $allowed_mime_types);
}

// Create Thumbnail
function createThumbnail($src, $dest, $desired_size) {
    $image_info = getimagesize($src);
    $image_type = $image_info[2];

    switch ($image_type) {
        case IMAGETYPE_JPEG:
            $source_image = imagecreatefromjpeg($src);
            break;
        case IMAGETYPE_PNG:
            $source_image = imagecreatefrompng($src);
            break;
        case IMAGETYPE_GIF:
            $source_image = imagecreatefromgif($src);
            break;
        default:
            return false;
    }

    $width = imagesx($source_image);
    $height = imagesy($source_image);
    $scaling_factor = $desired_size / max($width, $height);
    $new_width = (int)($width * $scaling_factor);
    $new_height = (int)($height * $scaling_factor);
    $virtual_image = imagecreatetruecolor($desired_size, $desired_size);

    if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
        imagealphablending($virtual_image, false);
        imagesavealpha($virtual_image, true);
        $transparent = imagecolorallocatealpha($virtual_image, 255, 255, 255, 127);
        imagefilledrectangle($virtual_image, 0, 0, $desired_size, $desired_size, $transparent);
    } else {
        $white = imagecolorallocate($virtual_image, 255, 255, 255);
        imagefilledrectangle($virtual_image, 0, 0, $desired_size, $desired_size, $white);
    }

    $x_offset = ($desired_size - $new_width) / 2;
    $y_offset = ($desired_size - $new_height) / 2;
    imagecopyresampled($virtual_image, $source_image, $x_offset, $y_offset, 0, 0, $new_width, $new_height, $width, $height);

    switch ($image_type) {
        case IMAGETYPE_JPEG:
            imagejpeg($virtual_image, $dest, 50);
            break;
        case IMAGETYPE_PNG:
            imagepng($virtual_image, $dest);
            break;
        case IMAGETYPE_GIF:
            imagegif($virtual_image, $dest);
            break;
    }

    imagedestroy($source_image);
    imagedestroy($virtual_image);
}


#Admin Login
function adminUser($username, $password){
    
    global $mysqli;

    $sql = "SELECT id,username FROM tbl_admin where username = '".$username."' and password = '".md5($password)."'";       
    $result = mysqli_query($mysqli,$sql);
    $num_rows = mysqli_num_rows($result);
     
    if ($num_rows > 0){
        while ($row = mysqli_fetch_array($result)){
            echo $_SESSION['ADMIN_ID'] = $row['id'];
                        echo $_SESSION['ADMIN_USERNAME'] = $row['username'];
                                      
        return true; 
        }
    }
    
}

# Insert Data 
function Insert($table, $data){

    global $mysqli;
    //print_r($data);

    $fields = array_keys( $data );  
    $values = array_map( array($mysqli, 'real_escape_string'), array_values( $data ) );
    
   //echo "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');";
   //exit;  
    mysqli_query($mysqli, "INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');") or die( mysqli_error($mysqli) );

}

// Update Data, Where clause is left optional
function Update($table_name, $form_data, $where_clause='')
{   
    global $mysqli;
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

    // loop and build the column /
    $sets = array();
    foreach($form_data as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $sql .= implode(', ', $sets);

    // append the where statement
    $sql .= $whereSQL;
         
    // run and return the query result
    return mysqli_query($mysqli,$sql);
}

 
//Delete Data, the where clause is left optional incase the user wants to delete every row!
function Delete($table_name, $where_clause='')
{   
    global $mysqli;
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;
     
    // run and return the query result resource
    return mysqli_query($mysqli,$sql);
}  
 
//GCM function
function Send_GCM_msg($registration_id,$data)
{
    $data1['data']=$data;
 
    $url = 'https://fcm.googleapis.com/fcm/send';
  
    $registatoin_ids = array($registration_id);
     // $message = array($data);
   
         $fields = array(
             'registration_ids' => $registatoin_ids,
             'data' => $data1,
         );
  
         $headers = array(
             'Authorization: key='.APP_GCM_KEY.'',
             'Content-Type: application/json'
         );
         // Open connection
         $ch = curl_init();
  
         // Set the url, number of POST vars, POST data
         curl_setopt($ch, CURLOPT_URL, $url);
  
         curl_setopt($ch, CURLOPT_POST, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
         // Disabling SSL Certificate support temporarly
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  
         // Execute post
         $result = curl_exec($ch);
         if ($result === FALSE) {
             die('Curl failed: ' . curl_error($ch));
         }
  
         // Close connection
         curl_close($ch);
       //echo $result;exit;
}


//Image compress
function compress_image($source_url, $destination_url, $quality) 
{

    $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg')
              $image = imagecreatefromjpeg($source_url);

        elseif ($info['mime'] == 'image/gif')
              $image = imagecreatefromgif($source_url);

      elseif ($info['mime'] == 'image/png')
              $image = imagecreatefrompng($source_url);

        imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

//Create Thumb Image
function create_thumb_image($target_folder ='',$thumb_folder = '', $thumb_width = '',$thumb_height = '')
 {  
     //folder path setup
         $target_path = $target_folder;
         $thumb_path = $thumb_folder;  
          

         $thumbnail = $thumb_path;
         $upload_image = $target_path;

            list($width,$height) = getimagesize($upload_image);
            $thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
            switch($file_ext){
                case 'jpg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'jpeg':
                    $source = imagecreatefromjpeg($upload_image);
                    break;
                case 'png':
                    $source = imagecreatefrompng($upload_image);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($upload_image);
                     break;
                default:
                    $source = imagecreatefromjpeg($upload_image);
            }
       imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width,$height);
            switch($file_ext){
                case 'jpg' || 'jpeg':
                    imagejpeg($thumb_create,$thumbnail,80);
                    break;
                case 'png':
                    imagepng($thumb_create,$thumbnail,80);
                    break;
                case 'gif':
                    imagegif($thumb_create,$thumbnail,80);
                     break;
                default:
                    imagejpeg($thumb_create,$thumbnail,80);
            }
 }
?>