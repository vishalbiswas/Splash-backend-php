<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(empty($_SERVER['CONTENT_TYPE'])) {
		$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
	}

	$uid = isset($_POST['uid']) ? $_POST['uid'] : null;

	if ($uid != null) {
		$access = true;
		include_once('config.php');
		$query = "SELECT users.username FROM users WHERE users.uid = $uid";
		header('Content-type: application/json');
		$response = array();
		$response['uid'] = $uid;

		if ($dbtype == 'm') {
			$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
			$result = $link->query($query);
			if ($result->num_rows) {
				$response['user'] = $result->fetch_row()[0];
			}
			$result->free();
		} else if ($dbtype == 'p') {
			$link = pg_connect("dbname=$dbname host=$dbaddr");
			$result = pg_query($query);
			if (pg_num_rows($result)) {
				$response['user'] = pg_fetch_row($result)[0];
			}
			pg_free_result($result);
			pg_close($link);
		}

	echo json_encode($response);
	}
}
