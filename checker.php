<?php
if (isset($access) && $access) {
	function check($dbtype, $strvalue, $connection, $column = 'username') {
		$query = "SELECT users.$column FROM users WHERE users.$column = '$strvalue'";
		if ($dbtype == 'm') {
			$result = $connection->query($query) or die ('Errant query');
		} else if ($dbtype == 'p') {
			$result = pg_query($connection, $query);
		}

		if (($dbtype == 'm' && $result->num_rows) || ($dbtype == 'p' && pg_num_rows($result))) {
			return false;
		} else {
			return true;
		}

		if ($dbtype == 'm') {
			$result->free();
		} else if ($dbtype == 'p') {
			pg_free_result($result);
		}
	}
}
else {
	header("HTTP/1.1 403 Forbidden");
	exit;
}
