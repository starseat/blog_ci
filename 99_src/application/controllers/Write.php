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

		// /write?seq=3  형식
		// /write/3  으로 가져오자니 upload, insert, update 등 다른것들과 꼬일 수 있으므로 이렇게 함.
		$isModify = false;
		$board_seq = $this->input->get('seq');
		if(!empty($board_seq) && is_numeric($board_seq)) {
			$isModify = true;
		}

		$viewInfo = array('categories' => $this->_category());
		$viewInfo['is_modify'] = $isModify;	
		if($isModify) {
			$this->load->model('board_model');
			$boardData = $this->board_model->getBoardData($board_seq);
			$viewInfo['board_data'] = $boardData;
		}

		$this->load->view('write', $viewInfo);
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

		return $this->_submitBlog('insert');
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

		$writer = $this->input->post('blog_writer', TRUE);
		if(empty($writer)) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		if($writer != $this->session->userdata('user_id')) {
			return $this->load->view('errors/error_404', array(
				'page_result' => false
			));
		}

		return $this->_submitBlog('update');
	}

	private function _submitBlog($submit_type) {

		$this->load->library('form_validation');
		$this->load->helper('alert');

		$this->form_validation->set_rules('blog_seq', 'seq', 'required');
		$this->form_validation->set_rules('blog_category', 'Blog Category', 'required');
		$this->form_validation->set_rules('blog_title', 'Blog Title', 'required|min_length[2]|max_length[64]');
		$this->form_validation->set_rules('blog_viewType', 'Blog View Type', 'required');
		$this->form_validation->set_rules('blog_content', 'Blog Contents', 'required|min_length[1]');

		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

		if (!$this->form_validation->run()) {
			return alert_history_back('글 정보가 올바르지 않습니다.');
		}

		$seved_seq = $this->input->post('blog_seq', TRUE);  // 0: wirte, other: modify
		if($submit_type == 'update') {
			// if(!isset($saved_seq)) {
			// 	return alert_history_back('게시글 번호가 존재하지 않습니다.');
			// }
			if(!is_numeric($seved_seq)) {
				return alert_history_back('게시글 번호가 올바르지 않습니다.');
			}
		} 

		$seved_seq = intVal($seved_seq);
		$category_id = $this->input->post('blog_category', TRUE);
		$title = $this->input->post('blog_title', TRUE);
		$view_type = intVal($this->input->post('blog_viewType', TRUE));
		$content = $this->input->post('blog_content', TRUE);

		$uploadThumbnailSeq = $this->_insertThumbnail($category_id);
		if($uploadThumbnailSeq < 0) {				
			return alert_history_back('썸네일 등록이 실패하였습니다.');
		}

		$boardInfo = array(
			'category_id' => $category_id,
			'title' => $title,
			'view_type' => $view_type,
			'thumbnail_seq' => $uploadThumbnailSeq, 
			'content' => $content
		);

		$resultSubmitId = 0;
		$resultMessage = '';
		$resultUrl = '';

		$this->load->model('board_model');
		if($submit_type == 'update') {
			$boardInfo['seq'] = $seved_seq;

			$resultSubmitId = $this->board_model->updateBoard($boardInfo);

			if ($resultSubmitId > 0) {
				$resultMessage = '블로그 게시글이 수정 되었습니다.';
				$resultUrl = '/blog/view/' . $resultSubmitId;
			} else {
				$resultMessage = '블로그 게시글 수정이 실패하였습니다.';
			}
		}
		else {
			$boardInfo['writer'] = $this->session->userdata('user_id');
			$resultSubmitId = $this->board_model->insertBoard($boardInfo);

			if ($resultSubmitId > 0) {
				$resultMessage = '블로그 게시글이 등록 되었습니다.';
				$resultUrl = '/blog/view/' . $resultSubmitId;
			} else {
				$resultMessage = '블로그 게시글 등록이 실패하였습니다.';
			}
		}

		if ($resultSubmitId > 0) {
			return alert($resultMessage, $resultUrl);
		} else {
			return alert_history_back($resultMessage);
		}
	}

	private function _insertThumbnail($category_id) {
		$uploadThumbnailSeq = 0;

		// thumbnail 등록 여부 검사
		if ($_FILES['blog_thumbnail']['name'] == '') {
			return 0;
		}

		$upload_path = 'uploads/' . $category_id . '/thumbnail/';
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 766, true);
		}

		$upload_config = array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|png|jpg|jpeg',
			'encrypt_name' => TRUE,
			'max_size' => (1024 * 10)  // (kb)
		);

		$this->load->library('upload', $upload_config);

		if ($this->upload->do_upload('blog_thumbnail')) {
			$uploadResultData = $this->upload->data();

			$uploadThumbnailInfo = array(
				'type' => 'thumbnail',
				'name' => $uploadResultData['orig_name'],
				'saved_name' => $uploadResultData['file_name'],
				'upload_path' => '/' . $upload_path,
				'saved_path' => $uploadResultData['file_path']
			);

			$this->load->model('image_model');
			$uploadThumbnailSeq = $this->image_model->insertImage($uploadThumbnailInfo);
		}
		else {
			$uploadErrorData = $this->upload->display_errors();
			$uploadThumbnailSeq = -1;
		}

		return $uploadThumbnailSeq;
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
