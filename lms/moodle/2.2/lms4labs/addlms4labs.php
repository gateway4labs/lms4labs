<?php

    require_once('../../config.php');
    require_once($CFG->libdir . '/adminlib.php');
    require_once($CFG->libdir . '/formslib.php');
    require_login();

    global $DB, $COURSE, $USER;
  
    require_once('util.php');
    $PAGE->set_url('/blocks/lms4labs/addlms4labs.php');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Connection Data for Labmanager');
    $PAGE->set_heading('LMSs for Labs');

    $adminroot = admin_get_root(); // need all settings
   
    echo $OUTPUT->header();

    class labmanager_form extends moodleform {
     
        function definition() {
            $mform =& $this->_form; 

            $mform->addElement('header',   'lms4labs configuration', 'lms4labs configuration');

            $mform->addElement('text',     'laburl',      'LabManager URL',      'maxlength="255" size="50" ');
            $mform->addElement('static',   'description', '',                    'E.g. http://localhost:5000/lms4labs (no trailing /) ');
            $mform->addElement('text',     'labuser',     'LabManager user',     'maxlength="255" size="50" ');
            $mform->addElement('password', 'labpass',     'LabManager password', 'maxlength="255" size="50" ');
            $mform->addElement('text',     'lmsuser',     'LMS user',            'maxlength="255" size="50" ');
            $mform->addElement('password', 'lmspass',     'LMS password',        'maxlength="255" size="50" ');

            $mform->closeHeaderBefore('buttonar');

            $this->add_action_buttons();

            foreach(array('laburl', 'labuser', 'lmsuser') as $field) {
                $mform->setDefault($field, $this->_customdata[$field]);
            }
        }
    }

    $mform = new labmanager_form();

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
    $initial_data = array('laburl' => $laburl, 'labuser' => $labuser, 'lmsuser' => $lmsuser);

    if ($mform->is_cancelled()) {
        $mform->set_data($initial_data);
        $mform->display();

    } else if ( $fromform = $mform->get_data()) {
        $laburl = $fromform->laburl;
        function lms_store_field($field, $value) {
            global $DB;
            $cond = array("name" => $field);
            $record = new stdClass();
            $record->name  = $field;
            $record->value = $value;

            if ($DB->record_exists("lms4labs", $cond)) {
                $cur = $DB->get_record('lms4labs',$cond);
                $record->id = $cur->id;
                $DB->update_record('lms4labs', $record, false);
            } else {
                $DB->insert_record('lms4labs', $record, false);
            }
        }

        lms_store_field("Labmanager-Url",  $fromform->laburl);
        lms_store_field("Labmanager-User", $fromform->labuser);
        lms_store_field("LMS-User", $fromform->lmsuser);

        if($fromform->labpass != "")
            lms_store_field("Labmanager-Password", md5($fromform->labpass));

        if($fromform->lmspass != "")
            lms_store_field("LMS-Password", $fromform->lmspass);

        $mform->display();
    } else {
        $mform->set_data($initial_data);
        $mform->display();
    } 

    if($laburl != "") {
        
        // Testing
        $request_data = array( "full-name"  => "Testing server" ); 
        $request= json_encode($request_data);
        $response = l4l_authenticate($request);	

        if (substr($response, 0, 4) == 'http') {
            echo "Configuration successfully validated!";
        } else {
            echo "Configuration is failing. If you have not configured the LabManager, this might be fine, just come back to this page to ensure yourself.";
        }
    }

    echo $validation;
    echo $OUTPUT->footer(); 
?>

