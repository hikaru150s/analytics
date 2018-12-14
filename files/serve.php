<?php
require_once '../../../config.php';
require_once '../lib.php';

global $DB, $OUTPUT, $PAGE, $USER;
// Get required parameters
$id = required_param('id', PARAM_INT);

// Next look for optional variables.
$act = optional_param('act', 'none', PARAM_TEXT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$blockid = optional_param('blockid', 0, PARAM_INT);

// Get data
$data = $DB->get_record('block_analytics_files', ['id' => $id]);

// Check permission to delete
$permission = ($data->userid == $USER->id);

if ($act == 'del' && $permission && $courseid != 0 && $blockid != 0) {
	$status = unlink($data->file);
	if ($status) {
		$DB->delete_records('block_analytics_files', ['id' => $id]);
		redirect(new moodle_url('/blocks/analytics/file.php', array('courseid' => $courseid, 'blockid' => $blockid)));
	}
} else {
	if (file_exists($data->file)) {
		header('Content-type: octet-stream');
		header('Content-length: ' . filesize($data->file));
		header('Content-Disposition: filename="' . basename($data->file));
		header('X-Pad: avoid browser bug');
		header('Cache-Control: no-cache');
		readfile($data->file);
	} else {
		header("HTTP/1.0 404 Not Found");
	}
}
