<?php

use Phppot\UserDetail;

$merchant_id = $_POST['merchant_id'];
$order_id = $_POST['order_id'];
$payhere_amount = $_POST['payhere_amount'];
$payhere_currency = $_POST['payhere_currency'];
$status_code = $_POST['status_code'];
$md5sig = $_POST['md5sig'];

$merchant_secret = '4vXBISyFnr28bT9ANACEaH4E1BnTFwWA84PVs5ZbPNdh'; // Replace with your Merchant Secret.

$local_md5sig = strtoupper (md5 ( $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret)) ) );

if (($local_md5sig === $md5sig) AND ($status_code == 2) ){
	
	if($payhere_amount == 1300){
		$max_search_count = 150;
	}else if($payhere_amount == 900){
		$max_search_count = 100;		
	}else if($payhere_amount == 500){
		$max_search_count = 50;		
	}else if($payhere_amount == 300){
		$max_search_count = 25;		
	}else{
		$max_search_count = 10;
	}
	
	require_once __DIR__ . '/Model/UserDetail.php';
	$userDetail = new UserDetail();
	$userDetail->setAsPaidUser($order_id, $max_search_count);

}

?>