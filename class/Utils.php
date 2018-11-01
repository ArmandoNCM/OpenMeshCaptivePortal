<?php

require_once(dirname(__FILE__) . '/../constants.php');
require_once(dirname(__FILE__) . '/Logger.php');

class Utils
{
    public static function replaceDashWithColon($string = null)
    {
        $var_1 = array('-');

        $var_2 = array(':');

        $string_final = str_ireplace($var_1, $var_2, $string);

        return $string_final;
    }

    // This method will perform an action/method through HTTP/API calls
    // Parameter description:
    // Method= POST, PUT, GET etc
    // Data= array("param" => "value") ==> index.php?param=value
    public static function perform_http_request($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ));

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $responseBody = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        
        $err = curl_error($curl);

        $response = FALSE;
        if ($err) {
            Logger::log("cURL Error #: " . json_encode($err), "message", __FILE__, __LINE__);
        } else {
            $response = array(
                'responseBody' => $responseBody,
                'responseCode' => $responseCode
            );
        }
        curl_close($curl);

        return $response;
    }

    /**
     * encode_password_challenge - encode plain text password to ascii string
     * @plain: plain password
     * @challenge: challenge from the node
     * @secret: Shared secret between node and server
     *
     * Returns encoded password or FALSE on error
     */
    public static function encode_password_challenge($plain, $challenge, $secret)
    {
        if ((strlen($challenge) % 2) != 0 ||
            strlen($challenge) == 0)
            return FALSE;

        $hexchall = hex2bin($challenge);
        if ($hexchall === FALSE)
            return FALSE;

        if (strlen($secret) > 0) {
            $crypt_secret = md5($hexchall . $secret, TRUE);
            $len_secret = 16;
        } else {
            $crypt_secret = $hexchall;
            $len_secret = strlen($hexchall);
        }

        /* simulate C style \0 terminated string */
        $plain .= "\x00";
        $crypted = '';
        for ($i = 0; $i < strlen($plain); $i++)
            $crypted .= $plain[$i] ^ $crypt_secret[$i % $len_secret];

        $extra_bytes = rand(0, 16);
        for ($i = 0; $i < $extra_bytes; $i++)
            $crypted .= chr(rand(0, 255));

        return bin2hex($crypted);
    }

    /* WARNING better use http_redirect from pecl_http for this */
    public static function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
    }

    /**
     * print_dictionary - Print dictionary as encoded key-value pairs
     * @dict: Dictionary to print
     */
    public static function print_dictionary($dict)
    {
        foreach ($dict as $key => $value) {
            echo '"', rawurlencode($key), '" "', rawurlencode($value), "\"\n";
        }
    }

    /**
     * calculate_new_ra - calculate new request authenticator based on old ra, code
     *  and secret
     * @dict: Dictionary containing old ra and code. new ra is directly stored in it
     * @secret: Shared secret between node and server
     */
    public static function calculate_new_ra(&$dict, $currentSignature, $secret)
    {
        $code = $dict['CODE'];
        $dict['RA'] = hash('md5', $code . $currentSignature . $secret);
    }

    /**
     * decode_password - decode encoded password to ascii string
     * @dict: dictionary containing request RA
     * @encoded: The encoded password
     * @secret: Shared secret between node and server
     *
     * Returns decoded password or FALSE on error
     */
    public static function decode_password($dict, $encoded, $secret)
    {
        if (!array_key_exists('RA', $dict))
            return FALSE;

        if (strlen($dict['RA']) != 32)
            return FALSE;

        $ra = hex2bin($dict['RA']);
        if ($ra === FALSE)
            return FALSE;

        if ((strlen($encoded) % 32) != 0)
            return FALSE;

        $bincoded = hex2bin($encoded);

        $password = "";
        $last_result = $ra;

        for ($i = 0; $i < strlen($bincoded); $i += 16) {
            $key = hash('md5', $secret . $last_result, TRUE);
            for ($j = 0; $j < 16; $j++)
                $password .= $key[$j] ^ $bincoded[$i + $j];
            $last_result = substr($bincoded, $i, 16);
        }

        $j = 0;
        for ($i = strlen($password); $i > 0; $i--) {
            if ($password[$i - 1] != "\x00")
                break;
            else
                $j++;
        }

        if ($j > 0) {
            $password = substr($password, 0, strlen($password) - $j);
        }

        return $password;
    }

    /**
     * validate_login - check if the login was valid for tos page
     *
     * Returns TRUE for valid logins, FALSE on errors
     */
    public static function validate_login($username, $password)
    {
        require_once(dirname(__FILE__) . '/' . '../../../config.php');
        $token_ttl = constant("OPENMESH_TOKEN_VALID_DURATION");
        $secret = constant('SHARED_SECRET');

        /* generate signature and compare with the password */
        $signature = base64_encode(hash_hmac('sha256', $username, $secret, true));
        
        return ($signature == $password);
    }

}
