<?php
 
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(empty($_SERVER['CONTENT_TYPE'])) {
		$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
	}

	$access = true;
	include_once('config.php');
	include_once('checker.php');

	function validateUser($user, $connection) {
		if (empty($user)) {
			return true;
		}
		return !check($dbtype, $user, $connection);
	}

	function validateEmail($email, $connection) {
		if (empty($email)) {
			return true;
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return !check($dbtype, $email, $connection, 'email');
	}

	function validatePassword($pass) {
		if (empty($pass)) {
			return true;
		}
		return strlen($pass) < 8;
	}


	// Get data
	$errormsg = "Invalid data in fields:";
	if ($dbtype == 'm') {
		$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
		$name = isset($_POST['name']) ? $link->real_escape_string($_POST['name']) : "";
		$email = isset($_POST['email']) ? $link->real_escape_string($_POST['email']) : "";
		$pass = isset($_POST['pwd']) ? $link->real_escape_string($_POST['pwd']) : "";
	} else if ($dbtype == 'p') {
		$link = pg_connect("host=$dbaddr dbname=$dbname");
		$name = isset($_POST['name']) ? pg_escape_string($_POST['name']) : "";
		$email = isset($_POST['email']) ? pg_escape_string($_POST['email']) : "";
		$pass = isset($_POST['pwd']) ? pg_escape_string($_POST['pwd']) : "";
	}

	//verify data and insert
	if (!$error=validateUser($name, $link)) {
		if (!$error=validateEmail($email, $link)) {
			if (!$error=validatePassword($pass)) {
				$now = time();
				if ($dbtype == 'p') {
					$now = "to_timestamp($now)";
				}
				$query = "INSERT INTO users (username, email, password, regTime) VALUES ('$name', '$email', '$pass', $now);";
				if ($dbtype == 'm') {
					$result = $link->query($query);
				} else if ($dbtype == 'p') {
					$result = pg_query($link, $query);
				}
				if ($result) {
					$json = array("status" => 0, "msg" => "Done User added!");
				} else {
					$json = array("status" => 1, "msg" => "Error adding user!");
				}
			} else {
				$json = array("status" => 4, "msg" => "Password invalid!");
			}
		} else {
			$json = array("status" => 3, "msg" => "Email invalid!");
		}
	} else {
		$json = array("status" => 2, "msg" => "Username invalid!");
	}

	if ($dbtype == 'm' ) {
		$link->close();
	} else if ($dbtype == 'p') {
		pg_close($link);
	}
} else {
	$json = array("status" => 5, "msg" => "Request method not accepted");
}
 
/* Output header */
header('Content-type: application/json');
echo json_encode($json);
