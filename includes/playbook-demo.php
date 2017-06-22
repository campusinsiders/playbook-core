<?php
/**
 * Welcome to Playbook!
 *
 * This is the entry point for playbook.  Thanks for working on it :)
 *
 * @version 0.1.0 Canonical version, bump this alongside package.json
 * @package Lift\Playbook
 */

use Lift\Playbook\Playbook_Demo;

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Playbook Demo</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head();?>
	</head>

	<body>
		<?php
		if ( get_query_var( 'playbook_component' ) ) {
			$component = 'Lift\Playbook\UI\Components\\' . get_query_var( 'playbook_component' );
			echo '<pre>';
			var_dump( $_GET['attributes'] );
			echo '</pre>';
			$demo = new Playbook_Demo();
			$demo->setup( new $component )
				->add_header(
					$demo->add_default_badges(),
					$demo->add_badge( 'My Custom Badge' )
				)
				->add_demo( new $component( $_GET['attributes'] ?? [] ) )
				->cleanup();
		}

		if ( get_query_var( 'playbook_factory' ) ) {
			$factory = playbook_get_factory( get_query_var( 'playbook_factory' ) );
			$demo = new Playbook_Demo();
			global $post;
			$demo->setup( $factory::create() )
				->add_header( $demo->add_default_badges() );
			if ( isset( $_GET['nav_menu'] ) ) {
				$demo->add_demo( call_user_func_array( array( $factory, 'nav_menus' ), array( [ urldecode( $_GET['nav_menu'] ) ] ) ) )
					->cleanup();
			} else {
				$demo->add_demo( $factory::bootstrap( $post, $_GET[ 'attributes' ] ?? [] ) )
				->cleanup();
			}
		}
		?>
		<?php wp_footer();?>
	</body>
</html>
