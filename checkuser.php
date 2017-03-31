<?php
if(empty($_SERVER['CONTENT_TYPE'])) {
	$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$user = isset($_POST['user']) ? $_POST['user'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;

if($user != null || $email != null) {
	$access = true;
	include_once('config.php');
	include_once('checker.php');
	if ($dbtype == 'm') {
		$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname) or die('Cannot connect to the DB');
	}
	else if ($dbtype == 'p') {
		$link = pg_connect("host=$dbaddr dbname=$dbname");
	}

	$availuser = isset($user) ? check($dbtype, $user, $link) : null;
	$availemail = isset($email) ? check($dbtype, $email, $link, 'email') : null;

	if ($dbtype == 'm') {
		$link->close();
	}
	else if ($dbtype == 'p') {
		pg_close($link);
	}

	header('Content-type: application/json');
	$available = array();
	if (isset($availuser)) {
		$available['user'] = $availuser;
	}
	if (isset($availemail)) {
		$available['email'] = $availemail;
	}
	echo json_encode($available);
}
