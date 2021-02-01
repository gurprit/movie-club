<?php
$servername = "sql307.epizy.com";
$username = "epiz_27629898";
$password = "sCZpr0zhpO1";
$dbname = "epiz_27629898_movieReview";

$conn = new mysqli($servername, $username, $password, $dbname); // Create connection
if ($conn->connect_error) {     // Check connection
    die("Connection failed: " . $conn->connect_error);
} 

$userID = mysqli_real_escape_string($conn, $_POST['userID']);
$movieName = mysqli_real_escape_string($conn, $_POST['movieName']);
$movieDirector = mysqli_real_escape_string($conn, $_POST['movieDirector']);
$movieReleaseYear = mysqli_real_escape_string($conn, $_POST['movieReleaseYear']);
$moviePlot = mysqli_real_escape_string($conn, $_POST['moviePlot']);
$moviePoster = mysqli_real_escape_string($conn, $_POST['moviePoster']);
$movieGenre = mysqli_real_escape_string($conn, $_POST['movieGenre']);
$userRating = mysqli_real_escape_string($conn, $_POST['userRating']);
$userComment = mysqli_real_escape_string($conn, $_POST['userComment']);

if (strlen($times) > 200000) {  $times = "";    }

$sql = "INSERT INTO movieReviews (userID, movieName, movieDirector, movieReleaseYear, moviePlot, moviePoster, movieGenre, userRating, userComment)
VALUES ('$userID','$movieName', '$movieDirector', '$movieReleaseYear', '$moviePlot', '$moviePoster', '$movieGenre', '$userRating', '$userComment') ON DUPLICATE KEY UPDATE    
userID='$userID', movieName='$movieName', movieDirector='$movieDirector', movieReleaseYear='$movieReleaseYear', moviePlot='$moviePlot', moviePoster='$moviePoster', movieGenre='$movieGenre', userRating='$userRating', userComment='$userComment'";


//$sql = "INSERT INTO movieReviews (userID, movieName, movieDirector, movieReleaseYear, moviePlot, moviePoster, movieGenre, userRating, userComment, date)
//VALUES ('$userID','$movieName', '$movieDirector', '$movieReleaseYear', '$moviePlot', '$moviePoster', '$movieGenre', '$userRating', '$userComment', CURDATE()) ON DUPLICATE KEY UPDATE    
//userID='$userID', movieName='$movieName', movieDirector='$movieDirector', movieReleaseYear='$movieReleaseYear', moviePlot='$moviePlot', moviePoster='$moviePoster', movieGenre='$movieGenre', userRating='$userRating', //userComment='$userComment', date=CURDATE()";

if ($conn->query($sql) === TRUE) {
    echo "Page saved!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>