<?php
/**
 * Interface: Adapter
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  Interfaces
 */

namespace Lift\Playbook\Interfaces;

interface Adapter {

	/**
	 * Set Source
	 *
	 * @since  v2.0.0
	 * @param  mixed  $source The source object.
	 * @return Adapter        Instance of self.
	 */
	public function set_source( $source ) : Adapter;

	/**
	 * Get Source
	 *
	 * @since  v2.0.0
	 * @return object The source object.
	 */
	public function get_source();

	/**
	 * Resolve
	 *
	 * @since  v2.0.0
	 * @param  string $name      The name of the desired attribute that the adapter should resolve.
	 * @param  array  $arguments Optional arguments to pass to an attribute getter method.
	 * @return mixed             The attribute from the source object as resolved by the adapter.
	 */
	public function resolve( string $name, array $arguments );
}
