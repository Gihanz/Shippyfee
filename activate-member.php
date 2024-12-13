<?php

use Phppot\Activation;
		
if(!empty($_GET['code']) && isset($_GET['code'])){

	$code=$_GET['code'];

	require_once __DIR__ . '/Model/Activation.php';
	$activation = new Activation();
	$countRecord_1 = $activation->validateActivationCode($code);

	if($countRecord_1>0){		
	
		$countRecord_2 = $activation->checkAllreadyActivated($code);
		
		if($countRecord_2>0){
			$activation->activate($code);
			$msg="Your account is successfully activated !</br></br></br><a href='index.php' style='background-color: #ffc72c; color: #565656; padding: 7px; border-radius: 3px; margin-right: 20px; font-weight: 700'>Click here to login</a>";			
		}else{
			$msg ="Your account is already active, no need to activate again. </br></br></br><a href='index.php' style='background-color: #ffc72c; color: #565656; padding: 7px; border-radius: 3px; margin-right: 20px; font-weight: 700'>Click here to login</a>";
		}		
	}else{
		$msg ="Wrong activation code.";
	}	
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
			<div class="signup-align" style="padding-bottom: 20px;">
				<?php echo $msg?></br></br></br></br></br>
			</div>
			<div style="text-align:center;">
				<img src="assets/img/favicon.png" style="width: 100px;" alt="ShippyFee">
			</div>
		</div>
	</div>

</BODY>
<?php
include "footer.php";
?>
</HTML>
