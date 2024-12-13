<?php
use Phppot\ForgotPassword;

if (! empty($_POST["forgotpassword-btn"])) {
    require_once __DIR__ . '/Model/ForgotPassword.php';
    $forgotPassword = new ForgotPassword();
    $forgotPasswordResponse = $forgotPassword->sendCredentials();
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
				<a href="login.php" style="background-color: #ffc72c; color: #565656; padding: 7px; border-radius: 3px; margin-right: 20px;">Login</a>
			</div>
			<div class="signup-align">
				<form name="forgotpassword" action="" method="post"
					onsubmit="return forgotPasswordValidation()">
					<div class="signup-heading">Forgot Password</div>
				<?php
			if (! empty($forgotPasswordResponse["status"])) {
				?>
							<?php
				if ($forgotPasswordResponse["status"] == "error") {
					?>
							<div class="server-response error-msg"><?php echo $forgotPasswordResponse["message"]; ?></div>
							<?php
				} else if ($forgotPasswordResponse["status"] == "success") {
					?>
							<div class="server-response success-msg"><?php echo $forgotPasswordResponse["message"]; ?></div>
							<?php
				}
				?>
						<?php
			}
			?>
				<div class="error-msg" id="error-msg"></div>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Email Address<span class="required error" id="email-info"></span>
							</div>
							<input class="input-box-330" type="text" name="email"
								id="email">
						</div>
					</div>
					<div class="row">
						<input class="btn" type="submit" name="forgotpassword-btn" style="margin-top: 50px;"
							id="forgotpassword-btn" value="Send Credential to Email">
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
function forgotPasswordValidation() {
	var valid = true;
	$("#email").removeClass("error-field");

	var Email = $("#email").val();

	$("#email-info").html("").hide();

	if (Email.trim() == "") {
		$("#email-info").html("required.").css("color", "#ee0000").show();
		$("#email").addClass("error-field");
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
