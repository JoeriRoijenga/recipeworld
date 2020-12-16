<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>Contact</title>
    <!--Connectie naar DB-->
    <?php
    include 'redirects/normal.php';
    $servername = "localhost";
    $username = "recipeworld";
    $password = "root";
    $database = "recipeworld";
//    session_start();
//    $_SESSION["id"] = "6";

    $connection = mysqli_connect($servername, $username, $password, $database);

    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }
    ?>
</head>

<?php include 'menu.php';     ?>
<body>

<div class="container container-body">
    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">Contact</h1>

    <?php
    if(!isset($_POST['Submit'])) {?>

        <form method="post" action="#">

            <?php
            $query = "SELECT permission FROM clients WHERE client_id = " . $_SESSION['id'] . ";";
            $result = mysqli_query($connection, $query);
            $result = mysqli_fetch_assoc($result);
            if($result['permission'] == 2) {
                echo "<h6>" . "To everyone! Watch out." . "</h6>";
            }?>

            <span>Bericht:</span></br>
            <textarea name="Message" rows="10" cols="100" maxlength="800" placeholder="Maximaal 800 karakters."></textarea></br>
            <input type="submit" name="Submit" class="btn btn-primary">
        </form>
        <?php


    }elseif(empty($_POST['Message'])) {
        echo "<h4 style=\"color:gray;\">" . "Vul een bericht in!" . "</h4>";

    } elseif(isset($_POST['Message'])) {

        $message = htmlspecialchars($_POST['Message']);
        $querySend = "INSERT INTO recipeworld.messages (message_message, message_timesend) VALUES ('$message', now());";
        $message = mysqli_real_escape_string($connection, $message);
        mysqli_query($connection, $querySend);


        $queryGetMessageID = "SELECT max(message_id) AS m_id FROM recipeworld.messages;";
        $messageID = mysqli_query($connection, $queryGetMessageID);

        $messageID = mysqli_fetch_assoc($messageID);

        $messageID = $messageID['m_id'];
        $queryLink = "INSERT INTO recipeworld.inbox (message_id, client_id) VALUES (" . $messageID . ", " . $_SESSION["id"] . ");";
        mysqli_query($connection, $queryLink);



        echo "<h6>" . "Verstuurd!" . "</h6>";

    } else {
        echo "Er ging iets mis";
    }

    ?>





</div>






</body>
</html>








<?php include 'footer.php' ?>






