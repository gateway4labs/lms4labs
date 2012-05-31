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


require_once("../../../../config.php");


global $DB; 

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="lab4lms"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}

$ok = 0;
if ( ($_SERVER['PHP_AUTH_USER'] == 'labmanager') && ($_SERVER['PHP_AUTH_PW'] == 'password' ) ){

	$ok = 1;
}

if($ok == 0) {
	echo "Error: invalid username or password";
	exit;
}

//$courses = $DB->get_records('course');


$N     = 2;
$dato=$_GET["q"];
$start=$_GET["start"];



if (is_null($dato)) {
	$dato="" ;}


if (is_null($start)){
	$start=0 ;}
elseif (!is_numeric($start)) {
 $start= (int) $start;
}
	
// echo "dato" . $dato . "<br>";
// echo "start" . $start . "<br>";


//$sql = "SELECT a.* FROM {course} a WHERE a.fullname LIKE " . "'%" . addslashes($dato) . "%' AND a.format <> 'site'";
//$courses =$DB->get_records_sql($sql); 



$sql1 = "SELECT id, fullname, shortname FROM {course} WHERE fullname LIKE". "'%" . addslashes($dato) . "%' LIMIT " . $start . ", " . ($start + $N);
$courses =$DB->get_records_sql($sql1); 
$sql2 = "SELECT COUNT(id)  FROM {course} WHERE fullname LIKE '%" . addslashes($dato)  . "%'";
$number= (int) $DB->count_records_sql($sql2); 

$listcourses = array();

$i=0;
foreach ($courses as $auxcourse) {
		
		$listcourses[$i] = array ("id" => $auxcourse->id, "name" => $auxcourse->shortname);
		$i=$i+1;
}


if(count($listcourses) == 0)
	echo "{}";
else {
	$request_data = array( 
                 "start"  => $start,
                 "number"  => $number,
                 "per-page" => $N,
		 "courses" => $listcourses,
          );
	echo json_encode($request_data); }