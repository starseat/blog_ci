<!-- preloader ================================================== -->
<div id="preloader">
	<div id="loader" class="dots-fade">
		<div></div>
		<div></div>
		<div></div>
	</div>
</div>

<div id="top" class="s-wrap site-wrapper">

	<!-- site header ================================================== -->
	<header class="s-header">

		<div class="header__top">
			<div class="header__logo">
				<a class="site-logo" href="/">
					<img src="/public/template/images/logo.svg" alt="Homepage">
				</a>
			</div>

			<div class="header__search">

				<form role="search" method="get" class="header__search-form" action="/blog/search">
					<label>
						<span class="hide-content">Search for:</span>
						<input type="search" class="search-field" placeholder="Type Keywords" value="" name="search_text" title="Search for:" autocomplete="off">
						<!-- onkeypress=search_enter(document.q); -->
					</label>
					<input type="submit" class="header__search-submit" value="Search" id="search_submit">
				</form>

				<a href="#0" title="Close Search" class="header__search-close">Close</a>

			</div> <!-- end header__search -->

			<!-- toggles -->
			<a href="#0" class="header__search-trigger"></a>
			<a href="#0" class="header__menu-toggle"><span>Menu</span></a>

			<script>
				function search_enter(_q) {
					const _keycode = window.event.keyCode;
					if (_keycode == 13) {
						$('#search_submit').click();
					}
				}
			</script>

		</div> <!-- end header__top -->

		<nav class="header__nav-wrap">

			<ul class="header__nav">
				<li class="<?= $navi_id == 'home' ? 'current' : ''; ?>"><a href="/" title="home">Home</a></li>
				<?php
				foreach ($categories as $category) {
					if (isset($category['children']) && !is_null($category['children']) && count($category['children']) > 0) {
				?>
						<li class="has-children <?= $navi_id == $category['category_id'] ? 'current' : '' ?>">
							<a href="#0" title="<?= $category['category_name'] ?>"><?= $category['category_name'] ?></a>
							<ul class="sub-menu">
								<?php
								foreach ($category['children'] as $child) {
								?>
									<li><a href="/blog/list/<?= $child['category_id'] ?>"><?= $child['category_name'] ?></a></li>
								<?php
								} // end of foreach($category['children'] as $child)
								?>
							</ul>
						<?php
					} else {
						?>
						<li class="<?= $navi_id == $category['category_id'] ? 'current' : '' ?>"><a href="/blog/list/<?= $category['category_id'] ?>" title="<?= $category['category_name'] ?>"><?= $category['category_name'] ?></a></li>
				<?php
					}
				}
				?>
				<!--
			<li class="has-children">
				<a href="#0" title="">Categories</a>
				<ul class="sub-menu">
					<li><a href="category.html">Lifestyle</a></li>
					<li><a href="category.html">Health</a></li>
					<li><a href="category.html">Family</a></li>
					<li><a href="category.html">Management</a></li>
					<li><a href="category.html">Travel</a></li>
					<li><a href="category.html">Work</a></li>
				</ul>
			</li>
			<li class="has-children">
				<a href="#0" title="">Blog Posts</a>
				<ul class="sub-menu">
					<li><a href="single-video.html">Video Post</a></li>
					<li><a href="single-audio.html">Audio Post</a></li>
					<li><a href="single-gallery.html">Gallery Post</a></li>
					<li><a href="single-standard.html">Standard Post</a></li>
				</ul>
			</li>
			<li><a href="styles.html" title="">Styles</a></li>
			<li><a href="page-about.html" title="">About</a></li>
			<li><a href="page-contact.html" title="">Contact</a></li>
			-->
			</ul> <!-- end header__nav -->

			<ul class="header__social">
				<!-- 
				<li class="ss-facebook">
					<a href="https://facebook.com/">
						<span class="screen-reader-text">Facebook</span>
					</a>
				</li> 
				<li class="ss-twitter">
					<a href="#0">
						<span class="screen-reader-text">Twitter</span>
					</a>
				</li>
				<li class="ss-dribbble">
					<a href="#0">
						<span class="screen-reader-text">Dribbble</span>
					</a>
				</li>
				<li class="ss-pinterest">
					<a href="#0">
						<span class="screen-reader-text">Behance</span>
					</a>
				</li>
				-->
				<li style="text-align: center;">
					<a href="https://github.com/" target="_blank">
						<i class="fa fa-github" aria-hidden="true"></i>
					</a>
				</li>
				<li style="text-align: center;">
					<a href="https://www.google.co.kr/" target="_blank">
						<i class="fa fa-google" aria-hidden="true"></i>
					</a>
				</li>
				<li style="text-align: center;">
					<a href="https://www.instagram.com/" target="_blank">
						<i class="fa fa-instagram" aria-hidden="true"></i>
					</a>
				</li>
				<li style="text-align: center;">
					<a href="https://twitter.com/" target="_blank">
						<i class="fa fa-twitter" aria-hidden="true"></i>
					</a>
				</li>
			</ul>

		</nav> <!-- end header__nav-wrap -->

	</header> <!-- end s-header -->
