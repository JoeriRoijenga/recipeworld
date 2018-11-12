<?php
spl_autoload_register(function ($classname) {
    include "classes/" . $classname . ".php";
});

session_start();
$functions = new functions("recipeworld");
$text = "Toevoegen";
$name = $url = $description = "";
$type = 0;
$types = $functions->getTypes();

if (isset($_POST["id"])){
    $product = $functions->getProductById($_POST["id"])->fetch_assoc();
    $text = "Aanpassen";
    $name = $product["product_name"];
    $url = $product["product_url"];
    $description = $product["product_description"];
    $type = $product["product_type"];
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
</head>
<body>
<div id="menu">
    <?php include 'menu.php'; ?>
</div>
<div class="container-fluid">
    <div class="offset-4 col-md-4 custom-margin">
        <form action="" method="post">
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
                            <input id="product_url" name="product_url" type="text" placeholder="URL" class="form-control input-md custom-width-textbox" value="<?php echo $url; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="description">Omschrijving</label>
                        <div class="col-md-4">
                            <input id="description" name="product_description" type="text" placeholder="Omschrijving" class="form-control input-md custom-width-textbox"value="<?php echo $description; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="type">Type</label>
                        <div class="col-md-10">
                            <select id="type" name="type" class="form-control">
                                <option value="0">Maak een keuze</option>
                                <?php
                                while ($productType = $types->fetch_assoc()) {
                                    ?>
                                    <option value="<?php $productType["type_id"]; ?>" <?php if ($type === $productType["type_id"]) { echo "selected";} ?>><?php echo $productType["type_name"]; ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" id="submit" name="submit" class="btn btn-primary"><?php echo $text; ?></button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
</body>
</html>