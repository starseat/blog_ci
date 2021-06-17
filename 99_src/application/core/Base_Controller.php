<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->database();
		$this->load->model('category_model');
		
		$this->load->helper(array('url', 'date', 'result'));
	}

	protected function _header() {
		$categoryId = 'home';

		// view page 에서는 category 를 가져올 수 없으므로 새롭게 구함.
		$pageType = $this->uri->segment(2);
		if($pageType == 'view') {
			$boardSeq = intVal($this->uri->segment(3));
			$categoryId = $this->category_model->getCategoryIdByBoardSeq($boardSeq);
		}
		else {  // if($pageType == 'list')
			if (!empty($this->uri->segment(3))) {
				$categoryId = $this->uri->segment(3);
			}
		}
		
		//log_message('blog', 'categoryId: ' . $categoryId);
		$this->load->view('fragments/head');
		$this->load->view('fragments/header', array('categories' => $this->_categories(), 'navi_id' => $this->_getCategoryNaviId($categoryId)));
	}

	protected function _footer() {
		$this->load->view('fragments/footer');
		$this->load->view('fragments/tail', array('is_write' => false));
	}

	protected function _categories() {
		return $this->category_model->gets();
	}

	protected function _getCategoryNaviId($categoryId) {
		if($categoryId == 'home') {
			return $categoryId;
		}
		return $this->category_model->getCategoryNaviId($categoryId);
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

	/* **************************************************************************************************** */
	/* **************************************************************************************************** */
	/* UTIL */
	/* **************************************************************************************************** */
	/* **************************************************************************************************** */

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

	public function convertUTF8String($str) {
		$enc = mb_detect_encoding($str, array("UTF-8", "EUC-KR", "SJIS"));
		if($str != "UTF-8") {
			$str = iconv($enc, "UTF-8", $str);
		}
		return $str;
	}

	public function SQLFiltering($sql) {
		// 해킹 공격을 대비하기 위한 코드
		$sql = preg_replace("/\s{1,}1\=(.*)+/", "", $sql); // 공백이후 1=1이 있을 경우 제거
		$sql = preg_replace("/\s{1,}(or|and|null|where|limit)/i", " ", $sql); // 공백이후 or, and 등이 있을 경우 제거
		$sql = preg_replace("/[\s\t\'\;\=]+/", "", $sql); // 공백이나 탭 제거, 특수문자 제거
		return $sql;
	}

	function xss_clean($data) {
		// jw add
		//$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

		// Fix &entity\n;
		$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);

		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);

		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do {
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		} while ($old_data !== $data);

		// we are done...
		return $data;
	}

	function historyBack() {
		$prevPage = $_SERVER['HTTP_REFERER'];
		header('location:' . $prevPage);
	}

	function uuidgen() {
		return sprintf(
			'%08x-%04x-%04x-%04x-%04x%08x',
			mt_rand(0, 0xffffffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffffffff)
		);
	}

	function getCharacter() {
		return var_dump(iconv_get_encoding('all'));
	}

	function removeFileExt($file_name) {
		$file_ext = strtolower(substr(strrchr($file_name, "."), 1));
		$fileNameWithoutExt = substr($file_name, 0, strrpos($file_name, "."));
		return $fileNameWithoutExt;
	}

	function getFileExt($file_name) {
		// 1. strrchr함수를 사용해서 확장자 구하기
		$ext = substr(strrchr($file_name, '.'), 1);

		// // 2. strrpos 함수와 substr함수를 사용해서 확장자 구하기
		// $ext = substr($file_name, strrpos($file_name, '.') + 1); 

		// // 3. expload 함수와 end 함수를 사용해서 확장자 구하기
		// end(explode('.', $file_name)); 

		// // 4. preg_replace 함수에 정규식을 대입해서 확장자 구하기
		// $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $file_name);

		// // 5. pathinfo 함수를 사용해서 확장자 구하기
		// $fileinfo = pathinfo($file_name);
		// $ext = $fileinfo['extension'];

		$ext = strtolower($ext);
		return $ext;
	}

	function isIE() {
		// IE 11
		if (stripos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0') !== false) return true;
		// IE 나머지
		if (stripos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) return true;

		return false;
	}

	function isEmpty($value) {
		if (isset($value) && !empty($value) && $value != null && $value != '') {
			return false;
		} else {
			return true;
		}
	}
	
}
