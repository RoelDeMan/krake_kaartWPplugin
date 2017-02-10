<?php
class Village{
	private $id;
	private $name;
	private $description;
	private $lat;
	private $lon;
	private $regionId;
	private $categoryId;
	private $website;
	
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
	public function getWebsite(){
		return $this->website;
	}
	public function setWebsite($value){
		$this->website = $value;
	}
	

	function __construct(){

	}
	

}

?>