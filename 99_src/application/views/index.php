<div class="s-content">

<?php
if ($page_type == 'category') {
	if(!empty($category_info)) {
?>
		<header class="listing-header">
			<h1 class="h2">Category: <?= $category_info->category_name ?></h1>
		</header>
<?php
	} // end of if(!empty($page_category_id) && $page_category_id == 'home')
} // end of if ($page_type == 'category')
else if ($page_type == 'search') {
?>
		<header class="listing-header">
			<h1 class="h2">Search: <?= $search_text ?></h1>
		</header>
<?php
} // end of if ($page_type == 'search') {
?>

	<div class="masonry-wrap">

		<div class="masonry">

			<div class="grid-sizer"></div>

<?php
if(count($board_list) > 0) {
	foreach ($board_list as $board) {
		// thumbnail 없는 게시글
		if (empty($board['thumbnail'])) {
?>
					<article class="masonry__brick entry format-quote animate-this">
						<div class="entry__thumb" style="height: 100px; min-height: 100px; max-height: 100px;">
							<a href="<?= base_url('/blog/view/' . $board['seq']); ?>">
								<blockquote></blockquote>
							</a>
						</div>
<?php
		} 
		// thumbnail 있는 게시글
		else {
?>
						<article class="masonry__brick entry format-standard animate-this">
							<div class="entry__thumb">
								<a href="<?= base_url('/blog/view/' . $board['seq']); ?>" class="entry__thumb-link">
									<img src="<?= $board['thumbnail'] ?>" alt="<?= $board['title'] . ' - thumbnail' ?>">
								</a>
							</div>
<?php
		}
?>
						<div class="entry__text">
							<div class="entry__header">
								<h2 class="entry__title"><a href="<?= base_url('/blog/view/' . $board['seq']); ?>"><?= $board['title'] ?></a></h2>
								<div class="entry__meta">
									<span class="entry__meta-cat">
										<a href="<?= base_url('/blog/view/' . $board['seq']); ?>"><?= $board['category_name'] ?></a>
										<a href="<?= base_url('/blog/view/' . $board['seq']); ?>"><?= $board['writer'] ?></a>
									</span>
									<span class="entry__meta-date">
										<a href="<?= base_url('/blog/view/' . $board['seq']); ?>"><?= $board['created_at'] ?></a>
									</span>
								</div>

							</div>
							<div class="entry__excerpt">
								<p>
									<?php echo mb_strimwidth(strip_tags($board['content']), '0', '100', '...', 'utf-8'); ?>
								</p>
							</div>
						</div>

						</article> <!-- end article -->
<?php
	} // end of foreach ($board_list as $board)
} // end of if(count($board_list) > 0)
else {
	$no_data_message = '등록된 게시글이 없습니다.';
	if ($page_type == 'search') {
		$no_data_message = '검색된 게시글이 없습니다.';
	}
?>
			<div style="text-align: center;"><?= $no_data_message ?></div>
<?php
}
?>
		</div> <!-- end masonry -->

	</div> <!-- end masonry-wrap -->

<?php
// check show pagenation 
if (count($board_list) > 0) {
	// pagenation - category
	if ($page_type == 'category') {
		if (!empty($category_info)) {
?>
		<div class="row">
			<div class="column large-full">
				<nav class="pgn">
					<ul>

<?php 
			if ($page_info['page_prev'] > 0) {  
?>
							<li><a class="pgn__prev" href="<?= base_url('/blog/list/' . $category_info->category_id . '/' . $page_info['page_prev']); ?>">Prev</a></li>
<?php
			} 
			for ($i = $page_info['page_start']; $i <= $page_info['page_end']; $i++) {
				if ($i == $page_info['page_current']) {
?>
								<li><span class="pgn__num current"><?= $i ?></span></li>
<?php 
				} 
				else { 
?>
								<li><a class="pgn__num" href="<?= base_url('/blog/list/' . $category_info->category_id . '/' . $i); ?>"><?= $i ?></a></li>
<?php 
				} 
			} // end of for ($i = $page_info['page_start']; $i <= $page_info['page_end']; $i++)
			if ($page_info['page_next'] < $page_info['page_total']) {  
?>
							<li><a class="pgn__next" href="<?= base_url('/blog/list/' . $category_info->category_id . '/' . $page_info['page_next']); ?>">Next</a></li>
<?php 
			} 
?>
					</ul>
				</nav>
			</div>
		</div>
<?php
		} // end of if(!empty($page_category_id) && $page_category_id == 'home')
	} // end of if ($page_type == 'category')

	// pagenation - search
	else if ($page_type == 'search') {
?>
		<div class="row">
			<div class="column large-full">
				<nav class="pgn">
					<ul>

<?php 
		if ($page_info['page_prev'] > 0) {  
?>
							<li><a class="pgn__prev" href="<?= base_url('/blog/search/' . $page_info['page_prev'] . '?search_text=' . $search_text); ?>">Prev</a></li>
<?php 
		} 

		for ($i = $page_info['page_start']; $i <= $page_info['page_end']; $i++) {
			if ($i == $page_info['page_current']) {
?>
								<li><span class="pgn__num current"><?= $i ?></span></li>
<?php 
			} 
			else { 
?>
								<li><a class="pgn__num" href="<?= base_url('/blog/search/' . $i . '?search_text=' . $search_text); ?>"><?= $i ?></a></li>
<?php 
			} 
		} // end of for ($i = $page_info['page_start']; $i <= $page_info['page_end']; $i++)
		if ($page_info['page_next'] < $page_info['page_total']) {  
?>
							<li><a class="pgn__next" href="<?= base_url('/blog/search/' . $page_info['page_next'] . '?search_text=' . $search_text); ?>">Next</a></li>
<?php 
		} 
?>
					</ul>
				</nav>
			</div>
		</div>
<?php
	} // end of if ($page_type == 'search')
} // end of if (count($board_list) > 0) // check show pagenation 

?>
</div> <!-- end s-content -->
