<?php
/**
 * Example Factory
 *
 * @package  Lift\Playbook\UI
 * @subpackage  Factories
 */
namespace Lift\Playbook\UI\Factories;
use Lift\Playbook\UI\Base_Template;
use Lift\Playbook\UI\Base_Template_Factory;
use Lift\Playbook\Interfaces\Template_Factory;
use Lift\Playbook\Interfaces\Template;
use Lift\Playbook\UI\Featured_Media_Utils;
use Lift\Playbook\UI\Post_Utils;
use Lift\Playbook\UI\Hooks;
use Lift\Playbook\UI\Components\Example_Component;



/**
 * Class: ExampleFactory
 *
 * @uses Lift\Playbook\UI\Featured_Media_Utils
 * @uses Lift\Playbook\UI\Post_Utils
 * @see  Lift\Playbook\UI\Base_Template_Factory
 * @see  Lift\Playbook\UI|Utils\Featured_Media_Utils
 * @see  Lift\Playbook\UI\Post_Utils
 * @see  Lift\Playbook\UI\Components\Example_Component
 */
class Example_Factory extends Base_Template_Factory implements Template_Factory {
	use Featured_Media_Utils;
	use Post_Utils;

	/**
	 * Create
	 *
	 * @param  array $attributes   An array of attributes
	 * @return Example_Component A Example_Component
	 */
	public static function create( array $attributes = array() ) : Template {
		return new Example_Component( $attributes );
	}

	/**
	 * Create from a WP_Post object
	 *
	 * @param  \WP_Post $post       WP_Post object
	 * @param  array    $attributes Default attributes
	 * @return Example_Component     Example_Component object
	 */
	public static function wp_post( \WP_Post $post, array $attributes = array() ) : Template {
		$o = new \stdClass();
		$o->title = $post->post_title;
		$o->content = $post->post_content;
		/**
		 * Filter: playbook\create_post_excerpt_component
		 *
		 * @param  array  	$attributes See Example_Component arguments
		 * @param  \WP_Post $post    	WP_Post object
		 */
		$attributes = Hooks::apply_filters( 'playbook\create_post_excerpt_component', array(
				'integer' 		=> $post->ID,
				'string'		=> $post->post_content,
				'boolean'		=> true,
				'double'		=> 3.14,
				'array'			=> [ $post->post_title, $post->post_content ],
				'object'		=> $o,
		), $post );

		return new Example_Component( $attributes );
	}

	/**
	 * Create from a WP_Term object
	 *
	 * @param  \WP_Term $term       WP_Term object
	 * @param  array    $attributes Default attributes
	 * @return Example_Component     Example_Component object
	 */
	public static function wp_term( \WP_Term $term, array $attributes = array() ) : Template {
		$o = new \stdClass();
		$o->name = $term->name;
		$o->description = $term->description;
		/**
		 * Filter: playbook\create_post_excerpt_component
		 *
		 * @param  array  	$attributes See Example_Component arguments
		 * @param  \WP_Term $term    	WP_Term object
		 */
		$attributes = Hooks::apply_filters( 'playbook\create_post_excerpt_component', array(
				'integer' 		=> $term->term_id,
				'string'		=> $term->description,
				'boolean'		=> true,
				'double'		=> 3.14,
				'array'			=> [ $term->name, $term->description ],
				'object'		=> $o,
		), $term );

		return new Example_Component( $attributes );
	}

	/**
	 * Create from a WP_User object
	 *
	 * @param  \WP_User $user       WP_User object
	 * @param  array    $attributes Default attributes
	 * @return Example_Component     Example_Component object
	 */
	public static function wp_user( \WP_User $user, array $attributes = array() ) : Template {
		/**
		 * Filter: playbook\create_post_excerpt_component
		 *
		 * @param  array  	$attributes See Example_Component arguments
		 * @param  \WP_User $user    	WP_User object
		 */
		$attributes = Hooks::apply_filters( 'playbook\create_post_excerpt_component', array(
				'integer' 		=> $user->ID,
				'string'		=> $user->data->user_login,
				'boolean'		=> true,
				'double'		=> 3.14,
				'array'			=> $user->caps,
				'object'		=> $user->data,
		), $user );

		return new Example_Component( $attributes );
	}
}

class Example2_Factory extends Example_Factory {};
