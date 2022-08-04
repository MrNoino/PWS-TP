<?php


require_once("lib/model/db_handler.php");
require_once("lib/model/user.php");

class handle_episodes{

    private int $code;

    public function get_code(): int
    {

        return $this->code;
        
    }

    public function get_watched($episode_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($episode_id, 0, 3) == "was"){

            $epsiode_id = substr($episode_id, 2, strlen($episode_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchedepisodes
                                        WHERE user_watched_episode_id = :episode_id and user_watched_episode_user_id = :user_id;");

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->bindValue(":user_id", $user_id);

        }else{

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchedepisodes 
                                        WHERE user_watched_episode_id = (SELECT episode_id FROM watchasave.episodes WHERE episode_api_id = :episode_api_id LIMIT 1) and user_watched_episode_user_id = :user_id;");

            $db_sta->bindValue(":episode_api_id", $episode_id);

            $db_sta->bindValue(":user_id", $user_id);

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $this->code = 200;

        return (bool) $db_sta->fetch();

    }

    public function set_watched($episode_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($episode_id, 0, 3) == "was"){

            $episode_id = substr($episode_id, 2, strlen($episode_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.episodes 
                                        WHERE episode_id = :episode_id;");

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() == 0){

                $this->code = 404;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchedepisodes (user_watched_episode_user_id, user_watched_episode_id)
                                        VALUES
                                        (:user_id, :episode_id");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }else{

            $episode_api_id = $episode_id;

            $db_sta = $db_conn->prepare("SELECT episode_id 
                                        FROM watchasave.episodes 
                                        WHERE episode_api_id = :episode_api_id;");

            $db_sta->bindValue(":episode_api_id", $episode_api_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $episode_id = $db_sta->fetch();

            if(!$episode_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.episodes (episode_api_id)
                                            VALUES
                                            (:episode_api_id);");

                $db_sta->bindValue(":episode_api_id", $episode_api_id);

                $db_sta->execute();

                $episode_id = $db_conn->lastInsertId();

            }

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchedepisodes 
                                        WHERE user_watched_episode_id = :episode_id and user_watched_episode_user_id = :user_id;");

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() != 0){

                $this->code = 400;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchedepisodes (user_watched_episode_user_id, user_watched_episode_id)
                                        VALUES
                                        (:user_id, :episode_id);");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }

    }

    public function unset_watched($episode_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($episode_id, 0, 3) == "was"){

            $episode_id = substr($episode_id, 2, strlen($episode_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT episode_id
                                        FROM watchasave.episodes 
                                        WHERE episode_api_id = :episode_api_id LIMIT 1;");

            $db_sta->bindValue(":episode_api_id", $episode_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $episode_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare("DELETE FROM watchasave.userwatchedepisodes 
                                    WHERE user_watched_episode_id = :episode_id and user_watched_episode_user_id = :user_id;");

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->bindValue(":episode_id", $episode_id);

        $db_sta->execute();

    }

    public function evaluate($episode_id, $user_id, $comment, $stars){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($episode_id, 0, 3) == "was"){

            $episode_id = substr($episode_id, 2, strlen($episode_id));

        }else{

            $episode_api_id = $episode_id;

            $db_sta = $db_conn->prepare("SELECT episode_id
            FROM watchasave.episodes 
            WHERE episode_api_id = :episode_api_id LIMIT 1;");

            $db_sta->bindValue(":episode_api_id", $episode_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $episode_id = $db_sta->fetch();

            if(!$episode_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.episodes (episode_api_id)
                                            VALUES
                                            (:episode_api_id);");

                $db_sta->bindValue(":episode_api_id", $episode_api_id);

                $db_sta->execute();

                $episode_id = $db_conn->lastInsertId();

            }

        }

        $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                    FROM watchasave.userepisodesevaluation 
                                    WHERE user_episode_evaluation_user_id = :user_id and user_episode_evaluation_episode_id = :episode_id;");

        $db_sta->bindValue(":episode_id", $episode_id);

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        if($db_sta->fetch() == 0){

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userepisodesevaluation (user_episode_evaluation_user_id, user_episode_evaluation_episode_id, user_episode_evaluation_comment, user_episode_evaluation_stars)
                                        VALUES
                                        (:user_id, :episode_id, :comment, :stars);");

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":comment", $comment);

            $db_sta->bindValue(":stars", $stars);

            $db_sta->execute();

        }else{

            $db_sta = $db_conn->prepare("UPDATE watchasave.userepisodesevaluation 
                                        SET user_episode_evaluation_comment = :comment, user_episode_evaluation_stars = :stars
                                        WHERE user_episode_evaluation_user_id = :user_id and user_episode_evaluation_episode_id = :episode_id;");

            $db_sta->bindValue(":episode_id", $episode_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":comment", $comment);

            $db_sta->bindValue(":stars", $stars);

            $db_sta->execute();

        }

        $this->code = 200;

    }

    public function get_evaluation($episode_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($episode_id, 0, 3) == "was"){

            $episode_id = substr($episode_id, 2, strlen($episode_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT episode_id
            FROM watchasave.episodes 
            WHERE episode_api_id = :episode_api_id LIMIT 1;");

            $db_sta->bindValue(":episode_api_id", $episode_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $episode_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare('SELECT user_episode_evaluation_comment as "Comment", user_episode_evaluation_stars as "Stars"
                                    FROM watchasave.userepisodesevaluation 
                                    WHERE user_episode_evaluation_user_id = :user_id and user_episode_evaluation_episode_id = :episode_id;');

        $db_sta->bindValue(":episode_id", $episode_id);

        $db_sta->bindValue(":user_id", $user_id);    
        
        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetch();

    }

    public function get_all_evaluations($episode_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($episode_id, 0, 3) == "was"){

            $episode_id = substr($episode_id, 2, strlen($episode_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT episode_id
            FROM watchasave.episodes 
            WHERE episode_api_id = :episode_api_id LIMIT 1;");

            $db_sta->bindValue(":episode_api_id", $episode_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $episode_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare('SELECT userepisodesevaluation.user_episode_evaluation_comment as "Comment", userepisodesevaluation.user_episode_evaluation_stars as "Stars", persons.person_name as "User"
                                    FROM watchasave.userepisodesevaluation
                                    INNER JOIN watchasave.persons
                                    ON persons.person_id = user_episode_evaluation_user_id
                                    WHERE user_episode_evaluation_episode_id = :episode_id;');

        $db_sta->bindValue(":episode_id", $episode_id);   
        
        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

}

?>