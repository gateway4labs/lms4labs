<?php

$DEBUGGING = true;

if($_SERVER['REQUEST_METHOD'] == "GET") {
    if($DEBUGGING){
    ?>
        <html>
        <head>
            <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
            <script type="text/javascript" src="http://www.json.org/json2.js"></script>
            <script type="text/javascript">
        function perform_post(){
         $(function() {
           var payload = {
            'action'     : 'reserve',
            'experiment' : 'robot'
           };
           $.ajax( {
            "url"  : "<?php echo $_SERVER['REQUEST_URI'] ?>",
            "data" : JSON.stringify(payload),
            "type" : "POST",
            "contentType" : "application/json",
            } ).done(function (data) {
            document.getElementsByTagName('body')[0].innerHTML = data;
            });
         });
        }
        </script>
        </head>
        <body>
            <p>forward.php works only through POST requests. This is a simple sample to check it.</p>
            <span onclick="javascript:perform_post();">Click me to create a sample request</span>
        </body>
        </html>
        
    <?php
    } else {
    ?>
        POST request expected. GET performed.
    <?php
    }
    exit;
}

require_once("../../../config.php");

global $USER, $DB;

require_once("lib/HttpClient.class.php");
require_once("../util.php");

$moodle_username  = $USER->username;
$moodle_full_name = $USER->firstname. " " . $USER->lastname ;

$courses = enrol_get_my_courses();

$courses_list = array();

foreach ($courses as $course) {
    $context = get_context_instance(CONTEXT_COURSE,$course->id);

    $roles_list = array();

    if ($roles = get_user_roles($context, $USER->id)) {
        $curpos = 0;
        foreach ($roles as $role) {
            $roles_list[$curpos]=$role->name; 
            $curpos = $curpos + 1;
        }
    }

    $courses_list[$course->id] = $roles_list;
}

$request_data = array( 
                 "user-id"         => $moodle_username,
                 "full-name"       => $moodle_full_name,
                 "is-admin"        => is_siteadmin(),
                 "user-agent"      => $_SERVER['HTTP_USER_AGENT'],
                 "origin-ip"       => $_SERVER['REMOTE_ADDR'],
                 "courses"         => $courses_list,
                 "request-payload" => $HTTP_RAW_POST_DATA,
          );

$request= json_encode($request_data);

echo l4l_post_request($request);
 
