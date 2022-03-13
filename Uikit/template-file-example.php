<?php
// This is just an example how you can use it in your themes template files
// Feel free to delete this file
?>

<div class="uk-container">
    <nav class="uk-navbar-container" uk-navbar>
        <div class="uk-navbar-left">

            <a class="uk-navbar-item uk-logo" href="#">Lehel Matyus</a>

            <?php
               // print a nice menu bar
                __urbi_navbar_walker_print_menu_location('primary');
            ?>

        </div>
        <div class="uk-navbar-right">

        <div class="uk-navbar-item">
                <button class="uk-button uk-button-default">Button</button>
        </div>

        </div>
    </nav>
</nav>


<div>
    <div>
        Example Simple Menu
    </div>
    <div>
        <?php
            // prints a uikit menu
            __urbi_nav_walker_print_menu_location('footer-sitemap');
        ?>
    </div>
</div>