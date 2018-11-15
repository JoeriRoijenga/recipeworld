<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

session_start();
$functions = new functions("recipeworld");
$text = "Toevoegen";
$name = $url = $description = "";
$type = $hidden = $allergen = 0;
$types = $functions->getTypes();
$url = ["", ""];
$allergens = $functions->getAllergens();

if (isset($_POST["product_id"])){
    $id = $_POST["product_id"];
    $product = $functions->getProductById($id)->fetch_assoc();
    $text = "Aanpassen";
    $hidden = 1;
    $name = $product["product_name"];
    $url = ["", $product["product_url"]];
    $description = $product["product_description"];
    $type = $product["product_type"];
    $savedAllergens = $functions->getAllergensWhere($product["product_id"]);
    while($allergen = $savedAllergens->fetch_assoc()) {
        $arrayAllergens[] = $allergen["allergen_id"];
    }
}

if (isset($_POST["submit"])) {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    }

    $name = $functions->checkValue($_POST["product_name"]);
    $url = $functions->checkUrl($_POST["product_url"]);
    $description = $functions->checkValue($_POST["product_description"]);
    $type = $functions->checkType($_POST["product_type"]);
    $chosenAllergens = $functions->checkAllergens($_POST["product_allergens"]);

    if ($url[0] !== false AND $name !== false AND $description !== false AND $type !== false AND $chosenAllergens !== false) {
        if ($_POST["hidden"] === "0") {
            if ($functions->addProduct($name, $url[1], $description, $type, $chosenAllergens)) {
                header("Location: products.php?add=true");
            }
        } elseif ($_POST["hidden"] === "1") {
            if ($functions->editProduct($name, $url[1], $description, $type, $id, $chosenAllergens)) {
                header("Location: products.php?edit=true");
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
    <title>Producten</title>
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
                        <legend>Product <?php echo $text; ?></legend>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="product_name">Naam</label>
                            <div class="col-md-4">
                                <input id="product_name" name="product_name" type="text" placeholder="Naam" class="form-control input-md custom-width-textbox" value="<?php echo $name; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="product_url">URL</label>
                            <div class="col-md-4">
                                <input id="product_url" name="product_url" type="text" placeholder="URL" class="form-control input-md custom-width-textbox" value="<?php echo $url[1]; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="product_description">Omschrijving</label>
                            <div class="col-md-4">
                                <input id="product_description" name="product_description" type="text" placeholder="Omschrijving" class="form-control input-md custom-width-textbox"value="<?php echo $description; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="product_type">Type</label>
                            <div class="col-md-10">
                                <select id="product_type" name="product_type" class="form-control">
                                    <option value="0">Maak een keuze</option>
                                    <?php
                                    while ($productType = $types->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $productType["type_id"]; ?>" <?php if ($type === $productType["type_id"]) { echo "selected"; } ?>><?php echo $productType["type_name"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="product_allergens">Allergieën</label>
                            <div class="col-md-10">
                                <select name="product_allergens[]" class="custom-multi-select" multiple>
                                    <?php
                                    while ($productAllergen = $allergens->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $productAllergen["allergen_id"]; ?>" <?php if (in_array($productAllergen["allergen_id"], $arrayAllergens)) { echo "selected"; } ?>><?php echo $productAllergen["allergen_name"]; ?></option>
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