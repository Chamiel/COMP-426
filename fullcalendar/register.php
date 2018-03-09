<?php
$name = $_GET["name"];
$username = $_GET["user"];
$password = $_GET["pass"];
$connection = mysqli_connect('classroom.cs.unc.edu','yinglinc','password','yinglincdb') or die (mysqli_error($connection));

$sql = "CREATE TABLE IF NOT EXISTS `users`( 
		`uid` INT(12) NOT NULL AUTO_INCREMENT, 
		`name` VARCHAR(50) NOT NULL, 
		`username` VARCHAR(50) NOT NULL, 
		`password` VARCHAR(50) NOT NULL, 
		PRIMARY KEY (`uid`))";
	
$insertinfo = "INSERT INTO users(name, username, password) VALUES('$name', '$username', '$password')";
 
if ($connection->query($sql) === TRUE) {
	} 
	else {
    echo "Error creating table: " . $connection->error;
	}
	
if (mysqli_query($connection, $insertinfo)) {
	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('Thanks for registering with us!')
    window.location.href='login.html';
    </SCRIPT>");
	exit;
} else {
    echo "Error: " . $insertinfo . "<br>" . mysqli_error($connection);
}

mysqli_close($connection);
?>