<?php

//se cargan los archivos requeridos
require_once(dirname(__FILE__) . '/../class/Utils.php');
require_once(dirname(__FILE__) . '/../class/Logger.php');

$browserData = get_browser(NULL, TRUE);
$browser = $browserData['browser'];
$platform = $browserData['platform'];
Logger::log("Browser and Platform: $browser & $platform", "info", __FILE__, __LINE__);

//'res' parameter specifies the particular operation the server is being asked to carry out.
$frame_case = $_GET['res'];

$access_point_mac = Utils::replaceDashWithColon($_GET['called']);

$client_mac = Utils::replaceDashWithColon($_GET['mac']);

Logger::log("Frame received from Auth Server: $frame_case", "info", __FILE__, __LINE__);

switch ($frame_case) {

    case "logoff":
        //Variables to render in the HTML Captive Portal Template
        break;

    case "success":
        //Variables to render in the HTML Captive Portal Template
        break;

    case "failed":
        // Authentication failed, try to log in again
    case "notyet":

        // User's originally requested url
        $original_user_url = $_GET['userurl'];

        // AP's Private IP Address
        $controller_ip = $_GET['uamip'];

        // AP's Port used in conjunction with the Private Address obtained in the previous line
        $controller_port = $_GET['uamport'];

        // Challenge
        $node_challenge = $_GET['challenge'];

        // Hidden fields to be sent in the login form
        $hidden_fields_array = array(
            'client_mac' => $client_mac,
            'access_point_mac' => $access_point_mac,
            'controller_ip' => $controller_ip,
            'controller_port' => $controller_port,
            'res' => $frame_case,
            'challenge' => $node_challenge
        );

        if ($platform == 'Android'){
            $hidden_fields_array['open_external_browser'] = TRUE;
        }

        require_once(dirname(__FILE__) . '/../splash-page/login_form.php');
        
        break;

    default:
        http_response_code(400);
        exit();
}