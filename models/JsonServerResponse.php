<?php

class JsonServerResponse{

    // Message types
    public const MESSAGE_FAIL = 0;
    public const MESSAGE_SUCCESSFUL = 1;

    public static function createJsonResponse($messageType,$message,$result){
        $jsonResponse = array("message"=>$message,"result"=>$result);
        switch ($messageType){
            case 0:
                $jsonResponse["messageType"] = "ERROR";
                break;
            case 1:
                $jsonResponse["messageType"] = "SUCCESS";
                break;
        }
        return json_encode($jsonResponse);
    }

}