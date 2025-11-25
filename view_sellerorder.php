<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php'); 
	
$user_id = sanitize($_GET['id']);

  $qry="SELECT * FROM tbl_vendor where id='$user_id'";
  $result=mysqli_query($mysqli,$qry);
  $categoryRow=mysqli_fetch_assoc($result);
  $sellerName = $categoryRow['email'];
  $sellerEmail = $categoryRow['username'];
  
  $settingsQry="SELECT * FROM tbl_settings where id=1";
  $settingsResult=mysqli_query($mysqli,$settingsQry);
  $settingsRow=mysqli_fetch_assoc($settingsResult);
  $commission=$settingsRow['commission'];
  $currency=$settingsRow['currency'];

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_order LEFT JOIN tbl_offers ON tbl_order.offer_id = tbl_offers.o_id LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id  LEFT JOIN tbl_users ON tbl_users.id = tbl_order.u_id WHERE tbl_offers.o_name LIKE '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_order.o_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_order";		
							$targetpage = "view_sellerorder.php"; 	
							$limit = 1500; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName LEFT JOIN tbl_offers ON tbl_order.offer_id = tbl_offers.o_id WHERE tbl_offers.id='$user_id'";
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
							

						 $users_qry="SELECT * 
                         FROM tbl_order
                         LEFT JOIN tbl_offers ON tbl_order.offer_id = tbl_offers.o_id
                         LEFT JOIN tbl_items ON tbl_items.item_id = tbl_order.offer_id 
                         LEFT JOIN tbl_users ON tbl_users.id = tbl_order.u_id
                         WHERE tbl_offers.id='$user_id'
						 ORDER BY tbl_order.o_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	 
	 if(isset($_GET['o_id']))
	{
	    
	    $offer_id = sanitize($_GET['o_id']);
		  
		 
		Delete('tbl_order','o_id='.$offer_id.'');
		
		$_SESSION['msg']="12";
		header( "Location:view_sellerorder.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('o_status'  =>  '0');
		
		$offer_id = sanitize($_GET['status_deactive_id']);
		
		$edit_status=Update('tbl_order', $data, "WHERE o_id = '".$offer_id."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:view_sellerorder.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('o_status'  =>  '1');
		
		$offer_id = sanitize($_GET['status_active_id']);
		
		$edit_status=Update('tbl_order', $data, "WHERE o_id = '".$offer_id."'");
		
		$_SESSION['msg']="13";
		 header( "Location:view_sellerorder.php");
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

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['order_received_by'].'<br>'.$sellerName.' ('.$sellerEmail.')' ?></div>
              <p class="control-label-help"><?php echo $client_lang['commission_rate'].' '. $commission.'%' ?> </p>
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
				  <th><?php echo $client_lang['order_details']; ?></th>
				  <th><?php echo $client_lang['commission_earned']; ?></th>
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
				   <td>
                        <?php
                        // Display ordered item details
                        echo '<strong>'.$client_lang['ordered_item'].':</strong> ' . $users_row['o_name'] . '<br>';
                        echo '<strong>'.$client_lang['ordered_by'].':</strong> ' . $users_row['name'] . '<br>';
                        echo '<strong>'.$client_lang['order_date'].':</strong>' . $users_row['order_date'] . '<br>';
                    
                        // Display order status
                        echo '<strong>'.$client_lang['order_status'].':</strong> ';
                        if ($users_row['order_status'] == 1) {
                            echo $client_lang['order_received'];
                        } elseif ($users_row['order_status'] == 2) {
                            echo $client_lang['order_processed'];
                        } elseif ($users_row['order_status'] == 3) {
                            echo $client_lang['order_shipped'];
                        } elseif ($users_row['order_status'] == 4) {
                            echo '<span style="color: darkgreen">'.$client_lang['order_delivered'].'</span>';
                        } elseif ($users_row['order_status'] == 5) {
                            echo '<span style="color: darkred">'.$client_lang['order_rejected'].'</span>';
                        }
                        ?>
                    </td>
		           <td>
                        <?php 
                            $pay_amount = $users_row['pay_amount'];
                            $commission_earned = ($pay_amount * $commission) / 100;
                            
                            echo  $currency . number_format($commission_earned, 2) . 
                                 '<br> (' . $commission . '% of ' . $currency . number_format($pay_amount, 2) . ')'; 
                        ?>
                    </td>
                   <td>
                    <a href="view_user.php?id=<?php echo $users_row['u_id'];?>" class="btn blue-btn"><?php echo $client_lang['view_user']; ?></a>
                    <!--<a href="view_order.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-primary"><?php echo $client_lang['view_order']; ?></a>-->
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