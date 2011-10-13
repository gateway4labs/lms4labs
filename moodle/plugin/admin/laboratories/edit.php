<?PHP // 

    // Allows the admin to edit laboratories

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');

    require_login();
    admin_externalpage_setup('editlabs');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");


    admin_externalpage_print_header();
?>

<center>

Modify laboratory

</center>

<?php
admin_externalpage_print_footer();
?>