<?php


    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_login();

    global $DB, $COURSE, $USER;
    
   require_once('../../config.php');
  
   require_login();
   $PAGE->set_url('/blocks/lms4labs/addlms4labs.php');
   $PAGE->set_pagelayout('standard');
   $PAGE->set_title('Connection Data for Labmanager');
   $PAGE->set_heading('LMSs for Labs');

   $adminroot = admin_get_root(); // need all settings
   

   echo $OUTPUT->header();
   
    $Laburl=$_POST['f1laburl'];
    $Labuser=$_POST['f1labuser'];
    $Labpass=$_POST['f1labpass'];
    $Lmsuser=$_POST['f1lmsuser'];
    $Lmspass=$_POST['f1lmspass'];
    

    $cond = array("name" => "Labmanager-Url");
    $record = new stdClass();
    $record->name = "Labmanager-Url";
    $record->value = $Laburl;

     if ($DB->record_exists("lms4labs", $cond)) {
      $aux=$DB->get_record('lms4labs',$cond);
	$record->id=$aux->id;
	$DB->update_record('lms4labs', $record, false);
     }
     else {

      $DB->insert_record('lms4labs', $record, false);

     }
   
    $cond = array("name" => "Labmanager-User");
    $record1 = new stdClass();
    $record1->name = "Labmanager-User";
    $record1->value = $Labuser;
     
     if ($DB->record_exists("lms4labs", $cond)) {
	$aux=$DB->get_record('lms4labs',$cond);
	$record1->id=$aux->id;
      $DB->update_record('lms4labs', $record1, false);
     }
     else {
	$DB->insert_record('lms4labs', $record1, false);
     }



    $cond = array("name" => "Labmanager-Password");  
    $record2 = new stdClass();
    $record2->name = "Labmanager-Password";
    $record2->value = $Labpass;

    if ($DB->record_exists("lms4labs", $cond)) {
      $aux=$DB->get_record('lms4labs',$cond);
	$record2->id=$aux->id;
      $DB->update_record('lms4labs', $record2, false);
    }
    else {
      $DB->insert_record('lms4labs', $record2, false);
     }



    $cond = array("name" => "LMS-User");
    
    $record3 = new stdClass();
    $record3->name = "LMS-User";
    $record3->value =  $Lmsuser;
    if ($DB->record_exists("lms4labs", $cond)) {
      $aux=$DB->get_record('lms4labs',$cond);
	$record3->id=$aux->id;
	$DB->update_record('lms4labs', $record3, false);
    }
    else {
      $DB->insert_record('lms4labs', $record3, false);
     }



    $cond = array("name" => "LMS-Password");
    $record4 = new stdClass();
    $record4->name = "LMS-Password";
    $record4->value = $Lmspass;

    if ($DB->record_exists("lms4labs", $cond)) {
     	$aux=$DB->get_record('lms4labs',$cond);
	$record4->id=$aux->id; 
     $DB->update_record('lms4labs', $record4, false);
    }
    else {
      $DB->insert_record('lms4labs', $record4, false);
     }




	
    echo $OUTPUT->footer();
?>

