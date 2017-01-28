<?php
/**
 * Playbook Feature Video Utils
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\UI\Hooks;

/**
 * Trait: Playbook Feature Video Utils
 *
 * @since  v2.0.0
 * @see    Lift\Playbook\UI\Hooks
 */
trait Featured_Video_Utils {
	/**
	 * Has Featured Video
	 *
	 * @todo   Implement a real way to check if a post has a featured video
	 * @since  v2.0.0
	 * @param  \WP_Post $post WP_Post object.
	 * @return boolean        True if the post has a featured image, false otherwise.
	 */
	public static function has_featured_video( \WP_Post $post ) : bool {
		return (bool) Hooks::apply_filters( 'playbook\has_featured_video', false, $post );
	}

	/**
	 * Get Featured Video ID
	 *
	 * @todo   Implement a real way to get a featured video id
	 * @since  v2.0.0
	 * @param  \WP_Post $post    WP_Post object.
	 * @return int               The Featured Video attachment ID
	 */
	public static function get_featured_video_id( \WP_Post $post ) : int {
		return (int) Hooks::apply_filters( 'playbook\get_featured_video_id', 0, $post );
	}

	/**
	 * Get Featured Video Src
	 *
	 * @todo   Implement a real way get a featured video source
	 * @since  v2.0.0
	 * @param  \WP_Post $post    WP_Post Object.
	 * @return string            An video url
	 */
	public static function get_featured_video_src( \WP_Post $post ) : string {
		return (string) Hooks::apply_filters( 'playbook\get_featured_video_src', '', $post );
	}
}
