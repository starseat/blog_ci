<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->database();
		$this->load->helper(array('url', 'date'));
	}

	protected function _header() {
		$this->load->view('fragments/head');
		$this->load->view('fragments/header', array('categories' => $this->_category()));
	}

	protected function _footer() {
		$this->load->view('fragments/footer');
		$this->load->view('fragments/tail', array('is_write' => false));
	}

	protected function _category() {
		$this->load->model('category_model');
		return $this->category_model->gets();
	}

	/**
	 * url 중 키값을 구분하여 값 가져오기
	 * 
	 * @param Array $url : segment_explode 한 url 값
	 * @param String $key : 가져오라는 값의 key
	 * @return String 가져오려는 값
	 */
	public function url_explode($url, $key) {
		$cnt = count($url);
		for($i=0; $i<$cnt; $i++) {
			if($url[$i] == $key) {
				return $url[$i + 1];
			}
		}
	}

	/**
	 * URL의 '/'를 배열로 바꾼다.
	 * 
	 * @param String $seg : 대상이 되는 문자열
	 * @return String[]
	 */
	public function segment_explode($seg) {
		// 세그먼트 앞뒤 '/' 제거 후 uri 를 배열로 변환
		$len = strlen($seg);
		if(substr($seg, 0, 1) == '/') {
			$seg = substr($seg, 1, $len);
		}
		
		$len = strlen($seg);
		if(substr($seg, -1) == '/') { 
			$seg = substr($seg, 0, $len-1);
		}
		
		return explode('/', $seg);
	}
}
