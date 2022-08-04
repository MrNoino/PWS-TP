/*############################################
##                                          ##
## Título: Script Login - Watch a Save      ##
##                                          ##
##############################################
##                                          ##
## Descrição: Script Login do website Watch ##
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
############################################*/

document.getElementById("toggle_visibility").addEventListener("click", function(){



    if(document.getElementById("input_password").type == "password"){

        document.getElementById("toggle_visibility_icon").className = "bi bi-eye-slash-fill";
        document.getElementById("input_password").type = "text";

    }else{

        document.getElementById("toggle_visibility_icon").className = "bi bi-eye-fill";
        document.getElementById("input_password").type = "password";

    }
    
});