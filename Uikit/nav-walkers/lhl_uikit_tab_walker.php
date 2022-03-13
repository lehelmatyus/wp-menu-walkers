<?php

class LhL_Uikit_Tab_Walker extends Walker_Nav_Menu {

    private $menu_type;

    function __construct($type='horizontal') {
        $this->menu_type = $type;
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

    }


    /**
     * Ends the list of after the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */

    public function end_lvl( &$output, $depth = 0, $args = null ) {

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

        // global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

        // Passed classes.
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        // is Active item 
        if(in_array('current_page_item', $classes)){
            $classes[] = 'uk-active';
        }

        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

        // Build HTML.
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="'. $class_names . '">';

        // Link attributes.
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        // Do something based on title
        switch ($item->title) {

            // case 'special-stuff':
            //     break;
            
            default:
                $link_title = apply_filters( 'the_title', $item->title, $item->ID );
                break;
        }

        // Build HTML output and pass through the proper filter.
        $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
            $args->before,
            $attributes,
            $args->link_before,
            $link_title,
            $args->link_after,
            $args->after
        );
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}

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
            'walker' => new Lhl_Uikit_Tab_Walker("nav"),
        ) );
    }
*/

?>


