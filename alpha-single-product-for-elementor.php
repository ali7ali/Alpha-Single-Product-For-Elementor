<?php
/**
 * Plugin Name: Alpha Single Product For Elementor
 * Plugin URI: https://ali-ali.org/
 * Description: Single WooCommerce Product Widget Addon For Elementor.
 * Author:      Ali Ali
 * Author URI:  https://github.com/Ali7Ali
 * Version:     1.3.0
 * Text Domain: alpha-single-product-for-elementor
 * Domain Path: /languages
 * Requires at least: 6.4
 * Tested up to: 7.0
 * Requires PHP: 7.4
 * License: GPLv3
 *
 * @package    AlphaSingleProduct
 */

/*
Copyright 2021 Ali Ali (email : ali.abdalhadi.ali@gmail.com)

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( defined( 'ALPHASP_PLUGIN_FILE' ) && realpath( (string) ALPHASP_PLUGIN_FILE ) !== realpath( __FILE__ ) ) {
	/**
	 * Display a notice when another copy of the plugin is already active.
	 */
	function alpha_single_product_for_elementor_duplicate_copy_notice(): void {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			esc_html__( 'Alpha Single Product For Elementor is already active from another plugin folder. Deactivate and delete the old copy before activating this one, or install the update using a ZIP whose top-level folder is alpha-single-product-for-elementor.', 'alpha-single-product-for-elementor' )
		);
	}
	add_action( 'admin_notices', 'alpha_single_product_for_elementor_duplicate_copy_notice' );

	register_activation_hook(
		__FILE__,
		static function (): void {
			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die(
				esc_html__( 'Alpha Single Product For Elementor is already active from another plugin folder. Deactivate and delete the old copy before activating this one, or install the update using a ZIP whose top-level folder is alpha-single-product-for-elementor.', 'alpha-single-product-for-elementor' ),
				esc_html__( 'Plugin activation blocked', 'alpha-single-product-for-elementor' ),
				array( 'back_link' => true )
			);
		}
	);

	return;
}

define( 'ALPHASP_VERSION', '1.3.0' );
define( 'ALPHASP_PLUGIN_FILE', __FILE__ );
define( 'ALPHASP_PLUGIN_URL', plugins_url( '/', ALPHASP_PLUGIN_FILE ) );
define( 'ALPHASP_PLUGIN_PATH', plugin_dir_path( ALPHASP_PLUGIN_FILE ) );
define( 'ALPHASP_PLUGIN_ASSETS', trailingslashit( ALPHASP_PLUGIN_URL . 'assets' ) );
define( 'ALPHASP_PLUGIN_INCLUDE', trailingslashit( ALPHASP_PLUGIN_PATH . 'include' ) );
define( 'ALPHASP_PLUGIN_LANGUAGES', trailingslashit( ALPHASP_PLUGIN_PATH . 'languages' ) );
define( 'ALPHASP_PLUGIN_BASENAME', plugin_basename( ALPHASP_PLUGIN_FILE ) );

// Required File.
require_once ALPHASP_PLUGIN_INCLUDE . 'class-alpha-single-product.php';

/**
 * Initializes the Alpha Single Product Addon and loads the main plugin class.
 *
 * @return void
 */
function alphasp_single_product_addon() {
	// Load the main plugin class.
	\Elementor_Alpha_Single_Product_Addon\Alpha_Single_Product_For_Elementor::instance();
}
add_action( 'plugins_loaded', 'alphasp_single_product_addon' );
