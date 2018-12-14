<?php
require_once __DIR__ . '/../lib.php';

function view_performance($userid) {
	// Show Performance (Table and Graphics)
	$dataset = get_group_evaluation($userid);
	$perf_stat = (!is_null($dataset) && count($dataset) > 0) ? stat_calc(normalize($dataset, 'score')) : null;

	return
		'<h1 class="printable">Performance</h1>' .
		print_table($dataset, 'table table-hover table-striped') .
		'<hr class="printable"/>' .
		'<h1 class="printable">Statistic</h1>' .
		print_stat($perf_stat) .
		'<hr class="printable"/>' .
		'<h1 class="printable">Graph</h1>' .
		print_graph($dataset, 'performance', 'time', 'score') .
		'<hr class="printable"/>';
}
