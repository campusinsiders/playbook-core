<?php
/**
 * Cacheable Decorator
 *
 * @since v2.0.0
 * @package Lift\Playbook
 * @subpackage Decorators
 */

namespace Lift\Playbook\Decorators;
use Lift\Playbook\Interfaces\Cacheable;

/**
 * Decorator: Cacheable_Fragment_Decorator
 */
class Cacheable_Fragment_Decorator extends Cacheable_Decorator implements Cacheable {
	/**
	 * Key
	 *
	 * @var string The cache key name.
	 */
	public $key;

	/**
	 * Group
	 *
	 * @var string The cache group name.
	 */
	public $group;

	/**
	 * Callback
	 *
	 * @var callable A function callback that returns the value to cache.
	 */
	public $callback;

	/**
	 * Expires
	 *
	 * @var integer Timestamp for when the cache should expire.
	 */
	public $expires;

	/**
	 * Start
	 *
	 * @var float Microtime indicating when the instance was instantiated.
	 */
	public $start;

	/**
	 * Constructor.
	 *
	 * @param string   $key      The cache lookup key.
	 * @param string   $group    The cache lookup group.
	 * @param callable $callback A callback function that returns the value to cache.
	 * @param integer  $expires  The length of time to cache the result.
	 * @return Cacheable       Self instance.
	 */
	public function __construct( string $key, string $group, callable $callback, int $expires ) {
		$this->start = microtime();
		parent::__construct( $key, $group, $callback, $expires );
	}

	/**
	 * Get Fragment
	 *
	 * @return string The fragment.
	 */
	public function get_fragment() : string {
		return $this->resolve();
	}

	/**
	 * Print Fragment
	 *
	 * @return void
	 */
	public function print_fragment() {
		if ( $this->cache_has() ) {
			echo $this->cache_get(); // PHPCS XSS okay.
			printf(
				'<!-- Fragment Cache Hit: Rendered fragment from cache in %fms -->',
				esc_html( ( microtime() - $this->start ) )
			);
		} else {
			echo $this->resolve(); // PHPCS XSS okay.
			printf(
				'<!-- Fragment Cache Miss: Rendered fragment in %fms, cached until %d. -->',
				esc_html( ( microtime() - $this->start ) ),
				absint( $this->expires )
			);
		}
	}
}
