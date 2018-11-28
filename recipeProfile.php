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
    session_start();

    $connection = mysqli_connect($servername, $username, $password, $database);



    include 'menu.php';
    ?>

<body>
<div class="container container-body">


    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;"></h1>

    <?php

    if (isset($_GET['id'])) {

    $recipe_id = $_GET['id'];

    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }

    if (!isset($_SESSION['views'])){
        $_SESSION['views'] = array();
    }


    if(!isset($_SESSION['views'][$recipe_id])) {
        $queryUpdateViews = "UPDATE recipes SET recipe_views = recipe_views + 1 WHERE recipe_id = " . $recipe_id . ";";
        mysqli_query($connection, $queryUpdateViews);
        $_SESSION['views'][$recipe_id] = "true";
    }

    $queryGetRecipe = "select * from recipes WHERE recipe_id = " . $recipe_id . ";";
    $recipe = $connection->query($queryGetRecipe);
    $recipe = $recipe->fetch_assoc();
    ?>

    <div class="container" style="padding:0;">
        <?php echo "<h1>" . $recipe['recipe_name'] . "</h1>" ; ?>

        <div class="row">
            <div class="col-sm-8">


                <br>
                <div style="min-height:300px;">
                    <?php echo $recipe['recipe_description']; ?>
                </div>
                <div>
                    <?php echo "Aantal keer bekeken: " . $recipe['recipe_views']; ?>
                </div>


                <?php
                } else {?>

                    <div style="min-height:300px;">
                        <?php echo "Geen recept geselecteerd"; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php

            if(empty($_GET['id'])){

            } else {


            ?>
            <div class="col-sm-4">
                <h5>Ingrediënten:</h5>




                <?php
                $queryGetIngredients = "SELECT * FROM ingredients JOIN products using (product_id) WHERE recipe_id = " . $recipe_id . ";";
                $result = mysqli_query($connection, $queryGetIngredients);


                $queryGetFridge = "SELECT * from fridges WHERE client_id = " . $_SESSION['id'] . ";";
                $resultFridge = mysqli_query($connection, $queryGetFridge);

                while($ingredient = mysqli_fetch_assoc($result)){
                    $inFridge = false;
                    mysqli_data_seek($resultFridge, 0);

                    while ($fridgeIngredient = mysqli_fetch_assoc($resultFridge)) {

                        if($ingredient['product_id'] == $fridgeIngredient['product_id']) {
                            $inFridge = true;
                        }

                    }

                    if ($inFridge) {
                        echo "</br>";
                        echo "<s>" . $ingredient['product_name'] . "</s>";
                    } else {
                        echo "</br>";
                        echo $ingredient['product_name'];
                    }

                }
                }
                ?>




            </div>
        </div>
    </div>



</div>
<?php        include 'footer.php';?>




</body>
</html>