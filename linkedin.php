<?php
function getLinkedInAuthUrl() {
    $client_id = "YOUR_LINKEDIN_CLIENT_ID";
    $redirect_uri = "YOUR_REDIRECT_URI";
    $scope = "r_liteprofile r_emailaddress";
    
    return "https://www.linkedin.com/oauth/v2/authorization?" . http_build_query([
        'response_type' => 'code',
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'scope' => $scope
    ]);
}

function getLinkedInToken($code) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://www.linkedin.com/oauth/v2/accessToken",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => 'YOUR_CLIENT_ID',
            'client_secret' => 'YOUR_CLIENT_SECRET',
            'redirect_uri' => 'YOUR_REDIRECT_URI'
        ])
    ]);
    
    $response = curl_exec($curl);
    curl_close($curl);
    
    return json_decode($response, true);
}
