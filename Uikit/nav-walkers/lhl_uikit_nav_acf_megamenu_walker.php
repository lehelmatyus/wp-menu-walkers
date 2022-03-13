<?php

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


/**
 * Add a sidebar
 * To hold MegaMenu regions
 */

function wpdocs_theme_slug_widgets_init() {

    $total_menuitems = 10;

    for ($i=1; $i <= $total_menuitems; $i++) {

        register_sidebar( array(
            'name'          => __( 'Mega Menu: megamenu-'. $i, 'textdomain' ),
            'id'            => 'megamenu-' . $i,
            'description'   => __( 'Mega Menu widget area for megamenu-'. $i, 'genesis-urbi' ),
            'before_widget' => '<div id="%1$s" class="widget megamenu_widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="megamenu_widget__title widgettitle">',
            'after_title'   => '</h2>',
        ) );

    }
}

add_action( 'widgets_init', 'wpdocs_theme_slug_widgets_init' );


class LhL_Uikit_Nav_Megamenu_Walker extends Walker_Nav_Menu {

    private $top_level_count;
    private $sub_level_count;
    private $menu_type;
    private $can_admin;

    function __construct($type, $parent_as_link = false, $can_admin = false) {
        $this->menu_type = $type;
        $this->can_admin = $can_admin;
        $this->parent_as_link = $parent_as_link;
        $this->top_level_count = 0;
        $this->sub_level_count = 0;
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

        // Depth-dependent classes.
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0

        // reset sublevel counter since a new level starts
        $this->sub_level_count = 0;

        $classes = array(
            'sub-menu',
            ( $this->menu_type == 'navbar' ? 'uk-nav uk-navbar-dropdown-nav' : 'uk-nav-sub'),
            ( $display_depth % 2  ? 'menu-depth-odd' : 'menu-depth-even' ),
            ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
            'menu-depth-' . $display_depth,
            'megamenu-submenu-list'
        );

        $class_names = implode( ' ', $classes );
        $ul_wrapper_div = "";

        if($this->menu_type == 'navbar'){
            $ul_wrapper_div = $display_depth >=1 ? '<div class="uk-navbar-dropdown"><div class="megamenu-item-wrapper">' : '';
        }

        // Gets populated by JS
        $megamenu_current_menu = '<div class="megamenu-current-menu-title">';
            $megamenu_current_menu .= '<h2 class="megamenu-current-menu-title-placeholder"></h2>';
        $megamenu_current_menu .= '</div>';

        // Build HTML for output.
        $output .= "\n" . $indent . $ul_wrapper_div . $megamenu_current_menu . '<ul class="' . $class_names . '">' . "\n";
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
            $ul_wrapper_div_end = $display_depth >=1 ? '</div></div>' : '';
        }

        // var_dump($args);
        $indent  = str_repeat( $t, $depth );
        $output .= "$indent" . "</ul>". "<div>" . $this->print_megamenu_region($this->top_level_count) . "</div>" . "$ul_wrapper_div_end" . "{$n}";

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

        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

        $nthitem = '';
        // count top items seperately
        // count sub items seperately inside their own lists

        if($depth == 0){
            $this->top_level_count++;
            $nthitem = $this->top_level_count;
        }else{
            $this->sub_level_count++;
            $nthitem = $this->sub_level_count;
        }

        // Depth-dependent classes.
        $depth_classes = array(
            ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
            ( $depth > 0 ? 'submenu-item-num--' . $this->sub_level_count : 'topmenu-item-num--' . $this->top_level_count ),
            ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
            ( $depth % 2 ? 'menu-depth-odd__item' : 'menu-depth-even__item' ),
            'menu-item-depth-' . $depth,

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
        // $megamenu_region = "hey";
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
        $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '" data-nthitem="' . $nthitem . '">';

        // Link attributes.
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' data-mega-id="#mega-id-' . $item->ID . '" ';
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

        if ($depth == 1){
            $mega_id = 'mega-id-' . $item->ID;
            $item_output .= sprintf($item->description, $mega_id);
        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

    }


    function print_megamenu_region($region_num){

        $output = "<div class='megamenu_region megamenu_region--". $region_num ."'>";

        if ( $this->can_admin ) {
            //$output .= "<a style='font-size:13px;' href='/wp-admin/widgets.php'> Widget area: megamenu-" . $region_num . "</a>";
        }

        if ( is_active_sidebar( 'megamenu-'. $region_num ) ) {

            // ob_start();
            // dynamic_sidebar('megamenu-'. $region_num);
            // $sidebar = ob_get_contents();
            // ob_end_clean();

            $output .= '<div id="megamenu-sidebar--' . $region_num . '" class="primary-sidebar widget-area mega-widget-area" role="complementary">';
                // $output .= $sidebar;
            $output .= '</div>';

        }

        $output .= "</div>";

        return $output;
    }

}
