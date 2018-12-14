<?php
require_once __DIR__ . '/../lib.php';

function view_quiz($userid) {
	// Show Quiz (Table and Graphics)
	$dataset = get_user_quiz($userid);
	$quiz_stat = (!is_null($dataset) && count($dataset) > 0) ? stat_calc(normalize($dataset, 'score')) : null;

	return 
		'<h1 class="printable">Quiz</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr class="printable"/>' .
		'<h1 class="printable">Statistic</h1>' .
		print_stat($quiz_stat) .
		'<hr class="printable"/>' .
		'<h1 class="printable">Graph</h1>' .
		print_graph($dataset, 'quiz', 'attempttime', 'score') .
		'<hr class="printable"/>';
}
