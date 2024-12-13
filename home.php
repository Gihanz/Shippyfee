<?php
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

use Phppot\Product;
use Phppot\ShippingInfo;
use Phppot\UserDetail;
use Phppot\SiteConfig;

require_once __DIR__ . '/Model/UserDetail.php';
$userDetail = new UserDetail();
$userDetailResult = $userDetail->getUserDetail($username);

if (! empty($_POST["search-btn"])) {
	if(! empty($_POST["product_url"])){
		$prod_id = explode("item/",$_POST["product_url"]);
		if (isset($prod_id[1])) {
			$prod_id = explode(".html",$prod_id[1])[0];

			require_once __DIR__ . '/Model/Product.php';
			$product = new Product();
			$productResult = $product->getProduct($prod_id, $username, $userDetailResult);
			
			if(! empty($productResult)){
				require_once __DIR__ . '/Model/ShippingInfo.php';
				$shippingInfo = new ShippingInfo();
				$shippingInfoResult = $shippingInfo->getShippingInfo($prod_id, $productResult[0]['min_activity_amount'], $userDetailResult);			
			}
					
			require_once __DIR__ . '/Model/UserDetail.php';
			$userDetail = new UserDetail();
			$userDetailResult = $userDetail->getUserDetail($username);
		}
	}    
}

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
		#overlay {
		  position: fixed;
		  display: none;
		  width: 100%;
		  height: 100%;
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		  background-color: rgb(0 0 0 / 78%);
		  z-index: 2;
		  cursor: pointer;
		}

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
		
		#search-btn{
			width: 100px;
			color: #484848; 
			background-color: #eab729; 
			border-radius: 5px;
			margin-left: 20px;
		}
		
		#shippingData_filter {
			color: #f0f8ff;
		}

	</style>
	<link rel="icon" type="image/png" href="assets/img/favicon.png"/>
</head>

<body style="background-color: #191919">
<?php
include "header.php";
?>
<div id="overlay"><img src="assets/img/loading.gif" style="display: block; margin: 200px auto 0 auto;"></div>
<div class="container-xxl">
  <div class="col-md-4" style="background-color: #292929">
	<div style="color: aliceblue;">
		<form name="home" action="" method="post" onsubmit="return splashOn()" style="padding-top: 10px">
			<label>* Product URL :</label>
			<input type="text" id="product_url" name="product_url" style="margin: 15px; color: black">
			<input type="submit" id="search-btn" name="search-btn" value="Search">
			<?php if(empty($productResult)){?>
				<label class="error-msg" style="padding-left: 30px;">No Result Found !</label>
			<?php }?>						
		</form>
	</div>
	<table width="100%">
		<tbody>
			<tr style="background-color:#101010">
				<td style="width: 155px;">Product ID:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['product_id'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr>
				<td>Product Title:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['product_title'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr style="background-color:#101010">
				<td>Available Quantity:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['available_quantity'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr>
				<td>Currency:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['currency'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr style="background-color:#101010">
				<td>Amount:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['amount']; if($productResult[0]['min_activity_amount']){echo " - ".$productResult[0]['min_activity_amount'];}} else {echo "|-----------------------------------|";}?></td>
			</tr>			
			<tr>
				<td>Formated Amount:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['formated_amount']; if($productResult[0]['min_activity_formated_amount']){echo " - ".$productResult[0]['min_activity_formated_amount'];}} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr style="background-color:#101010">
				<td>Ships From:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['ships_from'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr style="background-color:#101010">
				<td>Item Wished Count:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['item_wished_count'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr>
				<td>Trade Count:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['trade_count'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr style="background-color:#101010">
				<td>Review Count:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['review_count'];} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr>
				<td>Average Rating:</td>
				<td><?php if(!empty($productResult)){echo $productResult[0]['average_rating']." Star";} else {echo "|-----------------------------------|";}?></td>
			</tr>
			<tr style="background-color:#101010">
				<td>Image:</td>
				<td><img src=<?php if(!empty($productResult)){echo $productResult[0]['product_img'];} else{echo "assets/img/file.png";}?> alt="Product Image" width="180" height="150" style="padding: 10px 0 10px 10px"></td>
			</tr>
		</tbody>
	</table>
	
	<div class="row">
	  <div class="col-md-6">
		<table>
		  <tr style="background-color: #101010;">
			<th colspan="4">Shipping Regions</th>
		  </tr>
		  <tr>
			<td style="background-color: #4cfd32; width: 40px"></td>
			<td>Asia</td>
			<td style="background-color: #00ecd6; width: 40px"></td>
			<td>Central America and Caribbean</td>
		  </tr>
		  <tr>
			<td style="background-color: #f0f8ff"></td>
			<td>Africa</td>
			<td style="background-color: #ff99fc"></td>
			<td>South America</td>
		  </tr>
		  <tr>
			<td style="background-color: #879bff"></td>
			<td>Europe</td>
			<td style="background-color: #ff5151"></td>
			<td>North America</td>
		  </tr>
		  <tr>
			<td style="background-color: #dcff87"></td>
			<td>Oceania</td>
		  </tr>
		</table>
	  </div>
	  <div class="col-md-6">
		<table>
		  <tr style="background-color: #101010;">
			<th colspan="2">Cost Amount (USD)</th>
		  </tr>
		  <tr>
			<td style="background-color: #f0f8ff; width: 40px"></td>
			<td>< 100</td>
		  </tr>
		  <tr>
			<td style="background-color: #ffeb00"></td>
			<td>100 ~ 1000</td>
		  </tr>
		  <tr>
			<td style="background-color: #ff8d00"></td>
			<td>> 1000</td>
		  </tr>
		  <tr>
			<td style="background-color: red"></td>
			<td>Not Available &nbsp;</td>
		  </tr>
		</table>
	  </div>
	</div>
	
  </div>
  <div class="col-md-8" style="background-color: #191919">
  
	<table id="shippingData" style="overflow-x: auto; overflow-y: auto; display: block; max-height: 800px;">
		<thead style="background-color: #415771">
			<tr>
				<th>From Country</th>
				<th>To Country</th>
				<th>To Region</th>
				<th>Commit Day</th>
				<th>Company</th>
				<th>Estimated Delivery</th>
				<th>Amount (USD)</th>
				<th>Tracking</th>
			</tr>
		</thead>
		<tbody>
		<?php if(!empty($shippingInfoResult)){
				for ($row = 0; $row < count($shippingInfoResult); $row ++) {
		?>
			<tr>
				<td><?php echo $shippingInfoResult[$row]['send_goods_country_fullname']; ?></td>
				<td><?php echo $shippingInfoResult[$row]['to_goods_country_fullname']; ?></td>
				<td><?php echo $shippingInfoResult[$row]['to_goods_country_region']; ?></td>
				<td><?php echo $shippingInfoResult[$row]['commit_day']; ?></td>
				<td><?php echo $shippingInfoResult[$row]['company']; ?></td>
				<td><?php echo $shippingInfoResult[$row]['estimated_delivery']." days"; ?></td>
				<td><?php echo $shippingInfoResult[$row]['amount']; ?></td>
				<td><?php echo $shippingInfoResult[$row]['tracking']; ?></td>
			</tr>
		<?php
				}
			}
		?>
		</tbody>
	</table>

  </div>
</div>

<script>
	function splashOn() {
	  document.getElementById("overlay").style.display = "block";
	}
	
	$(document).ready(function() {
		
		$("#shippingData").DataTable({"paging":false, "info":false, "scrollX": true, "order": [[1, "asc"]]});
		
		var shipDataTable = document.getElementById("shippingData");
		var rows = shipDataTable.getElementsByTagName("tr");

		for(i = 1; i < rows.length; i++){
			if(rows[i].cells[1].innerHTML != rows[i-1].cells[1].innerHTML){
				if(rows[i-1].style.backgroundColor == ""){
					rows[i].style.backgroundColor = "#3a3a3a";
				}else{
					rows[i].style.backgroundColor = "";
				}
			}else{				
				rows[i].style.backgroundColor = rows[i-1].style.backgroundColor;
			}
			
			var from_country = rows[i].cells[0];
			if(from_country.innerHTML == "Not available"){
				from_country.style.color = "red";
			}
			
			var region = rows[i].cells[2];
			if(region.innerHTML == "Africa"){
				region.style.color = "#f0f8ff";
			}else if(region.innerHTML == "Asia"){
				region.style.color = "#57e068";	
			}else if(region.innerHTML == "Central America and Caribbean"){
				region.style.color = "#00ecd6";
			}else if(region.innerHTML == "Europe"){
				region.style.color = "#879bff";
			}else if(region.innerHTML == "North America"){
				region.style.color = "#ff5151";
			}else if(region.innerHTML == "Oceania"){
				region.style.color = "#dcff87";
			}else if(region.innerHTML == "South America"){
				region.style.color = "#ff99fc";
			}
			
			var company = rows[i].cells[4];
			if(company.innerHTML == "Not available"){
				company.style.color = "red";
			}
			
			var amount = rows[i].cells[6];
			if(parseFloat(amount.innerHTML) > 100 && parseFloat(amount.innerHTML) < 1000){
				amount.style.color = "#ffeb00";
			}else if(parseFloat(amount.innerHTML) > 1000 && amount.innerHTML != "99999.99"){
				amount.style.color = "#ff8d00";	
			}else if(amount.innerHTML == "99999.99"){
				amount.style.color = "red";	
			}
		}
		
	});
</script>

</body>

</html>