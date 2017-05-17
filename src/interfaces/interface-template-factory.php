<?php
/**
 * Template Factory Interface
 *
 * @package  Lift\Playbook\UI
 * @subpackage  Interfaces
 */

namespace Lift\Playbook\Interfaces;
use Lift\Playbook\Interfaces\Template;

/**
 * Interface: Template Factory Interface
 *
 * @since  v2.0.0
 */
interface Template_Factory {

	/**
	 * Create
	 *
	 * @since  v2.0.0
	 * @param  array $attributes An array of attributes.
	 * @return Template          An intance of Template
	 */
	public static function create( array $attributes ) : Template;

	/**
	 * Bootstrap
	 *
	 * @since  v2.0.0
	 * @param  mixed $object   An object.
	 * @param  array $defaults Default attributes to use on template construction.
	 * @return Template        An instance of Template
	 */
	public static function bootstrap( $object, array $defaults ) : Template;

	/**
	 * WP Post
	 *
	 * @since  v2.0.0
	 * @param  \WP_Post $post     A WP_Post object.
	 * @param  array    $defaults Default attributes to use on template construction.
	 * @return Template           An instance of Template
	 */
	public static function wp_post( \WP_Post $post, array $defaults ) : Template;

	/**
	 * WP Term
	 *
	 * @since  v2.0.0
	 * @param  \WP_Term $term     A WP_Term object.
	 * @param  array    $defaults Default attributes to use on template construction.
	 * @return Template           An instance of Template
	 */
	public static function wp_term( \WP_Term $term, array $defaults ) : Template;

	/**
	 * WP User
	 *
	 * @since  v2.0.0
	 * @param  \WP_User $user     A WP_User object.
	 * @param  array    $defaults Default attributes to use on template construction.
	 * @return Template           An instance of Template
	 */
	public static function wp_user( \WP_User $user, array $defaults ) : Template;

	/**
	 * Duplicate
	 *
	 * @since  v2.0.0
	 * @param  Template $template Template to duplicate.
	 * @return Template           An instance of Template
	 */
	public static function duplicate( Template $template ) : Template;
}
