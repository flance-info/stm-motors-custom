<?php
/**
 * Plugin Name: STM Motors Custom
 * Plugin URI:  https://stylemixthemes.com/
 * Description: STM Motors Extends WordPress Plugin for agrider
 * Version:     2.0.0
 * Author:      StylemixThemes
 * Author URI:  https://stylemixthemes.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: stm_motors_custom
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'MOTORS_ELEMENTOR_CUSTOMS_PATH', dirname( __FILE__ ) );
define( 'MOTORS_ELEMENTOR_CUSTOMS_URL', plugins_url( '', __FILE__ ) );
include_once 'inc/loader.php';

