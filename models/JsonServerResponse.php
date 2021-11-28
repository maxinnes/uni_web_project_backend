<?php

class JsonServerResponse{

    // Message types
    public const MESSAGE_FAIL = 0;
    public const MESSAGE_SUCCESSFUL = 1;

    public static function createJsonResponse($messageType,$message,$result = array()){
        $jsonResponse = array("message"=>$message,"result"=>$result);
        switch ($messageType){
            case self::MESSAGE_FAIL:
                $jsonResponse["messageType"] = "ERROR";
                break;
            case self::MESSAGE_SUCCESSFUL:
                $jsonResponse["messageType"] = "SUCCESS";
                break;
        }
        return json_encode($jsonResponse);
    }

}