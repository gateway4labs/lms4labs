<?php 
/**
 * This page lists all the instances of wlab in a particular course
 *
 * @author Elio San Cristobal
 * @version $Id: index.php,v 1 2009/08/28 
 * @package wlab
 **/


    require_once("../../config.php");
    require_once("lib.php");

    $id = required_param('id', PARAM_INT);   // course

    if (! $course = get_record("course", "id", $id)) {
        error("Course ID is incorrect");
    }

    require_login($course->id);

    add_to_log($course->id, "wlab", "view all", "index.php?id=$course->id", "");


/// Get all required stringswlab

    $strwlabs = get_string("modulenameplural", "wlab");
    $strwlab  = get_string("modulename", "wlab");


/// Print the header

    if ($course->category) {
        $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
    } else {
        $navigation = '';
    }

    print_header("$course->shortname: $strwlabs", "$course->fullname", "$navigation $strwlabs", "", "", true, "", navmenu($course));

/// Get all the appropriate data

    if (! $wlabs = get_all_instances_in_course("wlab", $course)) {
        notice("There are no wlabs", "../../course/view.php?id=$course->id");
        die;
    }

/// Print the list of instances (your module will probably extend this)

    $timenow = time();
    $strname  = get_string("name");
    $strweek  = get_string("week");
    $strtopic  = get_string("topic");

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname);
        $table->align = array ("center", "left");
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname);
        $table->align = array ("center", "left", "left", "left");
    } else {
        $table->head  = array ($strname);
        $table->align = array ("left", "left", "left");
    }

    foreach ($wlabs as $wlab) {
        if (!$wlab->visible) {
            //Show dimmed if the mod is hidden
            $link = "<a class=\"dimmed\" href=\"view.php?id=$wlab->coursemodule\">$wlab->name</a>";
        } else {
            //Show normal if the mod is visible
            $link = "<a href=\"view.php?id=$wlab->coursemodule\">$wlab->name</a>";
        }

        if ($course->format == "weeks" or $course->format == "topics") {
            $table->data[] = array ($wlab->section, $link);
        } else {
            $table->data[] = array ($link);
        }
    }

    echo "<br />";

    print_table($table);

/// Finish the page

    print_footer($course);

?>
