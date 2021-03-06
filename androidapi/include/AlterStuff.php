<?php

/**
 * Created by PhpStorm.
 * User: moncifbounif
 * Date: 15/03/2016
 * Time: 15:07
 */
require_once '../include/DbHandler.php';

 class AlterStuff{

 	/**
     * Echoing json response to client
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    function echoRespnse($status_code, $response) {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json');

        echo json_encode(array('response' => $response ));
    }

	/**
	 * Validating email address
	 */
	function validateEmail($email) {
	    $app = \Slim\Slim::getInstance();
	    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	        $response["error"] = true;
	        $response["message"] = 'Adresse mail non valide';
	        $this->echoRespnse(400, $response);
	        $app->stop();
	    }
	}

	/**
	 * Verifying required params posted or not
	 */
	function verifyRequiredParams($required_fields) {
	    $error = false;
	    $error_fields = "";
	    $request_params = array();
	    $request_params = $_REQUEST;
	    // Handling PUT request params
	    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
	        $app = \Slim\Slim::getInstance();
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
	        $response["message"] = 'Champ(s) ' . substr($error_fields, 0, -2) . ' manquant (s)';
	        $this->echoRespnse(400, $response);
	        $app->stop();
	    }
	}
 }