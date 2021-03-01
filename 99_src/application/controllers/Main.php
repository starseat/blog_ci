<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index() {
		$this->_header();

		$this->load->view('main');

		$this->_footer();
	}

	private function _header() {
		$this->load->view('fragments/head');
		$this->load->view('fragments/header');
	}

	private function _footer() {
		$this->load->view('fragments/footer');
		$this->load->view('fragments/tail');
	}
}
