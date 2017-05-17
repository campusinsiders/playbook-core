<?php
/**
 * Attribute Iterator
 *
 * @package  Lift\Playbook\UI
 * @since  v2.0.0
 * @codingStandardsIgnoreFile
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Playbook_Render_Exception;

/**
 * Trait: Attribute Iterator
 *
 * @since  v2.0.0
 * @see    Lift\Playbook\UI\Hooks
 */
trait Attribute_Iterator {
	/**
	 * Get Iterator
	 *
	 * @link http://us3.php.net/manual/en/class.iteratoraggregate.php
	 * @return \ArrayIterator An instance of ArrayIterator
	 */
	public function getIterator() {
		if ( ! is_array( $this->value ) ) {
			throw new Playbook_Render_Exception( 'The value of this attribute is not Traversable.' );
		}
		return new  \ArrayIterator( $this->value );
	}
}
