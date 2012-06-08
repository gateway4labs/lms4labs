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
   
    $Laburl="";
    $Labuser="";
    $Labpass="";
    $Lmsuser="";
    $Lmspass="";
    

    $cond = array("name" => "Labmanager-Url");
     if ($DB->record_exists("lms4labs", $cond)) {
	$Laburl=$DB->get_record("lms4labs", $cond);}
   
    $cond = array("name" => "Labmanager-User");
     if ($DB->record_exists("lms4labs", $cond)) {
	$Labuser=$DB->get_record("lms4labs", $cond);}

    $cond = array("name" => "Labmanager-Password");
    if ($DB->record_exists("lms4labs", $cond)) {
      $Labpass=$DB->get_record("lms4labs", $cond);}

    $cond = array("name" => "LMS-User");
    if ($DB->record_exists("lms4labs", $cond)) {
      $Lmsuser=$DB->get_record("lms4labs", $cond);}

    $cond = array("name" => "LMS_Password");
    if ($DB->record_exists("lms4labs", $cond)) {
      $Lmspass=$DB->get_record("lms4labs", $cond);}
	

?>


<h1><center>Connection Data</center></h1>


<form name="form" method="post" action="add.php" id="mform1" class="mform">
<fieldset class="clearfix"  id="moodle">
<legend class="ftoggler">lms4labs configuration</legend>
	<div class="fcontainer clearfix">
      
	<div class="fitem"><div class="fitemtitle"><label for="labmanager_url">Labmanager URL</label></div><div class="felement ftext"><input id="labmanager_url" type="text" name="f1laburl" size="50"  maxlength="255" value="<?php  p($Laburl) ?>" /></div></div>
	<div class="fitem"><div class="fitemtitle"><label for="labmanager_user">Labmanager username</label></div><div class="felement ftext"><input  id="labmanager_user" type="text" name="f1labuser" size="50"  maxlength="255" value="<?php  p($Labuser) ?>" /></div></div>
	<div class="fitem"><div class="fitemtitle"><label for="labmanager_password">Labmanager password:</label></div><div class="felement ftext"><input  id="labmanager_password" type="password" name="f1labpass" size="50"  maxlength="255" value="<?php  p($Labpass) ?>" /></div></div>
	<div class="fitem"><div class="fitemtitle"><label for="lms_user">LMS user</label></div><div class="felement ftext"><input  id="lms_user" type="text" name="f1lmsuser" size="50"  maxlength="255" value="<?php  p($Lmsuser) ?>" /></div></div>
	<div class="fitem"><div class="fitemtitle"><label for="lms_password">LMS password</label></div><div class="felement ftext"><input  id="lms_password" type="password" name="f1lmspass" size="50"  maxlength="20" value="<?php  p($Lmspass) ?>" /></div></div>
 

	<div class="fitem"><div class="fitemtitle"><label for="id_submitbutton"> </label></div><div class="felement fsubmit"><input name="submitbutton" type="submit" value="Add Database"  id="id_submitbutton" /></div></div>
	</div>
</fieldset>
</form>


<?php	
    echo $OUTPUT->footer();
?>

