<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

if (isset($_POST['submit']) && isset($_GET['add'])) {
    if ($_FILES['pg_image']['name'] != "") {
        // Validate the image file
        if (isValidImage($_FILES['pg_image'])) {
            $category_image = rand(0, 99999) . "_" . sanitize($_FILES['pg_image']['name']);

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["pg_image"]["tmp_name"], $tpath1);

            $data = array(
                'pg_name'  =>  sanitize($_POST['pg_name']),
                'pg_type'  =>  4,
                'pg_link'  =>  sanitize($_POST['pg_link']),
                'pg_image' => $category_image,
                'pg_details'  =>  sanitize($_POST['pg_details']),
                'pg_status' => 1
            );

            $qry = Insert('tbl_payment_gateway', $data);

            $_SESSION['msg'] = "10";
            header("location:payment_settings.php");
            exit;
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "invalid_image";
            header("location:edit_payment_gateway.php");
            exit;
        }
    }
}

if (isset($_GET['pg_id'])) {
    $user_qry = "SELECT * FROM tbl_payment_gateway where pg_id='" . sanitize($_GET['pg_id']) . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['pg_id'])) {
    if ($_FILES['pg_image']['name'] != "") {
        // Validate the image file
        if (isValidImage($_FILES['pg_image'])) {
            $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_payment_gateway WHERE pg_id=' . sanitize($_GET['pg_id']));
            $img_res_row = mysqli_fetch_assoc($img_res);

            if ($img_res_row['pg_image'] != "") {
                unlink('../seller/images/thumbs/' . $img_res_row['pg_image']);
                unlink('../seller/images/' . $img_res_row['pg_image']);
            }

            $category_image = rand(0, 99999) . "_" . sanitize($_FILES['pg_image']['name']);

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["pg_image"]["tmp_name"], $tpath1);

            // Optional: Create a thumbnail (uncomment if needed)
            // createThumbnail($tpath1, '../seller/images/thumbs/' . $category_image, 150);

            $data = array(
                'pg_name'  =>  sanitize($_POST['pg_name']),
                'pg_link'  =>  sanitize($_POST['pg_link']),
                'pg_image' => $category_image,
                'pg_details'  =>  sanitize($_POST['pg_details']),
            );

            $user_edit = Update('tbl_payment_gateway', $data, "WHERE pg_id = '" . sanitize($_POST['pg_id']) . "'");
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "Invalid file type. Please upload an image.";
            header("location:edit_payment_gateway.php?pg_id=" . sanitize($_POST['pg_id']));
            exit;
        }
    } else {
        $data = array(
            'pg_name'  =>  sanitize($_POST['pg_name']),
            'pg_link'  =>  sanitize($_POST['pg_link']),
            'pg_details'  =>  sanitize($_POST['pg_details']),
        );

        $user_edit = Update('tbl_payment_gateway', $data, "WHERE pg_id = '" . sanitize($_POST['pg_id']) . "'");
    }

    if ($user_edit > 0) {
        $_SESSION['msg'] = "11";
        header("Location:edit_payment_gateway.php?pg_id=" . sanitize($_POST['pg_id']));
        exit;
    }
}
?>
 	

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php if(isset($_GET['pg_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['payment_mode']; ?></div>
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
            	<input  type="hidden" name="pg_id" value="<?php echo $_GET['pg_id'];?>" />

              <div class="section">
                <div class="section-body">
                   
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['payment_mode_name']; ?>:-<br>
                    <p class="control-label-help"><?php echo $client_lang['payment_mode_name_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="pg_name" id="pg_name" placeholder="eg. Bank Transfer" title="enter gateway name" value="<?php if(isset($_GET['pg_id'])){echo $user_row['pg_name'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['payment_mode_details']; ?>:-<br>
                    <p class="control-label-help"><?php echo $client_lang['payment_mode_details_help']; ?></p></label>
                    <div class="col-md-6">
                      <textarea name="pg_details" id="pg_details"  class="form-control"><?php echo $user_row['pg_details'];?></textarea>
                      <script>CKEDITOR.replace( 'pg_details' );</script>
                    </div>
                  </div>
                  <br>
                
                     <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['gateway_image']; ?>:-
                      <p class="control-label-help"><?php echo $client_lang['image_size']; ?></p>
                    </label>
                    <div class="col-md-6">
                      <div class="fileupload_block">
                        <input type="file" name="pg_image" value="fileupload" id="fileupload">
                            
                            <div class="fileupload_img"><img type="image" src="assets/images/add-image.png" alt="category image" /></div>
                           
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">&nbsp; </label>
                    <div class="col-md-6">
                      <?php if(isset($_GET['pg_id']) and $user_row['pg_image']!="") {?>
                            <div class="block_wallpaper"><img src="../seller/images/<?php echo $user_row['pg_image'];?>" alt="category image" /></div>
                          <?php } ?>
                    </div>
                  </div><br>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['pg_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['payment_mode']; ?></button>
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