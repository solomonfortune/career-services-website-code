<?php
function getJobListings($query, $location) {
    // Using RapidAPI's JSearch API
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://jsearch.p.rapidapi.com/search?query=".urlencode($query)."&location=".urlencode($location),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: jsearch.p.rapidapi.com",
            "X-RapidAPI-Key: YOUR_API_KEY"
        ]
    ]);
    
    $response = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($response, true);
}
