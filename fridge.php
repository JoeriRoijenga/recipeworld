<!doctype html>
<?php
/*
 * Connectie naar DB
*/
$servername = "localhost";
$username = "recipeworld";
$password = "root";
$database = "recipeworld";
session_start();
//$_SESSION["id"] = "5";

$connection = mysqli_connect($servername, $username, $password, $database);

if (mysqli_connect_errno()) {
    die (mysqli_connect_error());
}
/*
Query om naam op te halen
*/
$naamquery = "SELECT first_name FROM clients WHERE client_id = " . $_SESSION["id"];
$naamresult = mysqli_query($connection, $naamquery) or die (mysqli_error($connection));
?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <title>Mijn Koelkast</title>
</head>
<body>
    <div id="menu">
        <?php include 'menu.php'; ?>
    </div>

<div class="container">
    <!--P R O D U C T E N-->
    <center><h2><?php
            foreach ($naamresult as $naam) {
                echo $naam["first_name"] . "'s";
            } ?> Koelkast</h2></center>
</div>
<hr>
<?php
/*
 * Toevoeg/verwijderquery voor producten
 */
if (isset($_POST["verwijderp"])) {
    $removequery = 'DELETE FROM fridges WHERE client_id = ' . $_SESSION["id"] . ' AND product_id = ' . $_POST["productid"];
    $removeresult = mysqli_query($connection, $removequery) or die (mysqli_error($connection));
}
if (isset($_POST["toevoegp"])) {
    $toevoegquery = 'INSERT IGNORE INTO `fridges` (`client_id`, `product_id`) VALUES (' . $_SESSION["id"] . ', ' . $_POST["productid"] . ');';
    $toevoegresult = mysqli_query($connection, $toevoegquery) or die (mysqli_error($connection));
}
/*
 * Query om de huidige koelkast op te halen
 */
$query = "SELECT p.product_name, p.product_id FROM `fridges` f 
JOIN `products` p ON p.product_id = f.product_id 
JOIN `clients` c ON c.client_id = f.client_id WHERE c.client_id = " . $_SESSION["id"];
$result = mysqli_query($connection, $query) or die (mysqli_error($connection));

if ($result->num_rows > 0) {
    echo "<center>";
    foreach ($result as $item) {
        echo "<form method='post'><input type='hidden' name='productid' value='" . $item["product_id"] . "'/>";
        echo $item["product_name"] . " <button id=\"verwijderp\" name=\"verwijderp\" class=\"btn btn-primary\">Verwijder</button></form>";
    }
    echo "</center>";
} else echo "<center>U heeft nog geen producten in uw koelkast.</center>";
?>
<!--
Zoeken naar producten
-->
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <form>
        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="textinput"><font size="2.5"><b>Zoek een
                        product:</b></font></label>
            <div class="col-md-4">
                <input id="producten" name="producten" type="text" placeholder="Zoek een product"
                       class="form-control input-md">
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <div class="col-md-4">
                <button id="submitp" name="submitp" class="btn btn-primary">Verstuur</button>
            </div>
        </div>
    </form>
</form>
</fieldset>
<?php
if (isset($_POST["submitp"]) && $_POST["producten"] == NULL) {
    echo "U heeft niets ingevuld.";
    echo "<hr>";
} elseif (isset($_POST["submitp"])) {
    $query3 = "SELECT product_name, product_id FROM products WHERE product_name LIKE '%" . $_POST["producten"] . "%'";
    mysqli_real_escape_string($connection, $_POST["producten"]);
    $result3 = mysqli_query($connection, $query3) or die (mysqli_error($connection));
    ?>
    <!--Zoekresultaten producten-->
    <div class="container">
        <center><h3>Resultaten: </h3></center>
    </div>
    <?php
    if ($result3->num_rows > 0) {
        echo "<center>";
        foreach ($result3 as $foundp) {
            echo "<form method='post'><input type='hidden' name='productid' value='" . $foundp["product_id"] . "'/>";
            echo $foundp["product_name"] . " <button id=\"toevoegp\" name=\"toevoegp\" class=\"btn btn-primary\">Voeg toe</button></form>";
        }
        echo "</center>";
    } else echo "<center>Er zijn geen producten gevonden.</center>";
    echo "<hr>";
}
?>
<!--R E C E P T E N-->
<div class="container">
    <center><h2>Favoriete recepten</h2></center>
</div>
<hr>
<?php
/*
 * Toevoeg/verwijderquery voor recepten
 */
if (isset($_POST["verwijderr"])) {
    $removequery = 'DELETE FROM favourites WHERE client_id = ' . $_SESSION["id"] . ' AND recipe_id = ' . $_POST["recipeid"];
    $removeresult = mysqli_query($connection, $removequery) or die (mysqli_error($connection));
}
if (isset($_POST["toevoegr"])) {
    $toevoegquery = 'INSERT IGNORE INTO `favourites` (`client_id`, `recipe_id`) VALUES (' . $_SESSION["id"] . ', ' . $_POST["recipeid"] . ');';
    $toevoegresult = mysqli_query($connection, $toevoegquery) or die (mysqli_error($connection));
}

/*
 * Huidige opgeslagen recepten ophalen
 */

$query1 = "SELECT r.recipe_name, r.recipe_id FROM favourites f 
JOIN recipes r ON f.recipe_id = r.recipe_id 
WHERE f.client_id =" . $_SESSION["id"];
$result1 = mysqli_query($connection, $query1) or die (mysqli_error($connection));

/*Percentage berekenen*/
if ($result1->num_rows > 0) {
    while ($item = $result1->fetch_assoc()) {
        $percentage = "SELECT * FROM ingredients WHERE recipe_id = " . $item["recipe_id"];
        $perresult = mysqli_query($connection, $percentage) or die (mysqli_error($connection));
        $aantal = 0;
        while ($per = $perresult->fetch_assoc()) {
            $percentage1 = "SELECT * FROM fridges WHERE product_id = " . $per["product_id"] . " AND client_id = " . $_SESSION["id"];
            $per1result = mysqli_query($connection, $percentage1) or die (mysqli_error($connection));
            if ($per1result->num_rows >= 1) {
                $aantal++;
            }
        }
        $aantal = ($aantal / $perresult->num_rows) * 100;
        echo "<center>";
        echo "<form method='post'><input type='hidden' name='recipeid' value='" . $item["recipe_id"] . "'/>";
        echo $item["recipe_name"] . " " . $aantal . "%" . " <button id=\"verwijderr\" name=\"verwijderr\" class=\"btn btn-primary\">Verwijder</button></form>";
        echo "</center>";
    }
} else echo "<center>U heeft nog geen favoriete recepten.</center>";
?>
<!--
Zoeken naar recepten
-->
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <form>
        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="textinput"><font size="2.5"><b>Zoek een
                        recept:</b></font></label>
            <div class="col-md-4">
                <input id="recepten" name="recepten" type="text" placeholder="Zoek een recept"
                       class="form-control input-md">
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <div class="col-md-4">
                <button id="submitr" name="submitr" class="btn btn-primary">Verstuur</button>
            </div>
        </div>
    </form>
</form>
</fieldset>
<?php
if (isset($_POST["submitr"]) && $_POST["recepten"] == NULL) {
    echo "U heeft niets ingevuld.";
} elseif (isset($_POST["submitr"]) && isset($_POST["recepten"])) {
    $query2 = "SELECT recipe_name, recipe_id FROM recipes WHERE recipe_name LIKE '%" . $_POST["recepten"] . "%'";
    mysqli_real_escape_string($connection, $_POST["recepten"]);
    $result2 = mysqli_query($connection, $query2) or die (mysqli_error($connection));
    ?>
    <!--Resulaten recepten-->
    <div class="container">
        <center><h3>Resultaten: </h3></center>
    </div>
    <?php
    if ($result2->num_rows > 0) {
        echo "<center>";
        foreach ($result2 as $foundr) {
            echo "<form method='post'><input type='hidden' name='recipeid' value='" . $foundr["recipe_id"] . "'/>";
            echo $foundr["recipe_name"] . " <button id=\"toevoegr\" name=\"toevoegr\" class=\"btn btn-primary\">Voeg toe</button></form>";
        }
        echo "</center>";
    } else echo "<center>Er zijn geen recepten gevonden.</center>";
}
mysqli_close($connection);
?>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"-->
<!--        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"-->
<!--        crossorigin="anonymous"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"-->
<!--        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"-->
<!--        crossorigin="anonymous"></script>-->
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"-->
<!--        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"-->
<!--        crossorigin="anonymous"></script>-->
</br>
</body>
</html>
<?php
include "footer.php";
?>