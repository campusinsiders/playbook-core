<?php
/**
 * Template Factory Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\UI\Components\Example_Component;
use Lift\Playbook\TestHelpers\WP_Objects;
use Mockery;

class Template_Factory_Test extends \PHPUnit_Framework_Testcase {
	use WP_Objects;

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_create() {
		$attributes = [ 'array' => [ 1,2,3 ] ];

		$c = Example_Factory::create( $attributes );

		$this->assertInstanceOf( Example_Component::class, $c );
		$this->assertEquals( $attributes['array'], $c->get('array') );
	}

	public function test_wp_post() {
		$post = self::mock_post();
		$post_content = 'Test content';
		$post_title = 'Test Title';
		$post->post_content = $post_content;
		$post->post_title = $post_title;

		$c = Example_Factory::wp_post( $post );

		$this->assertInstanceOf( Example_Component::class, $c );
		$this->assertEquals( $post_content, $c->get( 'string' ) );
		$this->assertEquals( $post_title, $c->get( 'object' )->title );
	}

	public function test_wp_term() {
		$term = self::mock_term();
		$name = 'Test Term';
		$description = 'Test Term Description';
		$term->name = $name;
		$term->description = $description;

		$c = Example_Factory::wp_term( $term );

		$this->assertInstanceOf( Example_Component::class, $c );
		$this->assertEquals( $description, $c->get( 'string' ) );
		$this->assertEquals( $name, $c->get( 'object' )->name );
	}

	public function test_wp_user() {
		$user = self::mock_user();
		$user_login = 'test_login';
		$caps = [ 'editor' => true ];
		$id = 25;

		$user->data->user_login = $user_login;
		$user->caps = $caps;
		$user->ID = $id;

		$c = Example_Factory::wp_user( $user );

		$this->assertInstanceOf( Example_Component::class, $c );
		$this->assertEquals( $user_login, $c->get('object')->user_login );
		$this->assertEquals( $caps, $c->get('array') );
		$this->assertEquals( $id, $c->get('integer') );
	}

	public function test_bootstrap__with_post() {
		$post = self::mock_post();

		$c = Example_Factory::bootstrap( $post );

		$this->assertInstanceOf( Example_Component::class, $c );
	}

	public function test_bootstrap__with_term() {
		$term = self::mock_term();

		$c = Example_Factory::bootstrap( $term );

		$this->assertInstanceOf( Example_Component::class, $c );
	}

	public function test_bootstrap__with_user() {
		$user = self::mock_user();

		$c = Example_Factory::bootstrap( $user );

		$this->assertInstanceOf( Example_Component::class, $c );
	}

	public function test_bootstrap__with_callback() {
		$callback = function() {
			return new Example_Component(['integer' => 25 ]);
		};

		$c = Example_Factory::bootstrap( null, [], $callback );

		$this->assertInstanceOf( Example_Component::class, $c );
		$this->assertEquals( 25, $c->get( 'integer' ) );
	}

	public function test_bootstrap__with_inexplicit_object_type() {
		$o = new \stdClass();
		$o->integer = 25;
		$o->string = 'my string';

		$c = Example_Factory::bootstrap( $o, ['array' => [1,2,3]] );

		$this->assertInstanceOf( Example_Component::class, $c );
		$this->assertEquals( 25, $c->get( 'integer' ) );
		$this->assertEquals( 'my string', $c->get( 'string' ) );
		$this->assertEquals( [1,2,3], $c->get( 'array') );
	}

	public function test_duplicate() {
		$origin = new Example_Component();

		$c = Example_Factory::duplicate( $origin );

		$this->assertEquals( $origin, $c );
	}
}
