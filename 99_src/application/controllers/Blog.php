<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
    
	function __construct() {
        parent::__construct();
		
		$this->load->database();
	}

	public function index() {
		
		$this->_header();

		$this->load->model('board_model');
		$this->load->view('index', array(
			// 'page_category_info' => array(
			// 	'category_id' => 'home', 
			// 	'category_name' => 'home'
			// ), 
			'board_list' => $this->board_model->getBoardListByHome())
		);

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
		$this->load->model('category_model');
		return $this->category_model->gets();
	}
	
	public function list($categoryId = 'home') {
		if($categoryId != 'home') {
			$this->load->model('category_model');
			$categoryInfo = $this->category_model->getById($categoryId);

			$this->_header();

			if( !empty($categoryInfo) ) {
				$this->load->model('board_model');
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

			$this->_footer();
		}
		else {
			$this->index();
		}
		
	}

}
