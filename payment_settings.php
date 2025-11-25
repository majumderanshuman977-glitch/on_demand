<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_payment_gateway WHERE tbl_payment_gateway.pg_name like '%".addslashes($_POST['search_value'])."%'";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_payment_gateway";		
							$targetpage = "payment_settings.php"; 	
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
							

						 $users_qry="SELECT * FROM tbl_payment_gateway
						 ORDER BY tbl_payment_gateway.pg_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['pg_id']))
	{
		  
		 
		Delete('tbl_payment_gateway','pg_id='.$_GET['pg_id'].'');
		
		$_SESSION['msg']="payment_mode_delete";
		header( "Location:payment_settings.php");
		exit;
	}
	
// Active and Deactive status
if (isset($_GET['status_deactive_id'])) {
    $status_deactive_id = sanitize($_GET['status_deactive_id']);
    $data = array('pg_status' => '0');
    $edit_status = Update('tbl_payment_gateway', $data, "WHERE pg_id = '".$status_deactive_id."'");
    $_SESSION['msg'] = "payment_mode_disable";
    header("Location:payment_settings.php");
    exit;
}
if (isset($_GET['status_active_id'])) {
    $status_active_id = sanitize($_GET['status_active_id']);
    $data = array('pg_status' => '1');
    $edit_status = Update('tbl_payment_gateway', $data, "WHERE pg_id = '".$status_active_id."'");
    $_SESSION['msg'] = "payment_mode_visible";
    header("Location:payment_settings.php");
    exit;
}
	
	
?>
<head>
<title><?php echo $client_lang['manage_payment_modes']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_payment_modes']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_payment_mode']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="edit_payment_gateway.php?add"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo $client_lang['add_payment_mode']; ?></a> </div>
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
                  <th><?php echo $client_lang['name']; ?></th>
				  <th><?php echo $client_lang['type']; ?></th>
				  <th><?php echo $client_lang['action']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($payment_option=mysqli_fetch_array($users_result))
						{

				?>
                <tr>
                    <td>
                        <img src="<?php echo '../seller/images/'.$payment_option['pg_image']; ?>" 
                             alt="<?php echo $payment_option['pg_name']; ?>" 
                             width="40" height="40" 
                             style="inline-size: 40px;">
                    </td>                   <td><?php echo $payment_option['pg_name']; ?></td>
                   <td>
                       <?php 
                       if ($payment_option['pg_type'] == 1) {
                           echo $client_lang['auto_mobile'];
                       } elseif ($payment_option['pg_type'] == 2) {
                           echo $client_lang['auto_web'];
                       } elseif ($payment_option['pg_type'] == 3) {
                           echo $client_lang['auto_all'];
                       } elseif ($payment_option['pg_type'] == 4) {
                           echo $client_lang['manual_pay_mode'];
                       }
                       ?>
                   </td>
                  <td>
                      <?php if ($payment_option['pg_status'] == 0) { ?>
                          <a href="payment_settings.php?status_active_id=<?php echo $payment_option['pg_id'];?>" onclick="return confirm('<?php echo $client_lang['show_payment_mode']; ?>');" class="btn btn-danger" title="Show Payment Mode">
                              <i class="fa fa-eye-slash"></i>&nbsp;<?php echo $client_lang['hidden']; ?>
                          </a>
                      <?php } elseif ($payment_option['pg_status'] == 1) { ?>
                          <a href="payment_settings.php?status_deactive_id=<?php echo $payment_option['pg_id'];?>" onclick="return confirm('<?php echo $client_lang['hide_payment_mode']; ?>');" class="btn btn-success" title="Hide Payment Mode">
                              <i class="fa fa-check-square"></i>&nbsp;<?php echo $client_lang['visible']; ?>
                          </a>
                      <?php } ?>
                      <?php 
                      if ($payment_option['pg_type'] != 4) {
                          echo '<a href="payment_gateway.php" class="btn btn-edit">'.$client_lang['modify_payment'].'</a>';
                      } elseif ($payment_option['pg_type'] == 4) {
                          echo '<a href="edit_payment_gateway.php?pg_id=' . $payment_option['pg_id'] . '" class="btn btn-edit">'.$client_lang['edit_payment_mode'].'</a>';
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