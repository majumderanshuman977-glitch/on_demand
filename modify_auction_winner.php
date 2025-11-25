<?php 
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');  

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (isset($_GET['o_id'])) {
    $o_id = intval($_GET['o_id']);
    
    // Get the offer details
    $offer_qry = "SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_offers.o_id = ?";
    $stmt = $mysqli->prepare($offer_qry);
    $stmt->bind_param("i", $o_id);
    $stmt->execute();
    $offer_result = $stmt->get_result();
    $offer = $offer_result->fetch_assoc();
        
    if (isset($_POST['save_winners'])) {
        $winner_id = intval($_POST['winner_id']);
        
        // Get the selected winner details
        $winner_qry = "SELECT tbl_bid.u_id, tbl_users.name, tbl_bid.bd_value FROM tbl_bid LEFT JOIN tbl_users ON tbl_users.id = tbl_bid.u_id WHERE tbl_bid.bd_id = ?";
        $stmt = $mysqli->prepare($winner_qry);
        $stmt->bind_param("i", $winner_id);
        $stmt->execute();
        $winner_result = $stmt->get_result();
        $winner = $winner_result->fetch_assoc();

        if ($winner) {
            $winner_update_qry = "UPDATE tbl_offers SET winner_type = ?, winner_id = ?, winner_name = ?, winning_value = ? WHERE o_id = ?";
            $stmt = $mysqli->prepare($winner_update_qry);
            $winner_type = 1;
            $winner_id = $winner['u_id'];
            $winner_name = $winner['name'];
            $winning_value = $winner['bd_value'];
            $stmt->bind_param("iissi", $winner_type, $winner_id, $winner_name, $winning_value, $o_id);
            $stmt->execute();
                
            $_SESSION['msg'] = "winnersModified";
            header("Location: modify_auction_winner.php?o_id=$o_id");
            exit;
        }
    }
}
?>

<head>
<title><?php echo $client_lang['manage_winners']; ?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
<style>
    .btn-save {
        background-color: #28a745; /* Green background color */
        color: #fff; /* White text color */
    }
    
    .btn-save:hover {
        background-color: #218838; /* Darker green color on hover */
        color: #fff; /* White text color */
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="card mrg_bottom">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title"><?php echo $client_lang['manage_winners']; ?></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row mrg-top">
                <div class="col-md-12">
                    <div class="col-md-12 col-sm-12">
                        <?php if(isset($_SESSION['msg'])){?> 
                        <div class="alert alert-success alert-dismissible" role="alert"> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <?php echo $client_lang[$_SESSION['msg']] ; ?> 
                        </div>
                        <?php unset($_SESSION['msg']);}?>    
                    </div>
                </div>
            </div>
            <div class="col-md-12 mrg-top">
                <form method="post" action="">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><?php echo $client_lang['prize_rank']; ?></th>
                                <th><?php echo $client_lang['prize_name']; ?></th>  
                                <th><?php echo $client_lang['select_winner']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo "1"; ?></td>
                                <td><?php echo $offer['o_name']; ?></td>
                                <td>
                                    <select name="winner_id" class="form-control">
                                        <?php
                                        // Fetch the current winner's ID from tbl_offers
                                        $current_winner_id = $offer['winner_id'];
                                        $current_winner_bid = $offer['winning_value'];
                                        
                                    if($current_winner_id !== NULL)
                                    {
                                        $checkCurrentWinnerQry = "SELECT tbl_bid.bd_id, tbl_users.name, tbl_bid.bd_value FROM tbl_bid LEFT JOIN tbl_users ON tbl_users.id = tbl_bid.u_id WHERE tbl_bid.u_id = ? AND tbl_bid.bd_value = ?";
                                        $stmt = $mysqli->prepare($checkCurrentWinnerQry);
                                        $stmt->bind_param("ii", $current_winner_id,$current_winner_bid);
                                        $stmt->execute();
                                        $checkCurrentWinnerResult = $stmt->get_result();
                                        $currentWinner = $checkCurrentWinnerResult->fetch_assoc();
                                        $currentWinnerBid = $currentWinner['bd_id'];
                                    }
                                        
                                        
                                        // Get the bids for this offer
                                        $bids_qry = "SELECT tbl_bid.bd_id, tbl_users.name, tbl_bid.bd_value FROM tbl_bid LEFT JOIN tbl_users ON tbl_users.id = tbl_bid.u_id WHERE tbl_bid.o_id = ?";
                                        $stmt = $mysqli->prepare($bids_qry);
                                        $stmt->bind_param("i", $o_id);
                                        $stmt->execute();
                                        $bids_result = $stmt->get_result();
                                
                                        // Check if there are any bids
                                        if ($bids_result->num_rows > 0) {
                                            while ($bid = $bids_result->fetch_assoc()) {
                                                 // Check if this bid is the current winner
                                                $selected = ($bid['bd_id'] == $currentWinnerBid) ? 'selected' : '';
                                                echo "<option value='{$bid['bd_id']}' $selected>Bid Value: {$bid['bd_value']} (User: {$bid['name']})</option>";
                                            }
                                        } else {
                                            echo "<option value=''>".$client_lang['no_bids']."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" name="save_winners" class="btn btn-save"><?php echo $client_lang['save']; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>     

<?php include('includes/footer.php'); ?>
