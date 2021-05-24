<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member_model extends Base_Model {
	public function __construct() {
		parent::__construct();
	}

	public function login($loginData) {
		$result_data = array();

		$sql = "
			SELECT seq, user_id, email, name, password, provider, sns_id, phone, remember_me_yn, enable_yn, member_type, introduction 
			FROM tbl_blog_members 
			WHERE user_id = ? AND enable_yn = 'Y' AND deleted_at IS NULL
		";

		$query_result = $this->db->query($sql, array($loginData['user_id']));

		if($query_result->num_rows() > 0) {
			$userInfo = $query_result->row_array();
			if($this->password_matches($loginData['password'], $userInfo['password'])) {
				$result_data['result'] = true;
				$result_data['message'] = 'success';
				$result_data['data'] = $query_result->row_array();
			}
			else {
				$result_data['result'] = false;
				$result_data['message'] = 'Passwords do not match.';
			}

		}
		else {
			$result_data['result'] = false;
			$result_data['message'] = 'User does not exist.';
		}

		return $result_data;
	}

	
	public function password_encrypt($password) {
		return password_hash($password, PASSWORD_DEFAULT /*, $option */);
	}

	public function password_matches($password, $hashed_password) {
		return password_verify($password, $hashed_password /*, options */);
	}

}
