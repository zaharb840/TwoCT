<?php 
    require_once "../../include/config.php";
    include '../../include/user.php';

    header('Content-Type: application/json');

    $json = array(
        'authorized' => false
    );

    if(token_data($_REQUEST['token'])['error'] == 0){
        $json['authorized'] = true;
    }

    echo(json_encode($json));
    
    mysqli_close($db);
?>