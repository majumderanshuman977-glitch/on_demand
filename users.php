<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_users WHERE tbl_users.name like '%".addslashes($_POST['search_value'])."%' or tbl_users.email like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_users.id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {
					$tableName="tbl_users";		
					$targetpage = "users.php"; 	
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
							
							
						 $users_qry="SELECT * FROM tbl_users
						 WHERE status = 1
						 ORDER BY tbl_users.id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
    	 // Check if there are any results
    if (mysqli_num_rows($users_result) == 0) {
        $title = $client_lang['no_users'];
        $description = $client_lang['no_users_description'];
        include('nodata.php');
        exit;
    }
	
		//Active and Deactive status
	if(isset($_GET['status_deactive_id1']))
	{
		$data = array('ban' => '0');
		
		$edit_status=Update('tbl_users', $data, "WHERE id = '".$_GET['status_deactive_id1']."'");
		
		 $_SESSION['msg']="user_not_banned";
		 header( "Location:users.php");
		 exit;
	}
	
	if(isset($_GET['status_active_id1']))
	{
		$data = array('ban' => '1');
		
		$edit_status=Update('tbl_users', $data, "WHERE id = '".$_GET['status_active_id1']."'");
		
		$_SESSION['msg']="user_banned";
		 header( "Location:users.php");
		 exit;
	}
        $querytime = "SELECT demo_access FROM tbl_settings";
        $resulttime = mysqli_query($mysqli, $querytime);
        $rowtime = mysqli_fetch_assoc($resulttime);
        $demo_access = $rowtime['demo_access'];
          	

?>

<head>
<title><?php echo $client_lang['manage_users']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_user']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search User" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                   <div class="add_btn_primary"> <a href="add_user.php?add"><i class="fa fa-plus"></i>&nbsp;<?php echo $client_lang['add_user']; ?></a> </div>
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
                    <th></th>
                    <th><?php echo $client_lang['user_name']; ?></th>						 
                    <th><?php echo $client_lang['user_details']; ?></th>
                    <th><?php echo $client_lang['user_balance']; ?></th>
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
                        <?php
                        $image_path = '../seller/images/' . $users_row['image'];
                        $placeholder_image = 'placeholder_user.jpg';
                        $image_src = (isset($users_row['image']) && !empty($users_row['image']) && file_exists($image_path)) ? $image_path : $placeholder_image;
                        ?>
                        <img src="<?php echo $image_src; ?>" class="img-fluid img-thumbnail" alt="<?php echo htmlspecialchars($users_row['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 100px; height: auto;"><br>
                    </td>
                    <td><?php echo $users_row['name'];?></td>
                    <td>    <?php echo $client_lang['user_mobile']; ?>:&nbsp;<?php if ($demo_access == 1) { ?><?php echo $client_lang['hidden_demo']; ?><?php } else { ?><?php echo '+'.$users_row['country_code'].' '.$users_row['phone'];?><?php } ?>
                        <br><?php echo $client_lang['user_email']; ?>:&nbsp;<?php if ($demo_access == 1) { ?><?php echo $client_lang['hidden_demo']; ?><?php } else { ?><?php echo $users_row['email'];?><?php } ?>
                    </td>
                    <td><?php echo $users_row['wallet'];?></td>  
                   <td>
                      <div class="button-container">
                          <a href="view_user.php?id=<?php echo $users_row['id'];?>" class="btn btn-view" title="View User"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['stats']; ?></a>
                          <a href="add_user.php?id=<?php echo $users_row['id'];?>" class="btn btn-edit" title="Edit User"><i class="fi fi-sr-pencil"></i></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                          
                          <a href="change_balance.php?id=<?php echo $users_row['id'];?>" class="btn btn-success" title="Change Balance"><i class="fi fi-br-coins"></i></i>&nbsp;<?php echo $client_lang['balance']; ?></a>
                          <?php if($users_row['ban'] == "1")
		          		{?>
		              <a href="users.php?status_deactive_id1=<?php echo $users_row['id'];?>" class="btn btn-primary" title="Allow Access"><i class="fa fa-check"></i></i>&nbsp;<?php echo $client_lang['unban']; ?></a>

		              <?php }else{?>
		              <a href="users.php?status_active_id1=<?php echo $users_row['id'];?>" class="btn btn-delete" title="Restrict Access"><i class="fa fa-ban"></i></i>&nbsp;<?php echo $client_lang['ban']; ?></a>
		              <?php }?>
		              <a href="referrals.php?id=<?php echo $users_row['id'];?>" class="btn btn-refund" title="View Referrals"><i class="fi fi-rs-users-alt"></i></i>&nbsp;<?php echo $client_lang['referral']; ?></a>
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