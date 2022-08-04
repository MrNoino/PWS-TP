var imgPreview = document.getElementById("profile_picture");

var inputPhoto = document.getElementById("file_photo");

if(imgPreview != undefined){

    inputPhoto.addEventListener("change", function(event){

        imgPreview.classList.remove("d-none");

        imgPreview.src = URL.createObjectURL(event.target.files[0]);

    }, false);

}

var inputEmail = document.getElementById("input_email");

var emailFeedback = document.getElementById("validationEmailFeedback");

inputEmail.addEventListener("keyup", function(){

    if(is_email_valid(inputEmail.value)){

        var http = new XMLHttpRequest();
        http.open('GET', "./check-email.php?email=" + encodeURIComponent(inputEmail.value));

        http.onload = function(){

            if(http.status === 200){

                var res = JSON.parse(http.response);

                if(res.exists){

                    if(!inputEmail.classList.contains("is-invalid")){

                        inputEmail.classList.add("is-invalid");

                    }

                    inputEmail.classList.remove("is-valid");

                    if(!emailFeedback.classList.contains("invalid-feedback")){

                        emailFeedback.classList.add("invalid-feedback");

                    }

                    emailFeedback.classList.remove("valid-feedback");

                }else{

                    inputEmail.classList.remove("is-invalid");

                    if(!inputEmail.classList.contains("is-valid")){

                        inputEmail.classList.add("is-valid");

                    }

                    

                    emailFeedback.classList.remove("invalid-feedback");

                    if(!emailFeedback.classList.contains("valid-feedback")){

                        emailFeedback.classList.add("valid-feedback");

                    }

                }

                emailFeedback.innerHTML = res.message;

            }

        };

        http.send();

    }else{

        if(!inputEmail.classList.contains("is-invalid")){

            inputEmail.classList.add("is-invalid");

        }

        if(!emailFeedback.classList.contains("invalid-feedback")){

            emailFeedback.classList.add("invalid-feedback");

        }

        var http = new XMLHttpRequest();
        http.open('GET', "lib/current_lang.php");

        http.onload = function(){

            if(http.status === 200){

                var res = JSON.parse(http.response);

                if(res.lang == "pt-PT"){

                    emailFeedback.innerHTML = "Email inválido";

                }else{

                    emailFeedback.innerHTML = "Invalid email";

                }

            }

        }

        http.send();

    }

}, false);

function is_email_valid(email){

    return String(email).toLowerCase().match(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i);

}