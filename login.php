<?php
use Phppot\Member;

if (! empty($_POST["login-btn"])) {
    require_once __DIR__ . '/Model/Member.php';
    $member = new Member();
    $loginResult = $member->loginMember();
}
?>
<HTML>
<HEAD>
<TITLE>Shippyfee</TITLE>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link href="assets/css/phppot-style.css" type="text/css" rel="stylesheet" />
<link href="assets/css/user-registration.css" type="text/css" rel="stylesheet" />
<script src="vendor/jquery/jquery-3.3.1.js" type="text/javascript"></script>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<script src="vendor/jquery/3.5.1/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<link rel="icon" type="image/png" href="assets/img/favicon.png"/>
	
</HEAD>
<BODY style="background: repeating-radial-gradient(circle at 0 0,#EFE,#dcdcdc 130px)">
	<div class="phppot-container">
		<div class="sign-up-container">
			<div class="login-signup">
				<a href="user-registration.php" style="background-color: #ffc72c; color: #565656; padding: 7px; border-radius: 3px; margin-right: 20px;">Sign up</a>
			</div>
			<div class="signup-align">
				<form name="login" action="" method="post"
					onsubmit="return loginValidation()">
					<div class="signup-heading">Login</div>
				<?php if(!empty($loginResult)){?>
				<div class="error-msg"><?php echo $loginResult;?></div>
				<?php }?>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Username<span class="required error" id="username-info"></span>
							</div>
							<input class="input-box-330" type="text" name="username"
								id="username">
						</div>
					</div>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Password<span class="required error" id="login-password-info"></span>
							</div>
							<input class="input-box-330" type="password"
								name="login-password" id="login-password">
						</div>
					</div>
					<div class="row">
						<input class="btn" type="submit" name="login-btn" style="margin-top: 50px;"
							id="login-btn" value="Login">
					</div>
					<div class="row">
						<a href="forgotpassword.php"> Forgot Password </a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
function loginValidation() {
	var valid = true;
	$("#username").removeClass("error-field");
	$("#password").removeClass("error-field");

	var UserName = $("#username").val();
	var Password = $('#login-password').val();

	$("#username-info").html("").hide();

	if (UserName.trim() == "") {
		$("#username-info").html("required.").css("color", "#ee0000").show();
		$("#username").addClass("error-field");
		valid = false;
	}
	if (Password.trim() == "") {
		$("#login-password-info").html("required.").css("color", "#ee0000").show();
		$("#login-password").addClass("error-field");
		valid = false;
	}
	if (valid == false) {
		$('.error-field').first().focus();
		valid = false;
	}
	return valid;
}
</script>
</BODY>
<?php
include "footer.php";
?>
</HTML>
