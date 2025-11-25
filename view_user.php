<?php
include('includes/connection.php');
include('includes/function.php');
include('language/language.php');
include("includes/session_check.php");

$querytime = "SELECT timezone, currency FROM tbl_settings";
$resulttime = mysqli_query($mysqli, $querytime);
$rowtime = mysqli_fetch_assoc($resulttime);
date_default_timezone_set($rowtime['timezone']);
$datetime = date('Y-m-d H:i:s'); // Current datetime
$date = date('Y-m-d'); // Current date
$time = date('H:i:s'); // Current time
$currency = $rowtime['currency'];

$userId = sanitize($_GET['id']);
$queryDetails = "SELECT * FROM tbl_users WHERE id=$userId";
$resultDetails = mysqli_query($mysqli, $queryDetails);
$rowDetails = mysqli_fetch_assoc($resultDetails);
$userName = $rowDetails['name'];

// Query to fetch all entries details
$queryBids = "SELECT * FROM tbl_bid WHERE u_id=$userId";
$resultBids = mysqli_query($mysqli, $queryBids);

// Query to fetch total number of entries
$queryTotalEntry = "SELECT COUNT(*) AS total_entries FROM tbl_bid WHERE u_id=$userId";
$resultTotalEntry = mysqli_query($mysqli, $queryTotalEntry);
$rowTotalEntry = mysqli_fetch_assoc($resultTotalEntry);
$totalEntries = $rowTotalEntry['total_entries'];

// Query to fetch bid dates and counts
$queryBidDates = "SELECT DATE(bd_date) as bid_date, COUNT(*) AS bid_count FROM tbl_bid WHERE u_id=$userId GROUP BY DATE(bd_date)";
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

// Query to fetch last 10 bids
$queryRecentBids = "SELECT * FROM tbl_bid LEFT JOIN tbl_users ON tbl_users.id = tbl_bid.u_id WHERE tbl_bid.u_id = $userId ORDER BY tbl_bid.bd_id DESC LIMIT 10";
$resultRecentBids = mysqli_query($mysqli, $queryRecentBids);

// Function to map transaction type numeric values to descriptive labels
function mapTransactionType($type) {
    switch ($type) {
        case 1:
            return "Coins Spent Transaction";
        case 2:
        case 3:
        case 4:
            return "Referral Transaction";
        case 5:
            return "Coin Purchase Transaction";
        case 6:
            return "Watch & Earn Transaction";
        case 7:
            return "Game Play Transaction";
        case 8:
            return "Scratch card Transaction";
        case 9:
            return "Refund Transaction";
        default:
            return "Unknown";
    }
}

// Query to fetch transaction types and their counts
$queryTransactionTypes = "SELECT type, COUNT(*) AS count FROM tbl_transaction WHERE user_id=$userId GROUP BY type";
$resultTransactionTypes = mysqli_query($mysqli, $queryTransactionTypes);

// Initialize arrays to store transaction types and counts
$transactionLabels = [];
$transactionCounts = [];

// Fetch transaction types and counts from the result set
while ($rowTransactionTypes = mysqli_fetch_assoc($resultTransactionTypes)) {
    $transactionLabels[] = mapTransactionType($rowTransactionTypes['type']);
    $transactionCounts[] = $rowTransactionTypes['count'];
}

// Create data for the pie chart
$pieChartData = [
    'labels' => $transactionLabels,
    'data' => $transactionCounts
];

?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<style>
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
        <h2 class="section-title">User Details</h2>
        
        <div class="item-details">
            <strong>Name:</strong> <?php echo $userName ?><br>
            <strong>Email:</strong> <?php echo $rowDetails['email']; ?><br>
        </div>
        <div class="item-details">
            <strong>Total Participations:</strong> <?php echo $totalEntries.' Participations' ?><br>
        </div>


    </div>
        
    <div class="section">
        <hr>
    <h2 class="section-title">Participation Data</h2>
        <h3>Datewise Participation</h3>
        <div class="chart-container">
            <canvas id="bidsChart"></canvas>
        </div>
        <hr>
        <h3>Transaction Data</h3>
       <div class="chart-container">
        <canvas id="transactionPieChart"></canvas>
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
                label: 'Participation',
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
<script>
    var ctxPie = document.getElementById('transactionPieChart').getContext('2d');
    var transactionPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($pieChartData['labels']); ?>,
            datasets: [{
                label: 'Transaction Types',
                data: <?php echo json_encode($pieChartData['data']); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right'
                }
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