<?php include('includes/header.php');

    include('includes/function.php');
	include('language/language.php'); 

 	require_once("thumbnail_images.class.php");
	 
	 
// Handling form submission for adding a vendor
if (isset($_POST['submit']) && isset($_GET['add'])) {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['name']);
    $balance = sanitize($_POST['balance']);
    $about = sanitize($_POST['about']);
    $password = sanitize($_POST['password']);
    $permission_auction = 0;
    $permission_lottery = sanitize($_POST['permission_lottery']);
    $permission_shop = sanitize($_POST['permission_shop']);
    $permission_coin = sanitize($_POST['permission_coin']);

    if (!empty($_FILES['image']['name'])) {
        if (isValidImage($_FILES['image'])) {
            $category_image = rand(0, 99999) . "_" . $_FILES['image']['name'];
            $tpath1 = '../seller/images/' . $category_image;
            compress_image($_FILES["image"]["tmp_name"], $tpath1, 80);

            // Create Thumbnail
            $thumbpath = '../seller/images/thumbs/' . $category_image;
            createThumbnail($tpath1, $thumbpath, 300);

            $data = [
                'username' => $username,
                'email' => $email,
                'balance' => $balance,
                'image' => $category_image,
                'about' => $about,
                'password' => $password,
                'permission_auction' => $permission_auction,
                'permission_lottery' => $permission_lottery,
                'permission_shop' => $permission_shop,
                'permission_coin' => $permission_coin,
            ];

            $qry = Insert('tbl_vendor', $data);

            $_SESSION['msg'] = "10";
            header("Location: vendors.php");
            exit;
        } else {
            echo "Uploaded file is not a valid image.";
        }
    } else {
        echo "No new image uploaded.";
    }
}

// Handling form submission for editing a vendor
if (isset($_POST['submit']) && isset($_POST['id'])) {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['name']);
    $balance = sanitize($_POST['balance']);
    $about = sanitize($_POST['about']);
    $password = sanitize($_POST['password']);
    $permission_auction = 0;
    $permission_lottery = sanitize($_POST['permission_lottery']);
    $permission_shop = sanitize($_POST['permission_shop']);
    $permission_coin = sanitize($_POST['permission_coin']);

    if (!empty($_FILES['image']['name'])) {
        if (isValidImage($_FILES['image'])) {
            $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_vendor WHERE id=' . $_GET['id']);
            $img_res_row = mysqli_fetch_assoc($img_res);

            if (!empty($img_res_row['image'])) {
                unlink('../seller/images/thumbs/' . $img_res_row['image']);
                unlink('../seller/images/' . $img_res_row['image']);
            }

            $category_image = rand(0, 99999) . "_" . $_FILES['image']['name'];
            $tpath1 = '../seller/images/' . $category_image;
            compress_image($_FILES["image"]["tmp_name"], $tpath1, 80);

            // Create Thumbnail
            $thumbpath = '../seller/images/thumbs/' . $category_image;
            createThumbnail($tpath1, $thumbpath, 300);

            $data = [
                'username' => $username,
                'email' => $email,
                'balance' => $balance,
                'image' => $category_image,
                'about' => $about,
                'password' => $password,
                'permission_auction' => $permission_auction,
                'permission_lottery' => $permission_lottery,
                'permission_shop' => $permission_shop,
                'permission_coin' => $permission_coin,
            ];

            $user_edit = Update('tbl_vendor', $data, "WHERE id = '" . $_POST['id'] . "'");
        } else {
            echo 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
        }
    } else {
        $data = [
            'username' => $username,
            'email' => $email,
            'balance' => $balance,
            'image' => $_POST['image'],
            'about' => $about,
            'password' => $password,
            'permission_auction' => $permission_auction,
            'permission_lottery' => $permission_lottery,
            'permission_shop' => $permission_shop,
            'permission_coin' => $permission_coin,
        ];

        $user_edit = Update('tbl_vendor', $data, "WHERE id = '" . $_POST['id'] . "'");
    }

    if ($user_edit > 0) {
        $_SESSION['msg'] = "11";
        header("Location: add_vendors.php?id=" . $_POST['id']);
        exit;
    }
}

// Fetching vendor details
if (isset($_GET['id'])) {
    $user_qry = "SELECT * FROM tbl_vendor WHERE id='" . sanitize($_GET['id']) . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}
?>
<head>
<title><?php if(isset($_GET['id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['seller']; ?></title>
</head>
 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['seller']; ?></div>
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
                    <label class="col-md-3 control-label"><?php echo $client_lang['seller_name']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['seller_name_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="name" id="name" placeholder="enter seller name" value="<?php if(isset($_GET['id'])){echo $user_row['email'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['seller_email']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['seller_email_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="username" id="username" placeholder="enter vendor email id" value="<?php if(isset($_GET['id'])){echo $user_row['username'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_password']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['seller_password_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password"placeholder="enter seller login password" value="<?php if(isset($_GET['id'])){echo $user_row['password'];}?>" class="form-control" <?php if(!isset($_GET['id'])){ echo $user_row['password'];?>required<?php }?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['seller_about']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['seller_about_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="about" id="about" placeholder="enter about the seller" value="<?php if(isset($_GET['id'])){echo $user_row['about'];}?>" class="form-control">
                    </div>
                  </div>
                  
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['seller_balance']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['seller_balance_help']; ?></p></label>
                    <div class="col-md-6">
                         <div class="input-group">
                      <input type="text" name="balance" id="balance" placeholder="eg. 100" value="<?php if(isset($_GET['id'])){echo $user_row['balance'];}?>" class="form-control">
                       <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                  </div>
                 </div>
                 
                 <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $client_lang['seller_image']; ?> :-
                            <p class="control-label-help"><?php echo $client_lang['image_size']; ?></p>
                        </label>
                        <div class="col-md-6">
                            <div class="fileupload_block">
                                <input type="file" name="image" id="fileupload" required>
                                <div class="fileupload_img">
                                    <img src="assets/images/add-image.png" alt="category image" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">&nbsp; </label>
                        <div class="col-md-6">
                            <?php if (isset($_GET['id']) && $user_row['image'] != "") { ?>
                                <div class="block_wallpaper">
                                    <img src="../seller/images/<?php echo $user_row['image'];?>" alt="category image" />
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                 <center><strong><?php echo $client_lang['seller_permission']; ?></strong></center><br>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['permission_lottery']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['permission_lottery_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="permission_lottery" id="permission_lottery" style="width:280px; height:25px;" class="select2" required>
                            <option value="1" <?php if($user_row['permission_lottery']=='1'){?>selected<?php }?>><?php echo $client_lang['allowed']; ?></option>
                            <option value="2" <?php if($user_row['permission_lottery']=='0'){?>selected<?php }?>><?php echo $client_lang['not_allowed']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['permission_shop']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['permission_shop_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="permission_" id="permission_shop" style="width:280px; height:25px;" class="select2" required>
                            <option value="1" <?php if($user_row['permission_shop']=='1'){?>selected<?php }?>><?php echo $client_lang['allowed']; ?></option>
                            <option value="2" <?php if($user_row['permission_shop']=='0'){?>selected<?php }?>><?php echo $client_lang['not_allowed']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['permission_coin']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['permission_coin_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="permission_coin" id="permission_coin" style="width:280px; height:25px;" class="select2" required>
                            <option value="1" <?php if($user_row['permission_coin']=='1'){?>selected<?php }?>><?php echo $client_lang['allowed']; ?></option>
                            <option value="2" <?php if($user_row['permission_coin']=='0'){?>selected<?php }?>><?php echo $client_lang['not_allowed']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['seller']; ?></button>
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