<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';
    $filename = $_POST['filename'] ?? '';
    
    // Validate URL (only allow Google Docs URLs)
    if (!filter_var($url, FILTER_VALIDATE_URL) || 
        !str_contains($url, 'docs.google.com')) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid URL']);
        exit;
    }

    try {
        // Initialize cURL session
        $ch = curl_init($url);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Execute cURL session
        $content = curl_exec($ch);
        
        // Check for cURL errors
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        
        // Close cURL session
        curl_close($ch);

        // Send appropriate headers
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: no-cache');
        
        // Output file content
        echo $content;

        // Log successful download
        $user_id = $_SESSION['user_id'] ?? null;
        $query = "INSERT INTO resource_downloads (user_id, resource_name, status, download_date) 
                 VALUES (?, ?, 'success', NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $user_id, $filename);
        $stmt->execute();

    } catch (Exception $e) {
        error_log("Download error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Download failed: ' . $e->getMessage()]);
    }
}
?>
