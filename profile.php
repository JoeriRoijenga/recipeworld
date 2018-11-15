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
    if(empty($_GET['id'])) {
        $_GET['id'] = $_SESSION['id'];
    }

    if(isset($_GET['id'])) {
    $profileId = htmlspecialchars($_GET['id']);
    $profileId = mysqli_real_escape_string($connection, $profileId);
    $queryGetClient = "SELECT first_name, last_name, email, date_of_birth, permission_name, diet_name, last_online
                       FROM clients
                       JOIN diets ON clients.diet = diets.diet_id
                       JOIN permissions ON clients.permission = permissions.permissions_id
                      WHERE client_id = " . $_GET['id'] . ";";


    $result = mysqli_query($connection, $queryGetClient);
    $clientData = mysqli_fetch_assoc($result);

    ?>



</head>

<?php include 'menu.php';     ?>


<div class="container container-body">

    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">Profiel</h1>

    <div class="container" >
        <div class="container nopadding profile-part">
            <table>
                <th>
                    <div class="profile-bar">
                        Persoonlijke Gegevens
                    </div>
                </th>


                <tr class="profile-row">
                    <td class="profile-data-table">
                        <div class="profile-data">
                            Naam
                        </div>
                    </td>
                    <td>
                        <?php echo $clientData['first_name'] . " " . $clientData['last_name']; ?>
                    </td>
                </tr>

                <tr class="profile-row">
                    <td class="profile-data-table">
                        <div class="profile-data">
                            Email
                        </div>
                    </td>
                    <td>
                        <?php echo $clientData['email']; ?>
                    </td>
                </tr>

                <tr class="profile-row">
                    <td class="profile-data-table">
                        <div class="profile-data">
                            Geboortedatum
                        </div>
                    </td>
                    <td>
                        <?php echo $clientData['date_of_birth']; ?>
                    </td>
                </tr>


                <tr class="profile-row">
                    <td class="profile-data-table">
                        <div class="profile-data">
                            Rol:
                        </div>
                    </td>
                    <td>
                        <?php echo $clientData['permission_name']; ?>
                    </td>
                </tr>




            </table>
        </div>
        <br><br>
        <div class="container nopadding profile-part">
            <table>
                <th>
                    <div class="profile-bar">
                        Algemeen
                    </div>
                </th>


                <tr class="profile-row">
                    <td class="profile-data-table">
                        <div class="profile-data">
                            Dieet
                        </div>
                    </td>
                    <td>
                        <?php echo $clientData['diet_name']; ?>
                    </td>
                </tr>

                <tr class="profile-row">
                    <td class="profile-data-table">
                        <div class="profile-data">
                            Laatst online
                        </div>
                    </td>
                    <td>
                        <?php echo $clientData['last_online']; ?>
                    </td>
                </tr>
            </table>

            <br>
            <?php if($_GET['id']==$_SESSION['id']) {?>


                <form method="POST" action="editProfile.php">
                    <input type="submit" value="Bewerken" name="submit" class="btn btn-primary">
                </form>
            <?php } ?>
        </div>
        <?php } else { echo "<h1>ERROR 404</h1>";}?>

        </br>




    </div>




















</div>






</body>
</html>







<?php include 'footer.php'; ?>


