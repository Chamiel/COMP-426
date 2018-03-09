<html>
<body>

<?php

$servername = "classroom.cs.unc.edu";
$username = "chamiel";
$password = "Js9ZwD05qUZ9sNwn";
$dbname = "chamieldb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("DROP TABLE IF EXISTS EventType");
$conn->query("DROP TABLE IF EXISTS Player");
$conn->query("DROP TABLE IF EXISTS Team");
$conn->query("DROP TABLE IF EXISTS Game");
$conn->query("DROP TABLE IF EXISTS ScoreEvent");

$conn->query("CREATE TABLE IF NOT EXISTS Player (
	id int AUTO_INCREMENT PRIMARY KEY,
	First varchar(25) NOT NULL,
	Last varchar(25) NOT NULL,
	team int NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS Team (
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(25) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS Game (
	id int AUTO_INCREMENT PRIMARY KEY,
	team1 int NOT NULL,
	team2 int NOT NULL,
	day int NOT NULL,
	month int NOT NULL,
	year int NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS ScoreEvent (
	id int AUTO_INCREMENT PRIMARY KEY,
	player int NOT NULL,
	game int NOT NULL,
	type int NOT NULL,
	passer int
)");

$conn->query("CREATE TABLE IF NOT EXISTS EventType (
	id int AUTO_INCREMENT PRIMARY KEY,
	type varchar(25) NOT NULL,
	points int NOT NULL
)");

$conn->query("INSERT INTO EventType VALUES (0, 'fieldgoal', 3)");
$conn->query("INSERT INTO EventType VALUES (0, 'passing', 7)");
$conn->query("INSERT INTO EventType VALUES (0, 'rushing', 7)");

$myfile = fopen("a3-data.txt", "r");
while(!feof($myfile)) {
	$input = explode(" ", str_replace("\n","",fgets($myfile)));
	if (count($input)<2)
		break;
	$team1_result = $conn->query("SELECT * FROM Team WHERE name='".$input[2]."'");
	if ($team1_result->num_rows == 0) {
		$conn->query("INSERT INTO Team VALUES (0, '".$input[2]."')");
		$team1 = $conn->insert_id;
	} else {
		$team1_fetch = $team1_result->fetch_assoc();
		$team1 = $team1_fetch['id'];
	}
	$player_id_result = $conn->query("SELECT * FROM Player WHERE First='".$input[0]."' AND Last='".$input[1]."'");
	if ($player_id_result->num_rows == 0) {
		$conn->query("INSERT INTO Player VALUES (0, '".$input[0]."', '".$input[1]."', '".$team1."')");
		$player_id = $conn->insert_id;
	} else {
		$player_id_fetch = $player_id_result->fetch_assoc();
		$player_id = $player_id_fetch['id'];
	}
	$team2_result = $conn->query("SELECT * FROM Team WHERE name='".$input[3]."'");
	if ($team2_result->num_rows == 0) {
		$conn->query("INSERT INTO Team VALUES (0, '".$input[3]."')");
		$team2 = $conn->insert_id;
	} else {
		$team2_fetch = $team2_result->fetch_assoc();
		$team2 = $team2_fetch['id'];
	}
	$date = explode("-", $input[4]);
	$game_result = $conn->query("SELECT * FROM Game WHERE (team1='".$team1."' OR team1='".$team2."') AND (team2='".$team1."' OR team2='".$team2."')
		AND day='".$date[2]."' AND month='".$date[1]."' AND year='".$date[0]."'");
	if ($game_result->num_rows == 0) {
		$conn->query("INSERT INTO Game VALUES(0, '".$team1."', '".$team2."', '".$date[2]."', '".$date[1]."', '".$date[0]."')");
		$game = $conn->insert_id;
	} else {
		$game_fetch = $game_result->fetch_assoc();
		$game = $game_fetch['id'];
	}
	$type = mysqli_fetch_assoc($conn->query("SELECT * FROM EventType WHERE type = '".$input[5]."'"))['id'];
	
	$passer = NULL;
	if (count($input) == 8) {
		$passer_result = $conn->query("SELECT * FROM Player WHERE First='".$input[6]."' AND Last='".$input[7]."'");
		if ($passer_result->num_rows == 0) {
			$conn->query("INSERT INTO Player VALUES (0, '".$input[6]."', '".$input[7]."', '".$team1."')");
			$passer = $conn->insert_id;
		} else {
			$passer_fetch = $passer_result->fetch_assoc();
			$passer = $passer_fetch['id'];
		}
	}
	if (is_null($passer)) {
		$conn->query("INSERT INTO ScoreEvent VALUES (0, '".$player_id."', '".$game."', '".$type."', NULL)");
	} else
	$conn->query("INSERT INTO ScoreEvent VALUES (0, '".$player_id."', '".$game."', '".$type."', '".$passer."')");
	
}
fclose($myfile);

?>

</body>
</html>