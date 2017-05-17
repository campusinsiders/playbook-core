<?php
/**
 * Playbook Demo Utils
 *
 * @package  Lift\Playbook\UI\Utils
 * @since  v2.0.0
 */

namespace Lift\Playbook;
use Lift\Playbook\UI\Base_Template;
use Lift\Playbook\Playbook_Render_Exception;

/**
 * Class: Playbook Demo Utils
 *
 * @since  v2.0.0
 */
class Playbook_Demo {
	/**
	 * Reflection
	 *
	 * @var  ReflectionClass Reflector of the class being demoed.
	 */
	public $reflection;

	/**
	 * Class Name
	 *
	 * @var string The (short) name of the Component or Module being demoed
	 */
	public $class_name;

	/**
	 * Public Url
	 *
	 * @var string The public url root of Playbook, helps with link building
	 */
	public $public_url;

	/**
	 * Docs Url
	 *
	 * @var string The public url root of the Documentation directory
	 */
	public $docs_url;

	/**
	 * Coverage Url
	 *
	 * @var string The public url root of the Coverage directory
	 */
	public $coverage_url;

	/**
	 * Ensures the required member properties have been setup
	 *
	 * @since  v2.0.0
	 * @throws Playbook_Render_Exception Thrown if a demo has not called `setup()` yet.
	 * @param  boolean $throw Whether or not to throw an execption.
	 * @return boolean True if setup, false if not
	 */
	public function ensure( bool $throw = false ) {
		if ( is_null( $this->reflection ) ) {
			if ( $throw ) {
				throw new Playbook_Render_Exception( 'The demo has not been setup, please $demo->setup( new Component );' );
			}
			return false;
		}
		return true;
	}

	/**
	 * Sets up class properties for a demo
	 *
	 * @since  v2.0.0
	 * @param  Base_Template $class The component or module we are setting the demo up for.
	 * @return Playbook_Demo   Self
	 */
	public function setup( Base_Template $class ) : Playbook_Demo {
		$this->reflection = new \ReflectionClass( $class );
		return $this;
	}

	/**
	 * Get Class Name (short) of the current reflection
	 *
	 * @since  v2.0.0
	 * @return string A class name
	 */
	public function get_class_name() : string {
		$this->class_name = $this->reflection->getShortName();
		return $this->class_name;
	}

	/**
	 * Get Current Version of Class, read from Class DocBlock
	 *
	 * @since  v2.0.0
	 * @return string Semver version string
	 */
	public function get_version() : string {
		$version = '';
		$pattern = '@version\s+([0-9a-zA-Z\.-]+)@';
		if ( preg_match( $pattern, $this->reflection->getDocComment(), $matches ) ) {
			$version = $matches[1];
		}
		return $version;
	}

	/**
	 * Get Public Link to the Documentation folder.
	 *
	 * @return string Url
	 */
	public function get_docs_link() : string {
		$file_name = str_replace( '\\', '.', $this->reflection->getName() );
		$root = dirname( dirname( dirname( __FILE__ ) ) );
		return $root . '/docs/classes/' . $file_name . '.html';
	}

	/**
	 * Get public link to Factory docs
	 *
	 * @return string Url
	 */
	public function get_factory_link() : string {
		$docs = $this->get_docs_link();
		$factory = str_replace( 'Components', 'Factories', $docs );
		return str_replace( 'Component', 'Factory', $factory );
	}

	/**
	 * Get Public Link to the Coverage folder.
	 *
	 * @return string Url
	 */
	public function get_coverage_link() : string {
		$abs_path = $this->reflection->getFileName();
		$root = dirname( dirname( dirname( __FILE__ ) ) );
		$path = ltrim( $abs_path, $root . 'UI' );
		return $root . '/coverage/' . $path . '.html';
	}

	/**
	 * Add Header
	 *
	 * @since v2.0.0
	 * @param string ...$badges Badge markup to inject into header.
	 * @return  Playbook_Demo self
	 */
	public function add_header( string ...$badges ) : Playbook_Demo {
		if ( ! $this->ensure() ) {
			return $this;
		}
		?>
		<div class="showcase-component">
			<h1 class="showcase-component--header">
				<?php echo esc_html( str_replace( '_', ' ', $this->get_class_name() ) ); ?>

				<?php if ( ! empty( $badges ) ) : ?>
				<span class="showcase-component--header-badges">
					<?php
					foreach ( $badges as $badge ) {
						echo wp_kses_post( $badge );
					}
					?>
				</span>
				<?php endif; ?>
			</h1>
			<div class="showcase-component--demos">
		<?php
		return $this;
	}

	/**
	 * Add Badge
	 *
	 * @since v2.0.0
	 * @param string $label A label for the badge.
	 * @param string $link  Option link for the badge.
	 */
	public function add_badge( string $label, string $link = '' ) : string {
		ob_start();
		?>
		<span class="header-badges--badge-version">
				<?php echo esc_html( $label ); ?>
		</span>
		<?php
		$badge = ob_get_clean();
		if ( '' !== $link ) :
			ob_start();
		?>
			<a href="<?php echo esc_url( $link ); ?>" style="text-decoration: none;" target="_blank">
				<?php echo wp_kses_post( $badge ); ?>
			</a>
		<?php
		$badge = ob_get_clean();
		endif;
		return $badge;
	}

	/**
	 * Add the default badges to the header, shortcut for passing the defaults as separate args
	 *
	 * @since  v2.0.0
	 * @return  string The markup of the default badges
	 */
	public function add_default_badges() : string {
		ob_start();
		echo wp_kses_post( $this->add_version_badge()
			. $this->add_dev_docs_badge()
			. $this->add_factory_badge()
			. $this->add_code_coverage_badge()
		);
		return ob_get_clean();
	}

	/**
	 * Add Version Badge to Header
	 *
	 * @since  v2.0.0
	 * @return  string The badge markup for the class version
	 */
	public function add_version_badge() : string {
		return $this->add_badge( $this->get_version() );
	}

	/**
	 * Add Developer Documentation Badge
	 *
	 * @since  v2.0.0
	 * @return  string The badge markup for the dev docs badge
	 */
	public function add_dev_docs_badge() {
		return $this->add_badge( 'Template Documentation', $this->get_docs_link() );
	}

	/**
	 * Add Code Coverage Badge
	 *
	 * @since  v2.0.0
	 * @return  string The badge markup for the code coverage badge
	 */
	public function add_code_coverage_badge() {
		return $this->add_badge( 'Code Coverage Reports', $this->get_coverage_link() );
	}

	/**
	 * Add Factory Badge
	 *
	 * @since  v2.0.0
	 * @return  string|void The badge markup for the factory documentation
	 */
	public function add_factory_badge() {
		$factory_name = str_replace( 'Component', 'Factory', $this->get_class_name() );
		$fqsen = '\\Lift\\Playbook\\UI\\Factories\\' . $factory_name;
		if ( class_exists( $fqsen ) ) {
			return $this->add_badge( 'Factory Documentation', $this->get_factory_link() );
		}
		return '';
	}

	/**
	 * Add Demo
	 *
	 * @since v2.0.0
	 * @param Base_Template $template The component or module to demo.
	 * @param string        $title    The title of the section.
	 * @return Playbook_Demo          self
	 */
	public function add_demo( Base_Template $template, string $title = '' ) : Playbook_Demo {
		if ( ! $this->ensure() ) {
			return $this;
		}
		$class_base = strtolower( $this->reflection->getShortName() );
		?>
		<div class="demo">
			<div class="<?php echo esc_attr( $class_base ); ?>">

				<h3 class="<?php echo esc_attr( $class_base ) . '--heading'; ?>">
					<?php echo esc_html( $title ); ?>
				</h3>

				<div class="<?php echo esc_attr( $class_base ) . '--template'; ?>">
					<?php echo $template; ?>
				</div>

			</div>
		</div>
		<?php
		return $this;
	}

	/**
	 * Cleans up the current object
	 *
	 * @since  v2.0.0
	 * @return Playbook_Demo Self
	 */
	public function cleanup() : Playbook_Demo {
		?>
			</div> <!-- /.showcase-component-demos -->
		</div> <!-- /.showcase-component -->
		<?php

		foreach ( get_object_vars( $this ) as $prop => $value ) {
			$this->$prop = null;
		}

		return $this;
	}
}
