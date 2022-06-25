<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href=".//main.css"/>
		<style>
			input[type=submit] {
				width: 10%;
				background-color: #4CAF50;
				color: white;
				padding: 14px 20px;
				margin: 8px 0;
				border: none;
				border-radius: 4px;
				cursor: pointer;
			}
		</style>
	</head>
<body style="background-color:navy;">

	<?php
		session_start();
		$user_name = $_SESSION["uname"];

		echo '<h2 class="head">Welcome Admin: ' . $user_name . '</h2>';
	?>

	<form method="POST">
		<input type="text" name="cid_i" placeholder="Course ID of new Course">
		<input type="text" name="cname_i" placeholder="Course Name of new Course">
		<input type="text" name="pname_i" placeholder="Instructor for new Course">
		<input type="submit" name="reg_course" value="Add course"/>
		<br><br>
		<input type="text" name="cname_o" placeholder="Course ID for the course to be deleted">
		<input type="submit" name="unreg_course" value="Remove course"/>
	</form>

<?php

$dbhost = 'localhost';
$dbuser = 'aims_admin';
$dbpass = 'Tsunami123!';
$dbname = 'aims_portal';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
} 

function query($qu_str, $conne) {
  $result = mysqli_query($conne, $qu_str);
  return $result;
}

$user_name = $_SESSION["uname"];

echo "<br>";

$str = '\'';

function print_all_courses() {
	global $conn, $user_name, $str;
	$que = 'select * from course_data';

	$result = query($que, $conn);

	echo "<br><br><h2 class=\"head\">List of all courses</h2><table id=\"customers\" class=\"center\">";
		echo "<th>" . "Course ID" . "</th><th>" . "Course Name" . "</th><th>" . "is_graded" . "</th><th>" . "is_active" . "</th>"; 
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" . $row['course_id'] . "</td><td>" . $row['course_name'] . "</td><td>" . $row['is_graded'] . "</td><td>" . $row['is_active'] . "</td></tr>"; 
	}
	echo "</table>";
}

print_all_courses();

function create_course() {
	if (isset($_POST['reg_course'])) {
		$course_name_reg = $_POST["cname_i"];
		$course_name_unreg = $_POST["cname_o"];
		global $conn, $user_name, $str;

		$cou_name = $_POST['cname_i'];
		$cou_id = $_POST['cid_i'];
		$proff_name = $_POST['pname_i'];

		$check_query1 = "select * from course_data where course_id='";
		$cq2 = $check_query1.$cou_id;
		$cq3 = $cq2."'";
		$cqf = $cq3.$str;

		$result = query($cqf, $conn);

		while($row = mysqli_fetch_assoc($result)) {
			die("A course with this id already exists<br>");
		}

		$q1 = "insert into course_data (course_id, is_graded, is_active, course_name) values ('" . $cou_id . "', '" . '0' . "', '" . '1' . "', '" . $cou_name . "')";

		$r1 = query($q1, $conn);

		$q2 = "insert into prof_course (pid, cid) values ('" . $proff_name . "', '" . $cou_id . "')";

		$r2 = query($q2, $conn);

		mysqli_close($conn);
	}
}

create_course();
?>

</body>
</html>
