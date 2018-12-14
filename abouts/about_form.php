<?php
require_once($CFG->libdir.'/formslib.php');

/*
block_analytics_about
	id
	userid
	about
	aboutformat
*/

class about_form extends moodleform {
	
	public function definition() {
		global $CFG;
		
		$mform = $this->_form;
		
		$config = array(
			'subdirs'				=> 0,
			'maxbytes'				=> 0,
			'maxfiles'				=> 0,
			'changeformat'			=> 0,
			'context'				=> null,
			'noclean'				=> 0,
			'trusttext'				=> 0,
			'enable_filemanagement'	=> true
		);
		
		// Add form
		$mform->addElement('hidden', 'id', 0);
		$mform->addElement('hidden', 'userid', 0);
		$mform->addElement('editor', 'about', 'About you', $config);
		
		$mform->setType('id', PARAM_INT);
		$mform->setType('userid', PARAM_INT);
		$mform->setType('about', PARAM_RAW);
		// Add standard buttons.
        $this->add_action_buttons();
	}
	
	function validation($data, $files) {
		return array();
	}
}
