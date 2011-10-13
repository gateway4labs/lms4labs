<?PHP 
   

 // Allows the admin to add laboratories

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');
    
    require_login();
    admin_externalpage_setup('addlabs');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");

    global $DB;

/// If data submitted, process and store in database
    if (($form = data_submitted())) {
	
	$msg= new stdClass();
   
      $msg->name       = $form->name;
      $msg->description = $form->Description;
      $msg->accesstype       = $form->accesstype;
         
		
     // Commit to DB
     
     	 
	 //DB->insert_record('wlab_list',$msg);
       
	
	   
}



   admin_externalpage_print_header();


?>

<script language="javascript">
 
 function visualizar()
 {	if (document.getElementById ("accesstype").value=="-1") {
	   document.getElementById ("Deusto").style.visibility="hidden";
	   document.getElementById ("iLab").style.visibility="hidden";
	}
	else
	{ if (document.getElementById ("accesstype").value=="1") {
	   document.getElementById ("Deusto").style.visibility="Visible";
	   document.getElementById ("iLab").style.visibility="hidden";
	    }
 	  else {
	   document.getElementById ("Deusto").style.visibility="hidden";
	   document.getElementById ("iLab").style.visibility="Visible";
	    }
       }

   }
</script>

<h1><center>ADD LABORATORY</center></h1>

<form name="form" method="post" action="add.php">
<center>
<table cellpadding="10">
<tr valign="top">
    <td align="left"><b>Name:</b> </td>
	<td align="left">
       <input type="text" name="name" size="70" value="<?php  p($form->name) ?>" />
      </td>
    </tr>


<tr valign="top">
    <td align="left"><b>Description:</b></td>
    <td align="left">
       <textarea name="Description" rows="6" cols="40" value="<?php  p($form->Description) ?>" />
</textarea>
    </td>
</tr>


<tr valign="top">
    <td align="left"><b>access:</b></td>
    	<td align="left">
	<select name="accesstype" id="accesstype" value="<?php  p($form->accesstype) ?>" onChange="javascript:visualizar();">
	 <option value="-1" SELECTED> Select an option </option> 
	 <?php
            $accesses=$DB->get_records('wlab_access');
             foreach ($accesses as $access) 
		  { 
		    ?>
             <option value="<?php  p($access->id) ?>"> <?php p($access->name) ?>  <?php p($access->version) ?>  </option>";
	 <?php } ?>	  		
	   
	</select>
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