<?php
/**
 * Array Attribute
 *
 * Defines the Lift\Playbook\UI|Array_Attribute class, which all template Array attributes should
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
 * Class: Array Attribute
 *
 * @since  v2.0.0
 */
class Array_Attribute extends Base_Attribute implements \ArrayAccess, \IteratorAggregate {
	use Attribute_Array_Access;
	use Attribute_Iterator;

	/**
	 * Constructor
	 *
	 * @param string $name  The name of the attribute.
	 * @param array  $value The value of the attribute.
	 */
	public function __construct( string $name, $value ) {
		if ( $this->is_valid( $value ) ) {
			$this->type = 'array';
			parent::__construct( $name, $value );
		}
	}

	/**
	 * Is Valid
	 *
	 * @throws Playbook_Strict_Type_Exception Thrown if the value is not an array.
	 * @param mixed $value The value to ensure validity.
	 * @return boolean
	 */
	public function is_valid( $value ) {
		if ( ! is_array( $value ) ) {
			if ( $this->use_strict ) {
				throw new Playbook_Strict_Type_Exception( 'Expected value to be an array, ' . gettype( $value ) . ' given.' );
			}
			return false;
		}
		return true;
	}
}
