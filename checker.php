<?php
    if (isset($access) && $access) {
        function check($strvalue, $connection, $column = 'username') {
		  $query = "SELECT users.$column FROM users WHERE users.$column = '$strvalue'";
		  $result = $connection->query($query) or die ('Errant query');

		  if ($result->num_rows) {
		  	return false;
		  } else {
		  	return true;
		  }
		  $result->free();
	   }
    }
    else {
	header("HTTP/1.1 403 Forbidden");
	exit;
}