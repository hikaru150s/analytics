<?php
require_once '../../config.php';
require_once 'lib.php';

global $DB, $OUTPUT, $PAGE, $USER;
// Get required parameters
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$type = required_param('type', PARAM_TEXT);

// Next look for optional variables.
$studentid = optional_param('studentid', $USER->id, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_analytics', $courseid);
}

require_login($course);

$PAGE->set_url('/blocks/analytics/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Statistic');
$PAGE->set_title('Statistic');

$settingsnode = $PAGE->settingsnav->add(ucfirst($type));
$editurl = new moodle_url('/blocks/analytics/view.php', array('type' => $type, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add('Statistic', $editurl);
$editnode->make_active();

$backurl = new moodle_url('/course/view.php', array('id' => $courseid));

// Check permission
$permission = 0;
$context = context_course::instance($courseid);
if ( has_capability('block/analytics:managepages', $context) ) {
	// Teacher Mode
	$permission = 2;
} else if ( has_capability('block/analytics:studentview', $context) ) {
	$permission = 1;
} else {
	// Guest Mode
	$permission = 0;
}

// Set view based on type and permission
if ($permission == 2) {
	echo $OUTPUT->header();
	require_once 'views/print.php';
	
	switch ($type) {
		case 'student' : {
			require_once 'views/students.php';
			echo view_print();
			echo view_students($courseid, $blockid);
			break;
		}
		case 'class' : {
			require_once 'views/classreport.php';
			echo view_print();
			echo view_classreport($courseid);
			break;
		}
		case 'correlation' : {
			require_once 'views/correlation.php';
			echo view_print();
			echo view_correlation($courseid);
			break;
		}
		case 'progress' : {
			require_once 'views/quiz.php';
			require_once 'views/groups.php';
			require_once 'views/performance.php';
			echo view_print();
			echo view_quiz($studentid);
			echo view_groups($studentid);
			echo view_performance($studentid);
			break;
		}
	}
	echo $OUTPUT->footer();
} else if ($permission == 1 && $type == 'progress') {
	echo $OUTPUT->header();
	require_once 'views/quiz.php';
	require_once 'views/groups.php';
	require_once 'views/performance.php';
	require_once 'views/print.php';
	echo view_print();
	echo view_quiz($studentid);
	echo view_groups($studentid);
	echo view_performance($studentid);
	echo $OUTPUT->footer();
} else {
	// Unathorized
	redirect($backurl);
}
