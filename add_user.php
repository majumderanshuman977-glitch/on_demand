<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

if (isset($_POST['submit']) && isset($_GET['add'])) {		
    // Sanitize input values
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = sanitize($_POST['password']);
    $wallet = sanitize($_POST['wallet']);

    // Prepare data array
    $data = array(
        'name'  => $name,
        'email'  => $email,
        'phone'  => $phone,
        'password'  => $password,
        'wallet'  => $wallet,
        'status' => 1
    );

    // Insert data into tbl_users
    $qry = Insert('tbl_users', $data);

    $_SESSION['msg'] = "10";
    header("location:users.php");	 
    exit;
}

if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']);
    $user_qry = "SELECT * FROM tbl_users WHERE id='$id'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['id'])) {
    // Sanitize input values
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = sanitize($_POST['password']);
    $wallet = sanitize($_POST['wallet']);
    $id = sanitize($_POST['id']);

    // Prepare data array for update
    $data = array(
        'name'  => $name,
        'email'  => $email,
        'phone'  => $phone,
        'password'  => $password,
        'wallet'  => $wallet,
    );

    // Update data in tbl_users
    $user_edit = Update('tbl_users', $data, "WHERE id = '$id'");

    if ($user_edit > 0) {
        $_SESSION['msg'] = "11";
        header("Location:add_user.php?id=$id");
        exit;
    } 	
}

$querytime = "SELECT demo_access FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
$demo_access = sanitize($rowtime['demo_access']);	 
?>

 	
<head>
<title><?php if(isset($_GET['id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['user']; ?></title>
</head>
 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['user']; ?></div>
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
                  <label class="col-md-3 control-label"><?php echo $client_lang['user_name']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['user_name_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" placeholder="enter user name "  name="name" id="name" value="<?php if(isset($_GET['id'])){echo $user_row['name'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_email']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['user_email_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="email" <?php if ($demo_access == 1) { ?>placeholder="Hidden@Demo"<?php } else { ?>value="<?php if(isset($_GET['id'])){echo $user_row['email'];}?>"<?php } ?>  name="email" id="email" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_password']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['user_password_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="password" name="password" id="password" <?php if ($demo_access == 1) { ?>placeholder="Hidden@Demo"<?php } else { ?>value="<?php if(isset($_GET['id'])){echo $user_row['password'];}?>"<?php } ?> class="form-control" <?php if(!isset($_GET['id'])){ echo $user_row['password'];?>required<?php }?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_mobile']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['user_mobile_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" <?php if ($demo_access == 1) { ?>placeholder="Hidden@Demo"<?php } else { ?>value="<?php if(isset($_GET['id'])){echo $user_row['phone'];}?>"<?php } ?>  name="phone" id="phone" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_balance']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['user_balance_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" placeholder="set user coin balance"   name="wallet" id="wallet" value="<?php if(isset($_GET['id'])){echo $user_row['wallet'];}?>" class="form-control">
                    </div>
                  </div>
                  
                 
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['user']; ?></button>
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