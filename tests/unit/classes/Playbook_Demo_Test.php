<?php
/**
 * Tests: Playbook Demo Utils
 *
 * @package  Lift\Playbook\Tests
 */
use Lift\Playbook\UI\Utils\Playbook_Demo;
use Lift\Playbook\UI\Components\Example_Component;
use Lift\Playbook\Playbook_Render_Exception;

class Playbook_DemoTest extends \PHPUnit_Framework_Testcase {

	public function setUp() {
		\WP_Mock::setUp();
		$this->class = new Playbook_Demo();
	}

	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_ensure_when_reflection_set() {
		$result = $this->class->setup( new Example_Component )->ensure();

		$this->assertTrue( $result );
	}

	public function test_ensure_when_reflection_not_set() {
		$empty_class = new Playbook_Demo();

		$result = $empty_class->ensure();

		$this->assertFalse( $result );
	}

	/** @expectedException Lift\Playbook\Playbook_Render_Exception */
	public function test_ensure_throws() {
		$empty_class = new Playbook_Demo();

		$result = $empty_class->ensure( $throw = true );
	}

	public function test_setup() {
		$result = $this->class->setup( new Example_Component );

		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertInstanceOf( \ReflectionClass::class, $result->reflection );
	}

	public function test_get_class_name() {
		$result = $this->class->setup( new Example_Component )->get_class_name();

		$this->assertEquals( 'Example_Component', $result );
	}

	public function test_get_version() {
		$result = $this->class->setup( new Example_Component )->get_version();

		$this->assertEquals( 'v1.0.0-stable', $result );
	}

	public function test_get_docs_link() {
		$suffix = 'docs/classes/Lift.Playbook.UI.Components.Example_Component.html';

		$result = $this->class->setup( new Example_Component )->get_docs_link();

		$this->assertStringEndsWith( $suffix, $result );
	}

	public function test_get_coverage_link() {
		$suffix = 'coverage/Example_Component/Example_Component.php.html';

		$result = $this->class->setup( new Example_Component )->get_coverage_link();

		$this->assertStringEndsWith( $suffix, $result );
	}

	public function test_add_header() {
		ob_start();
		$result = $this->class->setup( new Example_Component )->add_header();
		$html = ob_get_clean();
		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertThat( $html, $this->stringContains( $html, 'Example_Component' ) );
	}

	public function test_add_header_with_badges() {
		\WP_Mock::passthruFunction( 'wp_kses_post', array( 'times' => 1 ) );
		ob_start();
		$result = $this->class->setup( new Example_Component )->add_header(
			$this->class->add_badge( 'Example Badge' )
			);
		$html = ob_get_clean();
		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertThat( $html, $this->stringContains( $html, 'Example Badge' ) );
	}

	public function test_add_header_on_invalid_instance() {
		$empty_class = new Playbook_Demo();
		ob_start();
		$result = $empty_class->add_header();
		$html = ob_get_clean();
		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertEquals( '', $html );
	}

	public function skip_test_add_factory_badge__when_no_factory_exists() {
		ob_start();
		$result = $this->class->setup( new ButtonComponent )->add_header(
			$this->class->add_factory_badge()
			);
		$html = ob_get_clean();
		$this->assertNotContains( 'Factory', $html );
	}

	public function test_add_badge() {
		$label = 'Example Label';
		$url = '//example.com';

		ob_start();
		$result = $this->class->setup( new Example_Component )->add_header(
			$this->class->add_badge( $label, $url )
			);
		$html = ob_get_clean();

		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertThat( $html, $this->stringContains( $html, $label ) );
		$this->assertThat( $html, $this->stringContains( $html, $url ) );
	}

	public function test_add_default_badges() {
		ob_start();
		$result = $this->class->setup( new Example_Component )->add_header(
			$this->class->add_default_badges()
			);
		$html = ob_get_clean();

		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertThat( $html, $this->stringContains( $html, 'v1.0.0-stable' ) );
		$this->assertThat( $html, $this->stringContains( $html, 'Code Coverage' ) );
		$this->assertThat( $html, $this->stringContains( $html, 'Developer Documentations' ) );
	}

	public function test_add_demo() {
		$demo = new Example_Component();

		\WP_Mock::userFunction( 'locate_template', array(
			'times' => 1,
			//'args' => Example_Component::$renderer,
			'return' => ''
			));

		ob_start();
		$result = $this->class->setup( new Example_Component )
			->add_header()
			->add_demo( $demo, 'Testing Example' );
		$html = ob_get_clean();
		ob_start();
		$demo->render();
		$rendered = ob_get_clean();
		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertThat( $html, $this->stringContains( $html, $rendered ) );
	}

	public function test_empty_demo() {
		$empty_class = new Playbook_Demo;

		ob_start();
		$result = $empty_class->add_demo( new Example_Component );
		$html = ob_get_clean();

		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertEquals( '', $html );
	}

	public function test_cleanup() {
		ob_start();
		$result = $this->class->setup( new Example_Component )->add_header()->cleanup();
		$html = ob_get_clean();

		$this->assertInstanceOf( Playbook_Demo::class, $result );
		$this->assertThat( $html, $this->stringContains( $html, '/.showcase-component' ) );
		$this->assertEquals( null, $this->class->reflection );
	}
}
