<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_ticket
        left join tbl_users on tbl_users.id= tbl_ticket.u_id
        left join tbl_offers on tbl_offers.o_id = tbl_ticket.o_id
        LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
		WHERE tbl_users.name like '%".addslashes($_POST['search_value'])."%' or tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' or tbl_ticket.unique_ticket_id like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_ticket.ticket_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_ticket";		
							$targetpage = "ticket_purchases.php"; 	
							$limit = 15; 
							
							if(isset($_GET['lottery_id'])) {
                            $gameId = mysqli_real_escape_string($mysqli, $_GET['lottery_id']);
                            
							$query = "SELECT COUNT(*) as num FROM $tableName left join tbl_offers on tbl_offers.o_id = tbl_ticket.o_id
		                     LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_offers.o_type = 5 AND tbl_ticket.o_id = $gameId";
		                    
							} else {
		                    
		                    $query = "SELECT COUNT(*) as num FROM $tableName left join tbl_offers on tbl_offers.o_id = tbl_ticket.o_id
		                     LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_offers.o_type = 5";
		                    
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
							
						// Query to fetch tickets associated with the game ID provided in the URL
                        if(isset($_GET['lottery_id'])) {
                            $gameId = mysqli_real_escape_string($mysqli, $_GET['lottery_id']);
                            $users_qry = "SELECT * FROM tbl_ticket
                                LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id
                                LEFT JOIN tbl_offers ON tbl_offers.o_id = tbl_ticket.o_id
                                LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
                                LEFT JOIN tbl_lottery_balls ON tbl_lottery_balls.lottery_balls_id = tbl_offers.lottery_balls_id
                                WHERE tbl_offers.o_type = 5 AND tbl_ticket.o_id = $gameId
                                ORDER BY tbl_ticket.ticket_id DESC LIMIT $start, $limit";  
                        } else {
                            // Default query without game ID filter
                            $users_qry = "SELECT * FROM tbl_ticket
                                LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id
                                LEFT JOIN tbl_offers ON tbl_offers.o_id = tbl_ticket.o_id
                                LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
                                LEFT JOIN tbl_lottery_balls ON tbl_lottery_balls.lottery_balls_id = tbl_offers.lottery_balls_id
                                WHERE tbl_offers.o_type = 5
                                ORDER BY tbl_ticket.ticket_id DESC LIMIT $start, $limit";  
                        }  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['ticket_id']))
	{
		  
		 
		Delete('tbl_ticket','ticket_id='.$_GET['ticket_id'].'');
		
		$_SESSION['msg']="cancelled_ticket";
		header( "Location:ticket_purchases.php");
		exit;
	}
	
	if(isset($_GET['refund_transaction'])) {
        $ticketId = $_GET['refund_transaction'];
    
        // Fetch ticket details
        $queryTicket = "SELECT * FROM tbl_ticket WHERE ticket_id=$ticketId";
        $resultTicket = mysqli_query($mysqli, $queryTicket);
        $rowTicket = mysqli_fetch_assoc($resultTicket);
        
        // Extract ticket purchase amount and user ID
        $ticketPrice = $rowTicket['ticket_price'];
        $userId = $rowTicket['u_id'];
        $gameId = $rowTicket['o_id'];
    
        // Update user's wallet
        $queryUpdateWallet = "UPDATE tbl_users SET wallet = wallet + $ticketPrice WHERE id = $userId";
        mysqli_query($mysqli, $queryUpdateWallet);
    
        // Delete ticket from the database
        $queryDeleteTicket = "DELETE FROM tbl_ticket WHERE ticket_id = $ticketId";
        mysqli_query($mysqli, $queryDeleteTicket);
        
        // Insert transaction record
        $queryInsertTransaction = "INSERT INTO tbl_transaction (user_id, type, type_no, date, money) VALUES ($userId, 9, $gameId, NOW(), $ticketPrice)";
        mysqli_query($mysqli, $queryInsertTransaction);
        
        // Redirect to the page with a success message
       	$_SESSION['msg']="refunded_ticket";
        header("Location: ticket_purchases.php");
        exit;
    }
    
$currency_qry = "SELECT currency FROM tbl_settings";
$currency_result = mysqli_query($mysqli, $currency_qry);
$currency_row = mysqli_fetch_assoc($currency_result);
$currency = $currency_row['currency'];	
	
?>
<head>
<title><?php echo $client_lang['ticket_purchases']; ?></title>
</head>
<style>
.ticket-numbers {
    text-align: left;
}

.ticket-ball {
    display: inline-block;
    position: relative;
    width: 25px; /* Fixed size for ball images */
    height: 25px; /* Fixed size for ball images */
    margin-right: 3px; /* Adjust spacing between balls */
}

.ticket-ball img {
    width: 100%;
    height: 100%;
    display: block;
}

.ticket-number {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 10px; /* Adjust font size */
    font-weight: bold;
    color: #000; /* Text color */
}
</style>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
               <div class="page_title"><?php echo $client_lang['ticket_purchases']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
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
                  <th>#</th>
                  <!--<th><?php echo $client_lang['buyer']; ?></th>	-->
                  <th><?php echo $client_lang['ticket']; ?></th>
                  <th><?php echo $client_lang['details']; ?></th>
                  <th class="cat_action_list"><?php echo $client_lang['action']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
						    $blueBalls = $users_row['normal_ball_limit'];
							$goldBalls = $users_row['premium_ball_limit'];
							$totalBalls = $blueBalls+$goldBalls;

				?>
                <tr> 
                   <td><img src="../generate_qr.php?text=<?php echo urlencode($users_row['unique_ticket_id']); ?>" alt="QR Code" style="width: 90px; height: 90px;"><br><?php echo $users_row['unique_ticket_id']?></td>
                   <!--<td><?php echo $users_row['name'];?></td>-->
		           <td class="ticket-numbers">
                       <?php
                       // Loop through each ball attribute (assuming 8 balls per ticket)
                       for ($j = 1; $j <= $totalBalls; $j++) {
                           $ballValue = $users_row['ball_'.$j];
                           $ballImage = ($j <= $goldBalls) ? 'golden_ball.png' : 'blue_ball.png';
                           echo '<div class="ticket-ball">';
                           echo '<img src="images/static/'.$ballImage.'" alt="'.$ballValue.'" title="'.$ballValue.'" />';
                           echo '<span class="ticket-number">'.$ballValue.'</span>';
                           echo '</div>';
                       }
                       ?>
                   </td>
		           <td><?php echo $client_lang['buyer'].': '; ?><?php echo $users_row['name'];?><br><?php echo $client_lang['purchase_date'].': '; ?><?php echo $users_row['purchase_date'];?><br><?php echo $client_lang['ticket_price'].': '; ?><?php echo $users_row['ticket_price'].' '.$client_lang['coin'];?></td> 
                  <td>
                    <a href="ticket_purchases.php?refund_transaction=<?php echo $users_row['ticket_id'];?>" onclick="return confirm('<?php echo $client_lang['refund_ticket']; ?>');" class="btn btn-refund"><i class="fa fa-history"></i>&nbsp;<?php echo $client_lang['refund']; ?></a>
                    <a href="ticket_purchases.php?ticket_id=<?php echo $users_row['ticket_id'];?>" onclick="return confirm('<?php echo $client_lang['delete_ticket']; ?>');" class="btn btn-delete"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a></td>
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