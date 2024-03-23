<?php

/**
 * This file is part of TwoConnect project.
 *
 * @file login.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

/**
 * This file handles the login functionality of the site.
 * It sends a POST request to the API to authenticate the user and stores
 * the user's data in the session if the authentication is successful.
 * Redirects the user to the homepage if the user is already logged in.
 */

// Load the configuration file
require_once "../include/config.php";

// Check if the user is already logged in
if(isset($_SESSION['user'])){
	// Redirect the user to the homepage
	header("Location: $url");
}  // end if

// Check if the form was submitted
if(isset($_POST['do_login'])){
	// Send a POST request to the API to authenticate the user
	$data = json_decode(file_get_contents($url. '/api/login.php?username=' .urlencode($_POST['username']). '&password=' .urlencode($_POST['password'])), true);

	// Check if the authentication was successful
	if(empty($data['error_code'])){
		// Store the user's data in the session
		$_SESSION['user'] = $data;

		// Redirect the user to the homepage
		header("Location: $url");
	}  // end if
	else {
		// Store the error message if the authentication failed
		$error = $data['error_msg'];
	}  // end else
}  // end if
?>

<html>
<head>
	<?php include '../include/html/head.php'; ?>
    <!-- Set the title of the page to "Вход" -->
    <title>Вход</title>
</head>
<body>
	<?php include '../include/html/header.php'; ?>
	<div class="main_app">
		<div class="main">
			<!-- Display the login form -->
			<form action="login.php" method="POST">
				<p>
					<p>Логин:</p>
					<input type="email" name="username">
				</p>
				<p>
					<p>Пароль:</p>
					<input type="password" name="password">
				</p>
				<p>
					<button type="submit" name="do_login">Войти</button>
				</p>
			</form>
			<!-- Display the error message if the authentication failed -->
			<p><?php echo($error); ?></p>
		</div>
	</div>
	<?php include "../include/html/footer.php" ?>
</body>
</html>
<?php mysqli_close($db);
