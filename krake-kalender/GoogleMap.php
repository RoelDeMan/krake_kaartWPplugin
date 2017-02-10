<?php

include_once 'Category.php';
include_once 'GeneralUtilities.php';

class GoogleMap{
	private $googleAPIToken;
	private $objects;
	private $idName;
	private $width;
	private $height;
	private $legendheight;
	private $showFilters;
	private $showCategories;
	private $showPast;
	
	

	public function getGoogleAPIToken(){
		return $this->googleAPIToken;
	}
	public function setGoogleAPIToken($value){
		$this->googleAPIToken = $value;
	}
	public function getObjects(){
		return $this->objects;
	}
	public function setObjects($value){
		$this->objects = $value;
	}
	public function getIdName(){
		return $this->idName;
	}
	public function setIdName($value){
		$this->idName = $value;
	}
	public function getWidth(){
		return $this->width;
	}
	public function setWidth($value){
		$this->width = $value;
	}
	public function getHeight(){
		return $this->height;
	}
	public function setHeight($value){
		$this->height = $value;
		$this->legendheight = $value - 40;
	}
	public function getShowFilters(){
		return $this->showFilters;
	}
	public function setShowFilters($value){
		$this->showFilters = $value;
	}
	public function getShowCategories(){
		return $this->showCategories;
	}
	public function setShowCategories($value){
		$this->showCategories = $value;
	}
	public function getShowPast(){
		return $this->showPast;
	}
	public function setShowPast($value){
		$this->showPast = $value;
	}	
	

	function __construct($getGoogleAPIToken, $objects, $idName, $width, $height, $showFilters, $showPast){
		$this->setGoogleAPIToken($getGoogleAPIToken);
		$this->setObjects($objects);
		$this->setIdName($idName);
		$this->setWidth($width);
		$this->setHeight($height);
		$this->setShowFilters($showFilters);
		$this->setShowPast($showPast);
		
	}

	public function publishMapWithLegend(){
		$output = $this->makeHeaderWithLegend();
		$output .= $this->makeBodyForLegend();
		return $output;

	}

	private function makeHeaderWithLegend(){
		$output = '<head>
            <meta name="viewport'.$this->getIdName().'" content="initial-scale=1.0, user-scalable=no">
            <meta charset="utf-8">';
		$output .= $this->makeCSS();
		$output .= $this->makeJavascriptForLegend();
		$output .= '</head>';
		return $output;
	}

	private function makeCSS(){
		return '<style>
              html, body {
                height: '.$this->getHeight().'px;
                padding: 0;
                margin: 0;
                }
              #'.$this->getIdName().' {
               height: '.$this->getHeight().'px;
               width: 100%;
               z-index: 10;
               overflow: hidden;
               float: left;
               border: thin solid #333;
               }
              #'.$this->getIdName().$this->getIdName().' {
               height: '.$this->getHeight().'px;
               width: 20%;
               overflow: hidden;
               float: left;
               background-color: #ECECFB;
               border: thin solid #333;
               border-left: none;
               }
		      #legend {
              height: 380px;
               width: 200px;
              		z-index: 20;
		        font-family: Arial, sans-serif;

		        background-color:rgba(255, 255, 255, 1);
		        padding: 10px;
                	margin-top: 0px;

		   
		      }
		      #legend h3 {
		        margin-top: 0;
		      }
		      #legend p {
		        vertical-align: middle;
		      }
         
				#legend a { text-decoration: none;
				               		color:rgba(106,139,183, 1);
									box-shadow: none;}
				#legend a:link{ text-decoration: none;
				               		color:rgba(106,139,183, 1);
					box-shadow: none;}
				#legend a:visited{ text-decoration: none;
				               		color:rgba(106,139,183, 1);
					box-shadow: none;}
				#legend a:hover{ text-decoration: none;
				               		color:rgba(220, 225, 116, 1);
					box-shadow: none;}
				#legend a:active{ text-decoration: none;
				               		color:rgba(220, 225, 116, 1);
					box-shadow: none;}
				
				#triangle img{
				               		z-index: 30;
				              }
				.infowindowContent b {
				            	color:rgba(106,139,183, 1);
							}
				.infowindowContent a { text-decoration: none;
				               		color:rgba(220, 225, 116, 1);
									}
				.infowindowContent a:link{ text-decoration: none;
				               		color:rgba(220, 225, 116, 1);
					box-shadow: none;}
				.infowindowContent a:visited{ text-decoration: none;
				               		color:rgba(220, 225, 116, 1);
					box-shadow: none;}
				.infowindowContent a:hover{ text-decoration: none;
				               		color:rgba(106,139,183, 1);
					box-shadow: none;}
				.infowindowContent a:active{ text-decoration: none;
				               		color:rgba(106,139,183, 1);
					box-shadow: none;}
	         
	
	             #legend::-webkit-scrollbar {
						width: 0.5em;
	               		height: 30%;
	               		margin-top: 20px;
				}
	
				#legend::-webkit-scrollbar-track {
				    -webkit-box-shadow: inset 0 0 0px rgba(106,139,183,0.3);
				}
	
				 #legend::-webkit-scrollbar-thumb {
				  background-color:  rgba(106,139,183,1);
				  outline: 1px solid slategrey;
				}

            </style>';
	}


	private function makeJavascriptForLegend(){
		
		wp_enqueue_script("jquery");
		$categoryList;
		
		$calloutArray = array('listCategories' => 'true');
		$result = Utils::getObjects($calloutArray);
		$categoryList = Utils::makeObjectsOfData($result, 'Category');
			
		
			
		
		
		$objects = $this->getObjects();

		$output = '


				<script>
            var map;
		
            var objectName= new Array();
			var categoryNames = new Array();
			var	objectMarker= new Array();
			var objectLat= new Array();
			var objectLon= new Array();
			var objectDate= new Array();
			var objectIcons = new Array();
			var objectIconsBig = new Array();
      			var objectWebsite= new Array();
			var objectDescription= new Array();
			var markers= new Array();
			var infowindows =new Array();
			var infowindowMarker = -1;
			var showCategories = false;
			var infowindow;';
		 
		$icon = plugins_url().'/krake-kalender/images/icon.png';
		$iconRed = plugins_url().'/krake-kalender/images/iconRed.png';
		$iconbig = plugins_url().'/krake-kalender/images/iconbig.png';
		$iconbigRed = plugins_url().'/krake-kalender/images/iconbigRed.png';	
		
		if($this->getIdName() == "village"){
			for ($i = 0 ; $i < sizeof($objects) ; $i++){
				$output .= 'objectName.push("'.$objects[$i]->getName().'");';
				$output .= 'objectLat.push('.$objects[$i]->getLat().');';
				$output .= 'objectLon.push('.$objects[$i]->getLon().');';
				$output .= 'objectDescription.push("'.$objects[$i]->getDescription().'");';
				$output .= 'objectWebsite.push("'.$objects[$i]->getWebsite().'");';
				$output .= 'objectIcons.push("'.$icon.'");';
				$output .= 'objectIconsBig.push("'.$iconbig.'");';
			}
		}
		
		if($this->getIdName() == "event"){
			for ($i = 0 ; $i < sizeof($objects) ; $i++){
				$output .= 'objectName.push("'.$objects[$i]->getName().'");';
				$output .= 'objectLat.push('.$objects[$i]->getLat().');';
				$output .= 'objectLon.push('.$objects[$i]->getLon().');';
				$myTime = strtotime($objects[$i]->getStartTimeStamp());
				$dateString = date("j" , $myTime)  . "-" . date("n" , $myTime) ." '". date("y" , $myTime) ;
				$output .= 'objectDate.push("'.$dateString.'");';
				
				if(date("Y-m-d" , $myTime) < date("Y-m-d")){
					$output .= 'objectIcons.push("'.$iconRed.'");';
					$output .= 'objectIconsBig.push("'.$iconbigRed.'");';
				

				}else{
					$output .= 'objectIcons.push("'.$icon.'");';
					$output .= 'objectIconsBig.push("'.$iconbig.'");';
					
				}

					

				$output .= 'objectDescription.push("'.$objects[$i]->getDescription().'");';
				$output .= 'objectWebsite.push("'.$objects[$i]->getWebsite().'");';
			}
		}
		
		
			for ($i = 0 ; $i < sizeof($categoryList) ; $i++){
				$output .= 'categoryNames.push("'.$categoryList[$i]->getName().'");';
				
			}
		
		
		
		
		

		
		$output .= '


	        function getMarkers(){
				var bounds = new google.maps.LatLngBounds();
	         	for(var i = 0; i < objectName.length; i++) {
	    			var myLatLng = {lat: objectLat[i] , lng: objectLon[i]};
					
										var marker = new google.maps.Marker({
										          position: myLatLng,
										          map: map,
												  icon: objectIcons[i],
												  descr: i
										        });
	    			marker.addListener("click", function() {
			            	infowindow.close();
							var url;
							if(objectWebsite[this.descr] != "" ){
								url = "<div class=\'infowindowContent\'><b>" + objectName[this.descr] + "</b><br>" + objectDescription[this.descr] +"<br><a href =\'http://" + objectWebsite[this.descr] + "\' >website</a></div>";
							}else{
								url ="<div class=\'infowindowContent\'><b>" + objectName[this.descr] + "</b><br>" + objectDescription[this.descr] +"</div>"	;			
							}					  		
							infowindow = new google.maps.InfoWindow({
          							content: url,
									maxWidth: 150
        						});
					
									infowindowMarker = this.descr;

									infowindow.open(map, markers[this.descr]);
									animateMarker(this.descr)
			        		});
					markers.push(marker);
					bounds.extend(myLatLng);
				}
				map.fitBounds(bounds);
			}
		
			function animateMarker(markerIndex){
				for(var i = 0 ; i < markers.length; i++){
					markers[i].setIcon(objectIcons[i]);
				}
				markers[markerIndex].setIcon(objectIconsBig[markerIndex]);
			}
			
			function openInfoWindow(i){
				infowindow.close();
				var url;
				if(objectWebsite[i] != "" ){
					url = "<div class=\'infowindowContent\'><b>" + objectName[i] + "</b><br>" + objectDescription[i] +"<br><a href =\'http://" + objectWebsite[i] + "\' >website</a></div>";
				}else{
					url ="<div class=\'infowindowContent\'><b>" + objectName[i] + "</b><br>" + objectDescription[i] +"</div>"	;			
				}
					infowindow = new google.maps.InfoWindow({
					content: url,
					maxWidth: 150
        		});
				infowindowMarker = i;
				infowindow.open(map, markers[i]);
			}
			function setMapOnAll(map) {
		        for (var i = 0; i < markers.length; i++) {
		          markers[i].setMap(map);
		        }
		     }
			function emptyArrayName() {
		        for (var i = 0; i < objectName.length; i++) {
		          objectName[i].pop;
		        }
		     }			
							
						
			function clearMarkers() {
			        setMapOnAll(null);
			}
						
			function reloadData(catName){
				console.log(catName);
				jQuery.get( "'.Constants::getCalloutBaseUrlSecure().'?listSpecificVillages=true&category="+catName, function( data ) {
						console.log(data);
						objectName = [];
						emptyArrayName();
						document.getElementById("legend").innerHTML = "";
						objectLat= [];
						objectLon= [];
						objectDescription= [];
						objectWebsite= [];
						clearMarkers();
        				markers = [];
						infowindows=[];
				  		for(var i = 0 ; i < data.length ; i++){
						console.log(data[i].name);
								objectName.push(data[i].name);
								objectLat.push(parseFloat(data[i].lat));
								objectLon.push(parseFloat(data[i].lon));
								objectDescription.push(data[i].description);
							
								objectWebsite.push(data[i].website);
								
							
							
							
						}
						showCategories = true;
						getMarkers();
						setMarkers();
				}, "json" );
						
			}
			function reloadDataAlleDorpen(){
				
				jQuery.get( "'.Constants::getCalloutBaseUrlSecure().'?listSpecificVillages=true", function( data ) {
						console.log(data);
						objectName = [];
						emptyArrayName();
						document.getElementById("legend").innerHTML = "";
						objectLat= [];
						objectLon= [];
						objectDescription= [];
						objectDescription= [];
						objectWebsite= [];
						clearMarkers();
        				markers = [];
						infowindows=[];
				  		for(var i = 0 ; i < data.length ; i++){
						console.log(data[i].name);
								objectName.push(data[i].name);
								objectLat.push(parseFloat(data[i].lat));
								objectLon.push(parseFloat(data[i].lon));
								objectDescription.push(data[i].description);
								objectWebsite.push(data[i].website);
								
							
							
							
						}
						showCategories = false;
						getMarkers();
						setMarkers();
				}, "json" );
						
			}			
	


			function setMarkers(){
	     		
				var legend = document.getElementById(\'legend\');
				var triangle = document.getElementById(\'triangle\');
				var div = document.createElement("div");
				var content = "<div style=\'overflow:auto;\'><table style=\'border: 0px none black; width: 100%; white-space: nowrap; table-layout: fixed; \' border=\'0\' >";;
				//if we are talking about villages, make a list with two columns
				console.log(showCategories);

				//filters toevoegen
				if("'.$this->getShowFilters().'" == "nederlands"){
					if(showCategories){
						content += "<tr style=\'border: none;\'><td>Filter op:</td><td></td></tr><tr>	<td id=\'filters\'  style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><b><a href=\'javascript:reloadDataAlleDorpen()\' >Alle Dorpen</a></b></td><td></td><tr><tr><tr><td><br></td><td></td></tr>";
					}else{
						content += "<tr style=\'border: none;\'><td>Filter op:</td><td></td></tr><td id=\'filters\'  style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><b><a href=\'javascript:reloadData(\"Care\")\' >Per Community</a></b></td></td><td></td></tr><tr><tr><td><br></td><td></td></tr>";
					}	
					
				}else if("'.$this->getShowFilters().'" == "deutch"){
					if(showCategories){
						content += "<tr style=\'border: none;\'><td>Filtern auf:</td><td></td></tr><tr>	<td id=\'filters\'  style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><b><a href=\'javascript:reloadDataAlleDorpen()\' >Alle D&ouml;rfer</a></b></td><td></td><tr><tr><tr><td><br></td><td></td></tr>";
					}else{
						content += "<tr style=\'border: none;\'><td>Filtern auf:</td><td></td></tr><td id=\'filters\'  style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><b><a href=\'javascript:reloadData(\"Care\")\' >Pro Community</a></b></td></td><td></td></tr><tr><tr><td><br></td><td></td></tr>";
					}
	
				}

				//als filter geselecteerd
				if(showCategories){
					console.log(categoryNames);
						
					
					for(var i = 0; i < categoryNames.length; i++) {
		    			//markers[i].setMap(map);

						//set the uneven labels in the left column of the table, the even in the uneven.
						if(i%2==0){
				          	  content = content +  "<tr style=\'border: none;\'><td style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><a href=\'javascript:reloadData(\""+categoryNames[i]+"\");\'     >"+categoryNames[i]+"</a></td>";
		          		}else if (i%2!=0) {
						  content = content +  "<td style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><a href=\'javascript:reloadData(\""+categoryNames[i]+"\");\'     >"+categoryNames[i]+"</a></td></tr>";
			            }
						if(i%2==0 && i == categoryNames.length-1){
							content = content + "<td style=\'border: none; \'></td></tr>";
						}
					}
					content = content + "</table></div>";	
					
						
			 	}else if("' . $this->getIdName() . '" == "village"){
						console.log(showCategories);
										for(var i = 0; i < markers.length; i++) {
		    			markers[i].setMap(map);

						//set the uneven labels in the left column of the table, the even in the uneven.
						if(i%2==0){
				          content = content +  "<tr style=\'border: none;\'><td style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><a href=\'javascript:;\' onMouseOver=\'animateMarker("+i+")\'  onClick=\'openInfoWindow("+i+")\'  >"+objectName[i]+"</a></td>";
		          		}else if (i%2!=0) {
						  content = content +  "<td style=\'border: none; overflow: hidden; text-overflow: ellipsis\'><a href=\'javascript:;\' onMouseOver=\'animateMarker("+i+")\'  onClick=\'openInfoWindow("+i+")\'  >"+objectName[i]+"</a></td></tr>";
			            }
						if(i%2==0 && i == markers.length-1){
							content = content + "<td style=\'border: none; \'></td></tr>";
						}
					}
					content = content + "</table></div>";
				}
				//if we are talking about events, make a list with names and dates
				else if("' . $this->getIdName() . '" == "event"){
					for(var i = markers.length-1; i >= 0; i--) {
		    				markers[i].setMap(map);

						//set the uneven labels in the left column of the table, the even in the uneven.
						
				          content = content +  "<tr style=\'border: none; \'><td style=\'border: none; overflow: hidden; text-overflow: ellipsis; width: 65%;\'><a href=\'javascript:;\' onMouseOver=\'animateMarker("+i+")\'  onClick=\'openInfoWindow("+i+")\'  >"+objectName[i]+"</a></td>";
					  content = content +  "<td style=\'border: none; overflow: hidden; text-overflow: ellipsis; width: 35%;\'>"+objectDate[i]+"</td></tr>";

					}
					content = content + "</table></div>";
				}
				div.innerHTML = content;
				legend.appendChild(div);
			}
						
	
			

            function initMap() {

             		var bangalore = { lat: 52.021585, lng: 6.242922 };
			        map = new google.maps.Map(document.getElementById("'.$this->getIdName().'"), {
			          zoom: 10,
			          center: bangalore,
			          disableDefaultUI: true
			        });
                	infowindow = new google.maps.InfoWindow();
              		getMarkers();
              		setMarkers();
			   
                map.controls[google.maps.ControlPosition.LEFT_TOP].push(legend);
			
			  
			     

			}
			</script>';
		return $output;
	}

	private function makeBodyForLegend(){
		//<div id="legend"><h3>Legend</h3></div>
		$triangle = plugins_url().'/krake-kalender/images/driehoekje.png';
		$output = '<body>
					<div style="position:relative;">';
		
						
		$output.='		<div id="'.$this->getIdName().'"></div>
						<div id="legend" style="overflow:auto;"></div>
						<div id="triangle" style="overflow:auto;"> <img src="'.$triangle.'" ></div>
					</div>
					<script async defer
						src="https://maps.googleapis.com/maps/api/js?key=' . $this->getGoogleAPIToken() . '&callback=initMap">
					</script>

				</body>';
		
		return $output;
	}




	public function getLocationPicker(){

		return '    <head>
        <style type="text/css">
          #map{ width:'.$this->getWidth().'px; height: '.$this->getHeight().'px; }
        </style>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=' . $this->getGoogleAPIToken() . '"></script>
			<script>

		var map; //Will contain map object.
		var marker = false; ////Has the user plotted their location marker?

		//Function called to initialize / create the map.
		//This is called when the page has loaded.
		function initMap(lat, lon) {

		    //The center location of our map.
		    if(lat == null){
		    	centerOfMap = new google.maps.LatLng(51.979579, 6.146263);
			}else{
        		centerOfMap = new google.maps.LatLng(lat, lon);
        		}
     
		    //Map options.
		    var options = {
		      center: centerOfMap, //Set center.
		      zoom: 10 //The zoom value.
		    };

		    //Create the map object.
		    map = new google.maps.Map(document.getElementById(\'map\'), options);
		
        	if(lat == null){
		    	marker = false;
			}else{

        		marker = new google.maps.Marker({
		                position: new google.maps.LatLng(lat, lon),
		                map: map,
		                draggable: true //make it draggable
		            });

        	}

		    //Listen for any clicks on the map.
		    google.maps.event.addListener(map, \'click\', function(event) {
		        //Get the location that the user clicked.
		        var clickedLocation = event.latLng;

		        if(marker === false){

		            //Create the marker.
		            marker = new google.maps.Marker({
		                position: clickedLocation,
		                map: map,
		                draggable: false //make it draggable
		            });

		        } else{
		            //Marker has already been added, so just change its location.
		            marker.setPosition(clickedLocation);
		        }

		        markerLocation();
		    });

        	//Listen for drag events!
		    google.maps.event.addListener(marker, \'dragend\', function(event){
		        var draggedLocation = event.latLng;
        		markerLocation();

		    });
		}


		//values to our textfields so that we can save the location.
		function markerLocation(){
		    //Get location.
		    var currentLocation = marker.getPosition();
		    //Add lat and lng values to a field that we can save.
		    document.getElementById(\'lat\').value = currentLocation.lat(); //latitude (update form)
		    document.getElementById(\'lon\').value = currentLocation.lng(); //longitude (update form)
		    document.getElementById(\'latI\').value = currentLocation.lat(); //latitude (insert form)
		    document.getElementById(\'lonI\').value = currentLocation.lng(); //longitude (insert form)
		}

			</script>
		    </head>
		    <body>


        <!--map div-->
        <div id="mapDiv" style="display: none; float : right;"><h2>Location Picker</h2>
        		<div id="map"></div>
        </div>


    </body>';

	}
}
?>