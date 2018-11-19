<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>FAQ</title>
    <!--Connectie naar DB-->
    <?php
    $servername = "localhost";
    $username = "recipeworld";
    $password = "root";
    $database = "recipeworld";
    session_start();
//    $_SESSION["id"] = "6";

    $connection = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }?>



</head>

<?php include 'menu.php';     ?>


<div class="container kleur">

    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">FAQ</h1>





    <div class="container">
        <div id="accordion">
            <div class="card" >
                    <button class="btn btn-primary btn-faq" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Waarom bestaat deze site?
                    </button>
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">

                        Deze site is ons eerste project van het eerste jaar HBO-ICT op de Hanze.

                    </div>
                </div>
            </div>
            <div class="card">
                <button class="btn btn-primary btn-faq" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
                    Hoe stuur ik een recept in?
                </button>

                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        Recepten kun je <a href="contact.php">hier</a> insturen.
                    </div>
                </div>
            </div>
            <div class="card">
                <button class="btn btn-primary btn-faq" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseOne">
                    Wie zijn wij?
                </button>

                <div id="collapseThree" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        Wij zijn een paar studenten op de Hanze Hogeschool, uit klas ITV1D.
                        </br></br>
                        Wilt u meer over ons weten? zie <a style="text-decoration: underline;" href="Over ons.php">Over ons</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <button class="btn btn-primary btn-faq" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseOne">
                    Hoe bewerk ik mijn profiel?
                </button>

                <div id="collapseFour" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        Om je profiel te bewerken moet je op de blauwe knop klikken op je profiel.
                    </div>
                </div>
            </div>





        </div>
    </div>




















</div>


<div style="min-height: 200px;"></div>




</body>
</html>








<?php include 'footer.php' ?>


