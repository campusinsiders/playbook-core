<?php
/**
 * Global Alias Functions
 *
 * @since  v2.0.0
 *
 * @package  Lift\Playbook\Functions
 */

use Lift\Playbook\Playbook;

if ( ! function_exists( 'playbook' ) ) :
	/**
	 * Get the Main Playbook Instance
	 *
	 * @since  v2.0.0
	 * @return Playbook The main instance of Playbook
	 */
	function playbook() : Playbook {
		$playbook = Playbook::factory();
		return $playbook;
	}
endif;

if ( ! function_exists( 'playbook_get_factory' ) ) :
	/**
	 * Playbook Get Factory
	 *
	 * @since  v2.0.0
	 * @param  string $reference  The string reference of the desired factory.
	 * @return Base_Template|null An instance of the factory, or null if no factory found
	 */
	function playbook_get_factory( string $reference ) {
		if ( ! playbook() instanceof Playbook ) {
			return null;
		}
		$instance = playbook()->get_factory_map()->get_factory( $reference );
		return $instance;
	}
endif;

if ( ! function_exists( 'playbook_has_factory' ) ) :
	/**
	 * Playbook Has Factory
	 *
	 * @param  string $reference The string reference of the desired factory.
	 * @return bool              True if a factory exists with passed reference, false otherwise.
	 */
	function playbook_has_factory( string $reference ) : bool {
		if ( ! playbook() instanceof Playbook ) {
			return null;
		}
		return playbook()->get_factory_map()->has_factory( $reference );
	}
endif;
