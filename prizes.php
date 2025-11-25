<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	
		if(isset($_GET['o_id']))
	{
			 
			$user_qry="SELECT * FROM tbl_prizes LEFT JOIN tbl_items ON tbl_items.item_id = tbl_prizes.item_id where o_id='".$_GET['o_id']."'";
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
	
	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_prizes LEFT JOIN tbl_items ON tbl_items.item_id = tbl_prizes.item_id WHERE tbl_items.o_name like '%".addslashes($_POST['search_value'])."%' AND o_id='".$_GET['o_id']."'";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_prizes";		
							$targetpage = "prizes.php"; 	
							$limit = 15; 
							
							$query = "SELECT COUNT(*) as num FROM $tableName where o_id='".$_GET['o_id']."'";
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
							

						 $users_qry="SELECT * FROM tbl_prizes
						 LEFT JOIN tbl_items ON tbl_items.item_id = tbl_prizes.item_id
                         WHERE o_id='".$_GET['o_id']."'
						 ORDER BY tbl_prizes.prize_id ASC LIMIT $start, $limit";  
							 
							$users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['prize_id']))
	{
		  
		 
		Delete('tbl_prizes','prize_id='.$_GET['prize_id'].'');
		
		$_SESSION['msg']="delete_prize";
		header( "Location:prizes.php");
		exit;
	}

?>
<head>
<title><?php echo $client_lang['manage_prizes']; ?></title>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_prizes']; ?>&nbsp;<i class="fi fi-rr-trophy-star"></i></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_prize']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
                        <button type="submit" name="user_search" class="btn-search"><i class="fa fa-search"></i></button>
                      </form>  
                    </div>
                    <div class="add_btn_primary"> <a href="add_prize.php?add&o_id=<?php echo $_GET['o_id'];?>"><?php echo $client_lang['add_prize']; ?></a> </div>
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
                    <th><?php echo $client_lang['prize_rank']; ?></th>
				  <th><?php echo $client_lang['prize_image']; ?></th>
				  <th><?php echo $client_lang['prize_name']; ?></th>
                  <th class="cat_action_list"><?php echo $client_lang['action']; ?></th>
                </tr>
              </thead>
              <tbody>
              	<?php
						$i=0;
						while($users_row=mysqli_fetch_array($users_result))
						{
						    
						 $rank_display = ($users_row['rank_start'] == $users_row['rank_end']) ? $users_row['rank_start'] : $users_row['rank_start'] . '-' . $users_row['rank_end'];

				?>
                <tr>
                   <td><?php echo $rank_display; ?></td>  
				   <td style="background-color: white;" width="20%"><?php echo "<img src='../seller/images/" . $users_row['o_image'] . "'>";?></td>
		           <td><?php echo $users_row['o_name'];?></td>   
                    <td><a href="add_prize.php?prize_id=<?php echo $users_row['prize_id'];?>" class="btn btn-primary"><?php echo $client_lang['edit_prize']; ?></a></td>
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