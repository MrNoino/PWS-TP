<?php

##############################################
##                                          ##
## Título: Watch a Save - Sign Up           ##
##                                          ##
##############################################
##                                          ##
## Descrição: Página Sign Up do website     ##
## Watch a Save, trabalho prático da        ##
## cadeira de Programação Web Servidor no   ## 
## CTeSP de TPSI                            ##
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
    if(file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_nav_". $_SESSION["watch_a_save_lang"] .".php") && file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_signup_". $_SESSION["watch_a_save_lang"] .".php") && file_exists("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_footer_". $_SESSION["watch_a_save_lang"] .".php")){

        //importa os ficheiros do idioma escolhido
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_nav_". $_SESSION["watch_a_save_lang"] .".php");
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_signup_". $_SESSION["watch_a_save_lang"] .".php");
        include_once("resources/languages/". $_SESSION["watch_a_save_lang"] ."/i18n_footer_". $_SESSION["watch_a_save_lang"] .".php");

    //senao
    }else{

        //importa os ficheiros do idioma por defeito
        include_once("resources/languages/pt-PT/i18n_nav_pt-PT.php");
        include_once("resources/languages/pt-PT/i18n_signup_pt-PT.php");
        include_once("resources/languages/pt-PT/i18n_footer_pt-PT.php");

    }

//senao
}else{

    //importa os ficheiros do idioma por defeito
    include_once("resources/languages/pt-PT/i18n_nav_pt-PT.php");
    include_once("resources/languages/pt-PT/i18n_signup_pt-PT.php");
    include_once("resources/languages/pt-PT/i18n_footer_pt-PT.php");

}

remember_user();

check_if_user_exists();

if (isset($_POST["signup"])) {

    if(isset($_POST["name"], $_POST["email"], $_POST["password"], $_POST["confirm_pwd"], $_POST["gender"]) && !empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm_pwd"])  && !empty($_POST["gender"])) {

        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_pwd = $_POST["confirm_pwd"];
        $gender = $_POST["gender"];
        $birthdate = NULL;
        $photo = NULL;

        if(!is_email_valid($email)){

            $email_error = $email_invalid_error;

        }else{

            if($password != $confirm_pwd){

                $confirm_pwd_error = $confirm_pwd_not_match_error;     
                
            }else{

                if(!isset($_POST['terms'])){
    
                    $terms_error = $terms_pp_not_agreed_error;
        
                }else{

                    if(isset($_POST["birthdate"]) && !empty($_POST["birthdate"])){
                    
                        $birthdate = $_POST["birthdate"];
            
                    }
            
                    if(isset($_FILES["photo"]) && $_FILES["photo"]["size"] > 0){
                        
                        $photo = $_FILES["photo"];
            
                    }
                    
                    $resg_user = new handle_users();
            
                    $id = $resg_user->user_registration($name, $email, $password, $birthdate, $photo, $gender);
            
                    if($resg_user->get_code() == 409){

                        $email_error = $email_already_registered_error;

                    }else if($resg_user->get_code() != 200){
            
                        $error =  $default_error;
            
                    }else{

                        header("Location: email/activation_email.php?email=$email&name=$name&id=$id");

                    }

                }

            }
    
        }  

    }else {

        $error = $required_fields_error;

    }

}

if (is_logged()) {

    header("Location: ./index.php");

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="./styles/signup.css" rel="stylesheet">
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
                                    <a class="nav-link" href="./profile.php">'. $profile_link_content .'</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="./logout.php">'. $logout_link_content .'</a>
                                </li>';

                    }else{

                        echo '<li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="./login.php">'. $login_link_content .'</a>
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

    <main class="container mt-5 mb-5">

        <h2 class="text-center text-primary"><?php echo $signup ?></h3>

        <form class="row g-3 mt-3" method="POST" enctype="multipart/form-data">

            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="input_name" class="form-label"><?php echo $name_label ?><span class="text-danger">*</label>
                
                <input type="text" class="form-control<?php echo ((isset($resg_user) && $resg_user->get_code() != 200 &&  $resg_user->get_code() != 409 || isset($error)) ? " is-invalid" : ""); ?>" id="input_name" name="name" placeholder="<?php echo $name_placeholder ?>" required>

            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="input_email" class="form-label"><?php echo $email_label ?><span class="text-danger">*</span></label>
                
                <input type="email" class="form-control<?php echo ((isset($resg_user) && $resg_user->get_code() != 200 || isset($error) || isset($email_error)) ? " is-invalid" : ""); ?>" id="input_email" name="email" placeholder="<?php echo $email_placeholder ?>" required>

                <div id="validationEmailFeedback" class="invalid-feedback">

                    <?php echo(isset($email_error) ? $email_error : ""); ?>

                </div>

            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="input_password" class="form-label"><?php echo $password_label ?><span class="text-danger">*</span></label>
                
                <input type="password" class="form-control<?php echo ((isset($resg_user) && $resg_user->get_code() != 200 &&  $resg_user->get_code() != 409 || isset($error)) ? " is-invalid" : ""); ?>" id="input_password" name="password" placeholder="<?php echo $pwd_placeholder ?>" required>

            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="input_confirm_pwd" class="form-label"><?php echo $confirm_pwd_label ?><span class="text-danger">*</span></label>
                
                <input type="password" class="form-control<?php echo ((isset($resg_user) && $resg_user->get_code() != 200 &&  $resg_user->get_code() != 409 || isset($error) || isset($confirm_pwd_error)) ? " is-invalid" : ""); ?>" id="input_confirm_pwd" name="confirm_pwd" placeholder="<?php echo $confirm_pwd_placeholder ?>" required>

                <div id="validationConfirmPWDServerFeedback" class="invalid-feedback">

                <?php echo(isset($confirm_pwd_error) ? $confirm_pwd_error : ""); ?>

                </div>

            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="select_gender" class="form-label"><?php echo $gender_label ?><span class="text-danger">*</span></label>

                <select class="form-select<?php echo ((isset($resg_user) && $resg_user->get_code() != 200 &&  $resg_user->get_code() != 409 || isset($error)) ? " is-invalid" : ""); ?>" id="select_gender" name="gender" aria-label="Genders" required>

                    <option value="F" selected><?php echo $genders[0] ?></option>
                    <option value="M"><?php echo $genders[1] ?></option>
                    <option value="NB"><?php echo $genders[2] ?></option>

                </select>

            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="input_birthdate" class="form-label"><?php echo $birthdate_label ?></label>

                <input type="date" class="form-control" id="input_birthdate" name="birthdate">


            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <label for="file_photo" class="form-label"><?php echo $photo_label ?></label>
                    
                <input type="file" class="form-control" id="file_photo" name="photo" accept="image/*"/>

                <figure class="mt-4 text-center">

                    <img class="img-thumbnail text-center profile-picture d-none" alt="Profile Picture" id="profile_picture" src=""/>
                
                </figure>

            </div>

            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-3 col-lg-4"></div>

            <div class="col-md-6 col-lg-4 mt-4">

                <div class="form-check">

                    <input class="form-check-input<?php echo ((isset($resg_user) && $resg_user->get_code() != 200  && $resg_user->get_code() != 409 || isset($error) || isset($terms_error)) ? " is-invalid" : ""); ?>" type="checkbox" value="" id="checkbox_terms" name="terms" aria-describedby="agree_terms" required>
                    
                    <label class="form-check-label" for="checkbox_terms">
                    <?php echo $agree_label ?> <a class="link-info" id="input_email" name="email" placeholder="Insira o seu email" value="<?php echo ((isset($auth_user) && $auth_user->get_code() != 404) ? $_POST["email"] : ''); ?>" href=""><?php echo $terms_pp ?></a><span class="text-danger">*</span>
                    </label>

                    <div id="validationTermsServerFeedback" class="invalid-feedback">

                        <?php 
                        
                            echo(isset($terms_error) ? $terms_error : "");
                            echo(isset($error) ? $error : "");
                        
                        ?>

                    </div>
                
                </div>

            </div>


            <div class="col-md-3 col-lg-4"></div>
            <div class="col-md-4 col-lg-5"></div>

            <div class="d-grid gap-2 col-12 col-sm-9 col-md-4 col-lg-2 mx-auto mt-5">

                <button class="btn btn-primary" type="submit" name="signup"><?php echo $signup ?></button>

            </div>

            <div class="col-md-4 col-lg-5"></div>
            <div class="col-md-3"></div>

            <div class="col-md-6 text-center mt-4">

                <label for="lin_signup" class="form-label"><?php echo $have_account_label ?></label>
                <a href="./login.php" class="link-info"><?php echo $login ?></a>

            </div>

        </form>

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
    <script src="./js/signup.js"></script>

</body>

</html>