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


require_once("../../../config.php");
require_once("../../../lib/accesslib.php");

global $DB, $CFG; 

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="lab4lms"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
    exit;
}

if ( ($_SERVER['PHP_AUTH_USER'] == 'labmanager') && ($_SERVER['PHP_AUTH_PW'] == 'password' ) ) {
    // It's a valid user
} else {
	echo "Error: invalid username or password";
	exit;
}


$N     = 2;
$dato=$_GET["q"];
$start=$_GET["start"];

if (is_null($dato))
	$dato="" ;


if (is_null($start))
	$start=0 ;
elseif (!is_numeric($start)) 
    $start= (int) $start;


$sql1 = "SELECT id, fullname, shortname FROM " . $CFG->prefix . "course WHERE fullname LIKE " . '"%' . addslashes($dato) . '%" LIMIT ' . $start . ", " . ($start + $N);
$courses =get_records_sql($sql1); 
$sql2 = "SELECT COUNT(id)  FROM {course} WHERE fullname LIKE '%" . addslashes($dato)  . "%'";
$number= (int) count_records_sql($sql2); 

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
        "start"    => $start,
        "number"   => $number,
        "per-page" => $N,
		"courses"  => $listcourses,
    );
    echo json_encode($request_data); 
}
