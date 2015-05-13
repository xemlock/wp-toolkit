<?php

/**
 * Render navigation menu.
 *
 * @param  array|string $options OPTIONAL
 * @return string|void
 */
function wptoolkit_nav_menu($options = null) // {{{
{
    $defaults = array(
        'container'   => 'nav',
        'depth'       => 2,
        'walker'      => null,
        'fallback_cb' => null,
        'items_wrap'  => '<ul id="{id}" class="{class}">{items}</ul>',
        'echo'        => false,
    );
    $options = wp_parse_args($options, $defaults);

    $options['theme_location'] = (string) $theme_location;
    $options['items_wrap'] = strtr($options['items_wrap'], array(
        '{id}'    => '%1$s', // options[menu_id]
        '{class}' => '%2$s', // options[menu_class]
        '{items}' => '%3$s',
    ));

    if (isset($options['walker'])) {
        $walker = $options['walker'];
    } else {
        $walker = 'bootstrap3';
    }

    if (!$walker instanceof Walker_Nav_Menu) {
        $class = 'wpToolkit_Walker_NavMenu_' . ucfirst($walker);
        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Invalid nav walker name: %s', $walker));
        }
        $options['walker'] = new $class();
        $options['fallback_cb'] = 'wpToolkit_Walker_NavMenu_Bootstrap3::fallback';
    }

    $options['walker'] = $walker;

    // wp-includes/nav-menu-template.php
    $menu = wp_nav_menu($options);

    if ($options['echo']) {
        echo $menu;
    } else {
        return $menu;
    }
} // }}}


