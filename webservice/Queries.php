<?php
class Queries{
	function makeGetVillagesQuery($inputParameters){
		$query = '';
		//filters only on regions
		if($inputParameters->getRegion() && !$inputParameters->getCategory()){
			
			$query = "SELECT * FROM villages WHERE regionId = (SELECT id FROM regions WHERE name = '" . $inputParameters->getRegion() . "') ORDER BY name";
		}
		//filters on only categories
		else if($inputParameters->getCategory() && !$inputParameters->getRegion()){
			$query = "SELECT * FROM villages
					 WHERE
					MID(categoryId, 1,1) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 3,3) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 5,5) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 7,7) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 9,9) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 11,11) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 13,13) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 15,15) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 17,17) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 19,20) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
				ORDER BY name
;";
		}
	
		//no filter
		else if(!$inputParameters->getRegion() && !$inputParameters->getCategory()){
			$query = "SELECT * FROM villages  ORDER BY name";
		}
		//filters on both regions and categories
		else if($inputParameters->getRegion() && $inputParameters->getCategory()){
			$query = "SELECT * FROM villages WHERE regionId = (SELECT id FROM regions WHERE name = '" . $inputParameters->getRegion() . "')
				AND (MID(categoryId, 1,1) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 3,3) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 5,5) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 7,7) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 9,9) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 11,11) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 13,13) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 15,15) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 17,17) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )
					OR
					MID(categoryId, 19,20) = (SELECT id FROM categories WHERE name='" . $inputParameters->getCategory() . "' )) ORDER BY name ;";
		}
	
		return $query;
	}
	function makeGetEventsAndVillagesQuery($inputParameters){
		$query =
		"SELECT id,
            name,
            description,
            lat,
            lon,
			'events' as TableName
        FROM events
		UNION ALL		
		SELECT id,
            name,
            description,
            lat,
            lon,
			'villages' as TableName
        FROM villages
           ";

		return $query;
	}
	
	function makeGetEventsQuery($inputParameters){
		$query =
		"SELECT ev.id,
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
        INNER JOIN eventRelevance eR ON ev.id = eR.eventId
        WHERE (DATEDIFF(ev.startTimeStamp , '" . $inputParameters->getBeginDate() . "') >= 0 AND DATEDIFF(ev.startTimeStamp ,'" . $inputParameters->getEndDate() . "') <= 0)
    ";
		//
		//    // if a category is set, ONLY show those that have the category.
		if($inputParameters->getCategory() != null){
			$query .= " AND (eR.categoryId = (SELECT id FROM categories WHERE name = '" . $inputParameters->getCategory() . "'))";
		}
		//    //if a village OR a region is set, show only those that are relevant to either the village or the region. (Whilst avoiding nullpointers).
		if($inputParameters->getVillage() != null || $inputParameters->getRegion() != null){
			$query .= " AND (";
			if($inputParameters->getVillage() != null){
				$query .= "(eR.villageId = (SELECT id FROM villages WHERE name = '" . $inputParameters->getVillage() . "'))";
			}else if($inputParameters->getRegion() != null){
				$query .= "(eR.regionId = (SELECT id FROM regions WHERE name = '" . $inputParameters->getRegion() . "'))";
			}
			$query .= ") ";
		}
		$query .= 'ORDER BY ev.startTimeStamp ASC';
		return $query;
	}
	
	function makeInsertQuery($inputParameters){
		// insert villages
		if($inputParameters->getInsertType() == 'villages'){
			return "INSERT INTO ".$inputParameters->getInsertType()." (name, regionId, lat, lon, description, categoryId, website)
            VALUES ('".$inputParameters->getName()."',
             '".$inputParameters->getRegion()."' ,
             '". $inputParameters->getLat() ."' ,
             '" . $inputParameters->getLon() . "' ,
             '". $inputParameters->getDescription() ."',
             '".$inputParameters->getCategory()."',
			'".$inputParameters->getWebsite()."')";
			//insert regions
		}else if($inputParameters->getInsertType() == 'regions'){
			return "INSERT INTO ".$inputParameters->getInsertType()." (name)
            VALUES ('".$inputParameters->getName()."')";
			// insert events
		}else if($inputParameters->getInsertType() == 'categories'){
			return "INSERT INTO ".$inputParameters->getInsertType()." (name)
            VALUES ('".$inputParameters->getName()."')";
			// insert events
		}else if($inputParameters->getInsertType() == 'events'){
	
			$insertEventQuery = "INSERT INTO ".$inputParameters->getInsertType()." (name, description, lat, lon, startTimeStamp, endTimeStamp, website, imageUrl)
            VALUES ('".$inputParameters->getName()."',
             '".$inputParameters->getDescription()."' ,
             '". $inputParameters->getLat() ."' ,
             '" . $inputParameters->getLon() . "' ,
             '". $inputParameters->getBeginDate() ."',
             '" . $inputParameters->getEndDate() . "',
             '" . $inputParameters->getWebsite() . "',
             '" . $inputParameters->getImageUrl() . "'
        
             );";
	
			return $insertEventQuery;
		}else{
			return null;
		}
	}
	
	// after an event insert, the relevance must be inserted using the eventID
	function getInsertRelevanceQuery($con, $inputParameters){
		//make sure the right values are used if none is selected
		if ($inputParameters->getVillageRelevance() == 'none'){
			$inputParameters->setVillageRelevance('');
		}if ($inputParameters->getRegionRelevance() == 'none'){
			$inputParameters->setRegionRelevance('');
		}if ($inputParameters->getCategoryRelevance() == 'none'){
			$inputParameters->setCategoryRelevance('');
		}
		//get the new eventID
		$sql = "SELECT id FROM events WHERE name = '".$inputParameters->getName()."' AND description = '".$inputParameters->getDescription()."' AND lat =  '". $inputParameters->getLat() ."' AND startTimeStamp = '". $inputParameters->getBeginDate() ."' AND imageUrl =  '" . $inputParameters->getImageUrl() . "'";
		$result = $con->query($sql);
		$eventId ='';
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$eventId = $row["id"];
			}
		}
		//now insert the eventRelevance
		$output ="INSERT INTO eventRelevance ( villageId, categoryId, regionId, eventId)
			VALUES ('".$inputParameters->getVillageRelevance()."',
			'".$inputParameters->getCategoryRelevance()."',
			'".$inputParameters->getRegionRelevance()."',
			'".$eventId."');";
	
		return $output;
	}
	
	
	function makeDeleteQuery($inputParameters){
		 
		$output = "DELETE FROM ".$inputParameters->getDeleteType()."
            WHERE id='".$inputParameters->getId()."';";
		if($inputParameters->getDeleteType() == 'events'){
			$output .= "DELETE FROM eventRelevance
            WHERE eventId='".$inputParameters->getId()."';";
		}
		return $output;
	}
	
	function makeUpdateQuery($inputParameters){
		//villages
		if($inputParameters->getUpdateType() == 'villages'){
			$output= "UPDATE villages
            SET
                regionId = '" .$inputParameters->getRegion(). "' ,
                name = '".$inputParameters->getName()."' ,
                description = '".$inputParameters->getDescription()."' ,
                lat = '".$inputParameters->getLat()."',
                lon = '".$inputParameters->getLon()."',
                categoryId = '".$inputParameters->getCategory()."',
                website = '".$inputParameters->getWebsite()."'		
            WHERE id = '".$inputParameters->getId()."';";
			 
			return $output;
			//regions
		}else if ($inputParameters->getUpdateType() == 'regions'){
			$output ="UPDATE regions
            SET
                name = '".$inputParameters->getName()."'
            WHERE id = '".$inputParameters->getId()."'";
			return $output;
			// categories
		}else if ($inputParameters->getUpdateType() == 'categories'){
			$output ="UPDATE categories
            SET
                name = '".$inputParameters->getName()."'
            WHERE id = '".$inputParameters->getId()."'";
			return $output;
			// events
		}else if($inputParameters->getUpdateType() == 'events'){
			if ($inputParameters->getVillageRelevance() == 'none'){
				$inputParameters->setVillageRelevance("");
			}if ($inputParameters->getRegionRelevance() == 'none'){
				$inputParameters->setRegionRelevance('');
			}if ($inputParameters->getCategoryRelevance() == 'none'){
				$inputParameters->setCategoryRelevance('');
			}
			 
			$output= "UPDATE events
            SET
                name = '".$inputParameters->getName()."' ,
                description = '".$inputParameters->getDescription()."' ,
                lat = '".$inputParameters->getLat()."',
                lon = '".$inputParameters->getLon()."' ,
                startTimeStamp = '".$inputParameters->getBeginDate()."' ,
                endTimeStamp = '".$inputParameters->getEndDate()."' ,
                website = '".$inputParameters->getWebsite()."' ,		
                imageUrl = '".$inputParameters->getImageUrl()."'
            WHERE id = '".$inputParameters->getId()."';";
	
	
			$output .= " UPDATE eventRelevance
        	SET
        		categoryId = '".$inputParameters->getCategoryRelevance()."',
				regionId =  '".$inputParameters->getRegionRelevance()."',
				villageId = '".$inputParameters->getVillageRelevance()."'
			WHERE eventId = '".$inputParameters->getId()."';";
			return $output;
		}else {
			return null;
		}
	}
	
	function executeMultipleUpdates($con, $queries){
	
		if (!$con->multi_query($queries)) {
			echo "Multi query failed: (" . $con->errno . ") " . $con->error;
		}
	
		do {
			if ($res = $con->store_result()) {
				var_dump($res->fetch_all(MYSQLI_ASSOC));
				$res->free();
			}
		} while ($mysqli->more_results() && $mysqli->next_result());
	
	}
	
	function getQueryResult($con, $query){
		$result = mysqli_query($con, $query);
		if (!$result) {
			die('Invalid query: ' . mysqli_error() . $query);
		}
		return $result;
	}
	
}