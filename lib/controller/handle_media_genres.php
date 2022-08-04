<?php

require_once("lib/model/db_handler.php");
require_once("lib/model/user.php");

class handle_media_genres{

    private int $code;

    public function get_code(): int
    {

        return $this->code;
        
    }

    public function get_all_genres(){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->query('SELECT media_genre_id  as "id", media_genre_description as "genre" FROM watchasave.mediagenres;', PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

}


?>