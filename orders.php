<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_order LEFT JOIN tbl_items ON tbl_items.item_id = tbl_order.offer_id WHERE tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_order.o_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_order";		
							$targetpage = "orders.php"; 	
							$limit = 15; 
							
							 if (isset($_GET['id'])) {
                                 $offer_id = mysqli_real_escape_string($mysqli, $_GET['id']);
                                 $query = "SELECT COUNT(*) as num FROM $tableName WHERE offer_id = '$offer_id'";
                             } else {
                                 $query = "SELECT COUNT(*) as num FROM $tableName";
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
							

		                    if (isset($_GET['id'])) {
                                $offer_id = mysqli_real_escape_string($mysqli, $_GET['id']);
                                $users_qry = "SELECT *
                                              FROM tbl_order 
                                              LEFT JOIN tbl_users ON tbl_users.id = tbl_order.u_id
                                              LEFT JOIN tbl_items ON tbl_items.item_id = tbl_order.offer_id 
                                              WHERE tbl_order.offer_id = '$offer_id'
                                              ORDER BY tbl_order.o_id DESC 
                                              LIMIT $start, $limit";
                            } else {
                                $users_qry = "SELECT *
                                              FROM tbl_order 
                                              LEFT JOIN tbl_users ON tbl_users.id = tbl_order.u_id
                                              LEFT JOIN tbl_items ON tbl_items.item_id = tbl_order.offer_id 
                                              ORDER BY tbl_order.o_id DESC 
                                              LIMIT $start, $limit";  
                            }
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['o_id']))
	{
		  
		 
		Delete('tbl_order','o_id='.$_GET['o_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:orders.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('o_status'  =>  '0');
		
		$edit_status=Update('tbl_order', $data, "WHERE o_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:orders.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('o_status'  =>  '1');
		
		$edit_status=Update('tbl_order', $data, "WHERE o_id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="13";
		 header( "Location:orders.php");
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
<head>
<title><?php echo $client_lang['manage_order']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_order']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
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
                  <th><?php echo $client_lang['order_details']; ?></th>
				  <th><?php echo $client_lang['ordered_by']; ?></th>
				  <th><?php echo $client_lang['order_status']; ?></th>
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
                        </small>
                        <br>
                    </td>
                    <td><?php echo $users_row['name'];?></td>
                    <td><?php  if($users_row['order_status'] == 1) { echo $client_lang['order_received'];}
							  else if($users_row['order_status'] == 2) { echo $client_lang['order_processed'];}
							  else if($users_row['order_status'] == 3) { echo $client_lang['order_shipped'];}
							  else if($users_row['order_status'] == 4) {  echo $client_lang['order_delivered'];}
							  else if($users_row['order_status'] == 5) {  echo $client_lang['order_rejected'];}
		           ?></td>
                   <td>
                <div class="button-container">
                    <a href="view_order.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-view"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['view_order']; ?></a>
                    <a href="view_user.php?id=<?php echo $users_row['u_id'];?>" class="btn btn-edit"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['view_user']; ?></a>
                </div>
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