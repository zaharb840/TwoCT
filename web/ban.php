<?php

/**
 * This file is part of TwoConnect project.
 *
 * @file ban.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

/**
 * This script handles the display of a banned user account.
 * It checks if the user is banned and displays the reason for the ban,
 * or a generic message if no reason is given.
 * If the user is not banned, it redirects them to the index page.
 */

require_once '../include/config.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$user = mysqli_fetch_assoc(mysqli_query($db, "SELECT ban FROM users WHERE id = $userId"));

if ($user['ban'] != 1) {
    $_SESSION['user']['ban'] = 0;
    header('Location: index.php');
    exit();
}

$banData = mysqli_fetch_assoc(mysqli_query($db, "SELECT reason FROM banlist WHERE user_id = $userId"));

?>

<!DOCTYPE html>
<html>
<head>
    <?php include '../include/html/head.php'; ?>
    <title>Бан аккаунта</title>
</head>
<body>
    <div class="main_app">
        <div class="main">
            <h1>Вы были забанены в <?php echo $sitename; ?></h1>
            <?php if (!empty($banData['reason'])): ?>
                <h3>По причине: <?php echo $banData['reason']; ?></h3>
            <?php else: ?>
                <h3>По не указанной причине</h3>
            <?php endif; ?>
            <h3>На всегда</h3>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($db);
?>