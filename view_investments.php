<?php 
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');  


$querytime = "SELECT timezone FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
date_default_timezone_set($rowtime['timezone']);
$datetime = date('Y-m-d H:i:s'); // Current datetime
$date = date('Y-m-d'); // Current date
$time = date('H:i:s'); // Current time

if(isset($_GET['plan_id'])) {
    $plan_id = sanitize($_GET['plan_id']);
	$user_qry = "SELECT * FROM tbl_hyip_order LEFT JOIN tbl_hyip ON tbl_hyip.plan_id = tbl_hyip_order.plan_id LEFT JOIN tbl_users ON tbl_users.id = tbl_hyip_order.user_id WHERE tbl_hyip_order.plan_id = $plan_id ORDER BY tbl_hyip_order.order_id DESC";  
	$users_result = mysqli_query($mysqli,$user_qry);
}

else if(isset($_POST['user_search'])) {
	$user_qry = "SELECT * FROM tbl_hyip_order LEFT JOIN tbl_hyip ON tbl_hyip.plan_id = tbl_hyip_order.plan_id LEFT JOIN tbl_users ON tbl_users.id = tbl_hyip_order.user_id WHERE tbl_hyip.plan_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_hyip_order.order_id DESC";  
	$users_result = mysqli_query($mysqli,$user_qry);
}
else {
	$tableName = "tbl_hyip_order";		
	$targetpage = "view_investments.php"; 	
	$limit = 15; 
	
	$query = "SELECT COUNT(*) as num FROM $tableName";
	$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
	$total_pages = $total_pages['num'];
	
	$stages = 3;
	$page = 0;
	if(isset($_GET['page'])) {
		$page = mysqli_real_escape_string($mysqli,$_GET['page']);
	}
	if($page) {
		$start = ($page - 1) * $limit; 
	} else {
		$start = 0;	
	}	
	
	$users_qry = "SELECT * FROM tbl_hyip_order
	LEFT JOIN tbl_hyip ON tbl_hyip.plan_id = tbl_hyip_order.plan_id
	LEFT JOIN tbl_users ON tbl_users.id = tbl_hyip_order.user_id
	ORDER BY tbl_hyip_order.order_id DESC LIMIT $start, $limit";  
	$users_result = mysqli_query($mysqli,$users_qry);
}

if(isset($_GET['order_id'])) {
    $order_id = sanitize($_GET['order_id']);
	Delete('tbl_hyip_order','order_id='.$order_id.'');
	$_SESSION['msg'] = "12";
	header("Location:view_investments.php");
	exit;
}

// Active and Deactive status
if(isset($_GET['status_deactive_id'])) {
    $order_id = sanitize($_GET['status_deactive_id']);
	$data = array('status'  =>  '0');
	$edit_status = Update('tbl_hyip_order', $data, "WHERE order_id = '".$order_id."'");
	$_SESSION['msg'] = "14";
	header("Location:view_investments.php");
	exit;
}
if(isset($_GET['status_active_id'])) {
    $order_id = sanitize($_GET['status_active_id']);
	$data = array('status'  =>  '1');
	$edit_status = Update('tbl_hyip_order', $data, "WHERE order_id = '".$order_id."'");
	$_SESSION['msg'] = "13";
	header("Location:view_investments.php");
	exit;
}

$querytime = "SELECT currency FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
$currency = $rowtime['currency'];

?>

<head>
<title><?php echo $client_lang['investment_plan_order']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
<style>
    .btn-download {
        background-color: #6f42c1; /* Purple background color */
        color: #fff; /* White text color */
    }
    
    .btn-download:hover {
        background-color: #5a32a3; /* Darker purple color on hover */
        color: #fff; /* White text color */
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card mrg_bottom">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php echo $client_lang['investment_plan_order']; ?></div>
                </div>
                <div class="col-md-7 col-xs-12">
                    <div class="search_list">
                        <div class="search_block">
                            <form method="post" action="">
                                <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_order']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                                <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row mrg-top">
                <div class="col-md-12">
                    <div class="col-md-12 col-sm-12">
                        <?php if(isset($_SESSION['msg'])){?> 
                        <div class="alert alert-success alert-dismissible" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <?php echo $client_lang[$_SESSION['msg']] ; ?> 
                        </div>
                        <?php unset($_SESSION['msg']);}?>    
                    </div>
                </div>
            </div>
            <div class="col-md-12 mrg-top">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Investment Details</th>  
                            <th>User</th>
                            <th>Value</th>
                            <th class="cat_action_list"><?php echo $client_lang['action']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=0;
                        while($users_row = mysqli_fetch_array($users_result)) {
                        ?>
                        <tr>
                            
                            <td><?php echo $users_row['plan_name'];?><br>
                                <?php echo $client_lang['last_interest_update']; ?>:&nbsp;<?php echo $users_row['last_interest_update'];?><br>
                                <?php echo $client_lang['next_interest_update']; ?>:&nbsp;<?php echo $users_row['next_interest_update'];?>
                            </td>
                            <td><?php echo $users_row['name'];?></td>
                            <td><?php echo $client_lang['current_value']; ?>:&nbsp;<?php echo $users_row['current_value'].' '.$client_lang['coin']; ?><br>
                                <?php echo $client_lang['investment_amount']; ?>:&nbsp;<?php echo $users_row['investment_amount'].' '.$client_lang['coin']; ?>
                            </td>
                            <td>
                                <div class="button-container">
                                    <a href="view_user.php?id=<?php echo $users_row['user_id'];?>" class="btn btn-view" title="View Details"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['view_user']; ?></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 col-xs-12">
                <div class="pagination_item_block">
                    <nav>
                        <?php if(!isset($_POST["search"])) { include("pagination.php"); } ?>                 
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>     

<?php include('includes/footer.php'); ?>
