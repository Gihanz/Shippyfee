<?php
namespace Phppot;

class ShippingInfo
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }  

    public function getShippingInfo($productId, $min_price, $userDetailResult)
    {
        $query = 'SELECT * FROM tbl_shipping where product_id = ? AND is_active = 1';
        $paramType = 's';
        $paramValue = array(
            $productId
        );
        $shippingRecord = $this->ds->select($query, $paramType, $paramValue);
		
		if(empty($shippingRecord) && $userDetailResult[0]['max_search_count']>$userDetailResult[0]['search_count']){
						
			// Getting Product Shipping Details from API.			
			$query = 'SELECT * FROM tbl_country where is_active = 1';
			$paramType = '';
			$paramValue = array(
			);
			$countryRecord = $this->ds->select($query, $paramType, $paramValue);
			
			foreach ($countryRecord as $country){
				
				$shipCurl = curl_init();
				curl_setopt_array($shipCurl, [
					CURLOPT_URL => "https://www.aliexpress.com/aeglodetailweb/api/logistics/freight?productId=".$productId."&count=1&minPrice=".$min_price."&country=".$country['country_value']."&tradeCurrency=USD",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => [
						"user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36",
						"referer: https://aliexpress.com/item/".$productId.".html"
					],
				]);

				$shipResponse = json_decode(curl_exec($shipCurl), true);
				$err = curl_error($shipCurl);
				curl_close($shipCurl);

				if(!empty($shipResponse['body']['freightResult'])){
					foreach ($shipResponse['body']['freightResult'] as $freightResult) {			
						$product_id = $productId;
						$send_goods_country = isset($freightResult['sendGoodsCountry']) ? $freightResult['sendGoodsCountry'] : "-";
						$send_goods_country_fullname = isset($freightResult['sendGoodsCountryFullName']) ? $freightResult['sendGoodsCountryFullName'] : "-";
						$to_goods_country = $country['country_value'];
						$to_goods_country_fullname = $country['country_name'];
						$to_goods_country_region = $country['region'];
						$commit_day = isset($freightResult['commitDay']) ? $freightResult['commitDay'] : 0;
						$company = isset($freightResult['company']) ? $freightResult['company'] : "-";
						$currency = isset($freightResult['currency']) ? $freightResult['currency'] : "-";
						$estimated_delivery = isset($freightResult['time']) ? $freightResult['time'] : "0";
						$discount = isset($freightResult['discount']) ? $freightResult['discount'] : "0";
						$amount = isset($freightResult['freightAmount']['value']) ? $freightResult['freightAmount']['value'] : "0";
						$formated_amount = isset($freightResult['freightAmount']['formatedAmount']) ? $freightResult['freightAmount']['formatedAmount'] : "-";						
						if(isset($freightResult['tracking'])){
							if($freightResult['tracking'] == true){
								$tracking = "Yes";
							}else{
								$tracking = "No";
							}							
						}else{
							$tracking = "No";
						}
						
						$query = 'INSERT INTO tbl_shipping (product_id, send_goods_country, send_goods_country_fullname, to_goods_country, to_goods_country_fullname, to_goods_country_region, commit_day, company, currency, estimated_delivery, discount, amount, formated_amount, tracking) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
						$paramType = 'ssssssssssssss';
						$paramValue = array(
							$product_id,
							$send_goods_country,
							$send_goods_country_fullname,
							$to_goods_country,
							$to_goods_country_fullname,
							$to_goods_country_region,
							$commit_day,
							$company,
							$currency,
							$estimated_delivery,
							$discount,
							$amount,
							$formated_amount,
							$tracking				
						);
						$id = $this->ds->insert($query, $paramType, $paramValue);				
					}
				} else{
					
					$product_id = $productId;
					$send_goods_country = "NaN";
					$send_goods_country_fullname = "Not available";
					$to_goods_country = $country['country_value'];
					$to_goods_country_fullname = $country['country_name'];
					$to_goods_country_region = $country['region'];
					$commit_day = 0;
					$company = "Not available";
					$currency = "-";
					$estimated_delivery = "0";
					$discount = "0";
					$amount = "99999.99";
					$formated_amount = "99999.99";
					$tracking = "No";
						
					$query = 'INSERT INTO tbl_shipping (product_id, send_goods_country, send_goods_country_fullname, to_goods_country, to_goods_country_fullname, to_goods_country_region, commit_day, company, currency, estimated_delivery, discount, amount, formated_amount, tracking) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
					$paramType = 'ssssssssssssss';
					$paramValue = array(
						$product_id,
						$send_goods_country,
						$send_goods_country_fullname,
						$to_goods_country,
						$to_goods_country_fullname,
						$to_goods_country_region,
						$commit_day,
						$company,
						$currency,
						$estimated_delivery,
						$discount,
						$amount,
						$formated_amount,
						$tracking				
					);
					$id = $this->ds->insert($query, $paramType, $paramValue);					
				}
										
			}
					
			$query = 'SELECT * FROM tbl_shipping where product_id = ? AND is_active = 1';
			$paramType = 's';
			$paramValue = array(
				$productId
			);
			$shippingRecord = $this->ds->select($query, $paramType, $paramValue);
							
		}
		
        return $shippingRecord;
    }
  
}
