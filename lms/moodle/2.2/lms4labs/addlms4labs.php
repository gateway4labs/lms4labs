<?php

    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_login();

    global $DB, $COURSE, $USER;
  
    require_once('util.php');
    $PAGE->set_url('/blocks/lms4labs/addlms4labs.php');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Connection Data for Labmanager');
    $PAGE->set_heading('LMSs for Labs');

    $adminroot = admin_get_root(); // need all settings
   
    echo $OUTPUT->header();

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        function lms_store_field($field, $value) {
            global $DB;
            $cond = array("name" => $field);
            $record = new stdClass();
            $record->name  = $field;
            $record->value = $value;

            if ($DB->record_exists("lms4labs", $cond)) {
                $aux=$DB->get_record('lms4labs',$cond);
            $record->id=$aux->id;
            $DB->update_record('lms4labs', $record, false);
            } else {
                $DB->insert_record('lms4labs', $record, false);
            }
        }


        $laburl=$_POST['f1laburl'];
        $labuser=$_POST['f1labuser'];
        $labpass=$_POST['f1labpass'];
        $lmsuser=$_POST['f1lmsuser'];
        $lmspass=$_POST['f1lmspass'];

        lms_store_field("Labmanager-Url", $laburl);
        lms_store_field("Labmanager-User", $labuser);
        if($labpass != "")
            lms_store_field("Labmanager-Password", $labpass);
        lms_store_field("LMS-User", $lmsuser);
        if($lmspass != "")
            lms_store_field("LMS-Password", $lmspass);
    }

    function lms_retrieve_field($field) {
        global $DB;
        $cond = array("name" => $field);
        if ($DB->record_exists("lms4labs", $cond)) {
            return $DB->get_record("lms4labs", $cond)->value;
        } else { 
            return "";
        }
    }

    $laburl  = lms_retrieve_field("Labmanager-Url");
    $labuser = lms_retrieve_field("Labmanager-User");
    $lmsuser = lms_retrieve_field("LMS-User"); 

    $validation = "";

    if($laburl != "") {
        
        // Testing
        $request_data = array( "full-name"  => "Testing server" ); 
        $request= json_encode($request_data);
        $response = l4l_authenticate($request);	

        if (substr($response, 0, 4) == 'http') {
            $validation = "Configuration successfully validated!";
        } else {
            $validation = "Configuration is failing. If you have not configured the LabManager, this might be fine, just come back to this page to ensure yourself.";
        }
    }
?>

<h1><center>Connection Data</center></h1>


<form name="form" method="post" action="addlms4labs.php" id="mform1" class="mform">
<fieldset class="clearfix"  id="moodle">
<legend class="ftoggler">lms4labs configuration</legend>
    <div class="fcontainer clearfix">
      
    <div class="fitem"><div class="fitemtitle"><label for="labmanager_url">Labmanager URL</label></div><div class="felement ftext"><input id="labmanager_url" type="text" name="f1laburl" size="50"  maxlength="255" value="<?php  p($laburl) ?>" /></div></div>
    <div class="fitem"><div class="fitemtitle"><label for="labmanager_user">Labmanager username</label></div><div class="felement ftext"><input  id="labmanager_user" type="text" name="f1labuser" size="50"  maxlength="255" value="<?php  p($labuser) ?>" /></div></div>
    <div class="fitem"><div class="fitemtitle"><label for="labmanager_password">Labmanager password:</label></div><div class="felement ftext"><input  id="labmanager_password" type="password" name="f1labpass" size="50"  maxlength="255" /></div></div>
    <div class="fitem"><div class="fitemtitle"><label for="lms_user">LMS user</label></div><div class="felement ftext"><input  id="lms_user" type="text" name="f1lmsuser" size="50"  maxlength="255" value="<?php  p($lmsuser) ?>" /></div></div>
    <div class="fitem"><div class="fitemtitle"><label for="lms_password">LMS password</label></div><div class="felement ftext"><input  id="lms_password" type="password" name="f1lmspass" size="50"  maxlength="20" /></div></div>
 

    <div class="fitem"><div class="fitemtitle"><label for="id_submitbutton"> </label></div><div class="felement fsubmit"><input name="action" type="submit" value="Add"  id="id_submitbutton" /></div></div>
    </div>
</fieldset>
</form>
    <?php p($validation) ?>

<?php    
    echo $OUTPUT->footer();
?>

