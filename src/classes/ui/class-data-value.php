<?php
/**
 * Data Value
 *
 * Defines the Lift\Playbook\UI|Data_Value class, which all template attributes should
 * instantiate as their corresponding class properties.
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  UI
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Playbook_Strict_Type_Exception;

/**
 * Class: Data_Value
 *
 * Each template attribute is instantiated as an instance of the Data_Value class.
 * This allows us shortcuts to transforming and filtering data, as well as some of
 * the value utilities, like DateTime and quick decoding JSON to Object operations.
 *
 * @since  v2.0.0
 */
class Data_Value {
	use String_Utils;

	/**
	 * Name of a value, used to access value from public
	 *
	 * @since v2.0.0
	 * @var string
	 */
	public $name;

	/**
	 * The stored immunatable value
	 *
	 * @since v2.0.0
	 * @var mixed
	 */
	public $value;

	/**
	 * The value type, strict when strict_mode is (bool) true
	 *
	 * @since v2.0.0
	 * @var string
	 */
	protected $type;

	/**
	 * Strict mode will throw a Playbook_Strict_Type_Exception if the type value mutates
	 *
	 * @since  v2.0.0
	 * @var  boolean
	 */
	public $use_strict = false;

	/**
	 * Constructor
	 *
	 * @since v2.0.0
	 * @param String $name  The name of the Data_Value.
	 * @param mixed  $value The immutable value.
	 * @return Data_Value   $this
	 */
	public function __construct( string $name, $value ) {
		$this->name = $name;
		$this->value = $value;
		$this->type = $this->type();
		return $this;
	}

	/**
	 * Set
	 *
	 * @since v2.0.0
	 * @param mixed 	  $value An immutable value.
	 * @param String|Null $type  The value type.
	 * @return  Data_Value       $this
	 */
	public function set( $value, $type = null ) : Data_Value {
		$type = ! is_null( $type ) ? $type : gettype( $value );
		$this->value = $this->ensure( $value, $type );
		return $this;
	}

	/**
	 * Get
	 *
	 * @since v2.0.0
	 * @return mixed The value
	 */
	public function get() {
		return $this->value;
	}

	/**
	 * Is Set
	 *
	 * @since  v2.0.0
	 * @return boolean Whether the value is set or not.  False if the value is null.
	 */
	public function is_set() : bool {
		return isset( $this->value );
	}

	/**
	 * Is Full
	 *
	 * @since  v2.0.0
	 * @return boolean Whether the value is not null, and will not evaluate as `empty`
	 */
	public function is_full() : bool {
		if ( ! is_null( $this->value ) && ! empty( $this->value ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Type
	 *
	 * @since v2.0.0
	 * @param  String|Null $type String representation of the value's type or null.
	 * @return string            The type of the value
	 */
	public function type( $type = null ) : string {
		if ( ! is_null( $type ) ) {
			return $this->type = (string) $type;
		}
		return $this->type = gettype( $this->value );
	}

	/**
	 * Filter
	 *
	 * @since v2.0.0
	 * @param  String      $filter   WordPress Filter to pass value through.
	 * @param  Array|array $args     An array of arguments to pass to the callable function.
	 * @return Data_Value            A (clone) of $this instance with a new filtered value
	 */
	public function filter( string $filter, array $args = [] ) : Data_Value {
		$clone = clone $this;
		return $clone->set( apply_filters( $filter, $this->get(), $args ) );
	}

	/**
	 * Transform
	 *
	 * @since v2.0.0
	 * @param  String $thing  String representation of a transform.
	 * @return Data_Value     A new Datavalue with a tranfomed value, or the current Datavalue
	 */
	public function transform( string $thing ) : Data_Value {
		switch ( $thing ) {
			case 'datetime':
				return new self( $this->name, $this->to_datetime( $this->get() ) );
			case 'decoded':
				return new self( $this->name, $this->json_decode( $this->get() ) );
			case 'unserialized':
				return new self( $this->name, $this->unserialize( $this->get() ) );
			default :
				return $this;
		}
	}

	/**
	 * Ensure
	 *
	 * @since v2.0.0
	 * @throws Playbook_Strict_Type_Exception Thrown if type is changed and strict mode is on.
	 * @param  mixed  $value  The value to ensure the type of.
	 * @param  string $type  A string representation of the type to ensure the value is.
	 * @return mixed         The value with type ensured
	 */
	private function ensure( $value, string $type ) {
		// If type is null, straight pass through as this is an initial set.
		if ( 'NULL' === $type ) {
			return $value;
		}

		// If we're in strict mode, ensure the type hasn't changed.
		if ( $this->use_strict ) {
			if ( $type === $this->type ) {
				return $value;
			}
			throw new Playbook_Strict_Type_Exception( "$this->name mutated from $this->type to $type" );
		}

		// In not strict mode, pass value through a callback to help ensure the correct type.
		$sanitizations = [
			'string' => 'strval',
			'integer' => 'intval',
			'double' => 'floatval',
			'boolean' => 'boolval',
			'array' => function( $value ) { return (array) $value; },
			'object' => function( $value ) { return (object) $value; },
			'NULL' => function() { return null; },
		];

		return call_user_func( $sanitizations[ $type ], $value );
	}

	/**
	 * When
	 *
	 * When the value is strictly equal to the provided condition, will return the return
	 * value of the provided callback.
	 *
	 * @since  v2.0.0
	 * @param  mixed    $condition Something to compare the value with.
	 * @param  callable $callback  Callable function that returns something, passed $this.
	 * @param  mixed    ...$args   Variadic arguments.
	 * @return mixed|null          Return value of the callback when fired, null otherwise.
	 */
	public function when( $condition, callable $callback, ...$args ) {
		if ( $this->get() === $condition ) {
			return $callback( $this, $args );
		}
		return null;
	}

	/**
	 * Bind to
	 *
	 * Passes $this Data_Value as the first argument to the provided callable.
	 *
	 * @since  v2.0.0
	 * @param  callable $callback Callback function that accepts parameters.
	 * @param  mixed    ...$args  Variadic arguments.
	 * @return mixed              Return value of callback.
	 */
	public function bind_to( callable $callback, ...$args ) {
		return call_user_func( $callback, $this, $args );
	}

	/**
	 * Magic __call
	 *
	 * @since v2.0.0
	 * @param  string $method  In this case, the WordPress filter to pass the value to.
	 * @param  array  $args    An array of agrument to pass to the filter.
	 * @return Data_Value      A (clone) of $this instance with a new filtered value
	 */
	public function __call( string $method, $args ) : Data_Value {
		return $this->filter( $method, $args );
	}


	/**
	 * Magic __toString
	 *
	 * @since v2.0.0
	 * @return string String(iest) representation of the value
	 */
	public function __toString() : string {
		if ( is_scalar( $this->value ) ) {
			return strval( $this->value );
		} else {
			return json_encode( $this->value );
		}
	}

	/**
	 * Magic __invoke
	 *
	 * @since v2.0.0
	 * @return mixed The value
	 */
	public function __invoke() {
		return $this->value;
	}
}
