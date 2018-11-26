<?php
spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});
include "redirects/admin.php";

//session_start();
$functions = new functions("recipeworld");
$clients = $functions->getClients();
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gebruikers</title>
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
        <div class="row">
            <div class="col-md-12 custom-margin">
                <?php if (isset($_GET["delete"]) || isset($_GET["edit"])) {
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
                            <strong>Succes!</strong> De gebruiker is <?php echo $message; ?>.
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
                            <th scope="col">E-mailadres</th>
                            <th scope="col">Laatst online</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($client = $clients->fetch_assoc()) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo $client["client_id"]; ?></th>
                            <td><?php echo $client["first_name"] . " " . $client["last_name"]; ?></td>
                            <td><?php echo $client["email"]; ?></td>
                            <td><?php echo $client["last_online"]; ?></td>
                            <td>
                                <form action="editProfile.php" method="post">
                                    <input type="hidden" name="client_id" value="<?php echo $client["client_id"]; ?>" />
                                    <button type="submit"><span class="fas fa-pencil-alt"></span></button>
                                </form>
                            </td>
                            <td>
                                <form action="clients_delete.php" method="post">
                                    <input type="hidden" name="client_id" value="<?php echo $client["client_id"]; ?>" />
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
    </div>
    <div id="menu">
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>