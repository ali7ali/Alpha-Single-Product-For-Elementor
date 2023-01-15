<?php

/**
 * Main plugin class
 *
 * @package alpha-price-table-for-elementor
 *  */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class Alpha_Sinlge_Product_For_Elementor
 */
final class Alpha_Sinlge_Product_For_Elementor
{
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '5.6';
    /**
     * Self instance.
     *
     * @var null Self instance
     */
    private static $_instance = null;

    /**
     * Return self instance.
     *
     * @return Alpha_Sinlge_Product_For_Elementor|null
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Alpha_Sinlge_Product_For_Elementor constructor.
     */
    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'on_plugins_loaded'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'plugin_css'));
    }

    /**
     * Load the plugin text domain.
     */
    public function i18n()
    {
        load_plugin_textdomain('alpha-single-product-for-elementor');
    }

    /**
     * On plugins load check for compatibility.
     */
    public function on_plugins_loaded()
    {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    /**
     * Check if is compatible.
     *
     * @return bool
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

        // Add Plugin actions.
        add_action('elementor/widgets/register', array($this, 'init_widgets'));

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

    /*
    * Check Plugins is Installed or not
    */
    public function is_plugins_active($pl_file_path = NULL)
    {
        $installed_plugins_list = get_plugins();
        return isset($installed_plugins_list[$pl_file_path]);
    }

    /**
     * Admin notice.
     * For missing elementor.
     */
    public function admin_notice_missing_main_plugin()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $elementor = 'elementor/elementor.php';
        if ($this->is_plugins_active($elementor)) {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);
            /* translators: 1: Just text decoration 2: Just text decoration */
            $message = sprintf(__('%1$sAlpha Single Product Widget for Elementor%2$s requires %1$s"Elementor"%2$s plugin to be active. Please activate Elementor to continue.', 'alpha-single-product-for-elementor'), '<strong>', '</strong>');
            $button_text = esc_html__('Activate Elementor', 'alpha-single-product-for-elementor');
        } else {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            /* translators: 1: Just text decoration 2: Just text decoration */
            $message = sprintf(__('%1$sAlpha Single Product Widget for Elementor%2$s requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', 'alpha-single-product-for-elementor'), '<strong>', '</strong>');
            $button_text = esc_html__('Install Elementor', 'alpha-single-product-for-elementor');
        }
        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button);
    }

    /**
     * Admin notice.
     * For missing woocommerce.
     */
    public function admin_notice_missing_woocommerce()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);
        $woocommerce = 'woocommerce/woocommerce.php';
        if ($this->is_plugins_active($woocommerce)) {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woocommerce);
            /* translators: 1: Just text decoration 2: Just text decoration */
            $message = sprintf(__('%1$sAlpha Single Product Widget for Elementor%2$s requires %1$s"WooCommerce"%2$s plugin to be active. Please activate WooCommerce to continue.', 'alpha-single-product-for-elementor'), '<strong>', '</strong>');
            $button_text = esc_html__('Activate WooCommerce', 'alpha-single-product-for-elementor');
        } else {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
            /* translators: 1: Just text decoration 2: Just text decoration */
            $message = sprintf(__('%1$sAlpha Single Product Widget for Elementor%2$s requires %1$s"WooCommerce"%2$s plugin to be installed and activated. Please install WooCommerce to continue.', 'alpha-single-product-for-elementor'), '<strong>', '</strong>');
            $button_text = esc_html__('Install WooCommerce', 'alpha-single-product-for-elementor');
        }
        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button);
    }

    /**
     * Admin notice.
     * For minimum Elementor version required.
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
     * Admin notice.
     * For minimum PHP version required.
     */
    public function admin_notice_minimum_php_version()
    {

        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Required PHP version */
            esc_html__('"%1$s" requires PHP version %2$s or greater.', 'alpha-single-product-for-elementor'),
            '<strong>' . esc_html__('Alpha Single Product Widget', 'alpha-single-product-for-elementor') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Loading plugin css.
     */
    public function plugin_css()
    {
        wp_enqueue_style('alphasp-widget', ALPHASP_PL_ASSETS . 'css/alpha-sp-widget.css', '', ALPHASP_VERSION);
    }

    /**
     * Register the plugin widget.
     *
     * @param object $widgets_manager Elementor widgets object.
     *
     * @throws Exception File.
     */
    public function init_widgets($widgets_manager)
    {
        // Include Widget files
        include(ALPHASP_PL_INCLUDE . '/class-alpha-single-product-widget.php');
        // Register widget
        $widgets_manager->register(new \Elementor\Alpha_SP_Widget());
    }
}
Alpha_Sinlge_Product_For_Elementor::instance();
