<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

// Secure connection
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

$qry="SELECT * FROM tbl_settings where id='1'";
$result=mysqli_query($mysqli,$qry);
$settings_row=mysqli_fetch_assoc($result);


if (isset($_POST['submit'])) {
    if ($_FILES['app_logo']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        $fileExtension = strtolower(pathinfo($_FILES['app_logo']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {

            if ($settings_row['app_logo'] != "") {
                unlink('../images/' . $settings_row['app_logo']);
            }

            $app_logo = rand(0, 99999) . "_" . basename($_FILES['app_logo']['name']);

            // Securely move uploaded file
            $tpath1 = '../images/' . $app_logo;
            move_uploaded_file($_FILES["app_logo"]["tmp_name"], $tpath1);


            // Update tbl_offers
            $data = array(
                'timezone' =>  sanitize($_POST['timezone']),
                'currency' =>  sanitize($_POST['currency']),
                'coinvalue' =>  sanitize($_POST['coinvalue']),
                'app_name' => sanitize($_POST['app_name']),
                'app_logo' => $app_logo,
                // 'how_to_play' => sanitize($_POST['how_to_play']),
                // 'about_us' => sanitize($_POST['about_us']),
                'signup_bonus' =>  sanitize($_POST['signup_bonus']),
                'refercode_bonus' =>  sanitize($_POST['refercode_bonus']),
                'fcm_key' => sanitize($_POST['fcm_key']),
                'admin_email' => sanitize($_POST['admin_email']),
                'app_link' => sanitize($_POST['app_link']),
                'otp_system' =>  sanitize($_POST['otp_system']),
                'twilio_sid' =>  sanitize($_POST['twilio_sid']),
                'twilio_token' =>  sanitize($_POST['twilio_token']),
                'commission'  =>  sanitize($_POST['commission'])
            );

            $updateSettings = Update('tbl_settings', $data, "WHERE id = '1'");
        }
    } else {

        // Update tbl_offers
        $data = array(
                'timezone' =>  sanitize($_POST['timezone']),
                'currency' =>  sanitize($_POST['currency']),
                'coinvalue' =>  sanitize($_POST['coinvalue']),
                'app_name' => sanitize($_POST['app_name']),
                // 'how_to_play' => sanitize($_POST['how_to_play']),
                // 'about_us' => sanitize($_POST['about_us']),
                'signup_bonus' =>  sanitize($_POST['signup_bonus']),
                'refercode_bonus' =>  sanitize($_POST['refercode_bonus']),
                'fcm_key' => sanitize($_POST['fcm_key']),
                'admin_email' => sanitize($_POST['admin_email']),
                'app_link' => sanitize($_POST['app_link']),
                'otp_system' =>  sanitize($_POST['otp_system']),
                'twilio_sid' =>  sanitize($_POST['twilio_sid']),
                'twilio_token' =>  sanitize($_POST['twilio_token']),
                'commission'  =>  sanitize($_POST['commission'])
        );

        $updateSettings = Update('tbl_settings', $data, "WHERE id = '1'");
    }

    $_SESSION['msg'] = "40";
    header("location:general_settings.php");
    exit;
}
?>
<head>
<title><?php echo $client_lang['general_settings']; ?></title>
</head>

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['general_settings']; ?></div>
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
              <div class="section">
                <div class="section-body">
                   
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['company_name']; ?>:-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['company_logo']; ?>:-
                      <p class="control-label-help"><?php echo $client_lang['image_size']; ?></p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="app_logo" value="fileupload" id="fileupload">
                            
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                           
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp; </label>
                    <div class="col-md-6">
                        <div class="block_wallpaper"><img src="../images/<?php echo $settings_row['app_logo'];?>" alt="category image" /></div>
                    </div>
                  </div><br>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['support_email']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['support_email_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="admin_email" id="admin_email" value="<?php echo $settings_row['admin_email'];?>" class="form-control">
                    </div>
                  </div>
                 
                 
                 <div class="form-group">
                      <label class="col-md-3 control-label"><?php echo $client_lang['timezone']; ?>:-
                      <p class="control-label-help"><?php echo $client_lang['timezone_help']; ?></p></label>
                      <div class="col-md-6">
                          <select name="timezone" id="timezone" style="width:280px; height:25px;" class="select2" required>
                              <?php
                              // Get all timezones
                              $timezones = DateTimeZone::listIdentifiers();
                  
                              // Iterate through timezones and create options
                              foreach ($timezones as $tz) {
                                  echo '<option value="' . $tz . '"';
                                  if ($settings_row['timezone'] == $tz) {
                                      echo ' selected';
                                  }
                                  echo '>' . $tz . '</option>';
                              }
                              ?>
                  
                          </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['register_bonus']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['register_bonus_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="signup_bonus" id="signup_bonus" value="<?php echo $settings_row['signup_bonus'];?>" class="form-control" required>
                      <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                 </div>
                </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['refercode_bonus']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['refercode_bonus_help']; ?></p></label>
                    <div class="col-md-6">
                         <div class="input-group">
                      <input type="text" name="refercode_bonus" id="refercode_bonus" value="<?php echo $settings_row['refercode_bonus'];?>" class="form-control" required>
                      <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                 </div>
                </div>
                <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['currency']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['currency_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="currency" id="currency" placeholder="$" value="<?php echo $settings_row['currency'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                 <div class="form-group">
                   <label class="col-md-3 control-label"><?php echo $client_lang['add_money_cost']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['add_money_cost_help']; ?></p></label>
                   <div class="col-md-6">
                     <div class="input-group">
                       <input type="text" name="coinvalue" id="coinvalue" value="<?php echo $settings_row['coinvalue'];?>" class="form-control" required>
                       <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                     </div>
                   </div>
                 </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['firebase_key']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['firebase_key_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="fcm_key" id="fcm_key" value="<?php echo $settings_row['fcm_key'];?>" class="form-control">
                    </div>
                  </div>
                  
                 <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['app_link']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['app_link_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="app_link" id="app_link" placeholder="link from where user can install the app" value="<?php echo $settings_row['app_link'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['mobile_verification']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['mobile_verification_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="otp_system" id="otp_system" style="width:280px; height:25px;" class="select2" required>
                            <option value="0" <?php if($settings_row['otp_system']=='0'){?>selected<?php }?>><?php echo $client_lang['disable']; ?></option>
                            <option value="1" <?php if($settings_row['otp_system']=='1'){?>selected<?php }?>><?php echo $client_lang['enable']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['twilio_sid']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['twilio_sid_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="twilio_sid" id="twilio_sid" value="<?php echo $settings_row['twilio_sid'];?>" class="form-control" required>
                    </div>
                 </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['twilio_token']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['twilio_token_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="twilio_token" id="twilio_token" value="<?php echo $settings_row['twilio_token'];?>" class="form-control" required>
                    </div>
                 </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['seller_commission']; ?>:-
                    <p class="control-label-help"><?php echo $client_lang['seller_commission_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="commission" id="commission" value="<?php echo $settings_row['commission'];?>" class="form-control" required>
                      <span class="input-group-addon">%</span>
                    </div>
                 </div>
                </div>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php echo $client_lang['update'].' '.$client_lang['general_settings']; ?></button>
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