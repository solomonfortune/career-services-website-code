<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resource_name = $_POST['resource_name'] ?? '';
    $status = $_POST['status'] ?? 'success';
    $error = $_POST['error'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    try {
        $query = "INSERT INTO resource_downloads (user_id, resource_name, status, error_message, download_date) 
                 VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $user_id, $resource_name, $status, $error);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log("Download log error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to log download']);
    }
}
