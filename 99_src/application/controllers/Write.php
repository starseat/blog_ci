<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends Base_Controller {
    
	public function __construct() {
        parent::__construct();

		$this->load->helper('form');
		$this->load->model('board_model');
	}

	public function index() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if (!$this->input->method() == 'get') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		$this->load->view('write', array('categories' => $this->_category()));
	}

	public function insert() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if (!$this->input->method() == 'post') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		$this->load->helper('alert');
		
	}

	public function update() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if (!$this->input->method() == 'post') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}
	}

	public function addCategory() {
		$parentCategory = $this->input->post('addCategoryModal_newParent', TRUE);
		$newCategory = $this->input->post('addCategoryModal_newCategory', TRUE);

		echo 'parent category: ' . $parentCategory;
		echo '<br>';
		echo 'new category: ' . $newCategory;
	}


	// 필요한 것만 사용하려고 재정의
	protected function _header() {
		$this->load->view('fragments/head');
	}

	// 필요한 것만 사용하려고 재정의
	protected function _footer() {
		$this->load->view('fragments/tail', array('is_write' => true));
	}
	/**
	 * 사이트 해더, 푸터 자동 추가
	 */
	public function _remap($method) {
		// 해더
		$this->_header();

		if(method_exists($this, $method)) {
			$this->{"${method}"}();
		}

		// 푸터
		$this->_footer();
	}

}
