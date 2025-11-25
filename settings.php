<?php include("includes/header.php");

  require("includes/function.php");
  require("language/language.php");

  $qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);
  
  $qryNotification="SELECT * FROM tbl_notification_settings where id='1'";
  $resultNotification=mysqli_query($mysqli,$qryNotification);
  $rowNotification=mysqli_fetch_assoc($resultNotification);

 

 if (isset($_POST['submit'])) {
    $img_res = mysqli_query($mysqli, "SELECT * FROM tbl_settings WHERE id='1'");
    $img_row = mysqli_fetch_assoc($img_res);

    if ($_FILES['app_logo']['name'] != "") {
        $app_logo = 'profile.png';
        $pic1 = $_FILES['app_logo']['tmp_name'];

        // Check if the uploaded file is an image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileExtension = strtolower(pathinfo($app_logo, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            unlink('images/' . $img_row['app_logo']);
            $tpath1 = '../images/' . 'profile.png';
            copy($pic1, $tpath1);

            $data = array(
                'timezone' =>  $_POST['timezone'],
                'currency' =>  $_POST['currency'],
                'coinvalue' =>  $_POST['coinvalue'],
                'app_name' => $_POST['app_name'],
                'app_logo' => $app_logo,
                'how_to_play' => $_POST['how_to_play'],
                'about_us' => $_POST['about_us'],
                'signup_bonus' =>  $_POST['signup_bonus'],
                'refercode_bonus' =>  $_POST['refercode_bonus'],
                'fcm_key' => $_POST['fcm_key'],
                'admin_email' => $_POST['admin_email'],
                'app_link' => $_POST['app_link'],
                'otp_system' =>  $_POST['otp_system'],
                'twilio_sid' =>  $_POST['twilio_sid'],
                'twilio_token' =>  $_POST['twilio_token']
            );
        } else {
            echo "Invalid file type. Only images (jpg, jpeg, png, gif) are allowed.";
            exit;
        }
    } else {
        $data = array(
            'app_name' => $_POST['app_name'],
            'timezone' =>  $_POST['timezone'],
            'currency' =>  $_POST['currency'],
            'coinvalue' =>  $_POST['coinvalue'],
            'how_to_play' => $_POST['how_to_play'],
            'about_us' => $_POST['about_us'],
            'signup_bonus' =>  $_POST['signup_bonus'],
            'refercode_bonus' =>  $_POST['refercode_bonus'],
            'fcm_key' => $_POST['fcm_key'],
            'admin_email' => $_POST['admin_email'],
            'app_link' => $_POST['app_link']
        );
    }

    $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");

    $_SESSION['msg'] = "22";
    header("Location:settings.php");
    exit;
}


  if(isset($_POST['seller_submit']))
  {

        $data = array(
                'commission'  =>  $_POST['commission']
                 );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
   
        $_SESSION['msg']="31";
        header( "Location:settings.php");
        exit;
  
  }
  
  if(isset($_POST['admob_submit']))
  {

        $data = array(
                'showad'  =>  $_POST['showad'],
                'ads_reward'  =>  $_POST['ads_reward'],
                'vungle_app'  =>  $_POST['vungle_app'],
                'vungle_placement_rewarded'  =>  $_POST['vungle_placement_rewarded'],
                'adcolony_app'  =>  $_POST['adcolony_app'],
                'adcolony_rewarded'  =>  $_POST['adcolony_rewarded'],
                'unity_game'  =>  $_POST['unity_game'],
                'unity_rewarded'  =>  $_POST['unity_rewarded'],
                'admob_banner'  =>  $_POST['admob_banner'],
                'admob_rewarded'  =>  $_POST['admob_rewarded'],
                'admob_interstitial'  =>  $_POST['admob_interstitial'],
                'fb_rewarded'  =>  $_POST['fb_rewarded'],
                'fb_interstitial'  =>  $_POST['fb_interstitial'],
                'fb_banner'  =>  $_POST['fb_banner'],
                'applovin_rewarded'  =>  $_POST['applovin_rewarded'],
                 'startio_rewarded'  =>  $_POST['startio_rewarded'],
                 'ironsource_rewarded'  =>  $_POST['ironsource_rewarded']
                 );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
   
        $_SESSION['msg']="23";
        header( "Location:settings.php");
        exit;
  
  }
 
  if(isset($_POST['notification_submit']))
  {

        $data = array(
                'admin_user' => $_POST['admin_user'],
                'admin_order' => $_POST['admin_order'],
                'admin_coin' => $_POST['admin_coin'],
                'admin_winner' => $_POST['admin_winner'],
                'admin_bid' => $_POST['admin_bid'],
                'seller_order' => $_POST['seller_order'],
                'user_order' => $_POST['user_order'],
                'user_scratch' => $_POST['user_scratch'],
                'user_referral' => $_POST['user_referral'],
                  );

    
     $settings_edit=Update('tbl_notification_settings', $data, "WHERE id = '1'");
  
 
        $_SESSION['msg']="24";
        header( "Location:settings.php");
        exit;
 
  }

  if(isset($_POST['api_submit']))
  {

        $data = array(
                'api_latest_limit'  =>  $_POST['api_latest_limit'],
                'api_cat_order_by'  =>  $_POST['api_cat_order_by'],
                'api_cat_post_order_by'  =>  $_POST['api_cat_post_order_by'],
                'api_all_order_by'  =>  $_POST['api_all_order_by']
                  );

    
      $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
 
        $_SESSION['msg']="11";
        header( "Location:settings.php");
        exit;
   
 
  }

 if(isset($_POST['app_pri_poly']))
{
    // Check if the app_privacy_policy field contains PHP code or other unwanted content
    if (strpos($_POST['app_privacy_policy'], '<?php') === false) {
        // If no PHP code is found, proceed with the update
        $data = array(
            'app_privacy_policy' => $_POST['app_privacy_policy']
        );

        $settings_edit = Update('tbl_settings', $data, "WHERE id = '1'");

        $_SESSION['msg'] = "11";
        header("Location:settings.php");
        exit;
    } else {
        // Handle the case where PHP or unwanted content is detected
        echo "Invalid input detected.";
        // You can add additional error handling here or redirect as needed.
    }
}



?>

<style>
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }
    
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 34px;
    }
    
    .slider:before {
      position: absolute;
      content: "No";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
      text-align: center;
      line-height: 26px;
      font-size: 12px;
      color: black;
    }
    
    input:checked + .slider {
      background-color: #007AFF;
    }
    
    input:checked + .slider:before {
      transform: translateX(26px);
      content: "Yes";
    }
</style>


 
   <div class="row">
      <div class="col-md-12">
        <div class="card">
      <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Settings</div>
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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#app_settings" aria-controls="app_settings" role="tab" data-toggle="tab">General Settings</a></li>
                <li role="presentation"><a href="#seller_settings" aria-controls="seller_settings" role="tab" data-toggle="tab">Seller Settings</a></li>
               <li role="presentation"><a href="#ads_settings" aria-controls="ads_settings" role="tab" data-toggle="tab">Manage Advertisement</a></li>
                <li role="presentation"><a href="#payment_settings" aria-controls="payment_settings" role="tab" data-toggle="tab">Payment Methods</a></li>
                <li role="presentation"><a href="#refer_settings" aria-controls="refer_settings" role="tab" data-toggle="tab">Multi-level Refferal</a></li>
                <li role="presentation"><a href="#notification_settings" aria-controls="notification_settings" role="tab" data-toggle="tab">Notification Settings</a></li>
                <!--<li role="presentation"><a href="#api_settings" aria-controls="api_settings" role="tab" data-toggle="tab">API Settings</a></li>-->
                <li role="presentation"><a href="#api_privacy_policy" aria-controls="api_privacy_policy" role="tab" data-toggle="tab">Privacy Policy</a></li>
            </ul>
          
           <div class="tab-content">
              
              <div role="tabpanel" class="tab-pane active" id="app_settings">   
                <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <!-- <div class="form-group">
                    <label class="col-md-3 control-label">Host Email :-
                      <p class="control-label-help">(Note: This email required otherwise forgot password email feature will not be work. e.g.info@example.com)</p>
                    </label>
                    <div class="col-md-6">
                      <input type="text" name="email_from" id="email_from" value="<?php echo $settings_row['email_from'];?>" class="form-control">
                    </div>
                  </div>-->
                  <div class="form-group">
                    <label class="col-md-3 control-label">Website Name:-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_name" id="app_name" value="<?php echo $settings_row['app_name'];?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label">Image :-
                      <p class="control-label-help">(Recommended Image Size:- 512 px * 512 px)</p>
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
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="text" name="admin_email" id="admin_email" value="<?php echo $settings_row['admin_email'];?>" class="form-control">
                    </div>
                  </div>
                 
                 
                 <div class="form-group">
                      <label class="col-md-3 control-label">Timezone:-</label>
                      <div class="col-md-6">
                          <select name="timezone" id="timezone" style="width:280px; height:25px;" class="select2" required>
                              <option value="">-Select Timezone-</option>
                  
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
                    <label class="col-md-3 control-label">Signup Bonus:-</label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="signup_bonus" id="signup_bonus" value="<?php echo $settings_row['signup_bonus'];?>" class="form-control" required>
                      <span class="input-group-addon">Coins</span>
                    </div>
                 </div>
                </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label">Refer Code Bonus:- <br>(for the user who signed up using refer code)</label>
                    <div class="col-md-6">
                         <div class="input-group">
                      <input type="text" name="refercode_bonus" id="refercode_bonus" value="<?php echo $settings_row['refercode_bonus'];?>" class="form-control" required>
                      <span class="input-group-addon">Coins</span>
                    </div>
                 </div>
                </div>
                <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Currency:-</label>
                    <div class="col-md-6">
                      <input type="text" name="currency" id="currency" placeholder="$" value="<?php echo $settings_row['currency'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                 <div class="form-group">
                   <label class="col-md-3 control-label">Add Money (Coins for <?php echo $settings_row['currency'];?>1) <br>(only for mobile app):</label>
                   <div class="col-md-6">
                     <div class="input-group">
                       <input type="text" name="coinvalue" id="coinvalue" value="<?php echo $settings_row['coinvalue'];?>" class="form-control" required>
                       <span class="input-group-addon">Coins</span>
                     </div>
                   </div>
                 </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label">Firebase Key <br>(for app notification) :-</label>
                    <div class="col-md-6">
                      <input type="text" name="fcm_key" id="fcm_key" value="<?php echo $settings_row['fcm_key'];?>" class="form-control">
                    </div>
                  </div>
                  
                 <div class="form-group">
                    <label class="col-md-3 control-label">App Link:-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_link" id="app_link" placeholder="link from where user can install the app" value="<?php echo $settings_row['app_link'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Mobile Number Verification System :-</label>
                      <div class="col-md-6">                       
                        <select name="otp_system" id="otp_system" style="width:280px; height:25px;" class="select2" required>
                            <option value="">-Enable/Disable-</option>
                            <option value="0" <?php if($settings_row['otp_system']=='0'){?>selected<?php }?>>Disabled</option>
                            <option value="1" <?php if($settings_row['otp_system']=='1'){?>selected<?php }?>>Enabled</option>
                          
                        </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Twilio SID:- </label>
                    <div class="col-md-6">
                      <input type="text" name="twilio_sid" id="twilio_sid" value="<?php echo $settings_row['twilio_sid'];?>" class="form-control" required>
                    </div>
                 </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label">Twilio Token:- </label>
                    <div class="col-md-6">
                      <input type="text" name="twilio_token" id="twilio_token" value="<?php echo $settings_row['twilio_token'];?>" class="form-control" required>
                    </div>
                 </div>
               
                  <!-- <div class="form-group">
                    <label class="col-md-3 control-label">App Description :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_description" id="app_description" class="form-control"><?php echo $settings_row['app_description'];?></textarea>

                      <script>CKEDITOR.replace( 'app_description' );</script>
                    </div>
                  </div>
                  <div class="form-group">&nbsp;</div>                 


                  <div class="form-group">
                    <label class="col-md-3 control-label">App Version :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_version" id="app_version" value="<?php echo $settings_row['app_version'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Author :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_author" id="app_author" value="<?php echo $settings_row['app_author'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Contact :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_contact" id="app_contact" value="<?php echo $settings_row['app_contact'];?>" class="form-control">
                    </div>
                  </div>     
                   <div class="form-group">
                    <label class="col-md-3 control-label">Website :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_website" id="app_website" value="<?php echo $settings_row['app_website'];?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Developed By :-</label>
                    <div class="col-md-6">
                      <input type="text" name="app_developed_by" id="app_developed_by" value="<?php echo $settings_row['app_developed_by'];?>" class="form-control">
                    </div>
                  </div>
                     <div class="form-group">
                    <label class="col-md-3 control-label">How To Play :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="how_to_play" id="how_to_play" class="form-control"><?php echo $settings_row['how_to_play'];?></textarea>

                      <script>CKEDITOR.replace( 'how_to_play' );</script>
                    </div>
                  </div>
                  <div class="form-group">&nbsp;</div>
                     <div class="form-group">
                    <label class="col-md-3 control-label">About Us :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="about_us" id="about_us" class="form-control"><?php echo $settings_row['about_us'];?></textarea>

                      <script>CKEDITOR.replace( 'about_us' );</script>
                    </div>
                  </div>-->
                  <div class="form-group">&nbsp;</div>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Update General Settings</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div>
              
              <div role="tabpanel" class="tab-pane" id="seller_settings">   
                <form action="" name="seller_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Seller Commission:-</label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="commission" id="commission" value="<?php echo $settings_row['commission'];?>" class="form-control" required>
                      <span class="input-group-addon">%</span>
                    </div>
                 </div>
                </div>
               
                  <div class="form-group">&nbsp;</div>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="seller_submit" class="btn btn-primary">Update Seller Commission</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div>
              
              <div role="tabpanel" class="tab-pane" id="ads_settings">   
                <form action="" name="ads_settings" method="post" class="form form-horizontal" enctype="multipart/form-data">
                <div class="section">
          <div class="section-body">            
            <div class="row">
            <div class="form-group">
              <div class="col-md-6">                
              <div class="col-md-12">
                <div class="admob_title">Advertisement</div>
                <div class="form-group">
                      <label class="col-md-3 control-label">Banner Ad:-</label>
                      <div class="col-md-9">
                       <select name="showad" id="showad" class="select2">
                                <option value="1" <?php if($settings_row['showad']=='1'){?>selected<?php }?>>Yes, Show Banner ads</option>
                                <option value="0" <?php if($settings_row['showad']=='0'){?>selected<?php }?>>No, Don't show</option>
                        </select>
                      </div>
                    </div>
              
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Coin for each ads watch :-</label>
                      <div class="col-md-9">
                          <div class="input-group">
                      <input type="text" name="ads_reward" id="ads_reward" value="<?php echo $settings_row['ads_reward'];?>" class="form-control">
                       <span class="input-group-addon">Coins</span>
                    </div>
                 </div>
                </div> 
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #ea4235; color: #fff;">
                    <label class="control-label">Admob Ads (Google Ads)</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                    <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Banner ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="admob_banner" id="admob_banner" value="<?php echo $settings_row['admob_banner'];?>" class="form-control">
                      </div>
                    </div> 
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="admob_rewarded" id="admob_rewarded" value="<?php echo $settings_row['admob_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div> 
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #594195; color: #fff;">
                    <label class="control-label">Facebook Audience Network</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                    <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Banner ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="fb_banner" id="fb_banner" value="<?php echo $settings_row['fb_banner'];?>" class="form-control">
                      </div>
                    </div> 
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="fb_rewarded" id="fb_rewarded" value="<?php echo $settings_row['fb_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div> 
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #222c36; color: #fff;">
                    <label class="control-label">Unity Ads</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                    <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">App ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="unity_game" id="unity_game" value="<?php echo $settings_row['unity_game'];?>" class="form-control">
                      </div>
                    </div> 
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="unity_rewarded" id="unity_rewarded" value="<?php echo $settings_row['unity_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div> 
                
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #1d1d1b; color: #fff;">
                    <label class="control-label">Adcolony Ads</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                    <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">App ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="adcolony_app" id="adcolony_app" value="<?php echo $settings_row['adcolony_app'];?>" class="form-control">
                      </div>
                    </div> 
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="adcolony_rewarded" id="adcolony_rewarded" value="<?php echo $settings_row['adcolony_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div>
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #1085ab; color: #fff;">
                    <label class="control-label">Applovin Ads</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="applovin_rewarded" id="applovin_rewarded" value="<?php echo $settings_row['applovin_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div>
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #02db84; color: #fff;">
                    <label class="control-label">Startio Ads</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="startio_rewarded" id="startio_rewarded" value="<?php echo $settings_row['startio_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div>
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #11168e; color: #fff;">
                    <label class="control-label">Ironsource Ads</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="ironsource_rewarded" id="ironsource_rewarded" value="<?php echo $settings_row['ironsource_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div>
                
                <div class="banner_ads_block">
                  <div class="banner_ad_item" style="background-color: #0f2032; color: #fff;">
                    <label class="control-label">Vungle Ads</label>                                  
                  </div>
                  <div class="col-md-12">
                   
                    <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">App ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="vungle_app" id="vungle_app" value="<?php echo $settings_row['vungle_app'];?>" class="form-control">
                      </div>
                    </div> 
                     <div class="form-group">
                      <label class="col-md-3 control-label mr_bottom20">Rewarded Video ID :-</label>
                      <div class="col-md-9">
                      <input type="text" name="vungle_placement_rewarded" id="vungle_placement_rewarded" value="<?php echo $settings_row['vungle_placement_rewarded'];?>" class="form-control">
                      </div>
                    </div> 
                  </div>
                </div> 
              
              
              </div>
              </div>
            </div>
            </div>                        
            <div class="form-group">
              <div class="col-md-9">
              <button type="submit" name="admob_submit" class="btn btn-primary">Update Payment Gateway Keys</button>
              </div>
            </div>
            </div>
          </div>
                </form>
              </div>
              
        <div role="tabpanel" class="tab-pane" id="payment_settings">
            <?php // Fetch all payment options from the database
                    $payment_options_query = "SELECT * FROM tbl_payment_gateway";
                    $payment_options_result = mysqli_query($mysqli, $payment_options_query); 
               ?>
            <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="payment_settings">
                <div class="section">
                    <div class="section-body">
                        <!-- Payment options table -->
                        <div class="form-group">
                            <div class="col-md-9">
                                <table class="table table-striped table-bordered table-hover">
                                    <div class="page_title">Manage Payment Gateway &nbsp; &nbsp; &nbsp; &nbsp;<a href="edit_payment_gateway.php?add" class="btn btn-primary">Add New Payment Gateway</a></div>
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($payment_option = mysqli_fetch_assoc($payment_options_result)) { ?>
                                            <tr>
                                                <td><img src="<?php echo '../seller/images/'.$payment_option['pg_image']; ?>" alt="<?php echo $payment_option['pg_name']; ?>" width="10" height="10"></td>
                                                <td><?php echo $payment_option['pg_name']; ?></td>
                                                <td>
                                                    <?php 
                                                    if ($payment_option['pg_type'] == 1) {
                                                        echo 'Automatic Payment Gateway <br> for Mobile App';
                                                    } elseif ($payment_option['pg_type'] == 2) {
                                                        echo 'Automatic Payment Gateway <br> for Website';
                                                    } elseif ($payment_option['pg_type'] == 3) {
                                                        echo 'Automatic Payment Gateway';
                                                    } elseif ($payment_option['pg_type'] == 4) {
                                                        echo 'Manual Payment Gateway';
                                                    }
                                                    ?>
                                                </td>
                                               <td>
                                                   <?php 
                                                   if ($payment_option['pg_type'] != 4) {
                                                       echo '<a href="payment_gateway.php" class="btn btn-primary">Modify</a>';
                                                   } elseif ($payment_option['pg_type'] == 4) {
                                                       echo '<a href="edit_payment_gateway.php?pg_id=' . $payment_option['pg_id'] . '" class="btn btn-primary">Edit</a>';
                                                   }
                                                   ?>
                                               </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div role="tabpanel" class="tab-pane" id="refer_settings">
            <?php // Fetch all referral options from the database
                    $refferal_options_query = "SELECT * FROM tbl_referral_bonus";
                    $refferal_options_result = mysqli_query($mysqli, $refferal_options_query); 
               ?>
            <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="refer_settings">
                <div class="section">
                    <div class="section-body">
                        <!-- Payment options table -->
                        <div class="form-group">
                            <div class="col-md-9">
                                <table class="table table-striped table-bordered table-hover">
                                    <div class="page_title">Manage Referral Levels &nbsp; &nbsp; &nbsp; &nbsp;<a href="manage_referral_level.php?add" class="btn btn-primary">Add New Level</a></div>
                                    <thead>
                                        <tr>
                                            <th>Level</th>
                                            <th>Referral Bonus</th>
                                            <th>Purchase Bonus</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($refferal_option = mysqli_fetch_assoc($refferal_options_result)) { ?>
                                            <tr>
                                                <td><?php echo 'Level '.$refferal_option['level']; ?></td>
                                                <td><?php echo  $refferal_option['referral_bonus'].' Coins'; ?></td>
                                                <td><?php echo  $refferal_option['coin_purchase_bonus'].'%'; ?></td>
                                               <td>
                                                  <a href="manage_referral_level.php?id=<?php echo $refferal_option['id'];?>" class="btn btn-primary">Edit</a>
                                               </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
            
            <!-- Notification -->
            <div role="tabpanel" class="tab-pane" id="notification_settings">
              <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data" id="api_form">
                <div class="section">
                <div class="section-body">
                  <div class="banner_ad_item" style="background-color: #222c36; color: #fff;">
                    <label class="control-label">Admin Notification</label>                                  
                  </div>
                  <div class="form-group">
                      <label class="col-md-3 control-label">New User:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="admin_user" id="admin_user" value="1" <?php if($rowNotification['admin_user']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Get Notified everytime when a new user signup's</p>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label class="col-md-3 control-label">New Order:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="admin_order" id="admin_order" value="1" <?php if($rowNotification['admin_order']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Get Notified everytime a new order is placed</p>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label class="col-md-3 control-label">Coin Purchase:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="admin_coin" id="admin_coin" value="1" <?php if($rowNotification['admin_coin']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Get Notified everytime user purchases coin</p>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label class="col-md-3 control-label">New Winner:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="admin_winner" id="admin_winner" value="1" <?php if($rowNotification['admin_winner']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Get Notified everytime a winner is declared</p>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label class="col-md-3 control-label">New Bid:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="admin_bid" id="admin_bid" value="1" <?php if($rowNotification['admin_bid']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Get Notified everytime a new bid is placed</p>
                      </div>
                  </div>
                  <br>
                  <div class="banner_ad_item" style="background-color: #222c36; color: #fff;">
                    <label class="control-label">Seller Notification</label>                                  
                  </div>
                  
                  <div class="form-group">
                      <label class="col-md-3 control-label">New Order:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="seller_order" id="seller_order" value="1" <?php if($rowNotification['seller_order']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Send Notification to Seller everytime a new order is placed</p>
                      </div>
                  </div>
                  <br>
                  <div class="banner_ad_item" style="background-color: #222c36; color: #fff;">
                    <label class="control-label">User Notification</label>                                  
                  </div>
                  
                  <div class="form-group">
                      <label class="col-md-3 control-label">Order Status Change:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="user_order" id="user_order" value="1" <?php if($rowNotification['user_order']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Notify User whenever their order status changes</p>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label class="col-md-3 control-label">New Reward (Scratch Card):-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="user_scratch" id="user_scratch" value="1" <?php if($rowNotification['user_scratch']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Notify User whenever he get's a new scratch card from admin</p>
                      </div>
                  </div>
                  <br>
                  <div class="form-group">
                      <label class="col-md-3 control-label">New Referral:-</label>
                      <div class="col-md-6">
                        <label class="switch">
                          <input type="checkbox" name="user_referral" id="user_referral" value="1" <?php if($rowNotification['user_referral']=='1'){?>checked<?php }?>>
                          <span class="slider"></span>
                        </label>
                        <p>Notify User whenever a new user uses their referral code</p>
                      </div>
                  </div>
                  <br>         
                  <div class="form-group">
                  <div class="col-md-9 col-md-offset-3">
                    <button type="submit" name="notification_submit" class="btn btn-primary">Update Nortification Settings</button>
                  </div>
                  </div>
                </div>
                </div>
              </form>
            </div> 
              <div role="tabpanel" class="tab-pane" id="api_settings">   
                <form action="" name="settings_api" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  
                  <div class="form-group">
                    <div class="col-md-6">
                    </div>
                  </div> <hr/> 
                  <div class="form-group">
                    <label class="col-md-3 control-label">Latest Limit:-</label>
                    <div class="col-md-6">
                       
                      <input type="number" name="api_latest_limit" id="api_latest_limit" value="<?php echo $settings_row['api_latest_limit'];?>" class="form-control"> 
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category List Order By:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_order_by" id="api_cat_order_by" class="select2">
                          <option value="cid" <?php if($settings_row['api_cat_order_by']=='cid'){?>selected<?php }?>>ID</option>
                          <option value="category_name" <?php if($settings_row['api_cat_order_by']=='category_name'){?>selected<?php }?>>Name</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Category Video Order:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_cat_post_order_by" id="api_cat_post_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_cat_post_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_cat_post_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">All Video Order:-</label>
                    <div class="col-md-6">
                       
                        
                        <select name="api_all_order_by" id="api_all_order_by" class="select2">
                          <option value="ASC" <?php if($settings_row['api_all_order_by']=='ASC'){?>selected<?php }?>>ASC</option>
                          <option value="DESC" <?php if($settings_row['api_all_order_by']=='DESC'){?>selected<?php }?>>DESC</option>
              
                        </select>
                        
                    </div>
                   
                  </div>
                  
                
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="api_submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 

              <div role="tabpanel" class="tab-pane" id="api_privacy_policy">   
                <form action="" name="api_privacy_policy" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">App Privacy Policy :-</label>
                    <div class="col-md-6">
                 
                      <textarea name="app_privacy_policy" id="privacy_policy" class="form-control"><?php echo $settings_row['app_privacy_policy'];?></textarea>

                      <script>CKEDITOR.replace( 'privacy_policy' );</script>
                    </div>
                  </div>
                  
                
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="app_pri_poly" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div> 

            </div>   

          </div>
        </div>
      </div>
    </div>

        
<?php include("includes/footer.php");?>       
