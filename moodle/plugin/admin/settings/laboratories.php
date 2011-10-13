<?php // laboratories.php

// This file defines settingpages and externalpages under the "mnet" category

if ($hassiteconfig) { // speedup for non-admins, add all caps used on this page


$ADMIN->add('laboratories', new admin_externalpage('addtype', get_string('addtypes', 'admin'),
                                           "$CFG->wwwroot/$CFG->admin/laboratories/addtype.php",
                                           'moodle/site:config'));

$ADMIN->add('laboratories', new admin_externalpage('addlabs', get_string('addlaboratories', 'admin'),
                                           "$CFG->wwwroot/$CFG->admin/laboratories/add.php",
                                           'moodle/site:config'));

$ADMIN->add('laboratories', new admin_externalpage('addexp', get_string('addexperiment', 'admin'),
                                           "$CFG->wwwroot/$CFG->admin/laboratories/addexp.php",
                                           'moodle/site:config'));

$ADMIN->add('laboratories', new admin_externalpage('expcour', get_string('expcourse', 'admin'),
                                           "$CFG->wwwroot/$CFG->admin/laboratories/addcoexp.php",
                                           'moodle/site:config'));


$ADMIN->add('laboratories', new admin_externalpage('editlabs', get_string('editlaboratories', 'admin'),
                                           "$CFG->wwwroot/$CFG->admin/laboratories/edit.php",
                                           'moodle/site:config'));

$ADMIN->add('laboratories', new admin_externalpage('deletelabs', get_string('deletelaboratories', 'admin'),
                                           "$CFG->wwwroot/$CFG->admin/laboratories/delete.php",
                                           'moodle/site:config'));


} // end of speedup

?>