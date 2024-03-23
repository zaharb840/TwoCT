<?php 
    require_once "../../include/config.php";

    header('Content-Type: application/json');

    // Статистика

    $json['statistics'] = [
        'users_count' => mysqli_num_rows(mysqli_query($db, 'SELECT * FROM users')),
        'posts_count' => mysqli_num_rows(mysqli_query($db, 'SELECT * FROM post'))
    ];

    // Все админы

    $allUsers = mysqli_query($db, 'SELECT id, name, yespost FROM users WHERE priv > 1');
    
    while($user_data = mysqli_fetch_assoc($allUsers)){
        $json['administrators'][] = [
            'id' => (int)$user_data['id'],
            'name' => $user_data['name'],
        ];
    }

    // Ссылки
    
    foreach ($links as $name => $link){
        $json['links'][] = [
            'name' => $name,
            'url' => $link
        ];
    }

    echo(json_encode($json));
    
    mysqli_close($db);
?>