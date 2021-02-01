<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'sql307.epizy.com');
define('DB_USERNAME', 'epiz_27629898');
define('DB_PASSWORD', 'sCZpr0zhpO1');
define('DB_NAME', 'epiz_27629898_movieReview');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>