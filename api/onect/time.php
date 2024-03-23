<?php 
    header('Content-Type: application/json');

    $json = array(
        'unixtime' => time()
    );

    echo(json_encode($json));
?>