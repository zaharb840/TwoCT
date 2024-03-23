<?php

/**
 * This file is part of TwoConnect project.
 *
 * @file search.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

/**
 * This is the search page of the application. It allows users to search for other users.
 * It requires the user to be logged in.
 */

require_once "../include/config.php";

// Redirects to login page if user is not logged in
if(empty($_SESSION['user'])){
	header("Location: login.php");
}

?>

<html>
<head>
	<?php include '../include/html/head.php'; ?>
	<!-- Page title -->
    <title><?php echo($lang_search); ?></title>
</head>
<body>
	<?php include '../include/html/header.php'; ?>
	<!-- Main application content -->
	<div class="main_app">
		<div class="main">
			<!-- Search form -->
			<form action="search.php" method="get">
				<input type="text" placeholder="<?php echo($lang_search); ?>" value="<?php echo($_GET['q']) ?>" name="q">
			</form>
			
			<?php 
				// Query to get users whose name matches the search query
				if(isset($_GET['q'])){
					$allUsers = mysqli_query($db, 'SELECT id, name, priv, img200 FROM users WHERE name LIKE "%' .mysqli_real_escape_string($db, $_GET['q']). '%" ORDER BY id DESC');
				} else {
					// Query to get all users
					$allUsers = mysqli_query($db, 'SELECT id, name, priv, img200 FROM users ORDER BY id DESC');
				}
			?>

			<!-- Display the number of users found -->
			<p><?php echo($lang_find . mysqli_num_rows($allUsers) . $lang_find_users); ?></p>

			<!-- Loop through the users and display them -->
			<?php while($list = mysqli_fetch_assoc($allUsers)): ?>
				<table class="user">
					<tr>
						<?php if($list['img200'] != NULL): ?>
							<td><img class="img100" src="<?php echo($list['img200']); ?>"></td>
						<?php else: ?>
							<td><img class="img100" src="../imgs/blankimg.jpg"></td>
						<?php endif; ?>
						<td class="info">
							<a href="user.php?id=<?php echo($list['id']); ?>">
								<h1>
									<?php
										echo(strip_tags($list['name']).' ');

										// Display verification symbol if user is verified
										if ($list['priv'] >= 1){ 
											echo('<img src="../imgs/verif.gif">');
										}
									?>
								</h1>
							</a>
						</td>
					</tr>
				</table>
			<?php endwhile; ?>
		</div>
	</div>
	<!-- Footer -->
	<?php include "../include/html/footer.php" ?>
</body>
</html>

<?php mysqli_close($db);