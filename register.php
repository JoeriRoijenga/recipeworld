<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});

$functions = new functions("recipeworld");
$diets = $functions->getDiets();

if (isset($_POST["submit"])) {
    $firstName = $functions->checkValue($_POST["firstname"]);
    $lastName = $functions->checkValue($_POST["lastname"]);
    $email = $functions->checkEmail($_POST["email"]);
    $password = $functions->checkPassword($functions->checkValue($_POST["password"]), $functions->checkValue($_POST["password-check"]));
    $birthday = $functions->checkDate($_POST["birthday"]);
    $diet = $functions->checkDiet($_POST["diet"]);

    $functions->registerClient($firstName, $lastName, $email, $password, $birthday, $diet);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registreer</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
</head>
<body>
    <div class="container-fluid">
        <div class="offset-4 col-sm-4 custom-margin">
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
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="submit" class="btn btn-primary">Account Aanmaken</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</body>
</html>