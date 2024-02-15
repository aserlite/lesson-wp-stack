<?php
/*
Plugin Name: Portfolio
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Il est super cool ce plugin
Author: Arthur
Version: 1.0.0
Author URI: http://arthur.com
*/

require_once 'inc/PostType.php';
require_once 'inc/Hooks.php';
require_once 'inc/Filters.php';
require_once 'inc/Metaboxes.php';
require_once 'inc/Taxonomies.php';
require_once 'inc/ACF.php';
require_once 'inc/CustomBlocks.php';

class Portfolio {

    public function init() {
        (new \Portfolio\PostType())->register();
        (new \Portfolio\Hooks())->register();
        (new \Portfolio\Metaboxes())->register();
        (new \Portfolio\Taxonomies())->register();
        (new \Portfolio\CustomBlocks())->register();
        (new \Portfolio\Filters())->register();

        (new \Portfolio\ACF())->register();

    }

}

(new Portfolio())->init();

