<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hashtag_model extends CI_Model { // Base_Model
	public function __construct() {
		parent::__construct();
	}

	public function save($tag) {
		$newId = 0;

		$this->db->trans_start();

		$queryResult = $this->db->query($this->db->insert_string('tbl_blog_hashtags', array('tag' => trim($tag))));
		if($queryResult) {
			$newId = intVal($this->db->insert_id());
		}

		$this->db->trans_complete();

		return $newId;
	}

	public function saveAll($tags) {
		// Escape and quote each hashtag
//		$escapedHashtags = array_map(array($conn, 'real_escape_string'), $hashtags);
//		$quotedHashtags = array_map(function($tags) { return "'$tags'"; }, $escapedHashtags);
		$quotedHashtags = array_map(function($tags) { return "'$tags'"; });

		// Implode the quoted hashtags to use in the SQL query
		$values = implode(',', $quotedHashtags);

		// SQL query using INSERT IGNORE
		$sql = "INSERT IGNORE INTO `tbl_blog_hashtags` (`tag`) VALUES $values";

		return $this->db->query($sql);
	}

	public function mapping($boardId, $tags) {
		$this->deleteMapping($boardId);

		foreach ($tags as $tag) {
			$tagId = $this->getId($tag);

			if($tagId == 0) {
				$tagId = $this->save($tag);
			}

			$mappingInfo = array(
				'board_seq' => $boardId,
				'hashtag_id' => $tagId
			);

//			$this->db->trans_start();
			$this->db->query($this->db->insert_string('tbl_blog_board_hashtag', $mappingInfo));
//			$this->db->trans_complete();
		}
	}

	/**
	 * hashtag 의 id 조회
	 * @param $tag
	 * @return int|mixed (0: not exist, 0
	 */
	public function getId($tag) {
		$sql = "SELECT id FROM tbl_blog_hashtags WHERE tag = ? AND deleted_at IS NULL";
		$query_result = $this->db->query($sql, array($tag));

		if ($query_result->num_rows > 0) {
			$row = $query_result->fetch_assoc();
			return $row['id'];
		}
		return 0;
	}

	public function deleteMapping($boardId) {
		$sql = "DELETE FROM tbl_blog_board_hashtag WHERE board_seq = ?";
		$this->db->query($sql, array($boardId));
	}

	public function getBoardHashTags($boardSeq) {
		$sql = "SELECT tbh.id, TRIM(tbh.tag) AS tag FROM tbl_blog_board_hashtag tbbh 
			INNER JOIN tbl_blog_hashtags tbh ON tbbh.hashtag_id = tbh.id 
			WHERE tbbh.board_seq = ?";

//		$tags = $this->db->query($sql, array($boardSeq))->result_array();
//		return array_map(function($item) { return trim($item['tag']); }, $tags);

		return $this->db->query($sql, array($boardSeq))->result_array();
	}

	public function getBoardHashTagsOnlyString($boardSeq) {
		$tags = $this->getBoardHashTags($boardSeq);

		$results = [];
		foreach ($tags as $item) {
			array_push($results, $item['tag']);
		}
		return $results;
	}
}
