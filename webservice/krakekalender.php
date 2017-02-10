<?php
/**
 * Created by: Jille Treffers
 * For: HAN University of Applied Sciences, Krake Project
 * Date: 7/19/2016
 * Time: 11:30 AM
 */

/*
 * ************************ Script Logic *****************************
 */
// connect to database
include 'InputParameters.php';
include 'Queries.php';
include 'CreateOutput.php';

$dbConnection = getDatabaseConnection();
$inputParameters = InputParameters::getParameters($dbConnection); //also prevents sql injection and xss.
// the map uses a GET method, the rest uses POST method.

if(isset($_POST['list'])){
    $query = Queries::makeGetEventsQuery($inputParameters);
    $result = Queries::getQueryResult($dbConnection, $query);
    CreateOutput::makeJSONofResults($result);
}
if(isset($_POST['listSpecificVillages'])){
	$query = Queries::makeGetVillagesQuery($inputParameters);
	$result = Queries::getQueryResult($dbConnection, $query);
	CreateOutput::makeJSONofResults($result);
}
if(isset($_GET['listSpecificVillages'])){
	$query = Queries::makeGetVillagesQuery($inputParameters);
	$result = Queries::getQueryResult($dbConnection, $query);
	CreateOutput::makeJSONofResults($result);
}

if (isset($_POST['mapSpecificVillagesLegend'])) {
	$query = "SELECT * FROM villages  ORDER BY name";
	$result = Queries::getQueryResult($dbConnection, $query);
	CreateOutput::makeJSONofResults($result);
}
//used to get all villages for purposes of admin pages
if(isset($_POST['listVillages'])){
    $query = 'SELECT * FROM villages ORDER BY name';
    $result = Queries::getQueryResult($dbConnection, $query);
    CreateOutput::makeJSONofResults($result);
}
//used to get all categories for purposes of admin pages
if(isset($_POST['listCategories'])){
	$query = 'SELECT * FROM categories ORDER BY name';
	$result = Queries::getQueryResult($dbConnection, $query);
	CreateOutput::makeJSONofResults($result);
}
//used to get all regions for purposes of admin pages
if(isset($_POST['listRegions'])){
    $query = 'SELECT * FROM regions  ORDER BY name';
    $result = Queries::getQueryResult($dbConnection, $query);
    CreateOutput::makeJSONofResults($result);
}
if(isset($_POST['listEvents'])){
	$query = "SELECT ev.id,
            ev.name,
            ev.description,
            ev.startTimeStamp,
            ev.endTimeStamp, 
            ev.lat,
            ev.lon,
            ev.imageUrl,
			eR.villageId,
			eR.regionId,
			eR.categoryId,
			ev.website
        FROM events ev 
        INNER JOIN eventRelevance eR ON ev.id = eR.eventId";
	
	
	
	$result = Queries::getQueryResult($dbConnection, $query);
	CreateOutput::makeJSONofResults($result);
}
if(isset($_POST['insert'])){
    $query = Queries::makeInsertQuery($inputParameters);
    $result = Queries::getQueryResult($dbConnection, $query);
    //if the insert is an event, don't forget to also update the relevance table!
    if($_POST['insert'] == 'events'){
	    $relevanceQuery = Queries::getInsertRelevanceQuery($dbConnection, $inputParameters);
	    $result2 = Queries::getQueryResult($dbConnection, $relevanceQuery);
    }

}
if(isset($_POST['update'])){
    $query = Queries::makeUpdateQuery($inputParameters);
    $result = Queries::executeMultipleUpdates($dbConnection, $query);
}
if(isset($_POST['delete'])){
    $query = Queries::makeDeleteQuery($inputParameters);
    $result = Queries::executeMultipleUpdates($dbConnection, $query);
}
closeDatabaseConnection($dbConnection);


/*
 * ************************ Functions *****************************
 */

//Make database connection. Returns the connection variable
function getDatabaseConnection(){
    $con = mysqli_connect("localhost", "kraken", "*********", "krakenkalender");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    return $con;
}

function closeDatabaseConnection($con){
    mysqli_close($con);
}







?>
