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
 * This script redirects LMS administrator to the Lab Manager acting as a 
 * manager of this LMS.
 *
 * @copyright 2012 Elio San Cristobal, Alberto Pesquera Martin, Pablo Orduña
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 * @package lms4labs
 */


require_once("../../../config.php");
require_once("lib/HttpClient.class.php");
require_once("../../../lib/accesslib.php");

global $CFG, $USER;


$auxname=$USER->firstname. " " . $USER->lastname ;


if (is_siteadmin($USER->id)) {
      $request_data = array( 
                	"full-name"  => $auxname,
      ); 
	$request= json_encode($request_data);
	$auxu=get_record('lms4labs','name', 'LMS-User');
	$auxp=get_record('lms4labs','name', 'LMS-Password');
	$client = new HttpClient('weblab.ieec.uned.es:5000');
	//$client->setAuthorization("uned", "password");
	$client->setAuthorization($auxu->value, $auxp->value);
	$client->post('/lms4labs/labmanager/lms/admin/authenticate/',$request);
	$content = $client->getContent(); 
	echo $content; 	
}
else
 {
	echo "error:You don't have permissions";
}






 