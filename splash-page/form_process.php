<?php
require_once(dirname(__FILE__) . '/../constants.php');
require_once(dirname(__FILE__) . '/../class/Utils.php');
require_once(dirname(__FILE__) . '/../class/Logger.php');
require_once(dirname(__FILE__).'/../lib/phpqrcode/qrlib.php');
//General validation
$valid_fields = TRUE;

//Router Signatures

$client_mac = Utils::replaceDashWithColon($_POST['client_mac']);
$node_mac = Utils::replaceDashWithColon($_POST['access_point_mac']);
$controller_ip = $_POST['controller_ip'];
$controller_port = $_POST['controller_port'];
$node_frame_case = $_POST['res'];
$node_challenge = $_POST['challenge'];

$valid_fields = TRUE;

// TODO Check name and email and in case of error, show form with error and retry
$person_first_name = filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_STRING);
$person_last_name = filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_STRING);
//Email Validation
$person_email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

$city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_STRING);

$phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_STRING);
$phone = Tool::remove_non_numeric_characters($phone);

if ($person_email){
    strtolower(trim($person_email));
} else {
    Log::print("Error in the email validation.", "error", __FILE__, __LINE__);
    $valid_fields = FALSE;
}

if ($valid_fields) {

    $dataArray = array(
        'email' => $person_email,
        'phone' => $phone
    );

    $dataJson = json_encode($dataArray);

    // Qr Code generation
    $qrCodeContent = $dataJson;
    $qrCodeName = uniqid(hash('md5', $qrCodeContent));
    $qrCodePath = '/opt/qrCodes/' . $qrCodeName . '.png';
    QRcode::png($qrCodeContent, $qrCodePath); 
    $data = file_get_contents($qrCodePath);
    // unlink($qrCodePath);
    $base64QrCode = base64_encode($data);
    $prefix = 'data:image/png;base64,';
    
    // Creating full POST Body
    $dataArray['city'] = $city;
    $dataArray['firstname'] = $person_first_name;
    $dataArray['lastname'] = $person_last_name;
    $dataArray['mac'] = $client_mac;
    $dataArray['qrCode'] = $base64QrCode;
    $dataArray['trafficSource'] = 'captive-portal';

    $dataJson = json_encode($dataArray);

    $base64QrCode = $prefix . $base64QrCode;
    // TODO consume WS sending information

    $apiUrl = constant('API_URL') . '/exhibition-forms/expo-audi/lead';
    $apiResponse = Tool::perform_http_request('POST', $apiUrl, $dataJson);
    
    if (isset($apiResponse) && array_key_exists('response_code', $apiResponse)){
        if ($apiResponse['response_code'] == 204){

            Log::print("Granting access to person:\n\n$dataJson", 'message', __FILE__, __LINE__);
            require_once(dirname(__FILE__).'/grant_access.php');   
        } else {
            // There was a response error
            Log::print("Lead WS responded with Response HTTP Code: " . $apiResponse['response_code'], 'error', __FILE__, __LINE__);
            Log::print("Lead WS responded with Response Body:\n\n" . $apiResponse['response_body'], 'debug', __FILE__, __LINE__);
        }
    } else {
        // Couldn't consume WS
        Log::print("Could not consume Lead WS", 'error', __FILE__, __LINE__);
    }
}
?>