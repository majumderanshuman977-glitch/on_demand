<?php
include('includes/function.php');
include('language/language.php');

if (isset($_POST['o_id'])) {
    $o_id = intval($_POST['o_id']);
    
    // Get all tickets for the offer
    $tickets_qry = "SELECT * FROM tbl_ticket LEFT JOIN tbl_users ON tbl_users.id = tbl_ticket.u_id WHERE o_id = ?";
    $stmt = $mysqli->prepare($tickets_qry);
    $stmt->bind_param("i", $o_id);
    $stmt->execute();
    $tickets_result = $stmt->get_result();
    
    $tickets = [];
    while ($ticket = $tickets_result->fetch_assoc()) {
        $tickets[] = $ticket;
    }
    
    // Get the prizes for this lottery
    $prizes_qry = "SELECT * FROM tbl_prizes LEFT JOIN tbl_items ON tbl_items.item_id = tbl_prizes.item_id WHERE tbl_prizes.o_id = ? ORDER BY rank_start";
    $stmt = $mysqli->prepare($prizes_qry);
    $stmt->bind_param("i", $o_id);
    $stmt->execute();
    $prizes_result = $stmt->get_result();
    
    $prizes_by_rank = [];
    while ($prize = $prizes_result->fetch_assoc()) {
        for ($rank = $prize['rank_start']; $rank <= $prize['rank_end']; $rank++) {
            $prizes_by_rank[$rank] = $prize;
        }
    }
    
    $selected_winners = [];
    $used_u_ids = [];
    
    foreach ($prizes_by_rank as $rank => $prize) {
        if (count($tickets) == 0) {
            break;
        }
        
        // Filter tickets to ensure unique u_id
        $available_tickets = array_filter($tickets, function($ticket) use ($used_u_ids) {
            return !in_array($ticket['u_id'], $used_u_ids);
        });
        
        if (count($available_tickets) == 0) {
            break;
        }
        
        // Select a random winner from the available tickets
        $random_index = array_rand($available_tickets);
        $random_winner = $available_tickets[$random_index];
        
        $selected_winners[] = [
            'rank' => $rank,
            'ticket_id' => $random_winner['ticket_id']
        ];
        
        $used_u_ids[] = $random_winner['u_id'];
    }
    
    echo json_encode([
        'success' => true,
        'winners' => $selected_winners
    ]);
}
?>
