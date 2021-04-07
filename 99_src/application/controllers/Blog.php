<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends Base_Controller {
    
	public function __construct() {
        parent::__construct();
		
		$this->load->model('board_model');
	}

	public function index() {

		$this->load->view('index', array(
			// 'page_category_info' => array(
			// 	'category_id' => 'home', 
			// 	'category_name' => 'home'
			// ), 
			'board_list' => $this->board_model->getBoardListByHome())
		);
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
