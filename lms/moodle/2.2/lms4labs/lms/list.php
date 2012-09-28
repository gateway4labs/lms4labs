<?php

require_once("../../../config.php");

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="lab4lms"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'You must provide credentials';
    exit;
}

require_once("../util.php");

global $DB; 

$labmanager_user     = l4l_retrieve_field("Labmanager-User");
$labmanager_password = l4l_retrieve_field("Labmanager-Password");

if ($_SERVER['PHP_AUTH_USER'] != $labmanager_user || md5($_SERVER['PHP_AUTH_PW']) != $labmanager_password) {
	echo "Error: invalid username or password";
	exit;
}

// 
// Argument parsing
// 

$N     = 10;
$query = $_GET["q"];
$start = $_GET["start"];

if (is_null($query))
	$query = "";


if (is_null($start))
	$start = 0;

else if (!is_numeric($start)) 
    $start = (int)$start;

// 
// Course retrieval
// 
$retrieve_course_sql = "".
        "SELECT id, fullname, shortname FROM {course} " . 
        "WHERE fullname LIKE '%" . addslashes($query) . "%' ".
        "LIMIT " . $start . ", " . ($start + $N);

$courses =$DB->get_records_sql($retrieve_course_sql); 

$count_courses_sql = "" .
        "SELECT COUNT(id)  FROM {course} " . 
        "WHERE fullname LIKE '%" . addslashes($query)  . "%'";

$number = (int) $DB->count_records_sql($count_courses_sql); 

// 
// Creating return values
// 

$listcourses = array();

foreach ($courses as $course) 
    array_push($listcourses, array(
                    "id" => $course->id, 
                    "name" => $course->shortname
            ));


if(count($listcourses) == 0)
	echo "{}";
else
	echo json_encode(array( 
                 "start"    => $start,
                 "number"   => $number,
                 "per-page" => $N,
		         "courses"  => $listcourses,
          )); 
