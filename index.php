<!DocType html>
<html>
	<head><title>Dev</title></head>
	<body>
<?php
$dev = true;
if ($dev) {
	require_once 'lib.php';
	require_once 'dev/autoload.php';
	require_once 'views/classreport.php';
	require_once 'views/groups.php';
	require_once 'views/performance.php';
	require_once 'views/quiz.php';
	require_once 'views/students.php';
	require_once 'views/correlation.php';
	
	$role = isset($_GET['role']) ? $_GET['role'] : null;
	$userid = 29;
	$courseid = 9;
	
	if (is_null($role)) {
		echo '<a href="?role=student">Student</a> | <a href="?role=teacher">Teacher</a>';
	} else if ($role == 'student') {
		echo view_quiz($userid);
		echo view_groups($userid);
		echo view_performance($userid);
	} else if ($role == 'teacher') {
		echo view_correlation($courseid);
	}
}
?>
	</body>
</html>