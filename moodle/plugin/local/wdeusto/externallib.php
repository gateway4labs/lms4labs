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
 * external API web services
 *
 * @package    wdeusto
 * @subpackage wdeusto
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


require_once("$CFG->libdir/externallib.php");
require_once("$CFG->libdir/deusto/weblabdeusto.class.php");

class moodle_wdeusto_external extends external_api {


// funcion de parametros de entrada

    public static function get_visir_parameters() {
	 return new external_function_parameters(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of user'),
            )
        );
    }
 
 
/**
     * Return url
*/

    function get_visir($name) {
        

        $params = self::validate_parameters(self::get_visir_parameters(),
                      array('name'=>$name));
      
       $weblab = new WebLabDeusto("http://localhost/weblab/");
	  $sess_id = $weblab->login("any","password");
	  $exp_name = "visirtest";
	  $cat_name = "Dummy experiments";

	  $consumer_data = array( 
		"user_agent"    => $_SERVER['HTTP_USER_AGENT'],
    		"referer"       => $_SERVER['HTTP_REFERER'],
    		"from_ip"       => $_SERVER['REMOTE_ADDR'],
    		"external_user" => $name,


	   );
	
	$reservation_status = $weblab->reserve($sess_id, $exp_name, $cat_name,"{}", $consumer_data);

	$url = $weblab->create_client($reservation_status);
	return $url;
  }


//función de parametros de salida   

    public static function get_visir_returns() {
         return new external_value(PARAM_TEXT, 'url');       
    }  

//.................................................................................................................

// funcion de parametros de entrada

    public static function get_logic_parameters() {
	 return new external_function_parameters(
            array(
                'name' => new external_value(PARAM_TEXT, 'name of user'),
            )
        );
    }
 
 
/**
     * Return url
*/

    function get_logic($name) {
        
        $params = self::validate_parameters(self::get_logic_parameters(),
                      array('name'=>$name));
	  $weblab = new WebLabDeusto("http://localhost/weblab/");
	  $sess_id = $weblab->login("any","password");
	  $exp_name = "ud-logic";
	  $cat_name = "PIC experiments";

	  $consumer_data = array( 
		"user_agent"    => $_SERVER['HTTP_USER_AGENT'],
    		"referer"       => $_SERVER['HTTP_REFERER'],
    		"from_ip"       => $_SERVER['REMOTE_ADDR'],
    		"external_user" => $name,


	   );
	
	$reservation_status = $weblab->reserve($sess_id, $exp_name, $cat_name,"{}", $consumer_data);

	$url = $weblab->create_client($reservation_status);
	
      return $url;

  }


//función de parametros de salida   

    public static function get_logic_returns() {
         return new external_value(PARAM_TEXT, 'url');       
    }  


}