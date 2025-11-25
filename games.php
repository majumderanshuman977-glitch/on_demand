<?php 
include("includes/header.php");
require("includes/function.php");
require("language/language.php");

$qry = "SELECT * FROM tbl_games where id='1'";
$result = mysqli_query($mysqli, $qry);
$settings_row = mysqli_fetch_assoc($result);


if(isset($_POST['rps_submit']))
  {

        $data = array(
            'rps_min' => $_POST['rps_min'],
            'rps_max' => $_POST['rps_max'],
            'rps_chance' => $_POST['rps_chance'],
            'rps_win' => $_POST['rps_win'],
            'rps_status' => $_POST['rps_status'],
        );

    
    $settings_edit = Update('tbl_games', $data, "WHERE id = '1'");
    $_SESSION['msg'] = "39";
    header("Location: games.php");
    exit;
  
  }
  
if(isset($_POST['gn_submit']))
  {

        $data = array(
            'gn_min' => $_POST['gn_min'],
            'gn_max' => $_POST['gn_max'],
            'gn_chance' => $_POST['gn_chance'],
            'gn_win' => $_POST['gn_win'],
            'gn_status' => $_POST['gn_status'],
        );

    
    $settings_edit = Update('tbl_games', $data, "WHERE id = '1'");
    $_SESSION['msg'] = "39";
    header("Location: games.php");
    exit;
  
  }
  
if(isset($_POST['spin_submit']))
  {

        $data = array(
        'spin_min' => $_POST['spin_min'],
        'spin_max' => $_POST['spin_max'],
        'spin_win_min' => $_POST['spin_win_min'],
        'spin_win_max' => $_POST['spin_win_max'],
        'spin_status' => 1,
        );

    
    $settings_edit = Update('tbl_games', $data, "WHERE id = '1'");
    $_SESSION['msg'] = "39";
    header("Location: games.php");
    exit;
  
  }
  
if(isset($_POST['ct_submit']))
  {

        $data = array(
        'ct_min' => $_POST['ct_min'],
        'ct_max' => $_POST['ct_max'],
        'ct_chance' => $_POST['ct_chance'],
        'ct_win' => $_POST['ct_win'],
        'ct_status' => $_POST['ct_status'],
        );

    
    $settings_edit = Update('tbl_games', $data, "WHERE id = '1'");
    $_SESSION['msg'] = "39";
    header("Location: games.php");
    exit;
  
  }  
  
if(isset($_POST['cric_submit']))
  {

        $data = array(
        'cric_min' => $_POST['cric_min'],
        'cric_max' => $_POST['cric_max'],
        'cric_chance' => $_POST['cric_chance'],
        'cric_win' => $_POST['cric_win'],
        'cric_status' => $_POST['cric_status'],
        );

    
    $settings_edit = Update('tbl_games', $data, "WHERE id = '1'");
    $_SESSION['msg'] = "39";
    header("Location: games.php");
    exit;
  
  } 
  
if(isset($_POST['ouc_submit']))
  {

        $data = array(
        'ouc_min' => $_POST['ouc_min'],
        'ouc_max' => $_POST['ouc_max'],
        'ouc_amount' => $_POST['ouc_amount'],
        'ouc_bonus1' => $_POST['ouc_bonus1'],
        'ouc_bonus2' => $_POST['ouc_bonus2'],
        'ouc_bonus3' => $_POST['ouc_bonus3'],
        'ouc_win_min' => $_POST['ouc_win_min'],
        'ouc_win_max' => $_POST['ouc_win_max'],
        'ouc_status' => $_POST['ouc_status'],
        );

    
    $settings_edit = Update('tbl_games', $data, "WHERE id = '1'");
    $_SESSION['msg'] = "39";
    header("Location: games.php");
    exit;
  
  } 

?>
<head>
<title><?php echo $client_lang['game_settings']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
<style>
    .nav-tabs > li > a > i {
        font-size: 24px;
        vertical-align: middle;
    }
    .nav-tabs > li > a {
        display: flex;
        align-items: center;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php echo $client_lang['game_settings']; ?></div>
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
                            <?php echo $client_lang[$_SESSION['msg']] ; ?> 
                        </div>
                        <?php unset($_SESSION['msg']);}?> 
                    </div>
                </div>
            </div>
            <div class="card-body mrg_bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#spin" aria-controls="spin" role="tab" data-toggle="tab">
                            <?php echo $client_lang['spin']; ?>&nbsp;<i class="fi fi-rr-dharmachakra"></i>
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="spin">
                        <!-- Spin & Win form -->
                        <form action="" method="post" class="form form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['minimum_bet']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['minimum_bet_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="spin_min" id="spin_min" placeholder="eg. 1" value="<?php echo $settings_row['spin_min'];?>" class="form-control" required>
                      <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                 </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['maximum_bet']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['maximum_bet_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="spin_max" id="spin_max" placeholder="eg. 100" value="<?php echo $settings_row['spin_max'];?>" class="form-control" required>
                      <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                 </div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['minimum_win']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['minimum_win_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="spin_win_min" id="spin_win_min" placeholder="eg. 10" value="<?php echo $settings_row['spin_win_min'];?>" class="form-control" required>
                      <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                 </div>
                </div>
                
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['maximum_win']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['maximum_win_help']; ?></p></label>
                    <div class="col-md-6">
                        <div class="input-group">
                      <input type="text" name="spin_win_max" id="spin_win_max" placeholder="eg. 50" value="<?php echo $settings_row['spin_win_max'];?>" class="form-control" required>
                      <span class="input-group-addon"><?php echo $client_lang['coin']; ?></span>
                    </div>
                 </div>
                </div>
                            <div class="form-group">
                              <div class="col-md-9 col-md-offset-3">
                                <button type="submit" name="spin_submit" class="btn btn-primary"><?php echo $client_lang['save_game']; ?></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>                        
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
