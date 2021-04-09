<!-- site content
        ================================================== -->
<div class="s-content content">
	<main class="row content__page">
		<article class="column large-full entry format-standard">
			<?php
			if (!empty($board_data['thumbnail'])) {
			?>
				<div class="media-wrap entry__media">
					<div class="entry__post-thumb">
						<img src="<?= $board_data['thumbnail'] ?>" alt="<?= $board_data['title'] . '_thumbnail' ?>">
					</div>
				</div>
			<?php
			} // end of if(!empty($board_data['thumbnail']))
			?>

			<div class="content__page-header entry__header">
				<h1 class="display-1 entry__title"><?= $board_data['title'] ?></h1>
				<ul class="entry__header-meta">
					<li class="author">By <a href="#0"><?= $board_data['writer'] ?></a></li>
					<li class="date"><?= $board_data['created_at'] ?></li>
					<li class="cat-links">
						<a href="/blog/list/<?= $board_data['category_id'] ?>"><?= $board_data['category_name'] ?></a>
					</li>
				</ul>
				<ul class="entry__header-meta" style="text-align: right;">
					<li><a href="#0">수정</a></li>
					<li><a href="#0">삭제</a></li>
				</ul>
			</div> <!-- end entry__header -->

			<div class="entry__content">
				<!--
					태그 기능 추가시 아래 내용 추가
				<p class="entry__tags">
					<span>Post Tags</span>

					<span class="entry__tag-list">
						<a href="#0">orci</a>
						<a href="#0">lectus</a>
						<a href="#0">varius</a>
						<a href="#0">turpis</a>
					</span>
				</p>
				<hr>
				-->

				<?= $board_data['content'] ?>
			</div> <!-- end entry content -->

			<div class="entry__pagenav">
				<div class="entry__nav">
					<div class="entry__prev">
						<?php
						if (is_null($prev_data) || empty($prev_data)) {
						?>
							<span>Previous Post</span> -
						<?php
						} else {
						?>
							<a href="/blog/view/<?= $prev_data['seq'] ?>" rel="prev">
								<span>Previous Post</span>
								<?= $prev_data['title'] ?>
							</a>
						<?php
						}
						?>
					</div>

					<div class="entry__next">
						<?php
						if (is_null($next_data) || empty($next_data)) {
						?>
							<span>Next Post</span> -
						<?php
						} else {
						?>
							<a href="/blog/view/<?= $next_data['seq'] ?>" rel="next">
								<span>Next Post</span>
								<?= $next_data['title'] ?>
							</a>
						<?php
						}
						?>
					</div>

				</div>
			</div> <!-- end entry__pagenav -->

		</article>
	</main>

</div> <!-- end s-content -->
