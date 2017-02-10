<?php
class Utils{
	 public static function convertEntersToBr($value){
		return preg_replace( "/\r||\n/", "<br>", $value);
	}
	

	// insert array of category Ids and format it so it displays correctly in multiselect picklists
	public static function formatCategories($categoryIds){
		$catId = '';
		//Category ID is a multiselect picklist, make sure we get all the values.
		foreach ($categoryIds as $categoryId) {
			if($catId != ''){
				$catId .= ',';
			}
			$catId .= $categoryId;
		}
		return $catId;
	}
	
	public static function getRegions(){
		$data = array('listRegions' => 'true' );
		$regions = Utils::parse(Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $data));
		return $regions;
	}
	//makes a callout to the webserver
	public static function getObjects($calloutArray){
		$objects = Utils::parse(Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $calloutArray));
		return $objects;
	}
	public static function getVillages(){
		$data = array('listVillages' => 'true' );
		$result = Utils::parse(Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $data));
		return $result;
	}
	public static function getEvents(){
		$data = array('listEvents' => 'true' );
		$events = Utils::parse(Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $data));
		return $events;
	}
	public static function getCategories(){
		$data = array('listCategories' => 'true' );
		$categories = Utils::parse(Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $data));
		return $categories;
	}
	public static function makeDropDownOfList($data, $name, $includeNone, $multiple){
		$output = '';
		if($multiple){
			$output .= '<select multiple id="'.$name.'" name="'.$name.'[]">';
		}else{
			$output .= '<select id="'.$name.'" name="'.$name.'">';
		}
	
		if($includeNone){
			$output .= '<option value = "" >no filter</option >';
		}
		foreach ($data as $value) {
			$output .= '<option value = "'.$value["id"].'" >'.$value["name"].'</option >';
		}
		$output .= '</select>';
		return $output;
	}
	// generates a random 10 digit string that is used to generate a unique url everytime a get request is made.
	public static function getRandomWord() {
		$len = 10;
		$word = array_merge(range('a', 'z'), range('A', 'Z'));
		shuffle($word);
		return substr(implode($word), 0, $len);
	}
	public static function makeWebRequest($url, $data){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
	
	public static function parse($jsonArrays) {
	
		$output = array();
		$decoded = json_decode($jsonArrays, true);
		foreach($decoded as  $value) {
			$output[] = $value;
		}
		return $output;
	}
	
	public static function makeObjectsOfData($rawData, $objectType){
		//for every item returned, make an object
		$objects = array();
		for ($i = 0 ; $i < sizeOf($rawData) ; $i++){
			$rawObjects = $rawData[$i];
			$rawObjectNames = array_keys($rawData[$i]);
			//make a new object by creating its new function dynamically
			$objectTypeCapital = ucfirst($objectType);
			$object = new $objectTypeCapital();
			//now fill the object with the raw data by creating the get functions with the capitalized keys.
			for($x = 0 ; $x < sizeOf($rawObjects) ; $x++){
				$functionName = 'set'.ucfirst($rawObjectNames[$x]);
				$object->$functionName($rawObjects[$rawObjectNames[$x]]);
			}
			// put the object in the array
			array_push($objects,$object);
		}
		return $objects;
	}
	
}

?>