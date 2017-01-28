<?php
/**
 * Featured Video Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Utils\Featured_Video_Utils;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\TestHelpers\WP_Objects;
use Lift\Playbook\UI\Hooks;

class Featured_Video_Utils_Test extends \PHPUnit_Framework_Testcase {
	use WP_Objects;

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_has_featured_video__when_true() {
		$post = self::mock_post();

		// Filter to make this true
		\WP_Mock::onFilter( 'playbook\has_featured_video' )
			->with( false )
			->reply( true );

		$result = Example_Factory::has_featured_video( $post );

		$this->assertTrue( $result );
	}

	public function test_has_featured_video__when_false() {
		$post = self::mock_post();

		$result = Example_Factory::has_featured_video( $post );

		$this->assertFalse( $result );
	}

	public function test_get_featured_video_id() {
		$post = self::mock_post();
		$post_id = 25;

		// Filters should have this return $post_id
		\WP_Mock::onFilter( 'playbook\get_featured_video_id' )
			->with( 0 )
			->reply( $post_id );

		$result = Example_Factory::get_featured_video_id( $post );

		$this->assertEquals( $post_id, $result );
	}

	public function test_get_featured_video_src() {
		$post = self::mock_post();
		$dummy_video_src = '//example.com/video.mov';

		// Filters should have this return $dummy_video_src
		\WP_Mock::onFilter( 'playbook\get_featured_video_src' )
			->with( '' )
			->reply( $dummy_video_src );

		$result = Example_Factory::get_featured_video_src( $post );

		$this->assertEquals( $dummy_video_src, $result );
	}
}
