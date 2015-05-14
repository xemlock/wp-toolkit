<?php

if (defined('ABSPATH')) {
    // fix notice: Use of undefined constant SCRIPT_DEBUG in wp-includes/formatting.php on line 4144
    if (!defined('SCRIPT_DEBUG')) {
        define('SCRIPT_DEBUG', 0);
    }

    // load functions
    require_once __DIR__ . '/functions.php';
}
