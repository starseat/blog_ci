<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
    
	function __construct() {
        parent::__construct();
		
		$this->load->database();
	}

	public function index() {
		
		$this->_header();

		$this->load->view('main');

		$this->_footer();
	}

	private function _header() {
		$this->load->view('fragments/head');
		$this->load->view('fragments/header', array('categories' => $this->_category()));
	}

	private function _footer() {
		$this->load->view('fragments/footer');
		$this->load->view('fragments/tail');
	}

	private function _category() {
		$this->load->database();
		$this->load->model('category_model');

		return $this->category_model->gets();
	}
	
}
