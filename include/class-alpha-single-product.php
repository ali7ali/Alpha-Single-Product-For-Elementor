<?php
/**
 * Alpha Single Product
 *
 * @package AlphaSingleProduct
 */

namespace Elementor_Alpha_Single_Product_Addon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Alpha_Single_Product_For_Elementor class.
 *
 * The main class that initiates and runs the addon.
 *
 * @since 1.0.0
 */
final class Alpha_Single_Product_For_Elementor {

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var   string Minimum Elementor version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.21.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var   string Minimum PHP version required to run the addon.
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Instance
	 *
	 * @since  1.0.0
	 * @access private
	 * @static
	 * @var    \Elementor_Alpha_Single_Product_Addon\Alpha_Single_Product_For_Elementor The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @static
	 * @return \Elementor_Alpha_Single_Product_Addon\Alpha_Single_Product_For_Elementor An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', array( $this, 'init' ) );
		}
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function is_compatible() {
		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return false;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return false;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return false;
		}

		/**
		 * Check if WooCommerce is activated
		 */
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_woocommerce' ) );
			return false;
		}

		return true;
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_styles' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		add_filter(
			'wc_add_to_cart_params',
			function ( $params ) {
				// if we're on a WooCommerce page.
				if ( is_woocommerce() ) {
					return $params;
				}

				// Set the 'View cart' text.
				$params['i18n_view_cart'] = __( 'Go to cart', 'alpha-single-product-for-elementor' );
				// Set the 'View cart' URL.
				$params['cart_url'] = esc_url( wc_get_cart_url() );
				return $params;
			}
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			__( '"%1$s" requires "%2$s" to be installed and activated.', 'alpha-single-product-for-elementor' ),
			'<strong>' . __( 'Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor' ) . '</strong>',
			'<strong>' . __( 'Elementor', 'alpha-single-product-for-elementor' ) . '</strong>'
		);

		$elementor     = 'elementor/elementor.php';
		$pathpluginurl = \WP_PLUGIN_DIR . '/' . $elementor;
		$isinstalled   = file_exists( $pathpluginurl );

		// If installed but not activated.
		if ( $isinstalled && ! did_action( 'elementor/loaded' ) ) {
			$activation_url = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . $elementor . '&plugin_status=all&paged=1&s' ),
				'activate-plugin_' . $elementor
			);
			$button_text    = __( 'Activate Elementor', 'alpha-single-product-for-elementor' );
		} else {
			// If not installed.
			$activation_url = wp_nonce_url(
				self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ),
				'install-plugin_elementor'
			);
			$button_text    = __( 'Install Elementor', 'alpha-single-product-for-elementor' );
		}

		// Prepare button HTML.
		$button = sprintf(
			'<p><a href="%s" class="button-primary">%s</a></p>',
			esc_url( $activation_url ),
			esc_html( $button_text )
		);

		// Allowed HTML tags.
		$allowed_html = array(
			'strong' => array(),
			'p'      => array(),
			'a'      => array(
				'href'  => array(),
				'class' => array(),
			),
			'div'    => array(
				'class' => array(),
			),
		);

		// Output the notice.
		printf(
			'<div class="notice notice-warning is-dismissible">%s%s</div>',
			wp_kses( '<p>' . $message . '</p>', $allowed_html ),
			wp_kses( $button, $allowed_html )
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have WooCommerce installed or activated.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_missing_woocommerce() {
		$message = sprintf(
		/* translators: 1: Plugin name 2: WooCommerce */
			__( '"%1$s" requires "%2$s" to be installed and activated.', 'alpha-single-product-for-elementor' ),
			'<strong>' . __( 'Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor' ) . '</strong>',
			'<strong>' . __( 'WooCommerce', 'alpha-single-product-for-elementor' ) . '</strong>'
		);

		$woocommerce   = 'woocommerce/woocommerce.php';
		$pathpluginurl = \WP_PLUGIN_DIR . '/' . $woocommerce;
		$isinstalled   = file_exists( $pathpluginurl );

		if ( $isinstalled && ! class_exists( 'woocommerce' ) ) {
			// WooCommerce is installed but not activated.
			$activation_url = wp_nonce_url(
				self_admin_url( 'plugins.php?action=activate&plugin=' . $woocommerce . '&plugin_status=all&paged=1&s' ),
				'activate-plugin_' . $woocommerce
			);
			$button_text    = __( 'Activate WooCommerce', 'alpha-single-product-for-elementor' );
		} else {
			// WooCommerce is not installed.
			$activation_url = wp_nonce_url(
				self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ),
				'install-plugin_woocommerce'
			);
			$button_text    = __( 'Install WooCommerce', 'alpha-single-product-for-elementor' );
		}

		// Prepare button HTML.
		$button = sprintf(
			'<p><a href="%s" class="button-primary">%s</a></p>',
			esc_url( $activation_url ),
			esc_html( $button_text )
		);

		// Allowed HTML tags.
		$allowed_html = array(
			'strong' => array(),
			'p'      => array(),
			'a'      => array(
				'href'  => array(),
				'class' => array(),
			),
			'div'    => array(
				'class' => array(),
			),
		);

		// Output the notice.
		printf(
			'<div class="notice notice-warning is-dismissible">%s%s</div>',
			wp_kses( '<p>' . $message . '</p>', $allowed_html ),
			wp_kses( $button, $allowed_html )
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		$message = sprintf(
		/* translators: 1: Plugin name, 2: Elementor, 3: Required Elementor version */
			__( '"%1$s" requires the "%2$s" plugin version %3$s or greater.', 'alpha-single-product-for-elementor' ),
			'<strong>' . __( 'Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor' ) . '</strong>',
			'<strong>' . __( 'Elementor', 'alpha-single-product-for-elementor' ) . '</strong>',
			esc_html( self::MINIMUM_ELEMENTOR_VERSION )
		);

		// Allowed HTML tags.
		$allowed_html = array(
			'strong' => array(),
			'p'      => array(),
			'div'    => array(
				'class' => array(),
			),
		);

		// Output the notice.
		printf(
			'<div class="notice notice-warning is-dismissible">%s</div>',
			wp_kses( '<p>' . $message . '</p>', $allowed_html )
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		$message = sprintf(
		/* translators: 1: Plugin name, 2: PHP, 3: Required PHP version */
			__( '"%1$s" requires "%2$s" version %3$s or greater.', 'alpha-single-product-for-elementor' ),
			'<strong>' . __( 'Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor' ) . '</strong>',
			'<strong>' . __( 'PHP', 'alpha-single-product-for-elementor' ) . '</strong>',
			esc_html( self::MINIMUM_PHP_VERSION )
		);

		// Allowed HTML tags.
		$allowed_html = array(
			'strong' => array(),
			'p'      => array(),
			'div'    => array(
				'class' => array(),
			),
		);

		// Output the notice.
		printf(
			'<div class="notice notice-warning is-dismissible">%s</div>',
			wp_kses( '<p>' . $message . '</p>', $allowed_html )
		);
	}

	/**
	 * Loading plugin css.
	 */
	public function frontend_styles() {
		wp_enqueue_style( 'alphasp-widget', ALPHASP_PLUGIN_ASSETS . 'css/alpha-sp-widget.css', array(), ALPHASP_VERSION );
	}

	/**
	 * Register Widgets
	 *
	 * Load widgets files and register new Elementor widgets.
	 *
	 * Fired by `elementor/widgets/register` action hook.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		// Include Widget files.
		include_once ALPHASP_PLUGIN_INCLUDE . '/class-alpha-single-product-widget.php';
		// Register widget.
		$widgets_manager->register( new \Elementor_Alpha_Single_Product_Addon\Alpha_SP_Widget() );
	}
}
