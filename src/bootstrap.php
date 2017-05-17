<?php
/**
 * Playbook Bootstrapper
 */

// Very very deprecated.
// @codingStandardsIgnoreStart
namespace Old_Playbook;

/**
 * @codeCoverageIgnore
 */
class Core {

	protected static $instance = null;

	public static $app_dir;

	public static $root_dir;

	public static $component_dir;

	public static $module_dir;

	public static $layout_dir;

	public static $rel_path = null;

	public function __construct() {
		// $this->load_config();
		// $this->load_supplementals();
		$this->set_global_properties();
	}

	protected function load_config() {
		require_once( 'config.php' );
	}

	protected function load_supplementals() {
		if ( defined( 'ABSPATH' ) && ( defined( 'PLAYBOOK_DEMO' ) && ! PLAYBOOK_DEMO ) ) {
			require_once( 'WP_Template.php' );
			require_once( 'Component.php' );
			require_once( 'Module.php' );
			require_once( 'Layout.php' );
		} else {
			require_once( 'Template.php' );
			require_once( 'Component.php' );
			require_once( 'ShowcaseComponent.php' );
			require_once( 'Module.php' );
			require_once( 'ShowcaseModule.php' );
			require_once( 'Layout.php' );
			require_once( 'no-wp.php' );
		}
	}

	protected function set_global_properties() {
		self::$app_dir = dirname( __FILE__ );
		self::$root_dir = dirname( dirname( __FILE__ ) );
		self::$component_dir = self::$root_dir . '/components';
		self::$module_dir = self::$root_dir . '/modules';
		self::$layout_dir = self::$root_dir . '/layouts';
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function set_rel_path( $rel_path ) {
		if ( is_null( self::$rel_path ) ) {
			self::$rel_path = $rel_path;
		}
		return self::$rel_path;
	}

	public function get_rel_path() {
		return self::$rel_path;
	}

	public static function get_asset_url() {
		return self::$rel_path . '/assets/';
	}

	public static function get_asset_dir() {
		return dirname( dirname( __FILE__ ) ) . '/assets/';
	}
}


$GLOBALS['old_playbook'] = Core::get_instance();
