<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	function get_vendor_info($vendor_id,$field_name) 
	{
		global $mysqli;

		$qry_user="SELECT * FROM tbl_vendor WHERE id='".$vendor_id."'";
		$query1=mysqli_query($mysqli,$qry_user);
		$row_user = mysqli_fetch_array($query1);

		$num_rows1 = mysqli_num_rows($query1);
		
		if ($num_rows1 > 0)
		{		 	
			return $row_user[$field_name];
		}
		else
		{
			return "";
		}
	}
	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id  WHERE tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_offers.o_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_offers";		
							$targetpage = "redeem.php"; 	
							$limit = 15; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName WHERE o_type IN ('3')";
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
							

						 $users_qry="SELECT tbl_offers.*, COUNT(tbl_bid.bd_id) AS total_order
                                     FROM tbl_offers
                                     LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id 
                                     LEFT JOIN tbl_bid ON tbl_offers.o_id = tbl_bid.o_id
                                     WHERE o_type = '3'
                                     GROUP BY tbl_offers.o_id
                                     ORDER BY tbl_offers.o_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['o_id']))
	{
		  
		 
		Delete('tbl_offers','o_id='.$_GET['o_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:redeem.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('o_status'  =>  '0');
		
		$edit_status=Update('tbl_offers', $data, "WHERE o_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:redeem.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('o_status'  =>  '1');
		
		$edit_status=Update('tbl_offers', $data, "WHERE o_id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="13";
		 header( "Location:redeem.php");
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

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Manage Withdrawl Items</div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div> 
                    <div class="add_btn_primary"> <a href="add_redeem.php?add"><i class="fi fi-rr-square-plus"></i>&nbsp;Add Withdrawl Item</a> </div>
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
				 <th>Merchant</th>
                  <th>Item</th>						 
				  <th>Quantity</th>
				  <th>Status</th>
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
				  <td><?php echo get_vendor_info($users_row['id'],'email'); ?> </td>
                   <td><?php echo $users_row['o_name'];?></td>
                   <td style="text-align: right;"><?php echo $users_row['o_qty'] - $users_row['total_order'].' Available';?></td>
		           <td>
		          		<?php if($users_row['o_status']!="0"){?>
		              <a href="redeem.php?status_deactive_id=<?php echo $users_row['o_id'];?>" title="Change Status"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Visible</span></span></a>

		              <?php }else{?>
		              <a href="redeem.php?status_active_id=<?php echo $users_row['o_id'];?>" title="Change Status"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Hidden</span></span></a>
		              <?php }?>
              		</td>
                   <td>
                       <a href="item_details.php?o_id=<?php echo $users_row['o_id'];?>" class="btn blue-btn">See Statistics</a>
                       
                       <?php 
                       if(($users_row['o_qty'] - $users_row['total_order']) == $users_row['o_qty']) { ?>
                           <a href="orders.php?id=<?php echo $users_row['o_id'];?>" class="btn btn-default">No Orders</a>
                       <?php } else { ?>
                           <a href="orders.php?id=<?php echo $users_row['o_id'];?>" class="btn blue-btn">See Orders</a>
                       <?php } ?>
                       <a href="add_redeem.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-primary">Edit</a>
                       <a href="redeem.php?o_id=<?php echo $users_row['o_id'];?>" onclick="return confirm('Are you sure you want to delete this withdrawl item?');" class="btn btn-default">Delete</a></td>
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