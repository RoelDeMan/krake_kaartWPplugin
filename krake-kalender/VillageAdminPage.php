<?php
if(isset($_POST['updateSubmitted'])) {
	$catId = Utils::formatCategories($_POST['editCategoryId']);
	$updateData = array('update' => 'villages', 'id' => $_POST['villageId'], 'lon' => $_POST['lon'], 'lat' => $_POST['lat'], 'name' => $_POST['name'], 'description' => preg_replace( "/\r|\n/", "<br>", $_POST['description']), 'region' => $_POST['editRegionId'],  'category' => $catId, 'website' => $_POST['website']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $updateData );
	$response =  'Changes Saved';
}
if(isset($_POST['insertSubmitted'])) {
	$catId = Utils::formatCategories($_POST['newCategoryId']);
	$insertData = array('insert' => 'villages', 'lon' => $_POST['lonI'], 'lat' => $_POST['latI'], 'name' => $_POST['name'], 'description' => preg_replace( "/\r|\n/", "<br>", $_POST['description']), 'region' => $_POST['newRegionId'],  'category' => $catId, 'website' => $_POST['website'],);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $insertData );
	$response = 'Village Inserted';
}
if(isset($_POST['deleteSubmitted'])) {
	$deleteData = array('delete' => 'villages', 'id' => $_POST['idDelete']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $deleteData);
	$response = 'Village Deleted';
}
// If a response was set, print it.
if (isset($response)) {
	echo $response;
}
$output = '<div class="wrap" >';
$output.= '<h2>Manage Villages &nbsp &nbsp &nbsp <a href="#" onclick="createNewForm()">New Village</a></h2> ';

//create table
$columnsToShow = array('name' => 'Name' , 'description' => 'Description');
$calloutArray = array('listVillages' => 'true' );
echo "before table";
$adminTable = new Table('Village', $columnsToShow, true, $calloutArray);
echo "after table";
$output .= $adminTable->createTable();
//forms that are initially hidden
$output.= '<br><script>
    function createAndFillEditForm(id, name, region, lat, lon, description, category, website) {

		// makes sure the category multiselect picklist is populated properly
    	var categoryArray = category.split(",");
    	var categoryMultiSelectBox = document.getElementById("editCategoryId").options;
    	for (var i = 0; i < categoryMultiSelectBox.length; i++){
    		categoryMultiSelectBox[i].selected=false;
    		for(var y = 0; y < categoryArray.length; y++){
	    		var catId = Number(categoryArray[y]) * 1;
    			if(categoryMultiSelectBox[i].value == catId ){
    				categoryMultiSelectBox[i].selected="true";
    			}else{

    			}
	    	}
    	}

    	document.getElementById("insertBlock").style.display = "none";
        document.getElementById("editBlock").style.display = "inline";
    	document.getElementById("mapDiv").style.display = "block";
    	initMap(lat, lon); // initializes map
        document.getElementById("name").value = name;
        document.getElementById("description").innerHTML = replaceBRTags(description);
		document.getElementById("website").value = website;
        document.getElementById("editRegionId").value = region;
        document.getElementById("lat").value = lat;
        document.getElementById("lon").value = lon;
        document.getElementById("villageId").value = id;
   
    }

    function createNewForm() {
    	document.getElementById("editBlock").style.display = "none";
        document.getElementById("insertBlock").style.display = "inline";
    	document.getElementById("mapDiv").style.display = "block";
    	initMap(null, null); // initializes map
        
        return false;
    }

    function replaceBRTags(text)
	{
	    return text.replace(/<br><br>/g, "&#10;");
	}
    </script>
    ';
// The Edit Form which is initially hidden
$output .= '<div style="display: none; float : left;" id="editBlock">
		<h2>Edit Village</h2>  
                    <form action="'. basename(get_permalink()) .'"  method="post" id="updateForm" name="updateForm">
                      Village Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                        Village Description<br>
                         <textarea rows="4" cols="50" name="description" id="description" value=""></textarea><br>
                    	Village Website<br>
                      <input type="text" name="website" id="website" value=""><br>
                        Village Region<br>
                        ' . Utils::makeDropDownOfList(Utils::getRegions() , "editRegionId", false, false) . '<br>
                        Village is member of the following communities: (Select all that apply): <br>
                        ' . Utils::makeDropDownOfList(Utils::getCategories() , "editCategoryId", false, true) . '<br>
                        village X coordinate<br>
                         <input type="text" name="lat" id="lat" value=""><br>
                        village Y coordinate<br>
                      <input type="text" name="lon" id="lon" value="" ><br><br>
                      <input type="hidden" name="villageId" id="villageId" value="">
                      <input type="submit" name="updateSubmitted" value="Submit">
                    </form>
          
                </div>';
// the NEW form which is initially hidden
$output .= '<div style="display: none; float : left;" id="insertBlock">
                    <form action="'. basename(get_permalink()) .'"  method="post" id="insertForm" name="insertForm">
                      Village Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                        Village Description<br>
                         <textarea rows="4" cols="50" name="description" id="description" value=""></textarea><br>
                    		Village Website<br>
                      <input type="text" name="website" id="website" value=""><br>
                        Village Region<br>
                        ' . Utils::makeDropDownOfList(Utils::getRegions(), "newRegionId", false, false) . ' <br>
                         Village is member of the following communities: (Select all that apply): <br>
                        ' . Utils::makeDropDownOfList(Utils::getCategories() , "newCategoryId", false, true) . '<br>
                        village X coordinate<br>
                         <input type="text" name="latI" id="latI" value=""><br>
                        village Y coordinate<br>
                        <input type="text" name="lonI" id="lonI" value="" ><br><br>
                      <input type="submit" name="insertSubmitted" value="Submit">
                    </form>
                </div>';

$locationPicker = new GoogleMap(Constants::getMapsApiKey(), null, null, 400, 400, false);
$output .=  $locationPicker->getLocationPicker();


?>