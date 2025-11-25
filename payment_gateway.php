<?php include("includes/header.php");

  require("includes/function.php");
  require("language/language.php");
   
  
  $qry="SELECT * FROM tbl_settings where id='1'";
  $result=mysqli_query($mysqli,$qry);
  $settings_row=mysqli_fetch_assoc($result);

 

  if(isset($_POST['submit']))
  {


    $img_res=mysqli_query($mysqli,"SELECT * FROM tbl_settings WHERE id='1'");
    $img_row=mysqli_fetch_assoc($img_res);
    
    {
  
                $data = array(
              'mpesa_key' =>  $_POST['mpesa_key'],
              'mpesa_code' =>  $_POST['mpesa_code'],
              
              'paypal_id' =>  $_POST['paypal_id'],
              'paypal_secret' =>  $_POST['paypal_secret'],
              'paypal_currency' =>  $_POST['paypal_currency'],
              
              'cashfree_appid' =>  $_POST['cashfree_appid'],
              'cashfree_secret' =>  $_POST['cashfree_secret'],
              
              'flutterwave_public' =>  $_POST['flutterwave_public'],
              'flutterwave_encryption' =>  $_POST['flutterwave_encryption'],
              'flutterwavecurrency' =>  $_POST['flutterwavecurrency'],
              
              'razorpay_key' =>  $_POST['razorpay_key'],
              'stripe_key' =>  $_POST['stripe_key']

                  );

    } 

     $settings_edit=Update('tbl_settings', $data, "WHERE id = '1'");
 
  

        $_SESSION['msg']="11";
        header( "Location:payment_gateway.php");
        exit;
 
  }

?>
 
   <div class="row">
      <div class="col-md-12">
        <div class="card">
      <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title">Payment Gateway</div>
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
          
           <div class="tab-content">
              
              <div role="tabpanel" class="tab-pane active" id="app_settings">   
                <form action="" name="settings_from" method="post" class="form form-horizontal" enctype="multipart/form-data">
              <div class="section">
                <div class="section-body">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Mpesa Key:-</label>
                    <div class="col-md-6">
                      <input type="text" name="mpesa_key" id="mpesa_key" value="<?php echo $settings_row['mpesa_key'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Mpesa Code:-</label>
                    <div class="col-md-6">
                      <input type="text" name="mpesa_code" id="mpesa_code" value="<?php echo $settings_row['mpesa_code'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Paypal Id:-</label>
                    <div class="col-md-6">
                      <input type="text" name="paypal_id" id="paypal_id" value="<?php echo $settings_row['paypal_id'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Paypal Secret:-</label>
                    <div class="col-md-6">
                      <input type="text" name="paypal_secret" id="paypal_secret" value="<?php echo $settings_row['paypal_secret'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                   <div class="form-group">
                    <label class="col-md-3 control-label">PayPal Currency:-</label>
                    <div class="col-md-6">
                      <input type="text" name="paypal_currency" id="paypal_currency" value="<?php echo $settings_row['paypal_currency'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                   <hr>
                   
                   <div class="form-group">
                    <label class="col-md-3 control-label">Cashfree App Id:-</label>
                    <div class="col-md-6">
                      <input type="text" name="cashfree_appid" id="cashfree_appid" value="<?php echo $settings_row['cashfree_appid'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Cashfree Secret:-</label>
                    <div class="col-md-6">
                      <input type="text" name="cashfree_secret" id="cashfree_secret" value="<?php echo $settings_row['cashfree_secret'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Flutterwave Public:-</label>
                    <div class="col-md-6">
                      <input type="text" name="flutterwave_public" id="flutterwave_public" value="<?php echo $settings_row['flutterwave_public'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Flutterwave Encryption:-</label>
                    <div class="col-md-6">
                      <input type="text" name="flutterwave_encryption" id="flutterwave_encryption" value="<?php echo $settings_row['flutterwave_encryption'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Flutterwave Currency:-</label>
                    <div class="col-md-6">
                      <input type="text" name="flutterwavecurrency" id="flutterwavecurrency" value="<?php echo $settings_row['flutterwavecurrency'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Razorpay Key:-</label>
                    <div class="col-md-6">
                      <input type="text" name="razorpay_key" id="razorpay_key" value="<?php echo $settings_row['razorpay_key'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Stripe Key:-</label>
                    <div class="col-md-6">
                      <input type="text" name="stripe_key" id="stripe_key" value="<?php echo $settings_row['stripe_key'];?>" class="form-control" required>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">OpenPix Keys:-</label>
                    <div class="col-md-6">
                      <p>OpenPix Key's can be updated from frontend <a href="https://wa.me/919339932830">(Contact us for Help)</a></p>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Instamojo Keys:-</label>
                    <div class="col-md-6">
                      <p>Instamojo Key's can be updated from frontend <a href="https://wa.me/919339932830">(Contact us for Help)</a></p>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">PayStack Keys:-</label>
                    <div class="col-md-6">
                      <p>PayStack Key's can be updated from frontend <a href="https://wa.me/919339932830">(Contact us for Help)</a></p>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label">Midtrans Keys:-</label>
                    <div class="col-md-6">
                      <p>Midtrans Key's can be updated from frontend <a href="https://wa.me/919339932830">(Contact us for Help)</a></p>
                    </div>
                  </div>
                  
                  
                  <div class="form-group">&nbsp;</div>                 

                  <div class="form-group">&nbsp;</div>
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                  </div>
                </div>
              </div>
               </form>
              </div>

            </div>
            </div>                        
            </div>
          </div>
                </form>
              </div>


            </div>   

          </div>
        </div>
      </div>
    </div>

        
<?php include("includes/footer.php");?>       
