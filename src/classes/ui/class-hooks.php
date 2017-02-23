<?php
/**
 * Helper: Hooks
 *
 * @package  Lift\Playbook\UI
 */

namespace Lift\Playbook\UI;

/**
 * Trait: Hooks
 *
 * @since  v2.0.0
 */
class Hooks {

	/**
	 * Apply Filters
	 *
	 * Internally, we use this implementation of apply_filters for several reasons. Since we
	 * use filters liberally throughout Playbook, we can make sweeping adjustments to how
	 * we interact with WordPress via filters from a single function.  Additionally, we only
	 * have to write integration tests if we want.  All internals that use this implementation
	 * can be mocked without have to define the actual filter inside of a test suite.  Lastly,
	 * each internal call to apply filters that is wrapped by our implementation is, in turn,
	 * filtereable using WordPress filters system.
	 *
	 * Example of HookCeption
	 * ```php
	 * add_filter( 'playbook\apply_filters', function( $args ) {
	 *     // Describe the filter, then disable it
	 *     var_dump( $args[0] );
	 *     $args[0] = '__return_false';
	 *     return $args;
	 * });
	 * ```
	 *
	 * @since  v2.0.0
	 * @param  string $tag     The name of the filter.
	 * @param  mixed  $value   The value on which the filters hooked to $tag are applied on.
	 * @param  mixed  ...$args Additional variables passed to the functions hooked to $tag.
	 * @return mixed           The filtered value after all hooked functions are applied to
	 */
	public static function apply_filters( string $tag, $value, ...$args ) {
		if ( ! function_exists( '\apply_filters' ) ) {
			return $value;
		}
		$args = apply_filters( 'playbook\apply_filters', func_get_args() );
		return call_user_func_array( '\apply_filters', $args );
	}

	/**
	 * Do Action
	 *
	 * Internally, we use this implementation of do_action for several reasons. Since we
	 * use actions liberally throughout Playbook, we can make sweeping adjustments to how
	 * we interact with WordPress via actions from a single function.  Additionally, we only
	 * have to write integration tests if we want.  All internals that use this implementation
	 * can be mocked without have to define the actual action inside of a test suite.
	 *
	 * @since  v2.0.0
	 * @param  string $tag     The name of the action to be executed.
	 * @param  mixed  ...$args Additional arguments which are passed on to the hooked functions.
	 * @return void
	 */
	public static function do_action( string $tag, ...$args ) {
		if ( ! function_exists( 'do_action' ) ) {
			return;
		}
		$args = apply_filters( 'playbook\do_action', func_get_args() );
		call_user_func_array( 'do_action', $args );
	}
}
