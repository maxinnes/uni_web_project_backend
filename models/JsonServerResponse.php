<?php

class JsonServerResponse{

    // Message types
    public const MESSAGE_FAIL = 0;
    public const MESSAGE_SUCCESSFUL = 1;

//    public function __construct($messageType,$message,$result){ // TODO Make it so this can return json responses
//    }

    public static function createJsonResponse($messageType,$message,$result){
        $jsonResponse = array("message"=>$message,"result"=>$result);
        switch ($messageType){
            case 0:
                $jsonResponse[] = array("messageType" => "ERROR");
                break;
            case 1:
                $jsonResponse[] = array("messageType" => "SUCCESS");
                break;
        }

        return json_encode($jsonResponse);
    }

}