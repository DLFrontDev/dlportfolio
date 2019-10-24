<?php
	//Config PHP file
	$user = "root";
	$pwd = "root";
	$server = "localhost";
	$bdschema = "green_oracle_db";

	$connection = mysqli_connect($server, $user, $pwd, $bdschema);

	if (mysqli_connect_error()) {
		echo "Connection Error!";
		exit;
	}

	mysqli_set_charset($connection,"utf8");
	
	//Check for session persist Cookie
	if (isset($_COOKIE['remember_session']) && !isset($_SESSION['username'])) {
		$_SESSION['username']=$_COOKIE['remember_session'];
	}
?>