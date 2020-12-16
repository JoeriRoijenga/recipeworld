<!doctype html>
<html lang="en" style="height: 100%">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>Over ons</title>
    <!--Connectie naar DB-->
    <?php
    $servername = "localhost";
    $username = "recipeworld";
    $password = "root";
    $database = "recipeworld";
    session_start();
    $_SESSION["id"] = "6";

    $connection = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }
    include 'menu.php';
    ?>

<body>
<div class="container container-body">


    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">Over Ons</h1>

    <div class="container" style="padding:0;">
        <h2 style="margin-bottom:25px;">Recipeworld</h2>
        <div class="col-sm-7" style="border-radius: 5px; border: 2px solid #007BFF ; margin-bottom: 50px;">
            Wij zijn een groep studenten die HBO-ICT studeren aan de Hanze Hogeschool in Groningen. Om onze programmeervaardigheden te verbeteren hebben wij besloten om een receptenwebsite te maken. <br><br>
            Deze website hebben we gemaakt voor iedereen die niet weet wat ze moeten eten. Omdat duurzaamheid erg belangrijk is, vinden we het belangrijk dat je nog zoveel mogelijk recepten kunt maken met de producten in je koelkast. Met dit uitgangspunt hebben wij ons allereerste project in elkaar gezet.
        </div>


    </div>


<div style="min-height: 50px;"></div>

</div>
<?php        include 'footer.php';?>




</body>
</html>


