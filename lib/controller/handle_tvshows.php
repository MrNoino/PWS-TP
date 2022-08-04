<?php

require_once("lib/model/db_handler.php");
require_once("lib/model/user.php");

class handle_tvshows
{

    private int $code;

    public function get_code(): int
    {

        return $this->code;
        
    }

    public function get_seelater($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = null;

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchlaterseries 
                                        WHERE user_watch_later_serie_id = :tvshow_id and user_watch_later_serie_user_id = :user_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

        }else{

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchlaterseries 
                                        WHERE user_watch_later_serie_id = (SELECT serie_id FROM watchasave.series WHERE serie_api_id = :tvshow_api_id LIMIT 1) and user_watch_later_serie_user_id = :user_id;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $this->code = 200;

        return (bool) $db_sta->fetch();

    }

    public function set_seelater($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.series 
                                        WHERE serie_id = :tvshow_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() == 0){

                $this->code = 404;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchlaterseries (user_watch_later_serie_user_id, user_watch_later_serie_id)
                                        VALUES
                                        (:user_id, :tvshow_id");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }else{

            $tvshow_api_id = $tvshow_id;

            $db_sta = $db_conn->prepare("SELECT serie_id 
                                        FROM watchasave.series 
                                        WHERE serie_api_id = :tvshow_api_id;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_api_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

            if(!$tvshow_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.series (serie_api_id)
                                            VALUES
                                            (:tvshow_api_id);");

                $db_sta->bindValue(":tvshow_api_id", $tvshow_api_id);

                $db_sta->execute();

                $tvshow_id = $db_conn->lastInsertId();

            }

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchlaterseries
                                        WHERE user_watch_later_serie_id = :tvshow_id and user_watch_later_serie_user_id = :user_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() != 0){

                $this->code = 400;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchlaterseries (user_watch_later_serie_user_id, user_watch_later_serie_id)
                                        VALUES
                                        (:user_id, :tvshow_id);");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }

    }

    public function unset_seelater($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT serie_id
                                        FROM watchasave.series 
                                        WHERE serie_api_id = :tvshow_api_id LIMIT 1;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare("DELETE FROM watchasave.userwatchlaterseries 
                                    WHERE user_watch_later_serie_id = :tvshow_id and user_watch_later_serie_user_id = :user_id;");

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->bindValue(":tvshow_id", $tvshow_id);

        $db_sta->execute();

    }

    public function get_favorite($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = null;

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userfavoriteseries
                                        WHERE user_favorite_serie_id = :tvshow_id and user_favorite_serie_user_id = :user_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

        }else{

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userfavoriteseries
                                        WHERE user_favorite_serie_id = (SELECT serie_id FROM watchasave.series WHERE serie_api_id = :tvshow_api_id LIMIT 1) and user_favorite_serie_user_id = :user_id;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $this->code = 200;

        return (bool) $db_sta->fetch();



    }

    public function set_favorite($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.series 
                                        WHERE serie_id = :tvshow_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() == 0){

                $this->code = 404;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userfavoriteseries (user_favorite_serie_user_id, user_favorite_serie_id)
                                        VALUES
                                        (:user_id, :tvshow_id");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }else{

            $tvshow_api_id = $tvshow_id;

            $db_sta = $db_conn->prepare("SELECT serie_id 
                                        FROM watchasave.series 
                                        WHERE serie_api_id = :tvshow_api_id;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_api_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

            if(!$tvshow_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.series (serie_api_id)
                                            VALUES
                                            (:tvshow_api_id);");

                $db_sta->bindValue(":tvshow_api_id", $tvshow_api_id);

                $db_sta->execute();

                $tvshow_id = $db_conn->lastInsertId();

            }

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userfavoriteseries 
                                        WHERE user_favorite_serie_id = :tvshow_id and user_favorite_serie_user_id = :user_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() != 0){

                $this->code = 400;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userfavoriteseries (user_favorite_serie_user_id, user_favorite_serie_id)
                                        VALUES
                                        (:user_id, :tvshow_id);");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }

    }

    public function unset_favorite($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT serie_id
                                        FROM watchasave.series 
                                        WHERE serie_api_id = :tvshow_api_id LIMIT 1;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare("DELETE FROM watchasave.userfavoriteseries 
                                    WHERE user_favorite_serie_id= :tvshow_id and user_favorite_serie_user_id = :user_id;");

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->bindValue(":tvshow_id", $tvshow_id);

        $db_sta->execute();

    }

    public function evaluate($tvshow_id, $user_id, $comment, $stars){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

        }else{

            $tvshow_api_id = $tvshow_id;

            $db_sta = $db_conn->prepare("SELECT serie_id
                                        FROM watchasave.series 
                                        WHERE serie_api_id = :tvshow_api_id LIMIT 1;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

            if(!$tvshow_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.series (serie_api_id)
                                            VALUES
                                            (:tvshow_api_id);");

                $db_sta->bindValue(":tvshow_api_id", $tvshow_api_id);

                $db_sta->execute();

                $tvshow_id = $db_conn->lastInsertId();

            }

        }

        $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                    FROM watchasave.userseriesevaluation 
                                    WHERE user_serie_evaluation_user_id = :user_id and user_serie_evaluation_serie_id = :tvshow_id;");

        $db_sta->bindValue(":tvshow_id", $tvshow_id);

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        if($db_sta->fetch() == 0){

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userseriesevaluation (user_serie_evaluation_user_id, user_serie_evaluation_serie_id, user_serie_evaluation_comment, user_serie_evaluation_stars)
                                        VALUES
                                        (:user_id, :tvshow_id, :comment, :stars);");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":comment", $comment);

            $db_sta->bindValue(":stars", $stars);

            $db_sta->execute();

        }else{

            $db_sta = $db_conn->prepare("UPDATE watchasave.userseriesevaluation 
                                        SET user_serie_evaluation_comment = :comment, user_serie_evaluation_stars = :stars
                                        WHERE user_serie_evaluation_user_id = :user_id and user_serie_evaluation_serie_id = :tvshow_id;");

            $db_sta->bindValue(":tvshow_id", $tvshow_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":comment", $comment);

            $db_sta->bindValue(":stars", $stars);

            $db_sta->execute();

        }

        $this->code = 200;

    }

    public function get_evaluation($tvshow_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT serie_id
                                        FROM watchasave.series 
                                        WHERE serie_api_id = :tvshow_api_id LIMIT 1;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare('SELECT user_serie_evaluation_comment as "Comment", user_serie_evaluation_stars as "Stars"
                                    FROM watchasave.userseriesevaluation 
                                    WHERE user_serie_evaluation_user_id = :user_id and user_serie_evaluation_serie_id = :tvshow_id;');

        $db_sta->bindValue(":tvshow_id", $tvshow_id);

        $db_sta->bindValue(":user_id", $user_id);    
        
        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetch();

    }

    public function get_all_evaluations($tvshow_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($tvshow_id, 0, 3) == "was"){

            $tvshow_id = substr($tvshow_id, 2, strlen($tvshow_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT serie_id
            FROM watchasave.series 
            WHERE serie_api_id = :tvshow_api_id LIMIT 1;");

            $db_sta->bindValue(":tvshow_api_id", $tvshow_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $tvshow_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare('SELECT userseriesevaluation.user_serie_evaluation_comment as "Comment", userseriesevaluation.user_serie_evaluation_stars as "Stars", persons.person_name as "User"
                                    FROM watchasave.userseriesevaluation
                                    INNER JOIN watchasave.persons
                                    ON persons.person_id = user_serie_evaluation_user_id
                                    WHERE user_serie_evaluation_serie_id = :tvshow_id;');

        $db_sta->bindValue(":tvshow_id", $tvshow_id);   
        
        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function get_all_languages(){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->query('SELECT language_code as "code", language_name as "name" FROM watchasave.languages;', PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function add_tvshow($name, $resume, $release_date, $original_language, $genres, $poster){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("INSERT INTO watchasave.series (serie_title, serie_resume, serie_releasedate, serie_language_id, serie_poster)
                                    VALUES
                                    (:title, :resume, :release_date, (SELECT language_id FROM watchasave.languages WHERE language_code = :language), NULL);");


        $db_sta->bindValue(":title", $name);

        $db_sta->bindValue(":resume", $resume);

        $db_sta->bindValue(":release_date", $release_date);

        $db_sta->bindValue(":language", $original_language);

        $db_sta->execute();

        $id = $db_conn->lastInsertId();

        if($poster != NULL){
            
            if(move_uploaded_file($poster["tmp_name"], "./resources/tvshows/" . $id . substr($poster["name"], strrpos($poster["name"], '.')))){

                $db_sta = $db_conn->prepare("UPDATE watchasave.series
                                            SET serie_poster = :poster
                                            WHERE serie_id = (SELECT LAST_INSERT_ID());");

                $db_sta->bindValue(":poster", $id . substr($poster["name"], strrpos($poster["name"], '.')));

                $db_sta->execute();

            } 

        }

        foreach($genres as $genre){

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.seriegenres
                                    VALUES
                                    (:genre, :serie);");

            $db_sta->bindValue(":genre", $genre);

            $db_sta->bindValue(":serie", $id);

            $db_sta->execute();

        }

        $this->code = 200;

    }

}