<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function gets() {
		// 부모 먼저 list 만든 후
		$sql  = "SELECT seq, owner_id, category_id, category_name, level, parent_id, view_type, sort_index ";
		$sql .= "FROM tbl_blog_categories tbc ";
		$sql .= "WHERE deleted_at IS NULL AND level = 0 ";
		$sql .= "ORDER BY sort_index, seq";

		$parent_list = $this->db->query($sql)->result_array();

		// 자식을 부모에 매핑
		$sql  = "SELECT seq, owner_id, category_id, category_name, level, parent_id, view_type, sort_index ";
		$sql .= "FROM tbl_blog_categories tbc ";
		$sql .= "WHERE deleted_at IS NULL AND level = 1 ";
		$sql .= "ORDER BY sort_index, seq";

		$children_list = $this->db->query($sql)->result_array();

		// 왜 이런지는 모르겠지만.. 이렇게 해야되네...
		for ($i = 0; $i < count($parent_list); $i++) {
			$parent = $parent_list[$i];
			$child_temp_list = array();

			foreach ($children_list as $child) {
				if ($child['parent_id'] == $parent['category_id']) {
					array_push($child_temp_list, $child);
				}
			}

			$parent['children'] = $child_temp_list;
			$parent_list[$i] = $parent;
		}

		return $parent_list;
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

	public function insertCategory($categoryInfo) {
		$nextSortIndex = 0;

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

		return $this->db->query($this->db->insert_string('tbl_blog_categories', $categoryInfo));
	}

	private function _getLastSortIndex_parent() {
		$sql = "SELECT (ifnull(max(sort_index), 0) + 1) new_sort_index FROM tbl_blog_categories WHERE LEVEL = 0";
		return $this->db->query($sql)->row()->new_sort_index;
	}

	private function _getLastSortIndex($parentId) {
		$sql = "SELECT (ifnull(max(sort_index), 0) + 1) new_sort_index FROM tbl_blog_categories WHERE LEVEL = 1 WHERE parent_id = ?";
		return $this->db->query($sql, array('parent_id', $parentId))->row()->new_sort_index;
	}

}
