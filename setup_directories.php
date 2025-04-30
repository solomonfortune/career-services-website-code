<?php
$upload_path = __DIR__ . '/uploads/profile_pictures';

if (!file_exists($upload_path)) {
    if (mkdir($upload_path, 0755, true)) {
        echo "Successfully created directories at: " . $upload_path;
        
        // Create .htaccess file to protect direct access
        $htaccess_content = "Options -Indexes\nDeny from all\n<FilesMatch '\.(jpg|jpeg|png|gif)$'>\nAllow from all\n</FilesMatch>";
        file_put_contents($upload_path . '/.htaccess', $htaccess_content);
        
        echo "\nCreated .htaccess file for security";
    } else {
        echo "Failed to create directories. Please check permissions.";
    }
} else {
    echo "Directories already exist at: " . $upload_path;
}
