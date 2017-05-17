<?php
/**
 * Object Attribute
 *
 * Defines the Lift\Playbook\UI|Object_Attribute class, which all template Object attributes should
 * instantiate as their corresponding class properties.
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  UI
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Playbook_Strict_Type_Exception;
use Lift\Playbook\Interfaces\Attribute;

/**
 * Class: Object Attribute
 *
 * @since  v2.0.0
 */
class Object_Attribute extends Base_Attribute implements Attribute {

	/**
	 * Constructor
	 *
	 * @param string $name  The name of the attribute.
	 * @param object $value The value of the attribute.
	 */
	public function __construct( string $name, $value ) {
		if ( $this->is_valid( $value ) ) {	
			$this->type = 'object';
			parent::__construct( $name, $value );
		}
	}

	/**
	 * Is valid
	 *
	 * @throws Playbook_Strict_Type_Exception Thrown if the value is not an object.
	 * @param mixed $value The value to ensure validity.
	 * @return boolean
	 */
	public function is_valid( $value ) {
		if ( ! is_object( $value ) ) {
			if ( $this->use_strict ) {
				throw new Playbook_Strict_Type_Exception( 'Expected value to be an object, ' . gettype( $value ) . ' given.' );
			}
			return false;
		}
		return true;
	}

	/**
	 * Magic: Call
	 *
	 * @param string $method    The method name.
	 * @param array  $arguments The arguments.
	 * @return mixed            The return value of the method on the value, or the parent of $this.
	 */
	public function __call( string $method, $arguments ) {
		if ( method_exists( $this->get(), $method ) ) {
			return call_user_func_array( array( $this->get(), $method ), $arguments );
		}
		parent::__call( $method, $arguments );
	}
}
