<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $response = ['success' => false, 'message' => ''];

    if ($appointment_id > 0) {
        switch ($action) {
            case 'cancel':
                $query = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $appointment_id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Appointment cancelled successfully'];
                }
                break;

            case 'reschedule':
                $new_date = $_POST['new_date'];
                $new_time = $_POST['new_time'];
                $query = "UPDATE appointments SET appointment_date = ?, appointment_time = ?, status = 'rescheduled' WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Appointment rescheduled successfully'];
                }
                break;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
