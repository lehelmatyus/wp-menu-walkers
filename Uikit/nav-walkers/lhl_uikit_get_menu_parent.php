<?php

/*
* Gets the Parent of a Menu Item
*/
class LhL_Uikit_Get_Menu_Parent extends Walker_Nav_Menu {

    private $menu_type;
    private $curItem;

    function __construct($type, $parent_as_link = false) {
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

    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0 ) {
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
        // var_dump($item);
        // if(in_array("current-menu-parent", $item->classes)){
        //     $output = $item->object_id;
        // }

        if(in_array("current-menu-ancestor", $item->classes) && $item->menu_item_parent == 0){
            $output = $item->object_id;
        }

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


