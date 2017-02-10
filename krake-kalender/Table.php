<?php
include_once 'Village.php';
include_once 'Category.php';
include_once 'Region.php';
include_once 'Event.php';
include_once 'GeneralUtilities.php';


class Table{
	private $myObjects;
	private $tableFields;
	private $objectFields;
	private $objectType;
	private $isAdminTable;
	
	public function getMyObjects(){
		return $this->myObjects;
	}
	public function setMyObjects($value){
		$this->myObjects = $value;
	}
	public function getTableFields(){
		return $this->tableFields;
	}
	public function setTableFields($value){
		$this->tableFields = $value;
	}
	public function getObjectFields(){
		return $this->objectFields;
	}
	public function setObjectFields($value){
		$this->objectFields = $value;
	}
	public function getObjectType(){
		return $this->objectType;
	}
	public function setObjectType($value){
		$this->objectType = $value;
	}
	public function getIsAdminTable(){
		return $this->isAdminTable;
	}
	public function setIsAdminTable($value){
		$this->isAdminTable = $value;
	}	
	
	/*creates a table
	 * $objectType is the name of the type of object (in singular) for example: 'Village'
	 * $tableFields is an associative array of the fields that should be shown, with the database column name as key and the intended display name as value
	 * $isAdminTable is a boolean that indicates whether or not an edit/delete button should be added.
	 * $calloutArray is an associative array that is used to make a callout. for example: array('list' => 'true', 'begindate' => $timeMin , 'enddate'=>$timeMax  );
	 */
	function __construct($objectType, $tableFields, $isAdminTable, $calloutArray){
		$this->setObjectType($objectType);
		$this->setTableFields($tableFields);
		//do callout to webservice
		$rawData = $this->getObjects($calloutArray);
		//get the field names the database by finding the keys of the raw data
		$this->setObjectFields(array_keys($rawData[0]));
		//make objects of this data
		$this->setMyObjects(Utils::makeObjectsOfData($rawData, $objectType));
		//set admin table boolean
		$this->setIsAdminTable($isAdminTable);
	}
	
	public function createTable(){
		//get the size of both objects
		$objects = $this->getMyObjects();
		$numberOfObjects = sizeOf($objects);
		$numberOfObjectFields = sizeOf($this->getObjectFields());
		$numberOfTableFields = sizeOf($this->getTableFields());
		
		$columnScreenNames = array_values($this->getTableFields());
		$columnFieldNames = array_keys($this->getTableFields());
		$fieldNames = $this->getObjectFields();
		
		//make the headers of the table
		$output = "<table id='AdminTable'>";
		$output .= "<tr>";
		for ($x = 0 ; $x < $numberOfTableFields ; $x++) {
			$output .= "<td><b>";
			$output .= $columnScreenNames[$x];
			$output .= "</b></td>";
		}
		$output .= '</tr>';
		
		//for each object in the array (each village, event, etc), do the following
		
		for ($i =0 ; $i < $numberOfObjects ; $i++) {
			//for each title (column that was returned by the query)
			$output .= '<tr>';
			$output .= $this->createTableRow($objects[$i], $fieldNames, $columnFieldNames, $columnScreenNames , $numberOfTableFields, $numberOfObjectFields);
			$output .= "</tr>";
		}
		
		$output .= '</table>';
		return $output;
	}
	
	
	//loops though the fields af an object and outputs the ones that it should as a table.
	//it also calls the
	private function createTableRow($object, $fieldNames, $columnFieldNames, $columnScreenNames , $numberOfTableFields, $numberOfObjectFields ){
		// start looping through the row
		
		$output = '';
		for ($y =0 ; $y < $numberOfTableFields ; $y++) {
			//in PHP it is possible to create a functioncall by putting its name in a variable. We do this for each getter.
			$getFunction = 'get'.ucfirst($columnFieldNames[$y]);
			
			$output .= "<td>";
			$output .= $object->$getFunction();
			$output .= "</td>";
			//if it is an admin table, show edit and delete buttons
			if($this->getIsAdminTable()){
				//on the end of the row, create an edit button and a delete button.
				if($y == $numberOfTableFields-1){
					$output .= "<td>";
					$output .= $this->createEditButton($object, $numberOfObjectFields, $fieldNames);
		
					$theId = $object->getId();
					$theName = $object->getName();	
					$output .= $this->createDeleteButton($theId, $theName);
					$output .= '</td>';
				}
			}
		}
		return $output;
	}
	
	private function createEditButton($object, $numberOfObjectFields, $fieldNames){
		$output = '';
		//create the edit button
		$output .= '<a href=# onclick="createAndFillEditForm(';
		$output .= $this->formatObjectAsJavascriptParameters($numberOfObjectFields, $object, $fieldNames);
		$output .= ')">edit</a>';
		return $output;
	}
	
	//surrounds string with escaped quotes and puts comma's between them
	private function formatObjectAsJavascriptParameters($numberOfObjectFields, $object, $fieldNames){
		$output = '';
		for($a = 0 ; $a < $numberOfObjectFields ; $a++){
			$functionName = 'get'.ucfirst($fieldNames[$a]);
			$quote = ' \'';
			$theValue = $object->$functionName();
			$output .= ' \'' . $theValue . '\'';
			if ($a != $numberOfObjectFields-1){
				$output .= ' , ';
			}
		}
		return $output;
	}
	
	private function createDeleteButton($id, $name){
		$output = '
				<form action="'. basename(get_permalink()) .'"  method="post" id="listForm" name="listForm">
			        <input type="hidden" name="idDelete" id="idDelete" value="'.$id.'">
			        <input style="background:none!important; border:none; padding:0!important; font: inherit; cursor: pointer; outline: none; outline-offset: 0; color: #AE1121; text-decoration: underline;" type="submit" name="deleteSubmitted" value="delete" onclick="return confirm(\'Are you sure you want to delete ' . $name . '\')">
			        </form>';
		return $output;
	}
	
	//makes a callout to the webserver
	private function getObjects($calloutArray){
		$objects = Utils::parse(Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $calloutArray));
		return $objects;
	}
	

}
?>