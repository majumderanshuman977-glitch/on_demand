<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_lottery_balls
		WHERE tbl_lottery_balls.lottery_balls_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_lottery_balls.lottery_balls_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_lottery_balls";		
							$targetpage = "lottery_design.php"; 	
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
							
						// Query to fetch tickets associated with the game ID provided in the URL
                        
                            $users_qry = "SELECT * FROM tbl_lottery_balls
                                          ORDER BY tbl_lottery_balls.lottery_balls_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
    
$currency_qry = "SELECT currency FROM tbl_settings";
$currency_result = mysqli_query($mysqli, $currency_qry);
$currency_row = mysqli_fetch_assoc($currency_result);
$currency = $currency_row['currency'];	
	
?>
<head>
<title><?php echo $client_lang['lottery_design']; ?></title>
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
               <div class="page_title"><?php echo $client_lang['lottery_design']; ?></div>
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
                  <th><?php echo $client_lang['lottery_design_name']; ?></th>	
                  <th><?php echo $client_lang['ticket_design']; ?></th>
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
                   <td><?php echo $users_row['lottery_balls_name'];?></td>
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
                  <td>
                    <a href="manage_lotterydesign.php?design_id=<?php echo $users_row['lottery_balls_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
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