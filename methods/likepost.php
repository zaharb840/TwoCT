<?php
/**
 * Handle like/unlike request for a post.
*/
require_once "../include/config.php";
include '../include/user.php';

// Prepare SQL queries
$like = 'INSERT INTO likes (post_id, user_id) VALUES ('.(int)$_GET['id'].', '.$_SESSION['user']['user_id'].')';
$unlike = 'DELETE FROM likes WHERE post_id = '.(int)$_GET['id'].' AND user_id = '.$_SESSION['user']['user_id'];

// Fetch like data for the user on the post
$likeinf = mysqli_query($db, 'SELECT * FROM likes WHERE post_id = '.(int)$_GET['id'].' AND user_id = '.$_SESSION['user']['user_id']);
$likedata = mysqli_fetch_assoc($likeinf);

// Check if the user has a valid token and if the user is liking/unliking the post
if(token_data($_SESSION['user']['access_token'])['error'] == 0){
	// If the user has liked the post
	if(!empty($likedata)){
		mysqli_query($db, $unlike); // Remove like from the database
		header("Location: ".$_SERVER['HTTP_REFERER']."#post".(int)$_GET['id']); // Redirect back to the post
	}
	// If the user has not liked the post
	elseif(empty($likedata)){
		mysqli_query($db, $like); // Add like to the database
		header("Location: ".$_SERVER['HTTP_REFERER']."#post".(int)$_GET['id']); // Redirect back to the post
	}
} 
// If the user has an error, set the HTTP response code to 400 and set the error message
else {
	http_response_code(400);
	$error = "Bad request / token";
}

// Output the error message
echo($error);
