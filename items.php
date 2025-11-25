<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  

	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_items WHERE tbl_items.o_name like '%".addslashes($_POST['search_value'])."%'";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_items";		
							$targetpage = "items.php"; 	
							$limit = 15; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName WHERE item_status = 1";
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
							

						 $users_qry="SELECT * FROM tbl_items
						 WHERE item_status = 1
						 ORDER BY tbl_items.item_id DESC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['item_id']))
	{
		  
		 
		Delete('tbl_items','item_id='.$_GET['item_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:items.php");
		exit;
	}
	
	
?>
<head>
<title><?php echo $client_lang['manage_items']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_items']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_item']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_item.php?add"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo $client_lang['add_item']; ?></a> </div>
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
                    <th><?php echo $client_lang['item_name']; ?></th>
				  <th><?php echo $client_lang['item_image']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{

				?>
                <tr>
		           <td><img src="<?php echo file_exists('../seller/images/'.$users_row['o_image']) ? '../seller/images/'.$users_row['o_image'] : 'placeholder.jpg'; ?>" class="img-fluid img-thumbnail" alt="<?php echo $users_row['o_name']; ?>" style="width: 100px; height: auto;"></td>
		           <td><?php echo $users_row['o_name'];?></td>   
		        <td>
                   <a href="add_item.php?item_id=<?php echo $users_row['item_id'];?>" class="btn btn-edit" title="Edit Item">&nbsp;<i class="fa fa-pencil"></i>&nbsp;<?php echo $client_lang['edit']; ?></a>
                   <a href="items.php?item_id=<?php echo $users_row['item_id'];?>" onclick="return confirm('<?php echo $client_lang['delete_item']; ?>');"  class="btn btn-delete" title="Delete Item">&nbsp;<i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete']; ?></a>
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