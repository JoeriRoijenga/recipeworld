<?php
session_start();
if (isset($_SESSION)) {
    if ($_SESSION["permission"] !== "1" && $_SESSION["permission"] !== "2") {
        header("location: home.php");
    }
} else {
    header("location: home.php");
}