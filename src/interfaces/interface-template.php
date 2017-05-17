<?php
/**
 * Template Interface
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage Interfaces
 */

namespace Lift\Playbook\Interfaces;
use Lift\Playbook\Interfaces\Attribute;

/**
 * Interface: Template
 *
 * The basic interface that defines any template.
 *
 * @since  v2.0.0
 * @uses  Lift\Core\Attribute
 */
interface Template {
	/**
	 * Apply
	 *
	 * @since v2.0.0
	 * @param  Array $attributes Maps an associative array of name => values to the object props.
	 * @return Base_Template       Instance of $this with filled properties
	 */
	public function apply( array $attributes ) : Template;

	/**
	 * Get
	 *
	 * @since v2.0.0
	 * @param  String $name The property to get.
	 * @return mixed        The value of the property
	 */
	public function get( string $name );

	/**
	 * Set
	 *
	 * @since v2.0.0
	 * @param String $name  The property to set.
	 * @param mixed  $value  The value to set the property to.
	 * @return  Attribute   A Attribute object with the property set
	 */
	public function set( string $name, $value ) : Attribute;

	/**
	 * Has
	 *
	 * @since  v2.0.0
	 * @param  string $name The property to check.
	 * @return boolean       True if the value is not `null`, false if it is.
	 */
	public function has( string $name ) : bool;

	/**
	 * Render
	 *
	 * @since v2.0.0
	 * @throws Playbook_Render_Exception Thrown if template doesn't exist.
	 * @return mixed Return value (void) of Base_Template::render_with():void
	 */
	public function render();

	/**
	 * Render With
	 *
	 * @since v2.0.0
	 * @throws  Playbook_Render_Exception Thrown if the template doesn't exist.
	 * @param  String $template The file to use to render the template.
	 * @return void
	 */
	public function render_with( string $template );

	/**
	 * Defer Rendering to Function
	 *
	 * @since  v2.0.0
	 * @param  callable $func        		A function that will handle rendering of the template.
	 * @param  boolean  $ignore_return		If the function above returns a value, should we throw.
	 * @return boolean                   	True if a function handled the rendering, false otherwise.
	 */
	public function defer_rendering_to( $func, bool $ignore_return ) : bool;

	/**
	 * Template Will Render
	 *
	 * Hook that fires directly before the template renders to the screen
	 *
	 * @since  v2.0.0
	 * @return boolean True
	 */
	public function template_will_render() : bool;

	/**
	 * Template Did Render
	 *
	 * Hook that fires directly after the template renders to the screen
	 *
	 * @since  v2.0.0
	 * @return boolean True
	 */
	public function template_did_render() : bool;

	/**
	 * To JSON
	 *
	 * @since v2.0.0
	 * @return string JSON representation of the property assigned to $this
	 */
	public function to_json() : string;

	/**
	 * Magic __toString
	 *
	 * @since v2.0.0
	 * @return string JSON representation of the property assigned to $this
	 */
	public function __toString() : string;

	/**
	 * Magic __call
	 *
	 * @since  v2.0.0
	 * @param  string $name      The name of the called method.
	 * @param  array  $arguments Enumerated arguments.
	 * @return mixed             The value of the property that matched called method, or null.
	 */
	public function __call( string $name, $arguments );
}
