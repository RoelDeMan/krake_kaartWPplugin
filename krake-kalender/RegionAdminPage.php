<?php
if(isset($_POST['updateSubmitted'])) {
	$updateData = array('update' => 'regions', 'id' => $_POST['regionId'], 'name' => $_POST['name'] );
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $updateData );
	$response = 'Changes Saved';
}
if(isset($_POST['insertSubmitted'])) {
	$insertData = array('insert' => 'regions', 'name' => $_POST['name'] );
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $insertData );
	$response = 'Region Inserted';
}
if(isset($_POST['deleteSubmitted'])) {
	$deleteData = array('delete' => 'regions', 'id' => $_POST['idDelete']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $deleteData);
	$response = 'Region Deleted';
}
if (isset($response)) { // If a response was set, print it.
	echo $response;
}
$output = '<div class="wrap" >';
$output.= '<h2>Manage Regions &nbsp &nbsp &nbsp <a href="#" onclick="createNewForm()">New Region</a></h2> ';

//create table
$columnsToShow = array('name' => 'Name');
$calloutArray = array('listRegions' => 'true' );
$adminTable = new Table('Region', $columnsToShow, true, $calloutArray);
$output .= $adminTable->createTable();

$output .= '<br><script>
		function createAndFillEditForm(id, name) {
	        document.getElementById("insertBlock").style.display = "none";
	        document.getElementById("editBlock").style.display = "block";
			document.getElementById("name").value = name;
	        document.getElementById("regionId").value = id;
	    }
		function createNewForm() {
	        document.getElementById("insertBlock").style.display = "block";
	        document.getElementById("editBlock").style.display = "none";
	        return false;
	    	}
		function replaceBRTags(text)
		{
		    return text.replace(/<br><br>/g, "&#10;");
		}
		</script>';

// The Edit Form which is initially hidden
$output .= '<div style="display: none;" id="editBlock">
        <h2>Edit Region</h2>              
		<form action="'. basename(get_permalink()) .'"  method="post" id="updateForm" name="updateForm">
                      Region Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                      <input type="hidden" name="regionId" id="regionId" value="">
                      <input type="submit" name="updateSubmitted" value="Submit">

                    </form>
                </div>';
// the NEW form which is initially hidden
$output .= '<div style="display: none;" id="insertBlock">
                    <form action="'. basename(get_permalink()) .'"  method="post" id="insertForm" name="insertForm">
                      Region Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                      <input type="submit" name="insertSubmitted" value="Submit">
                    </form>
                </div>';

?>