<!DOCTYPE html>
<html>
<head></head>
<body>


<?php
session_start();
$username = $_GET["user"];
$password = $_GET["pass"];
$connection = mysqli_connect('classroom.cs.unc.edu','yinglinc','password','yinglincdb') or die (mysqli_error($connection));

$getuid = "SELECT uid FROM users WHERE `username` = '" . $username . "' AND `password` = '" . $password . "'";
$getname = "SELECT name FROM users WHERE `username` = '" . $username . "' AND `password` = '" . $password . "'";

$name = mysqli_query($connection, $getname);
$fname = mysqli_fetch_assoc($name);

$uid = mysqli_query($connection, $getuid);
$fuid = mysqli_fetch_assoc($uid);

if (mysqli_num_rows($uid) != 0) {
	$_SESSION['user'] = $fuid['uid'];
	echo sprintf("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Welcome Back %s!')
    window.location.href='index.php';
    </SCRIPT>", $fname['name']);
	exit;
} else {
	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Incorrect username or password')
    window.location.href='login.html';
    </SCRIPT>");
	exit;
}
?>
</body>
</html>