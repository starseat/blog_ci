<!-- TOAST UI Editor -->
<link rel="stylesheet" href="https://uicdn.toast.com/editor/3.1.2/toastui-editor-viewer.min.css" />
<!-- site content ================================================== -->
<link rel="stylesheet" href="/public/css/view.css" />

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
					<li class="author">By <a href="javascript: void(0);"><?= $board_data['writer'] ?></a></li>
					<li class="date"><?= $board_data['created_at'] ?></li>
					<li class="cat-links">
						<a href="/blog/list/<?= $board_data['category_id'] ?>"><?= $board_data['category_name'] ?></a>
					</li>
<?php if ($this->session->userdata('is_login')) { ?>
					<li class="viewer">
						 <embed class="viewer-icon" src="/public/imgs/book-open-reader-solid.svg">
						<!-- <embed src="/public/imgs/imgs/icon/document_magnifier/icon-document_magnifier_black_32.png"> -->
						<?= $board_data['view_count'] ?>
					</li>
					<li class="only-me">(<?= Category_model::getViewTypeName($board_data['view_type']); ?>)</li>
<?php } /* end of if ($this->session->userdata('is_login')) */ ?>
				</ul>

				<?php if ($this->session->userdata('is_login')) { ?>
					<ul class="entry__header-meta" style="text-align: right;">
						<li><a href="/write?seq=<?= $board_data['seq']; ?>">수정</a></li>
						<li>
							<?php
							// csrf 
							$attributes = array(
								'id' => 'deleteBlogForm',
								'name' => 'deleteBlogForm'
							);

							echo form_open('/blog/delete/' . $board_data['seq'], $attributes);
							?>
							<a href="javascript:void(0);" onclick="deleteBlog(event)">삭제</a>
							</form>
						</li>
					</ul>
				<?php } ?>

			</div> <!-- end entry__header -->

			<div class="entry__content" id="entry_content">
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

				<div id="entry_content_viewer"></div>
			</div> <!-- end entry content -->
			<div id="temp_entry_content">
				<input type="hidden" id="temp_writeType" value="<?= $board_data['write_type']; ?>">
				<textarea id="temp_content"><?= $board_data['content'] ?></textarea>
			</div>


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

<script src="https://uicdn.toast.com/editor/3.1.2/toastui-editor-viewer.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		if (document.querySelector('#temp_writeType').value == 'md') {
			const Viewer = toastui.Editor;
			const viewer = new Viewer({
				el: document.querySelector('#entry_content_viewer'),
				initialValue: document.querySelector('#temp_content').textContent,
			});
		} else {
			document.querySelector('#entry_content_viewer').innerHTML = document.querySelector('#temp_content').textContent
		}
	});
</script>
