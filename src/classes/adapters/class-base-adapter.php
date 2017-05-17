<?php
/**
 * Class: Base Adapter
 *
 * @since  v2.0.0
 * @package Lift\Playbook
 */

namespace Lift\Playbook\Adapters;
use Lift\Playbook\Interfaces\Adapter;

/**
 * Base Adapter
 *
 * @see Lift\Playbook\Interfaces\Adapter;
 */
class Base_Adapter implements Adapter {
	/**
	 * Source Object
	 *
	 * @var object The source object the Adapter acts upon.
	 */
	public $source;

	/**
	 * Constructor
	 *
	 * @since  v2.0.0
	 * @param mixed $source The source object the Adapter acts upon.
	 */
	public function __construct( $source = null ) {
		if ( $source ) {
			$this->source = $source;
		}
	}

	/**
	 * Set Source
	 *
	 * @since  v2.0.0
	 * @param  object $source The source object the Adapter acts upon.
	 * @return Adapter        Self instance.
	 */
	public function set_source( $source ) : Adapter {
		$this->source = $source;

		return $this;
	}

	/**
	 * Set Source
	 *
	 * @since  v2.0.0
	 * @return object|null The source object.
	 */
	public function get_source() {
		return $this->source;
	}

	/**
	 * Resolve
	 *
	 * Resolves an attribute by first performing a lookup of a getter method on the Adapter
	 * itself.  If a getter method exists on the Adapter will call that method with the
	 * provided arguments and return the result.  If no getter exists on the Adapter, will
	 * attempt to call the same method on the source object.  If a method exists on the source
	 * object, will call that method witht he provided arguments and return the result.
	 *
	 * If no getters exist on either the Adapter and Source, will attempt to lookup the attribute
	 * as a property on the source object.  If this exists, the Adapter will return this.  If all
	 * previous methods fail, null is returned and the implication is that the attribute does not
	 * exist.
	 *
	 * @since  v2.0.0
	 * @param  string $name      The name of the attribute to resolve.
	 * @param  array  $arguments An array of arguments, if set, to pass to a getter method.
	 * @return mixed             The resolved attribute.
	 */
	public function resolve( string $name, array $arguments = array() ) {
		if ( method_exists( $this, $name ) ) {
			return call_user_func_array( array( $this, $name ), $arguments );
		}

		if ( is_object( $this->get_source() ) && method_exists( $this->get_source(), $name ) ) {
			return call_user_func_array( array( $this->get_source(), $name ), $arguments );
		}

		if ( is_object( $this->get_source() ) && property_exists( $this->get_source(), $name ) ) {
			return $this->get_source()->$name;
		}

		return null;
	}

	/**
	 * Factory
	 *
	 * @since  v2.0.0
	 * @param  object|null $source The source object.
	 * @return Adapter             Instance of self.
	 */
	public static function factory( $source = null ) : Adapter {
		return new static( $source );
	}
}
