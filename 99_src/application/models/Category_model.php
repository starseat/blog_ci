<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends Base_Model {

	const VIEW_TYPE_ALL = 0;
	const VIEW_TYPE_FRIEND = 1;
	const VIEW_TYPE_ONLY_ME = 2;
	const VIEW_TYPE_ADMIN = 9;

	public function __construct() {
		parent::__construct();
	}

	public static function getViewTypeName($type) {
		switch(intVal($type)) {
			case Category_model::VIEW_TYPE_ALL: $result = '전체보기'; break;
			case Category_model::VIEW_TYPE_FRIEND: $result = '친구만 보기'; break;
			case Category_model::VIEW_TYPE_ONLY_ME: $result = '나만보기'; break;
			case Category_model::VIEW_TYPE_ADMIN: $result = '관리자용'; break;
			default: $result = $type; break;
		}
		return $result;
	}

	public function gets() {

		$temp_array = null;
		if($this->session->userdata('is_login')) {
			$temp_array = $this->_gets_viewTyps_me();
		}
		else {
			$temp_array = $this->_gets_viewType_all();
		}

		$parent_list = $temp_array['parent'];
		$child_list = $temp_array['child'];
		
		// 자식을 부모에 매핑
		// 왜 이런지는 모르겠지만.. 이렇게 해야되네...
		for ($i = 0; $i < count($parent_list); $i++) {
			$parent = $parent_list[$i];
			$child_temp_list = array();

			foreach ($child_list as $child) {
				if ($child['parent_id'] == $parent['category_id']) {
					array_push($child_temp_list, $child);
				}
			}

			$parent['children'] = $child_temp_list;
			$parent_list[$i] = $parent;
		}

		return $parent_list;
	}

	private function _gets_viewType_all() {		
		$sql  = " 
		SELECT seq, owner_id, category_id, category_name, level, parent_id, view_type, sort_index 
		FROM tbl_blog_categories tbc 
		WHERE deleted_at IS NULL AND level = ? AND view_type = ?
		ORDER BY sort_index, seq
		";

		// 부모 먼저 list 만든 후
		$parent_list = $this->db->query($sql, array(0, Category_model::VIEW_TYPE_ALL))->result_array();

		// 자식 list 가져오기
		$child_list = $this->db->query($sql, array(1, Category_model::VIEW_TYPE_ALL))->result_array();

		return array(
			'parent' => $parent_list, 
			'child' => $child_list
		);
	}

	private function _gets_viewTyps_me() {
		$sql  = "
		SELECT seq, owner_id, category_id, category_name, level, parent_id, view_type, sort_index 
		FROM tbl_blog_categories tbc 
		WHERE deleted_at IS NULL AND level = ? AND (view_type = ? OR (view_type = ? AND owner_id = ? ) )
		ORDER BY sort_index, seq
		";

		// 부모 먼저 list 만든 후
		$parent_list = $this->db->query($sql, array(0, Category_model::VIEW_TYPE_ALL, Category_model::VIEW_TYPE_ONLY_ME, $this->session->userdata('user_id')))->result_array();

		// 자식 list 가져오기
		$child_list = $this->db->query($sql, array(1, Category_model::VIEW_TYPE_ALL, Category_model::VIEW_TYPE_ONLY_ME, $this->session->userdata('user_id')))->result_array();

		return array(
			'parent' => $parent_list,
			'child' => $child_list
		);
	}

	public function getById($categoryId) {
		$sql  = "
			SELECT 
				seq, owner_id, category_id, category_name, level, parent_id, view_type, sort_index, 
				DATE_FORMAT(created_at, '%Y-%m-%d') as created_at 
			FROM tbl_blog_categories tbc 
			WHERE tbc.category_id = ?
		";
		return $this->db->query($sql, array($categoryId))->row();
	}

	public function getParnets() {
		$sql  = "
			SELECT seq, owner_id, category_id, category_name, level, parent_id, view_type, sort_index 
			FROM tbl_blog_categories tbc 
			WHERE deleted_at IS NULL AND level = 0 
			ORDER BY sort_index, seq
		";
		return $this->db->query($sql)->result_array();
	}

	public function insertCategory($categoryInfo) {
		$nextSortIndex = 0;

		$this->db->trans_start();

		if($categoryInfo['parent_id'] == '0') {
			$nextSortIndex = $this->_getLastSortIndex_parent();
		}
		else {
			$nextSortIndex = $this->_getLastSortIndex($categoryInfo['parent_id']);
		}
		$categoryInfo['sort_index'] = $nextSortIndex;

		$categoryInfo['created_at'] = date('Y-m-d H:i:s');
		$categoryInfo['updated_at'] = date('Y-m-d H:i:s');
		
		// $this->db->escape() 
		$queryResult = $this->db->query($this->db->insert_string('tbl_blog_categories', $categoryInfo));

		$this->db->trans_complete();
		
		return $queryResult;
	}

	private function _getLastSortIndex_parent() {
		$sql = "SELECT (ifnull(max(sort_index), 0) + 1) new_sort_index FROM tbl_blog_categories WHERE LEVEL = 0";
		return $this->db->query($sql)->row()->new_sort_index;
	}

	private function _getLastSortIndex($parentId) {
		$sql = "SELECT (ifnull(max(sort_index), 0) + 1) new_sort_index FROM tbl_blog_categories WHERE level = 1 AND parent_id = ?";
		return $this->db->query($sql, array($parentId))->row()->new_sort_index;
	}

	public function getCategoryNaviId($categoryId) {
		// dothome 의 mysql 에서 with 절 안먹음.
		// $sql = "
		// WITH temp_category AS ( SELECT category_id, parent_id, level FROM tbl_blog_categories tbc WHERE category_id = ?)  
		// SELECT (CASE WHEN temp_category.level = 0 THEN temp_category.category_id ELSE temp_category.parent_id END) AS navi_id FROM temp_category
		// ";
		
		// 아래와 같이 수정
		$sql = "
		SELECT (CASE WHEN temp_category.level = 0 THEN temp_category.category_id ELSE temp_category.parent_id END) AS navi_id FROM (
			SELECT tbc.category_id, tbc.parent_id, tbc.level FROM tbl_blog_categories tbc WHERE tbc.category_id = ?
		) temp_category
		";
		
		$query = $this->db->query($sql, array($categoryId));
		$retNaviId = '__empty_navi_id';
		if($query->num_rows() > 0) {
			$retNaviId = $query->row()->navi_id;
		}
		// log_message('blog', '[getCategoryNaviId] retNaviId: '. $retNaviId);
		return $retNaviId;
	}

	public function getCategoryIdByBoardSeq($boardSeq) {
		$sql = "
		SELECT category_id AS navi_id FROM tbl_blog_boards WHERE seq = ? AND deleted_at IS NULL
		";

		$query = $this->db->query($sql, array($boardSeq));
		$retCategoryId = '__empty_category_id';
		if ($query->num_rows() > 0) {
			$retCategoryId = $query->row()->navi_id;
		}
		// log_message('blog', '[getCategoryIdByBoardSeq] retCategoryId: ' . $retCategoryId);
		return $retCategoryId;
	}

}
