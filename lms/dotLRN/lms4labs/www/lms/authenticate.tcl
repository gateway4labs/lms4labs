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

    This script redirects LMS administrator to the Lab Manager acting as a 
    manager of this LMS.

    Returns a structure JSON with user information to Lab Manager for authorization

    @author Alberto Pesquera Martin (apm@innova.uned.es)
    @author Elio San Cristobal Ruiz (elio@ieec.uned.es)
    @author Pablo Orduña (pablo.orduña@deusto.es)
    @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
    @package lms4labs
    @creation-date 25 May 2012

} {
}

set user_id [ws::json::get_user_id]
if {![acs_user::site_wide_admin_p -user_id $user_id]} {
    ad_return_error [_ dotlrn.Not_Allowed] [_ dotlrn.lt_You_are_not_allowed_t]
}

set data_head [list]
set data_values [list]

lappend data_head "full-name"
lappend data_values [encoding convertto utf-8 [db_string get_user_name {}]]

set struc_json [ws::json::construct -data_head $data_head -data_values $data_values]

ns_log notice "STRUCT JSON:
$struc_json"


if {$struc_json eq "{}"} {
    ns_log error "User has belong any groups"
    set struc_json [ws::json::error_msg -error_code "1" -message "[_ ws.No_groups]"]
}


#ns_return 200 "application/json; charset=utf-8" $struc_json


### Communication with Lab Manager
set lab_manager_url [parameter::get -parameter LabManagerURL]
set lms_user [parameter::get -parameter LmsUser]
set lms_pass [parameter::get -parameter LmsPass]
set authorization_basic [base64::encode "${lms_user}:${lms_pass}"]
set requests_url "/lms4labs/labmanager/lms/admin/authenticate/"
set timeout 30
set depth 0
set authorization_header "http://www.uned.es/ \r\nAuthorization: Basic $authorization_basic"
ns_log notice "HTTP POST:
URL: [concat ${lab_manager_url}${requests_url}]
STRUCT_JSON: $struc_json
TIMEOUT: $timeout
DEPTH: $depth
AUTHORIZATION HEADER: $authorization_header
LMS user: $lms_user
LMS pass: $lms_pass
authorization_basic: $authorization_basic
"
#set url [util_httppost [concat ${lab_manager_url}${requests_url}] $struc_json $timeout $depth $authorization_header ]
set url [util_httppost_lms4labs -url [concat ${lab_manager_url}${requests_url}] -formvars $struc_json -http_referer $authorization_header -content_type "application/json; charset=utf-8"]
#set url "http://10.195.6.224:5000/lms4labs/lms/admin/authenticate/c9d15ab070974f44ae19d80dcc34174b"

ns_log notice "PAGE RESPONSE for LMS4LABS:
$url"

ns_return 200 "text/html; charset=utf-8" $url
