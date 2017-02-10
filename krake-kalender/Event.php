<?php
class Event{
	private $id;
	private $name;
	private $description;
	private $lat;
	private $lon;
	private $startTimeStamp;
	private $endTimeStamp;
	private $imageUrl;
	private $villageId;
	private $regionId;
	private $categoryId;
	private $website;
	
	
	public function getWebsite(){
		return $this->website;
	}
	public function setWebsite($value){
		$this->website = $value;
	}
	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getName(){
		return $this->name;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function getDescription(){
		return $this->description;
	}
	public function setDescription($description){
		$this->description = $description;
	}
	public function getLat(){
		return $this->lat;
	}
	public function setLat($value){
		$this->lat = $value;
	}
	public function getLon(){
		return $this->lon;
	}
	public function setLon($value){
		$this->lon = $value;
	}
	public function getStartTimeStamp(){
		return $this->startTimeStamp;
	}
	public function setStartTimeStamp($value){
		$this->startTimeStamp = $value;
	}
	public function getEndTimeStamp(){
		return $this->endTimeStamp;
	}
	public function setEndTimeStamp($value){
		$this->endTimeStamp = $value;
	}
	public function getImageUrl(){
		return $this->imageUrl;
	}
	public function setImageUrl($value){
		$this->imageUrl = $value;
	}	
	public function getRegionId(){
		return $this->regionId;
	}
	public function setRegionId($value){
		$this->regionId = $value;
	}
	public function getCategoryId(){
		return $this->categoryId;
	}
	public function setCategoryId($value){
		$this->categoryId = $value;
	}
	public function getVillageId(){
		return $this->villageId;
	}
	public function setVillageId($value){
		$this->villageId = $value;
	}
		
	
	//constructor with Id
	function __construct(){

	}

}
?>