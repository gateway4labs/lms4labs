<?PHP  

    // Allows the admin to add experiments

    require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require($CFG->libdir.'/deusto/weblabdeusto.class.php');
    
    require_login();
    admin_externalpage_setup('addexp');

    $context = get_context_instance(CONTEXT_SYSTEM);

    require_capability('moodle/site:config', $context, $USER->id, true, "nopermissions");
	
    $id= required_param('id', PARAM_INT);   // id of laboratory
    
    global $DB;

    $cond = array("id" =>$id);
    $lab=$DB->get_record("wlab_list", $cond);

    $rule = array("idlab" =>$id);
    $dataconnect=$DB->get_record('wlab_connection_deusto',$rule);

    
/// If data submitted, process and store in database
    if (($form = data_submitted())) {
	    
	$msg= new stdClass();
   
      $msg->idlab = $id;
      $msg->name = $form->exp;
      $msg->uniqueid = $form->unique;
      $msg->url = $form->url;
         
		
     // Commit to DB
     
	$DB->insert_record('wlab_experiment',$msg);
      
		         
}

   admin_externalpage_print_header();
?>
<h1><center>ADD EXPERIMENTS</center></h1>

<form name="form" method="post" action="expdeusto.php?id=<?php  p($id)?>">
<center>


<table cellpadding="10">

<tr valign="top">
    	<td align="left"><b>Name of Laboratory:</b> </td>
	<td align="left">
       <input type="text" name="idlab" size="70" disabled value="<?php  p($lab->name) ?>" />
      </td>
 </tr>

<tr valign="top">
    <td align="left"><b>experiments:</b></td>
    	<td align="left">
	<select name="exp" value="<?php  p($form->exp) ?>" />
	  <?php
           
		$weblab = new WebLabDeusto($dataconnect->url);
		$sess_id = $weblab->login($dataconnect->username,$dataconnect->pass);
		$experiments = $weblab->list_experiments($sess_id);
 		  
             foreach ($experiments as $experiment) 
		  { 
		    ?>
             <option value="<?php  p($experiment->name. '@'. $experiment->category)  ?>"> <?php p($experiment->name)?> </option>";
	 <?php } ?>	  		
	   
	</select>
    </td>
 </tr>

<tr valign="top">
    	<td align="left"><b>UniqueID:</b> </td>
	<td align="left">
       <input type="text" name="unique" size="70" value="<?php  p($form->unique) ?>" />
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
<input type="submit" value="add experiments" />
</center>

</form>
<?php
admin_externalpage_print_footer();
?>
