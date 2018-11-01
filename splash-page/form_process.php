<?php
require_once(dirname(__FILE__) . '/../constants.php');
require_once(dirname(__FILE__) . '/../class/Utils.php');
//General validation
$valid_fields = TRUE;

//Router Signatures
$secret = constant('SHARED_SECRET');

$client_mac = Utils::replaceDashWithColon($_POST['mac']);

$node_mac = Utils::replaceDashWithColon($_POST['node_mac']);

$node_private_ip = $_POST['uamip'];

$node_private_port = $_POST['uamport'];

$node_frame_case = $_POST['res'];

$node_challenge = $_POST['challenge'];

$username = 'any_username';

$password = base64_encode(hash_hmac('sha256', $username, $secret, true));
// Encrypt Password
$encrypted_password = Utils::encode_password_challenge($password,$node_challenge,$secret);
$redirectUrl = 'http://' . $node_private_ip . ':' . $node_private_port . '/logon?username=' . urlencode($username) . '&password=' . urlencode($encrypted_password);
Utils::redirect($redirectUrl);