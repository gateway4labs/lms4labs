#
#  Copyright (C) 2012 DIEEC - Department of Engineering, Electric and Control (UNED)
#
#  This file is part of lms4labs
#
#  lms4labs is free software: you can redistribute it and/or modify
#  it under the terms of the BSD 2-Clause License
#
#  lms4labs is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
#

ad_page_contract {

    This script forwards the requests performed by the students to the Lab 
    Manager, providing additional information such as the courses in which the 
    student is enrolled, the user agent or other additional information.

    Returns a structure JSON with user and courses information to Lab Manager

    @author Alberto Pesquera Martin (apm@innova.uned.es)
    @author Elio San Cristobal Ruiz (elio@ieec.uned.es)
    @author Pablo Orduña (pablo.orduña@deusto.es)
    @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
    @package lms4labs
    @creation-date 22 May 2012

} {
}

set user_id [ws::json::get_user_id]
set headers [ns_conn headers]
ns_log notice "CONTENT:
ENCODING: [ns_conn encoding]
[ns_conn content]
"

set data_head [list]
set data_values [list]

lappend data_head "user-id"
lappend data_values $user_id
lappend data_head "full-name"
lappend data_values [encoding convertto utf-8 [db_string get_user_name {}]]
lappend data_head "is-admin"
lappend data_values [db_0or1row check_swa {}]
lappend data_head "user-agent"
lappend data_values [ns_set iget $headers User-Agent]

lappend data_head "origin-ip"
lappend data_values [ns_conn peeraddr]
lappend data_head "referer"
lappend data_values [ns_set iget $headers Referer]
lappend data_head "courses"
lappend data_values [db_list_of_lists get_courses_list {}]
lappend data_head "request-payload"
lappend data_values [string map { "\"" "\\\""} [ns_conn content] ]


set struc_json [ws::json::construct -data_head $data_head -data_values $data_values]

ns_log notice "STRUCT JSON:
$struc_json"


if {$struc_json eq "{}"} {
    ns_log error "User has belong any groups"
    set struc_json [ws::json::error_msg -error_code "1" -message "[_ ws.No_groups]"]
}

#ns_return 200 "application/json; charset=utf-8" $struc_json


### Communication with Lab Manager
#set struc_json [ns_urlencode -charset utf8 $struc_json]
#set struc_json [ns_urldecode -charset utf8 $struc_json]
set lab_manager_url [parameter::get -parameter LabManagerURL]
set lms_user [parameter::get -parameter LmsUser]
set lms_pass [parameter::get -parameter LmsPass]
set authorization_basic [base64::encode "${lms_user}:${lms_pass}"]
set requests_url "/lms4labs/labmanager/requests/"
set timeout 30
set depth 0
set authorization_header "http://www.uned.es/ \r\nAuthorization: Basic $authorization_basic"
ns_log notice "HTTP POST:
URL: [concat ${lab_manager_url}${requests_url}]
STRUCT_JSON: $struc_json
TIMEOUT: $timeout
DEPTH: $depth
LMS User: $lms_user
LMS Pass: $lms_pass
authorizantion_basic: $authorization_basic
AUTHORIZATION HEADER: $authorization_header "

#set url [util_httppost [concat ${lab_manager_url}${requests_url}] $struc_json $timeout $depth $authorization_header ]
set url [util_httppost_lms4labs -url [concat ${lab_manager_url}${requests_url}] -formvars $struc_json -http_referer $authorization_header -content_type "application/json; charset=utf-8"]


ns_log notice "PAGE RESPONSE for LMS4LABS:
$url"

ns_return 200 "text/html; charset=utf-8" $url