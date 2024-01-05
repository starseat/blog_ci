<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// header('Content-Type: text/plain; charset=utf-8');

class Write extends Base_Controller {
    
	public function __construct() {
        parent::__construct();

		$this->load->helper('form');
		$this->load->helper('alert');
	}

	public function index() {
		if (!$this->session->userdata('is_login')) {
			return alert('로그아웃 되어 로그인 페이지로 이동합니다.', '/sign/in');
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

		$viewInfo = array('categories' => $this->_categories());
		$viewInfo['is_modify'] = $isModify;	
		if($isModify) {
			$this->load->model('board_model');
			$boardData = $this->board_model->getBoardData($board_seq);
			$viewInfo['board_data'] = $boardData;

			$this->load->model('hashtag_model');
			$tags = $this->hashtag_model->getBoardHashTagsOnlyString($board_seq);
			$viewInfo['board_tags'] = urlencode(json_encode($tags));
		}

		$this->load->view('write', $viewInfo);
	}

	public function insert() {
		if (!$this->session->userdata('is_login')) {
			return alert('로그아웃 되어 로그인 페이지로 이동합니다.', '/sign/in');
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
			return alert('로그아웃 되어 로그인 페이지로 이동합니다.', '/sign/in');
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

	// blog 게시글 저장시
	// 이미지 업로드때는 /upload/temp/{날짜}/{이미지_파일} 형식으로 저장하고
	// 실제 게시글 저장시에는 /upload/{category_id}/{이미지_파일} 형식으로 변경
	//  - 사용 안하는 이미지들은 나중에 crontab 으로 삭제 처리 하여 파일 수 줄이기
	//  - 사용 안하는 기준: /upload/temp 에서 하루전(내지 이틀전) 디렉토리 삭제
	private function _submitBlog($submit_type) {

		$this->load->library('form_validation');

		$this->form_validation->set_rules('blog_seq', 'seq', 'required');
		$this->form_validation->set_rules('blog_category', 'Blog Category', 'required');
		$this->form_validation->set_rules('blog_title', 'Blog Title', 'required|min_length[2]|max_length[128]');
		$this->form_validation->set_rules('blog_viewType', 'Blog View Type', 'required');
		$this->form_validation->set_rules('blog_writeType', 'Blog Write Type', 'required');
		// $this->form_validation->set_rules('blog_content', 'Blog Contents', 'required|min_length[1]');

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
		$write_type = $this->input->post('blog_writeType', TRUE);
		//$content = $this->input->post('blog_content', TRUE);
		$content = $this->input->post('blog_content');  // 내용 내 태그 존재시 attribute 없어져서 xss_clean 제거... xss_clean 처리 추가 필요...
		// log_message('blog', '[_submitBlog] content: ' . $content);

		$uploadThumbnailSeq = $this->_insertThumbnail($category_id);
		if($uploadThumbnailSeq < 0) {				
			return alert_history_back('썸네일 등록이 실패하였습니다.');
		}
		
		$boardInfo = array(
			'category_id' => $category_id,
			'title' => $title,
			'write_type' => $write_type,
			'view_type' => $view_type,
			'thumbnail_seq' => $uploadThumbnailSeq, 
			// 'content' => $this->_changeTempImagePath($category_id, $content)  // _changeTempImagePath 버그가 있어 임시 디렉토리가 아닌 날짜별로 구분해서 저장
			'content' => $content
		);		
		
		$resultSubmitId = 0;
		$resultMessage = '';
		$resultUrl = '';

		//log_message('debug', '[write._submitBlog] saved board info: ' . json_encode($boardInfo));

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

		if ($resultSubmitId == 0) {
			return alert_history_back($resultMessage);
		}

		// blog 정상 등록 되었으니 hashtag 등록
		$tags = $this->input->post('save_tags');
		$arrTags = json_decode($tags, true);
		if(count($arrTags) > 0) {
			$this->load->model('hashtag_model');
			$this->hashtag_model->mapping($resultSubmitId, $arrTags);
		}

		return alert($resultMessage, $resultUrl);
	}

	private function _insertThumbnail($category_id) {
		$uploadThumbnailSeq = 0;

		// thumbnail 등록 여부 검사
		if ($_FILES['blog_thumbnail']['name'] == '') {
			return 0;
		}

		$upload_path = 'uploads/' . $category_id . '/thumbnail/';
		if (!is_dir($upload_path)) {
			mkdir($upload_path, 0755, true);
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

	/**
	 * temp 파일 category 로 이동 및 경로 변경하여 contents 변경
	 * 
	 * [/uploads/_temp/20210726/4b4e4b9da91143ff2d8b6c344b89b282.png]
	 *         -> [/uploads/php/4b4e4b9da91143ff2d8b6c344b89b282.png]
	 * 
	 * 즉 /uploads/_temp/{날짜}/{saved file 명}
	 *   -> /uploads/{category}/{saved file 명}
	 */
	// private function _changeTempImagePath_temp($_categoryId, $_contents) {
	// 	$today = date("Ymd");
	// 	$retContents = str_replace('/uploads/_temp/' . $today, '/uploads/' . $_categoryId, $_contents);

	// 	// 자정에 글을 작성할 수 있으므로 어제 날짜까지 변환
	// 	// $yesterday = date('Ymd', strtotime('-1 day'));
	// 	$yesterday = date('Ymd', $_SERVER['REQUEST_TIME']-86400);
	// 	$retContents = str_replace('/uploads/_temp/' . $yesterday, '/uploads/' . $_categoryId, $retContents);

	// 	return $retContents;
	// }
	private function _changeTempImagePath($_categoryId, $_contents) {
		mb_internal_encoding("UTF-8");
		$retContents = '';

		$_find_keyword = '<img src="';
		$contents_split = explode($_find_keyword, $_contents);
		//log_message('blog', '[write._changeTempImagePath] contents_split : ' . json_encode($contents_split, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		$split_count = count($contents_split);
		for($i=0; $i<$split_count; $i++) {
			$split_temp = $contents_split[$i];
			$temp_dir_prefix = '/uploads/_temp/';
			$check_len = mb_strlen($temp_dir_prefix, 'utf-8');
			// 문자열 길이가 '/uploads/_temp/' 를 포함하지 않는 길이라면 pass
			if(mb_strlen($split_temp, 'utf-8') <= $check_len) {
				$retContents = $retContents . $split_temp;
				continue;
			}
			// 문자열에 '/uploads/_temp/' 가 없으면 pass
			if(mb_strpos($split_temp, $temp_dir_prefix, 0, 'utf-8') === false) {
				$retContents = $retContents . $split_temp;
				continue;
			}

			$check_str = mb_substr($split_temp, 0, $check_len, 'utf-8');
			if(!strcmp($check_str, $temp_dir_prefix)) {  // strcmp: 문자열 같으면 false, 다르면 true
				
				// 이미지 임시 경로 구하기
				$pos = mb_strpos($split_temp, '"', 0, 'utf-8');
				$temp_img_path = mb_substr($split_temp, 0, $pos, 'utf-8');
				
				// 새로운 경로로 변환
				$temp_new_dir_path = '/uploads/' . $_categoryId . '/';
				$temp_new_path = $temp_new_dir_path . mb_substr($temp_img_path, mb_strlen($temp_dir_prefix . '/yyyymmdd', 'utf-8'), mb_strlen($temp_img_path, 'utf-8'), 'utf-8');
				
				// 새로운 경로로 이미지 이동
				$temp_dir_path = mb_substr($temp_new_dir_path, 1, mb_strlen($temp_new_dir_path, 'utf-8'), 'utf-8');  // 맨 앞에 '/' 제거
				if (!is_dir($temp_dir_path)) {
					mkdir($temp_dir_path, 766, true);
				}

				$server_root_path = $_SERVER['DOCUMENT_ROOT'];
				rename($server_root_path . $temp_img_path, $server_root_path . $temp_new_path);

				// 변경된 경로로 블로그 다시 저장
				$replace_contents = str_replace($temp_img_path, $temp_new_path, $split_temp);
				$retContents = $retContents . $_find_keyword . $replace_contents;
			}
			else {
				$retContents = $retContents . $_find_keyword . $split_temp;
			}
		} // end of for($i=0; $i<$split_count; $i++)

		return $retContents;		
	}

	private function _saveHashTags() {

	}

	// public function test() {
	// 	$test_str = '<p>test 1</p><p><img src="/uploads/20210506/_temp/14e529841825e89cc98135f1046a3e9a.PNG" xss=removed></p><p>test 2</p><p><img src="/uploads/20210506/_temp/e56b8bf69369e92c292e9cfc9975d0e2.PNG" xss=removed></p><p>test 3</p><p><img src="/uploads/20210506/_temp/668894dee2291d6c3f44ab984ea8ee13.PNG" xss=removed></p><p>test 4 - url</p><p><img src="https://blog.kakaocdn.net/dn/0mySg/btqCUccOGVk/nQ68nZiNKoIEGNJkooELF1/img.jpg" xss=removed></p><p>test 5</p><p><img src="/uploads/20210506/_temp/b095cfac6bc35092c2481aa21b69b873.png" xss=removed><br></p>';
	// 	$this->_changeTempImagePath('cpp', $test_str);
	// }
	

	// 필요한 것만 사용하려고 재정의
	protected function _header() {
		$summary = array(
			'title' => '글쓰기',
			'category_id' => 'write',
			'thumbnail' => '',
			'url' => '/write'
		);
		$this->load->view('fragments/head', array('summary' => $summary));
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
