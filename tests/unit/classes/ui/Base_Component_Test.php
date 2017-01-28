<?php
/**
 * Base Component Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Data_Value;
use Lift\Playbook\UI\Components\Example_Component;
use Lift\Playbook\Playbook_Strict_Type_Exception;

class Base_Component_Test extends \PHPUnit_Framework_Testcase {
	public $values;

	public function setUp() {
		\WP_Mock::setUp();

		$obj = new \stdClass; $obj->prop = 'object value';
		$this->values = [
			'string' => 'string value',
			'integer' => 42,
			'boolean' => true,
			'double' => 3.14,
			'array' => ['Jan', 'Feb', 'Mar'],
			'object' => $obj
			];

		$this->class = new Example_Component();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function data_values() {
		$obj = new \stdClass; $obj->prop = 'object value';
		return [
			['string value'],
			[42],
			[true],
			[3.14],
			[['Jan', 'Feb', 'Mar']],
			['object' => $obj]
			];
	}

	public function test_has_component_id() {
		$result = $this->class->component_id;
		$this->assertNotNull( $this->class->component_id );
	}

	public function test_cannot_overwrite_component_id() {
		$initial = $this->class->component_id;
		$this->class->set_component_id();
		$after = $this->class->component_id;

		$this->assertEquals( $initial, $after );
	}

	public function test_apply() {
		$array = ['string' => 'new string'];

		$this->class->apply( $array );

		$this->assertEquals( new Data_Value( 'string', 'new string' ), $this->class->string );

		// Add new property
		$this->class->new_prop = 'new prop';

		$this->class->apply(['new_prop' => 'new value for prop' ]);

		$this->assertEquals( new Data_Value( 'new_prop', 'new value for prop' ), $this->class->new_prop );
	}

	public function test_component_will_render() {
		$result = $this->class->component_will_render();

		$this->assertTrue( $result );
	}

	public function test_component_did_render() {
		$result = $this->class->component_did_render();

		$this->assertTrue( $result );
	}

	public function test_get_component() {
		$this->assertEquals('Lift\Playbook\UI\Components\Example_Component', $this->class->get_component() );
	}

	public function test_get_component_name() {
		$this->assertEquals( 'Example_Component', $this->class->get_component_name() );
	}

	public function test_get_component_hook_base() {
		$hook_base = $this->class->get_component_hook_base();

		$this->assertEquals( 'Playbook\\Example_Component\\', $hook_base );
	}

	public function test_get_component_hook_tag() {
		$hook_suffix = 'test';
		$expected = 'Playbook\\Example_Component\\test';
		$result = $this->class->get_component_hook_tag( 'test' );
		$this->assertEquals( $expected, $result );
	}
}
