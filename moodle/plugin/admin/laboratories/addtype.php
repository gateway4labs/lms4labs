<?PHP // 

    // Allows the admin to edit laboratories

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');

    require_login();
    admin_externalpage_setup('editlabs');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");

    global $DB;

// grabar los datos en la base de datos


/// If data submitted, process and store in database
    if (($form = data_submitted())) {
	
	$msg= new stdClass();
   
      $msg->name       = $form->name;
      $msg->version    = $form->version;
      
	
     // lo grabo en la base de datos
     
     	 $DB->insert_record('wlab_access',$msg);
  
}



    admin_externalpage_print_header();

?>


<h1><center>ADD TYPES OF ACCESS</center></h1>

<form name="form" method="post" action="addtype.php">
<center>
<table cellpadding="10">

<tr valign="top"> 
    <td align="left"><b>Name of type of Access:</b></td>
   	
 <td align="left"> 
    <select name="name" value="<?php  p($form->name) ?>" />
 	<option selected>WebLab-Deusto</option>
 	<option>iLab</option>
   </select>
 </td>
</tr>
<br>
<br>
<tr valign="top"> 
    <td align="left"><b>Version:</b></td>
    <td align="left">  
      <input type="text" name="version" size="20" value="<?php  p($form->version) ?>" />
    </td>
</tr>

</table>
<!-- These hidden variables are always the same -->
<input type="hidden" name=course        value="<?php  p($form->course) ?>" />
<input type="hidden" name="sesskey"     value="<?php  p($form->sesskey) ?>" />
<input type="hidden" name=coursemodule  value="<?php  p($form->coursemodule) ?>" />
<input type="hidden" name=section       value="<?php  p($form->section) ?>" />
<input type="hidden" name=module        value="<?php  p($form->module) ?>" />
<input type="hidden" name=modulename    value="<?php  p($form->modulename) ?>" />
<input type="hidden" name=instance      value="<?php  p($form->instance) ?>" />
<input type="hidden" name=mode          value="<?php  p($form->mode) ?>" />
<br>

<input type="submit" value="Add lab" />
</center>

</form>

<?php
admin_externalpage_print_footer();
?>