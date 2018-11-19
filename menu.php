<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Recipe World</a>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="recipe.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <?php
            if (isset($_SESSION["id"])) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="fridge.php">Koelkast</a>
                </li>
            <?php
            }
            if (isset($_SESSION["id"])) {
                if ($_SESSION["permission"] === "2") {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Producten</a>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="recipe.php" method="get">
            <input class="form-control mr-sm-2" type="search" placeholder="Zoeken" aria-label="search" name="search">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit"><span class="fas fa-search"></span></button>
        </form>
        <ul class="navbar-nav mt-2 mt-lg-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    if (isset($_SESSION["name"])) {
                        echo $_SESSION["name"];
                    } else {
                        echo "Account";
                    }
                    ?>
                </a>
                <div class="dropdown-menu custom-dropdown" aria-labelledby="navbarDropdown">
                    <?php if (isset($_SESSION["id"])) { ?>
                        <a class="dropdown-item" href="profile.php">Profiel</a>
                        <a class="dropdown-item" href="inbox.php">Inbox</a>
                        <a class="dropdown-item" href="logout.php">Uitloggen</a>
                    <?php } else { ?>
                        <a class="dropdown-item" href="login.php">Inloggen</a>
                        <a class="dropdown-item" href="register.php">Registratie</a>
                    <?php } ?>
                </div>
            </li>
        </ul>
    </div>
</nav>