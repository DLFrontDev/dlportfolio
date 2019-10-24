<?php
	session_start();

	//Clean Session array
	session_unset();

	//Destroy browser Session
	session_destroy();

	//Clear Remember Session Cookie
	setcookie('remember_session', '', time() - 3600, '/');

	//Redirect to previous page
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>