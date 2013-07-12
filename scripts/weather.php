<?php
// DANTE SCRIPT ======
// Name: Weather Grabber
// Description: Custom Script for Cryptopolonium.
// Author: SubjectX52873M
// Version: 1.1
// Priv: 25
// Help: "weather (stationID)" Displays current weather for a place in the US
// ====================
//find a feed for any place in the US -> http://www.weather.gov/data/current_obs/
//XML Feed for SubjectX52873M's home http://www.weather.gov/data/current_obs/KGRB.xml
if( empty( $args[1] ) ) {
  $say="To use this command you need to grab a Station ID for your area. This command can only grab weather for areas in the United States. <a href=\"http://www.weather.gov/data/current_obs/\">[Find a station near you]</a>";
} elseif( is_numeric( $args[1] ) ) {
	$say="You need a proper station ID not a zip code. For example <code>{$tr}weather KGRB</code> grabs the current weather for Green Bay, WI. <a href=\"http://www.weather.gov/data/current_obs/\">[Find a station near you]</a> (US only)";
} elseif( $args[1] == "about" ) {
	$say="US Weather for Dante, coded by Subject<b></b>X52873M";
} else {
	$weather = @simplexml_load_file( "http://www.weather.gov/data/current_obs/" . $args[1] . ".xml" );
	if ($weather === false) {
	$say = "Unable to retrieve station. This command can only grab weather data for the United States. <a href=\"http://www.weather.gov/data/current_obs/\">[Find a station near you]</a>";
	} else {
		//Grab data
		$dAmn->say( "Retrieving Data...", $c );
		$time = strtotime( $weather->observation_time_rfc822 );
		$say = '';
		//Format data into something displayable.
		if ($weather->location != "NA") $say .= "<b>" . $weather->location . "</b><br>";
		if ($weather->observation_time_rfc822 != "NA") $say .= "<i>Last report update: " . timeString( ( time() - $time ), TRUE ) . " ago</i><br>";
		if ($weather->weather != "NA") $say .= "<b>Weather</b> " . $weather->weather . "<br>";
		if ($weather->temperature_string != "NA") $say .= "<b>Temp</b> " . $weather->temperature_string . "<br>";
		if ($weather->windchill_string != "NA") $say .= "<b>Wind Chill</b> " . $weather->windchill_string . "<br>";
		if ($weather->heat_index_string != "NA") $say .= "<b>Heat Index</b> " . $weather->heat_index_string . "<br>";
		if ($weather->wind_string != "NA") $say .= "<b>Wind</b> " . $weather->wind_string . "<br>";
		if ($weather->relative_humidity != "NA") $say .= "<b>Humidity</b> " . $weather->relative_humidity . "%<br>";
		if ($weather->pressure_in != "NA") $say .= "<b>Pressure</b> " . $weather->pressure_in . "\" (" . $weather->pressure_mb . "mbHg) <br>";
		if ($weather->dewpoint_string != "NA") $say .= "<b>Dew Point</b> " . $weather->dewpoint_string . "<br>";
		if ($weather->visibility_mi == "10.00") $say .= "<b>Visiblity</b> Unlimited";
		elseif ($weather->visibility_mi != "NA") $say .= "<b>Visiblity</b> " . $weather->visibility_mi . "mi";
	}
}
$dAmn->say( $say, $c );
/*
 object(SimpleXMLElement)#53 (42) {
 ["@attributes"]=>
 array(1) {
 ["version"]=>
 string(3) "1.0"
 }
 ["credit"]=>
 string(31) "NOAA's National Weather Service"
 ["credit_URL"]=>
 string(19) "http://weather.gov/"
 ["image"]=>
 object(SimpleXMLElement)#54 (3) {
 ["url"]=>
 string(38) "http://weather.gov/images/xml_logo.gif"
 ["title"]=>
 string(31) "NOAA's National Weather Service"
 ["link"]=>
 string(18) "http://weather.gov"
 }
 ["suggested_pickup"]=>
 string(25) "15 minutes after the hour"
 ["suggested_pickup_period"]=>
 string(2) "60"
 ["location"]=>
 string(52) "Green Bay, Austin Straubel International Airport, WI"
 ["station_id"]=>
 string(4) "KGRB"
 ["latitude"]=>
 string(9) "44.28.46N"
 ["longitude"]=>
 string(10) "088.08.12W"
 ["observation_time"]=>
 string(34) "Last Updated on Feb 4, 7:53 pm CST"
 ["observation_time_rfc822"]=>
 string(35) "Sun,  4 Feb 2007 19:53:00 -0600 CST"
 ["weather"]=>
 string(4) "Fair"
 ["temperature_string"]=>
 string(12) "-9 F (-23 C)"
 ["temp_f"]=>
 string(2) "-9"
 ["temp_c"]=>
 string(3) "-23"
 ["relative_humidity"]=>
 string(2) "52"
 ["wind_string"]=>
 string(23) "From the West at 10 MPH"
 ["wind_dir"]=>
 string(4) "West"
 ["wind_degrees"]=>
 string(3) "270"
 ["wind_mph"]=>
 string(5) "10.35"
 ["wind_gust_mph"]=>
 string(2) "NA"
 ["pressure_string"]=>
 string(18) "30.34" (1029.8 mb)"
 ["pressure_mb"]=>
 string(6) "1029.8"
 ["pressure_in"]=>
 string(5) "30.34"
 ["dewpoint_string"]=>
 string(13) "-22 F (-30 C)"
 ["dewpoint_f"]=>
 string(3) "-22"
 ["dewpoint_c"]=>
 string(3) "-30"
 ["heat_index_string"]=>
 string(2) "NA"
 ["heat_index_f"]=>
 string(2) "NA"
 ["heat_index_c"]=>
 string(2) "NA"
 ["windchill_string"]=>
 string(13) "-27 F (-33 C)"
 ["windchill_f"]=>
 string(3) "-27"
 ["windchill_c"]=>
 string(3) "-33"
 ["visibility_mi"]=>
 string(5) "10.00"
 ["icon_url_base"]=>
 string(42) "http://weather.gov/weather/images/fcicons/"
 ["icon_url_name"]=>
 string(8) "nskc.jpg"
 ["two_day_history_url"]=>
 string(47) "http://www.weather.gov/data/obhistory/KGRB.html"
 ["ob_url"]=>
 string(45) "http://www.nws.noaa.gov/data/METAR/KGRB.1.txt"
 ["disclaimer_url"]=>
 string(34) "http://weather.gov/disclaimer.html"
 ["copyright_url"]=>
 string(34) "http://weather.gov/disclaimer.html"
 ["privacy_policy_url"]=>
 string(30) "http://weather.gov/notice.html"
 }
 */
