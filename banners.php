<?php 
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');  

if (isset($_POST['user_search'])) {
    $search_value = sanitize($_POST['search_value']);
    $user_qry = "SELECT * FROM tbl_offers 
                 LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id 
                 WHERE tbl_items.o_name LIKE ? 
                 ORDER BY tbl_offers.o_id DESC";
    $stmt = $mysqli->prepare($user_qry);
    $search_value = "%$search_value%";
    $stmt->bind_param("s", $search_value);
    $stmt->execute();
    $users_result = $stmt->get_result();
} else {
    $tableName = "tbl_offers";        
    $targetpage = "banners.php";     
    $limit = 15; 
    
    $query = "SELECT COUNT(*) as num FROM $tableName WHERE o_type IN ('6')";
    $total_pages = mysqli_fetch_array(mysqli_query($mysqli, $query));
    $total_pages = $total_pages['num'];
    
    $stages = 3;
    $page = 0;
    if (isset($_GET['page'])) {
        $page = sanitize($_GET['page']);
    }
    if ($page) {
        $start = ($page - 1) * $limit; 
    } else {
        $start = 0;    
    }
    
    $users_qry = "SELECT * FROM tbl_offers
                  LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id 
                  WHERE o_type = '6'
                  ORDER BY tbl_offers.o_id DESC LIMIT ?, ?";
    $stmt = $mysqli->prepare($users_qry);
    $stmt->bind_param("ii", $start, $limit);
    $stmt->execute();
    $users_result = $stmt->get_result();
}

if (isset($_GET['o_id'])) {
    $o_id = sanitize($_GET['o_id']);
    Delete('tbl_offers', 'o_id='.$o_id);
    $_SESSION['msg'] = "banner_delete";
    header("Location:banners.php");
    exit;
}

// Active and Deactive status
if (isset($_GET['status_deactive_id'])) {
    $status_deactive_id = sanitize($_GET['status_deactive_id']);
    $data = array('o_status' => '0');
    $edit_status = Update('tbl_offers', $data, "WHERE o_id = '".$status_deactive_id."'");
    $_SESSION['msg'] = "banner_disable";
    header("Location:banners.php");
    exit;
}
if (isset($_GET['status_active_id'])) {
    $status_active_id = sanitize($_GET['status_active_id']);
    $data = array('o_status' => '1');
    $edit_status = Update('tbl_offers', $data, "WHERE o_id = '".$status_active_id."'");
    $_SESSION['msg'] = "banner_visible";
    header("Location:banners.php");
    exit;
}
?>

<head>
<title><?php echo $client_lang['banner']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_banners']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search..." aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_banners.php?add"><i class="fi fi-rs-banner-5"></i>&nbsp;<?php echo $client_lang['add_banner']; ?></a> </div>&nbsp;
                    <div class="add_btn_primary"> <a href="add_itembanner.php?add"><i class="fi fi-rr-box-open"></i>&nbsp;<?php echo $client_lang['add_feature_item']; ?></a> </div>&nbsp;
                    <!--<div class="add_btn_primary"> <a href="add_sellerbanner.php?add"><i class="fi fi-rr-seller"></i>&nbsp;Add Featured Seller</a> </div>-->
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
				  <th><?php echo $client_lang['visible_from']; ?></th>
				  <th><?php echo $client_lang['visible_till']; ?></th>
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
				   <td><img src="<?php echo file_exists('../seller/images/thumbs/'.$users_row['o_image']) ? '../seller/images/thumbs/'.$users_row['o_image'] : 'placeholder.jpg'; ?>" class="img-fluid img-thumbnail" alt="<?php echo $users_row['o_name']; ?>" style="width: 100px; height: auto;"><br></td>
		           <td><?php echo $users_row['o_date'].' at '.$users_row['o_stime'];?></td>   
				   <td><?php echo $users_row['o_edate'].' at '.$users_row['o_etime'];?></td> 
		           <td>
		          		<?php if($users_row['o_status']!="0"){?>
		              <a href="banners.php?status_deactive_id=<?php echo $users_row['o_id'];?>" title="Change Status"><span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span><?php echo $client_lang['visible']; ?></span></span></a>

		              <?php }else{?>
		              <a href="banners.php?status_active_id=<?php echo $users_row['o_id'];?>" title="Change Status"><span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span><?php echo $client_lang['hidden']; ?></span></span></a>
		              <?php }?>
              		</td>
                  <td>
                    <div class="button-container">
                     <?php if (strpos($users_row['o_link'], 's_id=') !== false): ?>
                        <a href="add_sellerbanner.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                    <?php elseif (strpos($users_row['o_link'], 'o_id=') !== false): ?>
                        <a href="add_itembanner.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                    <?php else: ?>
                        <a href="add_banners.php?o_id=<?php echo $users_row['o_id'];?>" class="btn btn-edit"><i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                    <?php endif; ?>
                  
                    <a href="banners.php?o_id=<?php echo $users_row['o_id'];?>" onclick="return confirm('<?php echo $client_lang['banner_delete_confirm']; ?>');" class="btn btn-delete"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a>
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