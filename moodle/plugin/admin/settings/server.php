<?php

// This file defines settingpages and externalpages under the "server" category

if ($hassiteconfig) { // speedup for non-admins, add all caps used on this page


// "systempaths" settingpage
$temp = new admin_settingpage('systempaths', get_string('systempaths','admin'));
$temp->add(new admin_setting_configselect('gdversion', get_string('gdversion','admin'), get_string('configgdversion', 'admin'), check_gd_version(), array('0' => get_string('gdnot'),
                                                                                                                                                          '1' => get_string('gd1'),
                                                                                                                                                          '2' => get_string('gd2'))));
$temp->add(new admin_setting_configexecutable('pathtodu', get_string('pathtodu', 'admin'), get_string('configpathtodu', 'admin'), ''));
$temp->add(new admin_setting_configexecutable('aspellpath', get_string('aspellpath', 'admin'), get_string('edhelpaspellpath'), ''));
$temp->add(new admin_setting_configexecutable('pathtodot', get_string('pathtodot', 'admin'), get_string('pathtodot_help', 'admin'), ''));
$ADMIN->add('server', $temp);



// "supportcontact" settingpage
$temp = new admin_settingpage('supportcontact', get_string('supportcontact','admin'));
if (isloggedin()) {
    global $USER;
    $primaryadminemail = $USER->email;
    $primaryadminname  = fullname($USER, true);

} else {
    // no defaults during installation - admin user must be created first
    $primaryadminemail = NULL;
    $primaryadminname  = NULL;
}
$temp->add(new admin_setting_configtext('supportname', get_string('supportname', 'admin'), get_string('configsupportname', 'admin'), $primaryadminname, PARAM_NOTAGS));
$temp->add(new admin_setting_configtext('supportemail', get_string('supportemail', 'admin'), get_string('configsupportemail', 'admin'), $primaryadminemail, PARAM_NOTAGS));
$temp->add(new admin_setting_configtext('supportpage', get_string('supportpage', 'admin'), get_string('configsupportpage', 'admin'), '', PARAM_URL));
$ADMIN->add('server', $temp);


// "sessionhandling" settingpage
$temp = new admin_settingpage('sessionhandling', get_string('sessionhandling', 'admin'));
$temp->add(new admin_setting_configcheckbox('dbsessions', get_string('dbsessions', 'admin'), get_string('configdbsessions', 'admin'), 1));
$temp->add(new admin_setting_configselect('sessiontimeout', get_string('sessiontimeout', 'admin'), get_string('configsessiontimeout', 'admin'), 7200, array(14400 => get_string('numhours', '', 4),
                                                                                                                                                      10800 => get_string('numhours', '', 3),
                                                                                                                                                      7200 => get_string('numhours', '', 2),
                                                                                                                                                      5400 => get_string('numhours', '', '1.5'),
                                                                                                                                                      3600 => get_string('numminutes', '', 60),
                                                                                                                                                      2700 => get_string('numminutes', '', 45),
                                                                                                                                                      1800 => get_string('numminutes', '', 30),
                                                                                                                                                      900 => get_string('numminutes', '', 15),
                                                                                                                                                      300 => get_string('numminutes', '', 5))));
$temp->add(new admin_setting_configtext('sessioncookie', get_string('sessioncookie', 'admin'), get_string('configsessioncookie', 'admin'), '', PARAM_ALPHANUM));
$temp->add(new admin_setting_configtext('sessioncookiepath', get_string('sessioncookiepath', 'admin'), get_string('configsessioncookiepath', 'admin'), '/', PARAM_LOCALURL));
$temp->add(new admin_setting_configtext('sessioncookiedomain', get_string('sessioncookiedomain', 'admin'), get_string('configsessioncookiedomain', 'admin'), '', PARAM_TEXT, 50));
$ADMIN->add('server', $temp);


// "stats" settingpage
$temp = new admin_settingpage('stats', get_string('stats'), 'moodle/site:config', empty($CFG->enablestats));
$temp->add(new admin_setting_configselect('statsfirstrun', get_string('statsfirstrun', 'admin'), get_string('configstatsfirstrun', 'admin'), 'none', array('none' => get_string('none'),
                                                                                                                                                           60*60*24*7 => get_string('numweeks','moodle',1),
                                                                                                                                                           60*60*24*14 => get_string('numweeks','moodle',2),
                                                                                                                                                           60*60*24*21 => get_string('numweeks','moodle',3),
                                                                                                                                                           60*60*24*28 => get_string('nummonths','moodle',1),
                                                                                                                                                           60*60*24*56 => get_string('nummonths','moodle',2),
                                                                                                                                                           60*60*24*84 => get_string('nummonths','moodle',3),
                                                                                                                                                           60*60*24*112 => get_string('nummonths','moodle',4),
                                                                                                                                                           60*60*24*140 => get_string('nummonths','moodle',5),
                                                                                                                                                           60*60*24*168 => get_string('nummonths','moodle',6),
                                                                                                                                                           'all' => get_string('all') )));
$temp->add(new admin_setting_configselect('statsmaxruntime', get_string('statsmaxruntime', 'admin'), get_string('configstatsmaxruntime3', 'admin'), 0, array(0 => get_string('untilcomplete'),
                                                                                                                                                            60*30 => '10 '.get_string('minutes'),
                                                                                                                                                            60*30 => '30 '.get_string('minutes'),
                                                                                                                                                            60*60 => '1 '.get_string('hour'),
                                                                                                                                                            60*60*2 => '2 '.get_string('hours'),
                                                                                                                                                            60*60*3 => '3 '.get_string('hours'),
                                                                                                                                                            60*60*4 => '4 '.get_string('hours'),
                                                                                                                                                            60*60*5 => '5 '.get_string('hours'),
                                                                                                                                                            60*60*6 => '6 '.get_string('hours'),
                                                                                                                                                            60*60*7 => '7 '.get_string('hours'),
                                                                                                                                                            60*60*8 => '8 '.get_string('hours') )));
$temp->add(new admin_setting_configtext('statsruntimedays', get_string('statsruntimedays', 'admin'), get_string('configstatsruntimedays', 'admin'), 31, PARAM_INT));
$temp->add(new admin_setting_configtime('statsruntimestarthour', 'statsruntimestartminute', get_string('statsruntimestart', 'admin'), get_string('configstatsruntimestart', 'admin'), array('h' => 0, 'm' => 0)));
$temp->add(new admin_setting_configtext('statsuserthreshold', get_string('statsuserthreshold', 'admin'), get_string('configstatsuserthreshold', 'admin'), 0, PARAM_INT));
$ADMIN->add('server', $temp);


// "http" settingpage
$temp = new admin_settingpage('http', get_string('http', 'admin'));
$temp->add(new admin_setting_configtext('framename', get_string('framename', 'admin'), get_string('configframename', 'admin'), '_top', PARAM_ALPHAEXT));
$temp->add(new admin_setting_configcheckbox('slasharguments', get_string('slasharguments', 'admin'), get_string('configslasharguments', 'admin'), 1));
$temp->add(new admin_setting_heading('reverseproxy', get_string('reverseproxy', 'admin'), '', ''));
$options = array(
    0 => 'HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR, REMOTE_ADDR',
    GETREMOTEADDR_SKIP_HTTP_CLIENT_IP => 'HTTP_X_FORWARDED_FOR, REMOTE_ADDR',
    GETREMOTEADDR_SKIP_HTTP_X_FORWARDED_FOR => 'HTTP_CLIENT, REMOTE_ADDR',
    GETREMOTEADDR_SKIP_HTTP_X_FORWARDED_FOR|GETREMOTEADDR_SKIP_HTTP_CLIENT_IP => 'REMOTE_ADDR');
$temp->add(new admin_setting_configselect('getremoteaddrconf', get_string('getremoteaddrconf', 'admin'), get_string('configgetremoteaddrconf', 'admin'), 0, $options));
$temp->add(new admin_setting_heading('webproxy', get_string('webproxy', 'admin'), get_string('webproxyinfo', 'admin')));
$temp->add(new admin_setting_configtext('proxyhost', get_string('proxyhost', 'admin'), get_string('configproxyhost', 'admin'), '', PARAM_HOST));
$temp->add(new admin_setting_configtext('proxyport', get_string('proxyport', 'admin'), get_string('configproxyport', 'admin'), 0, PARAM_INT));
$options = array('HTTP'=>'HTTP');
if (defined('CURLPROXY_SOCKS5')) {
    $options['SOCKS5'] = 'SOCKS5';
}
$temp->add(new admin_setting_configselect('proxytype', get_string('proxytype', 'admin'), get_string('configproxytype','admin'), 'HTTP', $options));
$temp->add(new admin_setting_configtext('proxyuser', get_string('proxyuser', 'admin'), get_string('configproxyuser', 'admin'), ''));
$temp->add(new admin_setting_configpasswordunmask('proxypassword', get_string('proxypassword', 'admin'), get_string('configproxypassword', 'admin'), ''));
$temp->add(new admin_setting_configtext('proxybypass', get_string('proxybypass', 'admin'), get_string('configproxybypass', 'admin'), 'localhost, 127.0.0.1'));
$ADMIN->add('server', $temp);

$temp = new admin_settingpage('maintenancemode', get_string('sitemaintenancemode', 'admin'));
$options = array(0=>get_string('disable'), 1=>get_string('enable'));
$temp->add(new admin_setting_configselect('maintenance_enabled', get_string('sitemaintenancemode', 'admin'),
                                          get_string('helpsitemaintenance', 'admin'), 0, $options));
$temp->add(new admin_setting_confightmleditor('maintenance_message', get_string('optionalmaintenancemessage', 'admin'),
                                              '', ''));
$ADMIN->add('server', $temp);

$temp = new admin_settingpage('cleanup', get_string('cleanup', 'admin'));
$temp->add(new admin_setting_configselect('deleteunconfirmed', get_string('deleteunconfirmed', 'admin'), get_string('configdeleteunconfirmed', 'admin'), 168, array(0 => get_string('never'),
                                                                                                                                                                    168 => get_string('numdays', '', 7),
                                                                                                                                                                    144 => get_string('numdays', '', 6),
                                                                                                                                                                    120 => get_string('numdays', '', 5),
                                                                                                                                                                    96 => get_string('numdays', '', 4),
                                                                                                                                                                    72 => get_string('numdays', '', 3),
                                                                                                                                                                    48 => get_string('numdays', '', 2),
                                                                                                                                                                    24 => get_string('numdays', '', 1),
                                                                                                                                                                    12 => get_string('numhours', '', 12),
                                                                                                                                                                    6 => get_string('numhours', '', 6),
                                                                                                                                                                    1 => get_string('numhours', '', 1))));

$temp->add(new admin_setting_configselect('deleteincompleteusers', get_string('deleteincompleteusers', 'admin'), get_string('configdeleteincompleteusers', 'admin'), 0, array(0 => get_string('never'),
                                                                                                                                                                    168 => get_string('numdays', '', 7),
                                                                                                                                                                    144 => get_string('numdays', '', 6),
                                                                                                                                                                    120 => get_string('numdays', '', 5),
                                                                                                                                                                    96 => get_string('numdays', '', 4),
                                                                                                                                                                    72 => get_string('numdays', '', 3),
                                                                                                                                                                    48 => get_string('numdays', '', 2),
                                                                                                                                                                    24 => get_string('numdays', '', 1))));

$temp->add(new admin_setting_configcheckbox('logguests', get_string('logguests', 'admin'),
                                            get_string('logguests_help', 'admin'), 1));
$temp->add(new admin_setting_configselect('loglifetime', get_string('loglifetime', 'admin'), get_string('configloglifetime', 'admin'), 0, array(0 => get_string('neverdeletelogs'),
                                                                                                                                                1000 => get_string('numdays', '', 1000),
                                                                                                                                                365 => get_string('numdays', '', 365),
                                                                                                                                                180 => get_string('numdays', '', 180),
                                                                                                                                                150 => get_string('numdays', '', 150),
                                                                                                                                                120 => get_string('numdays', '', 120),
                                                                                                                                                90 => get_string('numdays', '', 90),
                                                                                                                                                60 => get_string('numdays', '', 60),
                                                                                                                                                35 => get_string('numdays', '', 35),
                                                                                                                                                10 => get_string('numdays', '', 10),
                                                                                                                                                5 => get_string('numdays', '', 5),
                                                                                                                                                2 => get_string('numdays', '', 2))));


$temp->add(new admin_setting_configcheckbox('disablegradehistory', get_string('disablegradehistory', 'grades'),
                                            get_string('disablegradehistory_help', 'grades'), 0));

$temp->add(new admin_setting_configselect('gradehistorylifetime', get_string('gradehistorylifetime', 'grades'),
                                          get_string('gradehistorylifetime_help', 'grades'), 0, array(0 => get_string('neverdeletehistory', 'grades'),
                                                                                                   1000 => get_string('numdays', '', 1000),
                                                                                                    365 => get_string('numdays', '', 365),
                                                                                                    180 => get_string('numdays', '', 180),
                                                                                                    150 => get_string('numdays', '', 150),
                                                                                                    120 => get_string('numdays', '', 120),
                                                                                                     90 => get_string('numdays', '', 90),
                                                                                                     60 => get_string('numdays', '', 60),
                                                                                                     30 => get_string('numdays', '', 30))));

$ADMIN->add('server', $temp);



$ADMIN->add('server', new admin_externalpage('environment', get_string('environment','admin'), "$CFG->wwwroot/$CFG->admin/environment.php"));
$ADMIN->add('server', new admin_externalpage('phpinfo', get_string('phpinfo'), "$CFG->wwwroot/$CFG->admin/phpinfo.php"));


// "performance" settingpage
$temp = new admin_settingpage('performance', get_string('performance', 'admin'));

$temp->add(new admin_setting_configtext('numcoursesincombo', get_string('numcoursesincombo', 'admin'), get_string('numcoursesincombo_help', 'admin'), 500));

$temp->add(new admin_setting_configselect('extramemorylimit', get_string('extramemorylimit', 'admin'),
                                          get_string('configextramemorylimit', 'admin'), '512M',
                                          // if this option is set to 0, default 128M will be used
                                          array( '64M' => '64M',
                                                 '128M' => '128M',
                                                 '256M' => '256M',
                                                 '512M' => '512M',
                                                 '1024M' => '1024M'
                                             )));
$temp->add(new admin_setting_configtext('curlcache', get_string('curlcache', 'admin'),
                                        get_string('configcurlcache', 'admin'), 120, PARAM_INT));

$temp->add(new admin_setting_configtext('curltimeoutkbitrate', get_string('curltimeoutkbitrate', 'admin'),
                                        get_string('curltimeoutkbitrate_help', 'admin'), 56, PARAM_INT));
/* //TODO: we need to fix code instead of relying on slow rcache, enable this once we have some code that is actually using it
$temp->add(new admin_setting_special_selectsetup('cachetype', get_string('cachetype', 'admin'),
                                          get_string('configcachetype', 'admin'), '',
                                          array( '' => get_string('none'),
                                                 'internal' => 'internal',
                                                 'memcached' => 'memcached',
                                                 'eaccelerator' => 'eaccelerator')));
// NOTE: $CFG->rcache is forced to bool in lib/setup.php
$temp->add(new admin_setting_special_selectsetup('rcache', get_string('rcache', 'admin'),
                                          get_string('configrcache', 'admin'), 0,
                                          array( '0' => get_string('no'),
                                                 '1' => get_string('yes'))));
$temp->add(new admin_setting_configtext('rcachettl', get_string('rcachettl', 'admin'),
                                        get_string('configrcachettl', 'admin'), 10));
$temp->add(new admin_setting_configtext('intcachemax', get_string('intcachemax', 'admin'),
                                        get_string('configintcachemax', 'admin'), 10));
$temp->add(new admin_setting_configtext('memcachedhosts', get_string('memcachedhosts', 'admin'),
                                        get_string('configmemcachedhosts', 'admin'), ''));
$temp->add(new admin_setting_configselect('memcachedpconn', get_string('memcachedpconn', 'admin'),
                                          get_string('configmemcachedpconn', 'admin'), 0,
                                          array( '0' => get_string('no'),
                                                 '1' => get_string('yes'))));
*/
$ADMIN->add('server', $temp);


$ADMIN->add('server', new admin_externalpage('adminregistration', get_string('registration','admin'), "$CFG->wwwroot/$CFG->admin/registration/index.php"));
$ADMIN->add('root', new admin_externalpage('bloglevelupgrade', get_string('bloglevelupgrade', 'admin'), $CFG->wwwroot.'/'.$CFG->admin.'/blocklevelupgrade.php', 'moodle/site:config', true));

} // end of speedup
