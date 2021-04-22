<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends Base_Controller {
    
	public function __construct() {
        parent::__construct();

		$this->load->helper('form');
	}

	public function index() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if ($this->input->method() != 'get') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		$this->load->view('write', array('categories' => $this->_category()));
	}

	public function upload() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if ($this->input->method() != 'post') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		// $this->uri->segment(0) :: index.php 또는 공백
		// $this->uri->segment(1) :: write
		// $this->uri->segment(2) :: upload
		// $this->uri->segment(3) :: image

		$uploadType = $this->uri->segment(3);

		if($uploadType == 'image') {
			return $this->_uploadImage();
		}


		$this->load->helper('alert');
		return alert('잘못된 업로드 요청입니다.', '/write');
	}


	public function insert() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if ($this->input->method() != 'post') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		$this->load->library('form_validation');
		$this->load->helper('alert');

		$this->form_validation->set_rules('blog_category', 'Blog Category', 'required');
		$this->form_validation->set_rules('blog_title', 'Blog Title', 'required|min_length[2]|max_length[64]');
		$this->form_validation->set_rules('blog_viewType', 'Blog View Type', 'required');
		// $this->form_validation->set_rules('blog_thumbnail', 'Blog Thumbnail', 'required');		
		$this->form_validation->set_rules('blog_content', 'Blog Contents', 'required|min_length[1]');

		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

		if ($this->form_validation->run()) {

			$category_id = $this->input->post('blog_category', TRUE);
			$title = $this->input->post('blog_title', TRUE);
			//$thumbnail = $this->input->post('blog_thumbnail', TRUE);
			$view_type = $this->input->post('blog_title', TRUE);
			$content = $this->input->post('blog_content', TRUE);

			$boardInfo = array(
				'writer' => $this->session->userdata('user_id'),
				'category_id' => $category_id,
				'title' => $title,
				//'thumbnail' => $thumbnail,
				'view_type' => $view_type,
				'content' => $content
			);

			$this->load->model('board_model');
			$resultInsertId = $this->board_model->insertBoard($boardInfo);

			if($resultInsertId > 0) {
				return alert('블로그 게시글이 등록 되었습니다.', '/blog/view/' . $resultInsertId);
			}
			else {
				return alert_history_back('블로그 게시글 등록이 실패하였습니다.');
			}
		} // end of if ($this->form_validation->run())

		return alert_history_back('글 정보가 올바르지 않습니다.');
	}

	public function update() {
		if (!$this->session->userdata('is_login')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if ($this->input->method() != 'post') {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

	}

	public function addCategory() {
		$this->load->library('form_validation');
		$this->load->helper('alert');

		$this->form_validation->set_rules('addCategoryModal_newParent', 'Parent Category ID', 'required');
		$this->form_validation->set_rules('addCategoryModal_newCategoryId', 'New Category ID', 'trim|required|min_length[2]|max_length[12]|alpha');
		$this->form_validation->set_rules('addCategoryModal_newCategoryViewType', 'New Category View Type', 'required');
		$this->form_validation->set_rules('addCategoryModal_newCategoryName', 'New Category Name', 'required|min_length[2]|max_length[32]');

		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

		$result_message = '';
		$result_url = '';

		if ($this->form_validation->run()) {
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

			if ($insertResult == 1) {
				$result_message = '카테고리가 추가되었습니다.';
				$result_url = '/write';
			} else {
				$result_message = '카테고리를 추가하지 못하였습니다.';
				$result_url = '/write';
			}
		}
		else {
			$result_message = '카테고리 정보가 올바르지 않습니다.';
			$result_url = '/write';
		}		

		return alert($result_message, $result_url);
	}

	private function _uploadImage() {

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
