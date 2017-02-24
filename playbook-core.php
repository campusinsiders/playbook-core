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

require_once plugin_dir_path( __FILE__ ) . '/src/functions/global-alias-functions.php';

global $playbook;
$playbook = new Playbook( new Hook_Catalog, new Factory_Map );
