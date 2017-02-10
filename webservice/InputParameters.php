<?php
//Object that contains input parameters, and corresponding getters and setters for all possible variables.
class InputParameters{
	private $beginDate;
	private $endDate;
	private $village;
	private $region;
	private $category;
	private $id;
	private $name;
	private $lat;
	private $lon;
	private $description;
	private $updateType;
	private $insertType;
	private $deleteType;
	private $imageUrl;
	private $eventRelevance;
	private $villageRelevance;
	private $categoryRelevance;
	private $regionRelevance;
	private $website;
	
	public function getWebsite(){
		return $this->website;
	}
	public function setWebsite($value){
		$this->website = $value;
	}
	public function getBeginDate(){
		return $this->beginDate;
	}
	public function setBeginDate($beginDate){
		$this->beginDate = $beginDate;
	}
	public function getEndDate(){
		return $this->endDate;
	}
	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}
	public function getVillage(){
		return $this->village;
	}
	public function setVillage($village){
		$this->village = $village;
	}
	public function getRegion(){
		return $this->region;
	}
	public function setRegion($region){
		$this->region = $region;
	}
	public function getCategory(){
		return $this->category;
	}
	public function setCategory($category){
		$this->category = $category;
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
	public function setLat($lat){
		$this->lat = $lat;
	}
	public function getLon(){
		return $this->lon;
	}
	public function setLon($lon){
		$this->lon = $lon;
	}
	public function toString(){
		return $this->name;
	}
	public function getUpdateType(){
		return $this->updateType;
	}
	public function setUpdateType($updateType){
		$this->updateType = $updateType;
	}
	public function getInsertType(){
		return $this->insertType;
	}
	public function setInsertType($insertType){
		$this->insertType = $insertType;
	}
	public function getDeleteType(){
		return $this->deleteType;
	}
	public function setDeleteType($deleteType){
		$this->deleteType = $deleteType;
	}
	public function getImageUrl(){
		return $this->imageUrl;
	}
	public function setImageUrl($imageUrl){
		$this->imageUrl = $imageUrl;
	}
	public function getEventRelevance(){
		return $this->eventRelevance;
	}
	public function setEventRelevance($eventRelevance){
		$this->eventRelevance = $eventRelevance;
	}
	public function getVillageRelevance(){
		return $this->villageRelevance;
	}
	public function setVillageRelevance($villageRelevance){
		$this->villageRelevance = $villageRelevance;
	}
	public function getRegionRelevance(){
		return $this->regionRelevance;
	}
	public function setRegionRelevance($regionRelevance){
		$this->regionRelevance = $regionRelevance;
	}
	public function getCategoryRelevance(){
		return $this->categoryRelevance;
	}
	public function setCategoryRelevance($categoryRelevance){
		$this->categoryRelevance = $categoryRelevance;
	}
	
	
	function getParameters($con){
		$inputParameters = new InputParameters();
		if ($_SERVER["REQUEST_METHOD"] == "GET") {
			if (isset($_GET['begindate'])) {
				$inputParameters->setBeginDate(htmlspecialchars(mysqli_real_escape_string($con, $_GET['begindate'])));
			}
			if (isset($_GET['enddate'])) {
				$inputParameters->setEndDate(htmlspecialchars(mysqli_real_escape_string($con, $_GET['enddate'])));
			}
			if (isset($_GET['village'])) {
				$inputParameters->setVillage(htmlspecialchars(mysqli_real_escape_string($con, $_GET['village'])));
			}
			if (isset($_GET['region'])) {
				$inputParameters->setRegion(htmlspecialchars(mysqli_real_escape_string($con, $_GET['region'])));
			}
			if (isset($_GET['category'])) {
				$inputParameters->setCategory(htmlspecialchars(mysqli_real_escape_string($con, $_GET['category'])));
			}
		}else if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST['begindate'])) {
				$inputParameters->setBeginDate(htmlspecialchars(mysqli_real_escape_string($con, $_POST['begindate'])));
			}
			if (isset($_POST['enddate'])) {
				$inputParameters->setEndDate(htmlspecialchars(mysqli_real_escape_string($con, $_POST['enddate'])));
			}
			if (isset($_POST['village'])) {
				$inputParameters->setVillage(htmlspecialchars(mysqli_real_escape_string($con, $_POST['village'])));
			}
			if (isset($_POST['region'])) {
				$inputParameters->setRegion(htmlspecialchars(mysqli_real_escape_string($con, $_POST['region'])));
			}
			if (isset($_POST['gregion'])) {
				$inputParameters->setRegion(htmlspecialchars(mysqli_real_escape_string($con, $_POST['gregion'])));
			}
			if (isset($_POST['category'])) {
				$inputParameters->setCategory(htmlspecialchars(mysqli_real_escape_string($con, $_POST['category'])));
			}
			if (isset($_POST['id'])) {
				$inputParameters->setId(htmlspecialchars(mysqli_real_escape_string($con, $_POST['id'])));
			}
			if (isset($_POST['name'])) {
				$inputParameters->setName(htmlspecialchars(mysqli_real_escape_string($con, $_POST['name'])));
			}
			if (isset($_POST['lat'])) {
				$inputParameters->setLat(htmlspecialchars(mysqli_real_escape_string($con, $_POST['lat'])));
			}
			if (isset($_POST['lon'])) {
				$inputParameters->setLon(htmlspecialchars(mysqli_real_escape_string($con, $_POST['lon'])));
			}
			if (isset($_POST['description'])) {
				$inputParameters->setDescription(htmlspecialchars(mysqli_real_escape_string($con, $_POST['description'])));
			}
			if (isset($_POST['update'])) {
				$inputParameters->setUpdateType(htmlspecialchars(mysqli_real_escape_string($con, $_POST['update'])));
			}
			if (isset($_POST['insert'])) {
				$inputParameters->setInsertType(htmlspecialchars(mysqli_real_escape_string($con, $_POST['insert'])));
			}
			if (isset($_POST['delete'])) {
				$inputParameters->setDeleteType(htmlspecialchars(mysqli_real_escape_string($con, $_POST['delete'])));
			}
			if (isset($_POST['imageUrl'])) {
				$inputParameters->setImageUrl(htmlspecialchars(mysqli_real_escape_string($con, $_POST['imageUrl'])));
			}
			if (isset($_POST['eventRelevance'])) {
				$inputParameters->setEventRelevance(htmlspecialchars(mysqli_real_escape_string($con, $_POST['eventRelevance'])));
			}
			if (isset($_POST['villageRelevance'])) {
				$inputParameters->setVillageRelevance(htmlspecialchars(mysqli_real_escape_string($con, $_POST['villageRelevance'])));
			}
			if (isset($_POST['regionRelevance'])) {
				$inputParameters->setRegionRelevance(htmlspecialchars(mysqli_real_escape_string($con, $_POST['regionRelevance'])));
			}
			if (isset($_POST['categoryRelevance'])) {
				$inputParameters->setCategoryRelevance(htmlspecialchars(mysqli_real_escape_string($con, $_POST['categoryRelevance'])));
			}
			if (isset($_POST['website'])) {
				$inputParameters->setWebsite(htmlspecialchars(mysqli_real_escape_string($con, $_POST['website'])));
			}
		}
		return $inputParameters;
	}
}



?>