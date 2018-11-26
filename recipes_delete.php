<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

include "redirects/admin.php";

$functions = new functions("recipeworld");
$id = $_POST["recipe_id"];

$success = "false";

if ($functions->removeRecipe($id)) {
    $success = "true";
}

header("Location: recipes.php?delete=" . $success);