<?php
/**
 * Playbook Post Utilities
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\UI\Hooks;

/**
 * Trait: Post_Utils
 *
 * @since  v2.0.0
 */
trait Post_Utils {

	/**
	 * Stringify Post Author
	 *
	 * @internal  Needed because get_the_author can only be used inside a loop.
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return string         String reference to the author, defaults to display name
	 */
	public static function stringify_post_author( \WP_Post $post ) : string {
		$data = get_userdata( $post->post_author )->data;
		return Hooks::apply_filters( 'the_author', $data->display_name ?? $data->user_nicename );
	}

	/**
	 * Get All Post Terms
	 *
	 * @uses   get_object_taxonomies()
	 * @uses   get_the_terms()
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return array          An array of all post terms.
	 */
	public static function get_all_post_terms( \WP_Post $post ) : array {
		$taxonomies = get_object_taxonomies( $post, $output = 'names' );
		$terms = array();
		foreach ( $taxonomies as $taxonomy ) {
			$terms[ $taxonomy ] = get_the_terms( $post->ID, $taxonomy );
		}
		return $terms;
	}

	/**
	 * Stringify Post Terms
	 *
	 * @since  v2.0.0
	 * @param  \WP_Post $post     WP_Post object.
	 * @param  string   $taxonomy The requested taxonomy.
	 * @return string             String representation of assigned terms of the given taxonomy
	 */
	public static function stringify_post_terms( \WP_Post $post, string $taxonomy ) : string {
		$terms = self::get_all_post_terms( $post );
		if ( $terms[ $taxonomy ] ) {
			$glue = Hooks::apply_filters( 'playbook\stringify_post_terms_glue', ', ' );
			return implode( $glue, wp_list_pluck( $terms[ $taxonomy ], 'name' ) );
		}
		return '';
	}

	/**
	 * Stringify Post Categories
	 *
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return string         Stringified default taxonomy categories
	 */
	public static function stringify_post_categories( \WP_Post $post ) : string {
		return self::stringify_post_terms( $post, 'category' );
	}

	/**
	 * Stringify Post Categories
	 *
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return string         Stringified default taxonomy categories
	 */
	public static function stringify_post_tags( \WP_Post $post ) : string {
		return self::stringify_post_terms( $post, 'post_tag' );
	}

	/**
	 * Merge Post Classes
	 *
	 * @param  \WP_Post $post       WP_Post object.
	 * @param  array    $attributes An array of template attributes.
	 * @return string               A string of css classes
	 */
	public static function merge_post_classes( \WP_Post $post, array $attributes ) : string {
		$classes = $attributes['class'] ?? '';
		return implode( ' ', \get_post_class( $classes, $post->ID ) );
	}

	/**
	 * Get The Excerpt
	 *
	 * @codeCoverageIgnore
	 * @todo   We may be able to deprecated this after Trac #36934
	 * @link   https://core.trac.wordpress.org/ticket/36934
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post Object.
	 * @return string         The excerpt
	 */
	public static function get_the_excerpt( \WP_Post $post ) : string {
		if ( empty( $excerpt = $post->post_excerpt ) ) {
			$excerpt = Hooks::apply_filters( 'the_content', $post->post_content );
			$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			$excerpt_length = Hooks::apply_filters( 'excerpt_length', 55 );
			$excerpt_more = Hooks::apply_filters( 'excerpt_more', ' [&hellip;]' );
			$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
		}
		return Hooks::apply_filters( 'the_excerpt', $excerpt );
	}
}
