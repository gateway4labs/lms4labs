<?PHP // 

    // Allows the admin to delete laboratories 

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');

    require_login();
    admin_externalpage_setup('deletelabs');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");
    
  
      $id= required_param('id', PARAM_INT);   // id of laboratory
    
    global $DB;
    $cond = array("id" =>$id);
    $laboratory=$DB->get_record("wlab_list", $cond);

/// If data submitted, process and store in database
    if (($form = data_submitted())) {
	
	$msg= new stdClass();
   
      $msg->idlab = $id;
      $msg->username = $form->username;
      $msg->pass = $form->pass;
      $msg->url = $form->url;
         
		
     // Commit to DB
      if ($form->pass == $form->pass1) {

		$DB->insert_record('wlab_connection_deusto',$msg);
      
	 }else{echo "error";}
	   
}

    admin_externalpage_print_header();
?>

<h1><center>ADD DATA OF CONNECTION</center></h1>

<form name="form" method="post" action="connectiondeusto.php?id=<?php  p($id)?>">
<center>
<table cellpadding="10">

<tr valign="top">
    	<td align="left"><b>Name of Laboratory:</b> </td>
	<td align="left">
       <input type="text" name="idlab" size="90" disabled value="<?php  p($laboratory->name) ?>" />
      </td>
 </tr>



<tr valign="top">
    <td align="left"><b>Username:</b> </td>
	<td align="left">
       <input type="text" name="username" size="90" value="<?php  p($form->username) ?>" />
      </td>
</tr>

<tr valign="top">
    <td align="left"><b>Password:</b> </td>
	<td align="left">
       <input type="password" name="pass" size="90" value="<?php  p($form->pass) ?>" />
      </td>
</tr>

<tr valign="top">
    <td align="left"><b>Password:</b> </td>
	<td align="left">
       <input type="password" name="pass1" size="90" value="<?php  p($form->pass1) ?>" />
      </td>
</tr>


<tr valign="top">
    <td align="left"><b>url:</b> </td>
	<td align="left">
       <input type="text" name="url" size="90" value="<?php  p($form->url) ?>" />
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
<input type="submit" value="add connection data" />
</center>

</form>

<?php
admin_externalpage_print_footer();
?>