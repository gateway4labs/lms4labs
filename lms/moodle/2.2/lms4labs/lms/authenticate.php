<?php



require_once("../../../config.php");
require_once("lib/HttpClient.class.php");
require_once("../util.php");

global $CFG, $USER;

if (is_siteadmin()) {
    $moodle_name = $USER->firstname. " " . $USER->lastname ;
    $request_data = array( "full-name"  => $moodle_name ); 
	$request= json_encode($request_data);
	echo l4l_authenticate($request);	
}
else
 {
	echo "error:You don't have permissions";
}




 
