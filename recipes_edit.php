<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

include "redirects/admin.php";

//session_start();
$functions = new functions("recipeworld");
$text = "Toevoegen";
$name = $url = $description = "";
$chosenCategory = $hidden = 0;
$categories = $functions->getCategories();
$products = $functions->getProducts();

if (isset($_POST["recipe_id"])) {
    $id = $_POST["recipe_id"];
    $recipe = $functions->getRecipeById($id)->fetch_assoc();
    $text = "Aanpassen";
    $hidden = 1;
    $name = $recipe["recipe_name"];
    $description = $recipe["recipe_description"];
    $chosenCategory = $recipe["category_id"];
    $ingredients = $functions->getIngredientsWhere($recipe["recipe_id"]);

    while($ingredient = $ingredients->fetch_assoc()) {
        $arrayIngredients[] = $ingredient["product_id"];
    }
}

if (isset($_POST["submit"])) {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    }

    $name = $functions->checkValue($_POST["recipe_name"]);
    $description = $functions->checkValue($_POST["recipe_description"]);
    $chosenCategory = $functions->checkCategory($_POST["recipe_category"]);
    if (isset($_POST["recipe_ingredients"])) {
        $chosenIngredients = $functions->checkIngredients($_POST["recipe_ingredients"]);
    } else {
        $chosenIngredients = [];
    }
    if ($name !== "" AND $description !== false AND $chosenCategory !== false AND $chosenIngredients !== false) {
        if ($_POST["hidden"] === "0") {
            if ($functions->addRecipe($name, $description, $chosenCategory, $chosenIngredients)) {
                header("Location: recipes.php?add=true");
            }
        } elseif ($_POST["hidden"] === "1") {
            if ($functions->editRecipe($name, $description, $chosenCategory, $chosenIngredients, $id)) {
                header("Location: recipes.php?edit=true");
            }
        }
    }

    $failed = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recepten</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>
    <div id="menu">
        <?php include 'menu.php'; ?>
    </div>
    <div class="container-fluid">
        <div class="offset-4 col-md-4 custom-margin">
            <?php if (isset($_POST["submit"])) {
                if ($failed) {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Er is iets mis gegaan!</strong> Probeer het nog een keer.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
            <form action="#" method="post">
                <fieldset>
                    <div class="form-group">
                        <legend>Recept <?php echo $text; ?></legend>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="recipe_name">Naam</label>
                            <div class="col-md-4">
                                <input id="recipe_name" name="recipe_name" type="text" placeholder="Naam" class="form-control input-md custom-width-textbox" value="<?php echo $name; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="recipe_description">Omschrijving</label>
                            <div class="col-md-4">
                                <input id="recipe_description" name="recipe_description" type="text" placeholder="Omschrijving" class="form-control input-md custom-width-textbox" value="<?php echo $description; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="recipe_category">Categorie</label>
                            <div class="col-md-4">
                                <select id="recipe_category" name="recipe_category">
                                    <option value="0">Maak een keuze</option>
                                    <?php
                                    while ($category = $categories->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $category["category_id"]; ?>" <?php if ($chosenCategory === $category["category_id"]) { echo "selected"; } ?>><?php echo $category["category_name"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="product_allergens">Allergieën</label>
                            <div class="col-md-10">
                                <select name="recipe_ingredients[]" class="custom-multi-select" multiple>
                                    <?php
                                    while ($product = $products->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $product["product_id"]; ?>" <?php if (isset($arrayIngredients)) {if (in_array($product["product_id"], $arrayIngredients)) { echo "selected"; }} ?>><?php echo $product["product_name"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <input type="hidden" name="hidden" value="<?php echo $hidden; ?>" />
                            <?php if (isset($id)) { ?>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <?php } ?>
                            <input type="submit" id="submit" name="submit" class="btn btn-primary" value="<?php echo $text; ?>"/>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div id="menu">
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>