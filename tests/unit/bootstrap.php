<?php
/**
 * Constants
 */
define( 'ROOTDIR', dirname( dirname( dirname( __FILE__ ) ) ) );
define( 'APPDIR', ROOTDIR . '/src/app/');
define( 'APP', APPDIR . 'Bootstrap.php');
define( 'TESTDIR', dirname( __FILE__ ) );

/**
 * Require PHPUnit
 */
require_once( ROOTDIR . '/vendor/autoload.php');

/**
 * Require Helpers
 */
require_once( __DIR__ . '/helpers/WP_Objects.php' );
require_once( __DIR__ . '/helpers/Example_Component/Example_Component.php' );
//require_once( __DIR__ . '/helpers/Example_Component/Example_Component_Renderer.php' );
require_once( __DIR__ . '/helpers/Example_Component/Example_Factory.php' );

\WP_Mock::bootstrap();
