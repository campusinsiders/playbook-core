<?php
/**
 * Playbook String Utils
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Interfaces\Attribute;

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
	 * @param  String $string   A string representation of a date.  Cannot be a timestamp.
	 * @return \DateTime|string A \DateTime object, original string on failure.
	 */
	public function to_datetime( string $string = '' ) {
		if ( empty( $string ) && is_a( $this, Base_Attribute::class ) ) {
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
	 * @param  String $string   A json string hash.
	 * @return \stdClass|string A PHP stdClass Object, original string on failure.
	 */
	public function json_decode( string $string = '' ) {
		if ( empty( $string ) && is_a( $this, Base_Attribute::class ) ) {
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
	 * @param  String $string       A serialized string.
	 * @return mixed  Result of unserialization, original string on failure.
	 */
	public function unserialize( string $string = '' ) {
		if ( empty( $string ) && is_a( $this, Base_Attribute::class ) ) {
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
		if ( is_null( $haystack ) && is_a( $this, Base_Attribute::class ) ) {
			$haystack = $this->get();
		}

		if ( ! is_string( $haystack ) ) {
			return false;
		}

		return ( strpos( $haystack, $needle ) === false ) ? false : true;
	}

	/**
	 * Validate URL
	 *
	 * @since  v2.0.0
	 * @param  string|null $text Text to validate as url.
	 * @return bool              True if valid url, false otherwise
	 */
	public function validate_url( string $text = null ) : bool {
		if ( is_null( $text ) && is_a( $this, Base_Attribute::class ) ) {
			$text = $this->get();
		}
		return ( false !== filter_var( $text, FILTER_VALIDATE_URL ) );
	}

	/**
	 * Replace
	 *
	 * @param string $search  The string being searched for.
	 * @param string $replace The replacement value.
	 * @param string $text    The subject of the search.
	 * @return string         A string with the replaced values.
	 */
	public function replace( string $search, string $replace, string $text = null ) : string {
		if ( is_null( $text ) && is_a( $this, Base_Attribute::class ) ) {
			$text = $this->get();
		}
		return str_replace( $search, $replace, $text );
	}

	/**
	 * Concat
	 *
	 * @param string $addition The string to add.
	 * @param string $text     The string to add to.
	 * @return string          The resulting string.
	 */
	public function concat( string $addition, string $text = null ) : string {
		if ( is_null( $text ) && is_a( $this, Base_Attribute::class ) ) {
			$text = $this->get();
		}
		return $text . $addition;
	}

	/**
	 * Hex to RGB
	 *
	 * @param  string $hex The hex code.
	 * @return array       An array of rgb keyed values.
	 */
	public function hex2rgb( string $hex = null ) {
		if ( is_null( $hex ) && is_a( $this, Base_Attribute::class ) ) {
			$hex = $this->get();
		}
		$hex = ltrim( $hex, '#' );
		if ( strlen( $hex ) < 3 || strlen( $hex ) > 6 ) {
			return array();
		}
		if ( strlen( $hex ) === 3 ) {
			$red = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$green = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$blue = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$red = hexdec( substr( $hex, 0, 2 ) );
			$green = hexdec( substr( $hex, 2, 2 ) );
			$blue = hexdec( substr( $hex, 4, 2 ) );
		}
		return array(
			'red' => $red,
			'green' => $green,
			'blue' => $blue,
		);
	}
}
