<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_picture'];
    
    // Validate file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = "Invalid file type. Please upload a JPG, PNG, or GIF file.";
        header('Location: ../dashboard.php');
        exit();
    }
    
    if ($file['size'] > $max_size) {
        $_SESSION['error'] = "File is too large. Maximum size is 5MB.";
        header('Location: ../dashboard.php');
        exit();
    }
    
    // Create uploads directory if it doesn't exist
    $upload_dir = '../uploads/profile_pictures/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
    $filepath = $upload_dir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Update database with new profile picture path
        $relative_path = 'uploads/profile_pictures/' . $filename;
        $stmt = $conn->prepare("UPDATE users SET profile_pic_path = ? WHERE id = ?");
        $stmt->bind_param("si", $relative_path, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating profile picture in database.";
        }
    } else {
        $_SESSION['error'] = "Error uploading file.";
    }
    
    header('Location: ../dashboard.php');
    exit();
}
