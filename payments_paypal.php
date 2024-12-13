<?php

define('PAYPAL_ID', 'samphilip1992@gmail.com'); 
define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');

define('PAYPAL_RETURN_URL', 'https://shippyfee.com/home.php'); 
define('PAYPAL_CANCEL_URL', 'https://shippyfee.com/home.php'); 
define('PAYPAL_NOTIFY_URL', 'https://shippyfee.com/paypal-notify.php');

session_start();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
	$fullname = $_SESSION["fullname"];
    session_write_close();
} else {
    // since the username is not set in session, the user is not-logged-in
    // he is trying to access this page unauthorized
    // so let's clear all session variables and redirect him to index
    session_unset();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
}

use Phppot\UserDetail;
use Phppot\SiteConfig;

require_once __DIR__ . '/Model/UserDetail.php';
$userDetail = new UserDetail();
$userDetailResult = $userDetail->getUserDetail($username);

?>
<html>
<head>
	<title>Shippyfee</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">  
	<link href="assets/css/phppot-style.css" type="text/css" rel="stylesheet" />
	<link href="assets/css/user-registration.css" type="text/css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<script src="vendor/jquery/3.5.1/jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.min.js"></script>
	<link rel="stylesheet" href="assets/css/dataTables.bootstrap.min.css">
	<style>
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		  border-color: #f0f8ff;
		  padding: 5px;
		  margin-bottom: 15px;
		  color: #f0f8ff;
		}
		
		@media screen and (max-width: 1200px) {
			.col-md-6{
			  display: inline-block;
			  width: 100%;
			}
		}
		
		#submit-btn{
			width: 100px;
			color: #484848; 
			background-color: #eab729;
			padding: 5px;			
			border-radius: 10px;
			margin-left: 20px;
			font-weight: 700;
			font-size: 20px;
		}

	</style>
	<link rel="icon" type="image/png" href="assets/img/favicon.png"/>
</head>

<body style="background-color: #191919">
<?php
include "header.php";
?>
<div class="container-xxl">
	<div class="col-md-3" style="background-color: #292929; text-align: -webkit-center; height: 560px; border-style: solid; display: none">
		<form method="post" action="<?php echo PAYPAL_URL; ?>" id="subscription_0" style="display: none">   
			<!-- Identify your business so that you can collect the payments. -->
			<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
			<!-- Specify a Buy Now button. -->
			<input type="hidden" name="cmd" value="_xclick">
			<!-- Specify details about the item that buyers will purchase. -->
			<input type="hidden" name="item_name" value="<?php echo $username?>">
			<input type="hidden" name="item_number" value="<?php echo $username?>">
			<input type="hidden" name="amount" value="1.01">
			<input type="hidden" name="currency_code" value="USD">
			<!-- Specify URLs -->
			<input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
			<input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">	
			<input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">			
		</form>
		<div class="card" style="width: 28rem; padding-top: 30px">
		  <img src="assets/img/subscription_0.png" class="card-img-top" alt="basic">
		  <div class="card-body" style="color: white">
			<h2 class="card-title">Lite</h5>
			<p class="card-text">(For beginners)</p>
		  </div>
		  <ul class="list-group list-group-flush" style="font-weight: 900; font-size: 20px">
			<li class="list-group-item" style="background-color: #e8e8e8;">Price : $1.01 USD</li>
			<li class="list-group-item">25,000 Items</li>
			<li class="list-group-item" style="background-color: #e8e8e8;">Unlimited Period</li>
		  </ul>
		</div>
		<button id="submit-btn" type="submit" form="subscription_0" value="Submit">Buy</button>
	</div>
	<div class="col-md-4" style="background-color: #292929; text-align: -webkit-center; height: 560px; border-style: solid">
		<form method="post" action="<?php echo PAYPAL_URL; ?>" id="subscription_1" style="display: none">   
			<!-- Identify your business so that you can collect the payments. -->
			<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
			<!-- Specify a Buy Now button. -->
			<input type="hidden" name="cmd" value="_xclick">
			<!-- Specify details about the item that buyers will purchase. -->
			<input type="hidden" name="item_name" value="<?php echo $username?>">
			<input type="hidden" name="item_number" value="<?php echo $username?>">
			<input type="hidden" name="amount" value="2.82">
			<input type="hidden" name="currency_code" value="USD">
			<!-- Specify URLs -->
			<input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
			<input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">	
			<input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">			
		</form>
		<div class="card" style="width: 28rem; padding-top: 30px">
		  <img src="assets/img/subscription_1.png" class="card-img-top" alt="basic">
		  <div class="card-body" style="color: white">
			<h2 class="card-title">Basic</h5>
			<p class="card-text">(For smaller sellers)</p>
		  </div>
		  <ul class="list-group list-group-flush" style="font-weight: 900; font-size: 20px">
			<li class="list-group-item" style="background-color: #e8e8e8;">Price : $2.82 USD</li>
			<li class="list-group-item">50,000 Items</li>
			<li class="list-group-item" style="background-color: #e8e8e8;">Unlimited Period</li>
		  </ul>
		</div>
		<button id="submit-btn" type="submit" form="subscription_1" value="Submit">Buy</button>
	</div>
	<div class="col-md-4" style="background-color: #292929; text-align: -webkit-center; height: 600px; border-style: solid">
		<form method="post" action="<?php echo PAYPAL_URL; ?>" id="subscription_2" style="display: none">   
			<!-- Identify your business so that you can collect the payments. -->
			<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
			<!-- Specify a Buy Now button. -->
			<input type="hidden" name="cmd" value="_xclick">
			<!-- Specify details about the item that buyers will purchase. -->
			<input type="hidden" name="item_name" value="<?php echo $username?>">
			<input type="hidden" name="item_number" value="<?php echo $username?>">
			<input type="hidden" name="amount" value="4.82">
			<input type="hidden" name="currency_code" value="USD">
			<!-- Specify URLs -->
			<input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
			<input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
			<input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">			
		</form>
		<div class="card" style="width: 28rem; padding-top: 30px">
		  <img src="assets/img/subscription_2.png" class="card-img-top" alt="advanced">
		  <div class="card-body" style="color: white">
			<h2 class="card-title">Advanced</h5>
			<p class="card-text">(For medium scale sellers)</p>
		  </div>
		  <ul class="list-group list-group-flush" style="font-weight: 900; font-size: 20px">
			<li class="list-group-item" style="background-color: #e8e8e8;">Price : $4.82 USD</li>
			<li class="list-group-item">100,000 Items</li>
			<li class="list-group-item" style="background-color: #e8e8e8;">Unlimited Period</li>
		  </ul>
		</div>
		<button id="submit-btn" type="submit" form="subscription_2" value="Submit">Buy</button>
	</div>
	<div class="col-md-4" style="background-color: #292929; text-align: -webkit-center; height: 560px; border-style: solid">
		<form method="post" action="<?php echo PAYPAL_URL; ?>" id="subscription_3" style="display: none">   
			<!-- Identify your business so that you can collect the payments. -->
			<input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
			<!-- Specify a Buy Now button. -->
			<input type="hidden" name="cmd" value="_xclick">
			<!-- Specify details about the item that buyers will purchase. -->
			<input type="hidden" name="item_name" value="<?php echo $username?>">
			<input type="hidden" name="item_number" value="<?php echo $username?>">
			<input type="hidden" name="amount" value="6.82">
			<input type="hidden" name="currency_code" value="USD">
			<!-- Specify URLs -->
			<input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
			<input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
			<input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">			
		</form>
		<div class="card" style="width: 28rem; padding-top: 30px">
		  <img src="assets/img/subscription_3.png" class="card-img-top" alt="pro">
		  <div class="card-body" style="color: white">
			<h2 class="card-title">Pro</h5>
			<p class="card-text">(For larger scale sellers)</p>
		  </div>
		  <ul class="list-group list-group-flush" style="font-weight: 900; font-size: 20px">
			<li class="list-group-item" style="background-color: #e8e8e8;">Price : $6.82 USD</li>
			<li class="list-group-item">150,000 Items</li>
			<li class="list-group-item" style="background-color: #e8e8e8;">Unlimited Period</li>
		  </ul>
		</div>
		<button id="submit-btn" type="submit" form="subscription_3" value="Submit">Buy</button>
	</div>
	<div class="col-md-12" style="background-color: #292929; text-align: -webkit-center; color: white; padding: 20px; font-size: 16px; margin-top: 15px">
		For more inqueries contact us</br>(+94) 777 331 308</br><a target="_blank" href="https://www.facebook.com/Shippyfee">https://www.facebook.com/Shippyfee</a>
	</div>
</div>

</body>

</html>