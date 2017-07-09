<?php
/**
 * Data Value
 *
 * Defines the Lift\Playbook\UI|Base_Attribute class, which all template attributes should
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
 * Class: Base_Attribute
 *
 * Each template attribute is instantiated as an instance of the Base_Attribute class.
 * This allows us shortcuts to transforming and filtering data, as well as some of
 * the value utilities, like DateTime and quick decoding JSON to Object operations.
 *
 * @since  v2.0.0
 */
class Base_Attribute implements Attribute {
	/**
	 * Name of a value, used to access value from public
	 *
	 * @since v2.0.0
	 * @var string
	 */
	public $name;

	/**
	 * The stored value
	 *
	 * @since v2.0.0
	 * @var mixed
	 */
	public $value;

	/**
	 * Getter
	 *
	 * @var callable|null Callable function to handle getting the value.
	 */
	public $getter;

	/**
	 * Setter
	 *
	 * @var callable|null Callable function to handle setting the value.
	 */
	public $setter;

	/**
	 * The value type, strict when use_strict is (bool) true
	 *
	 * @since v2.0.0
	 * @var string
	 */
	public $type;

	/**
	 * Use strict
	 *
	 * @since v2.0.0
	 * @var bool
	 */
	public $use_strict = false;

	/**
	 * Constructor
	 *
	 * @since v2.0.0
	 * @param string        $name   The name of the attribute.
	 * @param mixed         $value  The value of the attribute.
	 * @param null|callable $setter An optional callable setter, passed the desired value.
	 * @param null|callable $getter An optional callable getter, passed the current value.
	 * @return Attribute            Self instance.
	 */
	public function __construct( string $name, $value, callable $setter = null, callable $getter = null ) {
		$this->name = $name;
		$this->setter = $setter;
		$this->getter = $getter;
		$this->value = is_callable( $this->setter ) ? call_user_func( $this->setter, $value ) : $value;
		$this->use_strict = ( defined( 'WP_DEUBG' ) && WP_DEBUG ) ? true : false;
		return $this;
	}

	/**
	 * Set
	 *
	 * @since v2.0.0
	 * @param mixed $value    An immutable value.
	 * @return Base_Attribute $this
	 */
	public function set( $value ) : Attribute {
		if ( $this->is_valid( $value ) ) {
			$this->value = is_callable( $this->setter ) ? call_user_func( $this->setter, $value ) : $value;
			return $this;
		}
		return Attribute_Factory::create( $this->name, $value, $this->setter, $this->getter );
	}

	/**
	 * Get
	 *
	 * @since v2.0.0
	 * @return mixed The value
	 */
	public function get() {
		return is_callable( $this->getter ) ? call_user_func( $this->getter, $this->value ) : $this->value;
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
	 * Is Valid
	 *
	 * @param mixed $value The value to ensure validity.
	 * @return bool
	 */
	public function is_valid( $value ) {
		return true;
	}

	/**
	 * Filter
	 *
	 * @since v2.0.0
	 * @param  String      $filter   WordPress Filter to pass value through.
	 * @param  Array|array $args     An array of arguments to pass to the callable function.
	 * @return Attribute            A (clone) of $this instance with a new filtered value
	 */
	public function filter( string $filter, array $args = [] ) : Attribute {
		$clone = clone $this;
		return $clone->set( apply_filters( $filter, $this->get(), $args ) );
	}

	/**
	 * Transform
	 *
	 * @since v2.0.0
	 * @param  String $thing  String representation of a transform.
	 * @return Attribute     A new Datavalue with a tranfomed value, or the current Datavalue
	 */
	public function transform( string $thing ) : Attribute {
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
	 * Passes $this Base_Attribute as the first argument to the provided callable.
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
	 * @return Attribute      A (clone) of $this instance with a new filtered value
	 */
	public function __call( string $method, $args ) : Attribute {
		return $this->filter( $method, $args );
	}


	/**
	 * Magic __toString
	 *
	 * @since v2.0.0
	 * @return string String(iest) representation of the value
	 */
	public function __toString() : string {
		if ( is_scalar( $this->get() ) ) {
			return strval( $this->get() );
		} else {
			return json_encode( $this->get() );
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
