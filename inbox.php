<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>Inbox</title>

    <!--Connectie naar DB-->
    <?php
    $servername = "localhost";
    $username = "recipeworld";
    $password = "root";
    $database = "recipeworld";
    session_start();
//    $_SESSION["id"] = "1";

    $connection = mysqli_connect($servername, $username, $password, $database);
    if (mysqli_connect_errno()) {
        die (mysqli_connect_error());
    }?>
</head>

<body>

<?php include 'menu.php';?>

<div class="container">

    <h1 style="margin-top:50px; margin-bottom:50px; text-align: center;">Inbox</h1>

    <?php

    if(empty($_POST['submit'])) {


    $queryGetPermission = "SELECT permission FROM recipeworld.clients WHERE client_id = " . $_SESSION['id'] . ";";
    $permission = mysqli_query($connection, $queryGetPermission);
    $permission = mysqli_fetch_assoc($permission);
    $permission = $permission['permission'];

    if($permission == 2) {

        $queryGetMessages = "SELECT concat(first_name, ' ', last_name) as fullname, email, inbox.client_id, message_message, message_timesend, inbox.message_id FROM recipeworld.inbox
                                 JOIN messages ON inbox.message_id = messages.message_id
                                 JOIN clients ON inbox.client_id = clients.client_id 
                                 WHERE permission = 1
                                 ORDER BY messages.message_timesend DESC;";
        $result = mysqli_query($connection, $queryGetMessages);

        ?>
        <table class="table table-striped"  style="table-layout:fixed;word-wrap: break-word;">
            <thead>
            <tr>
                <th width="7%">
                    Client ID
                </th>
                <th width="10%">
                    Naam
                </th>
                <th width="20%">
                    Email
                </th>
                <th>
                    Bericht
                </th>
                <th width="10%">
                    Datum Verstuurd
                </th>
                <th width="10%">
                    Delete
                </th>
            </tr>
            </thead>
            <tbody>
            <form method="POST" action="#">

                <?php
                while($message = mysqli_fetch_assoc($result)){
                    ?>

                    <tr>

                        <?php
                        echo "<td>" . $message['client_id'] . "</td>";
                        echo "<td>" . $message['fullname'] . "</td>";
                        echo "<td>" . $message['email'] . "</td>";
                        echo "<td>" . $message['message_message'] . "</td>";
                        echo "<td>" . $message['message_timesend'] . "</td>";
                        echo "<td><input type=\"checkbox\" name=\"" . $message['message_id'] . "\" value=\"Delete\"></td>";
                        ?>

                    </tr>

                    <?php
                }
                ?>  <tr><td><input type="submit" name=submit value="Verwijder"></td></tr>
            </tbody>
        </table>
        </form>






        <?php
    } elseif ($permission == 1) {


        $queryGetMessages = "SELECT first_name, message_message, message_timesend FROM recipeworld.inbox
                                     JOIN messages ON inbox.message_id = messages.message_id
                                     JOIN clients ON inbox.client_id = clients.client_id 
                                     WHERE permission = 2
                                     ORDER BY messages.message_timesend DESC;";
        $result = mysqli_query($connection, $queryGetMessages);

        ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="15%">
                    Naam
                </th>
                <th width="10px">
                    Bericht
                </th>
                <th align="right" width="17%">
                    Datum Verstuurd
                </th>
            </tr>
            </thead>
            <tbody>

            <?php
            while($message = mysqli_fetch_assoc($result)) {
                ?>
                <tr>

                    <?php
                    echo "<td>" . "Admin " . $message['first_name'] . "</td>";
                    echo "<td style='word-break:break-all;'>" . $message['message_message'] . "</td>";
                    echo "<td>" . $message['message_timesend'] . "</td>";
                    ?>
                </tr>


                <?php
            }
        }


        } elseif(isset($_POST['submit'])) {
            unset($_POST['submit']);

            foreach($_POST as $delMsgId => $tmp) {
                $queryDelMsg = "DELETE FROM inbox WHERE message_id = " . $delMsgId . ";";
                mysqli_query($connection, $queryDelMsg);
                $queryDelMsg = "DELETE FROM messages WHERE message_id = " . $delMsgId . ";";
                mysqli_query($connection, $queryDelMsg);

                echo "</br>";
                echo "Bericht " . $delMsgId . " Verwijderd!";
            }


        } else {echo "ERROR";}


        ?>
        </tbody>
        </table>



</div>

<?php include 'footer.php'; ?>

</body>
</html>


