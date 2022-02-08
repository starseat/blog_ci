<!DOCTYPE html>
<html>

<head>

	<!--- basic page needs
    ================================================== -->
	<meta charset="utf-8">

	<!-- Require SEO -->
	<?php if (!empty($summary)) { ?>
		<title><?= $summary['title']; ?> | Scribble - jw's blog.</title>
	<?php } else { ?>
		<title>Scribble - jw's blog.</title>
	<?php } ?>
	<meta name="description" content="IT 정보 및 여러가지를 작성한 개인 블로그 입니다.">
	<meta name="author" content="starseat">

	<!-- og tag start -->
	<meta property="og:type" content="website">
	<meta property="og:title" content="<?= $summary['title']; ?> | Scribble - jw's blog.">
	<meta property="og:description" content="IT 정보 및 여러가지를 작성한 개인 블로그 입니다.">
	<meta property="og:locale" content="ko-KR">

	<?php if (!empty($summary)) { ?>
		<?php if ($summary['category_id'] == 'home') { ?>
			<meta property="og:url" content="http://starseat.net/">
		<?php } else { ?>
			<meta property="og:url" content="<?= $summary['url']; ?>">
		<?php } ?>

		<?php if ($summary['thumbnail'] != '') { ?>
			<meta property="og:image" content="<?= $summary['thumbnail']; ?>" />
			<meta property="og:image:secure_url" content="<?= $summary['thumbnail']; ?>" />
		<?php } else {  ?>
			<meta property="og:image" content="/public/imgs/og-image.png" />
			<meta property="og:image:secure_url" content="/public/imgs/og-image.png" />
		<?php } ?>
	<?php } else { /* else of if(!empty($summary)) */ ?>
		<meta property="og:url" content="http://starseat.net/">
		<meta property="og:image" content="/public/imgs/og-image.png" />
		<meta property="og:image:secure_url" content="/public/imgs/og-image.png" />
	<?php } ?>


	<!-- og tag end -->

	<!-- twitter og tag start -->
	<meta name="twitter:card" content="summary">
	<?php if (!empty($summary)) { ?>
		<meta name="twitter:title" content="<?= $summary['title']; ?> | Scribble - jw's blog.">
		<meta name="twitter:description" content="IT 정보 및 여러가지를 작성한 개인 블로그 입니다.">
		<?php if ($summary['thumbnail'] != '') { ?>
			<meta name="twitter:image" content="<?= $summary['thumbnail']; ?>" />
			<meta name="twitter:image:secure_url" content="<?= $summary['thumbnail']; ?>" />
		<?php } else {  ?>
			<meta name="twitter:image" content="/public/imgs/og-image.png" />
			<meta name="twitter:image:secure_url" content="/public/imgs/og-image.png" />
		<?php } ?>
	<?php } else { /* else of if(!empty($summary)) */ ?>
		<meta name="twitter:title" content="Scribble - jw's blog.">
		<meta name="twitter:image" content="/public/imgs/og-image.png" />
		<meta name="twitter:image:secure_url" content="/public/imgs/og-image.png" />
	<?php } ?>

	<meta name="twitter:site" content="@scribble">
	<meta name="twitter:lcreator" content="@scribble">
	<!-- twitter og tag end -->

	<!-- google+ og tag start -->
	<meta property="itemprop:description" content="IT 정보 및 여러가지를 작성한 개인 블로그 입니다.">
	<?php if (!empty($summary)) { ?>
		<meta property="itemprop:title" content="<?= $summary['title']; ?> | Scribble - jw's blog.">
		<?php if ($summary['thumbnail'] != '') { ?>
			<meta property="itemprop:image" content="<?= $summary['thumbnail']; ?>" />
		<?php } else {  ?>
			<meta property="itemprop:image" content="/public/imgs/og-image.png" />
		<?php } ?>
	<?php } else { /* else of if(!empty($summary)) */ ?>
		<meta property="itemprop:title" content="Scribble - jw's blog.">
		<meta property="itemprop:image" content="/public/imgs/og-image.png" />
	<?php } ?>
	<!-- google+ og tag end -->

	<!-- meta http-equiv
    ================================================== -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<!-- mobile specific metas
    ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSS
    ================================================== -->
	<link rel="stylesheet" href="/public/template/css/base.css">
	<link rel="stylesheet" href="/public/template/css/vendor.css">
	<link rel="stylesheet" href="/public/template/css/main.css">
	<link rel="stylesheet" href="/public/vendor/font-awesome-4.7.0/css/font-awesome.min.css">

	<!-- script
    ================================================== -->
	<script src="/public/template/js/modernizr.js"></script>

	<!-- favicons
    ================================================== -->
	<link rel="apple-touch-icon" sizes="180x180" href="/public/imgs/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/public/imgs/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/public/imgs/favicon/favicon-16x16.png">
	<link rel="manifest" href="/public/imgs/favicon/site.webmanifest">

	<!-- spinner - loading progress
    ================================================== -->
	<link rel="stylesheet" href="/public/vendor/spin/jquery.spin/css/jquery.spin.css" />

	<!-- custom
    ================================================== -->
	<link rel="stylesheet" href="/public/css/common.css">

	<!-- Global site tag (gtag.js) - Google Analytics4 -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-5DBT2TNTEJ"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-5DBT2TNTEJ');
	</script>

	<!-- Global site tag (gtag.js) - Google Analytics (유니버셜) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-200366886-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-200366886-1');
	</script>

	<!-- Google AdSense -->
	<script data-ad-client="ca-pub-1835044006045704" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

	<!-- Naver Analytics -->
	<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script>
	<script type="text/javascript">
		if (!wcs_add) var wcs_add = {};
		wcs_add["wa"] = "8792e55fbe43f8";
		if (window.wcs) {
			wcs_do();
		}
	</script>

	<!-- ==================================================================================================== -->
	<!-- ==================================================================================================== -->
	<!-- ==================================================================================================== -->

	<!-- Java Script
	================================================== -->
	<script src="/public/template/js/jquery-3.2.1.min.js"></script>
	<script src="/public/template/js/plugins.js"></script>

	<!-- spinner - loading progress
    ================================================== -->
	<script src="/public/vendor/spin/jquery.spin/js/jquery.spin.js"></script>

	<script src="/public/js/common.js"></script>

</head>

<body>

	<!-- spinner - loading progress ================================================== -->
	<div id="spinner-bk">
		<div id="spinner" class="spin" data-spin></div>
	</div>
