<?php


    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_login();

    global $DB;
    
   require_once('../../config.php');
  
   require_login();
   $PAGE->set_url('/blocks/lms4labs/addlms4labs.php');
   $PAGE->set_pagelayout('standard');
   $PAGE->set_title('Add data for labmanager');
   $PAGE->set_heading('LMSs for Labs');

   $adminroot = admin_get_root(); // need all settings
   

   echo $OUTPUT->header();
    	

?>


<h1><center>ADD PAIRS</center></h1>

<form name="form" method="post" action="add.php">
<center>
<table cellpadding="10">

      Key: <input type="text" name="Key" size="100" /> <br> <br>
      Value: <input type="text" name="Value" size="100" />

 
</table>

<input type="submit" value="Add Database" />
</center>

</form>


<?php	
    echo $OUTPUT->footer();
?>

