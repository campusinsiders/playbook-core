<?php
/**
 * Decorator: Template Factory Profiler
 *
 * @package  Lift\Playbook
 * @subpackage  Decorators
 */

namespace Lift\Playbook\Decorators;

/**
 * Class: Template_Factory_Profiler_Decorator
 *
 * @since v2.0.0
 */
class Template_Factory_Profiler_Decorator {
	/**
	 * Start
	 *
	 * @var float Microtime at start.
	 */
	public $start;

	/**
	 * End
	 *
	 * @var float Microtime at end.
	 */
	public $end;

	/**
	 * Factory
	 *
	 * @var callable Callable function that returns a Template_Factory.
	 */
	public $factory_generator;

	/**
	 * Constructor
	 *
	 * @param callable $factory_generator Callable function that returns a Template_Factory.
	 */
	public function __construct( callable $factory_generator ) {
		$this->start();
		$this->factory = call_user_func( $factory_generator );
		$this->factory->render();
		$this->end();
	}

	/**
	 * Start
	 *
	 * @return void
	 */
	public function start() {
		$this->start = microtime();
	}

	/**
	 * End
	 *
	 * @return void
	 */
	public function end() {
		$this->end = microtime();
		printf( '<!-- %s rendered in %s ms. -->',
			esc_html( get_class( $this->factory ) ),
			esc_html( ( $this->end - $this->start ) )
		);
	}

	/**
	 * Magic: Invoke
	 *
	 * @return Template_Factory
	 */
	public function __invoke() {
		return $this->factory;
	}
}
