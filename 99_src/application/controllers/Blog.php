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
		if($categoryId != 'home') {
			$this->load->model('category_model');
			$categoryInfo = $this->category_model->getById($categoryId);

			if( !empty($categoryInfo) ) {
				$this->load->view(
					'index',
					array(
						'page_result' => true, 
						'category_info' => $categoryInfo, 
						'board_list' => $this->board_model->getBoardList($categoryId)
					)
				);			
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
