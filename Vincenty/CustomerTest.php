<?php
require_once 'Customer.php';

Class CustomerTest extends PHPUnit_Framework_TestCase{
	/**
	*@Covers : Customer::getCustomerRecords() , Customer::getDistanceVincentyFormula()
	*/
	private $objCustomer; 

	public function setUp(){
		$this->objCustomer = new Customer();
	}

	/**
	*Test if JSON records of the customer is available from the source. Cases: 1) URL 2) JSON Array 3) Invalid source
	*/
	public function testGetCustomerRecords(){
		$source = 'https://gist.githubusercontent.com/brianw/19896c50afa89ad4dec3/raw/6c11047887a03483c50017c1d451667fd62a53ca/gistfile1.txt';
		$source2 = '{"latitude": "52.986375", "user_id": 12, "name": "Christina McArdle", "longitude": "-6.043701"}';
		$source3 = 'fake.json';
		$this->assertTrue(is_array($this->objCustomer->getCustomerRecords($source)));
		$this->assertTrue(is_array($this->objCustomer->getCustomerRecords($source2)));
		$this->assertFalse(is_array($this->objCustomer->getCustomerRecords($source3)));
	}


	/**
	* Test if the function throws exceptions and expected results on Vicenty's formula. Cases: 1) Invalid Longiude 2) Invalid 
	* Latitude 3) Distance between two predifined locations
	*/
	public function testgetDistanceVincentyFormula(){
		try{
			$this->objCustomer->getDistanceVincentyFormula(51.92893,"sfsaafs" , 23423, "sdfsdf");  // Invalid longitude 
			$this->objCustomer->getDistanceVincentyFormula(515.92893,-6.2592576 , 23423, -6.2592576);  // Invalid longitude 
			
		}catch(Exception $e){
			 $this->assertEquals("INVALID LONGITUDE" , $e->getMessage() );
		}
		try{
			$this->objCustomer->getDistanceVincentyFormula("sfsaafs" ,12.23 ,  "sdfsdf" , -23.44); // Invalid latitude
			$this->objCustomer->getDistanceVincentyFormula(12323 ,12.23 ,  234 , -23.44); // Invalid latitude
		}catch(Exception $e){
			 $this->assertEquals("INVALID LATITUDE" , $e->getMessage() );
		}

		$this->assertTrue($this->errorCorrection($this->objCustomer->getDistanceVincentyFormula( 53.3381985, -6.2592576 , 53.1489345 , -6.8422408) ,47.8)); // Dublin Southside, Dublin, Ireland  to Curragh, Co. Kildare, Ireland ==> Google Result 47.8 , Actual Result 44.13+
		$this->assertTrue($this->errorCorrection($this->objCustomer->getDistanceVincentyFormula( 53.3478, -6.2597 , 51.5072 ,0.1275) ,498)); // Dublin to london
	}

	/**
	*Since google doesn't calculate distance between two locations in a straight line, a devience has to be allowed to the 
	*calculation of distance. In this case a 20 km errorcorrection is applied
	*@param Int $actual  
	*@param Int $expected 
	*@return Boolean
	*/
	public function errorCorrection($actual , $expected){
		$allowedError = 20;
		$deviance =  abs($actual - $expected);
		if($deviance < $allowedError){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>