<?php 
include('includes/header.php');
include('includes/function.php');
include('language/language.php'); 

// Fetch TimeZone from tbl_settings
$settings_query = "SELECT timezone FROM tbl_settings";
$settings_result = mysqli_query($mysqli, $settings_query);
$settings_row = mysqli_fetch_assoc($settings_result);

date_default_timezone_set($settings_row['timezone']);
$time = date('H:i:s');
$date = date('Y-m-d');

if (isset($_POST['submit']) && isset($_GET['add'])) {
    // Sanitize input values
    $c_id = sanitize($_POST['c_id']);
    $city_id = sanitize($_POST['city_id']);
    $item_id = sanitize($_POST['item_id']);
    $o_date = sanitize($date);
    $o_stime = sanitize($time);
    $o_amount = sanitize($_POST['o_amount']);
    $o_qty = sanitize($_POST['o_qty']);

    // Fetch item price from tbl_items
    $items_qry = "SELECT * FROM tbl_items WHERE item_id=$item_id";
    $items_result = mysqli_query($mysqli, $items_qry);
    $items_row = mysqli_fetch_assoc($items_result);
    $item_price = $items_row['price'];

    // Construct data array
    $data = [
        'id' => 1,
        'o_type' => 9,
        'c_id' => $c_id,
        'city_id' => $city_id,
        'item_id' => $item_id,
        'o_date' => $o_date,
        'o_stime' => $o_stime,
        'o_amount' => $o_amount,
        'o_price' => $item_price,
        'o_edate' => '2099-01-01',
        'o_etime' => '12:30:00',
        'o_qty' => $o_qty,
        'o_status' => 1
    ];

    // Insert data into tbl_offers
    $qry = Insert('tbl_offers', $data);

    $_SESSION['msg'] = "28";
    header("location:shop.php");
    exit;
}

if (isset($_GET['o_id'])) {
    $o_id = sanitize($_GET['o_id']);
    $user_qry = "SELECT * FROM tbl_offers WHERE o_id='$o_id'";
    $user_result = mysqli_query($mysqli, $user_qry);
    $user_row = mysqli_fetch_assoc($user_result);
}

if (isset($_POST['submit']) && isset($_POST['o_id'])) {
    // Sanitize input values
    $c_id = sanitize($_POST['c_id']);
    $city_id = sanitize($_POST['city_id']);
    $item_id = sanitize($_POST['item_id']);
    $o_date = sanitize($date);
    $o_stime = sanitize($time);
    $o_amount = sanitize($_POST['o_amount']);
    $o_qty = sanitize($_POST['o_qty']);

    // Fetch item price from tbl_items
    $items_qry = "SELECT * FROM tbl_items WHERE item_id=$item_id";
    $items_result = mysqli_query($mysqli, $items_qry);
    $items_row = mysqli_fetch_assoc($items_result);
    $item_price = $items_row['price'];

    // Construct data array for update
    $data = [
        'c_id' => $c_id,
        'city_id' => $city_id,
        'item_id' => $item_id,
        'o_date' => $o_date,
        'o_stime' => $o_stime,
        'o_amount' => $o_amount,
        'o_price' => $item_price,
        'o_edate' => '2099-01-01',
        'o_etime' => '12:30:00',
        'o_qty' => $o_qty,
    ];

    // Update data in tbl_offers
    $user_edit = Update('tbl_offers', $data, "WHERE o_id = '" . sanitize($_POST['o_id']) . "'");

    if ($user_edit > 0) {
        $_SESSION['msg'] = "27";
        header("Location:add_shop.php?o_id=" . sanitize($_POST['o_id']));
        exit;
    }
}

$category_qry1 = "SELECT c_name FROM tbl_cat WHERE c_id != 1 AND c_view = 3 ORDER BY RAND() LIMIT 1";
$category_result1 = mysqli_query($mysqli, $category_qry1);
$category_row1 = mysqli_fetch_assoc($category_result1);

$cat = sanitize($category_row1['c_name']);

$settings_qry = "SELECT * FROM tbl_settings";
$settings_result = mysqli_query($mysqli, $settings_qry);
$settings_row = mysqli_fetch_assoc($settings_result);

$currency = sanitize($settings_row['currency']);
?>

 	
<style>
    .block_wallpaper img {
  width: 200px;
  height: 200px;
   /* Keeps aspect ratio while fitting image */
}

.fileupload_img.replaced .database-image {
  display: none; /* Hide existing image when replaced class is present */
}
</style>

<head>
<title><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['shop']; ?></title>
</head>
<style>
    .safe-mode-title {
        display: block;
        text-align: center;
        font-size: 1em;
        font-weight: bold;
        color: #2c3e50; /* A dark color for better contrast */
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .safe-mode-description {
        text-align: center;
        font-size: 1em;
        line-height: 1.5;
        margin: 0 20px 20px;
        color: #34495e; /* Slightly lighter color for the description */
    }
</style>
 <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="page_title_block">
            <div class="col-md-5 col-xs-12">
              <div class="page_title"><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['edit']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['shop']; ?></div>
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
            	<input  type="hidden" name="o_id" value="<?php echo $_GET['o_id'];?>" />

              <div class="section">
                <div class="section-body">
                    
                   
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['item']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['shop_item_help']; ?></p></label>
                       <div class="col-md-6">
                            <select name="item_id" id="item_id" style="width:280px; height:25px;" class="select2" required>
                              <option value=""><?php echo $client_lang['select_item']; ?></option>
                               <?php
                                   $items_qry = "SELECT * FROM tbl_items WHERE item_status =1 ORDER BY item_id DESC";
                                   $items_result = mysqli_query($mysqli, $items_qry);
                                   while($items_row = mysqli_fetch_assoc($items_result)) {
                                   $selected = ($items_row['item_id'] == $user_row['item_id']) ? 'selected' : '';
                                   echo '<option value="'.$items_row['item_id'].'" '.$selected.'>'.$items_row['o_name'].' ('.$client_lang['worth'].' '.$currency.$items_row['price'].')'.'</option>';
                                }
                                ?>
                            </select>
                       </div>
                  </div>
                  
                  <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $client_lang['category']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['auction_category_help']; ?></p></label>
                     <div class="col-md-6">
                          <select name="c_id" id="c_id" style="width:280px; height:25px;" class="select2" required>
                            <option value=""><?php echo $client_lang['select_category']; ?></option>
                             <?php
                                 $category_qry = "SELECT c_id, c_name, c_image FROM tbl_cat WHERE c_id !=1 AND c_view = 3";
                                 $category_result = mysqli_query($mysqli, $category_qry);
                                 while($category_row = mysqli_fetch_assoc($category_result)) {
                                 $selected = ($category_row['c_id'] == $user_row['c_id']) ? 'selected' : '';
                                 echo '<option value="'.$category_row['c_id'].'" '.$selected.'>'.$category_row['c_name'].'</option>';
                              }
                              ?>
                          </select>
                     </div>
                </div>
                
                <div class="form-group">
                  <label class="col-md-3 control-label"><?php echo $client_lang['city']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['city_help']; ?></p></label>
                     <div class="col-md-6">
                          <select name="city_id" id="city_id" style="width:280px; height:25px;" class="select2" required>
                            <option value=""><?php echo $client_lang['select_city']; ?></option>
                             <?php
                                 $city_qry = "SELECT city_id, city_name, city_image FROM tbl_city";
                                 $city_result = mysqli_query($mysqli, $city_qry);
                                 while($city_row = mysqli_fetch_assoc($city_result)) {
                                 $selected = ($city_row['city_id'] == $user_row['city_id']) ? 'selected' : '';
                                 echo '<option value="'.$city_row['city_id'].'" '.$selected.'>'.$city_row['city_name'].'</option>';
                              }
                              ?>
                          </select>
                     </div>
                </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['buying_price']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['buying_price_help']; ?></p></label>
                    <div class="col-md-6">
                         <div class="input-group">
                        <span class="input-group-addon"><?php
                                $currency_qry = "SELECT currency FROM tbl_settings";
                                $currency_result = mysqli_query($mysqli, $currency_qry);
                                $currency_row = mysqli_fetch_assoc($currency_result);
                                echo $currency_row['currency'];?>
                        </span>
                      <input type="text" name="o_amount" title="amount users need to pay to buy it" id="o_amount" placeholder="eg. 1"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_amount'];}?>" class="form-control">
                      </div>
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo $client_lang['quantity']; ?>:-<br><p class="control-label-help"><?php echo $client_lang['quantity_help']; ?></p></label>
                    <div class="col-md-6">
                      <input type="text" name="o_qty" title="total quantity" id="o_qty" placeholder="eg. 100"  value="<?php if(isset($_GET['o_id'])){echo $user_row['o_qty'];}?>" class="form-control">
                      </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                      <button type="submit" name="submit" class="btn btn-primary"><?php if(isset($_GET['o_id'])){?><?php echo $client_lang['update']; ?><?php }else{?><?php echo $client_lang['add']; ?><?php }?>&nbsp;<?php echo $client_lang['shop']; ?></button>
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