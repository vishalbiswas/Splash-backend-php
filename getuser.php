<?php
$uid = isset($_POST['uid']) ? $_POST['uid'] : null;

if ($uid != null) {
	$access = true;
	include_once('config.php');
	$link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname) or die('Cannot connect to the DB');

	$query = "SELECT users.username FROM users WHERE users.uid = $uid";
	$result = $link->query($query) or die ('Errant query');

	header('Content-type: application/json');
	$response = array();
	$response['uid'] = $uid;

	if ($result->num_rows) {
		$response['user'] = $result->fetch_row()[0];
	} 

	$result->free();
	$link->close();

	echo json_encode($response);
}