<?php

session_start();

session_unset();

if(isset($_COOKIE[session_name()])){

    setcookie(session_name(), "", time() - 1000);

}

if(isset($_COOKIE["user_login"])){

    setcookie("user_login", "", time() - 1000);

}

session_destroy();

header("Location: ./index.php");