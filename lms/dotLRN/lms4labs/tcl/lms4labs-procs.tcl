#
#  Copyright (C) 2012 DIEEC - Department of Engineering, Electric and Control (UNED)
#
#  This file is part of dotLRN.
#
#  dotLRN is free software; you can redistribute it and/or modify it under the
#  terms of the GNU General Public License as published by the Free Software
#  Foundation; either version 2 of the License, or (at your option) any later
#  version.
#
#  dotLRN is distributed in the hope that it will be useful, but WITHOUT ANY
#  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
#  FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
#  details.
#

ad_library {

	Basic procs for Integration of LMSs with Labs

	@author Elio Sancristobal Ruiz (elio@ieec.uned.es)
	@author Pablo Orduña (pablo.orduna@deusto.es)
	@author Alberto Pesquera Martin (apm@innova.uned.es)
	@creation-date 2012-05-23
	@cvs-id $Id$

}

namespace eval lms4labs {}

ad_proc -public util_httpopen_lms4labs {
    -method:required
    -url:required
    {-rqset ""} 
    {-timeout 30} 
    {-http_referer ""}
} { 

    Like ns_httpopen but works for POST as well; called by util_httppost

    @author Tracy Adams (teadams@arsdigita.com)

} { 
# system by Tracy Adams (teadams@arsdigita.com) to permit AOLserver to POST 
# to another Web server; sort of like ns_httpget

    if { ![string match "http://*" $url] } {
        return -code error "Invalid url \"$url\":  _httpopen only supports HTTP"
    }
    set url [split $url /]
    set hp [split [lindex $url 2] :]
    set host [lindex $hp 0]
    set port [lindex $hp 1]
    if { [string match $port ""] } {set port 80}
    set uri /[join [lrange $url 3 end] /]
    set fds [ns_sockopen -nonblock $host $port]
    set rfd [lindex $fds 0]
    set wfd [lindex $fds 1]
    if { [catch {
        _ns_http_puts $timeout $wfd "$method $uri HTTP/1.0\r"
        _ns_http_puts $timeout $wfd "Host: $host:$port\r"
        if {$rqset ne ""} {
            for {set i 0} {$i < [ns_set size $rqset]} {incr i} {
                _ns_http_puts $timeout $wfd \
                    "[ns_set key $rqset $i]: [ns_set value $rqset $i]\r"
            }
        } else {
            _ns_http_puts $timeout $wfd \
                "Accept: */*\r"

            _ns_http_puts $timeout $wfd "User-Agent: Mozilla/1.01 \[en\] (Win95; I)\r"    
            _ns_http_puts $timeout $wfd "Referer: $http_referer \r"    
	}

    } errMsg] } {
        global errorInfo
        #close $wfd
        #close $rfd
        if { [info exists rpset] } {ns_set free $rpset}
        return -1
    }
    return [list $rfd $wfd ""]
    
}


ad_proc -public util_httppost_lms4labs {
    -url:required
    -formvars:required
    {-timeout 30}
    {-depth 0}
    {-http_referer ""}
    {-content_type "application/x-www-form-urlencoded; charset=[ns_config ns/parameters OutputCharset utf-8]\r"}
} {
    Returns the result of POSTing to another Web server or -1 if there is an error or timeout.  
    formvars should be in the form \"arg1=value1&arg2=value2\".  
    <p> 
    post is encoded as application/x-www-form-urlencoded.  See util_http_file_upload
    for file uploads via post (encoded multipart/form-data).
    <p> 
    @see util_http_file_upload
} {
    if { [catch {
	if {[incr depth] > 10} {
		return -code error "util_httppost:  Recursive redirection:  $url"
	}
#	set http [util_httpopen POST $url "" $timeout $http_referer]
	set http [util_httpopen_lms4labs -method POST -url $url -timeout $timeout -http_referer $http_referer]
	set rfd [lindex $http 0]
	set wfd [lindex $http 1]

	#headers necesary for a post and the form variables

	_ns_http_puts $timeout $wfd "Content-type: $content_type"
	_ns_http_puts $timeout $wfd "Content-length: [string length $formvars]\r"
	_ns_http_puts $timeout $wfd \r
	_ns_http_puts $timeout $wfd "$formvars\r"
	flush $wfd
	close $wfd

	set rpset [ns_set new [_ns_http_gets $timeout $rfd]]
		while 1 {
			set line [_ns_http_gets $timeout $rfd]
			if { ![string length $line] } break
			ns_parseheader $rpset $line
		}

	set headers $rpset
	set response [ns_set name $headers]
	set status [lindex $response 1]
	if {$status == 302} {
		set location [ns_set iget $headers location]
		if {$location ne ""} {
		    ns_set free $headers
		    close $rfd
		    return [util_httpget $location {}  $timeout $depth]
		}
	}
	set length [ns_set iget $headers content-length]
	if { "" eq $length } {set length -1}
      	set type [ns_set iget $headers content-type]
      	set_encoding $type $rfd
	set err [catch {
		while 1 {
			set buf [_ns_http_read $timeout $rfd $length]
			append page $buf
			if { "" eq $buf } break
			if {$length > 0} {
				incr length -[string length $buf]
				if {$length <= 0} break
			}
		}
	} errMsg]
	ns_set free $headers
	close $rfd
	if {$err} {
		global errorInfo
		return -code error -errorinfo $errorInfo $errMsg
	}
    } errmgs ] } {return -1}
	return $page
}
