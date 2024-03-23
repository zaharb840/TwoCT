<?php 
    header('Content-Type: application/json');

    $json = array(
        'response' => "фумо"
    );

    echo(json_encode($json));
?>