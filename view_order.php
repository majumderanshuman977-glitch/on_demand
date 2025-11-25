<?php include('includes/header.php');

    include('includes/function.php');
	include('language/language.php'); 

 	require_once("thumbnail_images.class.php");
 	
 	$o_id = sanitize ($_GET['o_id']);

	if(isset($o_id))
	{
	
			
			 $user_qry="SELECT * FROM tbl_order 
		    left join tbl_users on tbl_users.id = tbl_order.u_id
		    LEFT JOIN tbl_items ON tbl_items.item_id = tbl_order.offer_id 
		  	where tbl_order.o_id='$o_id' ";  
		  
		  
			$user_result=mysqli_query($mysqli,$user_qry);
			$user_row=mysqli_fetch_assoc($user_result);
		
	}
	
	if(isset($_POST['submit']) and isset($o_id))
	{
	    $order_status = sanitize ($_POST['order_status']);

			$data = array(
                'order_status'  =>  $order_status,
			);
			
		$user_edit=Update('tbl_order', $data, "WHERE o_id = '$o_id'");
	
			if ($user_edit > 0){
				
				$_SESSION['msg']="order_updated";
				header("Location:view_order.php?o_id=$o_id");
				exit;
			} 	
 }
    
    
$currency_qry="SELECT currency FROM tbl_settings";
$currency_result=mysqli_query($mysqli,$currency_qry);
$currency_row=mysqli_fetch_assoc($currency_result);
$currency=$currency_row['currency'];

$discount = ($user_row['price'] - $user_row['pay_amount']);
	
?>


<style>







#table {
  width:100%;
}
#table, #th, #td {
  border: 1px solid #dfd7ca;
  border-collapse: collapse;
}
#th, #td {
  padding: 15px;
  text-align: left;
}
#table#t01 #tr:nth-child(even) {
  background-color: #eee;
}
#table#t01 #tr:nth-child(odd) {
 background-color: #fff;
}
#table#t01 #th {
  background-color: white;
  color: black;
}


table {
  width:100%;
}
table, th, td {
  border: 0px solid #dfd7ca;
  border-collapse: collapse;
      margin-bottom: 10px;
}
th, td {
  padding: 0px;
  text-align: left;
}


.line {
  width:40%;
  
  margin-left: 600px;
}

 th, td {
  border: 0px solid #dfd7ca;
  border-collapse: collapse;
      margin-bottom: 10px;
}




</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<head>
<title><?php echo $client_lang['order_details']; ?></title>
</head>

 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
            <div class="page_title"><?php echo $client_lang['order_details']; ?></div>
           
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
            	<input  type="hidden" name="o_id" value="<?php echo $o_id;?>" />

                   
                    
                    
                    
   
                    <div class="col-md-4" >
                    <center><h5> <b><?php echo $client_lang['user_details']; ?></b> </h5></center>
                       <hr />
                    <div class="form-group">
                        <label class="col-md-12 control-label"><b><?php echo $client_lang['user_name']; ?> :- </b><?php if(isset($o_id)){echo $user_row['name'];}?> </label>
                     </div>
                  
                    <div class="form-group">
                      <label class="col-md-12 control-label"><b><?php echo $client_lang['user_email']; ?> :-  </b> <?php if(isset($o_id)){echo $user_row['email'];}?></label>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12 control-label"><b><?php echo $client_lang['user_mobile']; ?>:-  </b> <?php if(isset($o_id)){echo '+'.$user_row['country_code'].' '.$user_row['phone'];}?></label>
                    </div>
                  </div>
                
       <div class="col-md-4" >
          
                       <center><h5> <b><?php echo $client_lang['order_details']; ?></b> </h5></center>
                       <hr />
                         <div class="form-group">
                      <label class="col-md-12 control-label"><b><?php echo $client_lang['order_id']; ?> :-  </b><?php if(isset($o_id)){echo '#'.$user_row['o_id'];}?></label>
                    </div>
                  
                    <div class="form-group">
                      <label class="col-md-12 control-label"><b><?php echo $client_lang['order_date']; ?> :-  </b> <?php if(isset($o_id)){echo $user_row['order_date'];}?></label>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12 control-label"><b><?php echo $client_lang['shipping_address']; ?> :-  </b> <?php if(isset($o_id)){echo $user_row['o_address'];}?></label>
                    </div>
                
       </div>
                
                
 
 




 <table id='table'>   

             <div class="col-md-12 mrg-top">
            <table id="t01" class="table table-striped table-bordered table-hover">

                 <tr id='tr'>
                     <th id='th'><?php echo $client_lang['item_name']; ?></th>
                     <th id='th'><?php echo $client_lang['item_image']; ?></th>
                     <th id='th'><?php echo $client_lang['item_price']; ?></th>
                
                </tr>
                <tr>
                    <td>
                     <?php if(isset($o_id)){echo $user_row['o_name'];}?>
                    </td>
                    <td><span class="category_img"><img src="../seller/images/thumbs/<?php echo $user_row['o_image'];?>" /></span>
                  </td>
                    <td>
                     <?php if(isset($o_id)){echo $currency.$user_row['price'];}?>
                    </td>
                </tr>

      

  
   </div>
   </table>
   </table>

    <div class="col-md-12 mrg-top">
            <table style='border: 1px solid #ddd; border-collapse: collapse;' class="table line">
       

                    <tr>
                 <td> 
                   <b> <?php echo $client_lang['total_amount']; ?>:</b>
             
                    </td>
                    
                <td> <?php if(isset($o_id)){echo $currency.$user_row['price'].'/-';}?>
               
                    </td>


                    </tr>
                    
                     <tr>
                   <td> 
                   <b>  <?php echo $client_lang['order_discount']; ?> :	</b>  
                   </td>
                <td> 
               <?php if(isset($o_id)){echo $currency.$discount.'/-';}?>  
                                    
                    </td>
                        

                    
                     </tr>

                    <tr>
                   <td style="color: #337ab7;"> 
                   <b>  <?php echo $client_lang['amount_payable']; ?>:</b> 
                   </td>
                <td style="color: #337ab7;"> <?php if(isset($o_id)){echo '<b>'.$currency.$user_row['pay_amount'].'/-</b>';}?> 
                
                    </td>


                    
                     </tr>

             </table>
        </div>
        <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['order_status']; ?>:-</label>
                      <div class="col-md-6">                       
                        <select name="order_status" id="order_status" style="width:280px; height:25px;" class="select2" required>
                            <option value="1" <?php if($user_row['order_status']=='1'){?>selected<?php }?>><?php echo $client_lang['order_received']; ?></option>
                            <option value="2" <?php if($user_row['order_status']=='2'){?>selected<?php }?>><?php echo $client_lang['order_processed']; ?></option>
                            <option value="3" <?php if($user_row['order_status']=='3'){?>selected<?php }?>><?php echo $client_lang['order_shipped']; ?></option>
                            <option value="4" <?php if($user_row['order_status']=='4'){?>selected<?php }?>><?php echo $client_lang['order_delivered']; ?></option>
                            <option value="5" <?php if($user_row['order_status']=='5'){?>selected<?php }?>><?php echo $client_lang['order_rejected']; ?></option>
                          
                        </select>
                      </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php echo $client_lang['update_order']; ?></button>
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