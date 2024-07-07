<?php

// function to format the number
function replaceCountryCode($phoneNumber) {
    // Define the pattern to search for
    $pattern = '/^\+233\./';

    // Define the replacement string
    $replacement = '0';

    // Replace 0 with 233
    $newPhoneNumber = preg_replace($pattern, $replacement, $phoneNumber);

    // Replace any spaces
    $output = preg_replace('/\s+/', '', $newPhoneNumber);

    return $output;
}


// function to send the SMS
function send_sms_alert($userId, $hookName, $message) {
    // Get configuration
    $gatewayUrl = 'https://uellosend.com/quicksend/';
    $api_key = get_query_val('tbladdonmodules', 'value', ['module' => 'uellosend_sms_alerts', 'setting' => 'api_key']);
    $sender_id = get_query_val('tbladdonmodules', 'value', ['module' => 'uellosend_sms_alerts', 'setting' => 'sender_id']);

    // Get user phone number
    $result = select_query('tblclients', 'phonenumber', ['id' => $userId]);
    $data = mysql_fetch_array($result);
    $phoneNumber = $data['phonenumber'];

    $prefix = '+233';
    $string = $phoneNumber;
    
    
    //only send messages if the number is Ghanaian
    if(strpos($string, $prefix) === 0){

    // Perform the replacement
    $newPhoneNumber = replaceCountryCode($phoneNumber);
	

    // Send SMS
   // $url = $gatewayUrl . "?sender_id=" . urlencode($sender_id) . "&recipient=" . $newPhoneNumber . "&message=" . urlencode($message);

    $payload =  json_encode(["api_key"=>$api_key, "sender_id"=>$sender_id, 
    "message"=>$message, "recipient"=>$newPhoneNumber]);

    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $gatewayUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $response = curl_exec($ch);

    $error = curl_error($ch);

    curl_close($ch);

    $payload = json_decode($response,true);
    
    $status = strtolower($payload["status"]);

    logModuleCall('uellosend_sms_alerts', $hookName, $gatewayUrl, $response);
    logActivity("SMS Alert Sent for hook: " . $hookName . " with status: ".$status);

    }
    
}

// function to send the SMS
function send_admin_alert($hookName, $msg) {
    // Get configuration
    $gatewayUrl = 'https://uellosend.com/quicksend/';

    $api_key = get_query_val('tbladdonmodules', 'value', ['module' => 'uellosend_sms_alerts', 'setting' => 'api_key']);
    $sender_id = get_query_val('tbladdonmodules', 'value', ['module' => 'uellosend_sms_alerts', 'setting' => 'sender_id']);
    $phone_num = get_query_val('tbladdonmodules', 'value', ['module' => 'uellosend_sms_alerts', 'setting' => 'admin_phones']);
	$site_url = get_query_val('tbladdonmodules', 'value', ['module' => 'uellosend_sms_alerts', 'setting' => 'site_url']);
    $phones = explode(",",$phone_num);
	$message = $msg." on your site ". $site_url;
	
    for($i =0; $i < sizeof($phones); $i++){
        $phoneNumber = $phones[$i];
        $prefix = '+233';
        $string = $phoneNumber; 
        
        //only send messages if the number is Ghanaian
        if(strpos($string, $prefix) === 0){
            
            // Perform the replacement
            $newPhoneNumber = replaceCountryCode($phoneNumber);


            // Send SMS
            //$url = $gatewayUrl . "?sender_id=" . urlencode($sender_id) . "&recipient=" . $newPhoneNumber . "&message=" . urlencode($message);

            $payload =  json_encode(["api_key"=>$api_key, "sender_id"=>$sender_id, 
            "message"=>$message, "recipient"=>$newPhoneNumber]);

             
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $gatewayUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));


            $response = curl_exec($ch);

            $error = curl_error($ch);

            curl_close($ch);

            $payload = json_decode($response,true);

            $status = strtolower($payload["status"]);
            logModuleCall('uellosend_sms_alerts', $hookName, $gatewayUrl, $response);

            logActivity("SMS Alert Sent for hook: " . $hookName . " with status: ".$status);

        }
    }
    
}
?>