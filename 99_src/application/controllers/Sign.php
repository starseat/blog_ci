<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sign extends Base_Controller {

	public function __construct() {
		parent::__construct();

		// $this->load->library('session');
		$this->load->helper('form');
	}

	public function index() {
		$this->in();
	}

	// 로그인
	public function in() {
		$this->load->helper('alert');

		if ($this->input->method() == 'get') {
			if($this->session->userdata('is_login')) {
				return alert('이미 로그인 되어 있습니다.', base_url('/'));
			}
			else {
				return $this->load->view('sign/login');
			}			
		}
		else {
			if ($this->session->userdata('is_login')) {
				return $this->load->view('errors/error_404', array(
					'page_result' => false
				));
			}
			else {
				return $this->_loginAction();
			}
		}
	}	

	// 로그아웃
	public function out() {
		$this->load->helper('alert');
		$this->session->sess_destroy();

		return alert('로그아웃 되었습니다.', base_url('/'));
	}

	// 회원가입
	public function up() {
		
	}

	public function test() {
		$this->load->model('member_model');
		echo $this->member_model->password_encrypt('test123');
	}

	private function _loginAction()
	{
		$this->load->library('form_validation');
		$this->load->helper('alert');

		$this->form_validation->set_rules('userId', 'User ID', 'trim|required|min_length[4]|max_length[12]');
		$this->form_validation->set_rules('userPwd', 'Password', 'trim|required|min_length[4]|max_length[12]');

		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

		if ($this->form_validation->run() == TRUE) {
			$login_data = array(
				'user_id' => $this->input->post('userId', TRUE),
				'password' => $this->input->post('userPwd', TRUE)
			);

			$this->load->model('member_model');
			$login_result = $this->member_model->login($login_data);

			if ($login_result['result']) {
				$session_data = array(
					'is_login' => true,
					'user_id' => $login_result['data']['user_id'],
					'email' => $login_result['data']['email'],
					'name' => $login_result['data']['name'],
					'provider' => $login_result['data']['provider'],
					'remember_me_yn' => $login_result['data']['remember_me_yn'],
					'member_type' => $login_result['data']['member_type']
				);

				$this->session->set_userdata($session_data);

				// 로그인 후에 원래 보던 페이지로 이동하는거 추가 필요
				return alert('로그인에 성공하였습니다.', '/');
			} else {
				return alert($login_result['message'], base_url('/sign/in'));
			}
		} else {
			return alert('ID 또는 비밀번호를 정확히 입력해 주세요.', base_url('/sign/in'));
		}
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
