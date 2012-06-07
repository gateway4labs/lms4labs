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

    Returns a structure JSON with courses information of one user to Lab Manager

    @author Alberto Pesquera Martin (apm@innova.uned.es)
    @author Elio San Cristobal Ruiz (elio@ieec.uned.es)
    @author Pablo Orduña (pablo.orduna@deusto.es)
    @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
    @package lms4labs
    @creation-date 24 May 2012

} {
    {page:integer "0"}
    {q ""}
}

ns_log notice "PARAMETROS [ns_conn query]
SPLIT: [split [ns_conn query] &]
LSEARCH [lsearch [split [ns_conn query] &] \"q\"]
LINDEX: [lindex [split [ns_conn query] &] 0]
q: $q
page: $page
"
#Trick for get url parameters
set params_list [split [ns_conn query] &]
foreach param $params_list {
    set aux [split $param =]
    if {[lindex $aux 0] eq "q"} {
	set q [lindex $aux 1]
    }
}

set course_name_search "%$q%"

ns_log notice "PARAMETROS
q: $q
course_name_search: $course_name_search
page: $page
"

set LabManager_user [ns_conn authuser]
set LabManager_pass [ns_conn authpassword]
ns_log notice "HEADERS:
LabManager_user: $LabManager_user
LabManager_pass: $LabManager_pass
"

if {$LabManager_user eq "" || $LabManager_pass eq ""} {
#   WWW-Authenticate: Basic realm="Secure Area"
#   ns_return 401 "text/html" "You must provide a username and a password"
#    ns_log error "Return a 401 (Unauthorized) status message to the client"
    ns_returnunauthorized
    return
}

# Check Authorization
if {[parameter::get -parameter LabManagerUser] ne $LabManager_user || [parameter::get -parameter LabManagerPass] ne $LabManager_pass} {
    ns_log error "Return a 403 (Forbidden) status message to the client"
    ns_returnforbidden
    return
}

# Cambiar por comprobación autenticación HTTP Authorization Basic
#set user_id [ws::json::get_user_id]
set num_results 10
set start_num [expr $page * $num_results]
set end_num [expr $start_num + $num_results]

if {[exists_and_not_null q]} {
    set search_clause [db_map with_search_clause]
} else {
    set search_clause [db_map without_search_clause]
}

set courses_list [db_list_of_lists get_courses_list {}]
set total_results [db_string get_total_results {}]

#set struct_json [ws::json::construct -data_list_of_lists $courses_list]
set array_json [db_json get_courses_list {}]
if {$array_json eq "{}"} {
    set array_json "\[ \]"
} else {
    set array_json "\[ $array_json \]"
}

set data_list_plain [list start $start_num number $total_results "per-page" $num_results courses $array_json]
set struct_json [ws::json::construct -data_list_plain $data_list_plain]

if {$struct_json eq "{}"} {
    ns_log error "User has belong any groups"
    set struct_json [ws::json::error_msg -error_code "1" -message "[_ ws.No_groups]"]
}

ns_log notice "JSON:
$struct_json"
ns_return 200 "application/json; charset=utf-8" $struct_json
