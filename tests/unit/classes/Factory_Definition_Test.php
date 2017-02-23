<?php
/**
 * Template Factory Tests
 *
 * @since  v2.0.0
 * @package  Lift\Playbook\Tests
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Factories\Example_Factory;
use Lift\Playbook\Factory_Definition;

class Factory_Definition_Test extends \PHPUnit_Framework_Testcase {

	public function setUp() {
		$this->factory = new Example_Factory();
	}

	public function test_constructor() {
		$fd = new Factory_Definition( 'example', $this->factory );

		$this->assertEquals( 'example', $fd->reference );
		$this->assertEquals( $this->factory, $fd->class );
	}

	public function test_set() {
		$fd = new Factory_Definition( 'example', $this->factory );

		$new = new Example_Factory;
		$return = $fd->set( $new );

		$this->assertEquals( $new, $fd->class );
		$this->assertInstanceOf( Factory_Definition::class, $return );
	}

	public function test_invoke() {
		$fd = new Factory_Definition( 'example', $this->factory );

		$this->assertInstanceOf( Example_Factory::class, $fd() );
	}
}
