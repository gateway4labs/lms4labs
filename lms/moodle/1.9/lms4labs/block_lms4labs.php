<?php

class block_lms4labs extends block_base {

    function init() {
        $this->title = 'Lms for labs';
	 $this->version = 2004111200;
    }

    

    function get_content() {

        global $CFG, $USER;
       
       if ($this->content !== NULL) {
            return $this->content;
        }

    	$this->content = new stdClass;
      $url=$CFG->wwwroot ;
	$this->content->text = " <a href='". $url . "/blocks/lms4labs/addlms4labs.php'> Add data for Labmanager' </a>";
      $this->content->footer = '';

      return $this->content;
}
   
}


