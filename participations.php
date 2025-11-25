<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_bid
        left join tbl_users on tbl_users.id= tbl_bid.u_id
        left join tbl_offers on tbl_offers.o_id = tbl_bid.o_id
        LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
		WHERE tbl_users.name like '%".addslashes($_POST['search_value'])."%' or tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_bid.bd_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_bid";		
							$targetpage = "participations.php"; 	
							$limit = 15; 
							
							if(isset($_GET['game_id'])) {
                            $gameId = mysqli_real_escape_string($mysqli, $_GET['game_id']);
                            
							$query = "SELECT COUNT(*) as num FROM $tableName left join tbl_offers on tbl_offers.o_id = tbl_bid.o_id
		                     LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_offers.o_type IN (1,2,4,5,7,8) AND tbl_bid.o_id = $gameId";
		                    
							} else {
		                    
		                    $query = "SELECT COUNT(*) as num FROM $tableName left join tbl_offers on tbl_offers.o_id = tbl_bid.o_id
		                     LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_offers.o_type IN (1,2,4,5,7,8)";
		                    
							}
		                
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
							
						// Query to fetch bids associated with the game ID provided in the URL
                        if(isset($_GET['game_id'])) {
                            $gameId = mysqli_real_escape_string($mysqli, $_GET['game_id']);
                            $users_qry = "SELECT * FROM tbl_bid
                                LEFT JOIN tbl_users ON tbl_users.id = tbl_bid.u_id
                                LEFT JOIN tbl_offers ON tbl_offers.o_id = tbl_bid.o_id
                                LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
                                WHERE tbl_offers.o_type IN (1,2,4,5,7,8) AND tbl_bid.o_id = $gameId
                                ORDER BY tbl_bid.bd_id DESC LIMIT $start, $limit";  
                        } else {
                            // Default query without game ID filter
                            $users_qry = "SELECT * FROM tbl_bid
                                LEFT JOIN tbl_users ON tbl_users.id = tbl_bid.u_id
                                LEFT JOIN tbl_offers ON tbl_offers.o_id = tbl_bid.o_id
                                LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
                                WHERE tbl_offers.o_type IN (1,2,4,5,7,8)
                                ORDER BY tbl_bid.bd_id DESC LIMIT $start, $limit";  
                        }  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['bd_id']))
	{
		  
		 
		Delete('tbl_bid','bd_id='.$_GET['bd_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:participations.php");
		exit;
	}
	
	if(isset($_GET['refund_transaction'])) {
        $bidId = $_GET['refund_transaction'];
    
        // Fetch bid details
        $queryBid = "SELECT * FROM tbl_bid WHERE bd_id=$bidId";
        $resultBid = mysqli_query($mysqli, $queryBid);
        $rowBid = mysqli_fetch_assoc($resultBid);
        
        // Extract bid amount and user ID
        $bidAmount = $rowBid['bd_amount'];
        $userId = $rowBid['u_id'];
        $gameId = $rowBid['o_id'];
    
        // Update user's wallet
        $queryUpdateWallet = "UPDATE tbl_users SET wallet = wallet + $bidAmount WHERE id = $userId";
        mysqli_query($mysqli, $queryUpdateWallet);
    
        // Delete bid from the database
        $queryDeleteBid = "DELETE FROM tbl_bid WHERE bd_id = $bidId";
        mysqli_query($mysqli, $queryDeleteBid);
        
        // Insert transaction record
        $queryInsertTransaction = "INSERT INTO tbl_transaction (user_id, type, type_no, date, money) VALUES ($userId, 9, $gameId, NOW(), $bidAmount)";
        mysqli_query($mysqli, $queryInsertTransaction);
        
        // Redirect to the page with a success message
       	$_SESSION['msg']="25";
        header("Location: participations.php");
        exit;
    }


	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('bd_status'  =>  '0');
		
		$edit_status=Update('tbl_bid', $data, "WHERE bd_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:participations.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('bd_status'  =>  '1');
		
		$edit_status=Update('tbl_bid', $data, "WHERE bd_id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="13";
		 header( "Location:participations.php");
		 exit;
	}
$currency_qry = "SELECT currency FROM tbl_settings";
$currency_result = mysqli_query($mysqli, $currency_qry);
$currency_row = mysqli_fetch_assoc($currency_result);
$currency = $currency_row['currency'];	
	
?>


 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Manage Participation</div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <!--<div class="add_btn_primary"> <a href="add_raffle.php.php?add">Add Offers</a> </div>-->
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
                  <th>User</th>	
                  <th>Item</th>	
				   <th>Details</th>
                  <th class="cat_action_list">Action</th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{

				?>
                <tr>
                   <td><?php echo $users_row['name'];?></td>
		           <td><?php echo $users_row['o_name'];?><br><?php echo $users_row['bd_amount'].' Coins';?></td> 
		           <td>
                        <?php
                        if (in_array($users_row['o_type'], array(1, 2, 7, 8))) {
                            // Display as currency if o_type is 1, 2, 7, or 8
                            echo 'Placed Bid of: '.$currency . $users_row['bd_value'].'<br> on '.$users_row['bd_date'];
                        } elseif (in_array($users_row['o_type'], array(4, 5))) {
                            // Display as a pound sign if o_type is 4 or 5
                            echo 'Purchased Ticket: #' . $users_row['bd_value'].'<br> on '.$users_row['bd_date'];
                        } else {
                            // Handle other cases if needed
                            echo $users_row['bd_value'];
                        }
                        ?>
                    </td>
                  <td>
                    <a href="participations.php?refund_transaction=<?php echo $users_row['bd_id'];?>" onclick="return confirm('Are you sure you want to cancel and refund this entry?');" class="btn btn-refund"><i class="fa fa-history"></i>&nbsp;<?php echo $client_lang['refund']; ?></a>
                    <a href="participations.php?bd_id=<?php echo $users_row['bd_id'];?>" onclick="return confirm('Are you sure you want to delete this entry?');" class="btn btn-delete"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a></td>
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