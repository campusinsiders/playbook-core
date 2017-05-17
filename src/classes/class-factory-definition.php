<?php
/**
 * Factory Definition
 *
 * @since  v2.0.0
 *
 * @package  Lift\Playbook
 */

namespace Lift\Playbook;
use Lift\Playbook\Interfaces\Template_Factory;

/**
 * Class: Factory_Definition
 *
 * @since  v2.0.0
 */
final class Factory_Definition {
	/**
	 * Reference
	 *
	 * @var string String reference to the Factory
	 */
	public $reference;

	/**
	 * Class
	 *
	 * @var Template_Factory Class to use as the Factory
	 */
	public $class;

	/**
	 * Constructor
	 *
	 * @since  v2.0.0
	 * @param string                     $reference String reference to the Factory.
	 * @param Template_Factory $class     Factory to use, identified by $reference.
	 */
	public function __construct( string $reference, Template_Factory $class ) {
		$this->reference = $reference;
		$this->class = $class;
		return $this;
	}

	/**
	 * Set Class
	 *
	 * @param Template_Factory $class Factory to use.
	 */
	public function set( Template_Factory $class ) : Factory_Definition {
		$this->class = $class;
		return $this;
	}

	/**
	 * Magic: Invoke
	 *
	 * @return Template_Factory Instance of the factory
	 */
	public function __invoke() : Template_Factory {
		return $this->class;
	}
}
