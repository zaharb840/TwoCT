<?php 
/**
 * Retrieves user data based on the provided user ids.
 *
 * @throws Exception when there is an error in the database connection.
 * @return JSON encoded string containing the user data.
 */
require_once "../../include/config.php";

// Set the content type of the response to JSON
header('Content-Type: application/json');

/**
 * Array of user ids to retrieve data for.
 * @var array
 */
$user_ids = explode(',', $_REQUEST['ids']);

/**
 * Array to store the retrieved user data.
 * @var array
 */
$json = ['users' => []];

/**
 * Loop through each user id and retrieve data.
 */
for($i=0; $i <= count($user_ids) - 1; $i++){
    // Retrieve user data from the database
    $user_data = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM users where id = "' .(int)$user_ids[$i]. '"'));
    
    // If user data exists, add it to the JSON array
    if(!empty($user_data)){
        $json['users'][$i] = [ 
            'id' => (int)$user_data['id'],
            'name' => $user_data['name'],
            'description' => $user_data['descr'],
            'privilege' => $user_data['priv'],
            'wall_enable' => $user_data['yespost'],
            'img50' => $url . substr($user_data['img50'], 2),
            'img100' => $url . substr($user_data['img100'], 2),
            'img200' => $url . substr($user_data['img200'], 2),
            'img400' => $url . substr($user_data['img'], 2)
        ];
        
        // If user has no image, set default image URLs
        if(empty($user_data['img'])){
            $json['users'][$i]['img50'] = $url . "/imgs/blankimg.jpg";
            $json['users'][$i]['img100'] = $url . "/imgs/blankimg.jpg";
            $json['users'][$i]['img200'] = $url . "/imgs/blankimg.jpg";
            $json['users'][$i]['img400'] = $url . "/imgs/blankimg.jpg";
        }
    } 
    
    // If user data does not exist, add a 'unknown' user to the JSON array
    if(empty($user_data)){
        $json['users'][$i] = [ 
            'id' => (int)$user_ids[$i],
            "name" => "unknown"
        ];
    } 

    // If user is banned, add ban reason to the JSON array
    if($user_data['ban'] == 1){
        $bandata = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM banlist WHERE user_id = ' .(int)$user_ids[$i]));

        $json['users'][$i]['ban_reason'] = $bandata['reason'];
    }
}

// Output the JSON encoded string
echo(json_encode($json));

// Close the database connection
mysqli_close($db);
