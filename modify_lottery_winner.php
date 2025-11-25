<?php 
include('includes/header.php'); 
include('includes/function.php');
include('language/language.php');

$querytime = "SELECT timezone FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
date_default_timezone_set($rowtime['timezone']);
$datetime = date('Y-m-d H:i:s'); // Current datetime

if (isset($_GET['o_id'])) {
    $o_id = intval($_GET['o_id']);
    
    // Get the offer details
    $offer_qry = "SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE tbl_offers.o_id = ?";
    $stmt = $mysqli->prepare($offer_qry);
    $stmt->bind_param("i", $o_id);
    $stmt->execute();
    $offer_result = $stmt->get_result();
    $offer = $offer_result->fetch_assoc();
    
    // Get the lottery ball details
    $balls_qry = "SELECT * FROM tbl_lottery_balls LEFT JOIN tbl_offers ON tbl_offers.o_id = tbl_lottery_balls.o_id WHERE tbl_offers.o_id = ?";
    $stmt = $mysqli->prepare($offer_qry);
    $stmt->bind_param("i", $o_id);
    $stmt->execute();
    $balls_result = $stmt->get_result();
    $balls = $balls_result->fetch_assoc();
    $blue_balls = $balls['normal_ball_limit'];
    $gold_balls = $balls['premium_ball_limit'];
    $total_balls = $blue_balls + $gold_balls;
    
    // Check if the offer type is 5
    if ($offer['o_type'] == 5) {
        // Get the prizes for this lottery
        $prizes_qry = "SELECT * FROM tbl_prizes LEFT JOIN tbl_items ON tbl_items.item_id = tbl_prizes.item_id WHERE tbl_prizes.o_id = ? ORDER BY rank_start";
        $stmt = $mysqli->prepare($prizes_qry);
        $stmt->bind_param("i", $o_id);
        $stmt->execute();
        $prizes_result = $stmt->get_result();

        // Create an array to store the prizes by rank
        $prizes_by_rank = [];
        while ($prize = $prizes_result->fetch_assoc()) {
            for ($rank = $prize['rank_start']; $rank <= $prize['rank_end']; $rank++) {
                $prizes_by_rank[$rank] = $prize;
            }
        }

        // Fetch existing winners
        $winners_qry = "SELECT * FROM tbl_winners WHERE o_id = ?";
        $stmt = $mysqli->prepare($winners_qry);
        $stmt->bind_param("i", $o_id);
        $stmt->execute();
        $winners_result = $stmt->get_result();

        // Create an array to store existing winners by rank
        $existing_winners = [];
        while ($winner = $winners_result->fetch_assoc()) {
            $existing_winners[$winner['winner_rank']] = $winner;
        }
        
        if (isset($_POST['save_winners'])) {
            foreach ($_POST['prize_winners'] as $rank => $winner_data) {
                $winning_ticket_id = intval($winner_data['ticket_id']);
                
                // Get the winning ticket details
                $ticket_qry = "SELECT * FROM tbl_ticket LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id WHERE o_id = ? AND ticket_id = ?";
                $stmt = $mysqli->prepare($ticket_qry);
                $stmt->bind_param("ii", $o_id, $winning_ticket_id);
                $stmt->execute();
                $ticket_result = $stmt->get_result();
                $ticket = $ticket_result->fetch_assoc();

                // Concatenate winning value from ball_1 to ball_8
                $winning_value = "{$ticket['ball_1']} {$ticket['ball_2']} {$ticket['ball_3']} {$ticket['ball_4']} {$ticket['ball_5']} {$ticket['ball_6']} {$ticket['ball_7']} {$ticket['ball_8']}";

                // Check if the winner already exists for this rank
                if (isset($existing_winners[$rank])) {
                    // Update the existing winner
                    $winner_update_qry = "UPDATE tbl_winners SET u_id = ?, winner_name = ?, participation_id = ?, winning_value = ? WHERE o_id = ? AND winner_rank = ?";
                    $stmt = $mysqli->prepare($winner_update_qry);
                    $stmt->bind_param("isssii", $ticket['u_id'], $ticket['name'], $ticket['ticket_id'], $winning_value, $o_id, $rank);
                    $stmt->execute();
                } else {
                    // Insert a new winner
                    $winner_insert_qry = "INSERT INTO tbl_winners (o_id, u_id, winner_rank, winner_name, participation_id, winning_value) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($winner_insert_qry);
                    $stmt->bind_param("iiisss", $o_id, $ticket['u_id'], $rank, $ticket['name'], $ticket['ticket_id'], $winning_value);
                    $stmt->execute();
                }
                
            }
            $_SESSION['msg'] = "winnersModified";
            header("Location: modify_lottery_winner.php?o_id=$o_id");
            exit;
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
                            <?php for ($rank = 1; $rank <= 250; $rank++) { 
                                $prize = isset($prizes_by_rank[$rank]) ? $prizes_by_rank[$rank] : null;
                                if ($prize) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rank; ?>
                                    <input type="hidden" name="prize_winners[<?php echo $rank; ?>][rank]" value="<?php echo $rank; ?>">
                                </td>
                                <td><?php echo $prize['o_name']; ?></td>
                                <td>
                                    <select name="prize_winners[<?php echo $rank; ?>][ticket_id]" class="form-control">
                                        <?php
                                        // Get the tickets for this offer
                                        $tickets_qry = "SELECT * FROM tbl_ticket LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id WHERE o_id = ?";
                                        $stmt = $mysqli->prepare($tickets_qry);
                                        $stmt->bind_param("i", $o_id);
                                        $stmt->execute();
                                        $tickets_result = $stmt->get_result();
                                
                                        // Check if there are any tickets
                                        if ($tickets_result->num_rows > 0) {
                                            while ($ticket = $tickets_result->fetch_assoc()) {
                                                $selected = isset($existing_winners[$rank]) && $existing_winners[$rank]['participation_id'] == $ticket['ticket_id'] ? 'selected' : '';
                                                echo "<option value='{$ticket['ticket_id']}' $selected>Ticket:&nbsp;{$ticket['ball_1']}&nbsp;{$ticket['ball_2']}&nbsp;{$ticket['ball_3']}&nbsp;{$ticket['ball_4']}&nbsp;{$ticket['ball_5']}&nbsp;{$ticket['ball_6']}&nbsp;{$ticket['ball_7']}&nbsp;{$ticket['ball_8']} (User: {$ticket['name']})</option>";
                                            }
                                        } else {
                                            echo "<option value=''>".$client_lang['no_tickets']."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                    <button type="submit" name="save_winners" class="btn btn-save"><?php echo $client_lang['save']; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>     

<?php include('includes/footer.php'); ?>
<?php 
    }
}
?>
