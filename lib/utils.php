<?php

include_once("lib/controller/handle_users.php");

function is_email_valid(string $email):bool {
 
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 

    if (preg_match($regex, $email)) {

        return true;
    
    } else { 

        return false;

    }     

}

function is_logged():bool {

    return isset($_SESSION["watch_a_save_user_id"]);

}

function remember_user() {

    if(!isset($_SESSION["watch_a_save_user_id"]) && isset($_COOKIE["user_login"]) && !empty($_COOKIE["user_login"])){

        $handle_user = new handle_users();

        if($handle_user->exists_user($_COOKIE["user_login"])){

            $_SESSION["watch_a_save_user_id"] = $_COOKIE["user_login"];

        }

    }

}

function check_if_user_exists(){

    if(isset( $_SESSION["watch_a_save_user_id"])){

        $handle_user = new handle_users();

        if(!$handle_user->exists_user($_SESSION["watch_a_save_user_id"])){

            header("Location: ./logout.php");

        }

    }

}

?>