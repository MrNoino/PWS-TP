<?php

    session_start();

    echo json_encode([ "lang" => (isset($_SESSION["watch_a_save_lang"]) ? $_SESSION["watch_a_save_lang"] : "pt-PT")], JSON_FORCE_OBJECT);

?>