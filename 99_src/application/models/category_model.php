<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	public function gets() {
		// 부모 먼저 list 만든 후
		$sql  = "SELECT SEQ, OWNER_ID, CATEGORY_ID, CATEGORY_NAME, LEVEL, PARENT_ID, VIEW_TYPE, SORT_INDEX ";
		$sql .= "FROM TBL_BLOG_CATEGORIES TBC ";
		$sql .= "WHERE DELETED_AT IS NULL AND LEVEL = 0 ";
		$sql .= "ORDER BY SORT_INDEX, SEQ";

		$parent_list = $this->db->query($sql)->result_array();

		// 자식을 부모에 매핑
		$sql  = "SELECT SEQ, OWNER_ID, CATEGORY_ID, CATEGORY_NAME, LEVEL, PARENT_ID, VIEW_TYPE, SORT_INDEX ";
		$sql .= "FROM TBL_BLOG_CATEGORIES TBC ";
		$sql .= "WHERE DELETED_AT IS NULL AND LEVEL = 1 ";
		$sql .= "ORDER BY SORT_INDEX, SEQ";

		$children_list = $this->db->query($sql)->result_array();

		foreach ($parent_list as $parent) {
			$parent['CHILDREN'] = array();

			foreach ($children_list as $child) {
				if ($child['PARENT_ID'] == $parent['CATEGORY_ID']) {
					array_push($parent['CHILDREN'], $child);
					break;
				}
			}
		}
		
		return $parent_list;
	}

}
