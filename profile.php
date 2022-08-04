<?php

##############################################
##                                          ##
## Título: Watch a Save - Home              ##
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

include_once("./lib/controller/handle_users.php");
include_once("./lib/utils.php");

session_start();

if(isset($_GET["lang"])){

    $_SESSION["watch_a_save_lang"] = $_GET["lang"];

}

//verifica se o idioma está definido
//se sim
if(isset($_SESSION["watch_a_save_lang"])){

    //verifica se os ficheiros do idioma existem
    //se sim
    if(file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_nav_". $_SESSION["watch_a_save_lang"] .".php") && file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_profile_". $_SESSION["watch_a_save_lang"] .".php") && file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_footer_". $_SESSION["watch_a_save_lang"] .".php")){

        //importa os ficheiros do idioma escolhido
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_nav_". $_SESSION["watch_a_save_lang"] .".php");
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_profile_". $_SESSION["watch_a_save_lang"] .".php");
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_footer_". $_SESSION["watch_a_save_lang"] .".php");

    //senao
    }else{

        //importa os ficheiros do idioma por defeito
        include_once("resources/languages/pt-PT/i18n_nav_pt-PT.php");
        include_once("resources/languages/pt-PT/i18n_profile_pt-PT.php");
        include_once("resources/languages/pt-PT/i18n_footer_pt-PT.php");

    }

//senao
}else{

    //importa os ficheiros do idioma por defeito
    include_once("resources/languages/pt-PT/i18n_nav_pt-PT.php");
    include_once("resources/languages/pt-PT/i18n_profile_pt-PT.php");
    include_once("resources/languages/pt-PT/i18n_footer_pt-PT.php");

}

remember_user();

check_if_user_exists();

if (!is_logged()) {

    header("Location: ./login.php");
    
}

if(isset($_POST["update"])){

    if(isset($_POST["name"], $_POST["gender"], $_POST["birthdate"])){

        $handle_user = new handle_users();

        $handle_user->update_user($_SESSION["watch_a_save_user_id"], $_POST["name"], $_POST["birthdate"], $_FILES["photo"], $_POST["gender"]);

        if($handle_user->get_code() != 200){

            $error = "Erro ao atualizar";

        }

    }else{

        $error =  "Campos inválidos";

    }

}

if(isset($_POST["delete"])){

    $handle_user = new handle_users();

    $handle_user->delete_user($_SESSION["watch_a_save_user_id"]);

    header("Location: ./logout.php");


}

?>

<!DOCTYPE html>

<html lang="<?php echo (isset($_SESSION["watch_a_save_lang"]) ? substr($_SESSION["watch_a_save_lang"], 0, 2) : "pt") ?>">

<head>

    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link href="./styles/profile.css" rel="stylesheet">
    <link href="./styles/common.css" rel="stylesheet">

    <link href="./resources/assets/icon.jpeg" rel="shortcut icon" type="image/x-icon">

    <title>
        <?php echo $document_title ?>
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
                        <a class="nav-link" href="./tvshows.php"><?php echo $tvshows_link_content ?></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./actors.php"><?php echo $actors_link_content ?></a>
                    </li>

                    <?php

                    if(is_logged()){

                        echo '<li class="nav-item">
                                    <a class="nav-link active" href="./profile.php">'. $profile_link_content .'</a>
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

    <div class="container">

        <div class="row mt-4 mb-4">

            <aside class="col-12 col-md-3 col-lg-2 bg-light">

                <div class="list-group mt-3">

                <?php

                    include_once("layouts/profile-menu.php");

                    ?>
                
                </div>

            </aside>

            <main class="col-12 col-md-9 col-lg-10">

                <?php

                    $handle_user2 = new handle_users();

                    $user = $handle_user2->get_user($_SESSION["watch_a_save_user_id"]);

                    $files = scandir("./resources/users/");

                    $user_photo = "";

                    foreach($files as $file){

                        if(substr($file, 0, strpos($file, ".")) == strval($_SESSION["watch_a_save_user_id"])){

                            $user_photo = "./resources/users/" . $file;
                            break;

                        }

                    }

                    if(empty($user_photo)){

                        $user_photo = "./resources/assets/noimage.png";

                    }

                    if(isset($_GET['option'])){

                        if($_GET['option'] === "Statistics"){

                            include_once("layouts/statistics.php");

                        }else if($_GET['option'] === "Personal Data"){

                            include_once("layouts/personal-data.php");

                        }else if($_GET['option'] === "Settings"){

                            include_once("layouts/settings.php");

                        }

                    }else{

                        include_once("layouts/statistics.php");

                    }

                ?>

            </main>

        </div>

    </div>

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

                            <?php 
                            
                                if(isset($_GET["option"])){

                                    echo '<input type="hidden" name="option" value="' . $_GET["option"] . '" />';

                                }
                            
                            ?>

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

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./js/profile.js"></script>

</body>

</html>