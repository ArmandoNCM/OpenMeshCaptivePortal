<?php

require_once(dirname(__FILE__).'/../constants.php');

class Logger {
    static function log($message, $type = 'message', $file = '-', $line = '-'){
        if(DEBUG == true){
            
            $title = strtoupper($type);
            $start = "\n\n\t\t========================::START-$title::========================\n";
            $ending = "\n\t\t========================::ENDING-$title::========================\n\n";

            $messageFinal = $start . $message . $ending . "\n\tAt (File: $file Line: $line)\n\n";
            trigger_error($messageFinal);
        }
    }
}