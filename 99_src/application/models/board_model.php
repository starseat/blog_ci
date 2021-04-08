<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Board_model extends Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function getBoardListByHome() {
		// 메인에는 최신 Post 6개, 인기많은 Post 6개 => 총 12개 뿌림.
		$main_view_count = 6;

		$sql = "
		SELECT r1.* FROM (
			SELECT b1.seq, b1.category_id, c1.category_name, b1.writer, b1.title, b1.thumbnail, b1.view_count, 
			b1.like_count, b1.view_type, DATE_FORMAT(b1.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b1.content, 1, 40) as content 
			FROM tbl_blog_boards b1 INNER JOIN tbl_blog_categories c1 ON b1.category_id = c1.category_id 
			WHERE b1.deleted_at IS NULL 
			ORDER BY b1.created_at DESC LIMIT $main_view_count 
		) r1 
			UNION ALL 
		SELECT r2.* FROM (
			SELECT b2.seq, b2.category_id, c2.category_name, b2.writer, b2.title, b2.thumbnail, b2.view_count, 
			b2.like_count, b2.view_type, DATE_FORMAT(b2.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b2.content, 1, 40) as content 
			FROM tbl_blog_boards b2 INNER JOIN tbl_blog_categories c2 ON b2.category_id = c2.category_id 
			WHERE b2.deleted_at IS NULL 
			ORDER BY b2.view_count DESC LIMIT $main_view_count 
		) r2 
		";

		return $this->db->query($sql)->result_array();
	}

	public function getBoardList($categoryId, $current_page = 1) {
		$total_count = $this->_getBoardTotalCount($categoryId);
		$paging_info = $this->getPagingInfo($current_page, $total_count);

		$sql  = "
			SELECT 
				b.seq, b.category_id, c.category_name, b.writer, b.title, b.thumbnail, b.view_count, 
				b.like_count, b.view_type, DATE_FORMAT(b.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b.content, 1, 40) as content 
				FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.deleted_at IS NULL AND b.category_id = ? 
			ORDER BY b.seq DESC
			LIMIT ?, ?
		";

		return array(
			'board_list' => $this->db->query($sql, array($categoryId, $paging_info['page_db'], $this->ITEM_ROW_COUNT))->result_array(), 
			'page_info' => $paging_info
		);
	}

	private function _getBoardTotalCount($categoryId) {
		$sql = "
			SELECT count(*) as total_count
			FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.deleted_at IS NULL AND b.category_id = ? 
		";

		return intVal($this->db->query($sql, array($categoryId))->row()->total_count);
	}

	public function getBoardListBySearch($search_text, $current_page = 1) {
		$total_count = $this->_getBoardTotalCountBySearch($search_text);
		$paging_info = $this->getPagingInfo($current_page, $total_count);

		$sql  = "
			SELECT 
				b.seq, b.category_id, c.category_name, b.writer, b.title, b.thumbnail, b.view_count, 
				b.like_count, b.view_type, DATE_FORMAT(b.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b.content, 1, 40) as content 
			FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.deleted_at IS NULL 
			  AND (b.title LIKE concat('%', ?, '%') OR b.content LIKE concat('%', ?, '%') )
			ORDER BY b.seq DESC
			LIMIT ?, ?
		";

		return array(
			'board_list' => $this->db->query($sql, array($search_text, $search_text, $paging_info['page_db'], $this->ITEM_ROW_COUNT))->result_array(),
			'page_info' => $paging_info
		);
	}

	private function _getBoardTotalCountBySearch($search_text) {
		$sql = "
			SELECT count(*) as total_count
			FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.deleted_at IS NULL 
			  AND (b.title LIKE concat('%', ?, '%') OR b.content LIKE concat('%', ?, '%') )
		";

		return intVal($this->db->query($sql, array($search_text, $search_text))->row()->total_count);
	}
}
