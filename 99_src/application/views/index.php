<div class="s-content">

	<?php
	if (!empty($category_info)) {
	?>
		<header class="listing-header">
			<h1 class="h2">Category: <?= $category_info->category_name ?></h1>
		</header>
	<?php
	} // end of if(!empty($page_category_id) && $page_category_id == 'home')
	?>

	<div class="masonry-wrap">

		<div class="masonry">

			<div class="grid-sizer"></div>

			<?php
			foreach ($board_list as $board) {
				if (empty($board['thumbnail'])) {
			?>
					<article class="masonry__brick entry format-quote animate-this">
						<div class="entry__thumb" style="height: 100px; min-height: 100px; max-height: 100px;">
							<blockquote></blockquote>
						</div>
					<?php
				} else {
					?>
						<article class="masonry__brick entry format-standard animate-this">
							<div class="entry__thumb">
								<a href="#" class="entry__thumb-link">
									<img src="<?= $board['thumbnail'] ?>" alt="<?= $board['title'] . ' - thumbnail' ?>">
								</a>
							</div>
						<?php
					}
						?>
						<div class="entry__text">
							<div class="entry__header">
								<h2 class="entry__title"><a href="#"><?= $board['title'] ?></a></h2>
								<div class="entry__meta">
									<span class="entry__meta-cat">
										<a href="#"><?= $board['category_name'] ?></a>
										<a href="#"><?= $board['writer'] ?></a>
									</span>
									<span class="entry__meta-date">
										<a href="#"><?= $board['created_at'] ?></a>
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
					?>
		</div> <!-- end masonry -->

	</div> <!-- end masonry-wrap -->

	<?php
	if (!empty($category_info)) {
	?>
		<div class="row">
			<div class="column large-full">
				<nav class="pgn">
					<ul>

						<?php if ($page_info['page_prev'] > 0) {  ?>
							<li><a class="pgn__prev" href="<?= base_url('/blog/list/' . $category_info->category_id . '/' . $page_info['page_prev']); ?>">Prev</a></li>
						<?php } ?>

						<?php 
							for ($i = $page_info['page_start']; $i <= $page_info['page_end']; $i++) {
								if ($i == $page_info['page_current']) {
						?>
								<li><span class="pgn__num current"><?= $i ?></span></li>
							<?php } else { ?>
								<li><a class="pgn__num" href="<?= base_url('/blog/list/' . $category_info->category_id . '/' . $i); ?>"><?= $i ?></a></li>
							<?php } ?>
						<?php } ?>

						<?php if ($page_info['page_next'] < $page_info['page_total']) {  ?>
							<li><a class="pgn__next" href="<?= base_url('/blog/list/' . $category_info->category_id . '/' . $page_info['page_next']); ?>">Next</a></li>
						<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
	<?php
	} // end of if(!empty($page_category_id) && $page_category_id == 'home')
	?>

</div> <!-- end s-content -->
