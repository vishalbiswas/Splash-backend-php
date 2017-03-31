php based backed webservice for https://github.com/vishalbiswas/Splash

To use this backend create a file name `config.php` with credentials of your mysql database backend.
```php
<?php
if (isset($access) && $access) {
	$dbaddr = 'localhost';
	$dbuser = 'root';
	$dbpass = 'password';
	$dbname = 'database name';
	$dbtype = 'm';
} else {
	header("HTTP/1.1 403 Forbidden");
	exit;
}

```

`$dbtype` refers to the SQL database used. Use 'm' for MySQL and 'p' for PostgreSQL.
Replace the relevant credentials with your own.
