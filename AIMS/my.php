<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href=".//main.css"/>
        <style>
            input[type=text], select {
            width: 20%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            }

            input[type=password], select {
            width: 20%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            }

            button {
            width: 20%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            }

            input[type=submit]:hover {
            background-color: #45a049;
            }

            div {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
            }

            form {
                text-align: center;
            }
        </style>
        <h1 class="head">AIMS Login page</h1>
    </head>
<body style="background-color:navy;">

<form action="my.php" method="post">
<input type="text" class="input" name="uname" placeholder="Username"/> <br> <br>
<input type="password" class="input" name="passwd" placeholder="Password"/> <br> <br>
<button type="submit">Login</button>

</form>
<br>

<?php

session_start();

$user_name = $_REQUEST["uname"];
$pswd = $_REQUEST["passwd"];

$_SESSION["uname"] = $user_name;
$_SESSION["passcode"] = $pswd;

//echo $user_name;
//echo $pswd;

$dbhost = 'localhost';
$dbuser = 'aims_admin';
$dbpass = 'Tsunami123!';
$dbname = 'aims_portal';

//print 'test';

//echo "<br>";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
} 

echo "<br>";

//print 'conn';

//mysql_select_db($dbname, $conn);

function query($qu_str, $conne) {
  $result = mysqli_query($conne, $qu_str);
  return $result;
}

//$resulttest = query('select * from user_data');

function login($u_name, $passwd, $conne) {
    $q = 'select * from user_data where user_name = \'';
    $e = '\'';
    $str_temp = $q.$u_name;
    $result = query($str_temp.$e, $conne);
    //echo $str_temp.$e;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['pswd'] == $passwd) {
            return true;
        }
    }

    return false;
}

function get_role($u_name, $conne) {
    $q = 'select * from user_data where user_name = \'';
    $e = '\'';
    $str_temp = $q.$u_name;
    $result = query($str_temp.$e, $conne);
    $row =  mysqli_fetch_assoc($result);
    return $row['roll'];
}

if (login($user_name, $pswd, $conn)) {
    if (get_role($user_name, $conn) == 'S') {
        header("Location: http://srynsh.com/student_home.php");
    }
    elseif (get_role($user_name, $conn) == 'P') {
        header("Location: http://srynsh.com/prof_home.php");
    }
    else {
        header("Location: http://srynsh.com/admin_home.php");
    }
}

?>

</body>
</html>

