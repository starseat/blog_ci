<?php
defined('BASEPATH') or exit('No direct script access allowed');

function makeResultSuccess($data) {
	$resultArray = [
		'code' => 0, 
		'result' => TRUE
	];

	if( isset($data) && !empty($data)) {
		$resultArray['data'] = $data;
	}

	return $resultArray;
}

function makeResultSuccessOnData($data) {
	return [
		'code' => 0,
		'result' => TRUE,
		'data' => $data
	];
}

function makeResultError($code = '', $msg = '') {
	return [
		'code' => $code,
		'message' => $msg,
		'result' => FALSE
	];
}

function makeResult($code, $msg = '', $data = '') {
	return [
		'code' => $code,
		'result' => ($code == 0 ? TRUE : FALSE), 
		'message' => $msg,
		'data' => $data
	];
}


function makeResultErrorNotLogin($msg = 'Not logged in.') {
	return makeResultError(-1, $msg);
}

function makeResultErrorNotAllowedHttpMethod($msg = 'Not allowed http method.') {
	return makeResultError(-2, $msg);
}

function makeResultErrorParam($msg = 'Invalid parameter.') {
	return makeResultError(-3, $msg);
}
