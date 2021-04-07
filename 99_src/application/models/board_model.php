<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Board_model extends Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function getBoardListByHome() {
		$sql  = "SELECT b.seq, b.category_id, c.category_name, b.writer, b.title, b.thumbnail, b.view_count, ";
		$sql .= "b.like_count, b.view_type, DATE_FORMAT(b.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b.content, 1, 40) as content ";
		$sql .= "FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id ";
		$sql .= "WHERE b.deleted_at IS NULL ";
		$sql .= "ORDER BY b.seq DESC";

		return $this->db->query($sql)->result_array();
	}

	public function getBoardList($categoryId) {
		$sql  = "SELECT b.seq, b.category_id, c.category_name, b.writer, b.title, b.thumbnail, b.view_count, ";
		$sql .= "b.like_count, b.view_type, DATE_FORMAT(b.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b.content, 1, 40) as content ";
		$sql .= "FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id ";
		$sql .= "WHERE b.deleted_at IS NULL ";
		$sql .= "  AND b.category_id = '" . $categoryId . "' ";
		$sql .= "ORDER BY b.seq DESC";

		return $this->db->query($sql)->result_array();
	}
}
