<?php
// payment_callback.php
// Simulated webhook endpoint for Xendit / Paymongo callbacks

header('Content-Type: application/json');
require_once __DIR__ . '/php_includes/connection.php';

$hasAudit = file_exists(__DIR__ . '/php_includes/audit.php');
if ($hasAudit) require_once __DIR__ . '/php_includes/audit.php';

// Accept raw POST input
$input = file_get_contents('php_input');
if (!$input) {
    $input = file_get_contents('php://input');
}

$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload']);
    exit;
}

// Simulated Payload format:
// {
//    "payment_ref": "TXN-XXXXXX",
//    "status": "paid",  // paid | failed
//    "ticket_ids": [12, 13]
// }

$payment_ref = trim($data['payment_ref'] ?? '');
$status = trim($data['status'] ?? 'paid');
$ticket_ids = $data['ticket_ids'] ?? [];

if ($payment_ref === '' || empty($ticket_ids)) {
    http_response_code(422);
    echo json_encode(['error' => 'Missing payment reference or ticket IDs']);
    exit;
}

$con->begin_transaction();
try {
    $stmt = $con->prepare("UPDATE regs SET payment_status = ?, payment_ref = ? WHERE ticket = ?");
    if ($stmt) {
        foreach ($ticket_ids as $ticket_id) {
            $ticket_id_val = (int)$ticket_id;
            $stmt->bind_param("ssi", $status, $payment_ref, $ticket_id_val);
            $stmt->execute();
        }
        $stmt->close();
        $con->commit();

        if ($hasAudit) {
            audit_log($con, 'payment_gateway_webhook', 'payment_webhook_processed', [
                'ref' => $payment_ref,
                'status' => $status,
                'tickets' => $ticket_ids
            ]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Payment status updated successfully.']);
        exit;
    } else {
        throw new Exception("SQL Statement preparation failed.");
    }
} catch (Exception $ex) {
    $con->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $ex->getMessage()]);
    exit;
}
