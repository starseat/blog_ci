<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Base_Controller {

	public function __construct() {
        parent::__construct();
	}

	public function test() {
		log_message('error', '[upload.test] start..');

		$result_array = [
			'result' => true,
			'code' => 1,
			'message' => 'test'
		];

		echo json_encode($result_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	/**
	 * image
	 * 
	 * 이미지 업로드 처리
	 *  - 성공시 /upload/{categoryId}/ 에 이미지 파일 업로드 후 경로 return
	 */
	public function image() {

		// $result_array = [
		// 	'result' => false, 
		// 	'code' => -1, 
		// 	'messsage' => '', 
		// 	'data' => ''
		// ];
		$result_array = array();
		
		if (!$this->session->userdata('is_login')) {
			$result_array = [
				'result' => false,
				'code' => -1,
				'messsage' => 'Not logged in.', 
			];

			echo json_encode($result_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		if ($this->input->method() != 'post') {
			$result_array = [
				'result' => false,
				'code' => -1,
				'messsage' => 'Not allowed type. (Allow only POST method)'
			];

			echo json_encode($result_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			exit();
		}

		// thumbnail 등록 여부 검사
		if ($_FILES['uploadFile']['name'] == '') {
			return 0;
		}
		
		$today = date("Ymd");
		//log_message('error', '[_uploadImage] upload file name : ' . $_FILES['uploadFile']['name']);
		$upload_path = 'uploads' . '/_temp/' . $today . '/';
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

		$retUploadPath = '';		

		if ($this->upload->do_upload('uploadFile')) {
			$uploadResultData = $this->upload->data();

			$uploadImageInfo = array(
				'type' => 'board',
				'name' => $uploadResultData['orig_name'],
				'saved_name' => $uploadResultData['file_name'],
				'upload_path' => '/' . $upload_path,
				'saved_path' => $uploadResultData['file_path']
			);

			// 이미지 정보를 db 에 insert 하는게 무의미함...
			// $this->load->model('image_model');
			// $this->image_model->insertImage($uploadImageInfo);

			$retUploadPath = $uploadImageInfo['upload_path'] . $uploadImageInfo['saved_name'];

			$result_array['result'] = true;
			$result_array['code'] = 0;
			$result_array['message'] = 'success';
			$result_array['data'] = $retUploadPath;

		} else {
			$uploadErrorData = $this->upload->display_errors();
			$result_array['result'] = false;
			$result_array['code'] = -1;
			$result_array['message'] = 'failed';
			$result_array['data'] = $uploadErrorData;
		}

		echo json_encode($result_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

}
