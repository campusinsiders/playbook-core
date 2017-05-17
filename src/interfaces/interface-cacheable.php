<?php
/**
 * Cacheable Interface
 *
 * @package Lift\Playbook
 * @subpackage Interfaces
 */

namespace Lift\Playbook\Interfaces;
use Lift\Playbook\Interfaces\Template_Factory;

/**
 * Interface: Cacheable
 */
interface Cacheable {
	/**
	 * Constructor.
	 *
	 * @param string   $key      The cache lookup key.
	 * @param string   $group    The cache lookup group.
	 * @param callable $callback A callback function that returns the value to cache.
	 * @param integer  $expires  Timestamp for when the cache should expire.
	 * @return Cacheable       Self instance.
	 */
	public function __construct( string $key, string $group, callable $callback, int $expires );

	/**
	 * Resolve
	 *
	 * @return mixed The value in the cache if present, otherwise the return value of the callback.
	 */
	public function resolve();


	/**
	 * Cache Add.
	 *
	 * @return boolean True if added to the cache.
	 */
	public function cache_add() : bool;

	/**
	 * Cache Get.
	 *
	 * @return mixed The value.
	 */
	public function cache_get();

	/**
	 * Cache Has.
	 *
	 * @return boolean True if in the cache.
	 */
	public function cache_has() : bool;

	/**
	 * Cache Flush.
	 *
	 * @return boolean True if flushed to the cache.
	 */
	public function flush() : bool;
}
