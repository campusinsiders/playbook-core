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
	 * @param string $name  The name of the attribute.
	 * @param mixed  $value The value of the attribute.
	 * @return Attribute    Returns a class that implements the Attribute interface.
	 */
	public static function create( string $name, $value ) : Attribute {
		if ( is_null( $value ) ) {
			return self::create_deferred( $name, $value );
		}

		if ( is_string( $value ) ) {
			return self::create_string( $name, $value );
		}

		if ( is_bool( $value ) ) {
			return self::create_boolean( $name, $value );
		}

		if ( is_numeric( $value ) ) {
			return self::create_numeric( $name, $value );
		}

		if ( is_array( $value ) ) {
			return self::create_array( $name, $value );
		}

		if ( is_object( $value ) ) {
			return self::create_object( $name, $value );
		}
	}

	public static function create_deferred( $name, $value ) : Deferred_Attribute {
		return new Deferred_Attribute( $name, $value );
	}

	public static function create_string( string $name, string $value ) : String_Attribute {
		return new String_Attribute( $name, $value );
	}

	public static function create_boolean( string $name, bool $value ) : Boolean_Attribute {
		return new Boolean_Attribute( $name, $value );
	}

	public static function create_numeric( string $name, $value ) : Numeric_Attribute {
		return new Numeric_Attribute( $name, $value );
	}

	public static function create_array( string $name, array $value ) : Array_Attribute {
		return new Array_Attribute( $name, $value );
	}

	public static function create_object( string $name, $value ) : Object_Attribute {
		return new Object_Attribute( $name, $value );
	}
}
