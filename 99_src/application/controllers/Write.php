<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends Base_Controller {
    
	public function __construct() {
        parent::__construct();
		
		$this->load->model('board_model');
	}

	public function index() {
		$this->load->view('write');
	}

	// 필요한 것만 사용하려고 재정의
	protected function _header() {
		$this->load->view('fragments/head');
	}

	// 필요한 것만 사용하려고 재정의
	protected function _footer() {
		$this->load->view('fragments/tail');
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
