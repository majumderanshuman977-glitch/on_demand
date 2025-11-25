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

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_POST['submit']) && isset($_GET['add']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    if ($_FILES['city_image']['name'] != "" && isValidImage($_FILES['city_image'])) {
        $category_image = rand(0, 99999) . "_" . $_FILES['city_image']['name'];
        $tpath1 = '../seller/images/' . $category_image;

        if (move_uploaded_file($_FILES["city_image"]["tmp_name"], $tpath1)) {
            createThumbnail($tpath1, $tpath1, 200);
            $data = [
                'city_name'  => sanitize($_POST['city_name']),
                'city_image' => $category_image,
                'city_bw_image' => $category_image
            ];

            $qry = Insert('tbl_city', $data);
            $_SESSION['msg'] = "add_city";
            header("location:city.php");
            exit;
        } else {
            echo "File upload failed.";
        }
    } else {
        echo $client_lang['invalid_image'];
    }
}

if (isset($_GET['city_id'])) {
    $stmt = $mysqli->prepare("SELECT * FROM tbl_city WHERE city_id = ?");
    $stmt->bind_param("i", $_GET['city_id']);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_row = $user_result->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['submit']) && isset($_POST['city_id']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $city_id = (int)$_POST['city_id'];
    
    if ($_FILES['city_image']['name'] != "" && isValidImage($_FILES['city_image'])) {
        $stmt = $mysqli->prepare("SELECT * FROM tbl_city WHERE city_id = ?");
        $stmt->bind_param("i", $city_id);
        $stmt->execute();
        $img_res = $stmt->get_result();
        $img_res_row = $img_res->fetch_assoc();
        $stmt->close();

        if ($img_res_row['city_image'] != "") {
            unlink('../seller/images/thumbs/' . $img_res_row['city_image']);
            unlink('../seller/images/' . $img_res_row['city_image']);
        }

        $category_image = rand(0, 99999) . "_" . $_FILES['city_image']['name'];
        $tpath1 = '../seller/images/' . $category_image;
        move_uploaded_file($_FILES["city_image"]["tmp_name"], $tpath1);
        createThumbnail($tpath1, $tpath1, 200);

        $stmt = $mysqli->prepare("UPDATE tbl_city SET city_name = ?, city_image = ?, city_bw_image = ? WHERE city_id = ?");
        $stmt->bind_param("sssi", sanitize($_POST['city_name']), $category_image, $category_image, $city_id);
        $user_edit = $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("UPDATE tbl_city SET city_name = ? WHERE city_id = ?");
        $stmt->bind_param("si", sanitize($_POST['city_name']), $city_id);
        $user_edit = $stmt->execute();
        $stmt->close();
    }

    if ($user_edit) {
        $_SESSION['msg'] = "update_city";
        header("Location:add_city.php?city_id=" . $city_id);
        exit;
    }
}
?>

<head>
<title><?php if(isset($_GET['city_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['city']; ?></title>
</head>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php if(isset($_GET['city_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['city']; ?></div>
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
                        <?php unset($_SESSION['msg']);}?>
                    </div>
                </div>
            </div>
            <div class="card-body mrg_bottom"> 
                <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="city_id" value="<?php echo isset($_GET['city_id']) ? sanitize($_GET['city_id']) : ''; ?>" />
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['city_name']; ?>:<br>
                                    <p class="control-label-help"><?php echo $client_lang['city_name_help']; ?></p>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" name="city_name" id="city_name" placeholder="eg. New York" value="<?php echo isset($user_row['city_name']) ? sanitize($user_row['city_name']) : ''; ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['city_image']; ?>:-
                                    <p class="control-label-help"><?php echo $client_lang['image_size']; ?></p>
                                </label>
                                <div class="col-md-6">
                                    <div class="fileupload_block">
                                        <input type="file" name="city_image" value="fileupload" id="fileupload">
                                        <div class="fileupload_img"><img src="assets/images/add-image.png" alt="city image" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">&nbsp; </label>
                                <div class="col-md-6">
                                    <?php if(isset($_GET['city_id']) && $user_row['city_image']!="") {?>
                                    <div class="block_wallpaper"><img src="../seller/images/<?php echo sanitize($user_row['city_image']);?>" alt="city image" /></div>
                                    <?php } ?>
                                </div>
                            </div><br>
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['city_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['city']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
