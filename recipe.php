<!DOCTYPE html>
<html>
<title>Recepten</title>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
      integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<?php
include 'Connection.php';
include 'Menu.php';
?><!-- Connectie, menubalk -->
<h1 align="center">Recepten</h1>

<?php
if (isset($_GET['submitSrt'])) {
    $sort = $_GET['submitSort'];
} else {
    $sort = "ingredients";
}

?> <!-- Bepaalt Sort -->

<?php
/*if(isset($_GET['clearFltr'])) {
    $echoSearch = "";
    $echoFavorites = "";
    $echoType = "";
    unset($_GET['submitFltr']);
}*/
$echoSearch = "";
$echoFavorites = "";
$echoType = "";
if (isset($_GET['submitFltr'])) {

    unset($_GET['clearFltr']);

    if (isset($_GET['rname']) and $_GET['rname'] !== '' and is_string($_GET['rname'])) {
        $echoSearch = "Zoeken op: <b>'" . $_GET['rname'] . "'</b><br>";
    } else {
        $echoSearch = "";
    }

    if (isset($_GET['recipeFavorite'])) {
        if ($_GET['recipeFavorite'] == "Ja") {
            $echoFavorites = "'Alleen <b>favorieten</b>'<br>";
        } elseif ($_GET['recipeFavorite'] == "Nee") {
            $echoFavorites = "'Geen <b>favorieten</b>'<br>";
        }
    } else {
        $echoFavorites = "";
    }

    if (isset($_GET['recipeType'])) {
        $echoType = "Soort gerecht: <b>'" . $_GET['recipeType'] . "'</b><br>";
    } else {
        $echoType = "";
    }
}
?> <!-- Laat zien welke filters zijn toegepast -->

<?php
if (!isset($_GET['submitSrt'])) {

    switch ($sort) {
        case "ingredients":
            $id = $_SESSION['id'];
            $queryR = "SELECT	recipe_id AS 'ID', recipe_name AS 'rname' FROM recipes;";
            $resultR = mysqli_query($connection, $queryR) or die (mysqli_error($connection));
            $echoSort = "Gesorteerd op: Producten in Koelkast (%)";

            while ($recipe = mysqli_fetch_assoc($resultR)) {
                $recipes [] = $recipe['ID'];
            }// Een array maken met alle recept ID's -->

            $queryProductsClient = "(SELECT f.product_id FROM products JOIN fridges f USING (product_id) JOIN clients c USING (client_id) WHERE c.client_id = " . $_SESSION['id'] . ");";
            $resultProductsClient = mysqli_query($connection, $queryProductsClient) or die (mysqli_error($connection));

            foreach ($resultProductsClient as $value) {
                $queryInFridge = "SELECT  p.product_id AS 'pID', 
                              p.product_name AS 'pname', 
                              p.product_description AS 'pdescription', 
                              r.recipe_id AS 'rID', 
                              r.recipe_name AS 'rname',
                              r.recipe_description AS 'rdescription',
                              r.recipe_views AS 'rfavorites'
                  FROM products p	
                      JOIN ingredients i USING (product_id)
				      JOIN recipes r USING (recipe_id)
                  WHERE i.product_id = '" . $value["product_id"] . "'";
                $resultInFridge = mysqli_query($connection, $queryInFridge) or die (mysqli_error($connection));
                while ($productsInFridge = mysqli_fetch_assoc($resultInFridge)) {
                    $inFridge [$productsInFridge['rID']] = [
                        'receptnaam' => $productsInFridge['rname'],
                        'receptbeschrijving' => $productsInFridge['rdescription'],
                        'receptfavorieten' => $productsInFridge['rfavorites'],
                        array(
                            'productID' => $productsInFridge['pID'],
                            'productnaam' => $productsInFridge['pname'],
                            'productbeschrijving' => $productsInFridge['pdescription'])
                    ];
                }
            }
            foreach ($recipes as $value) {
                $queryNotFridge = "SELECT  p.product_id AS 'pID', 
                              p.product_name AS 'pname', 
                              p.product_description AS 'pdescription', 
                              r.recipe_id AS 'rID', 
                              r.recipe_name AS 'rname',
                              r.recipe_description AS 'rdescription',
                              r.recipe_views AS 'rfavorites'
                  FROM products p	JOIN ingredients i USING (product_id)
				      JOIN recipes r USING (recipe_id)
				      JOIN fridges f USING (product_id)
                      JOIN clients  USING (client_id)
                  WHERE client_id = " . $id . " AND recipe_id <> " . $value . ";";
                $resultNotFridge = mysqli_query($connection, $queryNotFridge) or die (mysqli_error($connection));
                while ($productsNotFridge = mysqli_fetch_assoc($resultNotFridge)) {
                    $NotFridge [$productsNotFridge['rID']] = [
                        'receptnaam' => $productsNotFridge['rname'],
                        'receptbeschrijving' => $productsNotFridge['rdescription'],
                        'receptfavorieten' => $productsNotFridge['rfavorites'],
                        array(
                            'productID' => $productsNotFridge['pID'],
                            'productnaam' => $productsNotFridge['pname'],
                            'productbeschrijving' => $productsNotFridge['pdescription'])
                    ];
                }
            }
    }
}
if (isset($inFridge)  && isset($NotFridge)) {
    if (count($inFridge) !== 0 && count($NotFridge) !== 0) {
        ?>

        <!-- Select: sorteren -->
        <span style="text-align:left;"> Sorteren op: </span>
        <form action="#" method="GET">
            <select name="submitSort">
                <option value="ingredients">Producten in Koelkast (%)</option>
                <option value="recipe_favorites">Aantal favorieten</option>
                <option value="AZ">Alfabetisch A-Z</option>
                <option value="ZA">Alfabetisch Z-A</option>
            </select>
            <input type="submit" name="submitSrt" value="Sorteren"><?= $echoSort ?>
        </form>
        <!-- Filters kiezen en tonen -->
        <form name="filters">
            <input type="text" name="rname" placeholder="Zoek naar recepten">
            <h3>Filters</h3>
            <br>
            <hr>
            <?= $echoSearch ?>
            <?= $echoFavorites ?>
            <?= $echoType ?>
            <hr>
            <h4>Alleen favorieten</h4>
            <input type="radio" name="recipeFavorite" value="Ja">Ja<br>
            <input type="radio" name="recipeFavorite" value="Nee">Nee<br>


            <h4>Categorie</h4>
            <input type="radio" name="recipeType" value="Vlees">Vlees<br>
            <input type="radio" name="recipeType" value="Vis">Vis<br>
            <input type="radio" name="recipeType" value="Kip">Kip<br>
            <input type="radio" name="recipeType" value="Vegetarisch">Vegetarisch<br>
            <input type="radio" name="recipeType" value="Veganistisch">Veganistisch<br>
            <br>
            <input type="submit" name="submitFltr" value="Toon Resultaten">

        </form>
        <form name="clearFilters" action="#" method="GET">
            <input type="submit" name="clearFltr" value="Filters wissen">
        </form>
        <h2>In de koelkast:</h2>
        <?php
        foreach ($inFridge as $key => $value) {
            ?>
            <table>
                <th><?php echo $key . " - " . $value['receptnaam'] . " - " . $value['receptbeschrijving'];
                    // foreach (/array product/ as $product)
                    //   echo $product['productnaam'] . $product['productbeschrijving']; ?></th>
            </table>
            <?php
        }
        ?>

        <h2>Niet in de koelkast:</h2>

        <?php
        foreach ($NotFridge as $key => $value) {
            ?>
            <table>
                <th><?php echo $key . " - " . $value['receptnaam'] . " - " . $value['receptbeschrijving']; ?></th>
            </table>
            <?php
        } ?>

        <?php include 'footer.php';
    }
} else {
    echo "<h1>Er is iets misgegaan.</h1>";
}
?>
</body>
</html>