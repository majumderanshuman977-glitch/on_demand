<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 

if (isset($_POST['submit']) && isset($_GET['add'])) {
    $rank_type = sanitize($_POST['rank_type']);
    $item_id = sanitize($_POST['item_id']);
    $o_id = sanitize($_GET['o_id']);
    
    if ($rank_type == 'single') {
        $rank_single = sanitize($_POST['rank_single']);
        $data = array(
            'rank_start' => $rank_single,
            'rank_end'   => $rank_single,
            'item_id'    => $item_id,
            'o_id'       => $o_id,
        );
    } else {
        $rank_start = sanitize($_POST['rank_start']);
        $rank_end = sanitize($_POST['rank_end']);
        $data = array(
            'rank_start' => $rank_start,
            'rank_end'   => $rank_end,
            'item_id'    => $item_id,
            'o_id'       => $o_id,
        );
    }

    $qry = Insert('tbl_prizes', $data);

    $_SESSION['msg'] = "add_prize";
    header("location:prizes.php?o_id={$o_id}");
    exit;
}

if (isset($_GET['prize_id'])) {
    $prize_id = sanitize($_GET['prize_id']);
    $user_qry = "SELECT * FROM tbl_prizes WHERE prize_id='" . $prize_id . "'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['prize_id'])) {
    $prize_id = sanitize($_POST['prize_id']);
    $rank_type = sanitize($_POST['rank_type']);
    $item_id = sanitize($_POST['item_id']);
    $rank_start = sanitize($_POST['rank_start']);
    $rank_single = sanitize($_POST['rank_single']);
    
    if ($rank_type == 'single') {
        $rank_single = sanitize($_POST['rank_single']);
        $data = array(
            'rank_start' => $rank_single,
            'rank_end'   => $rank_single,
            'item_id'    => $item_id,
        );
    } else {
        $rank_start = sanitize($_POST['rank_start']);
        $rank_end = sanitize($_POST['rank_end']);
        $data = array(
            'rank_start' => $rank_start,
            'rank_end'   => $rank_end,
            'item_id'    => $item_id,
        );
    }

    $user_edit = Update('tbl_prizes', $data, "WHERE prize_id = '" . $prize_id . "'");
    
    // Update tbl_offers if rank_start and rank_end are both 1
    if ($rank_start == 1 || $rank_single == 1) {
        $update_offer_data = array(
            'item_id' => $item_id,
        );
        // Example Update function for tbl_offers
        $update_offer = Update('tbl_offers', $update_offer_data, "WHERE o_id = '{$user_row['o_id']}'");
    }

    if ($user_edit > 0) {
        $_SESSION['msg'] = "update_prize";
        header("location:prizes.php?o_id={$user_row['o_id']}");
        exit;
    }
}

$settings_qry = "SELECT * FROM tbl_settings";
$settings_result = mysqli_query($mysqli, $settings_qry);      
$settings_row = mysqli_fetch_assoc($settings_result);

$currency = $settings_row['currency'];

?>
<head>
    <title><?php if(isset($_GET['prize_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['prizes']; ?></title>
    <script>
        function toggleRankFields() {
            const rankType = document.querySelector('input[name="rank_type"]:checked').value;
            const singleRankField = document.getElementById('single-rank');
            const multipleRankFields = document.getElementById('multiple-rank');
            
            if (rankType === 'single') {
                singleRankField.style.display = 'block';
                multipleRankFields.style.display = 'none';
            } else {
                singleRankField.style.display = 'none';
                multipleRankFields.style.display = 'block';
            }
        }
        
        window.onload = function() {
            <?php if(isset($_GET['prize_id'])): ?>
                const rankStart = <?php echo $user_row['rank_start']; ?>;
                const rankEnd = <?php echo $user_row['rank_end']; ?>;
                
                if (rankStart === rankEnd) {
                    document.getElementById('rank_single').value = rankStart;
                    document.querySelector('input[name="rank_type"][value="single"]').checked = true;
                } else {
                    document.getElementById('rank_start').value = rankStart;
                    document.getElementById('rank_end').value = rankEnd;
                    document.querySelector('input[name="rank_type"][value="multiple"]').checked = true;
                }
                toggleRankFields();
            <?php endif; ?>
        };
    </script>
</head>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php if(isset($_GET['prize_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['prizes']; ?></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row mrg-top">
                <div class="col-md-12">
                    <div class="col-md-12 col-sm-12">
                        <?php if(isset($_SESSION['msg'])){?> 
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <?php echo $client_lang[$_SESSION['msg']]; ?>
                        </div>
                        <?php unset($_SESSION['msg']);}?>	
                    </div>
                </div>
            </div>
            <div class="card-body mrg_bottom"> 
                <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="prize_id" value="<?php echo $_GET['prize_id']; ?>" />
                    <div class="section">
                        <div class="section-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['prize_rank_type']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['prize_rank_type_help']; ?></p></label>
                                <div class="col-md-6">
                                    <label><input type="radio" name="rank_type" value="single" onclick="toggleRankFields()" checked> <?php echo $client_lang['single_rank']; ?></label>
                                    <label><input type="radio" name="rank_type" value="multiple" onclick="toggleRankFields()"> <?php echo $client_lang['multiple_ranks']; ?></label>
                                </div>
                            </div>
                            <br>
                            <div class="form-group" id="single-rank" style="display: block;">
                                <label class="col-md-3 control-label"><?php echo $client_lang['single_rank']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['single_rank_help']; ?></p></label>
                                <div class="col-md-6">
                                    <input type="number" name="rank_single" id="rank_single" placeholder="Enter single rank" value="<?php if(isset($_GET['prize_id']) && $user_row['rank_start'] == $user_row['rank_end']){echo $user_row['rank_start'];}?>" class="form-control">
                                </div>
                            </div>
                            <div id="multiple-rank" style="display: none;">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $client_lang['rank_start']; ?>:<br><p class="control-label-help"><?php echo $client_lang['rank_start_help']; ?></p></label>
                                    <div class="col-md-6">
                                        <input type="number" name="rank_start" id="rank_start" placeholder="Enter start rank" value="<?php if(isset($_GET['prize_id']) && $user_row['rank_start'] != $user_row['rank_end']){echo $user_row['rank_start'];}?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $client_lang['rank_end']; ?>:<br><p class="control-label-help"><?php echo $client_lang['rank_end_help']; ?></p></label>
                                    <div class="col-md-6">
                                        <input type="number" name="rank_end" id="rank_end" placeholder="Enter end rank" value="<?php if(isset($_GET['prize_id']) && $user_row['rank_start'] != $user_row['rank_end']){echo $user_row['rank_end'];}?>" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo $client_lang['prize']; ?>:<br><p class="control-label-help"><?php echo $client_lang['prize_help']; ?></p></label>
                                <div class="col-md-6">
                                    <select name="item_id" id="item_id" style="width:280px; height:25px;" class="select2" required>
                                        <option value=""><?php echo $client_lang['select_prize']; ?></option>
                                        <?php
                                        $items_qry = "SELECT * FROM tbl_items WHERE item_status = 1 ORDER BY item_id";
                                        $items_result = mysqli_query($mysqli, $items_qry);
                                        while($items_row = mysqli_fetch_assoc($items_result)) {
                                            $selected = ($items_row['item_id'] == $user_row['item_id']) ? 'selected' : '';
                                            echo '<option value="'.$items_row['item_id'].'" '.$selected.'>'.$items_row['o_name'].' ('.$client_lang['worth'].' '.$currency.$items_row['price'].')'.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['prize_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['prizes']; ?></button>
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
