<?PHP

    // Allows the admin to experimets from a laboratory

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');

    require_login();
    admin_externalpage_setup('expcour');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");
      
    $id= required_param('id', PARAM_INT);   // id of laboratory
    
    global $DB;
    $cond = array("id" =>$id);
    $laboratory=$DB->get_record("wlab_list", $cond);

/// If data submitted, process and store in database
    if (($form = data_submitted())) {
	    
	$msg= new stdClass();
   
      $msg->idexp = $form->idexp;
      $msg->course = $form->idco;
      

      $DB->insert_record('wlab_experiment_course',$msg);
 
}


    admin_externalpage_print_header();
?>

<h1><center>ADD EXPERIMENTS TO COURSES</center></h1>

<form name="form" method="post" action="addcoexp1.php?id=<?php  p($id) ?>">
<center>


<table cellpadding="10">

<tr valign="top">
    	<td align="left"><b>Name of Laboratory:</b> </td>
	<td align="left">
       <input type="text" name="idlab" size="90" disabled value="<?php  p($laboratory->name) ?>" />
      </td>
 </tr>


<tr valign="top">
    <td align="left"><b>Experiment:</b></td>
    	<td align="left">
	<select name="idexp" value="<?php  p($form->idexp) ?>" />
	  <?php
            $cond = array("idlab" =>$id);
		$experiments=$DB->get_records('wlab_experiment',$cond);
 		  
             foreach ($experiments as $experiment) 
		  { 
		    ?>
             <option value="<?php  p($experiment->id) ?>"> <?php p($experiment->name) ?> </option>";
	 <?php } ?>	  		
	   
	</select>
    </td>
 </tr>


<tr valign="top">
    <td align="left"><b>Courses:</b></td>
    	<td align="left">
	<select name="idco" value="<?php  p($form->idco) ?>" />
	  <?php
            $courses=$DB->get_records('course');
 		  
             foreach ($courses as $course) 
		  { 
		    if (!($course->category == 0)){?>
             <option value="<?php  p($course->id) ?>"> <?php p($course->fullname) ?> </option>";
	 <?php }} ?>	  		
	   
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
<input type="submit" value="Add experiment to Course" />
</center>

</form>


<?php
admin_externalpage_print_footer();
?>