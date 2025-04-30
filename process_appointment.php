<?php
require_once 'config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['service_type']) || empty($_POST['appointment_date']) || empty($_POST['appointment_time'])) {
        header('Location: appointments.php?error=missing_fields');
        exit;
    }

    $user_id = 1; // This should come from your session management
    $service_type = $_POST['service_type'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
    $status = 'pending';

    // Validate date and time
    $current_date = date('Y-m-d');
    if ($appointment_date < $current_date) {
        header('Location: appointments.php?error=invalid_date');
        exit;
    }

    // Check if the service exists
    $service_check = $conn->prepare("SELECT id FROM services WHERE name = ?");
    $service_check->bind_param("s", $service_type);
    $service_check->execute();
    if ($service_check->get_result()->num_rows === 0) {
        header('Location: appointments.php?error=invalid_service');
        exit;
    }

    // Check for duplicate appointments
    $duplicate_check = $conn->prepare("SELECT id FROM appointments WHERE user_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'");
    $duplicate_check->bind_param("iss", $user_id, $appointment_date, $appointment_time);
    $duplicate_check->execute();
    if ($duplicate_check->get_result()->num_rows > 0) {
        header('Location: appointments.php?error=duplicate_appointment');
        exit;
    }

    // Insert the appointment
    $query = "INSERT INTO appointments (user_id, service_type, appointment_date, appointment_time, notes, status) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $user_id, $service_type, $appointment_date, $appointment_time, $notes, $status);
    
    if ($stmt->execute()) {
        header('Location: appointments.php?success=1');
    } else {
        header('Location: appointments.php?error=db_error');
    }
    
    $stmt->close();
    $service_check->close();
    $duplicate_check->close();
    $conn->close();
} else {
    header('Location: appointments.php');
}
?>
