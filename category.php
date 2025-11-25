<?php 
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');  


if (isset($_POST['user_search'])) {
    $search_value = sanitize($_POST['search_value']);
    $user_qry = "SELECT * FROM tbl_cat WHERE tbl_cat.c_name LIKE ? ORDER BY tbl_cat.c_id ASC";
    $stmt = $mysqli->prepare($user_qry);
    $search_value = "%$search_value%";
    $stmt->bind_param("s", $search_value);
    $stmt->execute();
    $users_result = $stmt->get_result();
} else {
    $tableName = "tbl_cat";        
    $targetpage = "category.php";     
    $limit = 15; 

    $query = "SELECT COUNT(*) as num FROM $tableName";
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

    $users_qry = "SELECT * FROM tbl_cat WHERE c_id != 1 ORDER BY tbl_cat.c_id ASC LIMIT ?, ?";
    $stmt = $mysqli->prepare($users_qry);
    $stmt->bind_param("ii", $start, $limit);
    $stmt->execute();
    $users_result = $stmt->get_result();
}

if (isset($_GET['c_id'])) {
    $c_id = sanitize($_GET['c_id']);
    Delete('tbl_cat', 'c_id='.$c_id);
    $_SESSION['msg'] = "category_delete";
    header("Location:category.php");
    exit;
}

// Active and Deactive status
if (isset($_GET['status_deactive_id'])) {
    $status_deactive_id = sanitize($_GET['status_deactive_id']);
    $data = array('c_status' => '0');
    $edit_status = Update('tbl_cat', $data, "WHERE c_id = '".$status_deactive_id."'");
    $_SESSION['msg'] = "category_disable";
    header("Location:category.php");
    exit;
}
if (isset($_GET['status_active_id'])) {
    $status_active_id = sanitize($_GET['status_active_id']);
    $data = array('c_status' => '1');
    $edit_status = Update('tbl_cat', $data, "WHERE c_id = '".$status_active_id."'");
    $_SESSION['msg'] = "category_visible";
    header("Location:category.php");
    exit;
}
?>

<head>
<title><?php echo $client_lang['manage_category']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_category']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="Search Category" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_category.php?add"><i class="fi fi-rr-plus"></i>&nbsp;&nbsp;<?php echo $client_lang['add_category']; ?></a> </div>
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
                  <th><?php echo $client_lang['category_name']; ?></th>						 
				  <th><?php echo $client_lang['type']; ?></th>
				  <th><?php echo $client_lang['data']; ?></th>
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
                   <td><img src="<?php echo file_exists('../seller/images/'.$users_row['c_image']) ? '../seller/images/'.$users_row['c_image'] : 'placeholder.jpg'; ?>" class="img-fluid img-thumbnail" alt="<?php echo $users_row['o_name']; ?>" style="width: 100px; height: auto;"></td>
                   <td><?php echo $users_row['c_name'];?></td>
		           <td>
                       <?php if($users_row['c_view'] == "1"){ ?>
                           <i class="fa fa-gavel" aria-hidden="true"></i><span>&nbsp;<?php echo $client_lang['auction']; ?></span>
                       <?php } else if($users_row['c_view'] == "2"){ ?>
                           <i class="fa fa-ticket" aria-hidden="true"></i><span>&nbsp;<?php echo $client_lang['lottery']; ?></span>
                       <?php } else if($users_row['c_view'] == "3"){ ?>
                           <i class="fa fa-shop" aria-hidden="true"></i><span>&nbsp;<?php echo $client_lang['shop']; ?></span>
                       <?php } ?>
                   </td>
		               <td><?php
                            $querytime = "SELECT COUNT(*) as count FROM tbl_offers WHERE c_id=".$users_row['c_id'];
                            $resulttime = mysqli_query($mysqli, $querytime);
                            $rowtime = mysqli_fetch_assoc($resulttime);
                            $quantity = $rowtime['count'];
                        ?> <?php echo $quantity.' Products';?> <a href="view_category_products.php?c_id=<?php echo $users_row['c_id'];?>" class="btn blue-btn" title="View Products"><i class="fi fi-rs-eye"></i></a>
                        </td>
                        <td>
                            <div class="button-container">
                                <?php if ($users_row['c_status'] == 0) { ?>
                                    <a href="category.php?status_active_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['show_category']; ?>');" class="btn btn-danger" title="Show Category">
                                        <i class="fa fa-eye-slash"></i>&nbsp;<?php echo $client_lang['hidden']; ?>
                                    </a>
                                <?php } elseif ($users_row['c_status'] == 1) { ?>
                                    <a href="category.php?status_deactive_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['hide_category']; ?>');" class="btn btn-success" title="Hide Category">
                                        <i class="fa fa-check-square"></i>&nbsp;<?php echo $client_lang['visible']; ?>
                                    </a>
                                <?php } ?>
                                <a href="add_category.php?c_id=<?php echo $users_row['c_id'];?>" class="btn btn-edit" title="<?php echo $client_lang['edit_category']; ?>">&nbsp;<i class="fi fi-br-pencil"></i>&nbsp;<?php echo $client_lang['edit_category']; ?>&nbsp;</a>
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