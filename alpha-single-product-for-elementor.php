<?php

/**
 * Plugin Name: Alpha Single Product For Elementor
 * Plugin URI: https://alphatrio.net
 * Description: Single WooCommerce Product Widget Addon For Elementor.
 * Author:      Ali Ali
 * Author URI:  https://github.com/Ali7Ali
 * Version:     1.0.5
 * Text Domain: alpha-single-product-for-elementor
 * Domain Path: /languages
 * License: GPLv3
 * 
 * 
 */

/* Copyright 2021 Ali Ali (email : ali.abdalhadi.ali@gmail.com) 
   
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('ALPHASP_VERSION', '1.0.5');
define('ALPHASP_ADDONS_PL_ROOT', __FILE__);
define('ALPHASP_PL_URL', plugins_url('/', ALPHASP_ADDONS_PL_ROOT));
define('ALPHASP_PL_PATH', plugin_dir_path(ALPHASP_ADDONS_PL_ROOT));
define('ALPHASP_PL_ASSETS', trailingslashit(ALPHASP_PL_URL . 'assets'));
define('ALPHASP_PL_INCLUDE', trailingslashit(ALPHASP_PL_PATH . 'include'));
define('ALPHASP_PL_LANGUAGES', trailingslashit(ALPHASP_PL_PATH . 'languages'));
define('ALPHASP_PLUGIN_BASE', plugin_basename(ALPHASP_ADDONS_PL_ROOT));

// Required File
include(ALPHASP_PL_INCLUDE . '/class-alpha-single-product.php');

function alpha_single_product_addon()
{

    // Load plugin file
    require_once(ALPHASP_PL_INCLUDE . '/class-alpha-single-product.php');

    // Run the plugin
    \Elementor_Alpha_Single_Product_Addon\Alpha_Single_Product_For_Elementor::instance();
}
add_action('plugins_loaded', 'alpha_single_product_addon');
