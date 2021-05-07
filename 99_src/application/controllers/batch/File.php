<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends Base_Controller {
	public function __construct() {
        parent::__construct();
	}

	/**
	 * 2일전 임시 이미지 디렉토리 삭제
	 */
	public function deleteTempImageDirectory() {
		// $yesterday = date('Ymd', strtotime('-2 day'));
		$yesterday = date('Ymd', $_SERVER['REQUEST_TIME'] - (86400 * 2));  // 이게 좀 더 빠름

		$server_root_path = $_SERVER['DOCUMENT_ROOT'];
		$target_path = '/uploads' . '/_temp/' . $yesterday . '/';

		$delete_dir = $server_root_path . $target_path;
		if(is_dir($delete_dir)) {
			log_message('blog', 'delete upload temp directory. delete target date: ' . $yesterday);
			rmdir($delete_dir);
		}
	}

}
