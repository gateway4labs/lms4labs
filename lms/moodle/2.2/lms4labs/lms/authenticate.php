<?php



require_once("../../../config.php");
require_once("lib/HttpClient.class.php");

global $CFG, $USER;


$auxname=$USER->firstname. " " . $USER->lastname ;


$cond = array("name" => "LMS-User");
$auxuser=$DB->get_record('lms4labs',$cond);
$cond1 = array("name" => "LMS-Password");
$auxpass=$DB->get_record('lms4labs',$cond1);

if (is_siteadmin()) {
      $request_data = array( 
                	"full-name"  => $auxname,
      ); 
	$request= json_encode($request_data);
	$client = new HttpClient('localhost:5000');
	$client->setAuthorization($auxuser->value, $auxpass->value);
	$client->post('/lms4labs/labmanager/lms/admin/authenticate/',$request);
	$content = $client->getContent(); 
	echo $content; 	
}
else
 {
	echo "error:You don't have permissions";
}






 