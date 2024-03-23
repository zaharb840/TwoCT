<?php
/**
 * Pin/unpin a post.
 *
 * This script handles pinning and unpinning a post.
*/

require_once "../include/config.php";
include '../include/user.php';

// SQL query to pin a post
$pin = 'UPDATE post SET pin = 1 WHERE id = ' .(int)$_GET['id'];

// SQL query to unpin a post
$unpin = 'UPDATE post SET pin = 0 WHERE id = ' .(int)$_GET['id'];

// Retrieve post data from the database
$postinf = mysqli_query($db, 'SELECT * FROM post WHERE id = ' .(int)$_GET['id']);
$postdata = mysqli_fetch_assoc($postinf);

// Retrieve user data from the database
$user_data = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM users WHERE id = ' .$_SESSION['user']['user_id']));

// Check if the user is allowed to pin/unpin the post
if(token_data($_SESSION['user']['access_token'])['error'] == 0){
	if($postdata['id_user'] or $postdata['id_who'] == $_SESSION['user']['user_id'] or $user_data['priv'] >= 2){
		// Pin the post if it's not already pinned
		if($postdata['pin'] == 1){
			mysqli_query($db, $unpin);
			header("Location: " .$_SERVER['HTTP_REFERER']);
		} elseif($postdata['pin'] == 0){
			mysqli_query($db, $pin);
			header("Location: " .$_SERVER['HTTP_REFERER']);
		}
	}
} else {
	http_response_code(400);
	$error = "Bad request / token";
}

echo($error);
