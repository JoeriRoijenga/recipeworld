<!DOCTYPE html>
<html>
<title>Recepten</title>
<body>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
      integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
<?php
include 'Connection.php';
include 'Menu.php';
$id = $_SESSION['id'];
?><!-- Connectie, menubalk -->
<h1 align="center">Recepten</h1>
<?php
$queryRecipes = "SELECT     recipe_id AS 'rID',
                            recipe_name AS 'rName',
                            recipe_description AS 'rDes',
                            recipe_views AS 'rViews',
                            category_id AS 'cID',
                            fridge_percentage AS 'fPct'
                            FROM recipes;";
$resultRecipes = mysqli_query($connection, $queryRecipes);
while ($fetchRecipes = mysqli_fetch_assoc($resultRecipes)) {
    $recipes[$fetchRecipes['rID']] = $fetchRecipes;
} // Alle recept ID's met eigenschappen worden opgehaald

foreach ($recipes as $key => $value) {
    $rID = $key;
    $queryIngredients = "SELECT
                            product_id AS 'iID',
                            product_name AS 'iName',
                            product_description AS 'iDes',
                            product_type AS 'iType'
                            FROM products
                            JOIN ingredients i USING (product_id)
                            WHERE i.recipe_id = " . $rID . ";";
    $resultIngredients = mysqli_query($connection, $queryIngredients);
    while ($fetchIngredients = mysqli_fetch_assoc($resultIngredients)) {
        $recipes[$rID]['ingredients'][$fetchIngredients['iID']] = $fetchIngredients;
        $ingredients[$rID][] = $fetchIngredients['iID'];
    } /*    Bij elk recept wordt alle informatie opgehaald en in 1 array gestopt. Daarnaast
            worden ook per recept de ingrediënt_ids opgehaald, om straks het pct te berekenen*/
    $queryFridgeProducts = "SELECT
                                product_id AS 'fpID',
                                product_name AS 'fpName',
                                product_description AS 'fpDes',
                                product_type AS 'fpType'
                                FROM products
                                JOIN fridges f USING (product_id)
                                JOIN ingredients i USING (product_id)
                                WHERE i.recipe_id = " . $rID . " AND f.client_id = " . $id . ";";
    $resultFridgeProducts = mysqli_query($connection, $queryFridgeProducts);
    while ($fetchFridgeProducts = mysqli_fetch_assoc($resultFridgeProducts)) {
        $recipes[$rID]['fridgeProducts'][$fetchFridgeProducts['fpID']] = $fetchFridgeProducts;
        $fridgeProducts[$rID][] = $fetchFridgeProducts['fpID'];
    } /*    Bij elk recept worden de ingrediënten opgehaald die tevens in het bezit zijn
            van de gebruiker. Ook worden hiervan de product_ids opgeslagen om pct te berekenen*/
} // Recepten en (overeenkomende) ingrediënten zijn opgehaald

foreach ($recipes as $key1 => $value1) {
    $rID = $key1;
    if (empty($ingredients[$rID])) {
        $pct = 0;
    } elseif (empty($fridgeProducts[$rID])) {
        $pct = 0;
    } else {
        $pct = round(100 / count($ingredients[$rID]) * count($fridgeProducts[$rID]), 2);
    }
    //$recipes[$rID]['%inFridge'] = $pct;
    $queryPctUpdate = "UPDATE   recipes
                                SET fridge_percentage = ".$pct."
                                WHERE recipe_id = ".$rID.";";
    mysqli_query($connection, $queryPctUpdate);
} // Percentage overeenkomende producten wordt per recept berekend en toegevoegd aan recept array
?> <!-- Querys voor: Recepten ophalen, Ingredienten ophalen, koelkastproducten ophalen -->

<?php
if (isset($_GET['clearFilter'])){
    unset($_GET['submitFilter']);
    $filter = "";
}
if (!isset($_GET['submitSrt'])){
    $sort = 'ORDER BY fridge_percentage DESC';
}
elseif (isset($_GET['submitSrt'])){
    if (isset($_GET['submitSort'])) {
        $sort = $_GET['submitSort'];
    }
}
$filterName = "";
$filterType = "";
$filter = "";
if (isset($_GET['submitFilter'])){
    if (isset($_GET['rName'])){
        $filterName = $_GET['rName'];
    }
    if (isset($_GET['recipeType'])){
        $filterType = $_GET['recipeType'];
    }
    if (isset($_GET['rName']) && !isset($_GET['recipeType'])){
        $filter = "WHERE recipe_name LIKE '%".$filterName."%'";
        //echo "$filter";
    }
    elseif (isset($_GET['recipeType']) && !isset($_GET['rName'])){
        $filter = "WHERE category_id = ".$filterType."";
    }
    elseif (isset($_GET['recipeType']) && isset($_GET['rName'])){
        $filter = "WHERE recipe_name LIKE '%".$filterName."%' AND category_id = ".$filterType."";
    }
}
$querySortIngredients = "SELECT     recipe_id AS 'rID',
                                    recipe_name AS 'rName',
                                    recipe_description AS 'rDes',
                                    recipe_views AS 'rViews',
                                    category_id AS 'cID',
                                    c.category_name AS 'cName',
                                    fridge_percentage AS 'fPct'
                                    FROM recipes JOIN categories c USING (category_id) ".$filter." ".$sort.";";
$resultSortIngredients = mysqli_query($connection, $querySortIngredients);
while ($fetchSortIngredients = mysqli_fetch_assoc($resultSortIngredients)){
    $recipesSorted[$fetchSortIngredients['rID']] = $fetchSortIngredients;
}
if (!empty($recipesSorted)) {
    foreach ($recipesSorted as $key5 => $value5) {
        $rID = $key5;
        $queryIngredientsSorted = "SELECT
                            product_id AS 'iID',
                            product_name AS 'iName',
                            product_description AS 'iDes',
                            product_type AS 'iType'
                            FROM products
                            JOIN ingredients i USING (product_id)
                            WHERE i.recipe_id = " . $rID . ";";
        $resultIngredientsSorted = mysqli_query($connection, $queryIngredientsSorted);
        while ($fetchIngredientsSorted = mysqli_fetch_assoc($resultIngredientsSorted)) {
            $recipesSorted[$rID]['ingredients'][$fetchIngredientsSorted['iID']] = $fetchIngredientsSorted;
        }
        $queryFridgeProductsSorted = "SELECT
                    product_id AS 'fpID',
                    product_name AS 'fpName',
                    product_description AS 'fpDes',
                    product_type AS 'fpType'
                    FROM products
                    JOIN fridges f USING (product_id)
                    JOIN ingredients i USING (product_id)
                    WHERE i.recipe_id = " . $rID . " AND f.client_id = " . $id . ";";
        $resultFridgeProductsSorted = mysqli_query($connection, $queryFridgeProductsSorted);
        while ($fetchFridgeProductsSorted = mysqli_fetch_assoc($resultFridgeProductsSorted)) {
            $recipesSorted[$rID]['fridgeProducts'][$fetchFridgeProductsSorted['fpID']] = $fetchFridgeProductsSorted;;
        }
    }
}
?>
<div class="row">
    <div class="col-sm-3">
        <form action="#" method="GET">
            <select name="submitSort">
                <option value="ORDER BY fridge_percentage DESC" <?php if ($sort == "ORDER BY fridge_percentage DESC"){echo "selected disabled";}?>>Producten in Koelkast (%)</option>
                <option value="ORDER BY recipe_views DESC" <?php if ($sort == "ORDER BY recipe_views DESC"){echo "selected disabled";};?>>Aantal keren bekeken</option>
                <option value="ORDER BY recipe_name ASC" <?php if ($sort == "ORDER BY recipe_name ASC"){echo "selected disabled";};?>>Alfabetisch A-Z</option>
                <option value="ORDER BY recipe_name DESC" <?php if ($sort == "ORDER BY recipe_name DESC"){echo "selected disabled";};?>>Alfabetisch Z-A</option>
            </select>
            <input type="submit" name="submitSrt" value="Sorteren">
        </form> <!-- Sorteerfunctie -->
        <br>
        <form action="#" method="GET">
            <input type="text" name="rName" value="<?=$filterName?>" placeholder="Zoek naar recepten">
            <h3>Filters</h3>
            <br>
            <?php //if (isset($_GET['rName'])){ echo "Recepten met: $filterName<br>";}?>
            <?php //if (isset($_GET['recipeType'])){ echo "Categorie: $filterType<br>";}?>
            <hr>
            <h4>Categorie</h4>
            <input type="radio" name="recipeType" value="1" <?php if (isset($_GET['recipeType']) && $filterType == 1){echo "checked";}?>>Pasta<br>
            <input type="radio" name="recipeType" value="2" <?php if (isset($_GET['recipeType']) && $filterType == 2){echo "checked";}?>>Rijst<br>
            <input type="radio" name="recipeType" value="3" <?php if (isset($_GET['recipeType']) && $filterType == 3){echo "checked";}?>>Ovenschotel<br>
            <input type="radio" name="recipeType" value="4" <?php if (isset($_GET['recipeType']) && $filterType == 4){echo "checked";}?>>Stamppot<br>
            <input type="radio" name="recipeType" value="5" <?php if (isset($_GET['recipeType']) && $filterType == 5){echo "checked";}?>>Pizza<br>
            <br>
            <input type="submit" name="submitFilter" value="Toon Resultaten">
            <input type="submit" name="clearFilter" value="Wis filters">
        </form>
    </div>
    <div <div class="col-sm-8">
        <?php
        if(empty($recipesSorted)){
            echo "Er zijn geen recepten gevonden";
        }
        else {
            foreach ($recipesSorted as $key2 => $value2) { $rID = $key2; ?>
                <fieldset>
                    <table border="1">
                        <tr>
                            <h3><a href='recipeprofile.php?id=<?=$rID?>'><?=htmlspecialchars($value2['rName'])?></a></h3>
                        </tr>
                        <tr>
                            <h5><?= $value2['rDes'] ?></h5>
                        </tr>
                        <tr>
                            <?= $value2['cName']?><br>
                        </tr>
                        <tr>
                            Aantal keren bekeken: <?= $value2['rViews'] ?><br>
                        </tr>
                        <tr>
                            U heeft <?= $value2['fPct'] ?>% van de ingrediënten in uw koelkast
                        </tr>
                        <tr>
                            <td>
                                Ingrediënten:
                            </td>
                            <td>
                                <?php
                                foreach ($recipesSorted[$rID]['ingredients'] as $key3 => $value3){
                                ?>
                            <td>
                                <?= $value3['iName'] ?>,
                                <?= $value3['iDes'] ?>
                            </td><?php } ?>
                        </tr>
                        <?php if (!empty($recipesSorted[$rID]['fridgeProducts'])) { ?>
                            <tr>
                                <td>
                                    Al in uw koelkast:
                                </td>
                                <td>
                                    <?php
                                    foreach ($recipesSorted[$rID]['fridgeProducts'] as $key4 => $value4){
                                    ?>
                                <td>
                                    <?= $value4['fpName'] . ", " . $value4['fpDes'] ?>
                                </td>
                                <?php } ?>
                            </tr>
                        <?php } elseif (empty($recipesSorted[$rID]['ingredients'])) {
                            ?>
                            <tr>
                                <td>
                                    Er zijn nog geen ingrediënten aan dit recept toegevoegd.
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <br><hr width="500" align="left"><br>
                </fieldset> <!-- Recepten naar het scherm schrijven -->
            <?php }} ?>
    </div>
</div>
<?php include 'footer.php'?>
</body>
</html>