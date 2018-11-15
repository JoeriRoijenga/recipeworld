<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>Profiel</title>
    <!--Connectie naar DB-->
    <?php
    $servername = "localhost";
    $username = "recipeworld";
    $password = "root";
    $database = "recipeworld";
    session_start();
//    $_SESSION["id"] = "6";

    $connection = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }

    if(empty($_POST)) {
        echo "<h1>ERROR 400</h1>";

    } elseif($_POST['submit'] == "Bewerken") {


    $queryGetClient = "SELECT first_name, last_name, email, date_of_birth, permission_name, diet_name, last_online, diet_id
                           FROM clients
                           JOIN diets ON clients.diet = diets.diet_id
                           JOIN permissions ON clients.permission = permissions.permissions_id
                          WHERE client_id = " . $_SESSION['id'] . ";";
    $result = mysqli_query($connection, $queryGetClient);
    $clientData = mysqli_fetch_assoc($result);

    ?>
    <style>
        td {
            width: 50%;
        }
    </style>
</head>
<body>
<?php include 'menu.php';?>


<h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">Bewerk account</h1>




<div class="container container-body">

    <div>

        <table class="table">

            <form method="POST" action="#">
                <tr>
                    <td class="align-right">
                        Naam:
                    </td>
                    <td>
                        <input type="text" name="first_name" value="<?php echo $clientData['first_name'];?>">
                        <input type="text" name="last_name" value="<?php echo $clientData['last_name']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="align-right">
                        Email:
                    </td>
                    <td>
                        <input type="text" name="email" value="<?php echo $clientData['email']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="align-right">
                        Geboortedatum:
                    </td>
                    <td>
                        <input type="text" name="date_of_birth" value="<?php echo $clientData['date_of_birth']; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="align-right">
                        Dieet:
                    </td>
                    <td>
                        <select name="diet_id">
                            <option value="1" <?php if($clientData['diet_id'] == 1){echo "selected";} ?>>Geen dieet</option>
                            <option value="2"<?php if($clientData['diet_id'] == 2){echo "selected";} ?>>Veganist</option>
                            <option value="3"<?php if($clientData['diet_id'] == 3){echo "selected";} ?>>Vegetarier</option>


                        </select>
                    </td>
                </tr>
                <tr><td>&nbsp</td><td>&nbsp</td></tr>
                <tr>
                    <td class="align-right">
                        <b>Wachtwoord:</b>
                    </td>
                    <td>
                        <input type="password" name="oldPassword" placeholder="Verplicht">
                    </td>
                </tr>
                <tr><td>&nbsp</td><td>&nbsp</td></tr>
                <tr>
                    <td class="align-right">
                        <i>Nieuw wachtwoord:</i>
                    </td>
                    <td>
                        <input type="password" name="newPassword" placeholder="Optioneel" value="">
                    </td>
                </tr>
                <tr>
                    <td class="align-right">
                        <i>Wachtwoord Bevestiging</i>
                    </td>
                    <td>
                        <input type="password" name="newPasswordConfirm" placeholder="Optioneel" value="">
                    </td>
                </tr>
        </table>
        <!--Tabel-->
    </div>

    <input type="submit" name="submit" value="Versturen" class="btn btn-primary">
    <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-secondary">Annuleren</a>
    </form>
    <div style="text-align: center;">


    </div>
    <?php } elseif($_POST['submit'] == "Versturen") {


        foreach($_POST as $clientValues => $newValue) {
            $_POST['$newValue'] = htmlspecialchars(mysqli_real_escape_string($connection, $newValue));
        }



        $queryGetPassword = "SELECT password FROM clients WHERE client_id = " . $_SESSION['id'] . ";";
        $result = mysqli_query($connection, $queryGetPassword);
        $result = mysqli_fetch_assoc($result);
        $savedPassword = $result['password'];

        $oldPassword = md5($_POST['oldPassword']);
        if($savedPassword === $oldPassword) {


            $queryUpdateClient = "UPDATE recipeworld.clients SET `first_name` = \"" . $_POST['first_name'] . "\", `last_name` = \"" . $_POST['last_name'] . "\", `email` = \"" . $_POST['email'] . "\", `date_of_birth` = \"" . $_POST['date_of_birth'] . "\", `diet` = " . $_POST['diet_id'] . " WHERE client_id = " . $_SESSION["id"] . ";";
            mysqli_query($connection, $queryUpdateClient);

            if(isset($_POST['newPassword']) && isset($_POST['newPasswordConfirm'])) {
                if($_POST['newPasswordConfirm'] === $_POST['newPassword']) {
                    $newPassword = $_POST['newPassword'];
                    $newPassword = md5($newPassword);

                    $queryUpdatePassword = "UPDATE recipeworld.clients SET `password` = \"" . $newPassword . "\" WHERE client_id = " . $_SESSION['id'] . ";";
                    mysqli_query($connection, $queryUpdatePassword);

                } else {$error = "Wachtwoord niet gelijk!";}
            } elseif (empty($_POST['newPassword']) or empty($_POST['newPasswordConfirm'])) {
                $error = "Wachtwoord niet bevestigd!";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Geen geldig email-adres!";
            }

        } else {$error = "Wachtwoord Fout!";}





        // Error pagina
        if (isset($error)) {


            include 'menu.php';?>



            <div class="container justify-content-center">
                <br><br><br>
                <?php
                echo "<h1>$error</h1>"
                ?>
                <br><br><br>
                <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-secondary">Terug</a>
                <br><br><br><br><br><br><br><br><br>
            </div>
            <div class="min-height"></div>

            <?php
            include 'footer.php';
        }



        // Gelukt-pagina
        ?>


        <?php
        if(empty($error)) {
            include 'menu.php';?>

            <div style="text-align: center;">
                <br><br><br>
                <h1>Opgeslagen!</h1>
                <br><br><br><br><br><br><br><br>
                <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-secondary">Terug</a>
            </div>

            <br><br><br><br><br>
            <br><br><br><br><br>

            <div class="min-height"></div>

            <?php
            include 'footer.php';
        }
    }

    ?>



</div>

</body>


