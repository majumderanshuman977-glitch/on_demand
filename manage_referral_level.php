<?php include('includes/header.php');

    include('includes/function.php');
	include('language/language.php'); 

 	require_once("thumbnail_images.class.php");
	 

	if(isset($_POST['submit']) and isset($_GET['add']))
	{		
	    
	        $checkLevelQry = "SELECT level FROM tbl_referral_bonus WHERE level = " . intval($_POST['level']);
            $checkLevelResult = mysqli_query($mysqli, $checkLevelQry);

			if (mysqli_num_rows($checkLevelResult) == 0) {
			    
            if ($_POST['level'] > $maxLevel) {
                $data = array(
                    'level' => $_POST['level'],
                    'referral_bonus' => $_POST['referral_bonus'],
                    'coin_purchase_bonus' => $_POST['coin_purchase_bonus'],
                );
        
                $qry = Insert('tbl_referral_bonus', $data);
        
                $_SESSION['msg'] = "10";
                header("location:settings.php#refer_settings");
                exit;
            }
        }
			else
			{
			    $_SESSION['msg'] = "26";
                header("Location:manage_referral_level.php?add");
                exit;
			}
    } 

	if(isset($_GET['id']))
	{
			 
			$user_qry="SELECT * FROM tbl_referral_bonus where id='".$_GET['id']."'";
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
if (isset($_POST['submit']) && isset($_POST['id'])) 

    {
    

            $data = array(
                'level'  =>  $_POST['level'],
                'referral_bonus'  =>  $_POST['referral_bonus'],
                'coin_purchase_bonus'  =>  $_POST['coin_purchase_bonus'],
            );

            $user_edit = Update('tbl_referral_bonus', $data, "WHERE id = '" . $_POST['id'] . "'");
    

    if ($user_edit > 0){
        $_SESSION['msg'] = "11";
        header("Location:manage_referral_level.php?id=" . $_POST['id']);
        exit;
    }
}


?>
 	

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['id'])){?>Edit<?php }else{?>Add<?php }?> Referral Level</div>
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
          <div class="card-body mrg_bottom"> 
            <form action="" name="addedituser" method="post" class="form form-horizontal" enctype="multipart/form-data" >
            	<input  type="hidden" name="id" value="<?php echo $_GET['id'];?>" />

              <div class="section">
                <div class="section-body">
                   
                  <div class="form-group">
                    <label class="col-md-3 control-label">Level :-</label>
                    <div class="col-md-6">
                      <input type="text" name="level" id="level" placeholder="eg. 1" title="enter referral level" value="<?php if(isset($_GET['id'])){echo $user_row['level'];}?>" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Referral Bonus:-</label>
                    <div class="col-md-6">
                      <input type="text" name="referral_bonus" id="referral_bonus" placeholder="eg. 5" title="enter referral bonus"  value="<?php if(isset($_GET['id'])){echo $user_row['referral_bonus'];}?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-md-3 control-label">Coin Purchase Bonus:-</label>
                      <div class="col-md-6">
                          <div class="input-group">
                        <input type="text" name="coin_purchase_bonus" id="coin_purchase_bonus" placeholder="eg. 5" title="enter coin purchase bonus"  value="<?php if(isset($_GET['id'])){echo $user_row['coin_purchase_bonus'];}?>" class="form-control">
                        <span class="input-group-addon">%</span>
                    </div>
                      </div>
                    </div>
                     
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save Referral Level</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   

<?php include('includes/footer.php');?>                  