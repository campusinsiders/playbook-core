<?php
/**
 * Attribute Array Access
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 * @codingStandardsIgnoreFile This implements the ArrayAccess, will throw snake_case errors.
 */

namespace Lift\Playbook\UI;

/**
 * Trait: Attribute Array Access
 *
 * @since  v2.0.0
 * @see    Lift\Playbook\UI\Hooks
 */
trait Attribute_Array_Access {

	/**
	 * Offset Exists
	 *
	 * @param mixed $offset The offset.
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->value[ $offset ] );
	}

	/**
	 * Offset Get
	 *
	 * @param mixed $offset The offset.
	 * @return mixed        The value.
	 */
	public function offsetGet( $offset ) {
		return $this->value[ $offset ];
	}

	/**
	 * Offset Set
	 *
	 * @param mixed $offset The offset.
	 * @param mixed $value  The value of at offset.
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		$this->value[ $offset ] = $value;
	}

	/**
	 * Offset Unset
	 *
	 * @param mixed $offset The offset.
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->value[ $offset ] );
	}
}