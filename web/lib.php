<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


date_default_timezone_set(get_ha_timezone());

$logfile="/share/kodi2sonos/kodi2sonos.log";
$options = json_decode(file_get_contents($options_json_file) );

$Mysql = $options->Mysql;
$dbname = $Mysql->dbname;
$servername = $Mysql->servername;
$db_username = $Mysql->dbusername;
$db_password = $Mysql->dbpassword;

$URIs = $options->URIreplace;
$uri_search = $URIs->uri_search;
$uri_replace = $URIs->uri_replace; 
$uri_search_song = $URIs->uri_search_song;
$uri_replace_song = $URIs->uri_replace_song;
$uri_search_cover = $URIs->uri_search_cover;
$uri_replace_cover = $URIs->uri_replace_cover;

$destinations = $options->Destinations;
$Sonos_1_ip = $destinations->Sonos_1_ip;
$Sonos_1_name = $destinations->Sonos_1_name; 
$Kodi_1_ip = $destinations->Kodi_1_ip;
$Kodi_1_name = $destinations->Kodi_1_name;
$Kodi_2_ip = $destinations->Kodi_2_ip; 
$Kodi_2_name = $destinations->Kodi_2_name;

$Kodi_credentials = $options->Kodi_credentials;
$Kuser = $Kodi_credentials->kodi_user;
$Kpassword = $Kodi_credentials->kodi_password; 
$Kport = $Kodi_credentials->kodi_port; 

$conn = mysqli_connect($servername, $db_username, $db_password,$dbname);
// Check MySQL connection
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}

if ($options->debug) {
		error_reporting(E_ALL);
    } else {
		error_reporting(0);
	}



function call_HA (string $eid, string $action ) {
	global $SUPERVISOR_TOKEN;
	global $HASSIO_URL;

    $domain = explode(".",$eid);
    $command_url = $HASSIO_URL . "/services/" . $domain[0] . "/turn_" . $action;
	
    $postdata = "{\"entity_id\":\"$eid\"}" ;
	
	$curlSES=curl_init(); 
	curl_setopt($curlSES,CURLOPT_URL,"$command_url");
	curl_setopt($curlSES,CURLOPT_POST, 1);
	// curl_setopt($curlSES,CURLOPT_VERBOSE, 1);
	curl_setopt($curlSES,CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlSES,CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($curlSES,CURLOPT_HTTPHEADER, array(
		'content-type: application/json',
		"Authorization: Bearer $SUPERVISOR_TOKEN"
	));
	
	$result = curl_exec($curlSES);
	curl_close($curlSES);	
	$ts = date("Y-m-d H:i");
	echo "\n\n$ts --> Turning $action $eid \n\n";
	return $result;
}

?>







