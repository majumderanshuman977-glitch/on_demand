<?php include('includes/header.php'); 

    include('includes/function.php');
	include('language/language.php');  


	if(isset($_POST['user_search']))
	 {
		 
		
		$user_qry="SELECT * FROM tbl_offers WHERE tbl_offers.o_name like '%".addslashes($_POST['search_value'])."%' ORDER BY tbl_offers.o_id DESC";  
							 
		$users_result=mysqli_query($mysqli,$user_qry);
		
		 
	 }
	 else
	 {

							$tableName="tbl_kyc";		
							$targetpage = "kyc.php"; 	
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
							
					 	    //INSERT INTO `tbl_offers`(`o_id`, `o_name`, `o_image`, `o_desc`, `o_date`, `o_stime`, `o_etime`, `o_status`) 
			
						 $users_qry="SELECT * FROM tbl_kyc LEFT JOIN tbl_users ON tbl_kyc.kyc_id = tbl_users.id ORDER BY tbl_kyc.kyc_id DESC LIMIT $start, $limit";  
						 $users_result=mysqli_query($mysqli,$users_qry);
							
	 }
	if(isset($_GET['kyc_id']))
	{
		  
		 
		Delete('tbl_kyc','kyc_id='.$_GET['kyc_id'].'');
		
		$_SESSION['msg']="12";
		header( "Location:kyc.php");
		exit;
	}
	
	
?>

<head>
<title><?php echo $client_lang['manage_id_proof']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>

 <div class="row">
      <div class="col-xs-12">
        <div class="card mrg_bottom">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php echo $client_lang['manage_id_proof']; ?></div>
            </div>
            <div class="col-md-7 col-xs-12">              
                  <div class="search_list">
                    <div class="search_block">
                      <form  method="post" action="">
                        <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_id_proof']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
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
                    <th><?php echo $client_lang['user_name']; ?></th>						 
                    <th><?php echo $client_lang['user_email']; ?></th>
                    <th><?php echo $client_lang['document_type']; ?></th>
                    <th><?php echo $client_lang['verification_status']; ?></th>
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
                    <td><?php echo $users_row['name'];?></td>
                    <td><?php echo $users_row['email'];?></td> 
                    
                    <td>
                        <?php
                        switch ($users_row['id_type']) {
                            case 1:
                                echo $client_lang['driving_license'];
                                break;
                            case 2:
                                echo $client_lang['national_id'];
                                break;
                            case 3:
                                echo $client_lang['passport'];
                                break;
                            case 4:
                                echo $client_lang['other_document'];
                                break;
                        }
                        ?>
                    </td>
                
                    <td>
                        <?php
                        switch ($users_row['kyc_status']) {
                            case 0:
                                echo $client_lang['incomplete'];
                                break;
                            case 1:
                                echo $client_lang['pending'];
                                break;
                            case 2:
                                echo $client_lang['completed'];
                                break;
                            case 3:
                                echo $client_lang['rejected'];
                                break;
                            default:
                                echo 'Unknown Type';
                                break;
                        }
                        ?>
                    </td>
                    
              		<td>
              		    <div class="button-container">
              		        <a href="manage_kyc.php?kyc_id=<?php echo $users_row['kyc_id'];?>" class="btn btn-view"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['see_document']; ?></a>
              		        <a href="add_user.php?id=<?php echo $users_row['u_id'];?>" class="btn btn-edit"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['view_user']; ?></a>
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