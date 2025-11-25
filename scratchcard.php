<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_scratch WHERE tbl_offers.s_name like '%".addslashes($_POST['search_value'])."%'";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_scratch";		
							$targetpage = "scratchcard.php"; 	
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
							

			$users_qry="SELECT * FROM tbl_scratch
		    left join tbl_users on tbl_users.id = tbl_scratch.u_id
			ORDER BY tbl_scratch.s_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['s_id']))
	{
		  
		 
		Delete('tbl_scratch','s_id='.$_GET['s_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:scratchcard.php");
		exit;
	}
	
	
?>


 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Manage Scratch Card</div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search scratch card.." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
            </div>
            <div class="add_btn_primary"> <a href="add_itemcard.php?add"><i class="fi fi-rr-box-open"></i>&nbsp;Add Item Card</a> &nbsp; <a href="add_couponcard.php?add"><i class="fi fi-rr-ticket"></i>&nbsp;Add Coupon Card</a> &nbsp; <a href="add_coincard.php?add"><i class="fi fi-rr-token"></i>&nbsp;Add Coin Card</a> 
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
                  <th>Scratch Card Name</th>
                  <th>User</th>
				  <th>Card Type</th>
				  <th>Card Status</th>
				  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
				?>
                <tr>
                    <td><?php echo $users_row['s_name'];?></td>
                    <td><?php echo $users_row['name'];?></td>
                    <td><?php  if($users_row['s_type'] == 1) { echo 'Scratch &amp; win Coin' ;}
							  else if($users_row['s_type'] == 2) { echo 'Scratch &amp; win Item' ;}
							  else if($users_row['s_type'] == 3) { echo 'Scratch &amp; win Coupon' ;}
		           ?></td>
                    <td><?php  if($users_row['s_status'] == 0) { echo 'Not Unlocked' ;}
							  else if($users_row['s_status'] == 1) {  echo '<span style="color: darkred"> Not Scratched</span>';}
							  else if($users_row['s_status'] == 2) {  echo '<span style="color:darkgreen"> Scratched</span>';}
							  else if($users_row['s_status'] == 3) {  echo '<span style="color:darkgreen"> Claimed</span>';}
		           ?></td>
                   <td>
                <?php if ($users_row['s_type'] == 1): ?>
                    <a href="add_coincard.php?s_id=<?php echo $users_row['s_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit_scratchcard']; ?></a>
                <?php elseif ($users_row['s_type'] == 2): ?>
                    <a href="add_itemcard.php?s_id=<?php echo $users_row['s_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit_scratchcard']; ?></a>
                <?php elseif ($users_row['s_type'] == 3): ?>
                    <a href="add_couponcard.php?s_id=<?php echo $users_row['s_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit_scratchcard']; ?></a>
                <?php endif; ?>
            
                   
                    <a href="scratchcard.php?s_id=<?php echo $users_row['s_id'];?>" onclick="return confirm('Are you sure you want to delete this Scratch Card?');" class="btn btn-delete"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a>
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