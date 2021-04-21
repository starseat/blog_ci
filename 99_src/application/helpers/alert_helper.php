<?php
defined('BASEPATH') or exit('No direct script access allowed');

function alert($msg = '', $url = '') {
	$CI =& get_instance();
	echo '<meta http-equiv="content-type" content="text/html; charset="' . $CI->config->item('charset') . '">';
	echo '<script type="text/javascript"> alert("' . $msg . '"); location.replace("' . $url . '"); </script>';
	exit;
}

function alert_close($msg) {
	$CI = &get_instance();
	echo '<meta http-equiv="content-type" content="text/html; charset="' . $CI->config->item('charset') . '">';
	echo '<script type="text/javascript"> alert("' . $msg . '"); window.close(); </script>';
	exit;
}

function alert_only($msg, $exit=true) {
	$CI = &get_instance();
	echo '<meta http-equiv="content-type" content="text/html; charset="' . $CI->config->item('charset') . '">';
	echo '<script type="text/javascript"> alert("' . $msg . '"); </script>';

	if($exit) {
		exit;
	}	
}

function alert_history_back($msg) {
	$CI = &get_instance();
	echo '<meta http-equiv="content-type" content="text/html; charset="' . $CI->config->item('charset') . '">';
	echo '<script type="text/javascript"> alert("' . $msg . '"); history.back(); </script>';
	exit;
}

function replace($url = '/') {
	echo '<script type="text/javascript"> location.replace("' . $url . '"); </script>';
	exit;
}

