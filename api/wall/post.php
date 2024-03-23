<?php 
/**
 * This PHP script handles the API endpoint for posting a message to a wall.
 */

// Include the config file
require_once "../../../include/config.php";

// Include the user helper file
include '../../../include/user.php';

// Set the content type of the response
header('Content-Type: application/json');

// Get the user ID from the access token
$user_id = token_data($_REQUEST['token'])['id']; 

// Query the owner's post settings
$owner_id = mysqli_query($db, 'SELECT yespost FROM users WHERE id =' .(int)$_REQUEST['owner_id']);

/**
 * Main logic of the script
 */
if(token_data($_REQUEST['token'])['error'] == 0){

    // Initialize the JSON response array
    $json = array();

    // Check if antispam is enabled
    if($enable_antispam == true){
        // Get the recent post of the user
        $recent = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM post WHERE id_who =  " .(int)$user_id. " ORDER BY date DESC"));

        // Calculate the time difference between the current time and the recent post time
        $date = round((time() - $recent['date']));
    }

    // Check if the 'message' parameter is empty
    if(empty(trim(strip_tags($_REQUEST['message'])))){
        $json['error_code'] = 100;
        $json['error_msg'] = "Required parameter 'message' missing.";
    }  

    // Check if the user has permission to post
    if((int)$_REQUEST['owner_id'] != $user_id){
        if(mysqli_fetch_assoc($owner_id)['yespost'] == 0) {
            $json['error_code'] = 15;
            $json['error_msg'] = "Access denied";
        }  
    }
    
    // Check if the 'owner_id' parameter is empty
    if(empty(trim($_REQUEST['owner_id']))){
        $json['error_code'] = 100;
        $json['error_msg'] = "Required parameter 'owner_id' missing.";
    }  

    // Check if antispam is enabled and the user is rate limited
    if($enable_antispam == true){
        if($date <= $antispam){
            $json['error_code'] = 29;
            $json['error_msg'] = "You have been rate limited.";
        }  
    }

    // If there are no errors, insert the post into the database
    if(empty(trim($json['error_code']))){
        $post = "INSERT INTO post(id_user, id_who, post, date) VALUES (
				'" .(int)$_REQUEST['owner_id']. "',
				'" .(int)$user_id. "',
				'" .mysqli_real_escape_string($db, strip_tags($_REQUEST['message'])). "',
                '" .time(). "'
			)";

        if(mysqli_query($db, $post)){
            // Get the ID of the newly inserted post
            $recent = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM post WHERE id_who =  " .(int)$user_id. " ORDER BY date DESC"));

            // Prepare the JSON response
            $json = array(
                'response' => array(
                    "post_id" => (int)$recent['id']
                )
            );
        }
    }
} else {
    // If the user authorization failed, set the error response
    $json['error_code'] = 5;
    $json['error_msg'] = "User authorization failed: no access_token passed.";
}

// Encode the
