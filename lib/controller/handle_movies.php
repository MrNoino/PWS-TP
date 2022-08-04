<?php

require_once("lib/model/db_handler.php");
require_once("lib/model/user.php");

class handle_movies
{

    private int $code;

    public function get_code(): int
    {

        return $this->code;
        
    }

    public function get_watched($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = null;

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchedmovies 
                                        WHERE user_watched_movie_id = :movie_id and user_watched_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

        }else{

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchedmovies 
                                        WHERE user_watched_movie_id = (SELECT movie_id FROM watchasave.movies WHERE movie_api_id = :movie_api_id LIMIT 1) and user_watched_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $this->code = 200;

        return (bool) $db_sta->fetch();

    }

    public function set_watched($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.movies 
                                        WHERE movie_id = :movie_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() == 0){

                $this->code = 404;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchedmovies (user_watched_movie_user_id, user_watched_movie_id)
                                        VALUES
                                        (:user_id, :movie_id");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }else{

            $movie_api_id = $movie_id;

            $db_sta = $db_conn->prepare("SELECT movie_id 
                                        FROM watchasave.movies 
                                        WHERE movie_api_id = :movie_api_id;");

            $db_sta->bindValue(":movie_api_id", $movie_api_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

            if(!$movie_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.movies (movie_api_id)
                                            VALUES
                                            (:movie_api_id);");

                $db_sta->bindValue(":movie_api_id", $movie_api_id);

                $db_sta->execute();

                $movie_id = $db_conn->lastInsertId();

            }

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchedmovies 
                                        WHERE user_watched_movie_id = :movie_id and user_watched_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() != 0){

                $this->code = 400;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchedmovies (user_watched_movie_user_id, user_watched_movie_id)
                                        VALUES
                                        (:user_id, :movie_id);");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }

    }

    public function unset_watched($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT movie_id
                                        FROM watchasave.movies 
                                        WHERE movie_api_id = :movie_api_id LIMIT 1;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare("DELETE FROM watchasave.userwatchedmovies 
                                    WHERE user_watched_movie_id = :movie_id and user_watched_movie_user_id = :user_id;");

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->bindValue(":movie_id", $movie_id);

        $db_sta->execute();

    }

    public function get_seelater($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = null;

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchlatermovies 
                                        WHERE user_watch_later_movie_id = :movie_id and user_watch_later_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

        }else{

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchlatermovies 
                                        WHERE user_watch_later_movie_id = (SELECT movie_id FROM watchasave.movies WHERE movie_api_id = :movie_api_id LIMIT 1) and user_watch_later_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $this->code = 200;

        return (bool) $db_sta->fetch();

    }

    public function set_seelater($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.movies 
                                        WHERE movie_id = :movie_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() == 0){

                $this->code = 404;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchlatermovies (user_watch_later_movie_user_id, user_watch_later_movie_id)
                                        VALUES
                                        (:user_id, :movie_id");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }else{

            $movie_api_id = $movie_id;

            $db_sta = $db_conn->prepare("SELECT movie_id 
                                        FROM watchasave.movies 
                                        WHERE movie_api_id = :movie_api_id;");

            $db_sta->bindValue(":movie_api_id", $movie_api_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

            if(!$movie_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.movies (movie_api_id)
                                            VALUES
                                            (:movie_api_id);");

                $db_sta->bindValue(":movie_api_id", $movie_api_id);

                $db_sta->execute();

                $movie_id = $db_conn->lastInsertId();

            }

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userwatchlatermovies 
                                        WHERE user_watch_later_movie_id = :movie_id and user_watch_later_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() != 0){

                $this->code = 400;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userwatchlatermovies (user_watch_later_movie_user_id, user_watch_later_movie_id)
                                        VALUES
                                        (:user_id, :movie_id);");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }

    }

    public function unset_seelater($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT movie_id
                                        FROM watchasave.movies 
                                        WHERE movie_api_id = :movie_api_id LIMIT 1;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare("DELETE FROM watchasave.userwatchlatermovies 
                                    WHERE user_watch_later_movie_id = :movie_id and user_watch_later_movie_user_id = :user_id;");

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->bindValue(":movie_id", $movie_id);

        $db_sta->execute();

    }

    public function get_favorite($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = null;

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userfavoritemovies 
                                        WHERE user_favorite_movie_id = :movie_id and user_favorite_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

        }else{

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userfavoritemovies 
                                        WHERE user_favorite_movie_id = (SELECT movie_id FROM watchasave.movies WHERE movie_api_id = :movie_api_id LIMIT 1) and user_favorite_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

        }

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $this->code = 200;

        return (bool) $db_sta->fetch();



    }

    public function set_favorite($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.movies 
                                        WHERE movie_id = :movie_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() == 0){

                $this->code = 404;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userfavoritemovies (user_favorite_movie_user_id, user_favorite_movie_id)
                                        VALUES
                                        (:user_id, :movie_id");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }else{

            $movie_api_id = $movie_id;

            $db_sta = $db_conn->prepare("SELECT movie_id 
                                        FROM watchasave.movies 
                                        WHERE movie_api_id = :movie_api_id;");

            $db_sta->bindValue(":movie_api_id", $movie_api_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

            if(!$movie_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.movies (movie_api_id)
                                            VALUES
                                            (:movie_api_id);");

                $db_sta->bindValue(":movie_api_id", $movie_api_id);

                $db_sta->execute();

                $movie_id = $db_conn->lastInsertId();

            }

            $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                        FROM watchasave.userfavoritemovies 
                                        WHERE user_favorite_movie_id = :movie_id and user_favorite_movie_user_id = :user_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            if($db_sta->fetch() != 0){

                $this->code = 400;

                return;

            }

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.userfavoritemovies (user_favorite_movie_user_id, user_favorite_movie_id)
                                        VALUES
                                        (:user_id, :movie_id);");

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->execute();

            $this->code = 200;
            
            return;

        }

    }

    public function unset_favorite($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT movie_id
                                        FROM watchasave.movies 
                                        WHERE movie_api_id = :movie_api_id LIMIT 1;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare("DELETE FROM watchasave.userfavoritemovies 
                                    WHERE user_favorite_movie_id= :movie_id and user_favorite_movie_user_id = :user_id;");

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->bindValue(":movie_id", $movie_id);

        $db_sta->execute();

    }

    public function evaluate($movie_id, $user_id, $comment, $stars){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

        }else{

            $movie_api_id = $movie_id;

            $db_sta = $db_conn->prepare("SELECT movie_id
            FROM watchasave.movies 
            WHERE movie_api_id = :movie_api_id LIMIT 1;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

            if(!$movie_id){

                $db_sta = $db_conn->prepare("INSERT INTO watchasave.movies (movie_api_id)
                                            VALUES
                                            (:movie_api_id);");

                $db_sta->bindValue(":movie_api_id", $movie_api_id);

                $db_sta->execute();

                $movie_id = $db_conn->lastInsertId();

            }

        }

        $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                    FROM watchasave.usermoviesevaluation 
                                    WHERE user_movie_evaluation_user_id = :user_id and user_movie_evaluation_movie_id = :movie_id;");

        $db_sta->bindValue(":movie_id", $movie_id);

        $db_sta->bindValue(":user_id", $user_id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        if($db_sta->fetch() == 0){

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.usermoviesevaluation (user_movie_evaluation_user_id, user_movie_evaluation_movie_id, user_movie_evaluation_comment, user_movie_evaluation_stars)
                                        VALUES
                                        (:user_id, :movie_id, :comment, :stars);");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":comment", $comment);

            $db_sta->bindValue(":stars", $stars);

            $db_sta->execute();

        }else{

            $db_sta = $db_conn->prepare("UPDATE watchasave.usermoviesevaluation 
                                        SET user_movie_evaluation_comment = :comment, user_movie_evaluation_stars = :stars
                                        WHERE user_movie_evaluation_user_id = :user_id and user_movie_evaluation_movie_id = :movie_id;");

            $db_sta->bindValue(":movie_id", $movie_id);

            $db_sta->bindValue(":user_id", $user_id);

            $db_sta->bindValue(":comment", $comment);

            $db_sta->bindValue(":stars", $stars);

            $db_sta->execute();

        }

        $this->code = 200;

    }

    public function get_evaluation($movie_id, $user_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT movie_id
            FROM watchasave.movies 
            WHERE movie_api_id = :movie_api_id LIMIT 1;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare('SELECT user_movie_evaluation_comment as "Comment", user_movie_evaluation_stars as "Stars"
                                    FROM watchasave.usermoviesevaluation 
                                    WHERE user_movie_evaluation_user_id = :user_id and user_movie_evaluation_movie_id = :movie_id;');

        $db_sta->bindValue(":movie_id", $movie_id);

        $db_sta->bindValue(":user_id", $user_id);    
        
        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetch();

    }

    public function get_all_evaluations($movie_id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        if(substr($movie_id, 0, 3) == "was"){

            $movie_id = substr($movie_id, 2, strlen($movie_id));

        }else{

            $db_sta = $db_conn->prepare("SELECT movie_id
            FROM watchasave.movies 
            WHERE movie_api_id = :movie_api_id LIMIT 1;");

            $db_sta->bindValue(":movie_api_id", $movie_id);

            $db_sta->execute();

            $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

            $movie_id = $db_sta->fetch();

        }

        $db_sta = $db_conn->prepare('SELECT usermoviesevaluation.user_movie_evaluation_comment as "Comment", usermoviesevaluation.user_movie_evaluation_stars as "Stars", persons.person_name as "User"
                                    FROM watchasave.usermoviesevaluation
                                    INNER JOIN watchasave.persons
                                    ON persons.person_id = user_movie_evaluation_user_id
                                    WHERE user_movie_evaluation_movie_id = :movie_id;');

        $db_sta->bindValue(":movie_id", $movie_id);   
        
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

    public function add_movie($title, $resume, $release_date, $duration, $original_language, $genres, $poster){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("INSERT INTO watchasave.movies (movie_title, movie_resume, movie_realesedate, movie_duration, movie_language_id, movie_poster)
                                    VALUES
                                    (:title, :resume, :release_date, :duration, (SELECT language_id FROM watchasave.languages WHERE language_code = :language), NULL);");


        $db_sta->bindValue(":title", $title);

        $db_sta->bindValue(":resume", $resume);

        $db_sta->bindValue(":release_date", $release_date);

        $db_sta->bindValue(":duration", $duration);

        $db_sta->bindValue(":language", $original_language);

        $db_sta->execute();

        $id = $db_conn->lastInsertId();

        if($poster != NULL){
            
            if(move_uploaded_file($poster["tmp_name"], "./resources/movies/" . $id . substr($poster["name"], strrpos($poster["name"], '.')))){

                $db_sta = $db_conn->prepare("UPDATE watchasave.movies
                                            SET movie_poster = :poster
                                            WHERE movie_id = (SELECT LAST_INSERT_ID());");

                $db_sta->bindValue(":poster", $id . substr($poster["name"], strrpos($poster["name"], '.')));

                $db_sta->execute();

            } 

        }

        foreach($genres as $genre){

            $db_sta = $db_conn->prepare("INSERT INTO watchasave.moviegenres
                                    VALUES
                                    (:genre, :movie);");

            $db_sta->bindValue(":genre", $genre);

            $db_sta->bindValue(":movie", $id);

            $db_sta->execute();

        }

        $this->code = 200;


    }

}

?>