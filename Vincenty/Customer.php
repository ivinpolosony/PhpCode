<?php 
define("EARTH_RADIUS",6371.009);
class Customer{
	private $floatRadius;

	public function __construct(){
		$this->floatRadius =  EARTH_RADIUS;// Earth's Radius in Kilometers
	}
	
	/**
	* Function name : public function getCustomerRecords(&String)
	* Use: Gets the customer records in the form of a json array from the the specified source i.e url or a file path
	* Version : 1.0
	* @param String $urlCustomer  
	* @return Mixed  
	*/
	public function getCustomerRecords($urlCustomer){
		$response = @file_get_contents((string)$urlCustomer);
		
		if($response === FALSE){
			if($this->isJSON($urlCustomer)){
				return (json_decode($this->getJsonMultipleRootToSingle($urlCustomer,"Customers") , true));
			}
			return ("Error reading source.\n");
		}
		return (json_decode($this->getJsonMultipleRootToSingle($response,"Customers") , true));
	}
	
	/**
	*Function name : public function getDistanceVincentyFormula(&float,&float,&float,&float)
	*Use: Calculates the distance between a pair of longitude and latitudes using Vincenty's Formula ( Ref https://en.wikipedia.org/wiki/Great-circle_distance)
	*@param Float $floatLatFrom
	*@param Float $floatLongFrom
	*@param Float $floatLatTo
	*@param Float $floatLongTo
	*@return Float
	*Version : 1.0
	*/
	public function getDistanceVincentyFormula($floatLatFrom, $floatLongFrom, $floatLatTo, $floatLongTo) {
		$floatLatFrom = deg2rad($this->Sanitise($floatLatFrom,Array("latitude")));// theta1
		$floatLongFrom = deg2rad($this->Sanitise($floatLongFrom,Array("longitude")));
		$floatLatTo = deg2rad($this->Sanitise($floatLatTo,Array("latitude")));  // theta2 
		$floatLongTo = deg2rad($this->Sanitise($floatLongTo,Array("longitude")));
		$lonDelta = $floatLongTo - $floatLongFrom; // delta
		$floatSinOfPhi = sqrt(pow(cos($floatLatTo) * sin($lonDelta) , 2) + pow(cos($floatLatFrom) * sin($floatLatTo) - sin($floatLatFrom) * cos($floatLatTo) * cos($lonDelta) , 2));
		$floatCosOfPhi = sin($floatLatFrom) * sin($floatLatTo) + cos($floatLatFrom) * cos($floatLatTo) * cos($lonDelta);
		$floatAngleElipsoid = atan2($floatSinOfPhi , $floatCosOfPhi);
		return $floatAngleElipsoid * $this->floatRadius;
	}

	/**
	*Function name : public function Sanitise(&float,Array())
	*Use: Validates the first parameter according to the filter array. This function can be used to validate : 1) Number 2) Latitude 3) Longitude  
	*@param Float $value
	*@param Array $value
	*@return Float
	*Version : 1.0
	*/
	public function Sanitise($value,$filter){
		foreach ($filter as $key => $fval) {
			switch ($fval) {
				case 'is_numeric':
					if(is_numeric($value) == FALSE){
						die("INPUT NUMERIC VALUE ONLY\n");
					}
					break;
				case 'latitude':
					if(is_numeric($value)&& ($value >= -90) && ($value <= 90))
						return $value;
					else
						throw new Exception("INVALID LATITUDE");
					break;
				case 'longitude':
					if(is_numeric($value)&& ($value >= -180) && ($value <= 180))
						return $value;
					else
						throw new Exception("INVALID LONGITUDE");
					break;	
				default:
					die("NO CHOICE PROVIDED FOR FILTER");
					break;
			}
		}
	}
	
	/**
	*Function name : public function getJsonMultipleRootToSingle(&String,&String)
	*Use: This function can be used for converting json objects with no or multiple roots to one single root making the passed parameter into one single array of JSON objects.
	*@param String $response 
	*@param String $root 
	*@return JSON
	*Version : 1.0
	*/
	public function getJsonMultipleRootToSingle($response,$root){
		$arrResp = explode("\n" , $response);
		$strImp = implode(",",$arrResp);
		$strJson = '{"'.$root.'":['.$strImp.']}';
		return $strJson;
	}
	
	/**
	*Function name : public function isJSON(&$string)
	*Use: This function checks is the parameter is JSON or not 
	*@param String $string  
	*@return Boolean
	*Version : 1.0
	*/
	public function isJSON($string){
   		return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE;
	}
}
?>