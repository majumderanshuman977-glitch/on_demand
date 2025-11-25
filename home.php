<?php include("includes/header.php");
      include("assets/js/app.php");
      include("language/language.php");

$qry_cat="SELECT COUNT(*) as num FROM tbl_users";
$total_category= mysqli_fetch_array(mysqli_query($mysqli,$qry_cat));
$total_category = $total_category['num'];

$qry_sub_cat="SELECT SUM(tbl_coin_list.c_amount) AS num FROM tbl_wallet_passbook JOIN tbl_coin_list ON tbl_wallet_passbook.wp_package_id = tbl_coin_list.c_id";
$total_sub_cat = mysqli_fetch_array(mysqli_query($mysqli,$qry_sub_cat));
$total_sub_cat = $total_sub_cat['num'];

$qry_sub_cat1="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type IN ('4','5')";
$total_sub_cat1 = mysqli_fetch_array(mysqli_query($mysqli,$qry_sub_cat1));
$total_sub_cat1 = $total_sub_cat1['num'];

$qry_redeem="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type = '3'";
$total_redeem = mysqli_fetch_array(mysqli_query($mysqli,$qry_redeem));
$total_redeem = $total_redeem['num'];

$qry_shop="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type = '9'";
$total_shop = mysqli_fetch_array(mysqli_query($mysqli,$qry_shop));
$total_shop = $total_shop['num'];

$qry_auction="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type IN (1,2,7,8)";
$total_auction = mysqli_fetch_array(mysqli_query($mysqli,$qry_auction));
$total_auction = $total_auction['num'];

$qry_auction2="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type = '2'";
$total_auction2 = mysqli_fetch_array(mysqli_query($mysqli,$qry_auction2));
$total_auction2 = $total_auction2['num'];

$qry_auction7="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type = '7'";
$total_auction7 = mysqli_fetch_array(mysqli_query($mysqli,$qry_auction7));
$total_auction7 = $total_auction7['num'];

$qry_auction8="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type = '8'";
$total_auction8 = mysqli_fetch_array(mysqli_query($mysqli,$qry_auction8));
$total_auction8 = $total_auction8['num'];


$qry_sub_sub_cat="SELECT COUNT(*) as num FROM tbl_bid";
$total_sub_sub_cat = mysqli_fetch_array(mysqli_query($mysqli,$qry_sub_sub_cat));
$total_sub_sub_cat = $total_sub_sub_cat['num'];

$qry_pdf = "SELECT ROUND(SUM(money)) AS num FROM tbl_transaction";
$total_pdf_result = mysqli_query($mysqli, $qry_pdf);
$total_pdf_row = mysqli_fetch_assoc($total_pdf_result);
$total_pdf = $total_pdf_row['num'];
 
$qry_network="SELECT COUNT(*) as num FROM tbl_network";
$total_network = mysqli_fetch_array(mysqli_query($mysqli,$qry_network));
$total_network = $total_network['num'];

$qry_vendor="SELECT COUNT(*) as num FROM tbl_vendor";
$total_vendor = mysqli_fetch_array(mysqli_query($mysqli,$qry_vendor));
$total_vendor = $total_vendor['num'];

$qry_orders="SELECT COUNT(*) as num FROM tbl_order";
$total_orders = mysqli_fetch_array(mysqli_query($mysqli,$qry_orders));
$total_orders = $total_orders['num'];

$qry_banner="SELECT COUNT(*) as num FROM tbl_offers  WHERE o_type = '6'";
$total_banner = mysqli_fetch_array(mysqli_query($mysqli,$qry_banner));
$total_banner = $total_banner['num'];

$qry_package="SELECT COUNT(*) as num FROM tbl_coin_list";
$total_package = mysqli_fetch_array(mysqli_query($mysqli,$qry_package));
$total_package = $total_package['num'];

$qry_currency="SELECT currency FROM tbl_settings";
$get_currency = mysqli_fetch_array(mysqli_query($mysqli,$qry_currency));
$currency = $get_currency['currency'];
?>       
<head>
<title><?php echo $client_lang['home']; ?></title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
<style>
    .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            margin-left: 10px;
            margin-bottom: 10px;
            margin-top:10px;
        }
        .chart-box, .pie-chart-box {
            flex: 1;
            min-width: 25%; /* Ensure a minimum width for each chart box */
            max-width: 33%; /* Limit the maximum width to prevent stretching */
            box-sizing: border-box;
        }
        .canvas-wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 18px;
            border-color: #fafafa;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        #auctionBidsChart, #auctionBidsPieChart, #auctionBidsPointChart, #topBidderChart, #topTicketChart {
            height: 400px !important; /* Set fixed height for charts */
        }
        
        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .chart-box, .pie-chart-box {
                flex: 1 1 45%; /* Adjust flex basis for medium screens */
            }
        }
        @media (max-width: 768px) {
            .chart-box, .pie-chart-box {
                flex: 1 1 100%; /* Full width on smaller screens */
            }
        }
        /* style for div row elements */
        /* Container for the row */

</style>
</head>

<!-- Icon -->
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.2.0/uicons-regular-straight/css/uicons-regular-straight.css'>


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
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="banners.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fa fa-image"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_banner']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_banner;?></div>
          </div>
        </div>
        </a> </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="users.php" class="card card-banner card-yellow-light">
        <div class="card-body"><i class="icon fi fi-rs-users-alt"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_user']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_category;?></div>
          </div>
        </div>
        </a> 
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="lottery.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rr-ticket-alt"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_lottery']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_sub_cat1;?></div>
          </div>
        </div>
        </a> 
        </div> 
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="redeem.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rr-gift"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_withdrawl']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_redeem;?></div>
          </div>
        </div>
        </a> 
        </div>
        
        
      </div>
        
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="coin_packages.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rr-token"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_recharge_pack']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_package;?></div>
          </div>
        </div>
        </a> </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="coin_purchases.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fa fa-money"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_payment']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $get_currency['currency'].$total_sub_cat;?></div>
          </div>
        </div>
        </a> 
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="coin_spends.php" class="card card-banner card-yellow-light">
        <div class="card-body"><i class="icon fa fa-history"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_spends']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_pdf;?></div>
          </div>
        </div>
        </a> </div> 
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="referrals.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rs-refer"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_referral']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_network;?></div>
          </div>
        </div>
        </a> </div>
      </div>
      
      <div class="row">  
         <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="orders.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rs-truck-box"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_order']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_orders;?></div>
          </div>
        </div>
        </a> </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="vendors.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rs-seller"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_seller']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_vendor;?></div>
          </div>
        </div>
        </a> </div>
        
        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> <a href="shop.php" class="card card-banner card-yellow-light">
        <div class="card-body"> <i class="icon fi fi-rr-box-open"></i>
          <div class="content">
            <div class="title"><?php echo $client_lang['total_items']; ?></div>
            <div class="value"><span class="sign"></span><?php echo $total_shop;?></div>
          </div>
        </div>
        </a> 
        </div>
    </div>

        
<?php include("includes/footer.php");?>       
