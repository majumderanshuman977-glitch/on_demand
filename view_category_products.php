<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php'); 
	
    $qry="SELECT * FROM tbl_cat where c_id='".$_GET['c_id']."'";
    $result=mysqli_query($mysqli,$qry);
    $categoryRow=mysqli_fetch_assoc($result);
    $cName = $categoryRow['c_name'];

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_offers.o_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_offers";		
							$targetpage = "view_category_products.php"; 	
							$limit = 15; 
							
							$c_id = sanitize($_GET['c_id']);
							
							$query = "SELECT COUNT(*) as num FROM $tableName WHERE c_id='".$c_id."'";
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
						 WHERE c_id='".$c_id."'
						 ORDER BY tbl_offers.o_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	 
	 if(isset($_GET['o_id']))
	{
	    $o_id = sanitize($_GET['o_id']);
		  
		 
		Delete('tbl_offers','o_id='.$o_id.'');
		
		$_SESSION['msg']="12";
		header( "Location:view_category_products.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('o_status'  =>  '0');
		
		$o_id = sanitize($_GET['status_deactive_id']);
		
		$edit_status=Update('tbl_offers', $data, "WHERE o_id = '".$o_id."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:view_category_products.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('o_status'  =>  '1');
		$o_id = sanitize($_GET['status_active_id']);
		
		$edit_status=Update('tbl_offers', $data, "WHERE o_id = '".$o_id."'");
		
		$_SESSION['msg']="13";
		 header( "Location:view_category_products.php");
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
    
</style>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>

<head>
<title><?php echo $cName ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $cName ?></div>
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
				 <th><?php echo $client_lang['sold_by']; ?></th>
                  <th><?php echo $client_lang['item_name']; ?></th>						 
				  <th><?php echo $client_lang['lottery_start_date']; ?></th>
				  <th><?php echo $client_lang['lottery_end_date']; ?></th>
				  <th><?php echo $client_lang['status']; ?></th>
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
				   <td><?php echo !empty(get_vendor_info($users_row['id'],'email')) ? get_vendor_info($users_row['id'],'email') : "Admin"; ?></td>
                   <td><?php echo $users_row['o_name'];?></td>
		           <td><?php echo $users_row['o_date'];?><br><?php echo $users_row['o_stime'];?></td>   
				   <td><?php echo $users_row['o_edate'];?><br><?php echo $users_row['o_etime'];?></td> 
		           <!--<td><a href="download.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-success">Download Bids&nbsp;<i class="fi fi-rr-file-download"></i></a>  Added Download Bids button --></td>
		           <td>
		          		<?php if($users_row['o_status']!="0"){?>
		              <a href="view_category_products.php?status_deactive_id=<?php echo $users_row['o_id'];?>" title="Change Status"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Visible</span></span></a>

		              <?php }else{?>
		              <a href="view_category_products.php?status_active_id=<?php echo $users_row['o_id'];?>" title="Change Status"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Hidden</span></span></a>
		              <?php }?>
              		</td>
              	    <?php
                    
                    if (in_array($users_row['o_type'], [1, 2, 7, 8, 10, 11])) {
                        echo '<td><a href="auction_details.php?o_id=' . $users_row['o_id'] . '" class="btn blue-btn" title="View Details"><i class="fi fi-rs-eye"></i>&nbsp;'.$client_lang['stats'].'</a>';
                    } elseif (in_array($users_row['o_type'], [4, 5])) {
                        echo '<td><a href="lottery_details.php?o_id=' . $users_row['o_id'] . '" class="btn blue-btn" title="View Details"><i class="fi fi-rs-eye"></i>&nbsp;'.$client_lang['stats'].'</a>';
                    } elseif (in_array($users_row['o_type'], [3, 9])) {
                        echo '<td><a href="item_details.php?o_id=' . $users_row['o_id'] . '" class="btn blue-btn" title="View Details"><i class="fi fi-rs-eye"></i>&nbsp;'.$client_lang['stats'].'</a>';
                    }
                    ?>
                   
                   
                   <?php
                    if (in_array($users_row['o_type'], [1, 2])) {
                        echo '<a href="add_uniquebid_auction.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [4, 5])) {
                        echo '<a href="add_lottery.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [7])) {
                        echo '<a href="add_english-auction.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [8])) {
                        echo '<a href="add_penny-auction.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [10])) {
                        echo '<a href="add_reverse-auction.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [11])) {
                        echo '<a href="add_slot-auction.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [9])) {
                        echo '<a href="add_shop.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    } elseif (in_array($users_row['o_type'], [3])) {
                        echo '<a href="add_redeem.php?o_id=' . $users_row['o_id'] . '" class="btn btn-edit" title="Edit Auction"><i class="fi fi-br-pencil"></i>&nbsp;'.$client_lang['edit'].'</a>';
                    }
                    ?>
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