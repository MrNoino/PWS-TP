var imgPreview = document.getElementById("profile_picture");

var inputPhoto = document.getElementById("file_photo");

if(imgPreview != undefined){

    inputPhoto.addEventListener("change", function(event){

        imgPreview.classList.remove("d-none");

        imgPreview.src = URL.createObjectURL(event.target.files[0]);

    }, false);

}

var piechart = document.getElementById("piechart");

if(piechart != undefined){

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(getTheMostTvShowsGenresWatched);

}

function getTheMostTvShowsGenresWatched(){

    var http = new XMLHttpRequest();
    http.open('GET', "most_tvshows_genres_watched.php");

    http.onload = function(){

        if(http.status === 200){

            var res = JSON.parse(http.response);

            var dataArray = [];

            var row = ["Genres", "Total Watched"];

            dataArray.push(row);

            row = [];

            for(var i = 0; i < res.length; i++){

                row.push(res[i].genre);
                row.push(parseInt(res[i].total));
                dataArray.push(row);
                row = [];

            }

            console.log(dataArray);

            var data = google.visualization.arrayToDataTable(
                dataArray
            );
        
            var options = {
        
              title: (getLang() === "pt-PT" ? "Géneros de séries mais assistidas" : "The Most Tv Shows Genres Watched")
        
            };
        
            var chart = new google.visualization.PieChart(piechart);
        
            chart.draw(data, options);

        }

    }

    http.send();

}

function getLang(){

    var http = new XMLHttpRequest();
    http.open('GET', "lib/current_lang.php");

    http.onload = function(){

        if(http.status === 200){

            var res = JSON.parse(http.response);

            return res.lang;

        }

    }

    http.send();

}

