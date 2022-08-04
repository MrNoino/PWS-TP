<?php

session_start();

include_once("lib/controller/handle_users.php");

if(isset($_GET["email"])){

    $handle_user = new handle_users();

    header("Content-type", "application/json");

    $exists = $handle_user->email_exists($_GET["email"]);

    if($exists){

        $message = ((isset($_SESSION["watch_a_save_lang"]) && $_SESSION["watch_a_save_lang"] == "en-US") ? "Email already registered" : "Email já registado");

    }else{

        $message = ((isset($_SESSION["watch_a_save_lang"]) && $_SESSION["watch_a_save_lang"] == "en-US") ? "Email valid" : "Email valido");

    }

    echo json_encode(["exists" => $handle_user->email_exists($_GET["email"]), "message" => $message]);

}

?>