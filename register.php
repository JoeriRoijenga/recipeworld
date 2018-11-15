<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

$functions = new functions("recipeworld");
$diets = $functions->getDiets();

if (isset($_POST["submit"])) {
    $register = false;
    $firstName = $functions->checkValue($_POST["firstname"]);
    $lastName = $functions->checkValue($_POST["lastname"]);
    $email = $functions->checkEmail($_POST["email"]);
    $password = $functions->checkPassword($functions->checkValue($_POST["password"]), $functions->checkValue($_POST["password-check"]));
    $birthday = $functions->checkDate($_POST["birthday"]);
    $diet = $functions->checkDiet($_POST["diet"]);
    if ($firstName !== "" AND $lastName !== "" AND !$email AND !$password AND !$birthday AND !$diet) {
        $register = $functions->registerClient($firstName, $lastName, $email, $password, $birthday, $diet);
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registreer</title>
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
            <?php if (isset($register)) {
                if (!$register) {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Er is iets mis gegaan!</strong> Het aanmaken van het account is niet gelukt. Het account bestaat al of opgegevens kloppen niet.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                } elseif ($register) {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Het account is aangemaakt voor <?php echo $firstName . " " . $lastName; ?>!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="offset-2">
                <form class="form-horizontal" action="#" method="post">
                    <fieldset>
                        <legend>Registreer</legend>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="firstname">Voornaam</label>
                            <div class="col-md-4">
                                <input id="firstname" name="firstname" type="text" placeholder="Voornaam" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="lastname">Achternaam</label>
                            <div class="col-md-4">
                                <input id="lastname" name="lastname" type="text" placeholder="Achternaam" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">E-mailadres</label>
                            <div class="col-md-4">
                                <input id="email" name="email" type="text" placeholder="e-mailadres" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="password">Wachtwoord</label>
                            <div class="col-md-4">
                                <input id="password" name="password" type="password" placeholder="Wachtwoord" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4">
                                <input id="password-check" name="password-check" type="password" placeholder="Wachtwoord Check" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="birthday">Geboortedatum</label>
                            <div class="col-md-4">
                                <input id="birthday" name="birthday" type="date" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="diet">Dieet</label>
                            <div class="col-md-10">
                                <select id="diet" name="diet" class="form-control">
                                    <option value="0">Maak een keuze</option>
                                    <?php
                                    while ($diet = $diets->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $diet['diet_id']; ?>"><?php echo $diet["diet_name"]; ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" id="submit" name="submit" class="btn btn-primary">Account Aanmaken</button>
                                <a href="login.php">Inloggen?</a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div id="menu">
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>