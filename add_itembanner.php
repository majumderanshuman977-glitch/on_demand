<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

if (isset($_POST['submit']) && isset($_GET['add'])) {
    if ($_FILES['o_image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'svg');
        $fileExtension = strtolower(pathinfo($_FILES['o_image']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $category_image = rand(0, 99999) . "_" . $_FILES['o_image']['name'];

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["o_image"]["tmp_name"], $tpath1);
            
            $item_oid= $_POST['o_link'];
            $category_qry = "SELECT * FROM tbl_offers WHERE o_id=$item_oid";
            $category_result = mysqli_query($mysqli, $category_qry);
            $category_row = mysqli_fetch_assoc($category_result);
            
            // Insert into tbl_items first
            $item_data = array(
                'o_name' => $category_row['o_name'],
                'o_desc' => $category_row['o_desc'],
                'o_image' => $category_image,
                'item_cat' => 'banner', // Adjust as per your data structure
                'price' => 0, // Adjust as per your data structure
                'item_status' => 0
            );

            $item_qry = Insert('tbl_items', $item_data);
            $item_id = mysqli_insert_id($mysqli); // Get the last inserted item ID

            // Now insert data into tbl_offers
            $o_price = calculateOPrice($category_row['o_type']); // Function to calculate o_price based on o_type

            $data = array(
                'id' => 1,
                'c_id' => 1,
                'city_id' => $category_row['city_id'],
                'item_id' => $item_id, // Reference to the newly inserted item
                'o_date'  =>  $category_row['o_date'],
                'o_edate'  =>  $category_row['o_edate'],
                'o_stime'  =>  $category_row['o_stime'], 
                'o_etime'  =>  $category_row['o_etime'], 
                'o_link'  =>  'o_id='.$category_row['o_id'],
                'o_type'  => 6,
                'o_price'  =>  $o_price,
                'o_status' => 1
            );

            $qry = Insert('tbl_offers', $data);

            $_SESSION['msg'] = "34";
            header("location:banners.php");
            exit;
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "Invalid file type. Please upload an image.";
            header("location:add_itembanner.php");
            exit;
        }
    }
}

if (isset($_GET['o_id'])) {
    $user_qry = "SELECT * FROM tbl_offers WHERE o_id='".$_GET['o_id']."'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);

    // Fetch item details if needed
    $item_qry = "SELECT * FROM tbl_items WHERE item_id='".$user_row['item_id']."'";
    $item_result = mysqli_query($mysqli, $item_qry);
    $item_row = mysqli_fetch_assoc($item_result);
}

if (isset($_POST['submit']) && isset($_POST['o_id'])) {
    if ($_FILES['o_image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array("jpg", "jpeg", "png", "gif"); // Line 5
        $fileExtension = strtolower(pathinfo($_FILES['o_image']['name'], PATHINFO_EXTENSION)); // Line 6

        if (in_array($fileExtension, $allowedExtensions)) { // Line 7
            $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_offers WHERE o_id=' . $_GET['o_id']);
            $img_res_row = mysqli_fetch_assoc($img_res);

            if ($img_res_row['o_image'] != "") {
                unlink('../seller/images/thumbs/' . $img_res_row['o_image']);
                unlink('../seller/images/' . $img_res_row['o_image']);
            }

            $category_image = rand(0, 99999) . "_" . $_FILES['o_image']['name'];

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["o_image"]["tmp_name"], $tpath1);
            
            // Remove 'o_id=' from  value if present
            $item_oid = ltrim($_POST['o_link'], 'o_id=');
            
            $category_qry = "SELECT * FROM tbl_offers WHERE o_id=$item_oid";
            $category_result = mysqli_query($mysqli, $category_qry);
            $category_row = mysqli_fetch_assoc($category_result);
            
            $o_price = calculateOPrice($category_row['o_type']); // Function to calculate o_price based on o_type
            
            // Update tbl_items
            $item_data = array(
                'o_name' => $_POST['o_name'],
                'o_desc' => $_POST['o_desc'],
                'o_image' => $category_image,
                'item_cat' => 'banner',
                'price' => 0,
                'item_status' => 0,
            );

            $item_edit = Update('tbl_items', $item_data, "WHERE item_id = '" . $user_row['item_id'] . "'");

            $data = array(
                'c_id' => 1,
                'city_id' => $category_row['city_id'],
                'o_date'  =>  $category_row['o_date'],
                'o_edate'  =>  $category_row['o_edate'],
                'o_stime'  =>  $category_row['o_stime'],
                'o_etime'  =>  $category_row['o_etime'],
                'o_link'  =>  'o_id='.$category_row['o_id'],
                'o_type'  => 6,
                'o_price'  =>  $o_price
                );

            $user_edit = Update('tbl_offers', $data, "WHERE o_id = '" . $_POST['o_id'] . "'");
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "Invalid file type. Please upload an image.";
            header("location:add_itembanner.php?o_id=" . $_POST['o_id']);
            exit;
        }
    } else {
        
            $item_oid= $_POST['o_link'];
            $category_qry = "SELECT * FROM tbl_offers WHERE o_id=$item_oid";
            $category_result = mysqli_query($mysqli, $category_qry);
            $category_row = mysqli_fetch_assoc($category_result);
            
            $o_price = calculateOPrice($category_row['o_type']); // Function to calculate o_price based on o_type
            
            // Update tbl_items
            $item_data = array(
                'o_name' => $_POST['o_name'],
                'o_desc' => $_POST['o_desc'],
                'item_cat' => 'banner',
                'price' => 0,
                'item_status' => 0,
            );

            $item_edit = Update('tbl_items', $item_data, "WHERE item_id = '" . $user_row['item_id'] . "'");
            
        $data = array(
            'c_id' => 1,
             'city_id' => $category_row['city_id'],
                'o_date'  =>  $category_row['o_date'],
                'o_edate'  =>  $category_row['o_edate'],
                'o_stime'  =>  $category_row['o_stime'], 
                'o_etime'  =>  $category_row['o_etime'], 
                'o_link'  =>  'o_id='.$category_row['o_id'],
                'o_type'  => 6,
                'o_price'  =>  $o_price,
        );

        $user_edit = Update('tbl_offers', $data, "WHERE o_id = '" . $_POST['o_id'] . "'");
    }

    if ($user_edit > 0){
        $_SESSION['msg'] = "33";
        header("Location:add_itembanner.php?o_id=" . $_POST['o_id']);
        exit;
    }
}

// Function to calculate o_price based on o_type
function calculateOPrice($o_type) {
    if (in_array($o_type, array(1, 2, 4, 5, 7, 8))) {
        return 1;
    } elseif (in_array($o_type, array(3, 9))) {
        return 2;
    } else {
        // Default value or error handling if necessary
        return 0; // Set a default value or handle error case
    }
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
            	<input  type="hidden" name="o_id" value="<?php echo $_GET['o_id'];?>" />

              <div class="section">
                <div class="section-body">
                    
                <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $client_lang['banner_item']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['banner_item_help']; ?></p></label>
                     <div class="col-md-6">
                          <select name="o_link" id="o_link" style="width:280px; height:25px;" class="select2" required>
                            <option value=""><?php echo $client_lang['select_item']; ?></option>
                             <?php
                                 $category_qry = "SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE o_type IN (1,2,3,4,5,7,8,9) AND item_status = 1 ORDER BY tbl_items.item_id DESC";
                                        $category_result = mysqli_query($mysqli, $category_qry);
                                 while($category_row = mysqli_fetch_assoc($category_result)) {
                                    $item_id = intval(substr($user_row['o_link'], strpos($user_row['o_link'], '=') + 1));
                                    $selected = ($category_row['o_id'] == $item_id) ? 'selected' : '';
                                    echo '<option value="'.$category_row['o_id'].'" '.$selected.'>'.$category_row['o_name'].' (Ends on:- '.$category_row['o_edate'].')'.'</option>';
                              }
                              ?>
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
                            <div class="block_wallpaper"><img src="../seller/images/<?php echo $item_row['o_image'];?>" alt="category image" /></div>
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