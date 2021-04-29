<?php 

$host = getenv("MARIADB_SERVICE_HOST");
$port = getenv("MARIADB_SERVICE_PORT");
//$host = getenv("MYSQL_SERVICE_HOST");
//$port = getenv("MYSQL_SERVICE_PORT");
$user = getenv("databaseuser");
$pass = getenv("databasepassword");
$db = getenv("databasename");

$con = mysqli_connect($host, $user, $pass);

mysqli_select_db($con, "mariadb_eric");

if (!$con) {
	print ("Not Connected<br>".mysql_error());
}
else {
	echo ("Connected!");	
}
?>
