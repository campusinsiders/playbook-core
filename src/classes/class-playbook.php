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
	 * Singleton Instance
	 *
	 * @var Playbook
	 */
	static $singleton;

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

		// Add rewrite hook.
		$this->add_rewrite_hook();

		// Inform WordPress that Playbook is ready.
		do_action( 'playbook_loaded', $this );

		return $this;
	}

	/**
	 * Static Factory
	 *
	 * @param  bool|boolean $prefer_new Optional flag defining whether to get a new instance, defaults to the singleton.
	 * @return Playbook                 Self instance.
	 */
	public static function factory( bool $prefer_new = false ) : Playbook {
		if ( $prefer_new ) {
			return new self( new Hook_Catalog, new Factory_Map );
		}
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self( new Hook_Catalog, new Factory_Map );
		}
		return self::$singleton;
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
	 * @param string           $reference Reference to the template factory.
	 * @param Template_Factory $factory   Template factory.
	 * @return Playbook                             Instance of self
	 */
	public function add_factory( string $reference, Template_Factory $factory ) : Playbook {
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
	 * Add Rewrite Hook
	 *
	 * Registers the hooks to functions that handle adding Playbook Demos to a site.
	 *
	 * @since  v2.0.0
	 * @return  Playbook Instance of self.
	 */
	public function add_rewrite_hook() {
		$rewrite = new Hook_Definition( 'init', array( $this, 'create_rewrite_rules' ), 10 );
		$this->get_hook_catalog()->add_entry( $rewrite );

		$template = new Hook_Definition( 'template_include', array( $this, 'get_demo_template' ) );
		$this->get_hook_catalog()->add_entry( $template );

		return $this;
	}

	/**
	 * Create Rewrite Rules
	 *
	 * Creates the rewrite rules that match requests to Playbook demos.
	 *
	 * @since  v2.0.0
	 * @return void
	 */
	public function create_rewrite_rules() {
		global $wp;
		$wp->add_query_var( 'playbook_component' );
		add_rewrite_rule( '^playbook/([^/]+)/?$', 'index.php?playbook_component=$matches[1]', 'top' );
		add_rewrite_endpoint( 'playbook_factory', EP_PERMALINK | EP_PAGES );
		flush_rewrite_rules( false );
	}

	/**
	 * Get Demo Template
	 *
	 * If the current route is to a Playbook Demo, this function will get the correct template
	 * for showing the template.  This will be either a theme file, another plugin file, or the
	 * file included with Playbook Core.
	 *
	 * @since  v2.0.0
	 * @param  string|null $original_template The original template that would have been shown.
	 * @return string                         The template that should be shown.
	 */
	public function get_demo_template( string $original_template = null ) : string {
		$comp = get_query_var( 'playbook_component' );
		if ( $comp ) {
			$theme = locate_template( [ 'playbook-demo-' . $comp . '.php', 'playbook-demo.php' ] );
			return ( '' !== $theme )
				? apply_filters( 'playbook_demo_template_include', $theme )
				: $this->get_demo_template_path();
		}

		$factory = get_query_var( 'playbook_factory' );
		if ( $factory ) {
			$theme = locate_template( [ 'playbook-demo-' . $factory . '.php', 'playbook-demo.php' ] );
			return ( '' !== $theme )
				? apply_filters( 'playbook_demo_template_include', $theme )
				: $this->get_demo_template_path();
		}
		return $original_template;
	}

	/**
	 * Get Demo Template Path
	 *
	 * @since  v2.0.0
	 * @return string The path to the plugin demo template.
	 */
	public function get_demo_template_path() : string {
		return  PLAYBOOK_CORE_PATH . '/includes/playbook-demo.php';
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
