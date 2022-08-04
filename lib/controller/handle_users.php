<?php

require_once("lib/model/db_handler.php");
require_once("lib/model/user.php");

class handle_users
{

    private int $code;

    public function get_code(): int
    {

        return $this->code;
        
    }

    public function user_authentication(string $email, string $password): int
    {

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("SELECT user_person_id, user_password 
                            FROM watchasave.users
                            WHERE user_email = :email");

        $db_sta->bindValue(":email", $email);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        $result = $db_sta->fetch();

        if ($result == null || empty($result)) {
            
            $this->code = 404;
            return -1;

        }

        if(!password_verify($password, $result["user_password"])) {

            $this->code = 401;
            return -1;

        }

        $db_sta = $db_conn->prepare("SELECT userstate.userstate_description 
                            FROM watchasave.userstate
                            INNER JOIN watchasave.users
                            ON users.user_state_id = userstate.userstate_id
                            WHERE users.user_person_id = :id");

        $db_sta->bindValue(":id", $result["user_person_id"]);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        if($db_sta->fetch() == "Por Ativar"){

            //Not Acceptable
            $this->code = 406;
            return -1;

        }

        $this->code = 200;

        return $result["user_person_id"];

    }

    public function user_registration(string $name, string $email, string $password, $birthdate, $photo, string $gender_code)
    {

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("SELECT COUNT(*) FROM watchasave.users WHERE users.user_email = :email;");

        $db_sta->bindValue(":email", $email);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $result = $db_sta->fetch();

        if($result != 0){

            //409 = conflict
            $this->code = 409;

            return -1;

        }

        $db_sta = $db_conn->prepare("INSERT INTO watchasave.persons (person_name, person_birthdate, person_photo, person_gender_id)
                                    VALUES 
                                    (:name, :birthdate, NULL, (Select gender_id FROM watchasave.genders WHERE gender_code = :gender_code));");

        $db_sta->bindValue(":name", $name);

        $db_sta->bindValue(":birthdate", $birthdate);

        $db_sta->bindValue(":gender_code", $gender_code);

        $db_sta->execute();

        $db_sta = $db_conn->query("SELECT LAST_INSERT_ID();");

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $id = $db_sta->fetch();

        if($photo != NULL){
            
            if(move_uploaded_file($photo["tmp_name"], "./resources/users/" . $id . substr($photo["name"], strrpos($photo["name"], '.')))){

                $db_sta = $db_conn->prepare("UPDATE watchasave.persons
                                            SET person_photo = :photo
                                            WHERE person_id = (SELECT LAST_INSERT_ID());");

                $db_sta->bindValue(":photo", $id . substr($photo["name"], strrpos($photo["name"], '.')));

                $db_sta->execute();

            } 

        }

        $db_sta = $db_conn->prepare("INSERT INTO watchasave.users (user_email, user_password, user_person_id, user_state_id)
                                    VALUES 
                                    (:email, :password, (SELECT LAST_INSERT_ID()), (Select userstate.userstate_id FROM watchasave.userstate WHERE userstate_description = :desc));");

        $db_sta->bindValue(":email", $email);

        $db_sta->bindValue(":password", password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]));

        $db_sta->bindValue(":desc", "Por Ativar");

        $db_sta->execute();

        $this->code = 200;

        return $id;

    }

    public function get_user(int $id) {

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT persons.person_id as "id", persons.person_name as "name", users.user_email  as "email", genders.gender_code  as "gender_code", IFNULL(persons.person_birthdate, "")  as "birthdate", IFNULL(persons.person_photo, "") as "photo"
                                    FROM (( watchasave.persons
                                    INNER JOIN watchasave.genders
                                    ON persons.person_gender_id = genders.gender_id)
                                    INNER JOIN watchasave.users
                                    ON persons.person_id = users.user_person_id)
                                    WHERE persons.person_id = :id;');

        $db_sta->bindValue(":id", $id);
        
        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'user');

        $result = $db_sta->fetch();

        $this->code = 200;

        return $result;

    }

    public function update_user(int $id, string $name, string $birthdate, $photo, string $gender_code){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("UPDATE watchasave.persons 
                                    SET 
                                    person_name = :name, 
                                    person_birthdate = :birthdate, 
                                    person_photo = :photo,
                                    person_gender_id = (Select gender_id FROM watchasave.genders WHERE gender_code = :gender_code)
                                    WHERE
                                    person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->bindValue(":name", $name);

        $db_sta->bindValue(":birthdate", $birthdate);

        $files = scandir("./resources/users/");

        if($photo != NULL){
            

            if(move_uploaded_file($photo["tmp_name"], "./resources/users/" . $id . substr($photo["name"], strrpos($photo["name"], '.')))){

                $db_sta->bindValue(":photo", $id . substr($photo["name"], strrpos($photo["name"], '.')));

            }else{

                $db_sta->bindValue(":photo", NULL);

                foreach($files as $file){

                    if(substr($file, 0, strpos($file, ".")) == strval($id)){

                        unlink("./resources/users/" . $file);
                        break;

                    }

                }

            }

        }else{

            $db_sta->bindValue(":photo", $photo);

            foreach($files as $file){

                if(substr($file, 0, strpos($file, ".")) == strval($id)){

                    unlink("./resources/users/" . $file);
                    break;

                }

            }

        }

        $db_sta->bindValue(":gender_code", $gender_code);

        $db_sta->execute();

        $this->code = 200;

    }

    public function exists_user(int $id): bool{

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                    FROM watchasave.users 
                                    WHERE user_person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        return (bool) $db_sta->fetch(); 

    }

    public function delete_user(int $id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("SELECT person_photo
                                    FROM watchasave.persons 
                                    WHERE person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $result = $db_sta->fetch();


        if(!empty($result)){

            if(file_exists("./resources/users/$result")){

                unlink("./resources/users/$result");

            }

        }
        

        $db_sta = $db_conn->prepare("DELETE 
                                    FROM watchasave.users 
                                    WHERE user_person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta = $db_conn->prepare("DELETE 
                                    FROM watchasave.persons 
                                    WHERE person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $this->code = 200;

    }

    public function email_exists($email){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("SELECT COUNT(*) 
                                    FROM watchasave.users 
                                    WHERE user_email = :email;");

        $db_sta->bindValue(":email", $email);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        return (bool) $db_sta->fetch(); 

    }

    public function activate_account($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare("SELECT COUNT(*) FROM watchasave.users WHERE users.user_person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $result = $db_sta->fetch();

        if($result != 1){

            echo "Não é possível ativar a conta.";

            return;

        }

        $db_sta = $db_conn->prepare("SELECT userstate.userstate_description FROM watchasave.userstate INNER JOIN watchasave.users ON users.user_state_id = userstate.userstate_id WHERE users.user_person_id = :id;");

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_COLUMN, 0);

        $result = $db_sta->fetch();

        if($result == "Ativo"){

            echo "Conta já ativada.";
            return;

        }else if($result != "Por Ativar"){

            echo "Não é possível ativar a conta.";

            return;

        }

        $db_sta = $db_conn->prepare("Update watchasave.users SET user_state_id = (SELECT userstate_id FROM watchasave.userstate WHERE userstate_description = :desc) WHERE user_person_id = :id;");

        $db_sta->bindValue(":desc", "Ativo");
        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        echo "Conta ativada com sucesso.";

    }

    public function get_most_tvshows_genres_watched($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT Distinct mediagenres.media_genre_description as "genre", COUNT(userwatchedepisodes.user_watched_episode_user_id) as "total"
                                        FROM watchasave.mediagenres
                                        INNER JOIN watchasave.seriegenres
                                        ON seriegenres.serie_media_genre_id = mediagenres.media_genre_id
                                        INNER JOIN watchasave.series
                                        ON series.serie_id = seriegenres.serie_genre_serie_id
                                        INNER JOIN watchasave.seasons
                                        ON seasons.season_serie_id = series.serie_id
                                        INNER JOIN watchasave.episodes
                                        ON episodes.episode_season_id = seasons.season_id
                                        INNER JOIN watchasave.userwatchedepisodes
                                        ON userwatchedepisodes.user_watched_episode_id = episodes.episode_id
                                        WHERE userwatchedepisodes.user_watched_episode_user_id = :id;');

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function get_watched_movies($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT movie_api_id as "id"
                                    FROM watchasave.movies
                                    INNER JOIN watchasave.userwatchedmovies
                                    ON movies.movie_id = userwatchedmovies.user_watched_movie_id;
                                    WHERE userwatchedmovies.user_watched_movie_user_id = :id');

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function get_seelater_movies($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT movie_api_id as "id"
                                    FROM watchasave.movies
                                    INNER JOIN watchasave.userwatchlatermovies
                                    ON movies.movie_id = userwatchlatermovies.user_watch_later_movie_id;
                                    WHERE userwatchlatermovies.user_watch_later_movie_user_id = :id');

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function get_favorites_movies($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT movie_api_id as "id"
                                    FROM watchasave.movies
                                    INNER JOIN watchasave.userfavoritemovies
                                    ON movies.movie_id = userfavoritemovies.user_favorite_movie_id;
                                    WHERE userfavoritemovies.user_favorite_movie_user_id = :id');

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function get_seelater_tvshows($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT serie_api_id as "id"
                                    FROM watchasave.series
                                    INNER JOIN watchasave.userwatchlaterseries
                                    ON series.serie_id = userwatchlaterseries.user_watch_later_serie_id;
                                    WHERE userwatchlaterseries.user_watch_later_serie_user_id = :id');

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

    public function get_favorites_tvshows($id){

        $db_handler = new DBHandler();

        $db_conn = $db_handler->get_connection();

        $db_sta = $db_conn->prepare('SELECT serie_api_id as "id"
                                    FROM watchasave.series
                                    INNER JOIN watchasave.userfavoriteseries
                                    ON series.serie_id = userfavoriteseries.user_favorite_serie_id;
                                    WHERE userfavoriteseries.user_favorite_serie_user_id = :id');

        $db_sta->bindValue(":id", $id);

        $db_sta->execute();

        $db_sta->setFetchMode(PDO::FETCH_ASSOC);

        return $db_sta->fetchAll();

    }

}
