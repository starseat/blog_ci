<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sign extends Base_Controller
{

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		
	}

	// 로그인
	public function in() {
		$this->load->view('sign/login');
	}

	// 로그아웃
	public function out() {

	}

	// 회원가입
	public function up() {

	}

	// 필요한 것만 사용하려고 재정의
	protected function _header()
	{
		$this->load->view('fragments/head');
	}

	// 필요한 것만 사용하려고 재정의
	protected function _footer()
	{
		$this->load->view('fragments/tail', array('is_write' => false));
	}
	/**
	 * 사이트 해더, 푸터 자동 추가
	 */
	public function _remap($method)
	{
		// 해더
		$this->_header();

		if (method_exists($this, $method)) {
			$this->{"${method}"}();
		}

		// 푸터
		$this->_footer();
	}
}
