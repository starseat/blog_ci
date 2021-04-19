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
		$newCategoryId = $this->input->post('addCategoryModal_newCategoryId', TRUE);
		$newCategoryViewType = $this->input->post('addCategoryModal_newCategoryViewType', TRUE);
		$newCategoryName = $this->input->post('addCategoryModal_newCategoryName', TRUE);

		$categoryInfo = array(
			'owner_id' => $this->session->userdata('user_id'),
			'category_id' => $newCategoryId,
			'category_name' => $newCategoryName, 
			'view_type' => $newCategoryViewType, 
			'level' => ($parentCategory != '0') ? 1 : 0,
			'parent_id' => $parentCategory
		);

		$this->load->model('category_model');
		$insertResult = $this->category_model->insertCategory($categoryInfo);

		$this->load->helper('alert');

		$result_message = '';
		$result_url = '';
		if($insertResult == 1) {
			$result_message = '카테고리가 추가되었습니다.';
			$result_url = '/write';
		}
		else {
			$result_message = '카테고리를 추가하지 못하였습니다.';
			$result_url = '/write';
		}

		return alert($result_message, $result_url);		
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
