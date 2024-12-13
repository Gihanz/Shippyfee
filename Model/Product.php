<?php
namespace Phppot;
include (dirname(__FILE__).'/../lib/simple_html_dom.php');

class Product
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . '/../lib/DataSource.php';
        $this->ds = new DataSource();
    }  

    public function getProduct($productId, $username, $userDetailResult)
    {
        $query = 'SELECT * FROM tbl_product where product_id = ? AND is_active = 1';
        $paramType = 's';
        $paramValue = array(
            $productId
        );
        $productRecord = $this->ds->select($query, $paramType, $paramValue);
		
		if(empty($productRecord) && $userDetailResult[0]['max_search_count']>$userDetailResult[0]['search_count']){
			
			// Extract Product Basic Details from Page.
			$html_dom = file_get_html('https://www.aliexpress.com/item/'.$productId.'.html');
			$finding = $html_dom->find('script', 17);
			
			if(!empty($finding)){
			    $split_1 = explode("data:",$finding)[1];
			    $split_2 = explode("csrfToken",$split_1)[0];
				
			    $new_str = trim($split_2);
			    $new_str2 = substr_replace($new_str ,"",-1);
			    $prodResponse = json_decode($new_str2, true);
			}
			
			if(isset($prodResponse['actionModule']['productId'])){
				
				$product_id = isset($prodResponse['actionModule']['productId']) ? $prodResponse['actionModule']['productId'] : "-";
				$product_title = isset($prodResponse['titleModule']['subject']) ? $prodResponse['titleModule']['subject'] : "-";
				$product_img = isset($prodResponse['pageModule']['imagePath']) ? $prodResponse['pageModule']['imagePath'] : "-";
				$description = isset($prodResponse['descriptionModule']['descriptionUrl']) ? $prodResponse['descriptionModule']['descriptionUrl'] : "-";
				$available_quantity = isset($prodResponse['actionModule']['totalAvailQuantity']) ? $prodResponse['actionModule']['totalAvailQuantity'] : 0;
				$currency = isset($prodResponse['priceModule']['maxAmount']['currency']) ? $prodResponse['priceModule']['maxAmount']['currency'] : "-";
				$min_activity_amount = isset($prodResponse['priceModule']['minActivityAmount']['value']) ? $prodResponse['priceModule']['minActivityAmount']['value'] : "-";	
				$min_activity_formated_amount = isset($prodResponse['priceModule']['minActivityAmount']['formatedAmount']) ? $prodResponse['priceModule']['minActivityAmount']['formatedAmount'] : "-";	
				$amount = isset($prodResponse['priceModule']['maxAmount']['value']) ? $prodResponse['priceModule']['maxAmount']['value'] : "-";					
				$formated_amount = isset($prodResponse['priceModule']['maxAmount']['formatedAmount']) ? $prodResponse['priceModule']['maxAmount']['formatedAmount'] : "-";
				$ships_from = isset($prodResponse['shippingModule']['regionCountryName']) ? $prodResponse['shippingModule']['regionCountryName'] : "-";
				$store_url = isset($prodResponse['storeModule']['storeURL']) ? $prodResponse['storeModule']['storeURL'] : "-";
				$item_wished_count = isset($prodResponse['actionModule']['itemWishedCount']) ? $prodResponse['actionModule']['itemWishedCount'] : 0;
				$trade_count = isset($prodResponse['titleModule']['tradeCount']) ? $prodResponse['titleModule']['tradeCount'] : 0;
				$review_count = isset($prodResponse['titleModule']['feedbackRating']['totalValidNum']) ? $prodResponse['titleModule']['feedbackRating']['totalValidNum'] : 0;
				$average_rating = isset($prodResponse['titleModule']['feedbackRating']['averageStar']) ? $prodResponse['titleModule']['feedbackRating']['averageStar'] : 0;
				

				$query = 'INSERT INTO tbl_product (product_id, product_title, product_img, description, available_quantity, currency, min_activity_amount, min_activity_formated_amount, amount, formated_amount, ships_from, store_url, item_wished_count, trade_count, review_count, average_rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
				$paramType = 'ssssssssssssssss';
				$paramValue = array(
					$product_id,
					$product_title,
					$product_img,
					$description,
					$available_quantity,
					$currency,
					$min_activity_amount,	
					$min_activity_formated_amount,
					$amount,					
					$formated_amount,
					$ships_from,
					$store_url,
					$item_wished_count,
					$trade_count,
					$review_count,
					$average_rating
				);
				$id = $this->ds->insert($query, $paramType, $paramValue);
				
			}
			
			$query = 'SELECT * FROM tbl_product where product_id = ? AND is_active = 1';
			$paramType = 's';
			$paramValue = array(
				$productId
			);
			$productRecord = $this->ds->select($query, $paramType, $paramValue);
			
			$query = 'UPDATE tbl_member SET search_count = search_count+1 where username = ?';
            $paramType = 's';
            $paramValue = array(
                $username
            );
			$searchCountRecord = $this->ds->insert($query, $paramType, $paramValue);
			
		}
		
        return $productRecord;
    }
  
}
