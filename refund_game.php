<?php
include('includes/header.php');
include('includes/connection.php');
include('includes/function.php');
include('language/language.php');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$refunds = array();
$refund_success = false; // Flag to indicate if refund is successful
$no_bids_message = false;


if (isset($_GET['o_id']) && !isset($_POST['confirm'])) {
    $o_id = intval($_GET['o_id']);
    if (isset($_POST['refund_percentage'])) {
        $refund_percentage = floatval($_POST['refund_percentage']);

        // Fetch item details from tbl_offers
        $query = "SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id  WHERE o_id = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "i", $o_id);
        mysqli_stmt_execute($stmt);
        $item_result = mysqli_stmt_get_result($stmt);
        $item_row = mysqli_fetch_assoc($item_result);
        $o_type = $item_row['o_type'];  // Fetch the o_type value
        
        // Determine the text for the buttons based on o_type
        if (in_array($o_type, [1, 2, 7, 8])) {
            $entry_text = 'Bid';
        } elseif (in_array($o_type, [4, 5])) {
            $entry_text = 'Ticket';
        } else {
            $entry_text = 'Entry';
        }

        if ($item_row) {
            // Fetch all bids for the item from tbl_bid and join with tbl_users
            $query_bids = "SELECT tbl_users.id as user_id, tbl_users.name, tbl_users.email, SUM(tbl_bid.bd_amount) as total_spent
                           FROM tbl_bid
                           JOIN tbl_users ON tbl_users.id = tbl_bid.u_id
                           WHERE tbl_bid.o_id = ?
                           GROUP BY tbl_users.id";
            $stmt_bids = mysqli_prepare($mysqli, $query_bids);
            mysqli_stmt_bind_param($stmt_bids, "i", $o_id);
            mysqli_stmt_execute($stmt_bids);
            $bids_result = mysqli_stmt_get_result($stmt_bids);

            $refunds = array();

            while ($bid_row = mysqli_fetch_assoc($bids_result)) {
                $total_spent = $bid_row['total_spent'];
                $refund_amount = $total_spent * ($refund_percentage / 100);

                // Prepare the data for confirmation
                $refunds[] = array(
                    'user_id' => $bid_row['user_id'],
                    'email' => $bid_row['email'],
                    'name' => $bid_row['name'],
                    'total_spent' => $total_spent,
                    'refund_amount' => $refund_amount
                );
            }
            if (empty($refunds)) {
                $no_bids_message = true;
            }
        }
    }
    
} elseif (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
    // Process the refund after confirmation
    $o_id = intval($_POST['o_id']);
    $refund_percentage = floatval($_POST['refund_percentage']);

    // Fetch all bids for the item from tbl_bid and join with tbl_users
    $query_bids = "SELECT tbl_users.id as user_id, tbl_users.name, tbl_users.email, SUM(tbl_bid.bd_amount) as total_spent
                   FROM tbl_bid
                   JOIN tbl_users ON tbl_users.id = tbl_bid.u_id
                   WHERE tbl_bid.o_id = ?
                   GROUP BY tbl_users.id";
    $stmt_bids = mysqli_prepare($mysqli, $query_bids);
    mysqli_stmt_bind_param($stmt_bids, "i", $o_id);
    mysqli_stmt_execute($stmt_bids);
    $bids_result = mysqli_stmt_get_result($stmt_bids);

    $refunds = array();

    while ($bid_row = mysqli_fetch_assoc($bids_result)) {
        $total_spent = $bid_row['total_spent'];
        $refund_amount = intval($total_spent * ($refund_percentage / 100));

        // Process the refund
        $refunds[] = array(
            'user_id' => $bid_row['user_id'],
            'email' => $bid_row['email'],
            'name' => $bid_row['name'],
            'total_spent' => $total_spent,
            'refund_amount' => $refund_amount
        );

        // Example of updating the user's balance
        $update_query = "UPDATE tbl_users SET wallet = wallet + ? WHERE id = ?";
        $update_stmt = mysqli_prepare($mysqli, $update_query);
        mysqli_stmt_bind_param($update_stmt, "di", $refund_amount, $bid_row['user_id']);
        mysqli_stmt_execute($update_stmt);
        
        // Insert transaction data
        $transaction_query = "INSERT INTO tbl_transaction (user_id, type_no, date, money, type) VALUES (?, ?, NOW(), ?, 9)";
        $transaction_stmt = mysqli_prepare($mysqli, $transaction_query);
        mysqli_stmt_bind_param($transaction_stmt, "idi", $bid_row['user_id'], $o_id, $refund_amount);
        mysqli_stmt_execute($transaction_stmt);
    
    }
    // Set refund success flag
    $refund_success = true;
}
elseif (isset($_POST['cancel_and_refund'])) {
    // Cancel and refund process
    $o_id = intval($_POST['o_id']);
    $refund_percentage = floatval($_POST['refund_percentage']);

    // Fetch all bids for the item from tbl_bid and join with tbl_users
    $query_bids = "SELECT tbl_users.id as user_id, tbl_users.name, tbl_users.email, SUM(tbl_bid.bd_amount) as total_spent
                   FROM tbl_bid
                   JOIN tbl_users ON tbl_users.id = tbl_bid.u_id
                   WHERE tbl_bid.o_id = ?
                   GROUP BY tbl_users.id";
    $stmt_bids = mysqli_prepare($mysqli, $query_bids);
    mysqli_stmt_bind_param($stmt_bids, "i", $o_id);
    mysqli_stmt_execute($stmt_bids);
    $bids_result = mysqli_stmt_get_result($stmt_bids);

    $refunds = array();

    while ($bid_row = mysqli_fetch_assoc($bids_result)) {
        $total_spent = $bid_row['total_spent'];
        $refund_amount = intval($total_spent * ($refund_percentage / 100));

        // Process the refund
        $refunds[] = array(
            'user_id' => $bid_row['user_id'],
            'email' => $bid_row['email'],
            'name' => $bid_row['name'],
            'total_spent' => $total_spent,
            'refund_amount' => $refund_amount
        );

        // Example of updating the user's balance
        $update_query = "UPDATE tbl_users SET wallet = wallet + ? WHERE id = ?";
        $update_stmt = mysqli_prepare($mysqli, $update_query);
        mysqli_stmt_bind_param($update_stmt, "di", $refund_amount, $bid_row['user_id']);
        mysqli_stmt_execute($update_stmt);

        // Insert transaction data into tbl_transaction
        $transaction_query = "INSERT INTO tbl_transaction (user_id, type_no, date, money, type) VALUES (?, ?, NOW(), ?, 9)";
        $transaction_stmt = mysqli_prepare($mysqli, $transaction_query);
        mysqli_stmt_bind_param($transaction_stmt, "iid", $bid_row['user_id'], $o_id, $refund_amount);
        mysqli_stmt_execute($transaction_stmt);
    }

    // Set bd_status to 2 for all bids
    $update_bids_query = "UPDATE tbl_bid SET bd_status = 2 WHERE o_id = ?";
    $update_bids_stmt = mysqli_prepare($mysqli, $update_bids_query);
    mysqli_stmt_bind_param($update_bids_stmt, "i", $o_id);
    mysqli_stmt_execute($update_bids_stmt);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Refund Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 50px auto;
            padding: 20px;
            width: 80%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label, input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="number"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
 <div class="row">
    <div class="col-xs-12">
        <div class="card mrg_bottom">
            <div class="page_title_block">
                <div class="col-md-5 col-xs-12">
                    <div class="page_title">Refund Portal</div>
                </div>
                <div class="container">
                    <?php if (empty($refunds)) { ?>
                        <form method="POST" action="refund_game.php?o_id=<?php echo $o_id; ?>">
                            <label for="refund_percentage">Refund Percentage:</label>
                            <div class="input-group">
                            <input type="number" id="refund_percentage" placeholder="100" name="refund_percentage" step="0.01" min="0" max="100" required>
                            <span class="input-group-addon">%</span>
                            </div>
                            <button type="submit">Process Refund</button>
                        </form>
                    <?php } else { ?>
                        <h2>Confirm Refund Details</h2>
                        <table>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Spent</th>
                                <th>Refund Amount</th>
                            </tr>
                            <?php foreach ($refunds as $refund) { ?>
                                <tr>
                                    <td><?php echo $refund['user_id']; ?></td>
                                    <td><?php echo $refund['name']; ?></td>
                                    <td><?php echo $refund['email']; ?></td>
                                    <td><?php echo $refund['total_spent'] . ' Coins'; ?></td>
                                    <td><?php echo intval($refund['refund_amount']) . ' Coins'; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                        <form method="POST" action="refund_game.php?o_id=<?php echo $o_id; ?>">
                            <input type="hidden" name="o_id" value="<?php echo $o_id; ?>">
                            <input type="hidden" name="refund_percentage" value="<?php echo $refund_percentage; ?>">
                            <input type="hidden" name="confirm" value="yes">
                            <button type="submit" name="cancel_and_refund" class="btn btn-success">Cancel <?php echo $entry_text; ?> and Refund</button>
                            <button type="submit" class="btn btn-warning">Refund without cancelling <?php echo $entry_text; ?></button>
                            <button type="button" class="btn btn-danger" onclick="window.location.href='refund_game.php?o_id=<?php echo $o_id; ?>';">Cancel</button>
                        </form>
                        <?php if ($refund_success) {
                            header("Location: refund_success.php");
                            exit; // Make sure to exit after redirection
                        } ?>
                    <?php } ?>
                    
                    <?php if ($no_bids_message) { ?>
                        <p>No active participation</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
