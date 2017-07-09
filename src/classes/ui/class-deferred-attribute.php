<?php
/**
 * Deferred Attribute
 *
 * When a Template contains a modified
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  UI
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Playbook_Strict_Type_Exception;
use Lift\Playbook\Interfaces\Attribute;

/**
 * Class: Deferred Attribute
 *
 * @since  v2.0.0
 */
class Deferred_Attribute extends Base_Attribute implements Attribute {
	/**
	 * Constructor
	 *
	 * @param string        $name   The name of the attribute.
	 * @param mixed         $value  The value of the attribute, if not null will return an assigned Attribute.
	 * @param null|callable $setter An optional callable setter, passed the desired value.
	 * @param null|callable $getter An optional callable getter, passed the current value.
	 */
	public function __construct( string $name, $value, callable $setter = null, callable $getter = null ) {
		$this->type = 'null';
		parent::__construct( $name, null, $setter, $getter );
		if ( ! is_null( $value ) ) {
			return $this->set( $value );
		}
	}

	/**
	 * Set
	 *
	 * @param mixed $value The attribute value.
	 * @return Attribute
	 */
	public function set( $value ) : Attribute {
		return Attribute_Factory::create( $this->name, $value, $this->setter, $this->getter );
	}
}
