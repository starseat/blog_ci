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
	<form action="" id="loginForm" name="loginForm">
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

		<button class="btn btn--stroke full-width" onclick="alert('123');">Login</button>
	</form>
</div>
