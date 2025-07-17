<?php
include '../../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['coachID'])) {
    $coachID = $data['coachID'];
    $despatchedDate = isset($data['despatchedDate']) ? $data['despatchedDate'] : null;

    // Convert despatchedDate to the required format
    $datedespatched = new DateTime($despatchedDate);
    $formattedDatedespatched = $datedespatched->format('Y-m-d H:i:s');

    // Update the tbl_TransactionalData table
    $query1 = "UPDATE [CoachMaster_V_2018].[dbo].[tbl_TransactionalData]
               SET 
                   despatched_date = ?
               WHERE CoachID = ? AND despatched_date IS NULL";
    $params1 = array($formattedDatedespatched, $coachID);
    $stmt1 = sqlsrv_query($conn, $query1, $params1);

    // Update the tbl_CoachPosition table
    $query2 = "UPDATE [CoachMaster_V_2018].[dbo].[tbl_CoachPosition]
               SET 
                   SdateOut = ?
               WHERE RSID = ? AND SdateOut IS NULL";
    $params2 = array($formattedDatedespatched, $coachID);
    $stmt2 = sqlsrv_query($conn, $query2, $params2);

    if ($stmt1 !== false && $stmt2 !== false) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No CoachID provided']);
}
?>
