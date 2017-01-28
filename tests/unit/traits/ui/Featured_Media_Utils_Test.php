<?php
/**
 * Featured Media Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Utils\Featured_Media_Utils;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\TestHelpers\WP_Objects;
use Lift\Playbook\UI\Hooks;

class Featured_Media_Utils_Test extends \PHPUnit_Framework_Testcase {
	use WP_Objects;

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_get_featured_media_type() {
		$post = self::mock_post();

		$result = Example_Factory::get_featured_media_type( $post );

		$this->assertEquals( 'image', $result );
	}

	public function test_get_featured_media__when_image() {
		$post = self::mock_post();

		$result = Example_Factory::get_featured_media( $post );

		$this->assertEquals( Example_Factory::get_featured_image_placeholder(), $result );
	}

	public function test_get_featured_media__when_video() {
		$post = self::mock_post();
		$dummy_video_url = '//example.com/test.mov';
		// Filter media type to return video
		\WP_Mock::onFilter( 'playbook\featured_media_type')
			->with( 'image' )
			->reply( 'video' );

		// Filter has video to ensure we have one
		\WP_Mock::onFilter( 'playbook\has_featured_video')
			->with( false )
			->reply( true );

		// Filter in video response
		\WP_Mock::onFilter( 'playbook\get_featured_video_src' )
			->with( '' )
			->reply( $dummy_video_url );

		$result = Example_Factory::get_featured_media( $post );

		$this->assertEquals( $dummy_video_url, $result );
	}

	public function test_has_featured_media() {
		$post = self::mock_post();

		$result = Example_Factory::has_featured_media( $post );

		$this->assertFalse( $result );
	}
}
