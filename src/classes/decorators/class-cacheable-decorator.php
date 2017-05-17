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
 * Decorator: Cacheable_Decorator
 */
class Cacheable_Decorator implements Cacheable {
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
	 * Constructor.
	 *
	 * @param string   $key      The cache lookup key.
	 * @param string   $group    The cache lookup group.
	 * @param callable $callback A callback function that returns the value to cache.
	 * @param integer  $expires  The length of time to cache the result.
	 * @return Cacheable       Self instance.
	 */
	public function __construct( string $key, string $group, callable $callback, int $expires ) {
		$this->key = $key;
		$this->group = $group;
		$this->callback = $callback;
		$this->expires = ( $expires ) ? time() + $expires : time() + HOUR_IN_SECONDS;
	}

	/**
	 * Resolve
	 *
	 * @return mixed The value in the cache if present, otherwise the return value of the callback.
	 */
	public function resolve() {
		if ( ! $this->cache_has() ) {
			$this->cache_add();
		}
		return $this->cache_get();
	}

	/**
	 * Cache Add.
	 *
	 * @return boolean True if added to the cache.
	 */
	public function cache_add() : bool {
		if ( ! $this->cache_has() ) {
			return wp_cache_add( $this->key, call_user_func( $this->callback ), $this->group, $this->expires );
		} else {
			return wp_cache_replace( $this->key, call_user_func( $this->callback ), $this->group, $this->expires );
		}
	}

	/**
	 * Cache Get.
	 *
	 * @return mixed The value, or null if not found.
	 */
	public function cache_get() {
		return ( $this->cache_has() ) ? wp_cache_get( $this->key, $this->group ) : null;
	}

	/**
	 * Cache Has.
	 *
	 * @return boolean True if in the cache.
	 */
	public function cache_has() : bool {
		return ( false === wp_cache_get( $this->key, $this->group ) ) ? false : true;
	}

	/**
	 * Cache Flush.
	 *
	 * @return boolean True if flushed to the cache.
	 */
	public function flush() : bool {
		return wp_cache_delete( $this->key, $this->group );
	}
}
