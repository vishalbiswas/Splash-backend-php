<?php
$msg = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(empty($_SERVER['CONTENT_TYPE'])) {
		$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
	}

        $user = isset($_POST['user'])?$_POST['user']:null;
	$pass = isset($_POST['pass'])?$_POST['pass']:null;

        if (isset($user) && isset($pass)) {
		$access = true;
		include_once('config.php');

		$query = "SELECT users.uid, users.email, users.fname, users.lname, users.profpic FROM users WHERE users.username = '$user' AND users.password = '$pass';";

		if ($dbtype == 'm') {
			$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
			$result = $link->query($query);
		} else if ($dbtype == 'p') {
			$link = pg_connect("host=$dbaddr dbname=$dbname") or die("db cannot be opened");
			$result = pg_query($link, $query) or die("query failed");
		}
    
		if (($dbtype == 'm' && $result->num_rows) || ($dbtype == 'p' && pg_num_rows($result))) {
			$msg['status'] = 0;
			$msg['msg'] = 'Login success';

			if ($dbtype == 'm') {
				$row = $result->fetch_row();
			} else if ($dbtype == 'p') {
				$row = pg_fetch_row($result);
			}

			if (isset($row[2])) {
				$msg['fname'] = $row[2];
			}
			if (isset($row[3])) {
				$msg['lname'] = $row[3];
			}

			$msg['uid'] = $row[0];
			$msg['email'] = $row[1];
			if (isset($row[4])) {
				$msg['profpic'] = base64_encode($row[4]);
			}
			$msg['user'] = $user;
		} else {
			$msg['status'] = 1;
			$msg['msg'] = 'Invalid credentials';
		}

		if ($dbtype == 'm') {
			$result->free();
		} else if ($dbtype == 'p') {
			pg_free_result($result);
		}
	} else {
		$msg['status'] = 2;
		$msg['msg'] = 'Incomplete data received';
	}
} else {
	$msg['status'] = 3;
	$msg['msg'] = 'Request type not supported';
}
    
header('Content-type: application/json');
echo json_encode($msg);
