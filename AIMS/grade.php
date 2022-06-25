<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href=".//main.css"/>
	<style>
		input[type=submit] {
			width: 5%;
			background-color: #4CAF50;
			color: white;
			padding: 10px 15px;
			margin: 4px 0;
			border: none;
			border-radius: 2px;
			cursor: pointer;
			font-size: large;
		}

		div {
  			margin: 100px;
		}
	</style>
</head>

<body style="background-color:navy;">

<?php
session_start();

$dbhost = 'localhost';
$dbuser = 'aims_admin';
$dbpass = 'Tsunami123!';
$dbname = 'aims_portal';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
} 

$user_name = $_SESSION["uname"];
$str = '\'';
$crse_id = $_GET['cou_id'];

echo '<h1 class="head"> Welcome ' . $user_name . ' to the grade page';

function query($qu_str, $conne) {
  $result = mysqli_query($conne, $qu_str);
  return $result;
}

function grade_course() {
	global $conn, $user_name, $str, $crse_id;
	echo '<div><h1 class="headL"> Please grade course: ' .$crse_id .'</h1>';

	$que = 'select student_username from student_course where course_id = \'';
  	$quem = $que.$crse_id;
  	$quef = $quem.$str;

  	$result = query($quef, $conn);
  	$i = 0;
	
  	echo '<form method="post" style="text-align: left">';
	while ($row = mysqli_fetch_assoc($result)) {
		echo '<b class="headL">' . $row['student_username'] . ": </b>";
		echo '<input type="radio" name="' . $i . '" value="A"><b class="tick">A</b>';
		echo '<input type="radio" name="' . $i . '" value="B"><b class="tick">B</b>';
		echo '<input type="radio" name="' . $i . '" value="C"><b class="tick">C</b>';
		echo '<input type="radio" name="' . $i . '" value="D"><b class="tick">D</b>';
		echo '<input type="radio" name="' . $i . '" value="F"><b class="tick">F</b>';
		echo "<br> <br>";
		$i = $i + 1;
	} 
  	echo '<input type="submit" value="submit" name="submit">';
	echo ' ';
	echo '<input type="button" class="s" value="Back" onclick="history.back()">';
	echo "</form>";

	if (isset($_POST['submit'])) {
		$j = 0;

		$result1 = query($quef, $conn);

		$set_que_p1 = "update course_data set graded_by='";
		$temp_que = $set_que_p1.$user_name;
		$set_q_p2 = $set_que_p1."' where cid='";
		$set_q_p3 = $set_q_p2.$crse_id;

		$result_temp1 = query($set_q_p3.$str, $conn);

		$temp = "update course_data set is_graded=1 where course_id='";
		$t1 = $temp.$crse_id;

		$result_temp1 = query($t1.$str, $conn);

		$temp1 = "update course_data set is_active=0 where course_id='";
		$t2 = $temp1.$crse_id;

		$result_temp2 = query($t2.$str, $conn);

		while($row = mysqli_fetch_assoc($result1)) {
			$s_id = $row['student_username'];
			
			$query_for_ins = "update student_course set grade='" . $_POST[$j] . "' where student_username='" . $s_id . "' and course_id='" . $crse_id . "'";

			$result_temp = query($query_for_ins, $conn);

			$j = $j + 1;
		}

		header("Location: http://srynsh.com/prof_home.php");
	}
}

grade_course();

mysqli_close($conn);
?>

</div>

</body>
</html>

