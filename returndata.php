<?php
header('Content-type: application/json');
//session_start();
$servername = "sql307.epizy.com";
$username = "epiz_27629898";
$password = "sCZpr0zhpO1";
$dbname = "epiz_27629898_movieReview";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM movieReviews";

//$sql = "SELECT * FROM `movieReviews` WHERE `userID` = $_SESSION['id']";


$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) { 
        $response[] = $row;
    }
    echo json_encode($response);

} else {
    echo "0 results";
}

$conn->close();     
?>