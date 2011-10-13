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
 * Manual plugin external functions and service definitions.
 *
 * @package    wlab
 * @subpackage wlab
 * @author 2011 Elio san cristobal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(

    // === visir ===//
        'moodle_wdeusto_get_visir' => array(
        'classname'   => 'moodle_wdeusto_external',
        'methodname'  => 'get_visir',
        'classpath'   => 'local/wdeusto/externallib.php',
        'description' => 'return url',
        'type'        => 'read',
    	  ),

    // === logic ===//

	  'moodle_wdeusto_get_logic' => array(
        'classname'   => 'moodle_wdeusto_external',
        'methodname'  => 'get_logic',
        'classpath'   => 'local/wdeusto/externallib.php',
        'description' => 'return url',
        'type'        => 'read',
    	  ),

);
