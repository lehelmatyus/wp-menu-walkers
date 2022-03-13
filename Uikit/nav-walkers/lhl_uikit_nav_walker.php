<?php

class LhL_Uikit_Nav_Walker extends Walker_Nav_Menu {

    private $menu_type;
    private $curItem;
    private $modifier;

    function __construct($type, $parent_as_link = false, $modifier = "") {

        $this->modifier = $modifier;
        $this->menu_type = $type;
        $this->parent_as_link = $parent_as_link;
    }

    /**
     * Starts the list before the elements are added.
     *
     * Adds classes to the unordered list sub-menus.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {

        // var_dump($this->curItem->ID );

        // Depth-dependent classes.
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent

        $display_depth = ( $depth + 1); // because it counts the first submenu as 0

        $classes = array(
            'sub-menu',
            ( $this->menu_type == 'navbar' ? 'uk-nav uk-navbar-dropdown-nav' : 'uk-nav-sub'),
            ( $display_depth % 2  ? '__' : 'menu-depth-even' ),
            ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth
        );

        $class_names = implode( ' ', $classes );

        $ul_wrapper_div = "";
        if($this->menu_type == 'navbar'){
            $ul_wrapper_div = $display_depth >=1 ? '<div class="uk-navbar-dropdown">' : '';
        }

        // Build HTML for output.
        $output .= "\n" . $indent . $ul_wrapper_div . '<ul class="' . $class_names . '">' . "\n";
    }


    /**
     * Ends the list of after the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */

    public function end_lvl( &$output, $depth = 0, $args = null ) {

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $display_depth = ( $depth + 1);

        $ul_wrapper_div_end = '';
        if($this->menu_type == 'navbar'){
            $ul_wrapper_div_end = $display_depth >=1 ? '</div>' : '';
        }

        $indent  = str_repeat( $t, $depth );
        $output .= "$indent" . "</ul>" . "$ul_wrapper_div_end" . "{$n}";

    }

    /**
     * Start the element output.
     *
     * Adds main/sub-classes to the list items and links.
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        // $this->curItem = $item;

        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

        // Depth-dependent classes.
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-depth-odd__item' : 'menu-depth-even__item' ),
            'menu-item-depth-' . $depth
        );
        $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );


        /**
         * Get Passed classes from WordPress
         */
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

        /**
         * For NAV
         * Check if Parent
         *
         *  For NAV type parent Li-s need
         *  if it's a parent it needs to have
         *  uk-parent class
         */
        if($this->menu_type == 'nav'){
            if($args->walker->has_children){
                $class_names .= ' uk-parent';
            }
        }


        /**
         *
         * Check if Currently active item
         */

        // if($this->menu_type == 'nav'){
            //Check if menu item is an ancestor of the current page
            $current_identifiers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' );
            // Check if item is a current item
            $ancestor_of_current = array_intersect( $current_identifiers, $classes );
            if( $ancestor_of_current ){
                // Yes on current item
                $class_names .= ' uk-open';
            }
        // }



        // Build HTML.
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . $this->modifier . '" class="' . $depth_class_names . ' ' . $class_names . '">';

        // Link attributes.
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        switch ($item->title) {
            case 'Search':
                $link_title = "<img class='menu_search' type='image' alt='Search' src='" . get_template_directory_uri() . "/images/search.png' /><img class='sticky_menu_search' type='image' alt='Search' src='" . get_template_directory_uri() . "/images/search-sticky.png' />";
                $attributes .= 'uk-toggle="target: #searchform; animation: uk-animation-fade"';
                break;

            default:
                $link_title = apply_filters( 'the_title', $item->title, $item->ID );
                break;
        }


        if ($this->parent_as_link) {

            // Build HTML output and pass through the proper filter.
            $item_output = sprintf( '%1$s<a%2$s>%3$s<span onclick="event.stopPropagation();">%4$s</span>%5$s</a>%6$s',
                $args->before,
                $attributes,
                $args->link_before,
                $link_title,
                $args->link_after,
                $args->after
            );

        }else {

            // Build HTML output and pass through the proper filter.
            $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                $args->before,
                $attributes,
                $args->link_before,
                $link_title,
                $args->link_after,
                $args->after
            );

        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}


/**
 * For regular Navigation use the following
 * https://getuikit.com/docs/navbar
 */

/*
    if ( has_nav_menu( 'YOUR_MENU_LOCATION' ) ) {
        wp_nav_menu( array(
            'container' => 'ul',
            'menu_class' => 'uk-navbar-nav',
            'theme_location' => 'YOUR_MENU_LOCATION',
            'depth' => 2,
            'walker' => new LhL_Uikit_Nav_Walker("navbar"),
        ) );
    }
*/

/**
 * For regular Navigation use the following
 * https://getuikit.com/docs/nav
 */

/*
    if ( has_nav_menu( 'YOUR_MENU_LOCATION' ) ) {
        wp_nav_menu( array(
            'container'       => '<div>',
            'menu_class' => 'uk-nav-primary uk-nav-parent-icon ',
            'items_wrap'      => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
            'theme_location' => 'YOUR_MENU_LOCATION',
            'depth' => 2,
            'walker' => new LhL_Uikit_Nav_Walker("nav"),
        ) );
    }
*/

/**
 * For Mobile Navigation use the following
 * https://getuikit.com/docs/nav
 */

/*
function urbi_primary_mobile_ex_in_navbar_menu() {
    $menu_location = "primary-mobile-extension-logged-in";

    if ( has_nav_menu( $menu_location ) ) {
        wp_nav_menu( array(
			'container'       => '<div>',
			'menu_class' => 'uk-nav-primary uk-nav-parent-icon menu-primary',
			'items_wrap'      => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
			'theme_location' => $menu_location,
			'depth' => 2,
			'walker' => new LhL_Uikit_Nav_Walker("nav"),
		) );
    }
}
*/


/**
 * For regular Navigation use the following
 * https://getuikit.com/docs/nav
 *
 * WITH: Extra option to make parent links clickable.
 */

/*
    if ( has_nav_menu( 'YOUR_MENU_LOCATION' ) ) {
        wp_nav_menu( array(
            'container'       => '<div>',
            'menu_class' => 'uk-nav-primary uk-nav-parent-icon ',
            'items_wrap'      => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
            'theme_location' => 'YOUR_MENU_LOCATION',
            'depth' => 2,
            'walker' => new LhL_Uikit_Nav_Walker("nav"),
        ) );
    }
*/

?>


