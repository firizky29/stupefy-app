<?php

    session_start();
    if(!isset($_SESSION['total_played'])) {
        $_SESSION['total_played'] = 0;
    }

    require_once __DIR__ . '/../app/controllers/AuthController.php';

    $nav = file_get_contents('./html/template/authorized-navbar.html');
    $sidebar = file_get_contents('./html/template/user-sidebar.html');
    $body = file_get_contents('./html/premium-song.html');

    $user = getUserInformation();

    if(isset($_GET['id']) && isset($user) && !$user['isAdmin']){
        $role = 'User';
        if($user['isAdmin']){
            $sidebar = file_get_contents('./html/template/admin-sidebar.html');
            $role = 'Admin';
        }
        $nav = str_replace('{{ user }}', $user['username'], $nav);
        $nav = str_replace('{{ role }}', $role , $nav);
        $body = str_replace('{{ nav }}', $nav, $body);
        $body = str_replace('{{ sidebar }}', $sidebar, $body);
    } else {
        http_response_code(403);
        $body = file_get_contents('./html/403.html');
    }


    echo $body;


?>
