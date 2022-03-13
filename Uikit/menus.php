<?php

// This file menus.php needs to be included in your functions.php

require_once( get_stylesheet_directory() . '/includes/nav-walkers/lhl_uikit_get_menu_parent.php');
require_once( get_stylesheet_directory() . '/includes/nav-walkers/lhl_uikit_nav_walker.php');
require_once( get_stylesheet_directory() . '/includes/nav-walkers/lhl_uikit_nav_acf_megamenu_walker.php');


#-----------------------------------------------------------------#
# Menu -- Register Menu Locations
# you can pass a menu location into one of the helper functions
#-----------------------------------------------------------------#

function urbi_register_my_menus() {
	register_nav_menus(
	  array(
		'banner-menu' => __( 'Top Banner Menu' ),
		'footer-sitemap' => __( 'Footer - Site Map Menu' ),
	  )
	);
  }
  add_action( 'init', 'urbi_register_my_menus' );


#-----------------------------------------------------------------#
#  Helper functions - to print Menus
#  custom functions to use in you template files
#  to print out menus,
#-----------------------------------------------------------------#

/*
* Generate Navigation Menu Bar
* pass a menu location
*/
function __urbi_nav_walker_print_menu_location($menu_location) {

    if ( has_nav_menu( $menu_location ) ) {
        wp_nav_menu( array(
            'container'       => '<div>',
            'menu_class' => 'uk-nav-parent-icon ' . $menu_location ."--menu",
            'items_wrap'      => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
            'theme_location' => $menu_location,
            'depth' => 1,
            'walker' => new LhL_Uikit_Nav_Walker("nav"),
        ) );
    }
}


/*
 * Generate Navigation Menu
 * pass a menu location
 */
function __urbi_navbar_walker_print_menu_location($menu_location) {
  if ( has_nav_menu( $menu_location ) ) {
      wp_nav_menu( array(
          'container' => 'ul',
          'menu_class' => 'uk-navbar-nav primary-menu',
          'theme_location' => $menu_location,
          'depth' => 2,
          'walker' => new LhL_Uikit_Nav_Walker("navbar"),
      ) );
  }
}
