<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

// HTTPS redirection
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}


if (isset($_POST['submit']) && isset($_GET['add'])) {
    $data = [];

    if ($_FILES['c_image']['name'] != "" && isValidImage($_FILES['c_image'])) {
        $image_filename = rand(0, 99999) . "_" . $_FILES['c_image']['name'];
        $target_path = '../seller/images/' . $image_filename;

        if (move_uploaded_file($_FILES['c_image']['tmp_name'], $target_path)) {
            createThumbnail($target_path, $target_path, 200);
            $data['c_image'] = $image_filename;
        } else {
            echo "File upload failed";
        }
    } else {
        $data['c_image'] = null;
    }

    $c_color = '000000';
    $data += [
        'c_name'  =>  sanitize($_POST['c_name']),
        'c_desc'  =>  sanitize($_POST['c_desc']),
        'c_color'  =>  $c_color,
        'c_view'  => sanitize($_POST['c_view']),
        'c_status'  => 1
    ];

    $qry = Insert('tbl_cat', $data);

    $_SESSION['msg'] = "add_category";
    header("location:category.php");
    exit;
}

if (isset($_GET['c_id'])) {
    $user_qry = "SELECT * FROM tbl_cat where c_id='" . sanitize($_GET['c_id']) . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['c_id'])) {
    $data = [];

    if ($_FILES['c_image']['name'] !== "" && isValidImage($_FILES['c_image'])) {
        $img_res = mysqli_query($mysqli, 'SELECT * FROM tbl_cat WHERE c_id=' . sanitize($_GET['c_id']));
        $img_res_row = mysqli_fetch_assoc($img_res);
        $existing_image_path = $img_res_row['c_image'];

        if ($existing_image_path !== "") {
            unlink('../seller/images/' . $existing_image_path);
        }

        $new_image_filename = rand(0, 99999) . "_" . $_FILES['c_image']['name'];
        $target_path = '../seller/images/' . $new_image_filename;

        if (move_uploaded_file($_FILES['c_image']['tmp_name'], $target_path)) {
            createThumbnail($target_path, $target_path, 200);
            $data['c_image'] = $new_image_filename;
        }
    }

    $c_color = '000000';
    $data += [
        'c_name'  =>  sanitize($_POST['c_name']),
        'c_desc'  =>  sanitize($_POST['c_desc']),
        'c_color'  =>  $c_color,
        'c_view'  => sanitize($_POST['c_view']),
        'c_status'  => 1
    ];

    $user_edit = Update('tbl_cat', $data, "WHERE c_id = '" . sanitize($_POST['c_id']) . "'");

    if ($user_edit > 0) {
        $_SESSION['msg'] = "update_category";
        header("Location:add_category.php?c_id=" . sanitize($_POST['c_id']));
        exit;
    }
}
?>
<head>
<title><?php if(isset($_GET['c_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['category']; ?></title>
</head>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php if(isset($_GET['c_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['category']; ?></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row mrg-top">
                <div class="col-md-12">
                    <div class="col-md-12 col-sm-12">
                        <?php if(isset($_SESSION['msg'])){?> 
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <?php echo $client_lang[$_SESSION['msg']]; ?>
                        </div>
                        <?php unset($_SESSION['msg']); }?>
                    </div>
                </div>
            </div>
            <div class="card-body mrg_bottom"> 
                <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="c_id" value="<?php echo sanitize($_GET['c_id']);?>" />

                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['category_name']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['category_name_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="text" name="c_name" id="c_name" placeholder="eg. Featured" value="<?php if(isset($_GET['c_id'])){echo sanitize($user_row['c_name']);}?>" class="form-control" required>
                                </div>
                            </div>
                           <div class="form-group">
                              <label class="col-md-3 control-label"><?php echo $client_lang['category_type']; ?> :-</label>
                              <div class="col-md-6">
                                <select name="c_view" id="c_view" style="width:280px; height:25px;" class="select2" required>
                                  <option value="">-<?php echo $client_lang['category_type']; ?>-</option>
                                  <option value="1" <?php if($user_row['c_view']=='1'){?>selected<?php }?>><?php echo $client_lang['auction']; ?></option>
                                  <option value="2" <?php if($user_row['c_view']=='2'){?>selected<?php }?>><?php echo $client_lang['lottery']; ?></option>
                                  <option value="3" <?php if($user_row['c_view']=='3'){?>selected<?php }?>><?php echo $client_lang['shop']; ?></option>
                                </select>
                              </div>
                            </div>
                            
                           <!-- <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['category_color']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['category_color_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="color" name="c_color" id="c_color" value="<?php if(isset($_GET['c_id'])){echo '#' . $user_row['c_color'];}?>" class="form-control" style="height: 34px; padding: 6px;">
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['category_image']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['image_size']; ?></p></label>
                                <div class="col-md-6">
                                    <div class="fileupload_block">
                                        <input type="file" name="c_image" value="fileupload" id="fileupload">
                                        <div class="fileupload_img"><img src="assets/images/add-image.png" alt="category image" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">&nbsp; </label>
                                <div class="col-md-6">
                                    <?php if(isset($_GET['c_id']) and $user_row['c_image']!="") {?>
                                    <div class="block_wallpaper"><img src="../seller/images/<?php echo sanitize($user_row['c_image']);?>" alt="category image" /></div>
                                    <?php } ?>
                                </div>
                            </div><br>
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['c_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['category']; ?></button>
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
