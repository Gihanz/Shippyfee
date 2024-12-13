<?php
session_start();
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    session_write_close();
}else {
    // since the username is not set in session, the user is not-logged-in
    // he is trying to access this page unauthorized
    // so let's clear all session variables and redirect him to index
    session_unset();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
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
			<div class="signup-align" style="padding-bottom: 20px; text-align:center">
				We have sent you an e-mail to </br>
				<b> <?php echo $email;?> </b> </br>
				to verify your e-mail address.</br></br></br></br>
				<b>Please Verify E-mail to Continue...</b></br>
			</div>
			<div style="text-align:center;">
				<img src="assets/img/loading.gif" style="width: 200px;" alt="ShippyFee">
			</div>
		</div>
	</div>

</BODY>
<?php
include "footer.php";
?>
</HTML>
