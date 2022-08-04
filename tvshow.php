<?php

##############################################
##                                          ##
## Título: Watch a Save - Tv Show           ##
##                                          ##
##############################################
##                                          ##
## Descrição: Página Home do website Watch  ##
## a Save, trabalho prático da cadeira de   ##
## Programção Web Servidor no CTeSP de TPSI ##
##                                          ##
##############################################
##                                          ##
## Autor: Nuno Lopes                        ##
##                                          ##
##############################################
##                                          ##
## Data: 24/11/2021                         ##
##                                          ##
##############################################

header('Content-Type: text/html; charset=utf-8');

session_start();

if(isset($_GET["lang"])){

    $_SESSION["watch_a_save_lang"] = $_GET["lang"];

}

include_once("./lib/utils.php");
require_once("./lib/controller/handle_tvshows.php");
require_once ('./vendor/autoload.php');
require_once("./lib/controller/setup_api_client.php");
$client = setup_api_client((isset($_SESSION["watch_a_save_lang"]) ? $_SESSION["watch_a_save_lang"] : "pt-PT"));

//se o utilizador nao está com sessão iniciada
if (!is_logged()) {

    header("Location: ./login.php");
    
}

//verifica se o idioma está definido
//se sim
if(isset($_SESSION["watch_a_save_lang"])){

    //verifica se os ficheiros do idioma existem
    //se sim
    if(file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_nav_". $_SESSION["watch_a_save_lang"] .".php") && file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_tvshow_". $_SESSION["watch_a_save_lang"] .".php") && file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_footer_". $_SESSION["watch_a_save_lang"] .".php")){

        //importa os ficheiros do idioma escolhido
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_nav_". $_SESSION["watch_a_save_lang"] .".php");
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_tvshow_". $_SESSION["watch_a_save_lang"] .".php");
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_footer_". $_SESSION["watch_a_save_lang"] .".php");

    //senao
    }else{

        //importa os ficheiros do idioma por defeito
        include_once("resources/languages/pt-PT/i18n_nav_pt-PT.php");
        include_once("resources/languages/pt-PT/i18n_tvshow_pt-PT.php");
        include_once("resources/languages/pt-PT/i18n_footer_pt-PT.php");

    }

//senao
}else{

    //importa os ficheiros do idioma por defeito
    include_once("resources/languages/pt-PT/i18n_nav_pt-PT.php");
    include_once("resources/languages/pt-PT/i18n_tvshow_pt-PT.php");
    include_once("resources/languages/pt-PT/i18n_footer_pt-PT.php");

}

remember_user();

check_if_user_exists();

if(isset($_GET["tvshowId"]) && !empty($_GET["tvshowId"])){

    $tvshow = $client->getTvApi()->getTvshow($_GET["tvshowId"]);
    
    $credits = $client->getTvApi()->getCredits($_GET["tvshowId"]);

    $cast = [];

    foreach($credits["cast"] as $cast_member){

        if($cast_member["known_for_department"] === "Acting"){

            $cast[] = $cast_member;

        }

    }

    $seasons = $tvshow["seasons"];

}else{

    echo '<script type="text/javascript">history.go(-1);</script>';

}

if(isset($_GET["operation"], $_GET["tvshowId"])){

    $handle_tvshows = new handle_tvshows();

    switch($_GET["operation"]){

        case "seelater":

            $handle_tvshows->set_seelater($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

            break;

        case "unseelater":

            $handle_tvshows->unset_seelater($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

            break;

        case "favorite":

            $handle_tvshows->set_favorite($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

            break;

        case "unfavorite":

            $handle_tvshows->unset_favorite($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

            break;

    }

}

if(isset($_POST["evaluate"])){

    if(isset($_POST["comment"], $_POST["stars"])){

        $handle_tvshows = new handle_tvshows();
        
        $handle_tvshows->evaluate($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"], $_POST["comment"], $_POST["stars"]);

    }else{



    }

}

$handle_tvshows = new handle_tvshows();
        
$user_evalutation = $handle_tvshows->get_evaluation($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

$evaluations = $handle_tvshows->get_all_evaluations($_GET["tvshowId"]);

$seelater = $handle_tvshows->get_seelater($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

$favorite = $handle_tvshows->get_favorite($_GET["tvshowId"], $_SESSION["watch_a_save_user_id"]);

?>

<!DOCTYPE html>

<html lang="<?php echo (isset($_SESSION["watch_a_save_lang"]) ? substr($_SESSION["watch_a_save_lang"], 0, 2) : "pt") ?>">

<head>

    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link href="./styles/common.css" rel="stylesheet">

    <link href="./resources/assets/icon.jpeg" rel="shortcut icon" type="image/x-icon">

    <title>
        <?php echo $document_title . (isset($tvshow) ? $tvshow["name"] : $default_title); ?>
    </title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-warning">

        <div class="container-fluid">

            <a class="navbar-brand" href="./index.php"><img class="logo-img" src="./resources/assets/logo2.png"/></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                
                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="./index.php"><?php echo $home_link_content ?></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./movies.php"><?php echo $movies_link_content ?></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="./tvshows.php"><?php echo $tvshows_link_content ?></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./actors.php"><?php echo $actors_link_content ?></a>
                    </li>

                    <?php

                    if(is_logged()){

                        echo '<li class="nav-item">
                                    <a class="nav-link" href="./profile.php">'. $profile_link_content .'</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="./logout.php">'. $logout_link_content .'</a>
                                </li>';

                    }else{

                        echo '<li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="./login.php">'. $login_link_content .'</a>
                                </li>';

                    }

                    ?>

                </ul>

                <form method="GET" action="./search.php" class="d-flex">

                    <input class="form-control me-2" type="search" name="search" placeholder="<?php echo $search_placeholder ?>" aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit"><?php echo $search_button_content ?></button>

                </form>

            </div>
        </div>
    </nav>

    <main class="container-fluid mt-4 mb-5">

        <div class="row">

            <div class="col-12 col-md-6 col-lg-4">

                <img class="rounded img-fluid" src="https://www.themoviedb.org/t/p/w600_and_h900_bestv2/<?php echo $tvshow["poster_path"]; ?>" alt="Poster do Filme - <?php echo $tvshow["name"]; ?>">

                <div class="d-flex d-flex justify-content-center">

                    <a href="?tvshowId=<?php echo $_GET["tvshowId"] . '&operation=' . ($seelater ? 'unseelater' : 'seelater') ?>"><button class="btn btn-outline-secondary rounded-btn<?php echo ($seelater ? " active" : "") ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $seelater_tooltip ?>"><span class="bi bi-bookmark"></span></button></a>

                    <a href="?tvshowId=<?php echo $_GET["tvshowId"] . '&operation=' . ($favorite ? 'unfavorite' : 'favorite') ?>"><button class="btn btn-outline-danger rounded-btn<?php echo ($favorite ? " active" : "") ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $fav_tooltip ?>"><span class="bi bi-heart"></span></button></a>

                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-8">

                <h2 class="text-center text-primary mt-3"><?php echo $tvshow["name"] ?></h2>

                <p class="p-2 mt-5">
                    
                    <?php 
                
                        $str = "";

                        foreach($tvshow["genres"] as $genre){

                            $str .= $genre["name"]. ", ";

                        }

                        echo "<strong>" . $genres_label . ":</strong> " . substr($str, 0, -2);

                    ?>
                    
                </p>

                <p class="p-2">

                    <?php echo "<strong>" . $release_date . ":</strong> " . $tvshow["first_air_date"] ?>

                </p>

                <p class="p-2">
                
                    <?php echo "<strong>" . $overview_label . "</strong><br>" . $tvshow["overview"] ?>

                </p>

                <p class="p-2"> 
                    
                    <?php echo "<strong>" . $score_label . ":</strong> " . $tvshow["vote_average"] ?><span class="bi bi-star text-warning"></span>
                
                </p>

                <p class="p-2"> 
                    
                    <?php echo "<strong>" . $original_lang_label . ":</strong> "  . strtoupper($tvshow["original_language"]) ?>
                
                </p>

            </div>

            <?php 

                if(count($seasons) > 0){

                    echo '<div class="col-12">

                            <div class="container-fluid mt-5">
                            
                                <div class="row mt-5">

                                    <div class="col-12"><h4>'. $seasons_label .'</h4></div> 
                                    
                                </div>
                                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">';

                }

                foreach($seasons as $season){

                    echo '<a href="season.php?tvshowId='. $_GET["tvshowId"] .'&season_number='. $season["season_number"] .'">
                    
                            <div class="col">

                                <div class="card card-height">

                                    <img src="'. (Empty($season["poster_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w600_and_h900_bestv2/" . $season["poster_path"]) .'" class="card-img-top" alt="Poster da temporada - ' . $season["name"] .'">
                    
                                    <div class="card-body">

                                        <h5 class="card-title">' . $season["name"] .'</h5>
                                        
                                    </div>
                
                                </div>
                            </div>
                    
                        </a>';

                }


                if(count($seasons) > 0){

                    echo '</div>

                        </div>

                    </div>';

                }
            
            ?>

            <div class="col-12">

                <div class="container-fluid mt-5">

                    <div class="row">

                        <div class="col-12"><h4><?php echo $evaluate_label ?></h4></div>

                    </div>

                    <form class="row" method="POST" action="./tvshow.php?tvshowId=<?php echo $_GET["tvshowId"] ?>">

                        <div class="col-12 col-md-7 col-xl-8 mt-3">

                            <div class="input-group">

                                <input type="text" class="form-control" name="comment" placeholder="<?php echo $comment_placeholder ?>" maxlength="256" value="<?php echo (!Empty($user_evalutation["Comment"]) ? $user_evalutation["Comment"] : "" )?>"/>

                                <span class="input-group-text bg-white bi bi-chat"></span>

                            </div>

                        </div>

                        <div class="col-12 col-md-3 mt-3">

                            <div class="input-group">

                                <input type="number" class="form-control" name="stars" placeholder="<?php echo $stars_placeholder ?>" step="1" min="0" max="10" value="<?php echo (!Empty($user_evalutation["Stars"]) ? $user_evalutation["Stars"] : "" )?>"/>

                                <span class="input-group-text bg-white bi bi-star text-warning"></span>

                            </div>
                            

                        </div>

                        <div class="col-12 col-md-2 col-xl-1 d-flex justify-content-center mt-3">

                            <input type="submit" class="btn btn-primary w-100" name="evaluate" value="Avaliar" />

                        </div>

                    </form>

                    <?php 

                        if(count($evaluations) > 0){

                            echo '<div class="row mt-5">

                                    <div class="col-12"><h4><?php echo $evaluations_label ?></h4></div>
            
                                </div>

                                <div class="row">

                                    <ul class="list-group col-12">';

                        }
                    
                        foreach($evaluations as $evaluation){

                            echo '<li class="list-group-item">
                                    <h5 class="mb-1">'. $evaluation["User"] .'</h5>
                                    <p class="mb-1">'. $evaluation["Stars"] .' <span class="bi bi-star text-warning"></span></p>
                                    <small>'. $evaluation["Comment"] .'</small>
                                </li>';

                        }

                        if(count($evaluations) > 0){

                            echo '</ul>
                                    </div>';

                        }

                    ?>

                </div>

            </div>

            <?php

                if(count($cast) > 0){

                    echo '<div class="col-12">

                            <div class="container-fluid mt-5">
    
                                <div class="row">
    
                                    <div class="col-12"><h4>'. $cast_label .'</h4></div>
    
                                </div>
    
                                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">';

                }

                foreach($cast as $actor){

                    echo '<div class="col">

                            <a class="no-decoration" href="./actor.php?actorId=' . $actor["id"] . '">
                                <div class="card card-height">
                
                                    <img src="'. (Empty($actor["profile_path"]) ? "resources/assets/noimage.png" : "https://www.themoviedb.org/t/p/w600_and_h900_bestv2/" . $actor["profile_path"]) .'" class="card-img-top" alt="Foto de perfil - ' . $actor["name"] .'">
                    
                                    <div class="card-body">

                                        <h5 class="card-title">' . $actor["name"] .'</h5>
                    
                                    </div>
                    
                                </div>
                            </a>
                        </div>';

                }

                if(count($cast) > 0){

                    echo '</div>

                        </div>
    
                    </div>';

                }

            ?>

        </div>

    </main>

    <footer class="text-center text-lg-start bg-footer text-muted">
        
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
           
            <div class="me-5 d-none d-lg-block">

                <span><?php echo $social_networks_content ?></span>

            </div>
            
            <div>

                <a href="https://www.facebook.com/" class="no-decoration me-4 text-reset">
                    <i class="bi bi-facebook"></i>
                </a>

                <a href="https://www.twitter.com/" class="no-decoration me-4 text-reset">
                    <i class="bi bi-twitter"></i>
                </a>

                <a href="https://www.google.com/" class="no-decoration me-4 text-reset">
                    <i class="bi bi-google"></i>
                </a>

                <a href="https://www.instagram.com/" class="no-decoration me-4 text-reset">
                    <i class="bi bi-instagram"></i>
                </a>

                <a href="https://www.linkedin.com/" class="no-decoration me-4 text-reset">
                    <i class="bi bi-linkedin"></i>
                </a>

                <a href="https://www.github.com/" class="no-decoration me-4 text-reset">
                    <i class="bi bi-github"></i>
                </a>

            </div>
        </section>

        <section class="">

            <div class="container text-center text-md-start mt-5">
            
                <div class="row mt-3">
                    
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    
                    <h6 class="text-uppercase fw-bold mb-4">
                        <i class="fas fa-gem me-3"></i>Watch a Save
                    </h6>

                    <p class="text-justify">
                        <?php echo $short_resume ?>
                    </p>

                    </div>
                    
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    
                        <h6 class="text-uppercase fw-bold mb-4">
                            <?php echo $language_label ?>
                        </h6>

                        <form id="language-form" method="GET">

                            <input type="hidden" name="tvshowId" value="<?php echo $_GET["tvshowId"] ?>">

                            <select id="language-select" name="lang" class="form-select" aria-label="Language">

                                <option value="pt-PT" <?php echo (isset($_SESSION["watch_a_save_lang"]) && $_SESSION["watch_a_save_lang"] == "pt-PT" ? "selected" : "") ?>>Português</option>
                                <option value="en-US" <?php echo (isset($_SESSION["watch_a_save_lang"]) && $_SESSION["watch_a_save_lang"] == "en-US" ? "selected" : "") ?>>English</option>

                            </select>

                        </form>

                    </div>
                    
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    
                        <h6 class="text-uppercase fw-bold mb-4">
                            <?php echo $useful_links ?>
                        </h6>

                        <p>
                            <a href="" class="text-reset"><?php echo $terms_link_content ?></a>
                        </p>

                        <p>
                            <a href="" class="text-reset"><?php echo $privacy_policy_link_content ?></a>
                        </p>

                        <p>
                            <a href="./contact.php" class="text-reset"><?php echo $contact_link_content ?></a>
                        </p>

                        <p>
                            <a href="./support.php" class="text-reset"><?php echo $support_link_content ?></a>
                        </p>

                    </div>

                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    
                        <h6 class="text-uppercase fw-bold mb-4">
                            Contato
                        </h6>

                        <p>
                            <i class="bi bi-house"></i> 
                            Aqui, acolá, onde ninguém mora, 1234-321
                        </p>

                        <p>
                            <i class="bi bi-envelope"></i>
                            contact@watchasave.com
                        </p>

                        <p>
                            <i class="bi bi-telephone"></i> 
                            +351 912345678
                        </p>

                    </div>
                    
                </div>
            
            </div>

        </section>
        
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="">Watch a Save</a>
        </div>

    </footer>

    <script src="./js/common.js"></script>

</body>

</html>