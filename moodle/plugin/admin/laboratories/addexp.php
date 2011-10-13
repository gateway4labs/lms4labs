<?PHP  

    // Allows the admin to add experiments

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');
        
    require_login();
    admin_externalpage_setup('addexp');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");

    global $DB;

/// If data submitted, process and store in database
    if (($form = data_submitted())) {
	    
		$conditions = array("id" => $form->laboratory);
		$record=$DB->get_record('wlab_list', $conditions);
		$codconnect = array("id" => $record->accesstype);
		$typeaccess=$DB->get_record('wlab_access', $codconnect);
		//redirect to the type of connection
		if (stripos($typeaccess->name,"deusto")){
			$url="Location: expdeusto.php?id=". $record->id;
			header( $url ) ;
		}
		else {
			$url="Location: expiLab.php?id=". $record->id;
			header( $url ) ;

		}
       
}

   admin_externalpage_print_header();
?>
<h1><center>ADD EXPERIMENTS</center></h1>

<form name="form" method="post" action="addexp.php">
<center>


<table cellpadding="10">


<tr valign="top">
    <td align="left"><b>Laboratory:</b></td>
    	<td align="left">
	<select name="laboratory" value="<?php  p($form->laboratory) ?>" />
	  <?php
            $labs=$DB->get_records('wlab_list');
 		  
             foreach ($labs as $lab) 
		  { 
		    ?>
             <option value="<?php  p($lab->id) ?>"> <?php p($lab->name) ?> </option>";
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
<input type="submit" value="add experiment" />
</center>

</form>
<?php
admin_externalpage_print_footer();
?>
