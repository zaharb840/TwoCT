<?php
/**
 * This file is part of TwoConnect project.
 *
 * @file authors.php
 * @author KovshKomeij (https://github.com/KovshKomeij) and Zahar Ivanov (https://github.com/zaharb840)
 * @license BSD License
 *
 * @copyright 2024 KovshKomeji and Zahar Ivanov
 */

require_once "../include/config.php";

// Start HTML document
?>
<html>
<head>
	<?php include '../include/html/head.php'; ?>
    <!-- Set page title -->
    <title><?php echo($lang_authors); ?></title>
</head>
<body>
	<?php include '../include/html/header.php'; ?>
	<div class="main_app">
		<div class="main">
            <!-- Author 1 section -->
            <h2><?php echo($lang_authors1); ?></h2>
			<table class="user">
				<tr>
					<td><img class="img100" src="https://avatars.githubusercontent.com/u/85364286?v=4"></td>
					<td class="info">
						<a href="https://github.com/KovshKomeij">
							<h1>Дибоф (KovshKomeij или dibof228)</h1>
						</a>
					</td>
				</tr>
			</table>

            <!-- Author 2 section -->
            <h2><?php echo($lang_authors2); ?></h2>

            <!-- Fetch all author users from database -->
            <?php $allUsers = mysqli_query($db, 'SELECT id, name, priv, img200 FROM users WHERE priv > 1'); ?>
				
			<!-- Loop through each author user -->
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
										// Display author name
										echo(strip_tags($list['name']).' ');

										// Display verification badge if author is verified
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

            <!-- Author 3 section -->
            <h2><?php echo($lang_authors3); ?></h2>
		</div>
	</div>
	<?php include "../include/html/footer.php" ?>
</body>
</html>

<?php mysqli_close($db);
