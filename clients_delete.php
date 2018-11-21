<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

$functions = new functions("recipeworld");
$id = $_POST["client_id"];

$success = "false";

if ($functions->removeClient($id)) {
    $success = "true";
}

header("Location: clients.php?delete=" . $success);