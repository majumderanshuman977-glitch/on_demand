<?php include('includes/header.php');

include('includes/function.php');
include('language/language.php');

require_once("thumbnail_images.class.php");


if (isset($_POST['submit']) and isset($_GET['add'])) {
    
    $category_id = $_POST['c_id'];
    $category_qry = "SELECT * FROM tbl_cat WHERE c_id=$category_id";
    $category_result = mysqli_query($mysqli, $category_qry);
    $category_row = mysqli_fetch_assoc($category_result);
    
    // Sanitize inputs
    $c_id = intval($_POST['c_id']); // Assuming c_id is an integer
    $winner_type = sanitize($_POST['winner_type']);
    $city_id = intval($_POST['city_id']); // Assuming city_id is an integer
    $item_id = intval($_POST['item_id']); // Assuming item_id is an integer
    $o_date = sanitize($_POST['o_date']);
    $o_edate = sanitize($_POST['o_edate']);
    $o_stime = sanitize($_POST['o_stime']) . ":00"; // Add seconds set to 00
    $o_etime = sanitize($_POST['o_etime']) . ":00"; // Add seconds set to 00
    $o_amount = sanitize($_POST['o_amount']);
    $o_price = sanitize($_POST['o_price']);
    $lottery_balls_id = sanitize($_POST['lottery_balls_id']);
    $o_qty = intval($_POST['o_qty']); // Assuming o_qty is an integer

     // Prepare data for insertion
        $data = [
            'id' => 1,
            'c_id' => $c_id,
            'winner_type' => $winner_type,
            'city_id' => $city_id,
            'item_id' => $item_id,
            'o_date' => $o_date,
            'o_edate' => $o_edate,
            'o_stime' => $o_stime,
            'o_etime' => $o_etime,
            'o_amount' => $o_amount,
            'o_type' => 5,
            'o_price' => $o_price,
            'o_qty' => $o_qty,
            'lottery_balls_id' => $lottery_balls_id,
            'o_status' => 1
        ];
    

    $qry = Insert('tbl_offers', $data);
    
    $oid_qry = "SELECT o_id FROM tbl_offers ORDER BY o_id DESC LIMIT 1";
    $oid_result = mysqli_query($mysqli, $oid_qry);
    $oid_row = mysqli_fetch_assoc($oid_result);
    
    // Data does not exist, insert it
        $prize_data = [
            'item_id' => $_POST['item_id'],
            'o_id' => $oid_row['o_id'],
            'rank_start' => 1,
            'rank_end' => 1
        ];
    $insert_prize = Insert('tbl_prizes', $prize_data);
      if ($insert_prize > 0) {
          $_SESSION['msg'] = "38";
          header("location:lottery.php");
          exit;
      }
        
    

    $_SESSION['msg'] = "38";
    header("location:lottery.php");
    exit;
}

if (isset($_GET['o_id'])) {

    $user_qry = "SELECT * FROM tbl_offers where o_id='" . $_GET['o_id'] . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) and isset($_POST['o_id'])) {
   
    $category_id = $_POST['c_id'];
    $category_qry = "SELECT * FROM tbl_cat WHERE c_id=$category_id";
    $category_result = mysqli_query($mysqli, $category_qry);
    $category_row = mysqli_fetch_assoc($category_result);
    
    // Sanitize inputs
    $c_id = intval($_POST['c_id']); // Assuming c_id is an integer
    $winner_type = sanitize($_POST['winner_type']);
    $city_id = intval($_POST['city_id']); // Assuming city_id is an integer
    $item_id = intval($_POST['item_id']); // Assuming item_id is an integer
    $o_date = sanitize($_POST['o_date']);
    $o_edate = sanitize($_POST['o_edate']);
    $o_stime = sanitize($_POST['o_stime']) . ":00"; // Add seconds set to 00
    $o_etime = sanitize($_POST['o_etime']) . ":00"; // Add seconds set to 00
    $o_amount = sanitize($_POST['o_amount']);
    $o_price = sanitize($_POST['o_price']);
    $lottery_balls_id = sanitize($_POST['lottery_balls_id']);
    $o_qty = intval($_POST['o_qty']); // Assuming o_qty is an integer
    
    // Prepare data for insertion
        $data = [
            'c_id' => $c_id,
            'winner_type' => $winner_type,
            'city_id' => $city_id,
            'item_id' => $item_id,
            'o_date' => $o_date,
            'o_edate' => $o_edate,
            'o_stime' => $o_stime,
            'o_etime' => $o_etime,
            'o_amount' => $o_amount,
            'o_price' => $o_price,
            'o_qty' => $o_qty,
            'lottery_balls_id' => $lottery_balls_id
        ];

    // Update the database with the collected data
    $user_edit = Update('tbl_offers', $data, "WHERE o_id = '" . $_POST['o_id'] . "'");

    // Check if the data exists in tbl_prizes
    $check_qry = "SELECT * FROM tbl_prizes WHERE o_id = '" . $_POST['o_id'] . "'";
    $check_result = mysqli_query($mysqli, $check_qry);
    $exists = mysqli_num_rows($check_result) > 0;

    if (!$exists) {
        // Data does not exist, insert it
        $prize_data = [
            'item_id' => $_POST['item_id'],
            'o_id' => $_POST['o_id'],
            'rank_start' => 1,
            'rank_end' => 1
        ];
    $insert_prize = Insert('tbl_prizes', $prize_data);
      if ($insert_prize > 0) {
          $_SESSION['msg'] = "38";
          header("location:lottery.php");
          exit;
      }
    } else {
        
        
        $prize_data = [
            'item_id' => $_POST['item_id'],
            'o_id' => $_POST['o_id'],
            'rank_start' => 1,
            'rank_end' => 1
        ];
        
        $update_prize = Update('tbl_prizes', $prize_data, "WHERE o_id = '" . $_POST['o_id'] . "'");
        if ($update_prize > 0) {
            $_SESSION['msg'] = "37";
            header("Location:add_lottery.php?o_id=" . $_POST['o_id']);
            exit;
        }
    }

    if ($user_edit > 0) {
        $_SESSION['msg'] = "37";
        header("Location:add_lottery.php?o_id=" . $_POST['o_id']);
        exit;
    }
}

$category_qry1 = "SELECT c_name FROM tbl_cat WHERE c_view = 2  ORDER BY RAND() LIMIT 1";
$category_result1 = mysqli_query($mysqli, $category_qry1);
$category_row1 = mysqli_fetch_assoc($category_result1);

$cat = $category_row1['c_name'];

$category_qry2 = "SELECT city_name FROM tbl_city ORDER BY RAND() LIMIT 1";
$category_result2 = mysqli_query($mysqli, $category_qry2);      
$category_row2 = mysqli_fetch_assoc($category_result2);

$city = $category_row2['city_name'];

$settings_qry = "SELECT * FROM tbl_settings";
$settings_result = mysqli_query($mysqli, $settings_qry);      
$settings_row = mysqli_fetch_assoc($settings_result);

$currency = $settings_row['currency'];	
?>
 	
<style>
    .block_wallpaper img {
  width: 200px;
  height: 200px;
   /* Keeps aspect ratio while fitting image */
}

.fileupload_img.replaced .database-image {
  display: none; /* Hide existing image when replaced class is present */
}
</style>
<head>
<title><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['lottery']; ?></title>
</head>
 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['lottery']; ?></div>
            </div>
          </div>
          <!--<?php if (isset($_GET['o_id'])) { ?>
           <strong><center><?php echo $client_lang['lottery_prizes']; ?></center></strong>
           <p><center><?php echo $client_lang['lottery_prizes_help']; ?></center></p>
           
          <div class="col-md-7 text-right">
            <a href="prizes.php?o_id=<?php echo $_GET['o_id']; ?>" class="btn btn-primary"><?php echo $client_lang['add_prizes']; ?></a>
          </div>
        <?php } ?>-->

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
            	<input  type="hidden" name="o_id" value="<?php echo $_GET['o_id'];?>" />

              <div class="section">
                <div class="section-body">
                    
                <strong><center><?php echo $client_lang['lottery_details']; ?></center></strong>
                <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['item']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_item_help']; ?></p></label>
                       <div class="col-md-6">
                            <select name="item_id" id="item_id" style="width:280px; height:25px;" class="select2" required>
                              <option value=""><?php echo $client_lang['lottery_select_item']; ?></option>
                               <?php
                                   $items_qry = "SELECT * FROM tbl_items WHERE item_status =1 ORDER BY item_id DESC";
                                   $items_result = mysqli_query($mysqli, $items_qry);
                                   while($items_row = mysqli_fetch_assoc($items_result)) {
                                   $selected = ($items_row['item_id'] == $user_row['item_id']) ? 'selected' : '';
                                   echo '<option value="'.$items_row['item_id'].'" '.$selected.'>'.$items_row['o_name'].' ('.$client_lang['worth'].' '.$currency.$items_row['price'].')'.'</option>';
                                }
                                ?>
                            </select>
                       </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_balls']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_balls_help']; ?></p></label>
                       <div class="col-md-6">
                            <select name="lottery_balls_id" id="lottery_balls_id" style="width:280px; height:25px;" class="select2" required>
                              <option value=""><?php echo $client_lang['lottery_balls_select']; ?></option>
                               <?php
                                   $balls_qry = "SELECT * FROM tbl_lottery_balls";
                                   $balls_result = mysqli_query($mysqli, $balls_qry);
                                   while ($balls_row = mysqli_fetch_assoc($balls_result)) {
                                   $selected = (isset($user_row['lottery_balls_id']) && $user_row['lottery_balls_id'] == $balls_row['lottery_balls_id']) ? 'selected' : '';
                                   echo "<option value='{$balls_row['lottery_balls_id']}' $selected>{$balls_row['lottery_balls_name']} ({$balls_row['premium_ball_limit']} Powerballs &amp; {$balls_row['normal_ball_limit']} Normal Balls)</option>";
                              }
                                ?>
                            </select>
                       </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_price']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_price_help']; ?></p></label>
                    <div class="col-md-6">
                         <div class="input-group">
                      <input type="text" name="o_amount" title="players will be charged few coins for each ticket they purchase" id="o_amount" placeholder="eg. 1"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_amount'];}?>" class="form-control">
                     <span class="input-group-addon">Coins</span>
                      </div>
                      </div>
                  </div>
                  
                  <div class="form-group">
                   <label class="col-md-3 control-label"><?php echo $client_lang['maximum_tickets']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['maximum_tickets_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="o_qty" id="o_qty" title="maximum tickets that can be generated" placeholder="eg.500" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_qty'];}?>" class="form-control">
                    </div>
                  </div>
                  
                  
                   <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_pool']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_pool_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                             <span class="input-group-addon"><?php
                                     $currency_qry = "SELECT currency FROM tbl_settings";
                                     $currency_result = mysqli_query($mysqli, $currency_qry);
                                     $currency_row = mysqli_fetch_assoc($currency_result);
                                     echo $currency_row['currency'];?>
                             </span>
                      <input type="text" name="o_price" id="o_price" title="total prize pool of the lottery" placeholder="eg. 15000"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_price'];}?>" class="form-control" required>
                    </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $client_lang['category']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_category_help']; ?></p></label>
                     <div class="col-md-6">
                          <select name="c_id" id="c_id" style="width:280px; height:25px;" class="select2" required>
                             <?php
                                 $category_qry = "SELECT c_id, c_name, c_image FROM tbl_cat WHERE c_view = 2";
                                 $category_result = mysqli_query($mysqli, $category_qry);
                                 while($category_row = mysqli_fetch_assoc($category_result)) {
                                 $selected = ($category_row['c_id'] == $user_row['c_id']) ? 'selected' : '';
                                 echo '<option value="'.$category_row['c_id'].'" '.$selected.'>'.$category_row['c_name'].'</option>';
                              }
                              ?>
                          </select>
                     </div>
                </div>
                  
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['safemode']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['safemode_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="winner_type" title="Safe Mode helps you to stay risk free" id="winner_type" style="width:280px; height:25px;" class="select2" required>
                            <option value="0" <?php if($user_row['winner_type']=='0'){?>selected<?php }?>><?php echo $client_lang['safemode_activate']; ?></option>
                            <option value="1" <?php if($user_row['winner_type']=='1'){?>selected<?php }?>><?php echo $client_lang['safemode_deactivate']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                  <strong><center><?php echo $client_lang['lottery_validity']; ?></center></strong>
                    <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_start_date']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_start_date_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="date" name="o_date" title="When the lottery should start? and user can purchase ticket" id="o_date"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_date'];}?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_start_time']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_start_time_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="time" name="o_stime" title="When the lottery should start? and user can purchase ticket" id="o_stime" step="60" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_stime'];}?>" class="form-control">
                    </div>
                  </div>
                  
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_end_date']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_end_date_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="date" name="o_edate" title="When the lottery should end? and winner should be announced" id="o_edate" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_edate'];}?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['lottery_end_time']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['lottery_end_time_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="time" name="o_etime" title="When the lottery should end? and winner should be announced" id="o_etime" step="60" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_etime'];}?>" class="form-control">
                    </div>
                  </div>
                  
                  
                   <!--<strong><center><?php echo $client_lang['image_gallery']; ?></center></strong>
                    <hr>
                
                
                        <div class="form-group">
                            <label class="col-md-3 control-label">1st Prize Image :-
                                <p class="control-label-help">(Recommended Image Size:- 512 * 512)</p>
                            </label>
                            <div class="col-md-6">
                                <div class="fileupload_block">
                                    <input type="file" name="o_image" value="fileupload" id="fileupload" onchange="previewImage(this)">
                                    <div class="fileupload_img">
                                        <?php if(isset($_GET['o_id']) and $user_row['o_image']!="") {?>
                                            <img src="../seller/images/<?php echo $user_row['o_image'];?>" alt="category image" class="database-image">
                                        <?php } ?>
                                        <img id="preview_img" src="#" alt="Preview image" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div><br>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Image Gallery: (optional)</label>
                            <div class="col-md-6">
                                <?php for ($i = 1; $i <= 4; $i++) { ?>
                                    <div class="fileupload_block">
                                        <input type="file" name="o_image<?php echo $i; ?>" value="fileupload" id="fileupload_<?php echo $i; ?>" onchange="previewImage(this)">
                                        <div class="fileupload_img">
                                            <?php if (isset($_GET['o_id']) && $user_row['o_image' . $i] != "") { ?>
                                                <img src="../seller/images/<?php echo $user_row['o_image' . $i]; ?>" alt="category image" class="database-image">
                                            <?php } ?>
                                            <img id="preview_img<?php echo $i; ?>" src="#" alt="Preview image" style="display: none;">
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <script>
                        function previewImage(input) {
                            var preview = document.getElementById("preview_img" + (input.id.slice(-1) || ""));
                            var reader = new FileReader();
                        
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.style.display = "initial"; // Show preview image
                        
                                // Hide the existing image
                                var databaseImage = preview.closest(".fileupload_img").querySelector(".database-image");
                                if (databaseImage) {
                                    databaseImage.style.display = "none";
                                }
                            };
                        
                            if (input.files.length > 0) { // Check if image selected
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                        </script>-->
                  
                  <strong><center>Region (where you want to show this lottery)</center></strong>
                  
                  <hr>
                
                 <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $client_lang['city']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['city_help']; ?></p></label>
                     <div class="col-md-6">
                          <select name="city_id" id="city_id" style="width:280px; height:25px;" class="select2" required>
                            <option value=""><?php echo $client_lang['select_city']; ?></option>
                             <?php
                                 $city_qry = "SELECT city_id, city_name, city_image FROM tbl_city";
                                 $city_result = mysqli_query($mysqli, $city_qry);
                                 while($city_row = mysqli_fetch_assoc($city_result)) {
                                 $selected = ($city_row['city_id'] == $user_row['city_id']) ? 'selected' : '';
                                 echo '<option value="'.$city_row['city_id'].'" '.$selected.'>'.$city_row['city_name'].'</option>';
                              }
                              ?>
                          </select>
                     </div>
                </div>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['save']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['lottery']; ?></button>
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