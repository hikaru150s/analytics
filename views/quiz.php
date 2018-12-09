<?php
require_once __DIR__ . '/../lib.php';

function view_quiz($userid) {
	// Show Quiz (Table and Graphics)
	$dataset = get_user_quiz($userid);
	$quiz_stat = (!is_null($dataset) && count($dataset) > 0) ? stat_calc(normalize($dataset, 'score')) : null;

	return 
		'<h1>Quiz</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr/>' .
		'<h1>Statistic</h1>' .
		print_stat($quiz_stat) .
		'<hr/>' .
		'<h1>Graph</h1>' .
		print_graph($dataset, 'quiz', 'attempttime', 'score') .
		'<hr/>';
}
