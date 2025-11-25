<?php 
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');  

$where_clause = ""; // Initialize where clause

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $where_clause = " AND tbl_wallet_passbook.wp_package_id = '$id'";
}

if (isset($_POST['user_search'])) {
    $search_value = mysqli_real_escape_string($mysqli, $_POST['search_value']);
    $user_qry = "SELECT * FROM tbl_wallet_passbook
                 LEFT JOIN tbl_users ON tbl_users.id = tbl_wallet_passbook.wp_user
                 LEFT JOIN tbl_coin_list ON tbl_coin_list.c_id = tbl_wallet_passbook.wp_package_id
                 WHERE tbl_users.name LIKE '%$search_value%' $where_clause
                 ORDER BY tbl_wallet_passbook.wp_id DESC";  
    $users_result = mysqli_query($mysqli, $user_qry);
} else {
    $tableName = "tbl_wallet_passbook";        
    $targetpage = "coin_purchases.php";     
    $limit = 15; 

    $query = "SELECT COUNT(*) as num FROM $tableName WHERE 1=1 $where_clause";
    $total_pages = mysqli_fetch_array(mysqli_query($mysqli, $query));
    $total_pages = $total_pages['num'];

    $stages = 3;
    $page = 0;
    if (isset($_GET['page'])) {
        $page = mysqli_real_escape_string($mysqli, $_GET['page']);
    }
    if ($page) {
        $start = ($page - 1) * $limit; 
    } else {
        $start = 0;    
    }    

    $users_qry = "SELECT * FROM tbl_wallet_passbook
                  LEFT JOIN tbl_users ON tbl_users.id = tbl_wallet_passbook.wp_user
                  LEFT JOIN tbl_coin_list ON tbl_coin_list.c_id = tbl_wallet_passbook.wp_package_id
                  WHERE 1=1 $where_clause
                  ORDER BY tbl_wallet_passbook.wp_id DESC LIMIT $start, $limit";  
    $users_result = mysqli_query($mysqli, $users_qry);
}

// if (isset($_GET['wp_id'])) {
//     Delete('tbl_wallet_passbook', 'wp_id=' . $_GET['wp_id']);
//     $_SESSION['msg'] = "12";
//     header("Location:coin_purchases.php");
//     exit;
// }

//Active and Deactive status
// if (isset($_GET['status_deactive_id'])) {
//     $data = array('wp_status'  =>  '0');
//     $edit_status = Update('tbl_wallet_passbook', $data, "WHERE wp_id = '".$_GET['status_deactive_id']."'");
//     $_SESSION['msg'] = "14";
//     header("Location:coin_purchases.php");
//     exit;
// }
// if (isset($_GET['status_active_id'])) {
//     $data = array('wp_status'  =>  '1');
//     $edit_status = Update('tbl_wallet_passbook', $data, "WHERE wp_id = '".$_GET['status_active_id']."'");
//     $_SESSION['msg'] = "13";
//     header("Location:coin_purchases.php");
//     exit;
// }
?>

<style>
    .blue-btn {
        background-color: #007bff; /* Blue background color */
        color: #fff; /* White text color */
        padding: 10px 20px; /* Padding for the button */
        border: none; /* No border */
        border-radius: 4px; /* Rounded corners */
        cursor: pointer; /* Cursor style */
        text-decoration: none; /* Remove default text decoration */
        display: inline-block; /* Make it inline-block to adjust width */
        transition: background-color 0.3s; /* Smooth transition on hover */
    }
    
    .blue-btn:hover {
        color: #fff;
        background-color: #0056b3; /* Darker blue color on hover */
    }
    
</style>

<head>
<title><?php echo $client_lang['recharge_history']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
<div class="row">
    <div class="col-xs-12">
        <div class="card mrg_bottom">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php echo $client_lang['recharge_history']; ?></div>
                </div>
                <div class="col-md-7 col-xs-12">              
                    <div class="search_list">
                        <div class="search_block">
                            <form method="post" action="">
                                <input class="form-control input-sm" placeholder="<?php echo $client_lang['search_recharge_history']; ?>" aria-controls="DataTables_Table_0" type="search" name="search_value" required>
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
                        <?php if (isset($_SESSION['msg'])) { ?> 
                            <div class="alert alert-success alert-dismissible" role="alert"> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <?php echo $client_lang[$_SESSION['msg']]; ?>
                            </div>
                        <?php unset($_SESSION['msg']); } ?>    
                    </div>
                </div>
            </div>
            <div class="col-md-12 mrg-top">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo $client_lang['package_name']; ?></th>    
                            <th><?php echo $client_lang['purchase_user_name']; ?></th>
                            <th><?php echo $client_lang['purchase_date']; ?></th>  
                            <th class="cat_action_list"><?php echo $client_lang['action']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0;
                            while ($users_row = mysqli_fetch_array($users_result)) {
                        ?>
                        <tr>
                            <td><?php echo $users_row['c_name']; ?></td>
                            <td><?php echo $users_row['name']; ?></td>  
                            <td><?php echo $users_row['wp_date']; ?></td>
                            <td>
                              <div class="button-container">
                                <a href="view_user.php?id=<?php echo $users_row['wp_user'];?>" class="btn btn-view"><i class="fa fa-eye"></i>&nbsp;<?php echo $client_lang['view_user']; ?></a>
                                <!--<a href="coin_purchases.php?wp_id=<?php echo $users_row['wp_id']; ?>" onclick="return confirm('<?php echo $client_lang['delete_package']; ?>');" class="btn btn-delete"><i class="fa fa-trash"></i>&nbsp;<?php echo $client_lang['delete_transaction']; ?></a>-->
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
                        <?php if (!isset($_POST["search"])) { include("pagination.php"); } ?>                 
                    </nav>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?> 
