<?php include('includes/header.php');

    include('includes/function.php');
	include('language/language.php'); 

 	require_once("thumbnail_images.class.php");
	 
	 

if (isset($_POST['submit']) && isset($_GET['add'])) {
    if ($_FILES['o_image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $fileExtension = strtolower(pathinfo($_FILES['o_image']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $category_image = rand(0, 99999) . "_" . $_FILES['o_image']['name'];

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["o_image"]["tmp_name"], $tpath1);

            $data = array(
            'kyc_status'  =>  $_POST['kyc_status'],
            );

            $qry = Insert('tbl_kyc', $data);

            $_SESSION['msg'] = "10";
            header("location:kyc.php");
            exit;
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "Invalid file type. Please upload an image.";
            header("location:manage_kyc.php");
            exit;
        }
    }
}

	if(isset($_GET['kyc_id']))
	{
			 
			$user_qry="SELECT * FROM tbl_kyc where kyc_id='".$_GET['kyc_id']."'";
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
if (isset($_POST['submit']) && isset($_POST['kyc_id'])) {
    if ($_FILES['o_image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedExtensions = array("jpg", "jpeg", "png", "gif"); // Line 5
        $fileExtension = strtolower(pathinfo($_FILES['o_image']['name'], PATHINFO_EXTENSION)); // Line 6

        if (in_array($fileExtension, $allowedExtensions)) { // Line 7
            $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_kyc WHERE kyc_id=' . $_GET['kyc_id'] . '');
            $img_res_row = mysqli_fetch_assoc($img_res);

            if ($img_res_row['o_image'] != "") {
                unlink('../seller/images/thumbs/' . $img_res_row['o_image']);
                unlink('../seller/images/' . $img_res_row['o_image']);
            }

            $category_image = rand(0, 99999) . "_" . $_FILES['o_image']['name'];

            // Main Image
            $tpath1 = '../seller/images/' . $category_image;
            move_uploaded_file($_FILES["o_image"]["tmp_name"], $tpath1);

            $data = array(
            'kyc_status'  =>  $_POST['kyc_status'],
            );

            $user_edit = Update('tbl_kyc', $data, "WHERE kyc_id = '" . $_POST['kyc_id'] . "'");
        } else {
            // Invalid file type, display an error message
            $_SESSION['msg'] = "Invalid file type. Please upload an image.";
            header("location:manage_kyc.php?kyc_id=" . $_POST['kyc_id']);
            exit;
        }
    } else {
        $data = array(
           'kyc_status'  =>  $_POST['kyc_status'],
        );

        $user_edit = Update('tbl_kyc', $data, "WHERE kyc_id = '" . $_POST['kyc_id'] . "'");
    }

    if ($user_edit > 0){
        $_SESSION['msg'] = "11";
        header("Location:manage_kyc.php?kyc_id=" . $_POST['kyc_id']);
        exit;
    }
}


?>
 	
<head>
<title><?php echo $client_lang['manage_id_proof']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_id_proof']; ?></div>
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
            	<input  type="hidden" name="kyc_id" value="<?php echo $_GET['kyc_id'];?>" />

              <div class="section">
                <div class="section-body">
                    
                    <p><strong><?php echo $client_lang['first_name']; ?>:- </strong><?php if(isset($_GET['kyc_id'])){echo $user_row['id_firstname'];}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $client_lang['last_name']; ?>:- </strong><?php if(isset($_GET['kyc_id'])){echo $user_row['id_lastname'];}?></p>
                    <p><strong><?php echo $client_lang['dob']; ?>:- </strong><?php if(isset($_GET['kyc_id'])){echo $user_row['dob'];}?></p>
                    <p><strong><?php echo $client_lang['country']; ?>:- </strong><?php if(isset($_GET['kyc_id'])){echo $user_row['id_country'];}?></p>
                    <p><strong><?php echo $client_lang['document_type']; ?>:- </strong>
                        <?php 
                        if($user_row['id_type'] == '1') {
                            echo $client_lang['driving_license'];
                        } else if($user_row['id_type'] == '2') {
                            echo $client_lang['national_id'];
                        } else if($user_row['id_type'] == '3') {
                            echo $client_lang['passport'];
                        } else if($user_row['id_type'] == '4') {
                            echo $client_lang['other_document'];
                        }
                        ?>
                    </p>
                    <p><strong><?php echo $client_lang['document_number']; ?>:- </strong><?php if(isset($_GET['kyc_id'])){echo $user_row['id_number'];}?></p>
                    <p><strong><?php echo $client_lang['document_front']; ?>:- </strong><img src="../seller/images/<?php echo $user_row['id_front'];?>" alt="id front image" /></p>
                    <p><strong><?php echo $client_lang['document_back']; ?>:- </strong><img src="../seller/images/<?php echo $user_row['id_back'];?>" alt="id back image" /></p>
                    
                 
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['document_status']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['document_status_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="kyc_status" id="kyc_status" style="width:280px; height:25px;" class="select2" required>
                            <option value="0" <?php if($user_row['kyc_status']=='0'){?>selected<?php }?>><?php echo $client_lang['incomplete']; ?></option>
                            <option value="1" <?php if($user_row['kyc_status']=='1'){?>selected<?php }?>><?php echo $client_lang['pending']; ?></option>
                            <option value="2" <?php if($user_row['kyc_status']=='2'){?>selected<?php }?>><?php echo $client_lang['completed']; ?></option>
                            <option value="3" <?php if($user_row['kyc_status']=='3'){?>selected<?php }?>><?php echo $client_lang['rejected']; ?></option>
                        </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php echo $client_lang['update']; ?></button>
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