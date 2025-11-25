<?php include('includes/header.php');

    include('includes/function.php');
	include('language/language.php'); 

 	require_once("thumbnail_images.class.php");
 	
	
	if(isset($_GET['id']))
	{
			 
			$user_qry="SELECT * FROM tbl_users where id='".$_GET['id']."'";
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
            if(isset($_POST['submit']) and isset($_POST['id']))
            {
                // Calculate new wallet value for the user
                $transactionType = sanitize($_POST['transaction_type']);
                $coinValue = sanitize($_POST['topup']);
                $userId = sanitize($_POST['id']);
                $transactionTypeId = sanitize($_POST['transaction_type_id']);
                $comments = sanitize($_POST['comments']);
                
                if ($transactionType == 1)
                {
                    $finalCoinValue =  $coinValue;
                    
                    $data = array(
                    'wallet' => $user_row['wallet'] + $finalCoinValue,
                );
                
                // Update the user's wallet
                $user_edit = Update('tbl_users', $data, "WHERE id = '".$userId."'");
            
                if ($user_edit > 0) {
                    
                    $insertMoneyValue = ('+ '.$coinValue);
                    
                        /*Add Transaction*/
                        $insertTransactionQuery = "INSERT INTO tbl_transaction (`user_id`, `type`, `type_no`, `date`, `money`, `comments`) 
                                                   VALUES ('$userId', $transactionTypeId, '$transactionTypeId', NOW(), '$insertMoneyValue', '$comments')";
                        $insertTransactionResult = mysqli_query($mysqli, $insertTransactionQuery);
                    
                        $_SESSION['msg'] = "17";
                        header("Location:change_balance.php?id=".$_POST['id']);
                        exit;
                    }
                }
                
                else if ($transactionType == 0 && $user_row['wallet'] < $coinValue)
                {
                    $_SESSION['msg'] = "18";
                     header("Location:change_balance.php?id=".$_POST['id']);
                     exit;
                }
                
                else if ($transactionType == 0 && $user_row['wallet'] > $coinValue)
                {
                    $finalCoinValue =  (-1 * $coinValue);
                    
                    $data = array(
                    'wallet' => $user_row['wallet'] + $finalCoinValue,
                );
                
                // Update the user's wallet
                $user_edit = Update('tbl_users', $data, "WHERE id = '".$_POST['id']."'");
            
                if ($user_edit > 0) {
                    
                        $insertMoneyValue = ('- '.$coinValue);
                    
                        /*Add Transaction*/
                        $insertTransactionQuery = "INSERT INTO tbl_transaction (`user_id`, `type`, `type_no`, `date`, `money`, `comments`) 
                                                   VALUES ('$userId', $transactionTypeId, '$transactionTypeId', NOW(), '$insertMoneyValue', '$comments')";
                        $insertTransactionResult = mysqli_query($mysqli, $insertTransactionQuery);
                    
                        $_SESSION['msg'] = "17";
                        header("Location:change_balance.php?id=".$_POST['id']);
                        exit;
                    }
                }
            }
            
		
	 
	
	
	
?>
<head>
<title><?php echo $client_lang['change_balance']; ?></title>
</head> 	

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['change_balance_title']; ?></div>
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
                    <label class="col-md-3 control-label"><h4><u><?php echo $client_lang['user_details']; ?></u></h4></label>
                  </div>
                    <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_name']; ?>:- <?php if(isset($_GET['id'])){echo $user_row['name'];}?></label>
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_email']; ?>:- <?php if(isset($_GET['id'])){echo $user_row['email'];}?></label>
                    <label class="col-md-3 control-label"><?php echo $client_lang['user_mobile']; ?>:- <?php if(isset($_GET['id'])){echo '+'.$user_row['country_code'].' '.$user_row['phone'];}?></label>
                  </div>
                 
                 <div class="form-group">
                    <label class="col-md-3 control-label"><b><?php echo $client_lang['user_balance']; ?>:- <?php if(isset($_GET['id'])){echo $user_row['wallet'].' '.$client_lang['coin'];}?></b></label>
                    <div class="col-md-6">
                    </div>
                  </div>
                  <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['transaction_type']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['transaction_type_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="transaction_type" id="transaction_type" style="width:280px; height:25px;" class="select2" required>
                            <option value="">-<?php echo $client_lang['transaction_type']; ?>-</option>
                            <option value="0"><?php echo $client_lang['debit_coins']; ?></option>
                            <option value="1"><?php echo $client_lang['credit_coins']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['transaction_detail']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['transaction_detail_help']; ?></p></label>
                      <div class="col-md-6">                       
                        <select name="transaction_type_id" id="transaction_type_id" style="width:280px; height:25px;" class="select2" required>
                            <option value="13"><?php echo $client_lang['manual_adjustment']; ?></option>
                            <option value="14"><?php echo $client_lang['reward_adjustment']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $client_lang['coin_value']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['coin_value_help']; ?></p></label>
                        <div class="col-md-6">
                            <input type="text" name="topup" id="topup" placeholder="Number of coins you want to debit/credit" class="form-control" required>
                            <p id="error-message" style="color: red; display: none;"></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?php echo $client_lang['comments']; ?>:-<br>
                        <p class="control-label-help"><?php echo $client_lang['comments_help']; ?></p></label>
                        <div class="col-md-6">
                            <input type="text" name="comments" id="comments" placeholder="Optional" value="" class="form-control">
                        </div>
                    </div>

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                 
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php echo $client_lang['update_coins']; ?></button>
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