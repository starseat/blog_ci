<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Board_model extends Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function getBoardListByHome() {
		// 최신 Post 12개
		$main_view_count = 12;

		$sql = "
		SELECT b1.seq, b1.category_id, c1.category_name, b1.writer, b1.title, b1.view_count, FN_GET_THUMBNAIL(b1.thumbnail_seq) thumbnail, 
			b1.like_count, b1.view_type, DATE_FORMAT(b1.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b1.content, 1, 40) as content 
			FROM tbl_blog_boards b1 INNER JOIN tbl_blog_categories c1 ON b1.category_id = c1.category_id 
			WHERE b1.deleted_at IS NULL AND b1.view_type = ?
			ORDER BY b1.created_at DESC LIMIT ?
		";

		return $this->db->query($sql, array(Category_model::VIEW_TYPE_ALL, $main_view_count))->result_array();
	}

	public function getBoardListByHome_bak() {
		// 메인에는 최신 Post 6개, 인기많은 Post 6개 => 총 12개 뿌림.
		$main_view_count = 6;

		$sql = "
		SELECT r1.* FROM (
			SELECT b1.seq, b1.category_id, c1.category_name, b1.writer, b1.title, b1.view_count, FN_GET_THUMBNAIL(b1.thumbnail_seq) thumbnail, 
			b1.like_count, b1.view_type, DATE_FORMAT(b1.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b1.content, 1, 40) as content 
			FROM tbl_blog_boards b1 INNER JOIN tbl_blog_categories c1 ON b1.category_id = c1.category_id 
			WHERE b1.deleted_at IS NULL 
			ORDER BY b1.created_at DESC LIMIT $main_view_count 
		) r1 
			UNION ALL 
		SELECT r2.* FROM (
			SELECT b2.seq, b2.category_id, c2.category_name, b2.writer, b2.title, b2.view_count, FN_GET_THUMBNAIL(b2.thumbnail_seq) thumbnail, 
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
				b.seq, b.category_id, c.category_name, b.writer, b.title,b.view_count, FN_GET_THUMBNAIL(b.thumbnail_seq) thumbnail, 
				b.like_count, b.view_type, DATE_FORMAT(b.created_at, '%Y-%m-%d') as created_at, SUBSTRING(b.content, 1, 40) as content 
				FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.deleted_at IS NULL AND b.category_id = ? ";

		$param_array = array($categoryId);
		if ($this->session->userdata('is_login')) {
			// $sql .= " AND (b.view_type = 0 OR (b.view_type = 2 AND b.writer = ? ) )";
			$sql .= " AND (b.view_type = ? OR (b.view_type = ? AND b.writer = ? ) )";
			array_push($param_array, intVal(Category_model::VIEW_TYPE_ALL));
			array_push($param_array, intVal(Category_model::VIEW_TYPE_ONLY_ME));
			array_push($param_array, $this->session->userdata('user_id'));
		}
		else {
			// $sql .= " AND b.view_type = 0 ";
			$sql .= " AND b.view_type = ? ";
			array_push($param_array, intVal(Category_model::VIEW_TYPE_ALL));
		}
		
		$sql .= "
			ORDER BY b.seq DESC
			LIMIT ?, ?
		";
		// log_message('blog', '[getBoardList] param_array > ' . json_encode($param_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

		array_push($param_array, $paging_info['page_db']);
		array_push($param_array, $this->ITEM_ROW_COUNT);

		return array(
			'board_list' => $this->db->query($sql, $param_array)->result_array(), 
			'page_info' => $paging_info
		);
	}

	private function _getBoardTotalCount($categoryId) {
		$retTotalCount = 0;

		$sql = "
			SELECT count(*) as total_count
			FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.deleted_at IS NULL AND b.category_id = ? ";

		if ($this->session->userdata('is_login')) {
			$sql .= " AND (b.view_type = ? OR (b.view_type = ? AND b.writer = ? ) )";
			$retTotalCount = intVal($this->db->query($sql, array(
				$categoryId, 
				Category_model::VIEW_TYPE_ALL,
				Category_model::VIEW_TYPE_ONLY_ME,
				$this->session->userdata('user_id')
			))->row()->total_count);
		}
		else {
			$sql .= " AND b.view_type = ? ";
			$retTotalCount = intVal($this->db->query($sql, array(
				$categoryId, 
				Category_model::VIEW_TYPE_ALL
			))->row()->total_count);
		}

		return $retTotalCount;
	}

	public function getBoardListBySearch($search_text, $current_page = 1) {
		$total_count = $this->_getBoardTotalCountBySearch($search_text);
		$paging_info = $this->getPagingInfo($current_page, $total_count);

		$sql  = "
			SELECT 
				b.seq, b.category_id, c.category_name, b.writer, b.title, b.view_count, FN_GET_THUMBNAIL(b.thumbnail_seq) thumbnail, 
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

	public function plusViewCount($board_seq, $category_id) {
		$this->load->helper('cookie');

		$VIEW_COUNT_COOKIE = 'blog_view_' . $board_seq;
		$cookie_data = get_cookie($VIEW_COUNT_COOKIE, TRUE);

		// 쿠키가 없으면
		if( !(isset($cookie_data) && !is_null($cookie_data) && !empty($cookie_data)) ) {
			$sql = "
				UPDATE tbl_blog_boards SET view_count = view_count+1 WHERE seq = ?
			";

			$this->db->trans_start();
			$this->db->query($sql, array($board_seq));
			$this->db->trans_complete();

			$this->_updateVisit($category_id, $board_seq);
			
			set_cookie($VIEW_COUNT_COOKIE, '1');
		}		
	}

	public function getBoardData($board_seq, $is_summary = false) {
		$sql  = "
			SELECT 
				b.seq, b.category_id, c.category_name, b.writer, b.title, FN_GET_THUMBNAIL(b.thumbnail_seq) thumbnail, 
				b.view_count, b.like_count, b.view_type, b.write_type, 
				DATE_FORMAT(b.created_at, '%Y-%m-%d %H:%i:%s') as created_at, 
				DATE_FORMAT(b.updated_at, '%Y-%m-%d %H:%i:%s') as updated_at 
		";

		if(!$is_summary) {
			$sql .= " , b.content ";
		}

		$sql .= "
			FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.seq = ? AND b.deleted_at IS NULL 
		";

		return $this->db->query($sql, array($board_seq))->row_array();
	}

	private function _getBoardSimpleData($board_seq) {
		$sql  = "
			SELECT 
				b.seq, b.category_id, c.category_name, b.writer, b.title, FN_GET_THUMBNAIL(b.thumbnail_seq) thumbnail, 
				DATE_FORMAT(b.created_at, '%Y-%m-%d %H:%i:%s') as created_at, 
				DATE_FORMAT(b.updated_at, '%Y-%m-%d %H:%i:%s') as updated_at
			FROM tbl_blog_boards b INNER JOIN tbl_blog_categories c ON b.category_id = c.category_id 
			WHERE b.seq = ? AND b.deleted_at IS NULL 
		";

		return $this->db->query($sql, array($board_seq))->row_array();
	}

	public function getPrevBoardData($board_seq) {
		$sql = "
			SELECT ifnull(max(b.seq), 0) prev_seq
			FROM tbl_blog_boards b INNER join (
				SELECT cb.seq, cb.category_id FROM tbl_blog_boards cb WHERE cb.seq = ?
			) cb2 ON b.category_id = cb2.category_id
			WHERE b.seq < ? AND b.deleted_at IS NULL 
		";

		$param_array = array($board_seq, $board_seq);
		if ($this->session->userdata('is_login')) {
			$sql .= " AND b.writer = ?";
			array_push($param_array, $this->session->userdata('user_id'));
		} else {
			$sql .= " AND b.view_type = ? ";
			array_push($param_array, intVal(Category_model::VIEW_TYPE_ALL));
		}

		$prevBoardSeq = $this->db->query($sql, $param_array)->row()->prev_seq;
		if($prevBoardSeq > 0) {
			return $this->_getBoardSimpleData($prevBoardSeq);
		}
		else {
			return null;
		}
	}

	public function getNextBoardData($board_seq) {
		$sql = "
			SELECT ifnull(min(b.seq), 0) next_seq
			FROM tbl_blog_boards b INNER join (
				SELECT cb.seq, cb.category_id FROM tbl_blog_boards cb WHERE cb.seq = ?
			) cb2 ON b.category_id = cb2.category_id
			WHERE b.seq > ? AND b.deleted_at IS NULL 
		";

		$param_array = array($board_seq, $board_seq);
		if ($this->session->userdata('is_login')) {
			$sql .= " AND b.writer = ?";
			array_push($param_array, $this->session->userdata('user_id'));
		} else {
			$sql .= " AND b.view_type = ? ";
			array_push($param_array, intVal(Category_model::VIEW_TYPE_ALL));
		}

		$nextBoardSeq = $this->db->query($sql, $param_array)->row()->next_seq;
		if ($nextBoardSeq > 0) {
			return $this->_getBoardSimpleData($nextBoardSeq);
		} else {
			return null;
		}
	}

	public function insertBoard($boardInfo) {
		$boardInfo['created_at'] = date('Y-m-d H:i:s');
		$boardInfo['updated_at'] = date('Y-m-d H:i:s');

		$this->db->trans_start();
		// $this->db->escape() 
		$insertResult = $this->db->query($this->db->insert_string('tbl_blog_boards', $boardInfo));
		$newBlogSeq = 0;
		if($insertResult) {
			$newBlogSeq = intVal($this->db->insert_id());
		}
		$this->db->trans_complete();

		return $newBlogSeq;
	}

	public function updateBoard($boardInfo) {
		$resultId = 0;		

		$sql = "
			UPDATE tbl_blog_boards SET 
			category_id = ?, 
			title = ?,
			view_type = ? ";

		if($boardInfo['thumbnail_seq'] > 0) {
			$sql .= ', thumbnail_seq = ' . $boardInfo['thumbnail_seq'];
		}

		$sql .= ", updated_at = now(), content = ? WHERE seq = ?";

		$this->db->trans_start();
		$updateResult = $this->db->query($sql, array(
			$boardInfo['category_id'],
			$boardInfo['title'],
			$boardInfo['view_type'],
			$boardInfo['content'],
			$boardInfo['seq']
		));
		$this->db->trans_complete();

		if($updateResult) {
			$resultId = $boardInfo['seq'];
		}

		return $resultId;
	}

	public function deleteBoard($board_seq) {
		// $update_data = array(
		// 	'deleted_at' => date('Y-m-d H:i:s')
		// );

		// $this->db->trans_start();
		// $this->db->where('seq', $board_seq);
		// $deleteResult = $this->db->update('tbl_blog_boards', $update_data);
		// $this->db->trans_complete();

		
		$sql = "UPDATE tbl_blog_boards SET deleted_at = now() WHERE seq = ?";
		$this->db->trans_start();
		$deleteResult = $this->db->query($sql, array($board_seq));
		$this->db->trans_complete();

		return $deleteResult;
	}

	private function _updateVisit($category_id, $board_seq) {
		$today = date("Ymd");

		$sql = "
			INSERT INTO tbl_blog_visit (
				date, category_id, board_seq, count
			) VALUES(
				?, ?, ?, 1
			)
			ON DUPLICATE KEY UPDATE count = count + 1
		";

		$this->db->trans_start();
		$updateResult = $this->db->query($sql, array(
			$today, 
			$category_id,
			$board_seq
		));
		$this->db->trans_complete();
	}
}
