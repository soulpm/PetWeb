<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	//require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/ws_basic_autenticate.php");
	
    function setResponse($value,$type){
        switch ($type) {
            case 'GET':
                echo $value;
                break;
            case 'POST':
                $_POST["response"] = $value;
                echo $_POST["response"];
                break;
            case 'PUT':
                echo "";
                break;
            default:
                echo "";
                break;	
        }
        die();
    }

    function validatePostParameters($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for($i=0;$i<count($required_fields);$i++){
                if(!isset($_POST[$required_fields[$i]])){
                    $error_fields = $error_fields.$required_fields[$i].", ";
                }
            }
            if($error_fields!=""){
                $params = substr($error_fields,0,(strlen($error_fields)-2));
                $_POST["response"] =  json_encode(
                array(VarConstantsMaskotaWeb::_ERROR_SERVICE_TITLE=>VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED." ".$params));
                echo $_POST["response"];
                die();
            }
        }
        else{
           echo json_encode(array(VarConstantsMaskotaWeb::_ERROR_SERVICE_TITLE=>VarConstantsMaskotaWeb::_ERROR_GET_SERVICE));     
           die();
        }
    }

    function formatResponse($code,$value){
        
        return 
        json_encode (array("responseCode"=>$code,"data"=>$value));
    }

    function toJSON($data){
        return json_encode($data);
    }

    /*
    function validateRequestParameters($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {$app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }
        foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
        $error = true;
        $error_fields .= $field . ', ';
        }
        }
        
        if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        
        $app->stop();
        }
        }
        */    
?>
















