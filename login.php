<?php
spl_autoload_register(function ($classname) {
    include "classes/" . $classname . ".php";
});

session_start();
$functions = new functions("recipeworld");

if (isset($_POST["submit"])) {
    $login = false;
    $email = $functions->checkEmail($_POST["email"]);
    $password = $functions->checkValue($_POST["password"]);
    $account = $functions->checkAccount($email, $password);

    if ($account->num_rows === 1) {
        $login = $functions->setLogin($account);
        $functions->updateDateTime($_SESSION["id"]);
        header("location: fridge.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            <?php if (isset($login)) {
                if (!$login) {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Er is iets mis gegaan!</strong> U heeft niet de juiste inloggegevens ingevuld.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                } elseif ($login) {
                    ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Welkom <?php echo $_SESSION["name"]; ?>!</strong> U bent ingelogd.
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
                        <!-- Form Name -->
                        <legend>Login</legend>

                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">E-mailadres</label>
                            <div class="col-md-4">
                                <input id="email" name="email" type="text"
                                       placeholder="E-mailadres" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <!-- Password input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="password">Wachtwoord</label>
                            <div class="col-md-4">
                                <input id="password" name="password" type="password" placeholder="Wachtwoord" class="form-control input-md custom-width-textbox">
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" id="submit" name="submit" class="btn btn-primary">Login</button>
<!--                                <a href="#" >Wachtwoord vergeten?</a>-->
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="min-height"></div>
    <div id="menu">
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>