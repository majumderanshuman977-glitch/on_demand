<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_city WHERE tbl_city.city_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_city.city_id ASC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_city";		
							$targetpage = "city.php"; 	
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
							
			
						 $users_qry="SELECT * FROM tbl_city
						 ORDER BY tbl_city.city_id ASC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['city_id']))
	{
		  
		 
		Delete('tbl_city','city_id='.$_GET['city_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:city.php");
		exit;
	}
	
	//Active and Deactive status
	if(isset($_GET['status_deactive_id']))
	{
		$data = array('city_status'  =>  '0');
		
		$edit_status=Update('tbl_city', $data, "WHERE city_id = '".$_GET['status_deactive_id']."'");
		
		 $_SESSION['msg']="14";
		 header( "Location:city.php");
		 exit;
	}
	if(isset($_GET['status_active_id']))
	{
		$data = array('city_status'  =>  '1');
		
		$edit_status=Update('tbl_city', $data, "WHERE city_id = '".$_GET['status_active_id']."'");
		
		$_SESSION['msg']="13";
		 header( "Location:city.php");
		 exit;
	}
	
	
?>

<head>
<title><?php echo $client_lang['city']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['city']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_city']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_city.php?add"><i class="fa fa-plus"></i>&nbsp;<?php echo $client_lang['add_new_city']; ?></a> </div>
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
                  <th><?php echo $client_lang['city_name']; ?></th>						 
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
                    <td style="background-color: white;">
                        <img src="<?php echo file_exists('../seller/images/'.$users_row['city_image']) ? '../seller/images/'.$users_row['city_image'] : 'placeholder.jpg'; ?>" class="img-fluid img-thumbnail" alt="<?php echo $users_row['city_name']; ?>" style="width: 100px; height: auto;">
                    </td>

                   <td><?php echo $users_row['city_name'];?></td>
                   <td>
                       <div class="button-container">
                           <?php if ($users_row['city_status'] == 0) { ?>
                               <a href="city.php?status_active_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['show_city']; ?>');" class="btn btn-danger" title="Show Region">
                                   <i class="fa fa-eye-slash"></i>&nbsp;<?php echo $client_lang['hidden']; ?>
                               </a>
                           <?php } elseif ($users_row['city_status'] == 1) { ?>
                               <a href="city.php?status_deactive_id=<?php echo $users_row['c_id'];?>" onclick="return confirm('<?php echo $client_lang['hide_city']; ?>');" class="btn btn-success" title="Hide Region">
                                   <i class="fa fa-check-square"></i>&nbsp;<?php echo $client_lang['visible']; ?>
                               </a>
                           <?php } ?>
                            <a href="add_city.php?city_id=<?php echo $users_row['city_id'];?>" class="btn btn-edit" title="Edit Region"><i class="fi fi-br-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                            <!--<a href="city.php?city_id=<?php echo $users_row['city_id'];?>" onclick="return confirm('<?php echo $client_lang['delete_city']; ?>');" class="btn btn-delete" title="Delete Region"><i class="fi fi-br-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a>-->
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