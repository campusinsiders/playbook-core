<?php
/**
 * Playbook Feature Image Utils
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\UI\Hooks;

/**
 * Trait: Playbook Feature Image Utils
 *
 * @since  v2.0.0
 * @see    Lift\Playbook\UI\Hooks
 */
trait Featured_Image_Utils {
	/**
	 * Has Featured Image
	 *
	 * @uses \has_post_thumbnail()
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return boolean        True if the post has a featured image, false otherwise.
	 */
	public static function has_featured_image( \WP_Post $post ) : bool {
		$has_image = \has_post_thumbnail( $post->ID );

		/**
		 * Filter: Playbook Has Featured Image
		 *
		 * @param boolean   $has_image  Boolean true | false.
		 * @param \WP_Post  $post       A WP_Post object.
		 * @since v2.0.0
		 */
		return (bool) Hooks::apply_filters( 'playbook\has_featured_image', $has_image , $post );
	}

	/**
	 * Get Featured Image ID
	 *
	 * @uses   \get_post_thumbnail_id()
	 * @since  v2.0.0
	 * @param  \WP_Post $post    WP_Post object.
	 * @return int               The Featured Image attachment ID
	 */
	public static function get_featured_image_id( \WP_Post $post ) : int {
		$img_id = 0;
		if ( self::has_featured_image( $post ) ) {
			$img_id = \get_post_thumbnail_id( $post->ID );
		}

		/**
		 * Filter: Playbook Featured Image Src
		 *
		 * @param int|NULL  $img_id The id of the placeholder image.
		 * @param \WP_Post  $post   A WP_Post object.
		 * @since v2.0.0
		 */
		return intval( Hooks::apply_filters( 'playbook\featured_image_id', $img_id, $post ) );
	}

	/**
	 * Get Featured Images Src
	 *
	 * @uses   \wp_get_attachment_url()
	 * @since  v2.0.0
	 * @param  \WP_Post $post    WP_Post Object.
	 * @return string            An image url
	 */
	public static function get_featured_image_src( \WP_Post $post ) : string {
		if ( self::has_featured_image( $post ) ) {
			$url = \wp_get_attachment_url( self::get_featured_image_id( $post ) );
		} else {
			$url = self::get_featured_image_placeholder();
		}

		/**
		 * Filter: Playbook Featured Image Src
		 *
		 * @param string    $url    The url of the placeholder image.
		 * @param \WP_Post  $post   A WP_Post object.
		 * @since v2.0.0
		 */
		return (string) Hooks::apply_filters( 'playbook\featured_image_src', $url, $post );
	}

	/**
	 * Get Featured Image Placeholder
	 *
	 * @since  v2.0.0
	 * @return string The default placeholder image
	 */
	public static function get_featured_image_placeholder() : string {
		$url = '//placehold.it/640x480';
		/**
		 * Filter: Playbook Featured Image Placeholder
		 *
		 * @param string $url The url of the placeholder image.
		 * @since v2.0.0
		 */
		return (string) Hooks::apply_filters( 'playbook\featured_image_placeholder', $url );
	}
}
