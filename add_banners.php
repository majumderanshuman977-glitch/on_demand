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

if (isset($_POST['submit']) && isset($_GET['add'])) {
    if ($_FILES['o_image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileExtension = strtolower(pathinfo($_FILES['o_image']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $category_image = rand(0, 99999) . "_" . basename($_FILES['o_image']['name']);

            // Securely move uploaded file
            $tpath1 = '../seller/images/thumbs/' . $category_image;
            move_uploaded_file($_FILES["o_image"]["tmp_name"], $tpath1);

            // Insert data into tbl_items first
            $item_data = array(
                'o_name' => sanitize($_POST['o_name']),
                'o_desc' => 'banner',
                'o_image' => $category_image,
                'item_cat' => 'banner',
                'price' => 0,
                'item_status' => 0,
            );

            $stmt = $mysqli->prepare("INSERT INTO tbl_items (o_name, o_desc, o_image, item_cat, price, item_status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $item_data['o_name'], $item_data['o_desc'], $item_data['o_image'], $item_data['item_cat'], $item_data['price'], $item_data['item_status']);
            $stmt->execute();
            $item_id = $stmt->insert_id;
            $stmt->close();

            // Now insert data into tbl_offers
            $data = array(
                'id' => 1,
                'c_id' => 1,
                'city_id' => sanitize($_POST['city_id']),
                'item_id' => $item_id,
                'o_edate' => sanitize($_POST['o_edate']),
                'o_stime' => sanitize($_POST['o_stime']) . ":00",
                'o_etime' => sanitize($_POST['o_etime']) . ":00",
                'o_amount' => sanitize($_POST['o_amount']),
                'o_link' => sanitize($_POST['o_link']),
                'o_type' => 6,
                'o_min' => sanitize($_POST['o_min']),
                'o_max' => sanitize($_POST['o_max']),
                'o_price' => sanitize($_POST['o_price']),
                'o_date' => sanitize($_POST['o_date']),
                'o_status' => 1
            );

            $stmt = $mysqli->prepare("INSERT INTO tbl_offers (id, c_id, city_id, item_id, o_edate, o_stime, o_etime, o_amount, o_link, o_type, o_min, o_max, o_price, o_date, o_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisssssssiiisi", $data['id'], $data['c_id'], $data['city_id'], $data['item_id'], $data['o_edate'], $data['o_stime'], $data['o_etime'], $data['o_amount'], $data['o_link'], $data['o_type'], $data['o_min'], $data['o_max'], $data['o_price'], $data['o_date'], $data['o_status']);
            $stmt->execute();
            $stmt->close();

            $_SESSION['msg'] = "34";
            header("location:banners.php");
            exit;
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "Invalid file type. Please upload an image.";
            header("location:add_banners.php");
            exit;
        }
    }
}

if (isset($_GET['o_id'])) {
    $o_id = sanitize($_GET['o_id']);
    $stmt = $mysqli->prepare("SELECT * FROM tbl_offers WHERE o_id = ?");
    $stmt->bind_param("i", $o_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_row = $user_result->fetch_assoc();
    $stmt->close();

    // Fetch item details if needed
    $stmt = $mysqli->prepare("SELECT * FROM tbl_items WHERE item_id = ?");
    $stmt->bind_param("i", $user_row['item_id']);
    $stmt->execute();
    $item_result = $stmt->get_result();
    $item_row = $item_result->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['submit']) && isset($_POST['o_id'])) {
    $o_id = sanitize($_POST['o_id']);
    if ($_FILES['o_image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        $fileExtension = strtolower(pathinfo($_FILES['o_image']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $stmt = $mysqli->prepare('SELECT * FROM tbl_offers WHERE o_id = ?');
            $stmt->bind_param("i", $o_id);
            $stmt->execute();
            $img_res = $stmt->get_result();
            $img_res_row = $img_res->fetch_assoc();
            $stmt->close();

            if ($img_res_row['o_image'] != "") {
                unlink('../seller/images/thumbs/' . $img_res_row['o_image']);
                unlink('../seller/images/' . $img_res_row['o_image']);
            }

            $category_image = rand(0, 99999) . "_" . basename($_FILES['o_image']['name']);

            // Securely move uploaded file
            $tpath1 = '../seller/images/thumbs/' . $category_image;
            move_uploaded_file($_FILES["o_image"]["tmp_name"], $tpath1);

            // Update tbl_items
            $item_data = array(
                'o_name' => sanitize($_POST['o_name']),
                'o_desc' => 'banner',
                'o_image' => $category_image,
                'item_cat' => 'banner',
                'price' => 0,
                'item_status' => 0,
            );

            $stmt = $mysqli->prepare("UPDATE tbl_items SET o_name = ?, o_desc = ?, o_image = ?, item_cat = ?, price = ?, item_status = ? WHERE item_id = ?");
            $stmt->bind_param("sssiiii", $item_data['o_name'], $item_data['o_desc'], $item_data['o_image'], $item_data['item_cat'], $item_data['price'], $item_data['item_status'], $user_row['item_id']);
            $stmt->execute();
            $item_edit = $stmt->affected_rows;
            $stmt->close();

            // Update tbl_offers
            $data = array(
                'c_id' => 1,
                'city_id' => sanitize($_POST['city_id']),
                'o_edate' => sanitize($_POST['o_edate']),
                'o_stime' => sanitize($_POST['o_stime']) . ":00",
                'o_etime' => sanitize($_POST['o_etime']) . ":00",
                'o_amount' => sanitize($_POST['o_amount']),
                'o_link' => sanitize($_POST['o_link']),
                'o_min' => sanitize($_POST['o_min']),
                'o_max' => sanitize($_POST['o_max']),
                'o_price' => sanitize($_POST['o_price']),
                'o_date' => sanitize($_POST['o_date'])
            );

            $stmt = $mysqli->prepare("UPDATE tbl_offers SET c_id = ?, city_id = ?, o_edate = ?, o_stime = ?, o_etime = ?, o_amount = ?, o_link = ?, o_min = ?, o_max = ?, o_price = ?, o_date = ? WHERE o_id = ?");
            $stmt->bind_param("iisssssiiisi", $data['c_id'], $data['city_id'], $data['o_edate'], $data['o_stime'], $data['o_etime'], $data['o_amount'], $data['o_link'], $data['o_min'], $data['o_max'], $data['o_price'], $data['o_date'], $o_id);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        // Update tbl_items
        $item_data = array(
            'o_name' => sanitize($_POST['o_name']),
            'o_desc' => 'banner',
            'item_cat' => 'banner',
            'price' => 0,
            'item_status' => 0,
        );

        $stmt = $mysqli->prepare("UPDATE tbl_items SET o_name = ?, o_desc = ?, item_cat = ?, price = ?, item_status = ? WHERE item_id = ?");
        $stmt->bind_param("sssiii", $item_data['o_name'], $item_data['o_desc'], $item_data['item_cat'], $item_data['price'], $item_data['item_status'], $user_row['item_id']);
        $stmt->execute();
        $stmt->close();

        // Update tbl_offers
        $data = array(
            'c_id' => 1,
            'city_id' => sanitize($_POST['city_id']),
            'o_edate' => sanitize($_POST['o_edate']),
            'o_stime' => sanitize($_POST['o_stime']) . ":00",
            'o_etime' => sanitize($_POST['o_etime']) . ":00",
            'o_amount' => sanitize($_POST['o_amount']),
            'o_link' => sanitize($_POST['o_link']),
            'o_min' => sanitize($_POST['o_min']),
            'o_max' => sanitize($_POST['o_max']),
            'o_price' => sanitize($_POST['o_price']),
            'o_date' => sanitize($_POST['o_date'])
        );

        $stmt = $mysqli->prepare("UPDATE tbl_offers SET c_id = ?, city_id = ?, o_edate = ?, o_stime = ?, o_etime = ?, o_amount = ?, o_link = ?, o_min = ?, o_max = ?, o_price = ?, o_date = ? WHERE o_id = ?");
        $stmt->bind_param("iisssssiiisi", $data['c_id'], $data['city_id'], $data['o_edate'], $data['o_stime'], $data['o_etime'], $data['o_amount'], $data['o_link'], $data['o_min'], $data['o_max'], $data['o_price'], $data['o_date'], $o_id);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['msg'] = "33";
    header("location:banners.php");
    exit;
}
?>
<head>
<title><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['banner']; ?></title>
</head>

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['banner']; ?></div>
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
            	<input  type="hidden" name="o_id" value="<?php echo $_GET['o_id'];?>"/>

              <div class="section">
                <div class="section-body">
                   
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_name']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_name_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="o_name" id="o_name" placeholder="eg. Banner 1" title="enter banner name" value="<?php if(isset($_GET['o_id'])){echo $item_row['o_name'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <!--<div class="form-group">-->
                  <!--  <label class="col-md-3 control-label"><?php echo $client_lang['banner_link']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_link_help']; ?></p></label>-->
                  <!--  <div class="col-md-6">-->
                  <!--    <input type="text" name="o_link" id="o_link" placeholder="eg. https://wowcodes.in/" title="enter website link"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_link'];}?>" class="form-control">-->
                  <!--  </div>-->
                  <!--</div>-->
                
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_valid_from_date']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_valid_from_date_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="date" name="o_date" title="When the banner should start showing?" id="o_date"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_date'];}?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_valid_from_time']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_valid_from_time_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="time" name="o_stime" title="When the banner should start showing?" id="o_stime" step="60" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_stime'];}?>" class="form-control">
                    </div>
                  </div>
                  
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_valid_to_date']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_valid_to_date_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="date" name="o_edate" title="When the banner should stop showing?" id="o_edate" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_edate'];}?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_valid_to_time']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_valid_to_time_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="time" name="o_etime" title="When the banner should stop showing?" id="o_etime" step="60" value="<?php if(isset($_GET['o_id'])){echo $user_row['o_etime'];}?>" class="form-control">
                    </div>
                  </div>
                  
                 <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $client_lang['city']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['city_help']; ?></p></label>
                     <div class="col-md-6">
                          <select name="city_id" id="city_id" style="width:280px; height:25px;" class="select2" required>
                            <!--<option value=""><?php echo $client_lang['select_city']; ?></option>-->
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
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_location']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_location_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="o_price" id="o_price" style="width:280px; height:25px;" class="select2" required>
                            <!--<option value="">- <?php echo $client_lang['select_banner_location']; ?> -</option>-->
                            <option value="1" <?php if($user_row['o_price']=='1'){?>selected<?php }?>><?php echo $client_lang['banner_location_home']; ?></option>
                            <option value="2" <?php if($user_row['o_price']=='2'){?>selected<?php }?>><?php echo $client_lang['banner_location_shop']; ?></option>
                            <option value="3" <?php if($user_row['o_price']=='3'){?>selected<?php }?>><?php echo $client_lang['banner_location_redeem']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                
                     <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['banner_image']; ?>:-
                      <p class="control-label-help"><?php echo $client_lang['banner_size']; ?></p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="o_image" value="fileupload" id="fileupload">
                            
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                           
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp; </label>
                    <div class="col-md-6">
                      <?php if(isset($_GET['o_id']) and $item_row['o_image']!="") {?>
                            <div class="block_wallpaper"><img src="../seller/images/thumbs/<?php echo $item_row['o_image'];?>" alt="category image" /></div>
                          <?php } ?>
                    </div>
                  </div><br>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['banner']; ?></button>
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