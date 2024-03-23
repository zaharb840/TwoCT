<?php
    require_once 'db.php';

    session_start();

    $url = 'http://' .$_SERVER['HTTP_HOST'] /* . '/onect' */;
    $sitename = 'OneConnect';
    $favicon = 'favicon.png';
    $style = 'md1';
    $lang = 'ru.php';
    $enable_antispam = true;
    $antispam = 60;
    $links = array(
        'Github' => 'https://github.com/zaharb840/TwoCT'
    );

    // Выполнение конфига

    include "../lang/$lang";

    $db = mysqli_connect(
        $dbconn['server'], 
        $dbconn['user'], 
        $dbconn['pass'], 
        $dbconn['db']
    );

    mysqli_set_charset($db,"utf8mb4");

    if($db == false){
        echo('Ошибка подключение базы данных');
        echo mysqli_connect_error();
    }
?>