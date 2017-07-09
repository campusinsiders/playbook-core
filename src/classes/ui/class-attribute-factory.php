<?php
/**
 * Attribute Factor
 *
 * Contains the Attribute_Factory Class, responsible for returning an instance of an Attribute interface.
 *
 * @since v2.0.0
 * @package Lift\Playbook
 * @subpackage UI
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Interfaces\Attribute;

/**
 * Class: Attribute_Factory
 *
 * @since v2.0.0
 */
class Attribute_Factory {
	/**
	 * Create
	 *
	 * @param string        $name   The name of the attribute.
	 * @param mixed         $value  The value of the attribute.
	 * @param null|callable $setter An optional callable setter, passed the desired value.
	 * @param null|callable $getter An optional callable getter, passed the current value.
	 * @return Attribute    Returns a class that implements the Attribute interface.
	 */
	public static function create( string $name, $value, callable $setter = null, callable $getter = null ) : Attribute {
		if ( is_null( $value ) ) {
			return self::create_deferred( $name, $value, $setter, $getter );
		}

		if ( is_string( $value ) ) {
			return self::create_string( $name, $value, $setter, $getter );
		}

		if ( is_bool( $value ) ) {
			return self::create_boolean( $name, $value, $setter, $getter );
		}

		if ( is_numeric( $value ) ) {
			return self::create_numeric( $name, $value, $setter, $getter );
		}

		if ( is_array( $value ) ) {
			return self::create_array( $name, $value, $setter, $getter );
		}

		if ( is_object( $value ) ) {
			return self::create_object( $name, $value, $setter, $getter );
		}
	}

	/**
	 * Create Deferred
	 *
	 * @param string        $name   The name of the attribute.
	 * @param mixed         $value  The value of the attribute, usually null.
	 * @param null|callable $setter An optional callable setter, passed the desired value.
	 * @param null|callable $getter An optional callable getter, passed the current value.
	 * @return Deferred_Attribute   Returns a class that implements the Deferred_Attribute interface.
	 */
	public static function create_deferred( $name, $value, $setter, $getter ) : Deferred_Attribute {
		return new Deferred_Attribute( $name, $value, $setter, $getter );
	}

	/**
	 * Create String
	 *
	 * @param  string        $name   The name of the attribute.
	 * @param  string        $value  The value of the attribute.
	 * @param  null|callable $setter An optional callable setter, passed the desired value.
	 * @param  null|callable $getter An optional callable getter, passed the current value.
	 * @return String_Attribute      Returns a class that implements the String_Attribute interface.
	 */
	public static function create_string( string $name, string $value, $setter, $getter ) : String_Attribute {
		return new String_Attribute( $name, $value, $setter, $getter );
	}

	/**
	 * Create Boolean
	 *
	 * @param  string        $name   The name of the attribute.
	 * @param  bool|boolean  $value  The value of the attribute.
	 * @param  null|callable $setter An optional callable setter, passed the desired value.
	 * @param  null|callable $getter An optional callable getter, passed the current value.
	 * @return Boolean_Attribute     Returns a class that implements the Boolean_Attribute interface.
	 */
	public static function create_boolean( string $name, bool $value, $setter, $getter ) : Boolean_Attribute {
		return new Boolean_Attribute( $name, $value, $setter, $getter );
	}

	/**
	 * Create Numeric
	 *
	 * @param  string        $name   The name of the attribute.
	 * @param  mixed         $value  The value of the attribute.
	 * @param  null|callable $setter An optional callable setter, passed the desired value.
	 * @param  null|callable $getter An optional callable getter, passed the current value.
	 * @return Numeric_Attribute     Returns a class that implements the Numeric_Attribute interface.
	 */
	public static function create_numeric( string $name, $value, $setter, $getter ) : Numeric_Attribute {
		return new Numeric_Attribute( $name, $value, $setter, $getter );
	}

	/**
	 * Create Array
	 *
	 * @param  string        $name   The name of the attribute.
	 * @param  array         $value  The value of the attribute.
	 * @param  null|callable $setter An optional callable setter, passed the desired value.
	 * @param  null|callable $getter An optional callable getter, passed the current value.
	 * @return Array_Attribute       Returns a class that implements the Array_Attribute interface.
	 */
	public static function create_array( string $name, array $value, $setter, $getter ) : Array_Attribute {
		return new Array_Attribute( $name, $value, $setter, $getter );
	}

	/**
	 * Create Object
	 *
	 * @param  string        $name   The name of the attribute.
	 * @param  mixed         $value  The value of the attribute.
	 * @param  null|callable $setter An optional callable setter, passed the desired value.
	 * @param  null|callable $getter An optional callable getter, passed the current value.
	 * @return Object_Attribute      Returns a class that implements the Object_Attribute interface.
	 */
	public static function create_object( string $name, $value, $setter, $getter ) : Object_Attribute {
		return new Object_Attribute( $name, $value, $setter, $getter );
	}
}
