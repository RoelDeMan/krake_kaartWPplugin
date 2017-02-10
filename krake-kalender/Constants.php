<?php
/**
 * Created for HAN.
 * User: Jille
 * Date: 7/26/2016
 * Time: 3:03 PM
 */
class Constants{
    const MAPS_API_KEY = 'AIzaSyAqv8tYOtRgdWBbEPvbtqP6mqtmXVjM4S8';
    const CALLOUT_BASEURL = 'https://www.project-krake.eu/webservice/krakekalender.php/';
    const CALLOUT_BASEURL_SECURE = '/webservice/krakekalender.php/';

    public static function getMapsApiKey(){
        return self::MAPS_API_KEY;
    }
    public static function getCalloutBaseUrl(){
        return self::CALLOUT_BASEURL;
    }
    public static function getCalloutBaseUrlSecure(){
    	return self::CALLOUT_BASEURL_SECURE;
    }
}