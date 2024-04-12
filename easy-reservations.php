<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.cmsminds.com/
 * @since             1.0.1
 * @package           Easy_Reservations
 *
 * @wordpress-plugin
 * Plugin Name:       Boat Rental Plugin for WordPress
 * Plugin URI:        https://www.github.com/cmsminds
 * Description:       Transfer your WordPress into an easy reservation system. Powered by cmsMinds.
 * Version:           1.0.1
 * Author:            cmsMinds
 * Author URI:        https://www.cmsminds.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-reservations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ERSRV_PLUGIN_VERSION', '1.0.1' );

// Plugin path.
if ( ! defined( 'ERSRV_PLUGIN_PATH' ) ) {
	define( 'ERSRV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Plugin URL.
if ( ! defined( 'ERSRV_PLUGIN_URL' ) ) {
	define( 'ERSRV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-reservations-activator.php
 *
 * @since 1.0.0
 */
function ersrv_activate_easy_reservations() {
	require_once ERSRV_PLUGIN_PATH . 'includes/class-easy-reservations-activator.php';
	Easy_Reservations_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-reservations-deactivator.php
 *
 * @since 1.0.0
 */
function ersrv_deactivate_easy_reservations() {
	require_once ERSRV_PLUGIN_PATH . 'includes/class-easy-reservations-deactivator.php';
	Easy_Reservations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'ersrv_activate_easy_reservations' );
register_deactivation_hook( __FILE__, 'ersrv_deactivate_easy_reservations' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks, then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function ersrv_run_easy_reservations() {
	// The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
	require ERSRV_PLUGIN_PATH . 'includes/class-easy-reservations.php';
	$plugin = new Easy_Reservations();
	$plugin->run();
}

/**
 * This initiates the plugin.
 * Checks for the required plugins to be installed and active.
 *
 * @since 1.0.0
 */
function ersrv_plugins_loaded_callback() {
	$active_plugins = get_option( 'active_plugins' );
	$is_wc_active   = in_array( 'woocommerce/woocommerce.php', $active_plugins, true );

	if ( current_user_can( 'activate_plugins' ) && false === $is_wc_active ) {
		add_action( 'admin_notices', 'ersrv_admin_notices_callback' );
	} else {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ersrv_plugin_actions_callback' );
		ersrv_run_easy_reservations();
	}
}

add_action( 'plugins_loaded', 'ersrv_plugins_loaded_callback' );

/**
 * Show admin notice for the required plugins not active or installed.
 *
 * @since 1.0.0
 */
function ersrv_admin_notices_callback() {
	$this_plugin_data = get_plugin_data( __FILE__ );
	$this_plugin      = $this_plugin_data['Name'];
	$wc_plugin        = 'WooCommerce';
	?>
	<div class="error">
		<p>
			<?php
			/* translators: 1: %s: strong tag open, 2: %s: strong tag close, 3: %s: this plugin, 4: %s: woocommerce plugin, 5: anchor tag for woocommerce plugin, 6: anchor tag close */
			echo wp_kses_post( sprintf( __( '%1$s%3$s%2$s is ineffective as it requires %1$s%4$s%2$s to be installed and active. Click %5$shere%6$s to install or activate it.', 'easy-reservations' ), '<strong>', '</strong>', esc_html( $this_plugin ), esc_html( $wc_plugin ), '<a target="_blank" href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">', '</a>' ) );
			?>
		</p>
	</div>
	<?php
}

/**
 * This function adds custom plugin actions.
 *
 * @param array $links Links array.
 * @return array
 * @since 1.0.0
 */
function ersrv_plugin_actions_callback( $links ) {
	$this_plugin_links = array(
		'<a title="' . __( 'Settings', 'easy-reservations' ) . '" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=easy-reservations' ) ) . '">' . __( 'Settings', 'easy-reservations' ) . '</a>',
		'<a title="' . __( 'Docs', 'easy-reservations' ) . '" href="javascript:void(0);">' . __( 'Docs', 'easy-reservations' ) . '</a>',
		'<a title="' . __( 'Support', 'easy-reservations' ) . '" href="javascript:void(0);">' . __( 'Support', 'easy-reservations' ) . '</a>',
		'<a title="' . __( 'Changelog', 'easy-reservations' ) . '" href="javascript:void(0);">' . __( 'Changelog', 'easy-reservations' ) . '</a>',
	);

	return array_merge( $this_plugin_links, $links );
}

/**
 * Remove Sticky Add to cart from storefront Theme 
 */
function remove_sticky_add_to_cart() {
	$get_current_theme = get_option( 'stylesheet' );
	if ( 'easy-storefront' === $get_current_theme || 'storefront' === $get_current_theme ) {
		remove_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );
	}
}
add_action('init', 'remove_sticky_add_to_cart');
