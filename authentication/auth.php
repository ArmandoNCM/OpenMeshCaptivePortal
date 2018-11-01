<?php
require_once(dirname(__FILE__) . '/../class/Utils.php');
require_once(dirname(__FILE__) . '/../class/Logger.php');

header("Content-Type: text/plain");

// Shared Secret
$secret = constant("SHARED_SECRET");

//Node MAC
$node_mac = Utils::replaceDashWithColon($_GET['node']);

//Device MAC
$client_mac = Utils::replaceDashWithColon($_GET['mac']);

// Signature
$signature = $_GET['ra'];

// Request Type
$type = $_GET['type'];
Logger::log("Authentication Server Received Request of Type: $type\nFrom Client Mac: $client_mac Through AP Node Mac: $node_mac", "message", __FILE__, __LINE__);

$user_download_limit = 2000;
$user_upload_limit = 1000;
$seconds_allowed = 1 * 60;

$authResponse = array();

switch ($type) {
    case 'login':

        /* decode password */
        $username = $_GET['username'];
        $password = Utils::decode_password($authResponse, $_GET['password'], $secret);

        $authResponse['CODE'] = 'ACCEPT';
        $authResponse['SECONDS'] = '60';
        $authResponse['DOWNLOAD'] = '2000';
        $authResponse['UPLOAD'] = '1000';

        break;

    case 'status':

        break;

    case 'acct':

        $downloaded_bytes = (int) $_GET['download'];
        $uploaded_bytes = (int) $_GET['upload'];
        $seconds = (int) $_GET['seconds'];
        $sesion = $_GET['session'];
        $ipv4 = $_GET['ipv4'];

        break;

    case 'logout':

	    Logger::log("Client with Mac: $client_mac has been logged out", "message", __FILE__, __LINE__);
        unset($authResponse['BLOCKED_MSG']);
        $authResponse['CODE'] = "OK";
        break;
};

/* calculate new request authenticator based on answer and request -> send it out */
Utils::calculate_new_ra($authResponse, $signature, $secret);
Utils::print_dictionary($authResponse);
