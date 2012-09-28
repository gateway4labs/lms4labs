<?php

function l4l_retrieve_field($field) {
    global $DB;
    $cond = array("name" => $field);
    return $DB->get_record('lms4labs',$cond)->value;
}

function l4l_client_request($relative_path, $request) {
    $labmanager_url = l4l_retrieve_field("Labmanager-Url");
    $lms_user       = l4l_retrieve_field("LMS-User");
    $lms_pass       = l4l_retrieve_field("LMS-Password");

    $splitted_url = parse_url($labmanager_url);

    $host = $splitted_url['host'];
    if(array_key_exists('port', $splitted_url))
        $port = $splitted_url['port'];
    else
        $port = 80;

    require_once("lms/lib/HttpClient.class.php");
    $client = new HttpClient($host . ':' . $port);
    $client->setAuthorization($lms_user, $lms_pass);

    $basepath = $splitted_url['path'];
    $client->post($basepath . $relative_path, $request);
    return $client->getContent();
}

function l4l_post_request($request) {
    return l4l_client_request('/labmanager/requests/', $request);
}

function l4l_authenticate($request) {
    return l4l_client_request('/labmanager/lms/admin/authenticate/', $request);
}

?>
