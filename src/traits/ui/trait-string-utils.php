<?php
/**
 * Playbook String Utils
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\UI\Data_Value;

/**
 * Trait: String Utils
 *
 * @since  v2.0.0
 */
trait String_Utils {

	/**
	 * Transforms a "date-ish" string to a DateTime object
	 *
	 * @uses  \DateTime::__construct()
	 * @since  v2.0.0
	 * @param  String $string 	A string representation of a date.  Cannot be a timestamp.
	 * @return \DateTime|string A \DateTime object, original string on failure.
	 */
	public function to_datetime( string $string = '' ) {
		if ( empty( $string ) && is_a( $this, Data_Value::class ) ) {
			$string = $this->get();
		}

		try {
			return new \DateTime( $string );
		} catch ( \Throwable $t ) {
			return $string;
		}
	}

	/**
	 * Decodes a JSON string into an Object
	 *
	 * @since  v2.0.0
	 * @param  String $string 	A json string hash.
	 * @return \stdClass|string A PHP stdClass Object, original string on failure.
	 */
	public function json_decode( string $string = '' ) {
		if ( empty( $string ) && is_a( $this, Data_Value::class ) ) {
			$string = $this->get();
		}

		$object = json_decode( $string );

		if ( ! is_null( $object ) ) {
			return $object;
		}
		return $string;
	}

	/**
	 * Unserializes a serialized string
	 *
	 * After deliberation, we're going to use `maybe_unserialize` for this. Core suppresses
	 * errors with the `@` operator when calling unserialize.  We can't do much better
	 * because unserialize doesn't throw anything on errors.
	 *
	 * @codeCoverageIgnore We don't unit test Core
	 * @uses   \maybe_unserialize()
	 * @link   https://developer.wordpress.org/reference/functions/maybe_unserialize/
	 * @since  v2.0.0
	 * @param  String $string 		A serialized string.
	 * @return mixed  Result of unserialization, original string on failure.
	 */
	public function unserialize( string $string = '' ) {
		if ( empty( $string ) && is_a( $this, Data_Value::class ) ) {
			$string = $this->get();
		}
		return maybe_unserialize( $string );
	}

	/**
	 * Contains
	 *
	 * @since  v2.0.0
	 * @param  string $needle   String to search for.
	 * @param  mixed  $haystack  Thing to search through.
	 * @return boolean          True if found, false otherwise.
	 */
	public function contains( string $needle, $haystack = null ) : bool {
		if ( is_null( $haystack ) && is_a( $this, Data_Value::class ) ) {
			$haystack = $this->get();
		}

		if ( ! is_string( $haystack ) ) {
			return false;
		}

		return ( strpos( $haystack, $needle ) === false ) ? false : true;
	}
}
