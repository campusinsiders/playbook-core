<?php
/**
 * Base Component
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  UI
 */

namespace Lift\Playbook\UI;

/**
 * Class: Base Component
 *
 * @since  v2.0.0
 */
class Base_Component extends Base_Template {
	/**
	 * Unique Component ID
	 *
	 * @var string
	 */
	public $component_id;

	/**
	 * Constructor
	 *
	 * @since v2.0.0
	 * @param Array|array $attributes The attributes to construct the component with.
	 * @return  Base_Component An instance of Base_Component
	 */
	public function __construct( array $attributes = [] ) {
		parent::__construct( $attributes );
		$this->set_component_id();
		return $this;
	}

	/**
	 * Set Component Id
	 *
	 * @uses uniqid()
	 * @since  v2.0.0
	 * @return  string A unique id for the specific component instance.
	 */
	public function set_component_id() : string {
		if ( $this->has( 'component_id' ) ) {
			return $this->component_id;
		}
		return uniqid( strtolower( $this->get_component_name() ) );
	}

	/**
	 * Get Component
	 *
	 * @since v2.0.0
	 * @return string Fully Qualified Class Name of the called Instance
	 */
	public function get_component() : string {
		return get_called_class();
	}

	/**
	 * Get Component Name
	 *
	 * @since v2.0.0
	 * @return string The name of the called component
	 */
	public function get_component_name() : string {
		$fqsen = $this->get_component();
		$split = array_reverse( explode( '\\', $fqsen ) );

		return reset( $split );
	}

	/**
	 * Get Component Hook Base
	 *
	 * Given a component named ExampleComponent, will build a hook prefix in a
	 * format such as `Playbook\ExampleComponent\`
	 *
	 * @since  v2.0.0
	 * @return string The base of the tag to assist in defining WordPress hooks
	 */
	public function get_component_hook_base() : string {
		return 'Playbook\\' . $this->get_component_name() . '\\';
	}

	/**
	 * Get Component Hook Tag
	 *
	 * Give a component named Example Component with the desired tag suffix of
	 * `my_action`, will construct a tag with format `Playbook\ExampleComponent\my_action`.
	 *
	 * @since  v2.0.0
	 * @param  String $hook_suffix The suffix to be appended to the hook tag base.
	 * @return String              The hook tag
	 */
	public function get_component_hook_tag( string $hook_suffix ) : string {
		return $this->get_component_hook_base() . $hook_suffix;
	}

	/**
	 * Template Will Render
	 *
	 * Fires parent method, then calls component hooks.
	 *
	 * @see  Lift\Playbook\UI\Base_Template::template_will_render()
	 * @since  v2.0.0
	 * @return boolean True if callbacks fired
	 */
	public function template_will_render() : bool {
		parent::template_will_render();
		return $this->component_will_render();
	}

	/**
	 * Template Did Render
	 *
	 * Fires parent method, then calls component hooks.
	 *
	 * @see  Lift\Playbook\UI\Base_Template::template_did_render()
	 * @since  v2.0.0
	 * @return boolean True if callbacks fired
	 */
	public function template_did_render() : bool {
		parent::template_did_render();
		return $this->component_did_render();
	}

	/**
	 * Component Will Render
	 *
	 * @uses  \do_action()
	 * @since  v2.0.0
	 * @return boolean True if callbacks fired
	 */
	public function component_will_render() : bool {
		do_action( $this->get_component_hook_tag( 'component_will_render' ), $this );
		return true;
	}

	/**
	 * Component Did Render
	 *
	 * @uses  \do_action()
	 * @since  v2.0.0
	 * @return boolean True if callbacks fired
	 */
	public function component_did_render() : bool {
		do_action( $this->get_component_hook_tag( 'component_did_render' ) , $this );
		return true;
	}
}
