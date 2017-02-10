<?php

/*
Plugin Name: KrakeKalender 
Plugin URI: http://jille.nl
Description: The Calender plugin made to show the activities in the border region of the Netherlands and Germany.
Version: 1.0
Author: Jille Treffers,  HAN University of Applied Sciences
Author URI: http://www.jille.nl
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: krake-kalender
*/

include_once 'Constants.php';
include_once 'Table.php';
include_once 'GeneralUtilities.php';
include_once 'GoogleMap.php';

/*
************************ SHORTCODES ***************************
*/
// adds all of the shortcodes defined in the kra_register_shortcodes function to init hook
add_action('init', 'kra_register_shortcodes');
wp_enqueue_script("jquery");
//collection of all shortcodes
function kra_register_shortcodes() {
	add_shortcode('kra_makeMap', 'kra_makeMap');
}


function kra_makeMap($args, $content=""){
	
	//get filter parameters
	$showVillages = ($args['villages'] == true) ? true : false;
	$showEvents = ($args['events'] == true) ? true : false;
	$showPast = ($args['showpast'] == true) ? true : false;
	
	$region = ($args['region'] != null)? $args['region'] : '';
	$category = ($args['category'] != null)? $args['category'] : '';
	$days = ($args['days'] != null)? $args['days'] : 720; // defaults to two years
	//what view to show?
	$showFilters = ($args['filters'] != null)? $args['filters'] : false;
	$showMap = ($args['map'] != null)? $args['map'] : false;
	$showList = ($args['list'] != null)? $args['list'] : false;
	//if neither is set, make Mapview the default
	$showMap = (!$showList && !$showMap)? true : $showMap;
	
	// get the current datetime
	$timeMin = date ( 'Y-m-d' );
	// get the last date to show (defaults to one year)
	$timeMax = date ( 'Y-m-d' ,time() + ($days * 24 * 60 * 60));
	$type = '';

	if($showPast){
		$timeMin = "2014-12-16";
	}
	
	if($showVillages && $showEvents){
		$showVillages = false;
	}
	
	if(isset($_GET['events'])) {
		$showEvents = true;
		$showVillages = false;
	}
	if(isset($_GET['villages'])) {
		$showEvents = false;
		$showVillages = true;
	}
	if($showEvents){
		$type = 'event';
	}else{
		$type = 'village';
	}
	
	$calloutArray = array('list' => 'true', 'begindate' => $timeMin , 'enddate'=>$timeMax , 'region'=>$region, 'category'=>$category );
	if($showEvents){
		$calloutArray = array('list' => 'true', 'begindate' => $timeMin , 'enddate'=>$timeMax , 'region'=>$region, 'category'=>$category );
	}else if($showVillages){
		$calloutArray = array('listSpecificVillages' => 'true', 'region'=>$region, 'category'=>$category);
	}
	
	$output = '';
	
	if($showList) {
		//create table
		$columnsToShow = array('name' => 'Name', 'description' => 'Description');
		$table = new Table('Event', $columnsToShow, false, $calloutArray);
		$output .= $table->createTable();
	}
	if($showMap){
		$result = Utils::getObjects($calloutArray);
		$events = Utils::makeObjectsOfData($result, $type);
		$googleMap = new GoogleMap(Constants::getMapsApiKey(), $events, $type, '500', '400', $showFilters, $showPast);
		$output .= $googleMap->publishMapWithLegend();
	}
	

	return $output;
}

/*
******************************** ADMIN MENUS ************************************
*/
add_action('admin_menu','createAdminMenus');

function createAdminMenus(){

    add_menu_page('', 'Manage Events' , 'manage_options', 'manageEvents' , 'manageEvents', 'dashicons-calendar');
    add_submenu_page('manageEvents', '', 'Manage Villages' , 'manage_options' , 'manageVillages', 'manageVillages');
    add_submenu_page('manageEvents', '', 'Manage Regions' , 'manage_options' , 'manageRegions','manageRegions');
    add_submenu_page('manageEvents', '', 'Manage Categories' , 'manage_options' , 'manageCategories', 'manageCategories');
}

function manageVillages(){
	include 'VillageAdminPage.php';
	
    echo $output;
}

function manageRegions(){
	include 'RegionAdminPage.php';
    echo $output;
}

function manageCategories(){
	include 'CategoryAdminPage.php';
    echo $output;

}
function manageEvents(){
	include 'EventsAdminPage.php';
    echo $output;
}






