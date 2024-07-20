<?php

namespace Elementor_Alpha_Single_Product_Addon;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Alpha_Single_Product_For_Elementor class.
 *
 * The main class that initiates and runs the addon.
 *
 * @since 1.0.0
 */
final class Alpha_Single_Product_For_Elementor
{
    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     * @var string Minimum Elementor version required to run the addon.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.21.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the addon.
     */
    const MINIMUM_PHP_VERSION = '7.4';

    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     * @var \Elementor_Alpha_Single_Product_Addon\Alpha_Single_Product_For_Elementor The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     * @return \Elementor_Alpha_Single_Product_Addon\Alpha_Single_Product_For_Elementor An instance of the class.
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) {
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
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    /**
     * Load the plugin text domain.
     */
    public function i18n()
    {
        load_plugin_textdomain('alpha-single-product-for-elementor', false, ALPHASP_PL_LANGUAGES);
    }

    /**
     * Compatibility Checks
     *
     * Checks whether the site meets the addon requirement.
     *
     * @since 1.0.0
     * @access public
     */
    public function is_compatible()
    {

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        /**
         * Check if WooCommerce is activated
         */
        if (class_exists('woocommerce')) {
            return true;
        } else {
            add_action('admin_notices', [$this, 'admin_notice_missing_woocommerce']);
            return false;
        }

        return true;
    }

    /**
     * Initialize the plugin.
     */
    public function init()
    {

        $this->i18n();

        add_action('elementor/frontend/after_enqueue_styles', [$this, 'frontend_styles']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        add_filter('wc_add_to_cart_params', function ($params) {
            // if we're on a WooCommerce page 
            if (is_woocommerce()) {
                return $params;
            }

            // Set the 'View cart' text
            $params['i18n_view_cart'] = __('Go to cart',  'alpha-single-product-for-elementor');
            // Set the 'View cart' URL
            $params['cart_url'] =  esc_url(wc_get_cart_url());
            return $params;
        });
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'alpha-single-product-for-elementor'),
            '<strong>' . esc_html__('Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'alpha-single-product-for-elementor') . '</strong>'
        );

        $elementor     = 'elementor/elementor.php';
        $pathpluginurl = \WP_PLUGIN_DIR . '/' . $elementor;
        $isinstalled   = file_exists($pathpluginurl);
        // If installed but didn't load
        if ($isinstalled && !did_action('elementor/loaded')) {
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);
            $button_text = esc_html__('Activate Elementor', 'alpha-single-product-for-elementor');
        } else {
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            $button_text = esc_html__('Install Elementor', 'alpha-single-product-for-elementor');
        }
        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have WooCommerce installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_woocommerce()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'alpha-single-product-for-elementor'),
            '<strong>' . esc_html__('Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor') . '</strong>',
            '<strong>' . esc_html__('WooCommerce', 'alpha-single-product-for-elementor') . '</strong>'
        );

        $woocommerce     = 'woocommerce/woocommerce.php';
        $pathpluginurl = \WP_PLUGIN_DIR . '/' . $woocommerce;
        $isinstalled   = file_exists($pathpluginurl);
        // If installed but didn't load
        if ($isinstalled && !class_exists('woocommerce')) {
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce);
            $button_text = esc_html__('Activate WooCommerce', 'alpha-single-product-for-elementor');
        } else {
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
            $button_text = esc_html__('Install WooCommerce', 'alpha-single-product-for-elementor');
        }
        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version()
    {

        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'alpha-single-product-for-elementor'),
            '<strong>' . esc_html__('Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'alpha-single-product-for-elementor') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version()
    {

        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'alpha-single-product-for-elementor'),
            '<strong>' . esc_html__('Alpha Single Product Widget for Elementor', 'alpha-single-product-for-elementor') . '</strong>',
            '<strong>' . esc_html__('PHP', 'alpha-single-product-for-elementor') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Loading plugin css.
     */
    public function frontend_styles()
    {
        wp_enqueue_style('alphasp-widget', ALPHASP_PL_ASSETS . 'css/alpha-sp-widget.css', '', ALPHASP_VERSION);
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
    public function register_widgets($widgets_manager)
    {
        // Include Widget files
        require_once ALPHASP_PL_INCLUDE . '/class-alpha-single-product-widget.php';
        // Register widget
        $widgets_manager->register(new \Elementor_Alpha_Single_Product_Addon\Alpha_SP_Widget());
    }
}
