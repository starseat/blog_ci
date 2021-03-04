<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model {
	function __construct() {
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

}
