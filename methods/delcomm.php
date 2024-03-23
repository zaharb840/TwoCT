<?php
/**
 * Delete a comment from the database.
 */

// Require the configuration file and the user module
require_once "../include/config.php";
include '../include/user.php';

// Prepare the SQL query to delete the comment
$deleteQuery = 'DELETE FROM comments WHERE id = ' .(int)$_GET['id'];

// Select the comment data from the database
$postInfo = mysqli_query($db, 'SELECT * FROM comments WHERE id = ' .(int)$_GET['id']);
$postData = mysqli_fetch_assoc($postInfo);

// Select the user data from the database
$userData = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM users WHERE id = ' .$_SESSION['user']['user_id']));

// Check if the user has a valid token and if the user can delete the comment
if(token_data($_SESSION['user']['access_token'])['error'] == 0){
	if($postData['user_id'] == $_SESSION['user']['user_id'] or $userData['priv'] >= 2){
		// Execute the delete query and redirect to the referer
		if(mysqli_query($db, $deleteQuery)){
			header("Location: " .$_SERVER['HTTP_REFERER']);
		}
	}
} else {
	// If the user has an error, set the HTTP response code to 400 and set the error message
	http_response_code(400);
	$error = "Bad request / token";
}

// Output the error message
echo($error);
?>