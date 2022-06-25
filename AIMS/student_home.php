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

	echo '<h2 class="head">Welcome Student: ' . $user_name . '</h2>';
?>
	<form method="post">
		<input type="text" name="cname_i" placeholder="Name of the course to Register">
		<input type="submit" name="reg_course" value="Register for course"/>
		<br>
		<input type="text" name="cname_o" placeholder="Name of the course to Unregister">
		<input type="submit" name="unreg_course" value="Unregister for course"/>
	</form>

	<br><br><br><h2 class="head">List of your regitered courses</h2>

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

$course_name_reg = $_POST["cname_i"];
$course_name_unreg = $_POST["cname_o"];


$str = '\'';

function print_student_courses() {
	global $conn, $user_name, $str;
	$que = 'select course_data.course_name, graded_by, grade, course_data.course_id from course_data left join student_course on course_data.course_id = student_course.course_id where student_username = \'';
	$quef = $que.$user_name;
	
	$result = query($quef.$str, $conn);

	echo "<table id=\"customers\" class=\"center\">";
	echo "<th>" . "Course Name" . "</th><th>" . "Grade" . "</th>"; 
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" . $row['course_name'] . "</td><td>" . $row['grade'] . "</td></tr>"; 
	}
	echo "</table>";
}

function is_registered($conne, $course_id, $usr_nme) {
	$que_check = "select * from student_course where course_id = '" . $course_id . "' and student_username = '" . $usr_nme . "'";

	$result_check = query($que_check, $conne);

	$rowCount = mysqli_num_rows($result_check);

	if ($rowCount != 0) {
		return true;
	}

	return false;
}

function get_course_details($c_id) {
	$que_cou = 'select * from course_data where course_id = \'';
	$que_cou_f = $que_cou.$c_id;

	global $conn, $str;

	$result = query($que_cou_f.$str, $conn);
	return $result;
} 

function reg_for_course($conn, $user_name, $course_name_reg) {
		$result = get_course_details($course_name_reg);
		$row = mysqli_fetch_assoc($result);

		if ($row['is_graded'] == true) {
			echo "Cant't register for this course as it is over<br>";
		}
		elseif ($row['is_active'] == false) {
			echo "Cant't register for this course as it is not active<br>";
		}
		else {
			if (is_registered($conn, $course_name_reg, $user_name)) {
				echo "Already Registered<br>";
			}
			else {
				$query_to_add_1 = "insert into student_course (course_id, student_username, grade) values ('" . $course_name_reg . "', '" . $user_name . "', '" . "N" . "')";

				query($query_to_add_1, $conn);

				echo "Registered succesfuly<br>";
			}
		}
}

function unreg_for_course() {
	global $conn, $course_name_unreg, $user_name;
	if (is_registered($conn, $course_name_unreg, $user_name)) {
		$result = get_course_details($course_name_unreg);
		$row = mysqli_fetch_assoc($result);

		if ($row['is_graded']) {
			echo "This course is alredy graded<br>";
			return;
		}
		
		$delete_query = "delete from student_course where course_id = '" . $course_name_unreg . "' and student_username = '" . $user_name . "'";

		query($delete_query, $conn);

		echo "Unregistered<br>";
	}
	else {
		echo "You are not registered for this course <br>";
	}
}

print_student_courses();

if (isset($_POST['reg_course'])) {
	reg_for_course($conn, $user_name, $course_name_reg);
}
elseif (isset($_POST['unreg_course'])) {
	unreg_for_course();
} 


?>

</body>
</html>

