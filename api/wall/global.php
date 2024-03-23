<?php
/**
 * This file handles the API request for the global wall
 * It requires config.php for database configuration,
 * and user.php for token_data function
 */

require_once "../../include/config.php";
include '../../include/user.php';

/**
 * Set the header to send JSON response
 */
header('Content-Type: application/json');

/**
 * Get the user_id from the access_token
 */
$user_id = token_data($_REQUEST['token'])['id']; 

/**
 * Check if the access_token is valid
 */
if(token_data($_REQUEST['token'])['error'] == 0){
    /**
     * Initialize the JSON response with an empty array of posts
     */
    $json = array(
        'posts' => array()
    );
    
    /**
     * Get the count and offset values from the request
     * Set default values if not present
     */
    if(empty(trim((int)$_REQUEST['count']))){
        $count = 10;
    } else {
        $count = (int)$_REQUEST['count'];
    }

    if(empty(trim((int)$_REQUEST['offset']))){
        $offset = 0;
    } else {
        $offset = (int)$_REQUEST['offset'];
    }

    /**
     * Fetch the posts from the database based on count and offset
     */
    $post_data = mysqli_query($db, 'SELECT * FROM post ORDER BY date DESC LIMIT ' .$count. ' OFFSET ' .$offset * $count);

    /**
     * Loop through the fetched posts and populate the JSON response
     */
    for($i=0; $list = mysqli_fetch_assoc($post_data); $i++){
        $likes = mysqli_num_rows(mysqli_query($db, 'SELECT * FROM likes WHERE post_id = ' .$list['id']));

        $json['posts'][$i] = [ 
            "id" => (int)$list['id'],
            "user_id" => (int)$list['id_user'],
            "by_id" => (int)$list['id_who'],
            "date" => (int)$list['date'],
            "text" => $list['post'],
            "can_del" => false,
            "can_pin" => false,
            "pin" => boolval((int)$list['pin']),
            "img" => $url . substr($list['img'], 2),
            "likes" => $likes,
        ];

        /**
         * Check if the user is authorized to delete or pin the post
         */
        if($list['id_user'] or $list['id_who'] == $user_id){
            $json['posts'][$i]['can_del'] = true;
            $json['posts'][$i]['can_pin'] = true;
        }
    }

} else {
    /**
     * If the access_token is not valid, return an error response
     */
    $json['error_code'] = 5;
    $json['error_msg'] = "User authorization failed: no access_token passed.";
}

/**
 * Send the JSON response and close the database connection
 */
echo(json_encode($json));

mysqli_close($db);
