<?php
/**
 * Playbook Feature Media Utils
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\UI\Hooks;

/**
 * Trait: Playbook Feature Media Utils
 *
 * @since  v2.0.0
 * @see    Featured_Image_Utils
 * @see    PlaybookFeaturedVideoUtils
 * @see    Lift\Playbook\UI\Hooks
 */
trait Featured_Media_Utils {
	use Featured_Image_Utils;
	use Featured_Video_Utils;

	/**
	 * Get Featured Media Type
	 *
	 * @todo   This needs proper implementation
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post Object.
	 * @return string         Returns `image` or `video`
	 */
	public static function get_featured_media_type( \WP_Post $post ) : string {
		/**
		 * Filter: playbook\featured_media_type
		 *
		 * @param string 	$type 	The type of featured media ( video | image ).
		 * @param \WP_post 	$post 	WP_Post object.
		 */
		return Hooks::apply_filters( 'playbook\featured_media_type', 'image', $post );
	}

	/**
	 * Get Featured Media
	 *
	 * @internal  	This will always return media, even if self::has_featured_media
	 *              is false.  This way, we never have an empty image src
	 * @since  		v2.0.0
	 * @param  		\WP_Post $post WP_Post object.
	 * @return 		string         The source url of the featured media
	 */
	public static function get_featured_media( \WP_Post $post ) : string {
		// If we're supposed to deliver a video, get a video.
		if ( 'video' === self::get_featured_media_type( $post ) ) {
			$media = self::get_featured_video_src( $post );
		}

		// If we're supposed to get an image, or we have no defined featured media, get an image.
		if ( 'image' === self::get_featured_media_type( $post ) || ! self::has_featured_media( $post ) ) {
			$media = self::get_featured_image_src( $post );
		}

		/**
		 * Filter: playbook\get_featured_media
		 *
		 * @param string 	$media 	The url to the featured media.
		 * @param \WP_post 	$post 	\WP_Post object.
		 */
		return Hooks::apply_filters( 'playbook\get_featured_media', $media, $post );
	}

	/**
	 * Has Featured Media
	 *
	 * @uses \has_post_thumbnail()
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return boolean        True if the post has a featured image, false otherwise.
	 */
	public static function has_featured_media( \WP_Post $post ) : bool {
		if ( ! self::has_featured_image( $post ) && ! self::has_featured_video( $post ) ) {
			return false;
		}
		return true;
	}
}
