<?php
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

if (isset($_POST['submit']) && isset($_GET['add'])) {
    $data = []; // Initialize data array

    // Process each image field
    foreach (['o_image', 'o_image1', 'o_image2', 'o_image3', 'o_image4'] as $image_field) {
        if ($_FILES[$image_field]['name'] != "" && isValidImage($_FILES[$image_field])) {
            // Image is uploaded and valid, process it
            $image_filename = rand(0, 99999) . "_" . $_FILES[$image_field]['name'];
            $target_path = '../seller/images/' . $image_filename;
            $thumbnail_path = '../seller/images/thumbs/' . $image_filename;

            if (move_uploaded_file($_FILES[$image_field]['tmp_name'], $target_path)) {
                createThumbnail($target_path, $thumbnail_path, 200); // Create thumbnail
                $data[$image_field] = $image_filename;
            } else {
                // Handle file upload error
                echo "File upload failed for $image_field";
            }
        } else {
            // Image is not uploaded or not valid, set it to NULL
            $data[$image_field] = null;
        }
    }

    // Remove '#' from color value if present
    $o_color = ltrim($_POST['o_color'], '#');
    
    // Other data fields
    $data += [
        'o_name'  =>  sanitize($_POST['o_name']),
        'o_desc'  =>  sanitize($_POST['o_desc']),
        'price' => sanitize($_POST['price']),
        'item_status' => 1
    ];

    $qry = Insert('tbl_items', $data);

    $_SESSION['msg'] = "10";
    header("location:items.php");
    exit;
}

if (isset($_GET['item_id'])) {
    $item_id = intval($_GET['item_id']);
    $user_qry = "SELECT * FROM tbl_items WHERE item_id = $item_id";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    // Initialize data array for database update
    $data = [];

    // Retrieve existing image paths if any
    $img_res = mysqli_query($mysqli, "SELECT * FROM tbl_items WHERE item_id = $item_id");
    $img_res_row = mysqli_fetch_assoc($img_res);

    // Process each image field
    foreach (['o_image', 'o_image1', 'o_image2', 'o_image3', 'o_image4'] as $image_field) {
        if ($_FILES[$image_field]['name'] !== "" && isValidImage($_FILES[$image_field])) {
            // Delete existing image if it exists
            $existing_image_path = $img_res_row[$image_field];
            if ($existing_image_path !== "") {
                unlink('../seller/images/thumbs/' . $existing_image_path);
                unlink('../seller/images/' . $existing_image_path);
            }

            // Generate a unique filename for the new image
            $new_image_filename = rand(0, 99999) . "_" . $_FILES[$image_field]['name'];
            $target_path = '../seller/images/' . $new_image_filename;
            $thumbnail_path = '../seller/images/thumbs/' . $new_image_filename;

            // Move the uploaded image to the target path
            if (move_uploaded_file($_FILES[$image_field]['tmp_name'], $target_path)) {
                createThumbnail($target_path, $thumbnail_path, 200); // Create thumbnail
                $data[$image_field] = $new_image_filename;
            }
        } else {
            // Image is not uploaded, retain existing value
            $data[$image_field] = $img_res_row[$image_field];
        }
    }

    // Remove '#' from color value if present
    $o_color = ltrim($_POST['o_color'], '#');

    // Add other data fields to the array
    $data += [
        'o_name'  =>  sanitize($_POST['o_name']),
        'o_desc'  =>  sanitize($_POST['o_desc']),
        'price' => sanitize($_POST['price']),
    ];

    // Update the database with the collected data
    $user_edit = Update('tbl_items', $data, "WHERE item_id = $item_id");

    if ($user_edit > 0) {
        $_SESSION['msg'] = "11";
        header("Location:add_item.php?item_id=" . $item_id);
        exit;
    }
}
?>

<head>
<title><?php if(isset($_GET['item_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['item']; ?></title>
</head> 	
<style>
    .block_wallpaper img {
        width: 200px;
        height: 200px;
    }

    .fileupload_img.replaced .database-image {
        display: none;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php if(isset($_GET['item_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['item']; ?></div>
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
                    <input type="hidden" name="item_id" value="<?php echo $_GET['item_id'];?>" />

                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['item_name']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['item_name_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="text" name="o_name" id="o_name" placeholder="<?php echo $client_lang['item_name_placeholder']; ?>" value="<?php if(isset($_GET['item_id'])){echo $user_row['o_name'];}?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['item_description']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['item_description_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="text" name="o_desc" id="o_desc" placeholder="<?php echo $client_lang['item_description_placeholder']; ?>" value="<?php if(isset($_GET['item_id'])){echo $user_row['o_desc'];}?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['item_price']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['item_price_help']; ?></p></label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <?php
                                            $currency_qry = "SELECT currency FROM tbl_settings";
                                            $currency_result = mysqli_query($mysqli, $currency_qry);
                                            $currency_row = mysqli_fetch_assoc($currency_result);
                                            echo $currency_row['currency'];
                                            ?>
                                        </span>
                                        <input type="text" name="price" id="price" placeholder="eg. 99"  value="<?php if(isset($_GET['item_id'])){echo $user_row['price'];}?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Main Image -->
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['main_item_image']; ?>:-
                                    <p class="control-label-help"><?php echo $client_lang['image_size']; ?></p>
                                </label>
                                <div class="col-md-6">
                                    <div class="fileupload_block">
                                        <input type="file" name="o_image" value="fileupload" id="fileupload_main" onchange="previewImage(this)">
                                        <div class="fileupload_img">
                                            <?php if(isset($_GET['item_id']) and $user_row['o_image']!="") {?>
                                                <img src="../seller/images/<?php echo $user_row['o_image'];?>" alt="category image" class="database-image">
                                            <?php } ?>
                                            <img id="preview_img_main" src="#" alt="Preview image" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Images -->
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['additional_image']; ?>: </label>
                                <div class="col-md-6">
                                    <?php for ($i = 1; $i <= 4; $i++) { ?>
                                        <div class="fileupload_block">
                                            <input type="file" name="o_image<?php echo $i; ?>" value="fileupload" id="fileupload_<?php echo $i; ?>" onchange="previewImage(this)">
                                            <div class="fileupload_img">
                                                <?php if (isset($_GET['item_id']) && $user_row['o_image' . $i] != "") { ?>
                                                    <img src="../seller/images/<?php echo $user_row['o_image' . $i]; ?>" alt="category image" class="database-image">
                                                <?php } ?>
                                                <img id="preview_img_<?php echo $i; ?>" src="#" alt="Preview image" style="display: none;">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            
                            <script>
                            function previewImage(input) {
                                var inputId = input.id;
                                var previewId = inputId === 'fileupload_main' ? 'preview_img_main' : 'preview_img_' + inputId.split('_').pop();
                                var preview = document.getElementById(previewId);
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
                            </script>
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['item_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['item']; ?></button>
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
