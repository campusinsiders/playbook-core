<?php
/**
 * String Attribute
 *
 * Defines the Lift\Playbook\UI|String_Attribute class, which all template string attributes should
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
 * Class: String Attribute
 *
 * Each template attribute that defines a string is instantiated as an instance of the String_Attribute class.
 * This allows us shortcuts to transforming and filtering data, as well as some of
 * the value utilities, like DateTime and quick decoding JSON to Object operations.
 *
 * @since  v2.0.0
 */
class String_Attribute extends Base_Attribute implements Attribute {
	use String_Utils;

	/**
	 * Constructor
	 *
	 * @param string        $name   The name of the attribute.
	 * @param string        $value  The value of the attribute.
	 * @param null|callable $setter An optional callable setter, passed the desired value.
	 * @param null|callable $getter An optional callable getter, passed the current value.
	 */
	public function __construct( string $name, $value, callable $setter = null, callable $getter = null ) {
		if ( $this->is_valid( $value ) ) {
			$this->type = 'string';
			parent::__construct( $name, $value, $setter, $getter );
		}
	}

	/**
	 * Is valid
	 *
	 * @throws Playbook_Strict_Type_Exception Thrown if the value is not a string.
	 * @param mixed $value The value to ensure validity.
	 * @return boolean
	 */
	public function is_valid( $value ) {
		if ( ! is_string( $value ) ) {
			if ( $this->use_strict ) {
				throw new Playbook_Strict_Type_Exception( 'Expected value to be a string, ' . gettype( $value ) . ' given.' );
			}
			return false;
		}
		return true;
	}
}
