<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php');
require_once("thumbnail_images.class.php");

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $s_name = sanitize($_POST['s_name']);
    $s_desc = sanitize($_POST['s_desc']);
    $s_colour = sanitize($_POST['s_colour']);
    $u_id = intval($_POST['u_id']); // As u_id is an integer
    $s_code = sanitize($_POST['s_code']);
    $s_link = sanitize($_POST['s_link']);
    $s_sdate = sanitize($_POST['s_sdate']);
    $s_edate = sanitize($_POST['s_edate']);
    $s_stime = sanitize($_POST['s_stime']) . ":00"; // Add seconds set to 00
    $s_etime = sanitize($_POST['s_etime']) . ":00"; // Add seconds set to 00

    if (isset($_GET['add'])) {
        // Prepare data for insertion
        $data = array(
            's_name'  =>  $s_name,
            's_desc'  =>  $s_desc,
            's_colour'  =>  $s_colour,
            's_type'  =>  2,
            'u_id'  =>  $u_id,
            's_code'  =>  $s_code,
            's_link'  =>  $s_link,
            's_sdate'  =>  $s_sdate,
            's_edate'  =>  $s_edate,
            's_stime'  =>  $s_stime,
            's_etime'  =>  $s_etime,
            's_status'  =>  1,
        );

        // Insert data
        Insert('tbl_scratch', $data);

        $_SESSION['msg'] = "add_scratchcard";
        header("Location: scratchcard.php");
        exit;
    }

    if (isset($_POST['s_id'])) {
        // Prepare data for update
        $data = array(
            's_name'  =>  $s_name,
            's_desc'  =>  $s_desc,
            's_colour'  =>  $s_colour,
            's_type'  =>  2,
            'u_id'  =>  $u_id,
            's_code'  =>  $s_code,
            's_link'  =>  $s_link,
            's_sdate'  =>  $s_sdate,
            's_edate'  =>  $s_edate,
            's_stime'  =>  $s_stime,
            's_etime'  =>  $s_etime,
        );

        // Update data
        $user_edit = Update('tbl_scratch', $data, "WHERE s_id = '" . intval($_POST['s_id']) . "'");

        if ($user_edit > 0) {
            $_SESSION['msg'] = "update_scratchcard";
            header("Location: add_couponcard.php?s_id=" . intval($_POST['s_id']));
            exit;
        }
    }
}
?>

   <style>
        .progress {
            height: 30px;
            background-color: #f5f5f5;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
        }

        .progress-bar {
            background-color: #4caf50;
            text-align: center;
            color: white;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>

<head>
<title><?php if(isset($_GET['s_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['scratch_card']; ?></title>
</head>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title">
                        <?php if(isset($_GET['s_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['scratch_card']; ?></div>
                    <p class="control-label-help"><?php echo $client_lang['add_item_card']; ?></p>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row mrg-top">
                <div class="col-md-12">
                    <div class="col-md-12 col-sm-12">
                        <?php if (isset($_SESSION['msg'])) { ?> 
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <?php echo $client_lang[$_SESSION['msg']] ; ?>
                            </div>
                        <?php unset($_SESSION['msg']); } ?>	
                    </div>
                </div>
            </div>
            <div class="card-body mrg_bottom">
                <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="s_id" value="<?php echo $_GET['s_id']; ?>" />
                    
                    <!-- Progress Bar -->
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            0% <!-- Initial text inside progress bar -->
                        </div>
                    </div>
                    
                    <!-- Section 1: Scratch Card Details -->
                    <div class="section" id="section1">
                        <div class="section-body">
                            <strong><center><?php echo $client_lang['scratch_card_details']; ?></center></strong>
                            <hr>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_name']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_name_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="text" name="s_name" id="s_name" placeholder="eg. 50% Off" title="enter scratch card name" value="<?php if (isset($_GET['s_id'])) { echo $user_row['s_name']; } ?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_description']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_description_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="text" name="s_desc" id="s_desc" placeholder="<?php echo $client_lang['scratch_card_description_placeholder']; ?>" title="enter scratch card description" value="<?php if (isset($_GET['s_id'])) { echo $user_row['s_desc']; } ?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_design']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_design_help']; ?></p></label>
                                <div class="col-md-6">
                                    <select name="s_colour" id="s_colour" class="form-control select2" required>
                                        <option value="">- <?php echo $client_lang['scratch_card_design_select']; ?> -</option>
                                        <option value="1" <?php if ($user_row['s_colour'] == '1') { ?>selected<?php } ?>>Blue</option>
                                        <option value="2" <?php if ($user_row['s_colour'] == '2') { ?>selected<?php } ?>>Dark Blue</option>
                                        <option value="3" <?php if ($user_row['s_colour'] == '3') { ?>selected<?php } ?>>Dark Pink</option>
                                        <option value="4" <?php if ($user_row['s_colour'] == '4') { ?>selected<?php } ?>>Green</option>
                                        <option value="5" <?php if ($user_row['s_colour'] == '5') { ?>selected<?php } ?>>Orange</option>
                                        <option value="6" <?php if ($user_row['s_colour'] == '6') { ?>selected<?php } ?>>Pink</option>
                                        <option value="7" <?php if ($user_row['s_colour'] == '7') { ?>selected<?php } ?>>Red</option>
                                        <option value="8" <?php if ($user_row['s_colour'] == '8') { ?>selected<?php } ?>>Teal Green</option>
                                        <option value="9" <?php if ($user_row['s_colour'] == '9') { ?>selected<?php } ?>>Violet</option>
                                        <option value="10" <?php if ($user_row['s_colour'] == '10') { ?>selected<?php } ?>>Yellow</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-success" onclick="showNextSection(2)"><?php echo $client_lang['next']; ?></button>
                        </div>
                    </div>
                    
                    <!-- Section 2: Item -->
                    <div class="section" id="section2" style="display:none;">
                        <div class="section-body">
                            <strong><center><?php echo $client_lang['scratch_card_prize']; ?></center></strong>
                            <hr>
                            
                            <div class="form-group">
                              <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_prize']; ?>:-</label>
                              <div class="col-md-6">
                                  <select name="s_code" id="s_code" style="width:280px; height:25px;" class="select2" required>
                                    <option value=""><?php echo $client_lang['scratch_card_select_item']; ?>:</option>
                                     <?php
                                         $category_qry1 = "SELECT o_id, o_name FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE o_type=3";
                                         $category_result1 = mysqli_query($mysqli, $category_qry1);
                                         while($category_row1 = mysqli_fetch_assoc($category_result1)) {
                                         $selected = ($category_row1['o_id'] == $user_row['s_code']) ? 'selected' : '';
                                         echo '<option value="'.$category_row1['o_id'].'" '.$selected.'>'.$category_row1['o_name'].'</option>';
                                      }
                                      ?>
                                  </select>
                              </div>
                            </div>
                            
                            <br>
                            <button type="button" class="btn btn-danger" onclick="showPreviousSection(1)"><?php echo $client_lang['previous']; ?></button>
                            <button type="button" class="btn btn-success" onclick="showNextSection(3)"><?php echo $client_lang['next']; ?></button>
                        </div>
                    </div>
                    
                    <!-- Section 3: User Selection -->
                    <div class="section" id="section3" style="display:none;">
                        <div class="section-body">
                            <strong><center><?php echo $client_lang['scratch_card_user']; ?></center></strong>
                            <hr>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['user']; ?> :-<br><p class="control-label-help"><?php echo $client_lang['scratch_user_help']; ?></p></label>
                                <div class="col-md-6">
                                    <select name="u_id" id="u_id" class="form-control select2" required>
                                        <option value=""><?php echo $client_lang['select_user']; ?></option>
                                        <?php
                                        $category_qry = "SELECT id, name, email FROM tbl_users";
                                        $category_result = mysqli_query($mysqli, $category_qry);
                                        while ($category_row = mysqli_fetch_assoc($category_result)) {
                                            $selected = ($category_row['id'] == $user_row['u_id']) ? 'selected' : '';
                                            echo '<option value="' . $category_row['id'] . '" ' . $selected . '>' . $category_row['name'] . ' (' . $category_row['email'] . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-danger" onclick="showPreviousSection(2)"><?php echo $client_lang['previous']; ?></button>
                            <button type="button" class="btn btn-success" onclick="showNextSection(4)"><?php echo $client_lang['next']; ?></button>
                        </div>
                    </div>
                    
                    <!-- Section 4: Validity -->
                    <div class="section" id="section4" style="display:none;">
                        <div class="section-body">
                            <strong><center><?php echo $client_lang['scratch_card_validity']; ?></center></strong>
                            <hr>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_valid_from_date']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_valid_from_date_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="date" name="s_sdate" id="s_sdate" value="<?php if (isset($_GET['s_id'])) { echo $user_row['s_sdate']; } ?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_valid_from_time']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_valid_from_time_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="time" name="s_stime" id="s_stime" step="60" value="<?php if (isset($_GET['s_id'])) { echo $user_row['s_stime']; } ?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_valid_to_date']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_valid_to_date_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="date" name="s_edate" id="s_edate" value="<?php if (isset($_GET['s_id'])) { echo $user_row['s_edate']; } ?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['scratch_card_valid_to_time']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['scratch_card_valid_to_time_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="time" name="s_etime" id="s_etime" step="60" value="<?php if (isset($_GET['s_id'])) { echo $user_row['s_etime']; } ?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-danger" onclick="showPreviousSection(3)"><?php echo $client_lang['previous']; ?></button>
                            <button type="submit" name="submit" class="btn btn-success"><?php if(isset($_GET['s_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['scratch_card']; ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Calculate total number of input/select fields
    const totalInputs = $("input, select").length - 2; // excluding the hidden input

    // Function to update progress bar
    const updateProgressBar = () => {
        let completedInputs = 0;
        $("input, select").each(function() {
            // Check if the input/select has a value
            if ($(this).val()) {
                completedInputs++;
            }
        });

        // Calculate percentage and ensure it does not exceed 100
        let progress = (completedInputs / totalInputs) * 100;
        progress = Math.min(progress, 100); // Cap at 100%

        // Update the progress bar width and text
        $(".progress-bar").css("width", progress + "%").attr("aria-valuenow", progress);
        $(".progress-bar").text(progress.toFixed(0) + "% Complete"); // Update text inside progress bar
    };

    // Bind change event to all input/select fields
    $("input, select").on("change", updateProgressBar);

    // Call the function initially to set the progress bar
    updateProgressBar();
});

// Function to show next section
function showNextSection(section) {
    $(".section").hide();
    $("#section" + section).show();
}

// Function to show previous section
function showPreviousSection(section) {
    $(".section").hide();
    $("#section" + section).show();
}
</script>


   

<?php include('includes/footer.php');?>                  