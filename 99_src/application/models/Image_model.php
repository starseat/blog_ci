<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image_model extends Base_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertImage($imageInfo) {
		/*
		$imageInfo['target_seq'] = // board 등 해당 seq
		$imageInfo['type'] = 'thumbnail' // 등록될 이미지 타입
		$imageInfo['name'] = // 이미지 원래 이름  // 'ci4.PNG'
		$imageInfo['saved_name'] = // 저장 이름  // fa5dd7b1cb713d3782ab534ed427391d.PNG
		$imageInfo['upload_path'] = // 저장 경로  // 'C:/workspace/personal/php/blog_ci/99_src/uploads/test/thumbnail/'
		*/
		$imageInfo['created_at'] = date('Y-m-d H:i:s');
		$imageInfo['updated_at'] = date('Y-m-d H:i:s');

		$this->db->trans_start();
		// $this->db->escape() 
		$insertResult = $this->db->query($this->db->insert_string('tbl_blog_images', $imageInfo));
		$newImageSeq = 0;
		if($insertResult) {
			$newImageSeq = intVal($this->db->insert_id());
		}
		$this->db->trans_complete();

		return $newImageSeq;
	}
}
