<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function gets() {
		if (!$this->session->userdata('is_login')) {
			echo json_encode(makeResultErrorNotLogin(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		if ($this->input->method() != 'get') {
			echo json_encode(makeResultErrorNotAllowedHttpMethod(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		echo json_encode(makeResultSuccess($this->_categories()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function get($id) {
		if (!$this->session->userdata('is_login')) {
			echo json_encode(makeResultErrorNotLogin(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		if ($this->input->method() != 'get') {
			echo json_encode(makeResultErrorNotAllowedHttpMethod(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		if ( empty($id) ) {
			echo json_encode(makeResultErrorParam(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		echo json_encode(makeResultSuccessOnData($this->category_model->getById($id)), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function parents() {
		if (!$this->session->userdata('is_login')) {
			echo json_encode(makeResultErrorNotLogin(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		if ($this->input->method() != 'get') {
			echo json_encode(makeResultErrorNotAllowedHttpMethod(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		echo json_encode(makeResultSuccessOnData($this->category_model->getParnets()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function add() {
		$result_array = [
			'result' => true,
			'code' => 1,
			'message' => 'api controller'
		];
		echo json_encode($result_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}
	
	public function modify($id) {

	}

	public function delete($id) {

	}
}

?>
