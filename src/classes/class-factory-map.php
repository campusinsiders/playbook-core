<?php
/**
 * Factory Map
 *
 * @version  2.0.0
 *
 * @package  Lift\Playbook\Integrations
 */

namespace Lift\Playbook;
use Lift\Playbook\Interfaces\Template_Factory;

/**
 * Class: Factory_Map
 *
 * Fairly standard Dependency Injector, here called Factory Map because that better describes
 * its role.  Handles Factory registration, Factory replacements, Factory removals, and
 * obviously getting and checking on the existence of the registered Factories.
 *
 * @since  v2.0.0.
 */
final class Factory_Map {

	/**
	 * Factories
	 *
	 * @var Template_Factory[]
	 */
	public $factories;

	/**
	 * Constructor
	 *
	 * @since  v2.0.0
	 * @return  self instance
	 */
	public function __construct() {
		$this->factories = array();
		return $this;
	}

	/**
	 * Register Factory
	 *
	 * @since  v2.0.0
	 * @param  string           $reference A string reference to the factory.
	 * @param  Template_Factory $class     A Factory implementing TemplateFactorInterface.
	 * @return self                                instance
	 */
	public function register_factory( string $reference, Template_Factory $class ) : Factory_Map {
		if ( ! in_array( $reference, $this->list_refs(), true ) ) {
			array_push( $this->factories, new Factory_Definition( $reference, $class ) );
		} else {
			$this->replace_factory( $reference, $class );
		}

		return $this;
	}

	/**
	 * Replace Factory
	 *
	 * @since  v2.0.0
	 * @param  string           $reference A reference to the factory.
	 * @param  Template_Factory $class     A factory to replace the currently set factory with.
	 * @return self                                instance
	 */
	public function replace_factory( string $reference, Template_Factory $class ) : Factory_Map {
		$this->factories = array_map( function( $factory ) use ( $reference, $class ) {
			return ( $reference === $factory->reference ) ? $factory->set( $class ) : $factory;
		}, $this->factories );

		return $this;
	}

	/**
	 * Remove Factory
	 *
	 * @since  v2.0.0
	 * @param  string $reference A reference to the factory to remove.
	 * @return self              instance
	 */
	public function remove_factory( string $reference ) {
		$this->factories = array_filter( $this->factories, function( $factory ) use ( $reference ) {
			return ( ! ( $reference === $factory->reference ) );
		});

		return $this;
	}

	/**
	 * Get Factory
	 *
	 * @since  v2.0.0
	 * @param  string $reference A reference to factory.
	 * @return Template_Factory|null  The factory, or null if factory doesn't exist
	 */
	public function get_factory( string $reference ) {
		$key = $this->get_factory_key_in_map( $reference );
		if ( false !== $key ) {
			return $this->factories[ $key ]();
		}
		return null;
	}

	/**
	 * Has Factory
	 *
	 * @since  v2.0.0
	 * @param  string $reference A reference to the factory.
	 * @return boolean            True if factory exists with passed reference, false otherwise
	 */
	public function has_factory( string $reference ) : bool {
		return ! ( false === $this->get_factory_key_in_map( $reference ) );
	}

	/**
	 * Get Factory Key in Map
	 *
	 * @since  v2.0.0
	 * @param  string $reference Reference to the factory.
	 * @return int|false         The integer key to the factory, false if it doesn't exist
	 */
	public function get_factory_key_in_map( string $reference ) {
		return array_search( $reference, array_column( $this->factories, 'reference' ) );
	}

	/**
	 * List References
	 *
	 * @since  v2.0.0
	 * @return array An array of the factory references stored in this Factory_Map
	 */
	public function list_refs() {
		return array_map( function( $factory ) {
			return $factory->reference;
		}, $this->factories );
	}

	/**
	 * List Classes
	 *
	 * @since  v2.0.0
	 * @return array An array of the factory classes stored in this Factory_Map
	 */
	public function list_classes() {
		return array_map( function( $factory ) {
			return $factory->class;
		}, $this->factories );
	}
}
