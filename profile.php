<?php include("includes/header.php");

	require("includes/function.php");
	require("language/language.php");
	 
	
	if(isset($_SESSION['id']))
	{
			 
		$qry="select * from tbl_admin where id='".$_SESSION['id']."'";
		 
		$result=mysqli_query($mysqli,$qry);
		$row=mysqli_fetch_assoc($result);

	}
if (isset($_POST['submit'])) {
    if ($_FILES['image']['name'] != "") {
        // Check if the uploaded file is an image
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif'); // Add more image extensions if needed
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedTypes)) {
            // Valid image file
            $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_admin WHERE id=' . $_SESSION['id'] . '');
            $img_res_row = mysqli_fetch_assoc($img_res);

            if ($img_res_row['image'] != "") {
                unlink('images/' . $img_res_row['image']);
            }

            $image = "profile.png";
            $pic1 = $_FILES['image']['tmp_name'];
            $tpath1 = 'images/' . $image;

            copy($pic1, $tpath1);

            $data = array(
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'email' => $_POST['email'],
                'image' => $image
            );

            $channel_edit = Update('tbl_admin', $data, "WHERE id = '" . $_SESSION['id'] . "'");
        } else {
            // Invalid file type
            $_SESSION['msg'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        $data = array(
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'email' => $_POST['email']
        );

        $channel_edit = Update('tbl_admin', $data, "WHERE id = '" . $_SESSION['id'] . "'");
    }

    $_SESSION['msg'] = "11";
    header("Location: profile.php");
    exit;
}



?>
 
	 <div class="row">
      <div class="col-md-12">
        <div class="card">
		  <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Admin Profile</div>
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
          	  
            <form action="" name="editprofile" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
               
                  <div class="form-group">
                    <label class="col-md-3 control-label">Username :-</label>
                    <div class="col-md-6">
                      <input type="text" name="username" id="username" value="<?php echo $row['username'];?>" class="form-control" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Password :-</label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password" value="<?php echo $row['password'];?>" class="form-control" required autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Email :-</label>
                    <div class="col-md-6">
                      <input type="text" name="email" id="email" value="<?php echo $row['email'];?>" class="form-control" required autocomplete="off">
                    </div>
                  </div>                 
                   
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

        
<?php include("includes/footer.php");?>       
