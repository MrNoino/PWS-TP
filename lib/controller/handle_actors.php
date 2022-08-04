<?php

require_once("lib/model/db_handler.php");
require_once("lib/model/user.php");

class handle_actors
{

    private int $code;

    public function get_code(): int
    {

        return $this->code;
        
    }

    public function add_actor($name, $biography, $birth_place, $birthdate, $gender, $photo){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("INSERT INTO watchasave.persons (person_name, person_birthdate, person_photo, person_gender_id)
                                    VALUES
                                    (:name, :birthdate, NULL, (SELECT gender_id FROM watchasave.genders WHERE gender_code = :gender_code));");

        $db_sta->bindValue(":name", $name);

        $db_sta->bindValue(":birthdate", $birthdate);

        $db_sta->bindValue(":gender_code", $gender);

        $db_sta->execute();

        $id = $db_conn->lastInsertId();

        if($photo != NULL){
            
            if(move_uploaded_file($photo["tmp_name"], "./resources/actors/" . $id . substr($photo["name"], strrpos($photo["name"], '.')))){

                $db_sta = $db_conn->prepare("UPDATE watchasave.persons
                                            SET person_photo = :photo
                                            WHERE person_id = (SELECT LAST_INSERT_ID());");

                $db_sta->bindValue(":photo", $id . substr($photo["name"], strrpos($photo["name"], '.')));

                $db_sta->execute();

            } 

        }

        $db_sta = $db_conn->prepare("INSERT INTO watchasave.actors (actor_person_id, actor_birthplace, actor_biography)
                                    VALUES 
                                    (:id, :birthplace, :biography);");

        $db_sta->bindValue(":id", $id);

        $db_sta->bindValue(":birthplace", $birth_place);

        $db_sta->bindValue(":biography", $biography);

        $db_sta->execute();

        $this->code = 200;

    }

}

?>