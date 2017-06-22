<?php
/**
 * Template Factory
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  UI
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Interfaces\Template;
use Lift\Playbook\Interfaces\Template_Factory;

/**
 * Class: Template_Factory
 *
 * @since  v2.0.0
 */
abstract class Base_Template_Factory implements Template_Factory {

	/**
	 * Disabled Constructor
	 *
	 * @return  void
	 */
	public final function __construct() {}

	/**
	 * Create
	 *
	 * @codeCoverageIgnore This will never execute (abstract class) but returns for Code Sniffs
	 * @since  v2.0.0
	 * @param  array $attributes An array of attributes.
	 * @return Template          A Template object.
	 */
	public static function create( array $attributes = array() ) : Template {
		return new Template;
	}

	/**
	 * Bootstrap
	 *
	 * @param  mixed         $object   Pretty much any object, built for WP_{Post|Term|User} objects.
	 * @param  array         $defaults An array of default attributes.
	 * @param  callable|null $func     Alternative callable to bootstrap Template from.
	 * @return Template                An instance of Template
	 */
	public static function bootstrap( $object, array $defaults = [], callable $func = null ) : Template {
		// If caller has defined a specific bootstrap method/function, call that instead.
		if ( null !== $func ) {
			return call_user_func( $func, $object, $defaults );
		}

		if ( $object instanceof \WP_Post ) {
			return static::wp_post( $object, $defaults );
		}

		if ( $object instanceof \WP_Term ) {
			return static::wp_term( $object, $defaults );
		}

		if ( $object instanceof \WP_User ) {
			return static::wp_user( $object, $defaults );
		}

		if ( false !== wp_get_nav_menu_object( $object ) ) {
			return static::nav_menus( $object, $defaults );
		}

		return static::create( array_merge( (array) $object, $defaults ) );
	}

	/**
	 * Bootstrap from a WP_Post
	 *
	 * @codeCoverageIgnore This will never execute (abstract class) but returns for Code Sniffs
	 * @since  v2.0.0
	 * @param  \WP_Post $post       A WP_Post to bootstrap a template from.
	 * @param  array    $defaults   An array of default attributes to pass to the template.
	 * @return Template   		    A Template
	 */
	public static function wp_post( \WP_Post $post, array $defaults = array() ) : Template {
		return new Template;
	}

	/**
	 * Bootstrap from a WP_Term
	 *
	 * @codeCoverageIgnore This will never execute (abstract class) but returns for Code Sniffs
	 * @since  v2.0.0
	 * @param  \WP_Term $term       A WP_Term to bootstrap a template from.
	 * @param  array    $defaults   An array of default attributes to pass to the template.
	 * @return Template   		    A Template
	 */
	public static function wp_term( \WP_Term $term, array $defaults = array() ) : Template {
		return new Template;
	}

	/**
	 * Bootstrap from a WP_User
	 *
	 * @codeCoverageIgnore This will never execute (abstract class) but returns for Code Sniffs
	 * @since  v2.0.0
	 * @param  \WP_User $user       A WP_User to bootstrap a template from.
	 * @param  array    $defaults   An array of default attributes to pass to the template.
	 * @return Template   		    A Template
	 */
	public static function wp_user( \WP_User $user, array $defaults = array() ) : Template {
		return new Template;
	}

	/**
	 * Duplicate
	 *
	 * @since  v2.0.0
	 * @param  Template $component A component to duplicate.
	 * @return Template            A duplicate of the passed component
	 */
	public static function duplicate( Template $component ) : Template {
		return clone ( $component );
	}

	/**
	 * __clone
	 *
	 * @codeCoverageIgnore
	 * @since   v2.0.0
	 * @access  private
	 * @return  void 	Private no op.
	 */
	private function __clone() {}
}
