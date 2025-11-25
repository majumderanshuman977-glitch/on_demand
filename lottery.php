<?php 
include('includes/header.php'); 
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
							$targetpage = "lottery.php"; 	
							$limit = 15; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName WHERE o_type IN ('5')";
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
							

						 $users_qry="SELECT * FROM tbl_offers
						 LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id
						 WHERE o_type = '5'
						 ORDER BY tbl_offers.o_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['o_id']))
	{
		  
		 
		Delete('tbl_offers','o_id='.$_GET['o_id'].'');
		
		$_SESSION['msg']="lottery_delete";
		header( "Location:lottery.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('o_status'  =>  '0');
		
		$edit_status=Update('tbl_offers', $data, "WHERE o_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="lottery_disable";
		 header( "Location:lottery.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('o_status'  =>  '1');
		
		$edit_status=Update('tbl_offers', $data, "WHERE o_id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="lottery_visible";
		 header( "Location:lottery.php");
		 exit;
	}
	
	
?>
<style>
    .blue-btn {
        background-color: #007bff; /* Blue background color */
        color: #fff; /* White text color */
        padding: 10px 20px; /* Padding for the button */
        border: none; /* No border */
        border-radius: 4px; /* Rounded corners */
        cursor: pointer; /* Cursor style */
        text-decoration: none; /* Remove default text decoration */
        display: inline-block; /* Make it inline-block to adjust width */
        transition: background-color 0.3s; /* Smooth transition on hover */
    }
    
    .blue-btn:hover {
        color: #fff;
        background-color: #0056b3; /* Darker blue color on hover */
    }
    .btn-prizes {
        background-color: #28a745; /* Green background */
        border-color: #28a745; /* Matching border color */
        color: #fff; /* White text */
    }
    
    .btn-prizes:hover {
        background-color: #218838; /* Darker green for hover */
        border-color: #1e7e34; /* Darker green border for hover */
        color: #fff; /* White text */
    }
    .btn-download {
        background-color: #6f42c1; /* Purple background color */
        color: #fff; /* White text color */
    }
    
    .btn-download:hover {
        background-color: #5a32a3; /* Darker purple color on hover */
        color: #fff; /* White text color */
    }
</style>
<head>
<title><?php echo $client_lang['manage_lottery']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_lottery']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_lottery']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div> 
                    <div class="add_btn_primary"> <a href="add_lottery.php?add"><i class="fa fa-plus"></i>&nbsp;<?php echo $client_lang['add_lottery']; ?></a> </div>
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
                  <th><?php echo $client_lang['lottery_details']; ?></th>						 
				  <th><?php echo $client_lang['ticket_price']; ?></th>
                 <th class="cat_action_list"><?php echo $client_lang['action']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
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
                        </small><br><br>
                        <?php echo $client_lang['starts_on'].': '.$users_row['o_date'].' '. $users_row['o_stime'];?><br>
                        <?php echo $client_lang['ends_on'].': '.$users_row['o_edate'].' '. $users_row['o_etime'];?>
                    </td>
                    <td><?php echo $users_row['o_amount'].' '.$client_lang['coin'];?></td> 
                   <td>
                      <div class="button-container">
                        <?php if ($users_row['o_status'] == 0) { ?>
                            <a href="lottery.php?status_active_id=<?php echo $users_row['o_id'];?>" onclick="return confirm('<?php echo $client_lang['show_lottery']; ?>');" class="btn btn-danger" title="Show Lottery">
                                <i class="fa fa-eye-slash"></i>&nbsp;<?php echo $client_lang['hidden']; ?>
                            </a>
                        <?php } elseif ($users_row['o_status'] == 1) { ?>
                            <a href="lottery.php?status_deactive_id=<?php echo $users_row['o_id'];?>" onclick="return confirm('<?php echo $client_lang['hide_lottery']; ?>');" class="btn btn-success" title="Hide Lottery">
                                <i class="fa fa-check-square"></i>&nbsp;<?php echo $client_lang['visible']; ?>
                            </a>
                        <?php } ?>
                        <a href="lottery_details.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-view" title="View Details"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['stats']; ?></a>
                        <a href="add_lottery.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-edit" title="Edit Lottery"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                        <a href="prizes.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-prizes" title="View Prizes"><i class="fa fa-trophy"></i>&nbsp;<?php echo $client_lang['prizes']; ?></a>
                        <a href="refund_game.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-refund" title="Cancel & Refund"><i class="fa fa-history">&nbsp;</i><?php echo $client_lang['refund']; ?></a>
                        <a href="download.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-download" title="Download Tickets"><i class="fa fa-download"></i>&nbsp;<?php echo $client_lang['download_ticket']; ?></a>
                        <a href="lottery.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-delete" title="Delete Lottery"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a>
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