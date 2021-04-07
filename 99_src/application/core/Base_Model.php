<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Base_Model extends CI_Model {

	// 게시굴 수
	protected $ITEM_ROW_COUNT = 10;

	// 하단 페이지 block 수 (1, 2, 3, 4, ...  이런거)
	protected $PAGE_BLOCK_COUNT = 10;

	public function __construct() {
		parent::__construct();
	}

	protected function getPagingInfo($current_page, $total_item_count) {
		$current_page = intVal($current_page);
		$total_item_count = intVal($total_item_count);

		$page_db = ($current_page - 1) * $this->ITEM_ROW_COUNT;

		// 전체 block 수
		$page_total = ceil($total_item_count / $this->PAGE_BLOCK_COUNT);
		if ($page_total == 0) {
			$page_total = 1;
		}
		// block 시작
		$page_start = (((ceil($current_page / $this->PAGE_BLOCK_COUNT) - 1) * $this->PAGE_BLOCK_COUNT) + 1);

		// block 끝
		$page_end = $page_start + $this->PAGE_BLOCK_COUNT - 1;
		if ($page_total < $page_end) {
			$page_end = $page_total;
		}

		// 시작 바로 전 페이지
		$page_prev = $page_start - 1;
		// 마지막 다음 페이지
		$page_next = $page_end + 1;

		return array(
			'page_current' => $current_page, 
			'page_db' => $page_db,  // db 조회시 사용
			'page_start' => $page_start, 
			'page_end' => $page_end,
			'page_prev' => $page_prev,
			'page_next' => $page_next, 
			'page_total' => $page_total
		);
	}

}
