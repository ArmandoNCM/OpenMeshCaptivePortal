<?php
$secret = constant('SHARED_SECRET');
$username = 'any_username';
$password = 'any_password'; //base64_encode(hash_hmac('sha256', $username, $secret, true));
// Encrypt Password
$encrypted_password = Utils::encode_password_challenge($password,$node_challenge,$secret);
$redirectUrl = 'http://' . $controller_ip . ':' . $controller_port . '/logon?username=' . urlencode($username) . '&password=' . urlencode($encrypted_password);
// Redirect
Utils::redirect($redirectUrl);
exit();
?>