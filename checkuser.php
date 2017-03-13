<?php
$user = isset($_POST['user']) ? $_POST['user'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;

if($user != null || $email != null) {
	$access = true;
	include_once('config.php');
    include_once('checker.php');
	$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname) or die('Cannot connect to the DB');

	$availuser = isset($user) ? check($user, $link) : null;
	$availemail = isset($email) ? check($email, $link, 'email') : null;

	$link->close();

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
