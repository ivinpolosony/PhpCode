<?php 
require_once 'ArrayManip.php';

Class ArrayManipTest extends PHPUnit_Framework_TestCase{
/**
*@Covers: Class ArrayManip::getFlatArray()
*/	

	private $objArrayManip; 

	public function setUp(){
		$this->objArrayManip = new ArrayManip();
	}

	/**
	*Test for passing a parameter which is not an Array to ArrayManip::getFlatArray()
	*/
	public function testNonArrayParam(){
		$this->assertFalse(is_string($this->objArrayManip->getFlatArray(Array(1,2))),is_string($this->objArrayManip->getFlatArray("sdfsfsdf")) );
	}	
	

	/**
	*Test if ArrayManip::getFlatArray() returns array.
	*/
	public function testIsArray(){
		$this->assertTrue(is_array($this->objArrayManip->getFlatArray(Array(1,"s",3,Array("ssdf" , 2 ,3 ) , Array(1,3)))) );
	}	
	

	/**
	*Test if  ArrayManip::getFlatArray() returns a flattened array 
	*/
	public function testIsSingleArray(){
		$arr =  $this->isSingleArray($this->objArrayManip->getFlatArray(Array(1,2,3,4,5)));
		$arr2 =  $this->isSingleArray($this->objArrayManip->getFlatArray(Array(1,Array(2,3),4,5)));
		$arr3 =  $this->isSingleArray($this->objArrayManip->getFlatArray(Array(1,Array(2,3),Array(4,5), "sdfsdf")));
		$arr4 =  $this->isSingleArray($this->objArrayManip->getFlatArray(Array(1,Array(2,3),Array(4,5,3,4,4,5), "sdfsdf")));
		$arr5 =  $this->isSingleArray($this->objArrayManip->getFlatArray(Array(2,Array(2,3),3),Array(1,Array(2,3),Array(4,5,3,4,4,5), "sdfsdf")));
		$arr6 =  $this->isSingleArray($this->objArrayManip->getFlatArray(Array(1,"sdfds" , 0.3 , Array(2,3),Array(4,5,3,4,4,5), "sdfsdf")));
		$this->assertTrue($arr,$arr2,$arr3,$arr4,$arr5,$arr6); 
	}
	
	/**
	*Checks if the passed parameter is single or multidimensional
	*@param $arrValue Array 
	*@return Boolean
	*/
	public function isSingleArray($arrValue){
		if(is_array($arrValue)){
			foreach ($arrValue as $key => $value) {
				if(is_array($value)){
					return FALSE;
				}
			}
			return TRUE;
		}else{
			return FALSE;	
		}
	}
} 
?>