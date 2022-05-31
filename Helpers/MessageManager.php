<?php
    class MessageManager {
        public static function checkForLeakedCredentials($message, $email, $name) {
            $timmedMessage = preg_replace("/\s+/", "", $message);
            
            if (preg_match("/".$email."/i", $timmedMessage) != 0) {
                return true;
            }
            if (preg_match("/".$name."/i", $timmedMessage) != 0) {
                return true;
            }

            return false;
        }
    }
?>