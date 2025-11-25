<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 
require_once("thumbnail_images.class.php");

$settings_query = "SELECT timezone FROM tbl_settings";
$settings_result = mysqli_query($mysqli, $settings_query);
$settings_row = mysqli_fetch_assoc($settings_result);

date_default_timezone_set($settings_row['timezone']);
$date = date('Y-m-d H:i:s');

// HTTPS redirection
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if (isset($_POST['submit']) && isset($_GET['add'])) {
    $data = [];
    
    $normal_ball_limit = (int)sanitize($_POST['normal_ball_limit']);
    $premium_ball_limit = (int)sanitize($_POST['premium_ball_limit']);
    
    if (($normal_ball_limit + $premium_ball_limit) > 8) {
        $_SESSION['msg'] = "error_limit_ball";
        header("Location:manage_lotterydesign.php?design_id=" . sanitize($_POST['design_id']));
        exit;
    }

    $data += [
        'lottery_balls_name'  =>  sanitize($_POST['lottery_balls_name']),
        'normal_ball_start'  =>  sanitize($_POST['normal_ball_start']),
        'normal_ball_end'  =>  sanitize($_POST['normal_ball_end']),
        'normal_ball_limit'  =>  sanitize($_POST['normal_ball_limit']),
        'premium_ball_start'  =>  sanitize($_POST['premium_ball_start']),
        'premium_ball_end'  =>  sanitize($_POST['premium_ball_end']),
        'premium_ball_limit'  =>  sanitize($_POST['premium_ball_limit']),
        'created_on'  =>  $date
    ];

    $qry = Insert('tbl_lottery_balls', $data);

    $_SESSION['msg'] = "add_lotterydesign";
    header("location:lottery_design.php");
    exit;
}

if (isset($_GET['design_id'])) {
    $user_qry = "SELECT * FROM tbl_lottery_balls where lottery_balls_id='" . sanitize($_GET['design_id']) . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['design_id'])) {
    $data = [];
    
    $normal_ball_limit = (int)sanitize($_POST['normal_ball_limit']);
    $premium_ball_limit = (int)sanitize($_POST['premium_ball_limit']);
    
    if (($normal_ball_limit + $premium_ball_limit) > 8) {
        $_SESSION['msg'] = "error_limit_ball";
        header("Location:manage_lotterydesign.php?design_id=" . sanitize($_POST['design_id']));
        exit;
    }

    $data += [
        'lottery_balls_name'  =>  sanitize($_POST['lottery_balls_name']),
        'normal_ball_start'  =>  sanitize($_POST['normal_ball_start']),
        'normal_ball_end'  =>  sanitize($_POST['normal_ball_end']),
        'normal_ball_limit'  =>  sanitize($_POST['normal_ball_limit']),
        'premium_ball_start'  =>  sanitize($_POST['premium_ball_start']),
        'premium_ball_end'  =>  sanitize($_POST['premium_ball_end']),
        'premium_ball_limit'  =>  sanitize($_POST['premium_ball_limit']),
    ];

    $user_edit = Update('tbl_lottery_balls', $data, "WHERE lottery_balls_id = '" . sanitize($_POST['design_id']) . "'");

    if ($user_edit > 0) {
        $_SESSION['msg'] = "update_lotterydesign";
        header("Location:manage_lotterydesign.php?design_id=" . sanitize($_POST['design_id']));
        exit;
    }
}
?>
<head>
<title><?php if(isset($_GET['design_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['ticket_design']; ?></title>
</head>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php if(isset($_GET['design_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['ticket_design']; ?></div>
                    <p><?php echo $client_lang['help_lottery_ball']; ?></p>
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
                    <input type="hidden" name="design_id" value="<?php echo sanitize($_GET['design_id']);?>" />

                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['lottery_balls_name']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['lottery_balls_name_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="text" name="lottery_balls_name" id="lottery_balls_name" placeholder="e.g. Featured" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['lottery_balls_name']);}?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['premium_ball_start']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['premium_ball_start_help']; ?></p></label>
                                <div class="col-md-3">
                                    <input type="number" name="premium_ball_start" id="premium_ball_start" placeholder="e.g. 51" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['premium_ball_start']);}?>" class="form-control" required>
                                </div>
                                <label class="col-md-1 control-label text-center"><?php echo $client_lang['to']; ?></label>
                                <div class="col-md-3">
                                    <input type="number" name="premium_ball_end" id="premium_ball_end" placeholder="e.g. 100" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['premium_ball_end']);}?>" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['normal_ball_start']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['normal_ball_start_help']; ?></p></label>
                                <div class="col-md-3">
                                    <input type="number" name="normal_ball_start" id="normal_ball_start" placeholder="e.g. 1" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['normal_ball_start']);}?>" class="form-control" required>
                                </div>
                                <label class="col-md-1 control-label text-center"><?php echo $client_lang['to']; ?></label>
                                <div class="col-md-3">
                                    <input type="number" name="normal_ball_end" id="normal_ball_end" placeholder="e.g. 50" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['normal_ball_end']);}?>" class="form-control" required>
                                </div>
                            </div>

                    
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['normal_ball_limit']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['normal_ball_limit_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="number" name="normal_ball_limit" id="normal_ball_limit" placeholder="e.g. 4" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['normal_ball_limit']);}?>" class="form-control" min="0" max="8" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['premium_ball_limit']; ?>:-<br>
                                <p class="control-label-help"><?php echo $client_lang['premium_ball_limit_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="number" name="premium_ball_limit" id="premium_ball_limit" placeholder="e.g. 4" value="<?php if(isset($_GET['design_id'])){echo sanitize($user_row['premium_ball_limit']);}?>" class="form-control" min="0" max="8" required>
                                </div>
                            </div>
                    
                    
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['design_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['ticket_design']; ?></button>
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
