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
 * Prints a particular instance of wlab
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package   mod_wlab
 * @copyright 2010 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace wlab with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require($CFG->libdir.'/deusto/weblabdeusto.class.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // wlab instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('wlab', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $wlab  = $DB->get_record('wlab', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $wlab  = $DB->get_record('wlab', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $wlab->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('wlab', $wlab->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

add_to_log($course->id, 'wlab', 'view', "view.php?id=$cm->id", $wlab->name, $cm->id);

global $DB, $USER;

/// Print the page header

$PAGE->set_url('/mod/wlab/view.php', array('id' => $cm->id));
$PAGE->set_title($wlab->name);
$PAGE->set_heading($course->shortname);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename', 'wlab')));

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');

// Output starts here
echo $OUTPUT->header();

// Replace the following lines with you own code

$exp=$DB->get_record('wlab',array('id'=>$wlab->id));
$lab=$DB->get_record('wlab_experiment',array('id'=>$exp->idexp));
$access=$DB->get_record('wlab_list',array('id'=>$lab->idlab));
$dataaccess=$DB->get_record('wlab_access',array('id'=>$access->accesstype));

if (stripos($dataaccess->name,"deusto")){


      echo "Reservating Experiment";

	
	$conn=$DB->get_record('wlab_connection_deusto',array('idlab'=>$lab->idlab));
	
	$user=$conn->username;
	$password=$conn->pass;
	
	$weblab = new WebLabDeusto($conn->url);

	
	$sess_id = $weblab->login($user,$password);

	// Create reservation (still with session_id)
	//

	
	$ch='@';
	$cad=$lab->name;
	$n=stripos($cad,$ch);
	$exp_name = substr($cad,0,$n);
	$cat_name = substr($cad,$n+1);;
	

	$consumer_data = array(


    		"user_agent"    => $_SERVER['HTTP_USER_AGENT'],
    		"referer"       => $_SERVER['HTTP_REFERER'],
    		"from_ip"       => $_SERVER['REMOTE_ADDR'],

 
    		"external_user"                 => 	$USER->username,
		//    "priority"                      => 3, // the lower, the better
		//   "time_allowed"]                 => 100, // seconds
		//    "initialization_in_accounting"] => false,

	);

	
	$reservation_status = $weblab->reserve($sess_id, $exp_name, $cat_name,"{}", $consumer_data);
	

	// Optional: check the reservation (now with reservation_id)

	$reservation_status = $weblab->get_reservation_status($reservation_status->reservation_id);


	$client_url = $weblab->create_client($reservation_status);


	echo "<br/><br/><iframe frameborder=0 width=100% height=600 src=" . $client_url . "></iframe>";

}






// Finish the page
echo $OUTPUT->footer();
