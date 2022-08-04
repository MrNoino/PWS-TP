<?php

require_once ('./vendor/autoload.php');
require_once("./lib/controller/setup_api_client.php");
$client = setup_api_client((isset($_SESSION["watch_a_save_lang"]) ? $_SESSION["watch_a_save_lang"] : "pt-PT"));

?>

<div class="container-fluid">

    <?php

        if(!isset($handle_users)){

            $handle_users = new handle_users();

        }

        $movie_ids = $handle_users->get_watched_movies($_SESSION["watch_a_save_user_id"]);

        if(count($movie_ids) > 0){

            echo '<div class="row mt-5">
            
                    <h2 class="text-center text-primary">'. $watched_movies_label .'</h2>

                </div>
            
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 mt-3">';

        }

        foreach($movie_ids as $id){

            if($id != NULL){

                $movie = $client->getMoviesApi()->getMovie($id["id"]);

                echo '<div class="col">

                                
                        <div class="card card-height">

                            <img src="' . (Empty($movie["poster_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w220_and_h330_face/" . $movie["poster_path"]) . '" class="card-img-top" alt="Poster do filme - ' . $movie["title"] .'">

                            <div class="card-body">

                                <h5 class="card-title">' . $movie["title"] .'</h5>

                            </div>

                        </div>
                        
                    </div>';

            }

            

        }

        if(count($movie_ids) > 0){

            echo '</div>';

        }

        $movie_ids = $handle_users->get_seelater_movies($_SESSION["watch_a_save_user_id"]);

        if(count($movie_ids) > 0){

            echo '<div class="row mt-5">
            
                    <h2 class="text-center text-primary">'. $seelater_movies_label .'</h2>

                </div>
            
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 mt-3">';

        }

        foreach($movie_ids as $id){

            if($id != NULL){

                $movie = $client->getMoviesApi()->getMovie($id["id"]);

                echo '<div class="col">

                                
                        <div class="card card-height">

                            <img src="' . (Empty($movie["poster_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w220_and_h330_face/" . $movie["poster_path"]) . '" class="card-img-top" alt="Poster do filme - ' . $movie["title"] .'">

                            <div class="card-body">

                                <h5 class="card-title">' . $movie["title"] .'</h5>

                            </div>

                        </div>
                        
                    </div>';

            }

            

        }

        if(count($movie_ids) > 0){

            echo '</div>';

        }

        $movie_ids = $handle_users->get_favorites_movies($_SESSION["watch_a_save_user_id"]);

        if(count($movie_ids) > 0){

            echo '<div class="row mt-5">
            
                    <h2 class="text-center text-primary">'. $favorites_movies_label .'</h2>

                </div>
            
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 mt-3">';

        }

        foreach($movie_ids as $id){

            if($id != NULL){

                $movie = $client->getMoviesApi()->getMovie($id["id"]);

                echo '<div class="col">

                                
                        <div class="card card-height">

                            <img src="' . (Empty($movie["poster_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w220_and_h330_face/" . $movie["poster_path"]) . '" class="card-img-top" alt="Poster do filme - ' . $movie["title"] .'">

                            <div class="card-body">

                                <h5 class="card-title">' . $movie["title"] .'</h5>

                            </div>

                        </div>
                        
                    </div>';

            }

            

        }

        if(count($movie_ids) > 0){

            echo '</div>';

        }

        $tvshow_ids = $handle_users->get_seelater_tvshows($_SESSION["watch_a_save_user_id"]);

        if(count($tvshow_ids) > 0){

            echo '<div class="row mt-5">
            
                    <h2 class="text-center text-primary">'. $seelater_tvshows_label .'</h2>

                </div>
            
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 mt-3">';

        }

        foreach($tvshow_ids as $id){

            if($id != NULL){

                $tvshow = $client->getTvApi()->getTvshow($id["id"]);

                echo '<div class="col">

                                
                        <div class="card card-height">

                            <img src="' . (Empty($tvshow["poster_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w220_and_h330_face/" . $tvshow["poster_path"]) . '" class="card-img-top" alt="Poster da série - ' . $tvshow["name"] .'">

                            <div class="card-body">

                                <h5 class="card-title">' . $tvshow["name"] .'</h5>

                            </div>

                        </div>
                        
                    </div>';

            }

            

        }

        if(count($tvshow_ids) > 0){

            echo '</div>';

        }

        $tvshow_ids = $handle_users->get_favorites_tvshows($_SESSION["watch_a_save_user_id"]);

        if(count($tvshow_ids) > 0){

            echo '<div class="row mt-5">
            
                    <h2 class="text-center text-primary">'. $favorites_tvshows_label .'</h2>

                </div>
            
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 mt-3">';

        }

        foreach($tvshow_ids as $id){

            if($id != NULL){

                $tvshow = $client->getTvApi()->getTvshow($id["id"]);

                echo '<div class="col">

                                
                        <div class="card card-height">

                            <img src="' . (Empty($tvshow["poster_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w220_and_h330_face/" . $tvshow["poster_path"]) . '" class="card-img-top" alt="Poster da série - ' . $tvshow["name"] .'">

                            <div class="card-body">

                                <h5 class="card-title">' . $tvshow["name"] .'</h5>

                            </div>

                        </div>
                        
                    </div>';

            }

            

        }

        if(count($tvshow_ids) > 0){

            echo '</div>';

        }

    ?>

</div>

<h2 class="text-center text-primary mt-5"><?php echo $serie_genders_label ?></h2>
<div id="piechart" class="mt-5">

</div>