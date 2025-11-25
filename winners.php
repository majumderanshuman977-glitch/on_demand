<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_offers.o_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_offers";		
							$targetpage = "winners.php"; 	
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
							
					 				
						 $users_qry="SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id 
						 ORDER BY tbl_offers.o_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	 
$querytime = "SELECT currency FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
$currency = $rowtime['currency'];

?>
<head>
<title><?php echo $client_lang['manage_winners']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_winners']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                   <!-- <div class="add_btn_primary"> <a href="add_winner.php?add">Add Winners</a> </div>-->
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
                  <th><?php echo $client_lang['item_name']; ?></th>						 
				  <th><?php echo $client_lang['status']; ?></th>
                  <th class="cat_action_list"><?php echo $client_lang['action']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
                    $i=0;
                    while($users_row = mysqli_fetch_array($users_result)) {
                        // Check if there are bids placed for this auction
                        $bid_count_query = "SELECT COUNT(*) as num FROM tbl_bid WHERE o_id = '".$users_row['o_id']."'";
                        $bid_count_result = mysqli_query($mysqli, $bid_count_query);
                        $bid_count_row = mysqli_fetch_assoc($bid_count_result);
                        $num_bids = $bid_count_row['num'];
                        // Determine auction status based on current time and bid count
                        $current_time = time();
                        $start_time = strtotime($users_row['o_date'] . ' ' . $users_row['o_stime']);
                        $end_time = strtotime($users_row['o_edate'] . ' ' . $users_row['o_etime']);
                        $o_type = $users_row['o_type'];
                        
                        if ($o_type == 5)
                        {
                            $isAuction = 0;
                        }
                        else
                        {
                            $isAuction = 1;
                        }
                        
                        if ($current_time < $start_time) {
                            $status = "<span class='badge badge-warning badge-icon'><i class='fa fa-clock-o' aria-hidden='true'></i><span>{$client_lang['not_started']}</span></span><br>{$client_lang['starts_on']}: {$users_row['o_date']} {$client_lang['at']} {$users_row['o_stime']}";
                        } elseif ($current_time > $end_time) {
                            if ($num_bids > 0) {
                                $status = "<span class='badge badge-success badge-icon'><i class='fa fa-trophy' aria-hidden='true'></i><span>{$client_lang['winner_announced']} </span></span><br>{$client_lang['ended_on']}: {$users_row['o_edate']} {$client_lang['at']} {$users_row['o_etime']}<br>";
                            } else {
                                if ($isAuction == 1)
                                {
                                    $status = "<span class='badge badge-danger badge-icon'><i class='fa fa-close' aria-hidden='true'></i><span>{$client_lang['no_bids']} </span></span><br>{$client_lang['ended_on']}: {$users_row['o_edate']} {$client_lang['at']} {$users_row['o_etime']}";
                                }
                                else
                                {
                                    $status = "<span class='badge badge-danger badge-icon'><i class='fa fa-close' aria-hidden='true'></i><span>{$client_lang['no_tickets']} </span></span><br>{$client_lang['ended_on']}: {$users_row['o_edate']} {$client_lang['at']} {$users_row['o_etime']}";
                                }
                            }
                        } else {
                            if ($isAuction == 1)
                            {
                                $status = "<span class='badge badge-info badge-icon'><i class='fa fa-gavel' aria-hidden='true'></i><span>{$client_lang['accepting_bid']} | <strong>{$client_lang['current_bid']}: $currency{$users_row['o_min']}</strong></span></span><br>{$client_lang['ends_on']}: {$users_row['o_edate']} {$client_lang['at']} {$users_row['o_etime']}";
                            }
                            else
                            {
                                $status = "<span class='badge badge-info badge-icon'><i class='fa fa-ticket' aria-hidden='true'></i><span>{$client_lang['accepting_ticket']} </span></span><br>{$client_lang['ends_on']}: {$users_row['o_edate']} {$client_lang['at']} {$users_row['o_etime']}";
                            }
                        }
                    ?>
                <tr>
                   <td style="background-color: #fff; padding: 10px; border-radius: 4px;">
                       <img src="<?php echo file_exists('../seller/images/'.$users_row['o_image']) ? '../seller/images/'.$users_row['o_image'] : 'placeholder.jpg'; ?>" class="img-fluid img-thumbnail" alt="<?php echo $users_row['o_name']; ?>" style="width: 100px; height: auto;"><br>
                       <?php echo $users_row['o_name'];?><br>
                       <small>
                           <?php echo $client_lang['sold_by']; ?> 
                           <?php 
                               $seller_email = get_vendor_info($users_row['id'], 'email'); 
                               if (!empty($seller_email)) { 
                                   echo "<a href='view_selleritem.php?id={$users_row['id']}'>{$seller_email}</a>"; 
                               } else { 
                                   echo "Admin"; 
                               } 
                           ?>
                       </small>
                   </td>
		           <td><?php echo $status; ?></td>
                   <td>
                       <div class="button-container">
                          <?php if ($isAuction == 0) { ?>
                           <a href="modify_lottery_winner.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['modifyWinner']; ?></a>
                           <?php } else { ?>
                           <a href="modify_auction_winner.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['modifyWinner']; ?></a>
                           <?php } ?>
                           <!--<a href="download.php?winner_id=<?php echo $users_row['o_id'];?>" class="btn btn-download"><i class="fa fa-download"></i>&nbsp;<?php echo $client_lang['downloadWinners']; ?></a>-->
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
              	<?php if(!isset($_POST["search"])){ include("pagination.php");}?>                 
              </nav>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>     



<?php include('includes/footer.php');?>                  