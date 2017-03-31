<?php
$msg = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(empty($_SERVER['CONTENT_TYPE'])) {
		$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
	}

	$uid = isset($_POST['uid'])?$_POST['uid']:null;
	$profpic = isset($_FILES['profpic'])?file_get_contents($_FILES["profpic"]["tmp_name"]):null;
	$fname = isset($_POST['fname'])?$_POST['fname']:null;
	$lname = isset($_POST['lname'])?$_POST['lname']:null;
        if (isset($uid)) {
		$access = true;
		include_once('config.php');

		if (isset($profpic)) {
			$query = "UPDATE users SET users.fname='$fname', users.lname='$lname', users.profpic='$profpic' WHERE users.uid = '$uid'";
		} else {
			$query = "UPDATE users SET users.fname='$fname', users.lname='$lname' WHERE users.uid = '$uid'";
		}

		if ($dbtype == 'm') {
			$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
			$result = $link->query($query);
		} else if ($dbtype == 'p') {
			$link = pg_connect("host=$dbaddr dbname=$dbname");
			$result = pg_query($link, $query);
		}

		if ($result) {
			$msg['status'] = 0;
			$msg['msg'] = 'Update success';
		} else {
			$msg['status'] = 1;
			$msg['msg'] = 'Internal Error';
		}

		if ($dbtype == 'm') {
			$result->free();
			$link->close();
		} else if ($dbtype == 'p') {
			pg_free_result($result);
			pg_close($link);
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
