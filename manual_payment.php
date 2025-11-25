<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_manual_payments WHERE tbl_manual_payments.transaction_id like '%".addslashes($_POST['search_value'])."%'";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_manual_payments";		
							$targetpage = "manual_payment.php"; 	
							$limit = 15; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName";
							$total_pages = mysqli_fetch_array(mysqli_query($mysqli,$query));
							$total_pages = $total_pages['num'];
							
							$stages = 3;
							$page=0;
							if(isset($_GET['page'])){
							$page = mysqli_real_escape_string($mysqli,$_GET['page']);
							}
							if($page){
								$start = ($page - 1) * $limit; 
							}else{
								$start = 0;	
								}	
							

						 $users_qry = "SELECT * FROM tbl_manual_payments
                                       LEFT JOIN tbl_users ON tbl_users.id = tbl_manual_payments.user_id
                                       LEFT JOIN tbl_payment_gateway ON tbl_payment_gateway.pg_id = tbl_manual_payments.pg_id
                                       ORDER BY FIELD(tbl_manual_payments.transaction_status, 1) DESC, tbl_manual_payments.manual_payment_id DESC
                                       LIMIT $start, $limit";
							 
						 $users_result=mysqli_query($mysqli,$users_qry);

							
	 }
	 
	 if(isset($_GET['status_deactive_id']))
	{
	    //Decline Payment
		$data = array('transaction_status'  =>  '0');
		
		$edit_status=Update('tbl_manual_payments', $data, "WHERE manual_payment_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="manual_transaction_declined";
		 header( "Location:manual_payment.php");
		 exit;
	}
	
	if(isset($_GET['status_active_id']))
	{
	    $transaction_id = $_GET['status_active_id'];
	    //Approve Payment
		$data = array('transaction_status'  =>  '2');
		
		$edit_status=Update('tbl_manual_payments', $data, "WHERE manual_payment_id = '".$_GET['status_active_id']."'");
		
		$manualPaymentQuery = "SELECT * FROM tbl_manual_payments WHERE manual_payment_id = '".$_GET['status_active_id']."'";
        $manualPaymentResult = mysqli_query($mysqli, $manualPaymentQuery);
        $manualPaymentRow = mysqli_fetch_assoc($manualPaymentResult);
        $user_id = $manualPaymentRow['user_id'];
        $item_id = $manualPaymentRow['item_id'];
        $item_type = $manualPaymentRow['item_type'];
        $payment_amount = $manualPaymentRow['amount'];
		
		if($item_type == 1) /*Coin Purchase*/
		{
		       $coinPackageQuery = "SELECT * FROM tbl_coin_list WHERE c_id = '$item_id'";
               $coinPackageResult = mysqli_query($mysqli, $coinPackageQuery);
               $coinPackageRow = mysqli_fetch_assoc($coinPackageResult);
               $coinToBeAdded = $coinPackageRow['c_coin'];
               
               /*Current wallet balance*/
               $userBalanceQuery = "SELECT * FROM tbl_users WHERE id = '$user_id'";
               $userBalanceResult = mysqli_query($mysqli, $userBalanceQuery);
               $userBalanceRow = mysqli_fetch_assoc($userBalanceResult);
               $userBalance = $userBalanceRow['wallet'];
               $newBalance = $userBalance + $coinToBeAdded;
               
               /*Add Coins into user wallet*/
               $updateBalanceQuery = "UPDATE tbl_users SET wallet = '$newBalance' WHERE id= '$user_id'";
               $updateBalanceResult = mysqli_query($mysqli, $updateBalanceQuery);
               
               /*Add Transaction*/
               $insertTransactionQuery = "INSERT INTO tbl_transaction (`user_id`, `type`, `type_no`, `date`, `money`) 
                                      VALUES ('$user_id', 12, '12', NOW(), '$coinToBeAdded')";
               $insertTransactionResult = mysqli_query($mysqli, $insertTransactionQuery);
               
               /*Add Coin Purchase*/
               $insertCoinPurchaseQuery = "INSERT INTO tbl_wallet_passbook (`wp_user`, `wp_package_id`, `wp_order_id`, `wp_date`) 
                                      VALUES ('$user_id', 1, '$transaction_id', NOW())";
               $insertCoinPurchaseResult = mysqli_query($mysqli, $insertCoinPurchaseQuery);
               
		}
		else if($item_type == 2) /*Item Purchase*/
		{
               
               /*Add Order*/
               $addOrderQuery = "INSERT INTO tbl_order
                                (u_id,
                                `offer_id`,
                                `total_amount`,
                                `dis_amount`,
                                `pay_amount`,
                                `o_address`,
                                `order_date`,
                                `o_status`
				                ) VALUES (
                                    '$user_id',
                                    '$item_id',
                                    '$payment_amount',
		    	                	'0',
				                	'$payment_amount',
				                	'Order Manually Added',
				                	'NOW()',
				                		'1'
				                )";
               $addOrderResult = mysqli_query($mysqli, $addOrderQuery);
               
		}
		
		$_SESSION['msg']="manual_transaction_approved";
		 header( "Location:manual_payment.php");
		 exit;
	}
	
	
?>
<head>
<title><?php echo $client_lang['manual_payment_page']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manual_payment_page']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_transaction']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
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
               	 <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                	<?php echo $client_lang[$_SESSION['msg']] ; ?></a> </div>
                <?php unset($_SESSION['msg']);}?>	
              </div>
            </div>
          </div>
          <div class="col-md-12 mrg-top">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th><?php echo $client_lang['payment_proof']; ?></th>
                  <th><?php echo $client_lang['payment_mode']; ?></th>
				  <th><?php echo $client_lang['transaction_id']; ?></th>
				  <th><?php echo $client_lang['paid_by']; ?></th>
				  <th><?php echo $client_lang['action']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{

				?>
                <tr>
                   <td><img src="<?php echo file_exists('../seller/images/'.$users_row['payment_screenshot']) ? '../seller/images/'.$users_row['payment_screenshot'] : 'placeholder.jpg'; ?>" class="img-fluid img-thumbnail" alt="<?php echo $users_row['pg_name']; ?>" style="width: 100px; height: auto;"></td>
		           <td><?php echo $users_row['pg_name'];?></td>
		           <td>#<?php echo $users_row['transaction_id'];?></td>
		           <td><a href="view_user.php?id=<?php echo $users_row['user_id'];?>" title="<?php echo $client_lang['view_user']; ?>"><?php echo $users_row['name'];?></td>
		        <td>
                    <?php if ($users_row['transaction_status'] == 1) { ?>
                        <a href="manual_payment.php?status_active_id=<?php echo $users_row['manual_payment_id'];?>" onclick="return confirm('<?php echo $client_lang['approve_manual_payment']; ?>');" class="btn btn-success" title="Approve Payment">
                            <i class="fa fa-check"></i> <?php echo $client_lang['approve']; ?>
                        </a>
                        <a href="manual_payment.php?status_deactive_id=<?php echo $users_row['manual_payment_id'];?>" onclick="return confirm('<?php echo $client_lang['decline_manual_payment']; ?>');" class="btn btn-delete" title="Decline Payment">
                            <i class="fa fa-close"></i> <?php echo $client_lang['decline']; ?>
                        </a>
                    <?php } elseif ($users_row['transaction_status'] == 0) { ?>
                        <a href="#" class="btn btn-delete" title="Already Declined">
                            <i class="fa fa-close"></i> <?php echo $client_lang['order_cancelled']; ?>
                        </a>
                    <?php } elseif ($users_row['transaction_status'] == 2) { ?>
                        <a href="#" class="btn btn-success" title="Already Approved">
                            <i class="fa fa-check"></i> <?php echo $client_lang['order_processed']; ?>
                        </a>
                    <?php } ?>
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
              	<?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
              </nav>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>     



<?php include('includes/footer.php');?>                  