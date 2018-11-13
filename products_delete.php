<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

$functions = new functions("recipeworld");
$id = $_POST["product_id"];

$success = "false";

if ($functions->removeProduct($id)) {
    $success = "true";
}

header("Location: products.php?delete=" . $success);