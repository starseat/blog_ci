<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Api_Controller extends Base_Controller {

	private $RESPONSE_TYPE = 'json';

	public function __construct() {
		parent::__construct();
		
		$this->setHeader();
	}

	// Set Response Header
	public function setHeader() {
		$uris = explode('/', $this->uri->uri_string);
		
		$this->RESPONSE_TYPE = $uris[sizeof($uris) - 1];

		if ($this->RESPONSE_TYPE == 'json') {
			header('Content-type:application/json;charset=UTF-8');
		} else if ($this->RESPONSE_TYPE == 'jsonp') {
			header('Content-type:text/javascript;charset=UTF-8');
		} else {
			header('Content-type:application/json;charset=UTF-8');

			$arr['result'] = false;
			$arr['error']['code'] = 900;
			$arr['error']['message'] = 'invalid response data Type [json or jsonp]';

			echo json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}
	}



	// Set Response Body

	// private function setBody($responseData)
	// {

	// 	if ($this->responseType == "json") {

	// 		echo json_encode($responseData);
	// 	} else if ($this->responseType == "jsonp") {

	// 		$stringJSON = json_encode($responseData);

	// 		echo $_GET['callback'] . "(" . urldecode($stringJSON) . ");";
	// 	}
	// }
}
