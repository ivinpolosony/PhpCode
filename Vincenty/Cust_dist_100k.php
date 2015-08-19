<?php 
require_once("Customer.php");

$OfficeLat = 53.3381985; // Office Latitude 
$OfficeLong =  -6.2592576; // Office Longitude 
$urlCustomer = 'https://gist.githubusercontent.com/brianw/19896c50afa89ad4dec3/raw/6c11047887a03483c50017c1d451667fd62a53ca/gistfile1.txt'; // Customer Records 
$arrCust_Close = [];
$objCust = new Customers();
$arrJson = $objCust->getCustomerRecords($urlCustomer);

foreach($arrJson as $key => $arr){
	foreach($arr as $k => $arrCust){
		$floatDist =   $objCust->getDistanceVincentyFormula($OfficeLat,$OfficeLong,$arrCust['latitude'],$arrCust['longitude']);
		if($floatDist <= 100){ // Within 100kms 
			$arrCust_Close[$arrCust['user_id']]['distance'] = $objCust->getDistanceVincentyFormula($OfficeLat,$OfficeLong,$arrCust['latitude'],$arrCust['longitude']);
			$arrCust_Close[$arrCust['user_id']]['name'] = $arrCust['name']; 
		}
	}
}
ksort($arrCust_Close);

foreach ($arrCust_Close as $key => $value) {
	echo "USER ID ==> ".$key ."\n";
	echo "NAME ==> ".$value['name'] ."\n"; 
	echo "USER ID ==> ".$value['distance'] ." Kms\n"; 
	echo "\n";

}

?>