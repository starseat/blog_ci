<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Base_ApiController {
// class Upload extends Base_Controller {
	public function __construct() {
        parent::__construct();
	}

	public function test() {
		$result_array = [
			'result' => true,
			'code' => 1, 
			'message' => 'test'
		];

		echo json_encode($result_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

}
