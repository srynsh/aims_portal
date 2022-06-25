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

$dbhost = 'localhost';
$dbuser = 'aims_admin';
$dbpass = 'Tsunami123!';
$dbname = 'aims_portal';

$user_name = $_SESSION["uname"];
$str = '\'';

echo '<h2 class="head">Welcome Professor: ' . $user_name . '</h2>';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
} 

function query($qu_str, $conne) {
  $result = mysqli_query($conne, $qu_str);
  return $result;
}

function print_prof_courses() {
  global $conn, $user_name, $str;

  $que = 'select course_data.course_name, graded_by, is_graded, course_data.course_id from course_data left join prof_course on course_data.course_id = prof_course.cid where pid = \'';
  $quef = $que.$user_name;
  
  $result = query($quef.$str, $conn);

  echo "<br><br><h2 class=\"head\">List of your instructed courses</h2><table id=\"customers\" class=\"center\">";
  echo "<th> Course ID </th><th> Course Name </th><th> Status </th>"; 
  while ($row = mysqli_fetch_assoc($result)) {
    if (!$row['is_graded']) {
      $link_to_grade = '<a href=grade.php?cou_id=' . $row['course_id'] ;
      $link_to_grade1 = $link_to_grade . '>Grade</a>';
      echo "<tr><td>" . $row['course_id'] . "</td><td>" . $row['course_name'] . "</td><td>" . $link_to_grade1 . "</td></tr>"; 
    }
    else if ($row['is_graded']) {
      echo "<tr><td>" . $row['course_id'] . "</td><td>" . $row['course_name'] . "</td><td>" . "Graded" . "</td></tr>"; 
    }
  }
  echo "</table>";	
}

print_prof_courses();


?>

</body>
</html>

