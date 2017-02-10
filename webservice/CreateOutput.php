<?php
class CreateOutput{
	//creates a simple json
	function makeJSONofResults($result){
	
		while ($e = mysqli_fetch_assoc($result)) {
			$output[] = $e;
		}
		
		mysqli_free_result($result);
		print json_encode($output);
		
	}
	
	//LEGACY! New version does not use KML anymore...
	// Outputs a downloadable kml file for use in google maps. 
	function makeMapFileOfResults($result){
	
		
		// Creates the Document.
		$dom = new DOMDocument('1.0', 'UTF-8');
	
		// Creates the root KML element and appends it to the root document.
		$node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
		$parNode = $dom->appendChild($node);

		// Creates a KML Document element and append it to the KML element.
		$dnode = $dom->createElement('Document');
		$docNode = $parNode->appendChild($dnode);

		
		// Iterates through the MySQL results, creating one Placemark for each row.
		while ($row = @mysqli_fetch_assoc($result)) {

			// Creates a Placemark and append it to the Document.
			$node = $dom->createElement('Placemark');
			$placeNode = $docNode->appendChild($node);
	
			// Create name, and description elements and assigns them the values of the name and address columns from the results.
			$nameNode = $dom->createElement('name', htmlentities($row['name']));
			$placeNode->appendChild($nameNode);
			$descNode = $dom->createElement('description', htmlentities($row['description']));
			$placeNode->appendChild($descNode);
			
	
			// Creates a Point element.
			$pointNode = $dom->createElement('Point');
			$placeNode->appendChild($pointNode);
	
			// Creates a coordinates element and gives it the value of the lng and lat columns from the results.
			$coorStr = $row['lon'] . ',' . $row['lat'];
			$coorNode = $dom->createElement('coordinates', $coorStr);
			$pointNode->appendChild($coorNode);
			
		}
	
		$kmlOutput = $dom->saveXML();
		header('Content-type: application/vnd.google-earth.kml+xml');
		echo $kmlOutput;
	}
	
	// Outputs a downloadable kml file for use in google maps.
	function makeMapFileOfResultsTest($result){
		echo var_dump($result);
		// Creates the Document.
		$dom = new DOMDocument('1.0', 'UTF-8');
	
		// Creates the root KML element and appends it to the root document.
		$node = $dom->createElementNS('http://earth.google.com/kml/2.1', 'kml');
		$parNode = $dom->appendChild($node);
	
		// Creates a KML Document element and append it to the KML element.
		$dnode = $dom->createElement('Document');
		$docNode = $parNode->appendChild($dnode);
	
		$uniqueArrays = array();
		while ($row = @mysqli_fetch_assoc($result)) {
				
			$uniqueArrays[] = $row['TableName'];
		}
		$uniqueArrays = array_unique($uniqueArrays);
	
		while ($row = @mysqli_fetch_assoc($result)){
			// Creates the style elements based on the type of element returned
			$restStyleNode = $dom->createElement('Style');
			$restStyleNode->setAttribute('id', $row.'Style');
			$restIconstyleNode = $dom->createElement('IconStyle');
			$restIconstyleNode->setAttribute('id', $row.'Icon');
			$restIconNode = $dom->createElement('Icon');
			$restHref = $dom->createElement('href', 'http://jille.nl/krake/icons/'.$row.'.png');
			$restIconNode->appendChild($restHref);
			$restIconstyleNode->appendChild($restIconNode);
			$restStyleNode->appendChild($restIconstyleNode);
			$docNode->appendChild($restStyleNode);
		}
		// Iterates through the MySQL results, creating one Placemark for each row.
		while ($row = @mysqli_fetch_assoc($result)) {
	
			// Creates a Placemark and append it to the Document.
			$node = $dom->createElement('Placemark');
			$placeNode = $docNode->appendChild($node);
	
			// Create name, and description elements and assigns them the values of the name and address columns from the results.
			$nameNode = $dom->createElement('name', htmlentities($row['name']));
			$placeNode->appendChild($nameNode);
			$descNode = $dom->createElement('description', htmlentities($row['description']));
			$placeNode->appendChild($descNode);
			$styleUrl = $dom->createElement('styleUrl', '#' . $row['TableName'] . 'Style');
			$placeNode->appendChild($styleUrl);
	
			// Creates a Point element.
			$pointNode = $dom->createElement('Point');
			$placeNode->appendChild($pointNode);
	
			// Creates a coordinates element and gives it the value of the lng and lat columns from the results.
			$coorStr = $row['lon'] . ',' . $row['lat'];
			$coorNode = $dom->createElement('coordinates', $coorStr);
			$pointNode->appendChild($coorNode);
				
		}
	
		$kmlOutput = $dom->saveXML();
		header('Content-type: application/vnd.google-earth.kml+xml');
		echo $kmlOutput;
	}
}