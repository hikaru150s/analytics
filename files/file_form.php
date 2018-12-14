<?php
require_once($CFG->libdir.'/formslib.php');

/*
block_analytics_about
	id
	userid
	about
	aboutformat
*/

class file_form extends moodleform {
	
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
		$mform->addElement('hidden', 'userid', 0);
		$mform->addElement('filepicker', 'userfile', 'New File', null, array('maxbytes' => self::return_bytes(ini_get('post_max_size')), 'accepted_types' => '*'));
		
		$mform->setType('userid', PARAM_INT);
		// Add standard buttons.
        $this->add_action_buttons();
	}
	
	function validation($data, $files) {
		return array();
	}
	
	public static function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		$val = intval($val);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
}
