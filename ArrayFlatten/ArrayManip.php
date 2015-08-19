<?php 
Class ArrayManip{
    /**
    *Function name : function getFlatArray(Array)
    *Use: This function can be used convert multi dimensional or multi level and multi dimensional array to a single dimensional array;  
    *@return Array 
    *@param Array $arrMultiple
    *Version : 1.0
    */
    function getFlatArray($arrMultiple) {
        $arrFlat = [];
        if (is_array($arrMultiple)) {
            foreach ($arrMultiple as $key => $value) {
                if (is_array($value)) {
                    $arrFlat = array_merge($arrFlat, $this->getFlatArray($value));
                } else {
                    $arrFlat[] = $value;
                }
            }
        }
        else{
        	return("Only arrays are allowed\n");
        }
        return $arrFlat;
    }
}
?>