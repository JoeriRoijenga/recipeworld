<!doctype html>
<html lang="en" style="height: 100%">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stylesheet.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>Recipe World</title>
    <!--Connectie naar DB-->
    <?php
    $servername = "localhost";
    $username = "recipeworld";
    $password = "root";
    $database = "recipeworld";
//    session_start();

    $connection = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }
    include 'menu.php';
    ?>

<body>
<div class="container container-body">


    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">Welkom op Recipe world!</h1>


    <div class="row" style="margin-bottom: 10%;">
        <div class="col-sm-6" style="padding:0;">
            <?php
            $queryGetNewRecipes = "select recipe_id, recipe_name from recipes order by recipe_views DESC LIMIT 5;";
            $recipes = $connection->query($queryGetNewRecipes);
            $x = 1;
            ?>
            <h2 style="margin-bottom:10px;">Populairste recepten:</h2>




            <?php
            while($recipe = $recipes->fetch_assoc()) {
                echo $x . ". ";
                $x++;
                echo "<a href='recipeprofile.php?id=" . $recipe['recipe_id'] . "'>" . $recipe['recipe_name'] . "</a>";
                echo "</br>";
            } ?>


        </div>
        <div class="col-sm-6">
            <h2>Categorieën</h2>

            <?php

            $queryGetCategories = "SELECT category_name, category_id FROM categories;";
            $categories = $connection->query($queryGetCategories);
            while($category = $categories->fetch_assoc()){

                echo "<a href='recipe.php?category=" . $category['category_id'] . "'>" . $category['category_name'] . "</a>";
                echo "<br>";
            }
            ?>



        </div>

    </div>




</div>
<?php        include 'footer.php';?>




</body>
</html>



