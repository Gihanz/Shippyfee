<?php
use Phppot\Member;
if (! empty($_POST["signup-btn"])) {
    require_once './Model/Member.php';
    $member = new Member();
    $registrationResponse = $member->registerMember();
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
				<a href="index.php" style="background-color: #ffc72c; color: #565656; padding: 7px; border-radius: 3px; margin-right: 20px;">Login</a>
			</div>
			<div class="">
				<form name="sign-up" action="" method="post"
					onsubmit="return signupValidation()">
					<div class="signup-heading">Registration</div>
				<?php
    if (! empty($registrationResponse["status"])) {
        ?>
                    <?php
        if ($registrationResponse["status"] == "error") {
            ?>
				    <div class="server-response error-msg"><?php echo $registrationResponse["message"]; ?></div>
                    <?php
        } else if ($registrationResponse["status"] == "success") {
            ?>
                    <div class="server-response success-msg"><?php echo $registrationResponse["message"]; ?></div>
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
								Full Name<span class="required error" id="fullname-info"></span>
							</div>
							<input class="input-box-330" type="text" name="fullname"
								id="fullname">
						</div>
					</div>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Mobile Number<span class="required error" id="phone-info"></span>
							</div>
							<input class="input-box-330" type="text" name="phone" id="phone">
						</div>
					</div>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Email<span class="required error" id="email-info"></span>
							</div>
							<input class="input-box-330" type="email" name="email" id="email">
						</div>
					</div>
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
								Password<span class="required error" id="signup-password-info"></span>
							</div>
							<input class="input-box-330" type="password"
								name="signup-password" id="signup-password">
						</div>
					</div>
					<div class="row">
						<div class="inline-block">
							<div class="form-label">
								Confirm Password<span class="required error"
									id="confirm-password-info"></span>
							</div>
							<input class="input-box-330" type="password"
								name="confirm-password" id="confirm-password">
						</div>
					</div>
					<div class="row">
						<input class="btn" type="submit" name="signup-btn" style="margin-top: 50px;"
							id="signup-btn" value="Sign up">
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
function signupValidation() {
	var valid = true;

	$("#fullname").removeClass("error-field");
	$("#phone").removeClass("error-field");
	$("#username").removeClass("error-field");
	$("#email").removeClass("error-field");
	$("#password").removeClass("error-field");
	$("#confirm-password").removeClass("error-field");

	var FullName = $('#fullname').val();
	var phone = $('#phone').val();
	var UserName = $("#username").val();
	var email = $("#email").val();
	var Password = $('#signup-password').val();
    var ConfirmPassword = $('#confirm-password').val();
	var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
	var phoneRegex = /^(?:0|94|\+94)?(?:(11|21|23|24|25|26|27|31|32|33|34|35|36|37|38|41|45|47|51|52|54|55|57|63|65|66|67|81|912)(0|2|3|4|5|7|9)|7(0|1|2|5|6|7|8)\d)\d{6}$/;

	$("#username-info").html("").hide();
	$("#email-info").html("").hide();

	if (FullName.trim() == "") {
		$("#fullname-info").html("required.").css("color", "#ee0000").show();
		$("#fullname").addClass("error-field");
		valid = false;
	}
	if (phone == "") {
		$("#phone-info").html("required").css("color", "#ee0000").show();
		$("#phone").addClass("error-field");
		valid = false;
	} else if (phone.trim() == "") {
		$("#phone-info").html("Invalid phone number.").css("color", "#ee0000").show();
		$("#phone").addClass("error-field");
		valid = false;
	} else if (!phoneRegex.test(phone)) {
		$("#phone-info").html("Invalid phone number.").css("color", "#ee0000")
				.show();
		$("#phone").addClass("error-field");
		valid = false;
	}
	if (UserName.trim() == "") {
		$("#username-info").html("required.").css("color", "#ee0000").show();
		$("#username").addClass("error-field");
		valid = false;
	}
	if (email == "") {
		$("#email-info").html("required").css("color", "#ee0000").show();
		$("#email").addClass("error-field");
		valid = false;
	} else if (email.trim() == "") {
		$("#email-info").html("Invalid email address.").css("color", "#ee0000").show();
		$("#email").addClass("error-field");
		valid = false;
	} else if (!emailRegex.test(email)) {
		$("#email-info").html("Invalid email address.").css("color", "#ee0000")
				.show();
		$("#email").addClass("error-field");
		valid = false;
	}
	if (Password.trim() == "") {
		$("#signup-password-info").html("required.").css("color", "#ee0000").show();
		$("#signup-password").addClass("error-field");
		valid = false;
	}
	if (ConfirmPassword.trim() == "") {
		$("#confirm-password-info").html("required.").css("color", "#ee0000").show();
		$("#confirm-password").addClass("error-field");
		valid = false;
	}
	if(Password != ConfirmPassword){
        $("#error-msg").html("Both passwords must be same.").show();
        valid=false;
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
