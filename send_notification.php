<?php
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 

// Retrieve firebase token from tbl_settings
$settings_qry = "SELECT fcm_key FROM tbl_settings LIMIT 1";
$settings_result = mysqli_query($mysqli, $settings_qry);
$settings_row = mysqli_fetch_array($settings_result);
$fcm_key = $settings_row['fcm_key'];

// Define your API access key
    define('API_ACCESS_KEY', $fcm_key);

// Function to send FCM notification
function sendFCMNotification($tokens, $title, $body, $action) {
    // FCM endpoint URL
    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    // Prepare notification data
    $notification = [
        'sound' => 'default',
        'title' => $title,
        'body' => $body,
        '_displayInForeground'=> 'true',
        'channelId'=> 'default'
    ];

    // Additional notification data if needed
    $extraNotificationData = [
        'message' => $notification,
        'open_action' => $action, // Assuming you have a default action
        'fields' => '' // Adjust this according to your needs
    ];

    // Prepare FCM notification payload
    $fcmNotification = [
        'registration_ids' => $tokens,
        'data' => $extraNotificationData,
        'notification' => $notification
    ];

    // Set headers
    $headers = [
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    ];

    // Initialize curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fcmUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

    // Execute curl and get result
    $result = curl_exec($ch);
    curl_close($ch);

    // Return result
    return $result;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Retrieve form data
    $title = $_POST['tittle'];
    $body = $_POST['body'];
    $action = $_POST['action'];
    $city_id = $_POST['city_id']; // Assuming this is the user id

    // Fetch FCM tokens based on city_id
    if ($city_id == 0) {
        // Fetch all FCM tokens
        $tokens_query = "SELECT DISTINCT fcm_token FROM tbl_fcm_token";
    } else {
        // Fetch FCM tokens based on city_id
        $tokens_query = "SELECT DISTINCT fcm_token FROM tbl_fcm_token WHERE u_id = $city_id";
    }

    $tokens_result = mysqli_query($mysqli, $tokens_query);
    $tokens = array();
    while ($row = mysqli_fetch_assoc($tokens_result)) {
        $tokens[] = $row['fcm_token'];
    }

    // Send FCM notification
    $result = sendFCMNotification($tokens, $title, $body, $action);

    // Handle result (you can do further processing here)
    if ($result) {
         $_SESSION['msg']="16";
    } else {
        $_SESSION['msg']="20";
    }

    // Insert notification data into the database
    if ($_FILES['image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $category_image = rand(0, 99999) . "_" . $_FILES['image']['name'];

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["image"]["tmp_name"], $tpath1);

            $data = array(
                'u_id' => $_POST['city_id'],
                'tittle'  =>  $_POST['tittle'],
                'body'  =>  $_POST['body'],
                'image' => $category_image,
                'link'  =>  'wowcodes.in',
                'action'  =>  $_POST['action'],
                'status' => 1
            );

            $qry = Insert('tbl_notifications', $data);

            $_SESSION['msg']="16";
            header("location:send_notification.php");
            exit;
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg']="19";
            header("location:send_notification.php");
            exit;
        }
    }
    else
    {
            $data = array(
                'u_id' => $_POST['city_id'],
                'tittle'  =>  $_POST['tittle'],
                'body'  =>  $_POST['body'],
                'action'  =>  $_POST['action'],
                'status' => 1
            );

            $qry = Insert('tbl_notifications', $data);

            $_SESSION['msg']="16";
            header("location:send_notification.php");
            exit;
    }
}

             $querytime = "SELECT demo_access FROM tbl_settings";
             $resulttime = mysqli_query($mysqli, $querytime);
             $rowtime = mysqli_fetch_assoc($resulttime);
             $demo_access = $rowtime['demo_access'];
              

?>
 	

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Send Notifications</div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row mrg-top">
            <div class="col-md-12">
               
              <div class="col-md-12 col-sm-12">
                <?php if(isset($_SESSION['msg'])){?> 
               	 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                	<?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?>	
              </div>
            </div>
          </div>
          <div class="card-body mrg_bottom"> 
            <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data" >
            	<input  type="hidden" name="id" value="<?php echo $_GET['id'];?>" />

              <div class="section">
                <div class="section-body">
                   
                  <div class="form-group">
                    <label class="col-md-3 control-label">Notification Tittle :-</label>
                    <div class="col-md-6">
                      <input type="text" name="tittle" id="tittle" placeholder="Tittle of the notification" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Notification Body:-</label>
                    <div class="col-md-6">
                      <input type="text" name="body" id="body" placeholder="Body of the notification" class="form-control" required>
                    </div>
                  </div>
                  
                  
                 <div class="form-group">
                    <label class="col-md-3 control-label">User:-</label>
                    <div class="col-md-6">
                        <select name="city_id" id="city_id" style="width:280px; height:25px;" class="select2" required>
                            <option value="">Select User</option>
                            <option value="0">All Users</option>
                            <?php
                            $city_qry = "SELECT email, name, id FROM tbl_users";
                            $city_result = mysqli_query($mysqli, $city_qry);
                            while($city_row = mysqli_fetch_assoc($city_result)) {
                                echo '<option value="'.$city_row['id'].'"';
                                if(isset($_POST['city_id']) && $_POST['city_id'] == $city_row['id']) echo "selected";
                                echo '>'.$city_row['name'].'('.$city_row['email'].')'.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                                
                <div class="form-group">
                    <label class="col-md-3 control-label">Action:-</label>
                      <div class="col-md-6">                       
                        <select name="action" id="action" style="width:280px; height:25px;" class="select2" required>
                            <option value="">-Select Action-</option>
                            <option value="0" <?php if($settings_row['action']=='0'){?>selected<?php }?>>Open App</option>
                            <option value="1" <?php if($settings_row['action']=='1'){?>selected<?php }?>>Open KYC Page</option>
                            <option value="2" <?php if($settings_row['action']=='2'){?>selected<?php }?>>Open Scratch Card Page</option>
                            <!--<option value="3" <?php if($settings_row['action']=='3'){?>selected<?php }?>>Open External URL</option>-->
                            <option value="4" <?php if($settings_row['action']=='4'){?>selected<?php }?>>Open Orders Page</option>
                            <option value="5" <?php if($settings_row['action']=='5'){?>selected<?php }?>>Open Refferal's Page</option>
                          
                        </select>
                      </div>
                  </div>
                  
                <!--<div class="form-group">
                    <label class="col-md-3 control-label">Link:-</label>
                    <div class="col-md-6">
                      <input type="text" name="link" id="link" placeholder="link you want to open when clicked" class="form-control" required>
                    </div>
                 </div>
                
               <div class="form-group">
                    <label class="col-md-3 control-label">Select Image :-
                      <p class="control-label-help">(Recommended Image Size:- 1100 * 600)</p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="image" value="fileupload" id="fileupload">
                            
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                           
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp; </label>
                    <div class="col-md-6">
                            <div class="block_wallpaper"><img src="../seller/images/<?php echo $user_row['image'];?>" alt="category image" /></div>
                    </div>
                  </div><br>-->
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        <?php if ($demo_access == 1) { ?>
                            <button type="demo" name="demo" class="btn btn-warning" disabled>Send Notification (NOT ALLOWED in DEMO)</button>
                        <?php } else { ?>
                            <button type="submit" name="submit" class="btn btn-primary">Send Notification</button>
                        <?php } ?>
                      
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   

<?php include('includes/footer.php');?>                  