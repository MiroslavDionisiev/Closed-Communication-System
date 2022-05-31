<?php
    class MessageManager {
        public static function checkForLeakedCredentials($message, $user) {
            $timmedMessage = preg_replace("/\s+/", "", $message);
            
            if (preg_match("/".$user->email."/i", $timmedMessage) != 0) {
                return false;
            }
            if (preg_match("/".$user->name."/i", $timmedMessage) != 0) {
                return false;
            }

            return true;
        }
    }
?>