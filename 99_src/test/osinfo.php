<?php

function getOsInfo()
{
	$userAgent = $_SERVER["HTTP_USER_AGENT"];

	if (preg_match('/linux/i', $userAgent)) {
		$os = 'linux';
	} elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
		$os = 'mac';
	} elseif (preg_match('/windows|win32/i', $userAgent)) {
		$os = 'windows';
	} else {
		$os = 'Other';
	}

	return $os;
}

echo getOsInfo();

?>
