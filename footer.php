<footer class="footer">
    <div class="footer-container">
        <div class="footer-line">
            <hr style="text-align: center; width: 80%; border-color: black; margin-top: 10px">
        </div>


        <div class="row justify-content-center">

            <div class="col-sm-3">
                <div class="footer-title">
                    Recipeworld
                </div>
                <div class="footer-text">
                    Recipeworld is een receptenwebsite waar je recepten kan zoeken op basis van je gekozen ingredienten. Het is gecreërd voor ons eerste project van de HBO-ICT opleiding van de Hanze Hogeschool.<br><br>Zie <a href="about.php">Over ons</a> voor meer informatie

                </div>
            </div>

            <div class="col-sm-2">
                <div class="footer-title">
                    Quick links
                </div>
                <div class="footer-text">
                    <a href="searchrecipes.php"><span class="footer-links">Recepten</span></a></br>
<!--                    <a href="submit.php"><span class="footer-links">Insturen</span></a></br>-->
                    <?php if (isset($_SESSION["id"])) {
                        ?>
                        <a href="profile.php"><span class="footer-links">Profile</span></a></br>
                        <?php
                    }
                    ?>
                    <a href="faq.php"><span class="footer-links">FAQ</span></a></br>
                    <?php if (isset($_SESSION["id"])) { ?>
                    <a href="contact.php"><span class="footer-links">Contact</span></a></br>
                    <?php } ?>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="footer-title">
                    Contact
                </div>

                <div class="footer-text">
                    <a class="fas fa-phone"></a> +31 6 28288830 </br>
                    <a class="fas fa-envelope"></a> Klanten@RecipeWorld.nl

                    </br></br>
                    Zernikeplein 11 </br>
                    9747 AS, Groningen </br>
                    Nederland
                </div>
            </div>
        </div>
    </div>

</footer>