<?php
/**
 * Featured Image Utils Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Utils\Featured_Image_Utils;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\TestHelpers\WP_Objects;
use Lift\Playbook\UI\Hooks;

class FeaturedImageUtilsTest extends \PHPUnit_Framework_Testcase {
	use WP_Objects;

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_apply_filters() {
		$tag = 'my_tag';
		$value = false;
		$arg1 = 'my arg 1';

		\WP_Mock::onFilter( $tag )
			->with( $value )
			->reply( $value );

		$result = Hooks::apply_filters( $tag, $value, $arg1 );

		$this->assertEquals( $value, $result );
	}

	public function test_has_featured_image() {
		\WP_Mock::userFunction( 'has_post_thumbnail', array(
			'times' => 2,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return_in_order' => array( true, false )
			));

		$post = self::mock_post();

		$result1 = Example_Factory::has_featured_image( $post );
		$result2 = Example_Factory::has_featured_image( $post );

		$this->assertTrue( $result1 );
		$this->assertFalse( $result2 );
	}

	public function test_get_featured_image_id() {
		\WP_Mock::userFunction( 'has_post_thumbnail', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return' => true
			));

		\WP_Mock::userFunction( 'get_post_thumbnail_id', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return' => 10
			));

		$post = self::mock_post();

		$result = Example_Factory::get_featured_image_id( $post );

		$this->assertEquals( 10, $result );
	}

	public function test_get_featured_image_src() {
		\WP_Mock::userFunction( 'has_post_thumbnail', array(
			'times' => 2,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return' => true
			));

		\WP_Mock::userFunction( 'get_post_thumbnail_id', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return' => 10
			));

		\WP_Mock::userFunction( 'wp_get_attachment_url', array(
			'times' => 1,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return' => 'http://example.com/image.jpg'
			));

		$post = self::mock_post();

		$result = Example_Factory::get_featured_image_src( $post );

		$this->assertEquals( 'http://example.com/image.jpg', $result );
	}

	public function test_get_featured_image_src__when_no_image_set() {
		\WP_Mock::userFunction( 'has_post_thumbnail', array(
			'times' => 2,
			'args' => array( \WP_Mock\Functions::type( 'int' ) ),
			'return' => false
			));

		$post = self::mock_post();
		$r = Example_Factory::has_featured_image( $post );

		$result = Example_Factory::get_featured_image_src( $post );
		$this->assertFalse( $r );
		$this->assertEquals( '//placehold.it/640x480', $result );
	}
}
