<?php
/**
 * Data Value Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\Interfaces\Attribute;
use Lift\Playbook\UI\Attribute_Factory;
use Lift\Playbook\UI\Base_Attribute;
use Lift\Playbook\Playbook_Strict_Type_Exception;

class Base_Attribute_Test extends \PHPUnit_Framework_Testcase {

	public function setUp() {
		\WP_Mock::setUp();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	/**
	 * @internal We must use (bool) true in this array because (bool) false triggers
	 *           an Error in WP_Mock
	 * @todo  	 Figure why :point_up:
	 */
	public function data_values() {
		$object = new \stdClass();
		$object->test_prop = 'Test Value';
		return array(
			[null],
			['test_string'],
			[1],
			[3.14],
			[true],
			[[1,2,3]],
			[[0 => 'value']],
			[$object]
			);
	}

	/**  @dataProvider data_values */
	public function test___construct( $value ) {
		$name = 'test_property';
		$class = Attribute_Factory::create( $name, $value );

		$this->assertEquals( $name, $class->name );
		$this->assertEquals( $value, $class->value );
		$this->assertEquals( strtolower(gettype($value)), $class->type );
	}

	/**  @dataProvider data_values */
	public function test_set( $new_value ) {
		$name = 'test';
		$old_value = 'Initial Value';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $old_value );

		// Test Old Value
		$this->assertEquals( $old_value, $class->value );

		// Set New Value
		$class = $class->set( $new_value );

		// Test New Value
		$this->assertEquals( $new_value, $class->value );
	}

	/**  @dataProvider data_values */
	public function test_set_with_types( $new_value ) {
		$name = 'test';
		$old_value = 'Initial Value';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $old_value );

		// Test Old Value
		$this->assertEquals( $old_value, $class->value );

		// Set New Value
		$class = $class->set( $new_value, gettype( $new_value ) );

		// Test New Value
		$this->assertEquals( $new_value, $class->value );
	}

	/**  @dataProvider data_values */
	public function test_get( $value ) {
		$name = 'test';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );

		// Test New Value
		$this->assertEquals( $value, $class->get() );
	}

	public function test_is_full_when_full() {
		$name = 'test';
		$value = 'full';
		$class = Attribute_Factory::create( $name, $value );

		$this->assertTrue( $class->is_full() );
	}

	public function test_is_full_when_empty() {
		$name = 'test';
		$value = null;
		$class = Attribute_Factory::create( $name, $value );

		$this->assertFalse( $class->is_full() );
	}

	/**  @dataProvider data_values */
	public function test_type( $value ) {
		$name = 'test';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );

		// Test New Value
		$this->assertEquals( strtolower(gettype( $value )), $class->type );
	}

	/**  @dataProvider data_values */
	public function test_filter( $value ) {
		\WP_Mock::tearDown();
		\WP_Mock::setUp();
		$name = 'test';

		// Setup Class
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Define a filter
		$filter_name = 'test_filter';
		$filter_response = 'Test Filter Response';
		\WP_Mock::onFilter( $filter_name )
			->with( $value )
			->reply( $filter_response );

		// Filter
		$result = $class->filter( $filter_name );

		// Result should be an instance of Attribute
		$this->assertInstanceOf( 'Lift\Playbook\Interfaces\Attribute', $result );

		// Test Response of Filter
		$this->assertEquals( $filter_response, $result->get() );

		// Test Original object hasn't changed
		$this->assertEquals( $freeze, $class );
	}

	public function test_transform_datetime() {
		$name = 'test';
		$value = '1987-01-12';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Setup a Datetime object from $value
		$dt = new \DateTime( $value );

		// Run
		$result = $class->transform( 'datetime' );

		// Test Result
		$this->assertInstanceOf( 'Lift\Playbook\Interfaces\Attribute', $result );
		$this->assertEquals( $dt, $result->get() );
		$this->assertEquals( $freeze, $class );
	}

	public function test_transform_invalid_datetime() {
		$name = 'test';
		$value = 'Not even close to something that can be called a date string!';

		// Setup with Value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Run
		$result = $class->transform( 'datetime' );

		// Test Result
		$this->assertInstanceOf( 'Lift\Playbook\Interfaces\Attribute', $result );
		$this->assertEquals( $value, $result->get() );
		$this->assertEquals( $freeze, $class );
	}

	public function test_to_datetime() {
		$name = 'test';
		$value = '1987-01-12';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Setup a Datetime object from $value
		$dt = new \DateTime( $value );

		// Run
		$result = $class->to_datetime();

		// Test Result
		$this->assertInstanceOf( '\DateTime', $result );
		$this->assertEquals( $dt, $result );
		$this->assertEquals( $freeze, $class );
	}

	public function test_transform_decoded() {
		$name = 'test';
		$value = '{"parent": { "child": "example value" } }';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Setup an object from the json
		$obj = json_decode( $value );

		// Run
		$result = $class->transform( 'decoded' );

		// Test Result
		$this->assertInstanceOf( 'Lift\Playbook\Interfaces\Attribute', $result );
		$this->assertEquals( $obj, $result->get() );
		$this->assertEquals( $freeze, $class );
	}

	public function test_transform_invalid_decoded() {
		$name = 'test';
		$value = 'invalid[]{}json!__()"\/\1|}{"';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Run
		$result = $class->transform( 'decoded' );

		// Test Result
		$this->assertInstanceOf( 'Lift\Playbook\Interfaces\Attribute', $result );
		$this->assertEquals( $value, $result->get() );
		$this->assertEquals( $freeze, $class );
	}

	public function test_json_decode() {
		$name = 'test';
		$value = '{"parent": { "child": "example value" } }';

		// Setup with Old Value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Setup an object from the json
		$obj = json_decode( $value );

		// Run
		$result = $class->json_decode();

		// Test Result
		$this->assertInstanceOf( '\stdClass', $result );
		$this->assertEquals( $obj, $result );
		$this->assertEquals( $freeze, $class );
	}

	public function test_transform_unserialized() {
		$name = 'test';
		$value = 'a:3:{i:1;s:6:"elem 1";i:2;s:6:"elem 2";i:3;s:7:" elem 3";}';

		// Setup with value
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Unserialize the string
		$arr = unserialize( $value );

		// Will use maybe_unserialize
		\WP_Mock::userFunction( 'maybe_unserialize', [
			'args' => $value,
			'times' => 1,
			'return' => $arr
			]);

		// Run
		$result = $class->transform( 'unserialized' );

		// Test Result
		$this->assertInstanceOf( 'Lift\Playbook\Interfaces\Attribute', $result );
		$this->assertEquals( $arr, $result->get() );
		$this->assertEquals( $freeze, $class );
	}

	public function test_contains() {
		$name = 'test';
		$value = 'Check for foo';

		$class = Attribute_Factory::create( $name, $value );
		$this->assertTrue( $class->contains( 'foo' ) );
	}

	public function test_contains_when_it_doesnt() {
		$name = 'test';
		$value = 'Check for foo';

		$class = Attribute_Factory::create( $name, $value );
		$this->assertFalse( $class->contains( 'bar' ) );
	}


	public function test_transform_invalid() {
		$name = 'test';
		$value = 'test value';

		$class = Attribute_Factory::create( $name, $value );

		$result = $class->transform( 'invalid' );

		$this->assertEquals( $class, $result );
	}

	/** @dataProvider data_values */
	public function test___call( $value ) {
		\WP_Mock::tearDown();
		\WP_Mock::setUp();
		$name = 'test';
		$value = 'string';

		// Setup Class
		$class = Attribute_Factory::create( $name, $value );
		$freeze = clone $class;

		// Define a filter
		$filter_name = 'test_filter';
		$filter_response = 'Test Filter Response';
		\WP_Mock::onFilter( $filter_name )
			->with( $value )
			->reply( $filter_response );

		// Filter
		$result = $class->$filter_name();

		// Result should be an instance of Base_Attribute
		$this->assertInstanceOf( get_class( $class ), $result );

		// Test Response of Filter
		$this->assertEquals( $filter_response, $result->get() );

		// Test Original object hasn't changed
		$this->assertEquals( $freeze, $class );
	}

	/** @dataProvider data_values */
	public function test___toString( $value ) {
		$name = 'test';

		$class = Attribute_Factory::create( $name, $value );

		$this->assertEquals( 'string', gettype( (string) $class ) );
	}

	/** @dataProvider data_values */
	public function test___invoke( $value ) {
		$name = 'test';

		$class = Attribute_Factory::create( $name, $value );

		$this->assertEquals( $value, $class() );
	}

	/** @expectedException Lift\Playbook\Playbook_Strict_Type_Exception */
	public function test_strict_mode() {
		$name = 'test';
		$string = 'string';
		$integer = 1;

		$class = Attribute_Factory::create( $name, $string );
		$class->use_strict = true;
		$class = $class->set( $integer);

		$class->assertNotEquals( $integer, $class->get() );
	}

	public function test_strict_mode_no_throw() {
		$name = 'test';
		$string = 'string';
		$integer = 'another_string';

		$class = Attribute_Factory::create( $name, $string );
		$class->use_strict = false;
		$class = $class->set( $integer);

		$class->assertNotEquals( $integer, $class->get() );
	}
}
