<?php

    session_start();

    if(!isset($_SESSION["watch_a_save_user_id"])){

        return;

    }

    include_once("./lib/controller/handle_users.php");

    $handle_users = new handle_users();

    $data = $handle_users->get_most_tvshows_genres_watched($_SESSION["watch_a_save_user_id"]);

    echo json_encode($data);


?>