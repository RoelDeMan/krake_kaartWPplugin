<?php
if(isset($_POST['updateSubmitted'])) {
	$updateData = array('update' => 'categories', 'id' => $_POST['categoryId'], 'name' => $_POST['name'] );
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $updateData );
	$response = 'Changes Saved';
}
if(isset($_POST['insertSubmitted'])) {
	$insertData = array('insert' => 'categories', 'name' => $_POST['name'] );
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $insertData );
	$response = 'Category Inserted';
}
if(isset($_POST['deleteSubmitted'])) {
	$deleteData = array('delete' => 'categories', 'id' => $_POST['id']);
	Utils::makeWebRequest(Constants::getCalloutBaseUrl(), $deleteData);
	$response = 'Category Deleted';
}
if (isset($response)) { // If a response was set, print it.
	echo $response;
}
$output = '<div class="wrap" >';
$output.= '<h2>Manage Categories &nbsp &nbsp &nbsp <a href="#" onclick="createNewForm()">New Category</a></h2> ';

//create table
$columnsToShow = array('name' => 'Name');
$calloutArray = array('listCategories' => 'true' );
$adminTable = new Table('Category', $columnsToShow, true, $calloutArray);
$output .= $adminTable->createTable();
// The Edit Form which is initially hidden
$output .= '<br>';
$output .= '<script>
		function createAndFillEditForm(id, name) {
	        document.getElementById("insertBlock").style.display = "none";
	        document.getElementById("editBlock").style.display = "block";
			document.getElementById("name").value = name;
	        document.getElementById("categoryId").value = id;
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


$output .= '<div style="display: none;" id="editBlock">
	        <h2>Edit Category</h2>            
			<form action="'. basename(get_permalink()) .'"  method="post" id="updateForm" name="updateForm">
                      Category Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                      <input type="hidden" name="categoryId" id="categoryId" value="">
                      <input type="submit" name="updateSubmitted" value="Submit">
                    </form>
                </div>';
// the NEW form which is initially hidden
$output .= '<div style="display: none;" id="insertBlock">
                    <form action="'. basename(get_permalink()) .'"  method="post" id="insertForm" name="insertForm">
                      Category Name<br>
                      <input type="text" name="name" id="name" value=""><br>
                      <input type="submit" name="insertSubmitted" value="Submit">
                    </form>
                </div>';

?>