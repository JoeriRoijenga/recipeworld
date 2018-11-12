<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

$functions = new functions();
$name = $_POST["name"];
$url = $_POST[];
$description = $_POST[];
$type = $_POST[];

$functions->addProduct()