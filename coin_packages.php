<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  
	
	// Retrieve currency symbol from tbl_settings
$settings_qry = "SELECT currency FROM tbl_settings LIMIT 1";
$settings_result = mysqli_query($mysqli, $settings_qry);
$settings_row = mysqli_fetch_array($settings_result);
$currency_symbol = $settings_row['currency'];



	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_coin_list WHERE tbl_coin_list.name like '%".addslashes($_POST['search_value'])."%' or tbl_coin_list.email like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_coin_list.id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

					$tableName="tbl_coin_list";		
					$targetpage = "coin_packages.php"; 	
					$limit = 150; 
					
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
							
							
						 $users_qry="SELECT * FROM tbl_coin_list
						 WHERE c_id !=1
						 ORDER BY tbl_coin_list.c_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['c_id']))
	{
		  
		 
		Delete('tbl_coin_list','c_id='.$_GET['c_id'].'');
		
		$_SESSION['msg']="package_delete";
		header( "Location:coin_packages.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('c_status'  =>  '0');
		
		$edit_status=Update('tbl_coin_list', $data, "WHERE c_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="package_disable";
		 header( "Location:coin_packages.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('c_status'  =>  '1');
		
		$edit_status=Update('tbl_coin_list', $data, "WHERE c_id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="package_visible";
		 header( "Location:coin_packages.php");
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
<title><?php echo $client_lang['manage_recharge']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_recharge']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                
                    <div class="add_btn_primary"> <a href="add_coin_packages.php?add"><i class="fa fa-plus"></i>&nbsp;<?php echo $client_lang['add_package']; ?></a> </div>
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
                    <th><?php echo $client_lang['package_name']; ?></th>						 
                    <th><?php echo $client_lang['coin']; ?></th>
                    <th><?php echo $client_lang['package_price']; ?></th>
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
                    <td><?php echo $users_row['c_name'];?></td>
                    <td><?php echo $users_row['c_coin'].' '.$client_lang['coin'];?></td>   
                    <td><?php echo $currency_symbol . $users_row['c_amount'] . '/-'; ?></td>
		          
                   <td>     
                   <?php if ($users_row['c_status'] == 0) { ?>
                        <a href="coin_packages.php?status_active_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['show_package']; ?>');" class="btn btn-danger" title="Show Coin Package">
                            <i class="fa fa-eye-slash"></i>&nbsp;<?php echo $client_lang['hidden']; ?>
                        </a>
                    <?php } elseif ($users_row['c_status'] == 1) { ?>
                        <a href="coin_packages.php?status_deactive_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['hide_package']; ?>');" class="btn btn-success" title="Hide Coin Package  ">
                            <i class="fa fa-check-square"></i>&nbsp;<?php echo $client_lang['visible']; ?>
                        </a>
                    <?php } ?>
                    <a href="coin_purchases.php?id=<?php echo $users_row['c_id'];?>" class="btn btn-view"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['view_purchase']; ?></a>
                    <a href="add_coin_packages.php?c_id=<?php echo $users_row['c_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                    <a href="coin_packages.php?c_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['delete_package']; ?>');" class="btn btn-delete"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a></td>
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