<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

session_start();
$functions = new functions("recipeworld");
$products = $functions->getProducts();
$message = "";
if (isset($_GET["edit"])) {
    if ($_GET["edit"] === "true") {
        $message = "aangepast";
    } else {
        $message = false;
    }
}
if (isset($_GET["delete"])) {
    if ($_GET["delete"] === "true") {
        $message = "verwijderd";
    } else {
        $message = false;
    }
}
if (isset($_GET["add"])) {
    if ($_GET["add"] === "true") {
        $message = "toegevoegd";
    } else {
        $message = false;
    }
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
        <div class="col-md-12 custom-margin">
            <?php if (isset($_GET["delete"]) || isset($_GET["edit"]) || isset($_GET["add"])) {
                if ($message === false) {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Er is iets mis gegaan!</strong> Probeer het nog een keer.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                } elseif ($message !== false) {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Succes!</strong> Het product is <?php echo $message; ?>.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Naam</th>
                        <th scope="col">URL</th>
                        <th scope="col">Omschrijving</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($product = $products->fetch_assoc()) {
                    ?>
                    <tr>
                        <th scope="row"><?php echo $product["product_id"]; ?></th>
                        <td><?php echo $product["product_name"]; ?></td>
                        <td><?php echo $product["product_url"]; ?></td>
                        <td><?php echo $product["product_description"]; ?></td>
                        <td>
                            <form action="products_edit.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>" />
                                <button type="submit"><span class="fas fa-pencil-alt"></span></button>
                            </form>
                        </td>
                        <td>
                            <form action="products_delete.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>" />
                                <button type="submit"><span class="fas fa-trash-alt"></span></button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="custom-margin custom-add-button-bottom">
        <form action="products_edit.php" method="post">
            <button type="submit" class="btn btn-danger btn-circle btn-xl"><i class="fa fa-plus"></i></button>
        </form>
    </div>
    <div id="menu">
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>