<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Wrapper script redirecting user operations to correct destination.
 *
 * @copyright 1999 Martin Dougiamas  http://dougiamas.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package user
 */


require_once("../config.php");
//require_once("/lib/HttpClient.class.php");

global $CFG, $USER, $DB, $COURSE, $OUTPUT;

$dato= $_GET["request_payload"]; 



$auxuser=$USER->username;
$auxname=$USER->firstname. " " . $USER->lastname ;

echo $auxname;
echo "<br>";
echo $COURSE->id;
//$courses = enrol_get_my_courses();

//$listcourol= array();

//foreach ($courses as $course) {


//	$context = get_context_instance(CONTEXT_COURSE,$course->id);
	
//	if ($roles = get_user_roles($context, $USER->id)) {
//	 $i=0;
//	 foreach ($roles as $role) {
//		if ($role->name == 'Manager') {
  //           $general=$role->name; }
	//	$listroles[$i]=$role->name; 
	//	$i=$i+1;
//		}
//	}
// $listcourol[$course->id] = $listroles;
//$listroles=null;
//}

//$request_data = array( 
  //               "author"  => $auxuser,
    //             "complete-name"  => $auxname,
      //           "general-role" => $general,
	//	     "courses" => $listcourol,
        //         "request-payload" => $dato,
//          );

//$request= json_encode($request_data);
//echo $request;
//$client = new HttpClient('localhost:5000');
//$client->setAuthorization("uned", "password");
//$client->$headers[] = 'Content-Type: application/json';
//$client->post('/lms4labs/requests/',$request);
//echo "mola";
//$content = $client->getContent();
//var_dump($content);




 