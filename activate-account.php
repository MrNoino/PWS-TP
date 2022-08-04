<?php

include_once("lib/controller/handle_users.php");

if(!isset($_GET["id"])){

    echo "Link inválido.";

}

$handle_user = new handle_users();

$handle_user->activate_account($_GET["id"]);

echo '<br><br><a href="index.php">Watchasave</a>'

?>