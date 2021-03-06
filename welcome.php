<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    
<style>
@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto+Slab:wght@100;400&display=swap');

input, button, select {
  margin: 10px 0 10px 10px;
  padding: 10px;
}

.movieInfo {
  padding: 10px;
}
.movieInfo img {
  float: left;
  padding: 0 20px 20px 0;
}
@media screen and (max-width: 600px) {
  .movieInfo img {
    float: none;
    padding: 0 0 20px 0;
  }
}
.movieInfo h1 {
    font-size: 5vw;
    line-height: 5vw;
    font-weight: normal;
    font-family: 'Roboto Slab', serif;
}
@media screen and (max-width: 600px) {
  .movieInfo h1 {
    font-size: 40px;
    line-height: 50px;
  }
}
.movieInfo h2 {
    font-family: 'Roboto Slab', serif;
    font-weight: 100;
    font-size: 3vw;
    line-height: 3vw;
    font-style: italic;
}
@media screen and (max-width: 600px) {
  .movieInfo h2 {
    font-size: 15px;
    line-height: 25px;
  }
}
.movieInfo h3 {
  font-family: 'Roboto Slab', serif;
  font-weight: 100;
  font-size: 3vw;
  line-height: 4vw;
}
@media screen and (max-width: 600px) {
  .movieInfo h3 {
    font-size: 15px;
    line-height: 25px;
  }
}
.movieInfo h4 {
    font-family: 'Open Sans', sans-serif;
    font-weight: 300;
    font-size: 2vw;
    line-height: 3vw;
    padding: 20px 0;
}
@media screen and (max-width: 600px) {
  .movieInfo h4 {
    font-size: 15px;
    line-height: 20px;
  }
}
.movieInfo h5 {
    font-size: 1.5vw;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
}
@media screen and (max-width: 600px) {
  .movieInfo h5 {
    font-size: 15px;
    line-height: 20px;
  }
}

.rating-container {
  display: flex;
  padding: 20px 0;
}
.rating-container .rating {
    padding: 0 10px 0 0;
    font-family: 'Open Sans', sans-serif;
}
.rating-container .rating span {
    font-weight: 600;
    font-size: 2vw;
}
@media screen and (max-width: 600px) {
  .rating-container .rating span {
    font-size: 15px;
  }
}

.movie-review-zone {
    display: none;
    clear: both;
    padding: 0 0 50px 0;
    text-align: center;
}

.reviewed-movies-container {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-evenly;
}

.reviewed-movie-tile {
    padding: 0 0 20px 0;
    position: relative;
}
.rating-sticker {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    position: absolute;
    top: 10px;
    right: 10px;
    left: auto;
    border-radius: 100%;
    width: 20px;
    padding: 20px 20px;
    height: 20px;
    text-align: center;
    background: rgba(255, 255, 255, 1);
    color: #000;
    border: solid 5px #000;
}
</style>        
</head>
<body>
    <div class="page-header">
        <h1>Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
        <a href="reset-password.php" class="btn btn-warning">Reset Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
       <form>
        <input id="friendRequestUserName" value="" placeholder="Enter Friend Username"></input>
        <button id="sendFriendRequest" class="send-friend-request" onclick="requestFriend()">Send Friend Request</button>
       </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

    <input id="movieSearchTerm" class="movie-search-field" placeholder="Enter movie title here"></input>
    <button id="getInfo" class="movie-search-button">Find Movie</button>
    <div class="movieInfo"></div>

    <div id="movieReviewZone" class="movie-review-zone">
        <form>
            <input id="userComment" value="" placeholder="Thoughts and Comments"></input>
            <select id="userRating">
                <option>Your rating</option>
                <option id="1">1</option>
                <option id="2">2</option>
                <option id="3">3</option>
                <option id="4">4</option>
                <option id="4">4</option>
                <option id="5">5</option>
                <option id="6">6</option>
                <option id="7">7</option>
                <option id="8">8</option>
                <option id="9">9</option>
                <option id="10">10</option>
            </select>

            <button type="submit" onclick="saveMovieReview()">Save Review</button>

        </form>
    </div>


<script type="text/javascript">

<!-- movie finder --> 
var reviewZone = document.querySelector('#movieReviewZone');
var button = document.querySelector('#getInfo');
var search = document.querySelector('#movieSearchTerm');
var movieInfoCont = document.querySelector('.movieInfo');

var getJSON = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      var status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
};

button.addEventListener("click", getMovieInfo);
var movieTitle;
var movieRelaseYear;
var movieDirector;
var moviePlot;
var movieAwards;
var moviePosterURL;
var movieRatings;
var movieGenre;

function getMovieInfo() {
  movieInfoCont.innerHTML = "";
  getJSON('https://www.omdbapi.com/?apikey=eafe3ca6&t=' + search.value + '',
  function(err, data) {
    if (err !== null) {
      alert('Something went wrong: ' + err);
    } else {
      var h1 = document.createElement("h1");
      movieTitle = data.Title;
      var title = document.createTextNode(movieTitle);
      
      var h2 = document.createElement("h2");
      movieRelaseYear = data.Year;
      var year = document.createTextNode(movieRelaseYear);
      
      var h3 = document.createElement("h3");
      movieDirector = data.Director;
      var director = document.createTextNode(movieDirector);

      var h4 = document.createElement("h4");
      moviePlot = data.Plot;
      var plot = document.createTextNode(moviePlot);
      
      var h5 = document.createElement("h5");
      movieAwards = data.Awards;
      var awards = document.createTextNode(movieAwards);

      movieGenre = data.Genre
      
      var img = document.createElement('img'); 
      img.src = data.Poster;
      moviePosterURL = data.Poster;
      
      var dataRatings = data.Ratings;
      
      h1.appendChild(title);
      h2.appendChild(year);
      h3.appendChild(director);
      h4.appendChild(plot);
      h5.appendChild(awards);
      
      movieInfoCont.appendChild(img);
      movieInfoCont.appendChild(h1);
      
      movieInfoCont.appendChild(h3);
      movieInfoCont.appendChild(h2);
      movieInfoCont.appendChild(h4);
      movieInfoCont.appendChild(h5);
      
      var ratingContainer = document.createElement("div");
      ratingContainer.classList.add('rating-container');
      
      for (var i = 0; i < dataRatings.length; i++) {
        
        var div = document.createElement("div");
        var span = document.createElement("span");
        var p = document.createElement("p");
        var ratingSource = document.createTextNode(dataRatings[i].Source);
        var ratingScore = document.createTextNode(dataRatings[i].Value);

        div.classList.add('rating-' + [i]);
        div.classList.add('rating');

        p.appendChild(ratingSource);

        div.appendChild(p);
        ratingContainer.appendChild(div);
        span.appendChild(ratingScore);
        div.appendChild(span);

      }
      movieInfoCont.appendChild(ratingContainer);
      
    }
  });

  reviewZone.style.display = 'block';
}

var getuserComment = document.querySelector('#userComment');
var getUserRating = document.querySelector('#userRating');
var currentUserID =  <?php echo htmlspecialchars($_SESSION["id"]); ?>;

var userRating;

function saveMovieReview() {
    userComment = getuserComment.value;
    userRating = getUserRating.options[getUserRating.selectedIndex].id; 

    $.post("savesettings.php",
    {
        userID: currentUserID,
        movieName: movieTitle,
        movieDirector: movieDirector,
        movieReleaseYear: movieRelaseYear,
        moviePlot: moviePlot,
        moviePoster: moviePosterURL,
        movieGenre: movieGenre,
        userComment: userComment,
        userRating: userRating
    });

    getMovieReviews()
}


function getMovieReviews() {
    var body = document.querySelector('body');
    var reviewedMoviesContainer = document.createElement("div");
    reviewedMoviesContainer.classList.add('reviewed-movies-container');

    $.post(
        "returndata.php",
        function(response) {

            for (var i = 0; i < response.length; i++) {
                if (response[i].userID == currentUserID) {

                    var reviewedMovieTile = document.createElement("div");
                    reviewedMovieTile.classList.add('reviewed-movie-tile');

                    var reviewedMoviePoster = document.createElement('img');
                    reviewedMoviePoster.src = response[i].moviePoster;

                    var ratingSticker = document.createElement("div");
                    ratingSticker.classList.add('rating-sticker');
                    var userRating = document.createTextNode(response[i].userRating);
                    ratingSticker.appendChild(userRating);

                    reviewedMovieTile.appendChild(ratingSticker);
                    reviewedMovieTile.appendChild(reviewedMoviePoster);
                    reviewedMoviesContainer.appendChild(reviewedMovieTile);
                    body.appendChild(reviewedMoviesContainer);
                }
            }
        }, 'json'
    ); 
}
getMovieReviews()










function requestFriend() {
    var friendRequestField = document.querySelector('#friendRequestUserName');
    var requestedFriendRequest = friendRequestField.value;

    $.post(
        "requestFriend.php",
        function(response) {

            //for (var i = 0; i < response.length; i++) {
                    console.log(response);

            //}
        }, 'json'
    ); 
}
</script>
</body>
</html>
