<?php
/**
 * Template Factory Interface
 *
 * @package  Lift\Playbook\UI
 * @subpackage  Interfaces
 */

namespace Lift\Playbook\Interfaces;
use Lift\Playbook\UI\Base_Template;

/**
 * Interface: Template Factory Interface
 *
 * @since  v2.0.0
 */
interface Template_Factory_Interface {

	/**
	 * Create
	 *
	 * @since  v2.0.0
	 * @param  array $attributes An array of attributes.
	 * @return Base_Template       An intance of Base_Template
	 */
	public static function create( array $attributes ) : Base_Template;

	/**
	 * Bootstrap
	 *
	 * @since  v2.0.0
	 * @param  mixed $object    An object.
	 * @param  array $defaults Default attributes to use on template construction.
	 * @return Base_Template     An instance of Base_Template
	 */
	public static function bootstrap( $object, array $defaults ) : Base_Template;

	/**
	 * WP Post
	 *
	 * @since  v2.0.0
	 * @param  \WP_Post $post     A WP_Post object.
	 * @param  array    $defaults Default attributes to use on template construction.
	 * @return Base_Template      An instance of Base_Template
	 */
	public static function wp_post( \WP_Post $post, array $defaults ) : Base_Template;

	/**
	 * WP Term
	 *
	 * @since  v2.0.0
	 * @param  \WP_Term $term     A WP_Term object.
	 * @param  array    $defaults Default attributes to use on template construction.
	 * @return Base_Template      An instance of Base_Template
	 */
	public static function wp_term( \WP_Term $term, array $defaults ) : Base_Template;

	/**
	 * WP User
	 *
	 * @since  v2.0.0
	 * @param  \WP_User $user     A WP_User object.
	 * @param  array    $defaults Default attributes to use on template construction.
	 * @return Base_Template      An instance of Base_Template
	 */
	public static function wp_user( \WP_User $user, array $defaults ) : Base_Template;

	/**
	 * Duplicate
	 *
	 * @since  v2.0.0
	 * @param  Base_Template $template Template to duplicate.
	 * @return Base_Template           An instance of Base_Template
	 */
	public static function duplicate( Base_Template $template ) : Base_Template;
}
