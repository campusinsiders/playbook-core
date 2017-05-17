<?php
/**
 * Plugin Name:     Playbook Core
 * Plugin URI:      https://liftux.com
 * Description:     Playbook Core
 * Author:          Christian Chung <christian@liftux.com>
 * Author URI:      https://github.com/christianc1
 * Text Domain:     lift-core
 * Domain Path:     /languages
 * Version:         2.0.0
 *
 * @package         Lift\Playbook
 */

namespace Lift\Playbook;

use Lift\Playbook\Playbook;
use Lift\Playbook\Factory_Map;
use Lift\Core\Hook_Catalog;

define( 'PLAYBOOK_CORE_PATH', dirname( __FILE__ ) );

// Require the library files.
require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

add_action( 'plugins_loaded', function() {
	if ( ! class_exists( 'Lift\Core\Hook_Catalog' ) ) {
		wp_die( 'Core Library Does Not Exist.' );
	}
	global $playbook;
	$playbook = new Playbook( new Hook_Catalog, new Factory_Map );
}, 5 );
