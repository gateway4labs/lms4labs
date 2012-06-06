<?php

// This file is part of lms4labs
//
// lms4labs is free software: you can redistribute it and/or modify
// it under the terms of the BSD 2-Clause License

// lms4labs is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
//

/**
 * This script forwards the requests performed by the students to the Lab 
 * Manager, providing additional information such as the courses in which the 
 * student is enrolled, the user agent or other additional information.
 *
 * @copyright 2012 Elio San Cristobal, Alberto Pesquera Martin, Pablo Orduña
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @package lms4labs
 */

$DEBUGGING = true;

require_once("../../../config.php");
require_once("../../../lib/accesslib.php");
require_once("lib/HttpClient.class.php");

global $CFG, $USER, $DB, $OUTPUT;

$dato= $HTTP_RAW_POST_DATA; 

$agent=$_SERVER['HTTP_USER_AGENT'];
$dirip=$_SERVER['REMOTE_ADDR'] ;



$auxuser=$USER->username;
$auxname=$USER->firstname. " " . $USER->lastname ;
$courses=get_my_courses($USER->id);
$listcourol= array();

$general=false;
if (is_siteadmin($USER->id)) {
    $general=true;
}

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
$client = new HttpClient('localhost:5000');
$client->setAuthorization("uned", "password");
$client->post('/lms4labs/labmanager/requests/',$request);
$content = $client->getContent();
echo $content;

?>