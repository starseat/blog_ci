    </div> <!-- end s-wrap -->

    <script src="/public/template/js/main.js"></script>

    <?php
	if ($this->session->userdata('is_login')) {
	?>
    	<script src="/public/js/blog.js"></script>
    <?php
	} // end of if ($this->session->userdata('is_login'))
	?>

    </body>

    </html>
