<?php
function parseResume($file) {
    // Using Affinda Resume Parser API
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.affinda.com/v3/documents",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer YOUR_AFFINDA_API_KEY",
            "Content-Type: multipart/form-data"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'file' => new CURLFILE($file)
        ]
    ]);
    
    $response = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($response, true);
}
