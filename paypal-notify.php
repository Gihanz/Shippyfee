<?php 

use Phppot\UserDetail;

define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');

/* 
 * Read POST data 
 * reading posted data directly from $_POST causes serialization 
 * issues with array data in POST. 
 * Reading raw POST data from input stream instead. 
 */         
$raw_post_data = file_get_contents('php://input'); 
$raw_post_array = explode('&', $raw_post_data); 
$myPost = array(); 
foreach ($raw_post_array as $keyval) { 
    $keyval = explode ('=', $keyval); 
    if (count($keyval) == 2) 
        $myPost[$keyval[0]] = urldecode($keyval[1]); 
} 
 
// Read the post from PayPal system and add 'cmd' 
$req = 'cmd=_notify-validate'; 
if(function_exists('get_magic_quotes_gpc')) { 
    $get_magic_quotes_exists = true; 
} 
foreach ($myPost as $key => $value) { 
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
        $value = urlencode(stripslashes($value)); 
    } else { 
        $value = urlencode($value); 
    } 
    $req .= "&$key=$value"; 
} 
 
/* 
 * Post IPN data back to PayPal to validate the IPN data is genuine 
 * Without this step anyone can fake IPN data 
 */ 
$paypalURL = PAYPAL_URL; 
$ch = curl_init($paypalURL); 
if ($ch == FALSE) { 
    return FALSE; 
} 
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $req); 
curl_setopt($ch, CURLOPT_SSLVERSION, 6); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
 
// Set TCP timeout to 30 seconds 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close')); 
$res = curl_exec($ch); 
 
/* 
 * Inspect IPN validation result and act accordingly 
 * Split response headers and payload, a better way for strcmp 
 */  
$tokens = explode("\r\n\r\n", trim($res)); 
$res = trim(end($tokens)); 
if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) { 
     
    // Retrieve transaction info from PayPal 
    $item_number = $_POST['item_number']; 
    $txn_id = $_POST['txn_id']; 
    $payment_gross = $_POST['mc_gross']; 
    $currency_code = $_POST['mc_currency']; 
    $payment_status = $_POST['payment_status']; 
     
    $isPaymentCompleted = false;
	if($payment_status == "Completed") {
		$isPaymentCompleted = true;
	}
	
	if($isPaymentCompleted || $payment_status == "Pending") {
		
	    if($payment_gross == 6.82){
			$max_search_count = 150000;
		}else if($payment_gross == 4.82){
			$max_search_count = 100000;		
		}else if($payment_gross == 2.82){
			$max_search_count = 50000;		
		}else if($payment_gross == 1.01){
			$max_search_count = 25000;		
		}else{
			$max_search_count = 10000;
		}
		
		require_once __DIR__ . '/Model/UserDetail.php';
		$userDetail = new UserDetail();
		$userDetail->setAsPaidUser($item_number, $max_search_count);
	    
	} 
 
} 
?>