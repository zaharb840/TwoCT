<?php

/**
 * This file is part of TwoConnect project.
 *
 * @file register.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

/*
 * This file handles the registration process.
 * It requires the config.php file for database connection.
 */

require_once "../include/config.php";

/**
 * Redirects to the homepage if user is already logged in.
 */
if(isset($_SESSION['user'])) {
    header("Location: $url");
}

/**
 * Query to check if email already exists in the database.
 * @var string $checkemail
 */
$checkemail = 'SELECT email FROM users WHERE email = "' .$_POST['email']. '"';

/**
 * Query to check if user already exists in the database.
 * @var string $checkip
 */
$checkip = 'SELECT ip FROM users WHERE ip = "' .$_SERVER['REMOTE_ADDR']. '"';

/**
 * Query to create a new user in the database.
 * @var string $createacc
 */
$createacc = 'INSERT INTO users(name, email, pass, ip, descr) VALUES (
    "' .mysqli_real_escape_string($db, $_POST['username']). '", 
    "' .mysqli_real_escape_string($db, $_POST['email']). '", 
    "' .password_hash($_POST['pass'], PASSWORD_DEFAULT). '", 
    "' .$_SERVER['REMOTE_ADDR']. '", 
    "' .mysqli_real_escape_string($db, $_POST['descr']). '"
)';

/**
 * Checks if user wants to register and validates the form data.
 */
if(isset($_POST['do_signup'])) {
    // Check if username is empty
    if(empty(trim($_POST['username']))) {
        $text = 'Введите свой ник!';
    }

    // Check if email is empty
    if(empty(trim($_POST['email']))) {
        $text = 'Введите свою email почту!';
    }

    // Check if password is empty
    if(empty(trim($_POST['pass']))) {
        $text = 'Введите свой пароль!';
    }	

    // Check if repeated password matches
    if($_POST['pass2'] != $_POST['pass'] ) {
        $text = 'Повторный пароль введён неверно!';
    }

    // Check if email is valid
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $text = 'email почта не является email почтой';
    }

    // Check if email already exists in the database
    if((mysqli_num_rows(mysqli_query($db, $checkemail))) != 0) {
        $text = 'Email почта занятя!';
    }	

    // Check if user already exists in the database
    if(mysqli_num_rows(mysqli_query($db, $checkip)) != 0) {
        $text = 'Вы уже зарегистрированы!';
    }

    // Create new user if form data is valid
    if(empty(trim($text))) {
        if(mysqli_query($db, $createacc)) {
            $text = 'Вы успешно зарегистрированы';
        } else {
            $text = 'Ошибка сервера';
        }
    }
}

?>

<html>
	<head>
		<?php include '../include/html/head.php'; ?>
		<title>Регистрация</title>
	</head>
	<body>
		<?php include '../include/html/header.php'; ?>
		<div class="main_app">
			<div class="main">
				<form action="register.php" method="POST">
					<p>
						<p>Ваш ник:</p>
						<input type="text" name="username" maxlength="50" value="<?php echo $_POST['username']; ?>">
					</p>
					<p>
						<p>Ваша электронная почта:</p>
						<input type="email" name="email" value="<?php echo $_POST['email']; ?>">
					</p>
					<p>
						<p>Ваш пароль:</p>
						<input type="password" name="pass" maxlength="20" value="<?php echo $_POST['pass']; ?>">
					</p>
					<p>
						<p>Повторите ваш пароль:</p>
						<input type="password" name="pass2">
					</p>
					<p>
						<p>Описание вашего аккаунта:</p>
						<textarea name="descr"></textarea>
					</p>
					<p>
						<button type="submit" name="do_signup">Зарегестрироваться</button>
					</p>
				</form>
				<p><?php echo($text); ?></p><br>
				<p>При регистрации прочитайте <a href="<?php echo($url); ?>/web/terms.php">пользовательское соглашение</a> <?php echo($sitename); ?></p>
			</div>
		</div>
		<?php include "../include/html/footer.php" ?>
	</body>
</html>
<?php mysqli_close($db);