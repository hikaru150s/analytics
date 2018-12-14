<?php
require_once '../../config.php';
require_once 'lib.php';

global $DB, $OUTPUT, $PAGE, $USER;
// Get required parameters
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);

// Next look for optional variables.
$studentid = optional_param('studentid', $USER->id, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_analytics', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/analytics/about.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Files');
$PAGE->set_title('Files');

$settingsnode = $PAGE->settingsnav->add('Files');
$editurl = new moodle_url('/blocks/analytics/file.php', array('courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add('Files', $editurl);
$editnode->make_active();

$backurl = new moodle_url('/course/view.php', array('id' => $courseid));

// Check permission to edit
$permission = ($studentid == $USER->id);
require_once 'files/viewer.php';

if ($permission) {
	// Have permission to edit
	require_once 'files/file_form.php';
	$err = '';
	
	$mform = new file_form($editurl);
	if ( $mform->is_cancelled() ) {
		// NOP()
		redirect($editurl);
	} else if ( $data = $mform->get_data() ) {
		$filename = $mform->get_new_filename('userfile');
		$fullpath = __DIR__ . '/files/securedassets/' . $data->userid . '/' . $filename;
		if (!file_exists($fullpath)) {			
			$state = $mform->save_file('userfile', $fullpath);
			if ($state) {
				$data->file = $fullpath;
				$DB->insert_record('block_analytics_files', $data);
				redirect($editurl);
			} else {
				$err = '<pre>Something is happened, cannot save file ' . $filename . ' now!</pre>';
			}
		} else {
			$err = '<pre>File ' . $filename . ' is already exist!</pre>';
		}
	}
	
	echo $OUTPUT->header();
	echo $err;
	echo file_viewer($studentid, true, $courseid, $blockid);
	$mform->set_data(['userid' => $studentid]);
	$mform->display();
} else {
	// View only
	echo file_viewer($studentid);
}
echo $OUTPUT->footer();
