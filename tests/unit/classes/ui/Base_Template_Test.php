<?php
/**
 * Base Template Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\Interfaces\Attribute;
use Lift\Playbook\UI\Base_Attribute;
use Lift\Playbook\UI\Attribute_Factory;
use Lift\Playbook\UI\Components\Example_Component;
use Lift\Playbook\Playbook_Strict_Type_Exception;

class Base_Template_Test extends \PHPUnit_Framework_Testcase {
	public $values;

	public function setUp() {
		\WP_Mock::setUp();

		$obj = new \stdClass;
		$obj->prop = 'object value';
		$this->values = [
			'string' => 'string value',
			'integer' => 42,
			'boolean' => true,
			'double' => 3.14,
			'array' => [ 'Jan', 'Feb', 'Mar' ],
			'object' => $obj,
			];

		$this->class = new Example_Component();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function data_values() {
		$obj = new \stdClass;
		$obj->prop = 'object value';
		return [
			[ 'string value' ],
			[ 42 ],
			[ true ],
			[ 3.14 ],
			[ [ 'Jan', 'Feb', 'Mar' ] ],
			[ 'object' => $obj ],
			];
	}

	public function test_apply() {
		$array = [ 'string' => 'new string' ];

		$this->class->apply( $array );

		$this->assertEquals( Attribute_Factory::create( 'string', 'new string' ), $this->class->string );

		// Add new property
		$this->class->new_prop = 'new prop';

		$this->class->apply( [ 'new_prop' => 'new value for prop' ] );

		$this->assertEquals( Attribute_Factory::create( 'new_prop', 'new value for prop' ), $this->class->new_prop );
	}

	/** @dataProvider data_values */
	public function test_get( $value ) {
		$name = gettype( $value );
		$this->class->apply( $this->values );
		$this->assertEquals( $value, $this->class->get( $name ) );
	}

	/** @dataProvider data_values */
	public function test_set( $value ) {
		$name = gettype( $value );
		$this->class->set( $name, $value );

		$this->assertInstanceOf( Base_Attribute::class, $this->class->$name );
		$this->assertEquals( $value, $this->class->$name->get() );
	}

	public function test_render() {
		$expected_file = TESTDIR . '/unit/helpers/Example_Component/Example_Component_Rendered.html';
		$this->class->apply( $this->values );

		\WP_Mock::userFunction( 'locate_template', array(
			'args' => 'playbook/Example_Component_Renderer.php',
			'times' => 1,
			'return' => '',
		));

		ob_start();
		$this->class->render();
		$html = ob_get_clean();
		$this->assertStringEqualsFile( $expected_file, $html );
	}

	/** @expectedException Lift\Playbook\Playbook_Render_Exception */
	public function test_render_with_missing_file() {
		$missing = '/this/file/should/not/exist.php';
		$this->class->render_with( $missing );
	}

	public function test_defer_rendering_to() {
		// Set a function counter to ensure our render function is called only once.
		$called = 0;

		// Define a callback to handle rendering
		$render_func = function( $component ) use ( &$called ) {
			$called++;
			return $component;
		};

		$expect_true = $this->class->defer_rendering_to( $render_func, $ignore_return = true );
		$expect_false = $this->class->defer_rendering_to( 'undefinedFunction', $ignore_return = true );

		$this->assertTrue( $expect_true );
		$this->assertFalse( $expect_false );
		$this->assertEquals( 1, $called );
	}

	public function test_defer_rendering_to__function_that_echoes() {
		// Define a callback that echoes a value
		$echo_func = function( $component ) {
			echo 'Hello World';
		};

		ob_start();
		$expect_print = $this->class->defer_rendering_to( $echo_func );
		$from_func = ob_get_clean();

		$this->assertTrue( $expect_print );
		$this->assertEquals( 'Hello World', $from_func );
	}

	public function test_defer_rendering_to__function_that_returns_value_to_echo() {
		// Define a callback that returns a value to echo
		$echo_ret_func = function( $component ) {
			return 'Echo this when you get it.';
		};

		// We are expecting a _doing_it_wrong
		\WP_Mock::userFunction( '_doing_it_wrong', array(
			'times' => 1,
			'args' => [
				\WP_Mock\Functions::type( 'string' ),
				\WP_Mock\Functions::type( 'string' ),
				\WP_Mock\Functions::type( 'string' )
				],
			'return' => null
			));

		ob_start();
		$echo_this = $this->class->defer_rendering_to( $echo_ret_func );
		$from_tested = ob_get_clean();

		$this->assertTrue( $echo_this );
		$this->assertEmpty( $from_tested );
	}

	public function test_template_will_render() {
		$result = $this->class->template_will_render();

		$this->assertTrue( $result );
	}

	public function test_template_did_render() {
		$result = $this->class->template_did_render();

		$this->assertTrue( $result );
	}

	public function test_to_json() {
		$json = json_encode( get_object_vars( $this->class ) );

		$this->assertEquals( $json, $this->class->to_json() );
	}

	public function test__toString() {
		$this->assertEquals( 'string', gettype( (string) $this->class ) );
	}

	public function test__toString_with_exception() {
		$this->class->apply( $this->values );

		\WP_Mock::userFunction( 'locate_template', array(
			'args' => 'playbook/Example_Component_Renderer.php',
			'times' => 1,
			'return' => 'invalid/template',
		));
		ob_start();
		echo $this->class;
		$output = ob_get_clean();

		$this->assertEquals( 'Could not find template invalid/template', $output );
	}
}
