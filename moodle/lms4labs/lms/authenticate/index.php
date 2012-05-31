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
require_once("../lib/HttpClient.class.php");

global $CFG, $USER;


$auxname=$USER->firstname. " " . $USER->lastname ;


if (is_siteadmin()) {
      $request_data = array( 
                	"full-name"  => $auxname,
      ); 
	$request= json_encode($request_data);
	$client = new HttpClient('localhost:5000');
	$client->setAuthorization("uned", "password");
	$client->post('/lms4labs/labmanager/lms/admin/authenticate/',$request);
	$content = $client->getContent(); 
	echo $content; 	
}
else
 {
	echo "error:You don't have permissions";
}






 