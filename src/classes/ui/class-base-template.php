<?php
/**
 * Base_Template
 *
 * @since  v2.0.0
 * @package  Lift\Playbook
 * @subpackage  UI
 */

namespace Lift\Playbook\UI;
use Lift\Playbook\Playbook_Render_Exception;
use Lift\Playbook\Interfaces\Template;
use Lift\Playbook\Interfaces\Attribute;
use Lift\Core\Interfaces\File_Loader;

/**
 * Class: Base Template
 *
 * Meant to be used as the base class to build Components from.
 *
 * @since  v2.0.0
 */
abstract class Base_Template implements Template {

	/**
	 * The path of file that will act as the Renderer for the Component/Module template
	 *
	 * @since v2.0.0
	 * @var string
	 */
	static $renderer;

	/**
	 * File Loader
	 *
	 * @since v2.0.0
	 * @var File_Loader
	 */
	public $file_loader;

	/**
	 * Constructor
	 *
	 * @since v2.0.0
	 * @param Array|array      $attributes  An array of attributes, set in child constructors.
	 * @param File_Loader|null $file_loader An instance of a File_Loader interface.
	 * @return  Base_Template		        Instance of this with filled properties
	 */
	public function __construct( array $attributes = [], File_Loader $file_loader = null ) {
		foreach ( get_object_vars( $this ) as $prop => $value ) {
			$this->$prop = Attribute_Factory::create( $prop, $value );
		}

		if ( $file_loader ) {
			$this->file_loader = $file_loader;
		}

		return $this->apply( $attributes );
	}

	/**
	 * Apply
	 *
	 * @since v2.0.0
	 * @param  Array $attributes Maps an associative array of name => values to the object props.
	 * @return Template          Instance of $this with filled properties
	 */
	public function apply( array $attributes ) : Template {
		foreach ( $attributes as $name => $value ) {
			$setter = ( method_exists( $this, 'set_' . $name ) ) ? array( $this, 'set_' . $name ) : null;
			$getter = ( method_exists( $this, 'get_' . $name ) ) ? array( $this, 'get_' . $name ) : null;
			if ( property_exists( $this, $name ) ) {
				if ( $this->$name instanceof Attribute ) {
					$this->$name->name = $name;
					$this->$name->setter = $setter;
					$this->$name->getter = $getter;
					$this->$name = $this->$name->set( $value );
					continue;
				}
				$this->$name = Attribute_Factory::create( $name, $value, $setter, $getter );
			}
		}
		return $this;
	}

	/**
	 * Get
	 *
	 * @since v2.0.0
	 * @param  String $name The property to get.
	 * @return mixed        The value of the property
	 */
	public function get( string $name ) {
		return $this->$name->get();
	}

	/**
	 * Set
	 *
	 * @since v2.0.0
	 * @param String $name  The property to set.
	 * @param mixed  $value  The value to set the property to.
	 * @return  Base_Attribute   A Base_Attribute object with the property set
	 */
	public function set( string $name, $value ) : Attribute {
		$this->apply( [
			$name => $value,
		] );
		return $this->$name;
	}

	/**
	 * Has
	 *
	 * @since  v2.0.0
	 * @param  string $name The property to check.
	 * @return boolean       True if the value is not `null`, false if it is.
	 */
	public function has( string $name ) : bool {
		return ( ! $this->$name instanceof Attribute ) ? false : $this->$name->is_set();
	}

	/**
	 * Render
	 *
	 * @since v2.0.0
	 * @throws Playbook_Render_Exception Thrown if template doesn't exist.
	 * @return mixed Return value (void) of Base_Template::render_with():void
	 */
	public function render() {
		$renderer = static::$renderer;
		$custom = locate_template( 'playbook/' . basename( static::$renderer ) );
		if ( '' !== $custom && ! is_null( $custom ) ) {
			$renderer = $custom;
		}
		return $this->render_with( $renderer );
	}

	/**
	 * Render With
	 *
	 * @since v2.0.0
	 * @throws  Playbook_Render_Exception Thrown if the template doesn't exist.
	 * @param  String $template The file to use to render the template.
	 * @return void
	 */
	public function render_with( string $template ) {
		if ( file_exists( $template ) ) {
			$this->template_will_render();
			include $template;
			$this->template_did_render();
			return;
		}
		throw new Playbook_Render_Exception( 'Could not find template ' . $template );
	}

	/**
	 * Defer Rendering to Function
	 *
	 * @since  v2.0.0
	 * @param  callable $func        		A function that will handle rendering of the template.
	 * @param  boolean  $ignore_return		If the function above returns a value, should we throw.
	 * @return boolean                   	True if a function handled the rendering, false otherwise.
	 */
	public function defer_rendering_to( $func, bool $ignore_return = false ) : bool {
		if ( is_callable( $func ) ) {
			// Alert lifecyle that template is about to render.
			$this->template_will_render();
			// Pass $this to rendering function.
			$return_from_func = call_user_func_array( $func, [ $this ] );
			// If rendering function returned something, should we alert developer.
			if ( ! $ignore_return && $return_from_func && ! is_bool( $return_from_func ) ) {
				$message = 'Template rendering was deferred to a function/method that returned a ';
				$message .= 'non boolean value.  NO operations are performed on the return value of the ';
				$message .= 'function rendering was deferred to, for purposes of escaping.';
				_doing_it_wrong( __FUNCTION__, esc_html( $message ), 'v2.0.0' );
			}
			// Alert lifecycle template rendered.
			$this->template_did_render();

			// Return a boolean from inside the callable, or true.
			return is_bool( $return_from_func ) ? $return_from_func : true;
		}

		return false;
	}

	/**
	 * Template Will Render
	 *
	 * Hook that fires directly before the template renders to the screen
	 *
	 * @since  v2.0.0
	 * @return boolean True
	 */
	public function template_will_render() : bool {
		return true;
	}

	/**
	 * Template Did Render
	 *
	 * Hook that fires directly after the template renders to the screen
	 *
	 * @since  v2.0.0
	 * @return boolean True
	 */
	public function template_did_render() : bool {
		return true;
	}

	/**
	 * To JSON
	 *
	 * @since v2.0.0
	 * @return string JSON representation of the property assigned to $this
	 */
	public function to_json() : string {
		return json_encode( get_object_vars( $this ) );
	}

	/**
	 * Magic __toString
	 *
	 * @since v2.0.0
	 * @return string JSON representation of the property assigned to $this
	 */
	public function __toString() : string {
		ob_start();
		try {
			$this->render();
		} catch ( \Throwable $t ) {
			echo esc_html( $t->getMessage() );
		}
		return ob_get_clean();
	}

	/**
	 * Magic __call
	 *
	 * @since  v2.0.0
	 * @param  string $name      The name of the called method.
	 * @param  array  $arguments Enumerated arguments.
	 * @return mixed             The value of the property that matched called method, or null.
	 */
	public function __call( string $name, $arguments ) {
		$getter = 'get_' . $name;
		if ( $this->has( $name ) || method_exists( $this, $getter ) ) {
			return method_exists( $this, $getter )
				? call_user_func( [ $this, $getter ], $arguments )
				: $this->get( $name );
		}
		return null;
	}
}
