<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->database();
		$this->load->helper(array('url', 'date'));
	}

	protected function _header() {
		$this->load->view('fragments/head');
		$this->load->view('fragments/header', array('categories' => $this->_category()));
	}

	protected function _footer() {
		$this->load->view('fragments/footer');
		$this->load->view('fragments/tail');
	}

	protected function _category() {
		$this->load->model('category_model');
		return $this->category_model->gets();
	}
}
