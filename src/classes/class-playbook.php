<?php
/**
 * Playbook
 *
 * @since  v2.0.0
 *
 * @package  Lift\Playbook
 */

namespace Lift\Playbook;
use Lift\Core\Hook_Catalog;
use Lift\Core\Hook_Definition;

/**
 * Class: Playbook
 *
 * Core Playbook class, it all begins here.
 *
 * @since  v2.0.0
 */
class Playbook {
	/**
	 * Hook Catalog
	 *
	 * @var Hook_Catalog
	 */
	public $hook_catalog;

	/**
	 * Factory Map
	 *
	 * @var Factory_Map
	 */
	public $factory_map;

	/**
	 * Constructor
	 *
	 * @since v2.0.0
	 * @param Hook_Catalog $hook_catalog An instance of Hook_Catalog.
	 * @param Factory_Map  $factory_map  An instance of Factory_Map.
	 */
	public function __construct( Hook_Catalog $hook_catalog, Factory_Map $factory_map ) {
		$this->hook_catalog = $hook_catalog;
		$this->factory_map = $factory_map;

		// Add registry hook.
		$this->add_register_hook();

		// Inform WordPress that Playbook is ready.
		do_action( 'playbook_loaded', $this );

		return $this;
	}

	/**
	 * Get Hook Catalog
	 *
	 * @since  v2.0.0
	 * @return Hook_Catalog The $hook_catalog property.
	 */
	public function get_hook_catalog() : Hook_Catalog {
		return $this->hook_catalog;
	}

	/**
	 * Get Factory Map
	 *
	 * @since  v2.0.0
	 * @return Factory_Map The $factory_map property.
	 */
	public function get_factory_map() : Factory_Map {
		return $this->factory_map;
	}

	/**
	 * Add Factory
	 *
	 * @param string                     $reference Reference to the template factory.
	 * @param Template_Factory_Interface $factory   Template factory.
	 * @return Playbook                             Instance of self
	 */
	public function add_factory( string $reference, Template_Factory_Interface $factory ) : Playbook {
		$this->get_factory_map()->register_factory( $reference, $factory );
		return $this;
	}

	/**
	 * Registry Entry Hook
	 *
	 * @since  v2.0.0
	 * @return Playbook Instance of self.
	 */
	public function add_register_hook() {
		$hook = new Hook_Definition( 'init', array( $this, 'register_hook' ), 10 );
		$this->get_hook_catalog()->add_entry( $hook );

		return $this;
	}

	/**
	 * Register Hook
	 *
	 * @since  v2.0.0
	 * @return Playbook Instance of self.
	 */
	public function register_hook() {
		do_action( 'playbook_register', $this );
		return $this;
	}

	/**
	 * Add Default Factories
	 *
	 * @since  v2.0.0
	 * @return Factory_Map The current Factory_Map with all default factories added
	 */
	public function add_default_factories() : Factory_Map {
		$namespace = 'Lift\\Playbook\\UI\\Factories\\';
		$default_factories = array(
			'AuthorInfo' => $namespace . 'AuthorInfoFactory',
			'PostExcerpt' => $namespace . 'PostExcerptFactory',
			);

		foreach ( $default_factories as $reference => $class ) {
			$this->get_factory_map()->register_factory( $reference, new $class() );
		}

		return $this->get_factory_map();
	}
}
