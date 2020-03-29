<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once("conf.php");

date_default_timezone_set(get_ha_timezone());

$logfile="/share/kodi2sonos/kodi2sonos.log";
$options = json_decode(file_get_contents($options_json_file) );
$translations = $options->translations;
$components = (array) $options->components;

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

function get_ha_timezone() {
	global $SUPERVISOR_TOKEN;
	global $HASSIO_URL;
	
	$curlSES=curl_init(); 
	curl_setopt($curlSES,CURLOPT_URL,"$HASSIO_URL/config");
	curl_setopt($curlSES,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curlSES, CURLOPT_HTTPHEADER, array(
		'content-type: application/json',
		"Authorization: Bearer $SUPERVISOR_TOKEN"
	));
	
	$result = json_decode( curl_exec($curlSES) );
	curl_close($curlSES);
	return $result->time_zone;
}

function load_data() {
	global $json_folder;
	$sched = array();
	$filter = "*.json";
	$filelist = glob($json_folder.$filter);
	foreach ($filelist as $f) {
		$content = file_get_contents($f);
		$sched[] = json_decode($content);
	}
	return $sched;
	
}

function delete_file(string $id) {
	global $json_folder;
	$filename = $json_folder.$id.".json";	
	unlink ($filename);
	return $filename;
}

function create_file(string $eid) {
	global $json_folder;
	$on_dow="";
	$off_dow="";
	$id = ($eid!="") ? $eid : uniqid();
	$filename = $json_folder.$id.".json";
	if ($_POST['on_dow']) foreach($_POST['on_dow'] as $v) $on_dow.= $v;
	if ($_POST['off_dow']) foreach($_POST['off_dow'] as $v) $off_dow.= $v;
	
	$item = new stdClass();	
	$item->id = $id;
	$item->enabled   = $_POST["enabled"];
	$item->entity_id = $_POST["entity_id"];
	$item->on_tod 	 = $_POST["on_tod"];
	$item->on_dow    = $on_dow;
	$item->off_tod 	 = $_POST["off_tod"];
	$item->off_dow 	 = $off_dow;
	file_put_contents($filename, json_encode($item));
	
	return $filename;
}

function get_friendly_html_dow(string $s,bool $on) {
	global $weekdays;
	$html = "<div>";
	$onOffClass = ($on) ? "dowHiglightG" : "dowHiglightR";
	for($i=1; $i<=7; $i++) {
		$d = substr($weekdays[$i],0,2);
		$class = (strpos($s, chr($i+48)  ) !== false ) ? $onOffClass : "" ;
		$html .= "<div class=\"dowIcon $class \" >$d</div> ";
	}
	$html .= "</div>";
	return $html;
	
}

function get_switch_html_select_options() {	
	$html = "";
	$switch_list = get_switch_list();	
	$comp="";
	foreach ($switch_list as $s) {
		$c = explode('.',$s->entity_id); 
		if ($comp!=$c[0]) {
			if ($comp!="") $html .= '</optgroup>';
			$comp=$c[0];
			$html .= '<optgroup label="' . $comp . '">';
		}
		$name = ($s->friendly_name=="") ? $s->entity_id : "$s->friendly_name ($s->entity_id)" ;
		$html .= "<option value=\"$s->entity_id\">$name</option>";
	}
	$html .= '</optgroup>';
	return $html;
}

function is_checked(string $s , int $i) {
	$checked = "checked";
	if (strpos($s, chr($i+48)  ) === false ) $checked = "";
	return $checked;
}

 
 function is_scheduler_running() {
	$response=0;
	try {
		exec("pgrep -af scheduler", $pids);
		foreach ($pids as $p) {		if (strpos($p,"scheduler.php")!== false) $response=1; }
	} catch (Exception $e) {
		if ($options->debug) echo 'Caught exception: ',  $e->getMessage(), "\n";
	}		
	return $response ;
 }