<div class="s-content">
    
    <div class="masonry-wrap">

        <div class="masonry">

            <div class="grid-sizer"></div>

			<?php
			foreach ($board_list as $board) {
				if( empty($board['thumbnail']) ) {
			?>
				<article class="masonry__brick entry format-quote animate-this">
					<div class="entry__thumb" style="height: 100px; min-height: 100px; max-height: 100px;">
						<blockquote></blockquote>
					</div>
			<?php
				}
				else {
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

    <div class="row">
        <div class="column large-full">
            <nav class="pgn">
                <ul>
                    <li><a class="pgn__prev" href="#0">Prev</a></li>
                    <li><a class="pgn__num" href="#0">1</a></li>
                    <li><span class="pgn__num current">2</span></li>
                    <li><a class="pgn__num" href="#0">3</a></li>
                    <li><a class="pgn__num" href="#0">4</a></li>
                    <li><a class="pgn__num" href="#0">5</a></li>
                    <li><span class="pgn__num dots">…</span></li>
                    <li><a class="pgn__num" href="#0">8</a></li>
                    <li><a class="pgn__next" href="#0">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>

</div> <!-- end s-content -->
