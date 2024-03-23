<?php
/**
 * This PHP script handles the API endpoint for user profile
 */

// Include necessary files
require_once "../../include/config.php"; // Include config file
include '../../include/user.php'; // Include user helper file

// Set the content type of the response
header('Content-Type: application/json');

// Initialize the JSON response array
$json = array();

// Get the user ID from the access token
$user_id = token_data($_REQUEST['token'])['id']; 

/**
 * Main logic of the script
 */

// Check if the user is authorized
if(token_data($_REQUEST['token'])['error'] == 0){
    // Query the user data from the database
    $user_data = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM users where id = "' .(int)$user_id. '"'));

    // Check if the user is not banned
    if($user_data['ban'] != 1){
        // Initialize the JSON response array
        $json = [ 
            'id' => (int)$user_data['id'], // User ID
            'name' => $user_data['name'], // User name
            'email' => $user_data['email'], // User email
            'description' => $user_data['descr'], // User description
            'privilege' => $user_data['priv'], // User privilege
            'wall_enable' => $user_data['yespost'], // Wall enable status
            'img50' => $url . substr($user_data['img50'], 2), // User image (50px)
            'img100' => $url . substr($user_data['img100'], 2), // User image (100px)
            'img200' => $url . substr($user_data['img200'], 2), // User image (200px)
            'img400' => $url . substr($user_data['img'], 2) // User image (400px)
        ];

        // Check if the user has a profile image
        if(empty($user_data['img'])){
            // Set the default image URLs if the user has no profile image
            $json['img50'] = $url . "/imgs/blankimg.jpg"; // Default image (50px)
            $json['img100'] = $url . "/imgs/blankimg.jpg"; // Default image (100px)
            $json['img200'] = $url . "/imgs/blankimg.jpg"; // Default image (200px)
            $json['img400'] = $url . "/imgs/blankimg.jpg"; // Default image (400px)
        }

    } else {
        // Query the ban data if the user is banned
        $bandata = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM banlist WHERE user_id = ' .(int)$user_data['id']));

        // Initialize the JSON response array with ban data
        $json = [ 
            'id' => (int)$user_data['id'], // User ID
            'email' => $user_data['email'], // User email
            'name' => $user_data['name'], // User name
            'ban_reason' => $bandata['reason'] // Ban reason
        ];
    }

} else {
    // If the user is not authorized, set the error response
    $json['error_code'] = 5;
    $json['error_msg'] = "User authorization failed: no access_token passed.";
}

// Encode the JSON response and output it
echo(json_encode($json));

// Close the database connection
mysqli_close($db);
