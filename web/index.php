<?php

/**
 * This file is part of TwoConnect project.
 *
 * @file index.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

/**
 * This is the index.php file, the main file of the web directory.
 * It handles the logic for the home page of the site.
 * It redirects logged in users to their user page or ban page,
 * and handles the display of the welcome message for non-logged in users.
 */

require_once "../include/config.php";

// Check if user is logged in
if(isset($_SESSION['user'])){

    // Fetch user data from database
    $all = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM users WHERE id = ' .(int)$_SESSION['user']['user_id']));

    // Check if user is banned
    if($all['ban'] == 1){

        // Set the ban flag in the session and redirect to the ban page
        $_SESSION['user']['ban'] = 1;
        header("Location: ban.php");
    } else { 
        // Redirect to the user page
        header("Location: user.php?id=" .$_SESSION['user']['user_id']);
    }
}
?>

<html>
<head>
	<?php include "../include/html/head.php" ?>
	<!-- Include the head.php file to generate the head HTML -->
    <title><?php echo($sitename); ?></title>
</head>
<body>
	<?php include '../include/html/header.php'; ?>
	<!-- Include the header.php file to generate the header HTML -->
	<div class="main_app">
		<div class="main">
			<?php echo($lang_welcome); ?>
		</div>
	</div>
	<?php include "../include/html/footer.php" ?>
	<!-- Include the footer.php file to generate the footer HTML -->
</body>
</html>
