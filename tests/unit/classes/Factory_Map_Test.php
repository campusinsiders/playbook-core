<?php
/**
 * Template Factory Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\UI\Factories\Example2_Factory;
use Lift\Playbook\Factory_Map;
use Lift\Playbook\Factory_Definition;

class Factory_Map_Test extends \PHPUnit_Framework_Testcase {

	public function setUp() {
		$this->factory_map = new Factory_Map;

		$this->factory = new Example_Factory;

		$this->factory_definition = new Factory_Definition( 'example', $this->factory );
	}

	public function test_constructor() {
		$map = new Factory_Map();

		$this->assertTrue( is_array( $map->factories ) );
	}

	public function test_register_factory() {
		$this->factory_map->register_factory( 'example', $this->factory );
		$fd = new Factory_Definition( 'example', $this->factory );

		$this->assertInstanceOf( Factory_Definition::class, $this->factory_map->factories[0] );
		$this->assertEquals( $fd, $this->factory_map->factories[0] );
	}

	public function test_replace_factory() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$this->factory_map->register_factory( 'example', new Example2_Factory );

		$this->assertInstanceOf( Example2_Factory::class, $this->factory_map->factories[0]->class );

		$this->factory_map->replace_factory( 'example', $this->factory );

		$this->test_register_factory();
	}

	public function test_remove_factory() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$this->factory_map->remove_factory( 'example' );

		$this->assertFalse( ! empty( $this->factory_map->factories ) );
	}

	public function test_get_factory() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$return = $this->factory_map->get_factory( 'example' );
		$null = $this->factory_map->get_factory( 'meatballs' );

		$this->assertEquals( $this->factory, $return );
		$this->assertEquals( null, $null );
	}

	public function test_has_factory() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$true = $this->factory_map->has_factory( 'example' );
		$false = $this->factory_map->has_factory( 'pizza' );

		$this->assertTrue( $true );
		$this->assertFalse( $false );
	}

	public function test_get_factory_key_in_map() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$key = $this->factory_map->get_factory_key_in_map( 'example' );

		$this->assertEquals( 0, $key );
	}

	public function test_list_refs() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$array = $this->factory_map->list_refs();

		$this->assertEquals( ['example'], $array );
	}

	public function test_list_classes() {
		$this->factory_map->register_factory( 'example', $this->factory );

		$array = $this->factory_map->list_classes();

		$this->assertEquals( [$this->factory], $array );
	}
}
