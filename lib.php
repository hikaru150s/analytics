<?php
# DEV
//require_once 'dev/autoload.php';
# END DEV
require_once 'vendor/autoload.php';

use Phpml\Math\Statistic\Variance;
use Phpml\Math\Statistic\Mean;
use Phpml\Math\Statistic\StandardDeviation;
use Phpml\Math\Statistic\Correlation;

/*
	Calculate average of array with element pointed by field
*/
function calculate_average($arr, $field = null) {
	$sum = 0;
	$c = 0;
	foreach ($arr as $v) {
		$sum += is_null($field) ? $v : $v->$field;
		$c += 1;
	}
	
	return ($c == 0) ? null : $sum / $c;
}

/*
	Get $userid quiz history
*/
function get_user_quiz($userid) {
	global $DB;
	
	$sql = "select * from mdl_adaptive_transactions where userid = $userid order by attempttime asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			// Convert attempttime to readable format
			$v->attempttime = date('Y-m-d H:i:s', $v->attempttime);
			$result[] = $v;
		}
		return $result;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Get the average of $userid quiz history
*/
function mean_of_quiz($userid) {
	global $DB;
	
	$sql = "select avg(score) as average from mdl_adaptive_transactions where userid = $userid";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return 0;
	} else {
		$result = null;
		foreach ($res as $v) {
			$result = $v;
		}
		return (is_null($result)) ? 0 : $result->average;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Get the group evaluation history of $userid
*/
function get_group_evaluation($userid) {
	global $DB;
	
	$sql = "select avg(score) as score, `time` from mdl_block_adg_collab_history where touser = $userid group by sessionid order by time asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		return $result;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Get the average group evaluation of $userid
*/
function mean_of_evaluation_performance($userid) {
	global $DB;
	
	$res = get_group_evaluation($userid);
	return (is_null($res)) ? 0 : calculate_average($res, 'score');
}

/*
	Get all students in $courseid
*/
function list_students_in_course($courseid) {
	global $DB;
	
	$sql = "select id, username, firstname, lastname, email from mdl_user where id in (select userid from mdl_user_enrolments where enrolid in (select id from mdl_enrol where courseid = $courseid))";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		return $result;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Get all groups in $courseid
*/
function get_groups($courseid) {
	global $DB;
	
	$q1 = "select * from mdl_block_adg_mbti_group where courseid = $courseid";
	
	$res1 = $DB->get_records_sql($q1);
	if (is_null($res1)) {
		return null;
	} else {
		$result = array();
		foreach ($res1 as $v) {
			$result[$v->id . '. ' . $v->name] = array();
			$q2 = 'select a.userid, a.nim, a.name, b.type, a.degree, a.level from mdl_block_adg_mbti_user a, mdl_block_adg_mbti_char b where a.characteristic = b.id and groupid = ' . $v->id;
			$res2 = $DB->get_records_sql($q2);
			if (is_null($res2)) {
				$result[$v->id . '. ' . $v->name][] = null;
			} else {
				foreach ($res2 as $w) {
					$result[$v->id . '. ' . $v->name][] = $w;
				}
			}
		}
		return $result;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Get group info from $userid
*/
function get_group_from_user($userid) {
	global $DB;
	
	$sql = "select * from mdl_block_adg_mbti_group where id = (select groupid from mdl_block_adg_mbti_user where id = $userid)";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		return $result;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Get group members that in same group of $userid
*/
function list_group_from_user($userid) {
	global $DB;
	
	$sql = "select * from mdl_block_adg_mbti_user where groupid = (select groupid from mdl_block_adg_mbti_user where id = $userid)";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		return $result;
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Print table
*/
function print_table($data, $class = null, $printable = true) {
	if (is_array($data) && count($data) > 0) {
		$col = array_keys((array) reset($data));
		$result = (is_null($class)) ? '<table>' : '<table class="' . $class  . ($printable ? ' printable' : '') . '">';
		
		$result .= '<tr>';
		foreach ($col as $v) {
			$result .= "<th>$v</th>";
		}
		$result .= '</tr>';
		
		
		foreach ($data as $row) {
			$result .= '<tr>';
			foreach ((array) $row as $v) {
				$result .= '<td>';
				$result .= $v;
				$result .= '</td>';
			}
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	} else {
		return '<pre>No data!</pre>';
	}
}

/*
	Print Matrix
*/
function print_matrix($data, $class = null, $preferClassColoring = false, $printable = true) {
	if (is_array($data) && count($data) > 0) {
		$col = array_keys((array) reset($data));
		array_unshift($col, 'x');
		$result = (is_null($class)) ? '<table>' : '<table class="' . $class  . ($printable ? ' printable' : '') . '">';
		
		$result .= '<tr>';
		foreach ($col as $v) {
			$result .= "<th>$v</th>";
		}
		$result .= '</tr>';
		
		$func = function($val) {
			$a = array(
				'R'	=> 0xF8 + (0x03/2),
				'G'	=> 0x69 + (0x4B/2),
				'B'	=> 0x68 + (0x4B/2)
			);
			$b = array(
				'R'	=> 0x63 + (0x4E/2),
				'G'	=> 0xBE + (0x20/2),
				'B'	=> 0x7B + (0x42/2)
			);
			
			$R = range($a['R'], $b['R'], abs($a['R'] - $b['R']) / 5);
			$G = range($a['G'], $b['G'], abs($a['G'] - $b['G']) / 5);
			$B = range($a['B'], $b['B'], abs($a['B'] - $b['B']) / 5);
			
			if ($val <= 0) {
				return sprintf('%X%X%X', $R[0], $G[0], $B[0]);
			} else if ($val > 0 && $val <= 0.25) {
				return sprintf('%X%X%X', $R[1], $G[1], $B[2]);
			} else if ($val > 0.25 && $val <= 0.5) {
				return sprintf('%X%X%X', $R[2], $G[2], $B[2]);
			} else if ($val > 0.5 && $val <= 0.75) {
				return sprintf('%X%X%X', $R[3], $G[3], $B[3]);
			} else if ($val > 0.75 && $val < 1) {
				return sprintf('%X%X%X', $R[4], $G[4], $B[4]);
			} else {
				return sprintf('%X%X%X', $R[5], $G[5], $B[5]);
			}
		};
		
		$classColoring = function($val) {
			$order = array('danger', 'warning', 'secondary', 'info', 'primary', 'success');
			if ($val <= 0) {
				return sprintf('table-%s', $order[0]);
			} else if ($val > 0 && $val <= 0.25) {
				return sprintf('table-%s', $order[1]);
			} else if ($val > 0.25 && $val <= 0.5) {
				return sprintf('table-%s', $order[2]);
			} else if ($val > 0.5 && $val <= 0.75) {
				return sprintf('table-%s', $order[3]);
			} else if ($val > 0.75 && $val < 1) {
				return sprintf('table-%s', $order[4]);
			} else {
				return sprintf('table-%s', $order[5]);
			}
		};
		
		foreach ($data as $k => $row) {
			$result .= '<tr>';
			$result .= '<th>';
			$result .= $k;
			$result .= '</th>';
			foreach ((array) $row as $v) {
				$result .= $preferClassColoring ? '<td class="' . $classColoring($v) . '">' : '<td style="background-color: #' . $func($v) . '">';
				$result .= sprintf('%f', $v);
				$result .= '</td>';
			}
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	} else {
		return '<pre>No data!</pre>';
	}
}

/*
	Print statistic data
*/
function print_stat($stat, $printable = true) {
	$data = (array) $stat;
	if (is_array($data) && count($data) > 0) {
		$result = '<table' . ($printable ? ' class="printable"' : '') . '>';
		
		foreach ($data as $k => $v) {
			$result .= '<tr>';
			$result .= '<td>';
			$result .= $k;
			$result .= '</td>';
			$result .= '<td>';
			$result .= $v;
			$result .= '</td>';
			$result .= '</tr>';
		}
		
		$result .= '</table>';
		
		return $result;
	} else {
		return '<pre>No data!</pre>';
	}
}

/*
	Calculate statistics of given array
*/
function stat_calc($arr) {
	$result = new stdClass();
	$result->count					= count($arr);
	$result->variance				= Variance::population($arr);
	$result->mean					= Mean::arithmetic($arr);
	$result->median					= Mean::median($arr);
	$result->mode					= Mean::mode($arr);
	$result->stdDev_pop				= StandardDeviation::population($arr, false);
	$result->stdDev_sumOfSquares	= StandardDeviation::sumOfSquares($arr);
	return $result;
}

/*
	Normalize array before statistic calculation
*/
function normalize($arr, $field) {
	$func = function($in, $field) {
		//return (is_array($in)) ? intval($in[$field]) : intval($in->$field);
		return (is_array($in)) ? ($in[$field]) : ($in->$field);
	};
	return array_map($func, $arr, array_pad(array(), count($arr), $field));
}

/*
	Print Graph
*/
function print_graph($arr, $chartname, $label, $value, $printable = true) {
	$jsaddr = 'js/Chart.bundle.min.js';
	$data = array();
	
	foreach ($arr as $r) {
		$data[$r->$label] = $r->$value;
	}
	
	$dataset = new stdclass();
	$dataset->label = $value;
	$dataset->data = array_values($data);
	$dataset->backgroundColor = 'rgba(0, 255, 0, 0.05)';
	$dataset->borderColor = 'rgba(75, 192, 192, 1)';
	
	$tickOption = new stdClass();
	$tickOption->ticks = new stdClass();
	$tickOption->ticks->beginAtZero = true;
	
	$chart = new stdClass();
	$chart->type = 'bar';
	$chart->data = new stdClass();
	$chart->data->labels = array_keys($data);
	$chart->data->datasets = array($dataset);
	$chart->options = new stdClass();
	$chart->options->scales = new stdClass();
	$chart->options->scales->yAxes = array($tickOption);
	
	$line = '
		<canvas id="' . $chartname . '" width="100%" height="50%"' . ($printable ? ' class="printable"' : '') . '></canvas>
		<script type="text/javascript" src="' . $jsaddr . '"></script>
		<script>
			var context = document.getElementById("' . $chartname . '");
			var ' . $chartname . ' = new Chart(context, ' . json_encode($chart) . ');
		</script>
	';
	
	return $line;
}

/*
	Print Groups
*/
function print_groups($data, $class = null, $printable = true) {
	if (is_array($data) && count($data) > 0) {
		$step1 = reset($data);
		$step2 = reset($step1);
		
		$col = array_keys((array) $step2);
		
		$result = (is_null($class)) ? '<table>' : '<table class="' . $class  . ($printable ? ' printable' : '') . '">';
		
		$c = 0;
		$result .= '<tr>';
		foreach ($col as $v) {
			$result .= "<th>$v</th>";
			$c += 1;
		}
		$result .= '</tr>';
		
		
		foreach ($data as $k => $row) {
			$result .= '<tr>';
			$result .= '<td colspan="' . $c . '">';
			$result .= $k;
			$result .= '</td>';
			$result .= '</tr>';
			foreach ($row as $subrow) {
				$result .= '<tr>';
				foreach ((array) $subrow as $v) {
					$result .= '<td>';
					$result .= $v;
					$result .= '</td>';
				}
				$result .= '</tr>';
			}
		}
		
		$result .= '</table>';
		
		return $result;
	} else {
		return '<pre>No data!</pre>';
	}
}

/*
	create string range
*/
function str_range($start, $end) {
	return '(' . implode(', ', range($start, $end)) . ')';
}

/*
	extract Knowledge points from $courseid
*/
function var_knowledge($courseid) {
	global $DB;
	
	//$sql = "select avg(score) as average from mdl_adaptive_transactions where adaptiveid = (select id from mdl_adaptive where course = $courseid) group by userid";
	$sql = "select avg(score) as average from mdl_adaptive_transactions where userid in (select id from mdl_user where id in (select userid from mdl_user_enrolments where enrolid in (select id from mdl_enrol where courseid = $courseid))) group by userid order by userid asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		// In order to works well, the data must be normalized first
		$func = function($a) {
			return $a / 100; // Value normalization
		};
		return array_map($func, normalize($result, 'average'));
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	extract Sharing points from $courseid
*/
function var_sharing($courseid) {
	global $DB;
	
	$sql = "select avg(score) as average from mdl_block_adg_collab_history where courseid = $courseid and questionid in " . str_range(1, 3) . " group by touser order by touser asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		// In order to works well, the data must be normalized first
		$func = function($a) {
			return $a / 5; // Value normalization
		};
		return array_map($func, normalize($result, 'average'));
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	extract Negotiating points from $courseid
*/
function var_negotiating($courseid) {
	global $DB;
	
	$sql = "select avg(score) as average from mdl_block_adg_collab_history where courseid = $courseid and questionid in " . str_range(4, 13) . " group by touser order by touser asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		// In order to works well, the data must be normalized first
		$func = function($a) {
			return $a / 5; // Value normalization
		};
		return array_map($func, normalize($result, 'average'));
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	extract Regulating points from $courseid
*/
function var_regulating($courseid) {
	global $DB;
	
	$sql = "select avg(score) as average from mdl_block_adg_collab_history where courseid = $courseid and questionid in " . str_range(14, 24) . " group by touser order by touser asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		// In order to works well, the data must be normalized first
		$func = function($a) {
			return $a / 5; // Value normalization
		};
		return array_map($func, normalize($result, 'average'));
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	extract Communication points from $courseid
*/
function var_communication($courseid) {
	global $DB;
	
	$sql = "select avg(score) as average from mdl_block_adg_collab_history where courseid = $courseid and questionid in " . str_range(25, 33) . " group by touser order by touser asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		// In order to works well, the data must be normalized first
		$func = function($a) {
			return $a / 5; // Value normalization
		};
		return array_map($func, normalize($result, 'average'));
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	extract Collaborative Performance from $userid
*/
function var_collaborative($courseid) {
	global $DB;
	
	$func = function($a, $b, $c, $d) {
		return ($a + $b + $c + $d) / 4;
	};
	
	return array_map($func, var_sharing($courseid), var_negotiating($courseid), var_regulating($courseid), var_communication($courseid));
}

/*
	extract Skill from $courseid
*/
function var_skill($courseid) {
	global $DB;
	
	// One data (a.userid = 48) must be excluded in order to continue the process
	$sql = "select avg(b.score) as average from mdl_block_adg_mbti_user a, mdl_collab_transactions b where a.groupid = b.groupid and a.userid != 48 and collabid in (select id from mdl_collab where course = $courseid) group by a.userid, b.groupid order by a.userid asc";
	
	$res = $DB->get_records_sql($sql);
	if (is_null($res)) {
		return null;
	} else {
		$result = array();
		foreach ($res as $v) {
			$result[] = $v;
		}
		// In order to works well, the data must be normalized first
		$func = function($a) {
			return $a / 100; // Value normalization
		};
		return array_map($func, normalize($result, 'average'));
	}
	
	//return $DB->get_records_sql($sql);
}

/*
	Generate correlation matrix
*/
function correlate($courseid) {
	// Generate vars
	$vars = array(
		'knowledge'		=> var_knowledge($courseid),
		'sharing'		=> var_sharing($courseid),
		'negotiating'	=> var_negotiating($courseid),
		'regulating'	=> var_regulating($courseid),
		'communication'	=> var_communication($courseid),
		'collaborative'	=> var_collaborative($courseid),
		'skill'			=> var_skill($courseid)
	);
	// Generate empty matrix
	$row = array(
		'knowledge'		=> 0,
		'sharing'		=> 0,
		'negotiating'	=> 0,
		'regulating'	=> 0,
		'communication'	=> 0,
		'collaborative'	=> 0,
		'skill'			=> 0
	);
	$mat = array(
		'knowledge'		=> $row,
		'sharing'		=> $row,
		'negotiating'	=> $row,
		'regulating'	=> $row,
		'communication'	=> $row,
		'collaborative'	=> $row,
		'skill'			=> $row
	);
	// Fill matrix with Pearson Correlation
	foreach($mat as $y => &$row) {
		foreach ($row as $x => &$unit) {
			// Unique check
			$u_x = array_unique($vars[$x]);
			$u_y = array_unique($vars[$y]);
			$v_x = count($u_x) > 1 || (count($u_x) == 1 && $u_x[0] > 0);
			$v_y = count($u_y) > 1 || (count($u_y) == 1 && $u_y[0] > 0);
			// Pearson must have at least 2 elements
			if ($v_x && $v_y && count($vars[$x]) > 1 && count($vars[$y]) > 1 && count($vars[$x]) == count($vars[$y])) {
				$unit = Correlation::pearson($vars[$x], $vars[$y]);
			} else if (count($vars[$x]) != count($vars[$y])) {
				$unit = "Cannot calculate: $x count(" . count($vars[$x]) . ") different with $y count(" . count($vars[$y]) . ")";
			}
		}
	}
	// Return
	return $mat;
}
