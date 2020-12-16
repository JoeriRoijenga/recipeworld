<?php
$servername = "localhost";
$username = "recipeworld";
$password = "root";
$database = "recipeworld";
session_start();
$_SESSION["id"] = "2";

$connection = mysqli_connect($servername, $username, $password, $database);

if(mysqli_connect_errno()){
    die("De verbinding met de database is mislukt: " .
        mysqli_connect_error() . "(" .
        mysqli_connect_errno() .")");
}
?>