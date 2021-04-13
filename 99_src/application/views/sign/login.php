<style>
	#login-box {
		width: 360px;
		position: absolute;
		top: 40%;
		left: 50%;
		transform: translate(-40%, -50%);
	}

	#login-box h1 {
		border-bottom: 6px solid #000000;
		margin-bottom: 40px;
		padding: 12px 0;
		width: 40%;
	}
</style>


<div id="login-box">
	<!-- <form action="/sign/in" method="post" id="loginForm" name="loginForm"> -->
	<?php
	// csrf 
	$attributes = array(
		'id' => 'loginForm', 
		'name' => 'loginForm'
	);

	echo form_open('/sign/in', $attributes);
	?>
		<div style="text-align: center;">
			<h1>Login</h1>
		</div>

		<div>
			<label for="userId">User ID</label>
			<input type="text" class="full-width" placeholder="User ID" id="userId" name="userId" value="">
		</div>

		<div>
			<label for="userPwd">Password</label>
			<input type="password" class="full-width" placeholder="Password" id="userPwd" name="userPwd" value="">
		</div>

		<button class="btn btn--stroke full-width" onclick="loginSubmit(event)">Login</button>
	</form>
</div>

<script>
	function loginSubmit(event) {
		event.preventDefault();
		event.stopPropagation();

		if ($('#userId').val() == '') {
			alert('ID 를 입력해 주세요.');
			$('#userId').focus();
			return false;
		}

		if ($('#userPwd').val() == '') {
			alert('비밀번호를 입력해 주세요.');
			$('#userPwd').focus();
			return false;
		}

		$('#loginForm').submit();
	}
</script>
