<?php
/**
 * Example Component
 *
 * An example component.  Shouldn't really be touched, as this is used in our Unit Tests to ensure
 * all value Types are rendered correctly to the brower.
 *
 * @package  Lift\Playbook\UI\Components\Example
 * @since  v2.0.0
 * @version  v1.0.0-stable
 */
namespace Lift\Playbook\UI\Components;
use Lift\Playbook\UI\Base_Component;

/**
 * Class: Example Component
 *
 * @since  v2.0.0
 * @version  v1.0.0-stable
 */
class Example_Component extends Base_Component{

	/** @var boolean|DataValue An example boolean value, on instantiaton a DataValue instance */
	public $boolean = false;

	/** @var string|DataValue An example string value, on instantiaton a DataValue instance */
	public $string = 'string';

	/** @var integer|DataValue An example integer value, on instantiaton a DataValue instance */
	public $integer = 1;

	/** @var float|DataValue An example float value, on instantiaton a DataValue instance */
	public $double = 3.14;

	/** @var array|DataValue An example array value, on instantiaton a DataValue instance */
	public $array = [ 'one', 'two', 'three' ];

	/** @var object|DataValue An example object value, on instantiaton a DataValue instance */
	public $object;

	/** @var string The template file to use as the (default) renderer */
	public static $renderer = __DIR__ . '/Example_Component_Renderer.php';

	/**
	 * Constructor
	 *
	 * @example   `new ExampleComponent([ 'string' => 'My String', 'boolean' => true ]);`
	 * @since  v2.0.0
	 * @param Array|array $attributes An array of attributes to which to build the Example.
	 */
	public function __construct( array $attributes = [] ) {
		parent::__construct( get_object_vars( $this ) );
		$this->apply( $attributes );
	}
}
