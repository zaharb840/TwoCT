<?php

/**
 * This file is part of TwoConnect project.
 *
 * @file pass.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

/**
 * This file handles the change of user password
 */

require_once "../include/config.php";

// Check if the user is logged in, redirect to login page if not
if(empty($_SESSION['user'])) {
    header("Location: login.php");
}

// Prepare the SQL query to update the user's password
$change = "UPDATE users SET pass = '" .password_hash($_POST['pass'], PASSWORD_DEFAULT). "' WHERE id = '" .$_SESSION['user']['user_id']. "'";

// Fetch the user's current password from the database
$user = mysqli_fetch_assoc(mysqli_query($db, 'SELECT pass FROM users where id = ' .(int)$_SESSION['user']['user_id']));

// Check if the form is submitted
if(isset($_POST['do_change'])) {
    // Check if the old password is correct
    if(!password_verify($_POST['oldpass'], $user['pass'])) {
        $error = 'Старый пароль не верный!';
    }

    // Check if the new passwords match
    if($_POST['pass'] != $_POST['pass2']) {
        $error = '2 пароль не верный';
    }

    // Check if the new password is empty
    if(empty(trim($_POST['pass']))) {
        $error = 'Пароль пустой';
    }
    
    // If there are no errors, update the password and redirect to the logout page
    if(empty($error)) {
        mysqli_query($db, $change);
        header("Location: logout.php");
    }
}
?>
<html>
<head>
	<?php include '../include/html/head.php'; ?>
	<!-- Page title -->
    <title>Изменение аккаунта</title>
</head>
<body>
	<?php include '../include/html/header.php'; ?>
	<!-- Main application container -->
	<div class="main_app">
		<!-- Main content container -->
		<div class="main">
			<!-- Title -->
			<h1>После смены пароля вы должны перезайти в аккаунт!</h1>
			<!-- Password change form -->
			<form action="pass.php" method="POST">
				<!-- Old password -->
				<p>
					<p>Старый Пароль: </p>
					<input type="password" name="oldpass">
				</p>
				<!-- New password -->
				<p>
					<p>Новый Пароль: </p>
					<input type="password" name="pass">
				</p>
				<!-- Repeat new password -->
				<p>
					<p>Повторите новый пароль:</p>
					<input type="password" name="pass2">
				</p>
				<!-- Change password button -->
				<p>
					<button type="submit" name="do_change">Изменить пароль</button>
				</p>
			</form>
			<!-- Error message -->
			<p><?php echo($error); ?></p>
		</div>
	</div>
	<!-- Footer -->
	<?php include "../include/html/footer.php" ?>
</body>
</html>
<?php mysqli_close($db);