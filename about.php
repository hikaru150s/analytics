<?php
require_once '../../config.php';
require_once 'lib.php';

global $DB, $OUTPUT, $PAGE, $USER;
// Get required parameters
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);

// Next look for optional variables.
$operation = optional_param('operation', 'view', PARAM_TEXT);
$studentid = optional_param('studentid', $USER->id, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_analytics', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/analytics/about.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Summary');
$PAGE->set_title('Summary');

$settingsnode = $PAGE->settingsnav->add(ucfirst($operation));
$editurl = new moodle_url('/blocks/analytics/about.php', array('courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add('Summary', $editurl);
$editnode->make_active();

$backurl = new moodle_url('/course/view.php', array('id' => $courseid));

// Check permission to edit
$permission = ($studentid == $USER->id);
require_once 'abouts/about.php';

echo $OUTPUT->header();
if ($permission) {
	// Have permission to edit
	require_once 'abouts/about_form.php';
	
	$mform = new about_form($editurl);
	if ( $mform->is_cancelled() ) {
		// NOP()
	} else if ( $data = $mform->get_data() ) {
		$data->aboutformat = $data->about['format'];
		$data->about = $data->about['text'];
		$DB->update_record('block_analytics_about', $data);
	}
	
	echo about_about($studentid);
	
	$old = $DB->get_record('block_analytics_about', ['userid' => $studentid]);
	$prep = (array) $old;
	$compound = array('text' => $prep['about'], 'format' => $prep['aboutformat']);
	$prep['about'] = $compound;
	$mform->set_data($prep);
	$mform->display();
} else {
	// View only
	echo about_about($studentid);
}
echo $OUTPUT->footer();
