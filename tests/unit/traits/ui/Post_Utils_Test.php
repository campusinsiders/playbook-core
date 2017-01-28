<?php
/**
 * Post Utils Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\TestHelpers\WP_Objects;

class Post_Utils_Test extends \PHPUnit_Framework_Testcase {
	use WP_Objects;

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_stringify_post_author() {
		$post = self::mock_post();
		$user = self::mock_user();
		$user->data->display_name = 'Bob Ross';

		\WP_Mock::userFunction( 'get_userdata', array(
			'times' => 1,
			'args' => array( $post->post_author ),
			'return' => $user
			));

		$result = Example_Factory::stringify_post_author( $post );

		$this->assertEquals( 'Bob Ross', $result );
	}

	public function test_get_all_post_terms() {
		$post = self::mock_post();
		$term = self::mock_term();

		\WP_Mock::userFunction( 'get_object_taxonomies', array(
			'times' => 1,
			'args' => array( $post, 'names' ),
			'return' => ['category']
			));

		\WP_Mock::userFunction( 'get_the_terms', array(
			'times' => 1,
			'args' => array( $post->ID, 'category' ),
			'return' => [ $term ]
			));

		$result = Example_Factory::get_all_post_terms( $post );

		$this->assertArrayHasKey( 'category', $result );
		$this->assertEquals( $term, $result['category'][0] );
	}

	public function test_stringify_post_terms() {
		$post = self::mock_post();
		$term1 = self::mock_term();
		$term1->name = 'Test Term 1';
		$term2 = self::mock_term();
		$term2->name = 'Test Term 2';

		\WP_Mock::userFunction( 'get_object_taxonomies', array(
			'times' => 1,
			'args' => array( $post, 'names' ),
			'return' => ['category']
			));

		\WP_Mock::userFunction( 'get_the_terms', array(
			'times' => 1,
			'args' => array( $post->ID, 'category' ),
			'return' => [ $term1, $term2 ]
			));

		\WP_Mock::userFunction( 'wp_list_pluck', array(
			'times' => 1,
			'args' => [[ $term1, $term2], 'name' ],
			'return' => [ 'Test Term 1', 'Test Term 2' ]
			));

		$result = Example_Factory::stringify_post_terms( $post, 'category' );

		$this->assertEquals( 'Test Term 1, Test Term 2', $result );
	}

	public function test_stringify_post_terms__when_post_has_no_terms() {
		$post = self::mock_post();

		\WP_Mock::userFunction( 'get_object_taxonomies', array(
			'times' => 1,
			'args' => array( $post, 'names' ),
			'return' => ['category']
			));

		\WP_Mock::userFunction( 'get_the_terms', array(
			'times' => 1,
			'args' => array( $post->ID, 'category' ),
			'return' => false
			));

		$result = Example_Factory::stringify_post_terms( $post, 'category' );

		$this->assertEquals( '', $result );
	}

	public function test_stringify_post_categories() {
		$post = self::mock_post();
		$term1 = self::mock_term();
		$term1->name = 'Test Term 1';
		$term2 = self::mock_term();
		$term2->name = 'Test Term 2';

		\WP_Mock::userFunction( 'get_object_taxonomies', array(
			'times' => 1,
			'args' => array( $post, 'names' ),
			'return' => ['category']
			));

		\WP_Mock::userFunction( 'get_the_terms', array(
			'times' => 1,
			'args' => array( $post->ID, 'category' ),
			'return' => [ $term1, $term2 ]
			));

		\WP_Mock::userFunction( 'wp_list_pluck', array(
			'times' => 1,
			'args' => [[ $term1, $term2], 'name' ],
			'return' => [ 'Test Term 1', 'Test Term 2' ]
			));

		$result = Example_Factory::stringify_post_categories( $post );

		$this->assertEquals( 'Test Term 1, Test Term 2', $result );
	}

	public function test_stringify_post_tags() {
		$post = self::mock_post();
		$term1 = self::mock_term();
		$term1->name = 'Test Term 1';
		$term2 = self::mock_term();
		$term2->name = 'Test Term 2';

		\WP_Mock::userFunction( 'get_object_taxonomies', array(
			'times' => 1,
			'args' => array( $post, 'names' ),
			'return' => ['post_tag']
			));

		\WP_Mock::userFunction( 'get_the_terms', array(
			'times' => 1,
			'args' => array( $post->ID, 'post_tag' ),
			'return' => [ $term1, $term2 ]
			));

		\WP_Mock::userFunction( 'wp_list_pluck', array(
			'times' => 1,
			'args' => [[ $term1, $term2], 'name' ],
			'return' => [ 'Test Term 1', 'Test Term 2' ]
			));

		$result = Example_Factory::stringify_post_tags( $post );

		$this->assertEquals( 'Test Term 1, Test Term 2', $result );
	}

	public function test_merge_post_classes() {
		$post = self::mock_post();
		$attributes = [ 'class' => 'my-other-class' ];
		$expected = 'my-other-class wp-class hentry';

		\WP_Mock::userFunction( 'get_post_class', array(
			'times' => 1,
			'args' => [ \WP_Mock\Functions::type( 'string' ), $post->ID ],
			'return' => [ 'my-other-class', 'wp-class', 'hentry']
			));

		$result = Example_Factory::merge_post_classes( $post, $attributes );

		$this->assertEquals( $expected, $result );
	}
}
