<?php

$DEBUGGING = true;

if($_SERVER['REQUEST_METHOD'] == "GET") {
	if($DEBUGGING){
	?>
	<html>
	<head>
	    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	    <script type="text/javascript" src="http://www.json.org/json2.js"></script>
	    <script type="text/javascript">
	function perform_post(){
	 $(function() {
	   var payload = {
		'action'     : 'reserve',
		'experiment' : 'robot'
	   };
	   $.ajax( {
		"url"  : "<?php echo $_SERVER['REQUEST_URI'] ?>",
		"data" : JSON.stringify(payload),
		"type" : "POST",
		"contentType" : "application/json",
	    } ).done(function (data) {
		document.getElementsByTagName('body')[0].innerHTML = data;
	    });
	 });
	}
	</script>
	</head>
	<body>
	    <span onclick="javascript:perform_post();">Click me to create a sample request</span>
	</body>
	</html>
	
	<?php
	} else {
	?>
	POST request expected. GET performed.
	<?php
	}
	exit;
}

require_once("../../../config.php");
require_once("lib/HttpClient.class.php");

global $CFG, $USER, $DB, $OUTPUT;

$dato= $HTTP_RAW_POST_DATA; 

$agent=$_SERVER['HTTP_USER_AGENT'];
$dirip=$_SERVER['REMOTE_ADDR'] ;


$auxuser=$USER->username;
$auxname=$USER->firstname. " " . $USER->lastname ;

$courses = enrol_get_my_courses();

$listcourol= array();

$general=false;
if (is_siteadmin()) {
             $general=true; }

foreach ($courses as $course) {


	$context = get_context_instance(CONTEXT_COURSE,$course->id);
	
	if ($roles = get_user_roles($context, $USER->id)) {
	 $i=0;
	 foreach ($roles as $role) {
		$listroles[$i]=$role->name; 
		$i=$i+1;
		}
	}
$listcourol[$course->id] = $listroles;
$listroles=null;
}

$request_data = array( 
                 "user-id"  => $auxuser,
                 "full-name"  => $auxname,
                 "is-admin" => $general,
 		 "user-agent" => $agent,
   		 "origin-ip"  => $dirpip,
		 "courses" => $listcourol,
                 "request-payload" => $dato,
          );

$request= json_encode($request_data);

$cond = array("name" => "LMS-User");
$auxuser=$DB->get_record('lms4labs',$cond);
$cond1 = array("name" => "LMS-Password");
$auxpass=$DB->get_record('lms4labs',$cond1);

$client = new HttpClient('localhost:5000');
// $client->setAuthorization($auxuser->value, $auxpass->value);

$client->setAuthorization("uned", "password");


$client->post('/lms4labs/labmanager/requests/',$request);

$content = $client->getContent();
echo $content;




 