<?php
/**
 * Interface: Attribute
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  Interfaces
 */

namespace Lift\Playbook\Interfaces;

/**
 * Interface: Attribute
 */
interface Attribute {
	/**
	 * Constructor
	 *
	 * @since v2.0.0
	 * @param string        $name   The name of the attribute.
	 * @param mixed         $value  The value of the attribute.
	 * @param null|callable $setter An optional callable setter, passed the desired value.
	 * @param null|callable $getter An optional callable getter, passed the current value.
	 * @return Attribute   $this
	 */
	public function __construct( string $name, $value, callable $setter = null, callable $getter = null );

	/**
	 * Set
	 *
	 * @since  v2.0.0
	 * @param  mixed $value An immutable value.
	 * @return Attribute    $this
	 */
	public function set( $value ) : Attribute;

	/**
	 * Get
	 *
	 * @since v2.0.0
	 * @return mixed The value
	 */
	public function get();

	/**
	 * Is Set
	 *
	 * @since  v2.0.0
	 * @return boolean Whether the value is set or not.  False if the value is null.
	 */
	public function is_set() : bool;

	/**
	 * Is Full
	 *
	 * @since  v2.0.0
	 * @return boolean Whether the value is not null, and will not evaluate as `empty`
	 */
	public function is_full() : bool;

	/**
	 * Filter
	 *
	 * @since v2.0.0
	 * @param  String      $filter   WordPress Filter to pass value through.
	 * @param  Array|array $args     An array of arguments to pass to the callable function.
	 * @return Attribute            A (clone) of $this instance with a new filtered value
	 */
	public function filter( string $filter, array $args = [] ) : Attribute;
	/**
	 * Transform
	 *
	 * @since v2.0.0
	 * @param  String $thing  String representation of a transform.
	 * @return Attribute     A new Datavalue with a tranfomed value, or the current Datavalue
	 */
	public function transform( string $thing ) : Attribute;

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
	public function when( $condition, callable $callback, ...$args );

	/**
	 * Bind to
	 *
	 * Passes $this Attribute as the first argument to the provided callable.
	 *
	 * @since  v2.0.0
	 * @param  callable $callback Callback function that accepts parameters.
	 * @param  mixed    ...$args  Variadic arguments.
	 * @return mixed              Return value of callback.
	 */
	public function bind_to( callable $callback, ...$args );

	/**
	 * Magic __call
	 *
	 * @since v2.0.0
	 * @param  string $method  In this case, the WordPress filter to pass the value to.
	 * @param  array  $args    An array of agrument to pass to the filter.
	 * @return Attribute      A (clone) of $this instance with a new filtered value
	 */
	public function __call( string $method, $args );


	/**
	 * Magic __toString
	 *
	 * @since v2.0.0
	 * @return string String(iest) representation of the value
	 */
	public function __toString() : string;

	/**
	 * Magic __invoke
	 *
	 * @since v2.0.0
	 * @return mixed The value
	 */
	public function __invoke();
}
