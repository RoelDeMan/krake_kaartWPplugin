<?php
if(isset($_POST['updateSubmitted'])) {
	$updateData = array('update' => 'events', 'id' => $_POST['eventId'], 'lon' => $_POST['lon'], 'lat' => $_POST['lat'], 'name' => $_POST['name'], 'description' => preg_replace( "/\r|\n/", "<br>", $_POST['description']), 'imageUrl' => $_POST['imageUrl'] , 'begindate' => $_POST['startTimeStamp'] , 'enddate' => $_POST['endTimeStamp'], 'villageRelevance' => $_POST['editVillageId'], 'regionRelevance' => $_POST['editRegionId'], 'categoryRelevance' => $_POST['editCategoryId'], 'website' => $_POST['website']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $updateData );
	$response = 'Changes Saved';
}
if(isset($_POST['insertSubmitted'])) {
	$insertData = array('insert' => 'events', 'lon' => $_POST['lonI'], 'lat' => $_POST['latI'], 'name' => $_POST['name'], 'description' => preg_replace( "/\r|\n/", "<br>", $_POST['description']), 'imageUrl' => $_POST['imageUrl'] , 'begindate' => $_POST['startTimeStamp'] , 'enddate' => $_POST['endTimeStamp'], 'villageRelevance' => $_POST['newVillageId'], 'regionRelevance' => $_POST['newRegionId'], 'categoryRelevance' => $_POST['newCategoryId'], 'website' => $_POST['website']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $insertData );
	$response = 'Event Inserted';
}
if(isset($_POST['deleteSubmitted'])) {
	$deleteData = array('delete' => 'events', 'id' => $_POST['idDelete']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $deleteData);
	$response = 'Event Deleted';
}
if (isset($response)) { // If a response was set, print it.
	echo $response;
}
$output = '<div class="wrap" >';
$output.= '<h2>Manage Events &nbsp &nbsp &nbsp <a href="#" onclick="createNewForm()">New Event</a></h2> ';

//create table
$columnsToShow = array('name' => 'Name' , 'description' => 'Description', 'startTimeStamp' => 'Date');
$calloutArray = array('listEvents' => 'true' );
$adminTable = new Table('Event', $columnsToShow, true, $calloutArray);
$output .= $adminTable->createTable();
// The Edit Form which is initially hidden
$output .= '<br><script>

	function createAndFillEditForm(id, name, description, start, end, lat, lon, imageUrl, vilRel, regRel, catRel, website) {	
        document.getElementById("insertBlock").style.display = "none";
    	document.getElementById("mapDiv").style.display = "block";
        document.getElementById("editBlock").style.display = "inline";
        document.getElementById("eventId").value = id;
        document.getElementById("name").value = name;
        document.getElementById("description").innerHTML = replaceBRTags(description);
        document.getElementById("imageUrl").value = imageUrl;
		document.getElementById("website").value = website;
        document.getElementById("lat").value = lat;
        document.getElementById("lon").value = lon;
    	initMap(lat, lon); // initializes map
    	document.getElementById("startTimeStamp").value = start;
    	document.getElementById("endTimeStamp").value = end;
    	document.getElementById("editVillageId").value = vilRel;
    	document.getElementById("editRegionId").value = regRel;
    	document.getElementById("editCategoryId").value = catRel;
    }
		
    function createNewForm() {
        document.getElementById("insertBlock").style.display = "inline";
    	document.getElementById("mapDiv").style.display = "block";
        document.getElementById("editBlock").style.display = "none";
    	initMap(null, null); // initializes map
        return false;
    }
		
    function replaceBRTags(text)
	{
	    return text.replace(/<br><br>/g, "&#10;");
	}
    </script>';


$output .= '<div style="display: none;  float : left;" id="editBlock">
                    <form action="'. basename(get_permalink()) .'"  method="post" id="updateForm" name="updateForm">
                    <h2>Edit Events</h2>  
                      Event Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                      Event Description<br>
                      <textarea rows="4" cols="50" name="description" id="description" value=""></textarea><br>
 						Event Website<br>
                      <input type="text" name="website" id="website" value=""><br>
                    		Event X coordinate<br>
                         <input type="text" name="lat" id="lat" value=""><br>
                        Event Y coordinate<br>
                      <input type="text" name="lon" id="lon" value="" ><br>
                    	Event Start date<br>
                       <input type="date" name="startTimeStamp" id="startTimeStamp" value=""><br>
                        Event End date<br>
                      <input type="date" name="endTimeStamp" id="endTimeStamp" value="" ><br>
                    	Image URL<br>
                      <input type="text" name="imageUrl" id="imageUrl" value="" ><br>
                    		Relevant for Village<br>
                    	' . Utils::makeDropDownOfList(utils::getVillages(),"editVillageId", true, false) . '
                    		<br>
                    			Relevant for Region<br>
                    	' . Utils::makeDropDownOfList(Utils::getRegions(),"editRegionId", true, false) . '
                    		<br>
                    		relevant for Category<br>
                    	' . Utils::makeDropDownOfList(Utils::getCategories(),"editCategoryId", true, false) . '
                    		<br><br>
                    		<input type="hidden" name="eventId" id="eventId" value="">
                      <input type="submit" name="updateSubmitted" value="Submit">
                    </form>
                </div>';
// the NEW form which is initially hidden
$output .= '<div style="display: none;  float : left;" id="insertBlock">
                    <form action="'. basename(get_permalink()) .'"  method="post" id="insertForm" name="insertForm">
                      Event Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                      Event Description<br>
                      <textarea rows="4" cols="50" name="description" id="description" value=""></textarea><br>
                    		Event Website<br>
                      <input type="text" name="website" id="website" value=""><br>
 						Event X coordinate<br>
                         <input type="text" name="latI" id="latI" value=""><br>
                        Event Y coordinate<br>
                      <input type="text" name="lonI" id="lonI" value="" ><br>
                    	Event Start date<br>
                       <input type="date" name="startTimeStamp" id="startTimeStamp" value=""><br>
                        Event End date<br>
                      <input type="date" name="endTimeStamp" id="endTimeStamp" value="" ><br>
                    	Image URL<br>
                      <input type="text" name="imageUrl" id="imageUrl" value="" ><br>
                    		Relevant for Village<br>
                    	' . Utils::makeDropDownOfList(Utils::getVillages(),"newVillageId", true, false) . '
                    		<br>
                    		Relevant for Region<br>
                    	' . Utils::makeDropDownOfList(Utils::getRegions(),"newRegionId", true, false) . '
                    		<br>
                    		relevant for Category<br>
                    	' . Utils::makeDropDownOfList(Utils::getCategories(),"newCategoryId", true, false) . '
                    		<br><br>
                      <input type="submit" name="insertSubmitted" value="Submit">
                    </form>
                </div> ';
				$locationPicker = new GoogleMap(Constants::getMapsApiKey(), null, null, 400, 400, false);
				$output .= $locationPicker->getLocationPicker();

?>