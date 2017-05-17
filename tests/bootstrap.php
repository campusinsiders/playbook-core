<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Playbook_Core
 */

// PHPUnit Upgrade Compatibility
if ( ! class_exists( 'PHPUnit_Framework_Testcase' ) ) {
	class PHPUnit_Framework_Testcase extends PHPUnit\Framework\TestCase {}
}

// Constants
define( 'ROOTDIR', dirname( dirname( __FILE__ ) ) );
define( 'APPDIR', ROOTDIR . '/src/app/');
define( 'APP', APPDIR . 'Bootstrap.php');
define( 'TESTDIR', dirname( __FILE__ ) );

function get_test_type() {
	$args = $_SERVER['argv'];
	if ( in_array( '--testsuite', $args ) ) {
		$key = array_search( '--testsuite', $args );
		if ( isset( $args[++$key] ) ) {
			$arg = $args[$key];
			var_dump($arg);
			switch( $arg ) {
				case ( 0 <= strpos( $arg, 'unit' ) ) :
					return 'unit';
				case ( 0 <= strpos( $arg, 'integration' ) ) :
					return 'integration';
				default :
					return 'unit';
			}
		}
	}
	return 'unit';
}

function bootstrap_tests( $type ) {
	require_once( ROOTDIR . '/vendor/autoload.php');
	switch ( $type ) {
		case 'unit' :
			bootstrap_unit_tests();
			break;
		case 'integration' :
			bootstrap_integration_tests();
			break;
		case '*' :
		default:
			bootstrap_all_tests();
			break;
	}
}

function bootstrap_unit_tests() {
	require_once( __DIR__ . '/unit/helpers/WP_Objects.php' );
	require_once( __DIR__ . '/unit/helpers/Example_Component/Example_Component.php' );
	require_once( __DIR__ . '/unit/helpers/Example_Component/Example_Factory.php' );
	\WP_Mock::bootstrap();
}

function bootstrap_integration_tests() {
	$_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $_tests_dir ) {
		$_tests_dir = '/tmp/wordpress-tests-lib';
	}

	if ( ! file_exists( $_tests_dir ) ) {
		return;
	}

	// Give access to tests_add_filter() function.
	require_once $_tests_dir . '/includes/functions.php';

	/**
	 * Manually load the plugin being tested.
	 */
	function _manually_load_plugin() {
		require dirname( dirname( __FILE__ ) ) . '/playbook-core.php';
	}
	tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

	// Start up the WP testing environment.
	require $_tests_dir . '/includes/bootstrap.php';
}

bootstrap_tests( get_test_type() );
