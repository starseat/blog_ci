<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends Base_Controller {
    
	public function __construct() {
        parent::__construct();
		
		$this->load->model('board_model');

		// view page 에서 delete button 할때 필요
		$this->load->helper('form');
	}

	public function index() {

		$this->load->view('index', array(
			// 'page_category_info' => array(
			// 	'category_id' => 'home', 
			// 	'category_name' => 'home'
			// ), 
			'page_type' => 'home', 
			'board_list' => $this->board_model->getBoardListByHome(), 
		));
	}	
	
	public function list($categoryId = 'home') {

		// parameter 로 받은 $categoryId 가 안되서 segment 로 변경함.

		// url 이 http://localhost:81/blog/list/cpp/2 일 경우
		// $this->uri->segment(0) :: index.php 또는 공백
		// $this->uri->segment(1) :: blog
		// $this->uri->segment(2) :: list
		// $this->uri->segment(3) :: cpp
		// $this->uri->segment(4) :: 2

		if(!empty($this->uri->segment(3))) {
			$categoryId = $this->uri->segment(3);
		}

		$current_page = 1;
		if (!empty($this->uri->segment(4))) {
			$current_page = intVal($this->uri->segment(4));
		}

		if($categoryId != 'home') {
			$this->load->model('category_model');
			$categoryInfo = $this->category_model->getById($categoryId);

			if( !empty($categoryInfo) ) {
				$boardListData = $this->board_model->getBoardList($categoryId, $current_page);
				$boardListData['page_result'] = true;
				$boardListData['page_type'] = 'category';
				$boardListData['category_info'] = $categoryInfo;

				$this->load->view('index', $boardListData);
			}
			else {
				$this->load->view(
					'errors/not_found_category',
					array(
						'page_result' => false, 
					)
				);
			}
		}
		else {
			$this->index();
		}		
	}

	public function search() {
		$search_text = $this->input->get('search_text');

		$current_page = 1;
		if (!empty($this->uri->segment(3))) {
			$current_page = intVal($this->uri->segment(3));
		}

		$boardListData = $this->board_model->getBoardListBySearch($search_text, $current_page);
		$boardListData['page_result'] = true;
		$boardListData['page_type'] = 'search';
		$boardListData['search_text'] = $search_text;

		$this->load->view('index', $boardListData);
	}

	public function view($board_seq = 0) {
		if(empty($this->uri->segment(3))) {
			// 잘못된 접근
			return $this->load->view('errors/error_404', array(
					'page_result' => false
				)
			);
		}

		$board_seq = $this->uri->segment(3);

		if(!is_numeric($board_seq)) {
			// 잘못된 접근
			return $this->load->view('errors/error_404', array(
					'page_result' => false
				)
			);
		}

		$boardData = $this->board_model->getBoardData($board_seq);

		if(is_null($boardData) || empty($boardData)) {
			return $this->load->view('errors/error_404', array(
					'page_result' => false
				)
			);
		}

		$this->board_model->plusViewCount($board_seq, $boardData['category_id']);

		//var_dump($boardData);
		return $this->load->view('view', array(
					'page_result' => true, 
					'board_data' => $boardData, 
					'prev_data' => $this->board_model->getPrevBoardData($board_seq), 
					'next_data' => $this->board_model->getNextBoardData($board_seq), 
				)
			);
	}

	public function delete() {
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

		if(empty($this->uri->segment(3))) {
			// 잘못된 접근
			return $this->load->view('errors/error_404', array(
					'page_result' => false
				)
			);
		}

		$board_seq = $this->uri->segment(3);

		if(!is_numeric($board_seq)) {
			// 잘못된 접근
			return $this->load->view('errors/error_404', array(
					'page_result' => false
				)
			);
		}

		$boardData = $this->board_model->getBoardData($board_seq);
		$deleteResult = $this->board_model->deleteBoard($board_seq);

		$resultUrl = '';
		$resultMessage = '';
		if($deleteResult) {
			$resultUrl = '/blog/list/' . $boardData['category_id'];
			$resultMessage = '게시글이 삭제되었습니다.';
		}
		else {
			$resultUrl = '/blog/view/' . $board_seq;
			$resultMessage = '게시글 삭제가 실패하였습니다.';
		}

		$this->load->helper('alert');
		return alert($resultMessage, $resultUrl);
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
