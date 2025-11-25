<?php
include('includes/connection.php');
include('includes/function.php');
include('language/language.php');
include("includes/session_check.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$querytime = "SELECT timezone, currency FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
date_default_timezone_set($rowtime['timezone']);
$datetime = date('Y-m-d H:i:s'); // Current datetime
$date = date('Y-m-d'); // Current date
$time = date('H:i:s'); // Current time
$currency = $rowtime['currency'];

$itemId = $_GET['o_id'];
$queryDetails = "SELECT * FROM tbl_offers LEFT JOIN tbl_items ON tbl_items.item_id = tbl_offers.item_id WHERE o_id=$itemId";
$resultDetails = mysqli_query($mysqli, $queryDetails);
$rowDetails = mysqli_fetch_assoc($resultDetails);
$itemName = $rowDetails['o_name'];
$itemStart = $rowDetails['o_date'].$rowDetails['o_stime'];
$itemEnd = $rowDetails['o_edate'].$rowDetails['o_etime'];
$itemType = $rowDetails['o_type'];

if ($datetime >= $itemStart && $datetime <= $itemEnd) {
    $itemStatus= "Live";
} elseif ($datetime < $itemStart) {
    $itemStatus= "Upcoming";
} elseif ($datetime > $itemEnd) {
    $itemStatus= "Ended";
}

// Query to fetch all entries details
$queryBids = "SELECT * FROM tbl_ticket WHERE o_id=$itemId";
$resultBids = mysqli_query($mysqli, $queryBids);

// Query to fetch total number of entries
$queryTotalEntry = "SELECT COUNT(*) AS total_entries FROM tbl_ticket WHERE o_id=$itemId";
$resultTotalEntry = mysqli_query($mysqli, $queryTotalEntry);
$rowTotalEntry = mysqli_fetch_assoc($resultTotalEntry);
$totalEntries = $rowTotalEntry['total_entries'];

// Query to fetch bid dates and counts
$queryBidDates = "SELECT DATE(purchase_date) as bid_date, COUNT(*) AS bid_count FROM tbl_ticket WHERE o_id=$itemId GROUP BY DATE(purchase_date)";
$resultBidDates = mysqli_query($mysqli, $queryBidDates);

// Initialize arrays to store bid dates and counts
$bidDates = [];
$bidCounts = [];

// Fetch bid dates and counts from the result set
while ($rowBidDates = mysqli_fetch_assoc($resultBidDates)) {
    $bidDates[] = $rowBidDates['bid_date'];
    $bidCounts[] = $rowBidDates['bid_count'];
}

// Create data for the bar chart
$chartData = [
    'labels' => $bidDates,
    'data' => $bidCounts
];

// Query to fetch top bidders
$queryTopBidders = "SELECT tbl_users.name, tbl_users.id, COUNT(tbl_ticket.u_id) AS bid_count
                    FROM tbl_ticket
                    LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id
                    WHERE tbl_ticket.o_id = $itemId
                    GROUP BY tbl_ticket.u_id
                    ORDER BY bid_count DESC
                    LIMIT 5";
$resultTopBidders = mysqli_query($mysqli, $queryTopBidders);

// Query to fetch last 10 bids
$queryRecentBids = "SELECT * FROM tbl_ticket LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id left join tbl_offers on tbl_offers.o_id = tbl_ticket.o_id LEFT JOIN tbl_lottery_balls ON tbl_lottery_balls.lottery_balls_id = tbl_offers.lottery_balls_id WHERE tbl_ticket.o_id = $itemId ORDER BY tbl_ticket.ticket_id DESC LIMIT 10";
$resultRecentBids = mysqli_query($mysqli, $queryRecentBids);

// Query to fetch prizes
$queryPrizes = "SELECT * FROM tbl_prizes LEFT JOIN tbl_items ON tbl_items.item_id = tbl_prizes.item_id  WHERE `o_id` = $itemId";
$resultPrizes = mysqli_query($mysqli, $queryPrizes);
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<style>
.ticket-numbers {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
}

.ticket-ball {
    margin-right: 10px; /* Add space between balls */
    position: relative;
    width: 30px; /* Set the size of the ball container */
    height: 30px;
}

.ticket-ball img {
    width: 100%; /* Make the image fill the container */
    height: 100%;
}

.ticket-number {
    font-size: 14px; /* Adjust the size of the ticket numbers */
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #000; /* Change the text color to white */
    font-weight: bold;
    text-align: center;
}
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background:#fff;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .section {
        margin-bottom: 20px;
    }
    .section-title {
        text-align: center;
        font-size: 24px;
        margin-bottom: 10px;
    }
    .item-details {
        border: 1px solid #ccc;
        padding: 20px;
        background-color: #f9f9f9;
        display: flex;
        justify-content: space-between; /* Align items to the left and right */
        align-items: center;
    }
    .item-details strong {
        font-size: 20px;
    }
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }
    .table-container {
        margin-top: 20px;
    }
    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }
    .table-container th, .table-container td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .table-container th {
        background-color: #f2f2f2;
        text-align: center;
    }
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


<div class="container">
    <div class="section">
        <h2 class="section-title">Lottery Details</h2>
        
        <div class="item-details">
            <strong>Grand Name:</strong> <?php echo $itemName ?><br>
            <strong>Lottery Status:</strong> <?php
            if ($itemStatus == "Live") {
                echo '<span class="badge badge-success badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Live</span></span>';
            } elseif ($itemStatus == "Upcoming") {
                echo '<span class="badge badge-warning badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Upcoming</span></span>';
            } elseif ($itemStatus == "Ended") {
                echo '<span class="badge badge-danger badge-icon"><i class="fa fa-check" aria-hidden="true"></i><span>Ended</span></span>';
            }
            ?><br>
        </div>
        <div class="item-details">
            <strong>Total Tickets Purchased:</strong> <?php echo $totalEntries.' Tickets' ?><br>
        </div>


    </div>
        
    <div class="section">
        <hr>
        <div class="table-container">
            <h3>Prizes for this Lottery &nbsp;<a href="prizes.php?o_id=<?php echo $itemId;?>" class="btn blue-btn" style="float: right;">Edit Prize</a></h3><br>
            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Prize</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rowPrizes = mysqli_fetch_assoc($resultPrizes)) { ?>
                    <tr>
                        <td>
                            <?php
                            if ($rowPrizes["rank_start"] == $rowPrizes["rank_end"]) {
                                echo $rowPrizes["rank_start"];
                            } else {
                                echo $rowPrizes["rank_start"] . " - " . $rowPrizes["rank_end"];
                            } ?>
                        </td>
                        <td><?php echo $rowPrizes['o_name']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <hr>
    <h2 class="section-title">Tickets Data</h2>
        <div class="table-container">
            <h3>Top Buyers</h3>
            <table>
                <thead>
                    <tr>
                        <th>Buyer Name</th>
                        <th>Tickets</th>
                        <th>View User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rowTopBidders = mysqli_fetch_assoc($resultTopBidders)) { ?>
                    <tr>
                        <td><?php echo $rowTopBidders['name']; ?></td>
                        <td><?php echo $rowTopBidders['bid_count'].' Tickets'; ?></td>
                        <td><a href="view_user.php?id=<?php echo $rowTopBidders['id'];?>" class="btn blue-btn">View User</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <hr>
        <h3>Datewise Ticket Purchases</h3>
        <div class="chart-container">
            <canvas id="bidsChart"></canvas>
        </div>
        <hr>
        <div class="table-container">
        <h3>Recent Purchases <a href="participations.php?game_id=<?php echo $itemId;?>" class="btn blue-btn" style="float: right;">View All Purchase</a></h3><br>
            <table>
                <thead>
                    <tr>
                        <th>Buyer</th>
                        <th>Ticket Number</th>
                        <th>View User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i=0;
						while ($rowRecentBids = mysqli_fetch_assoc($resultRecentBids))
						{
						    $blueBalls = $rowRecentBids['normal_ball_limit'];
							$goldBalls = $rowRecentBids['premium_ball_limit'];
							$totalBalls = $blueBalls+$goldBalls;

				?>
                    <tr>
                        
                        <td><?php echo $rowRecentBids['name']; ?></td>
                        <td class="ticket-numbers">
                        <?php
                        // Loop through each ball attribute (assuming 8 balls per ticket)
                        for ($j = 1; $j <= $totalBalls; $j++) {
                            $ballValue = $rowRecentBids['ball_'.$j];
                            $ballImage = ($j <= $goldBalls) ? 'golden_ball.png' : 'blue_ball.png';
                            echo '<div class="ticket-ball">';
                            echo '<img src="/images/static/'.$ballImage.'" alt="'.$ballValue.'" title="'.$ballValue.'" />';
                            echo '<span class="ticket-number">'.$ballValue.'</span>';
                            echo '</div>';
                        }
                        ?>
                    </td>
                        <td><center><a href="view_user.php?id=<?php echo $rowRecentBids['id'];?>" class="btn blue-btn">View User</a></center></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('bidsChart').getContext('2d');
    var bidsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartData['labels']); ?>,
            datasets: [{
                label: 'Number of Tickets',
                data: <?php echo json_encode($chartData['data']); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: '#555',
                    backgroundColor: '#fff',
                    borderRadius: 4,
                    font: {
                        weight: 'bold'
                    }
                }
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeInOutBounce'
            }
        }
    });
</script>
<br>
<br>