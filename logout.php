<?php
spl_autoload_register(function ($classname) {
    include "classes/" . $classname . ".php";
});

session_start();
$functions = new functions("recipeworld");
$functions->updateDateTime($_SESSION["id"]);
session_destroy();

header('Location: login.php');