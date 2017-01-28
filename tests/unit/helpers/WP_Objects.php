<?php
/**
 * Test Helper: WP Objects
 *
 * @package  Lift\Playbook
 * @subpackage  Tests
 */

namespace Lift\Playbook\TestHelpers;
use Mockery;

trait WP_Objects {

	public static function mock_post() {
		$mock_post = Mockery::mock( '\WP_Post' );
		$mock_post->ID = 1000;
		$mock_post->post_author = '1';
		$mock_post->post_date = '2016-12-25 15:15:15';
		$mock_post->post_date_gmt = '2016-12-25 22:15:15';
		$mock_post->post_content = 'This is some sample content';
		$mock_post->post_title = 'This is a sample title';
		$mock_post->post_excerpt = '';
		$mock_post->post_status = 'publish';
		$mock_post->comment_status = 'closed';
		$mock_post->ping_status = 'closed';
		$mock_post->post_password = '';
		$mock_post->post_name = 'this-is-some-sample-content';
		$mock_post->to_ping = '';
		$mock_post->pinged = '';
		$mock_post->post_modified = '2012-03-15 15:15:12';
		$mock_post->post_modified_gmt = '2012-03-15 22:15:12';
		$mock_post->post_content_filtered = '';
		$mock_post->post_parent = 0;
		$mock_post->guid = 'http://wptest.io/demo/?p=1011';
		$mock_post->menu_order = 0;
		$mock_post->post_type = 'post';
		$mock_post->post_mime_type = '';
		$mock_post->comment_count = '0';
		$mock_post->filter = 'raw';

		return $mock_post;
	}

	public static function mock_term() {
		$mock_term = Mockery::mock( '\WP_Term' );

		$mock_term->term_id = 1;
		$mock_term->name = 'My Term';
		$mock_term->slug = 'my-term';
		$mock_term->term_group = 0;
		$mock_term->term_taxonomy_id = 1;
		$mock_term->taxonomy = 'category';
		$mock_term->description = 'My Term Description';
		$mock_term->parent = 0;
		$mock_term->count = 10;
		$mock_term->filter = 'raw';

		return $mock_term;
	}

	public static function mock_user() {
		$mock_user = Mockery::mock( '\WP_User' );

		// User Data
		$data = new \stdClass;
		$data->ID = '1';
		$data->user_login = 'wordpress';
		$data->user_pass = md5('wordpress');
		$data->user_nicename = 'wordpress';
		$data->user_email = 'nobody@wordpress.org';
		$data->user_url = 'https://wordpress.org';
		$data->user_registered = '2016-12-25 15:15:15';
		$data->user_activation_key = '';
		$data->user_status = '0';
		$data->display_name = 'WordPress';

		$mock_user->data = $data;
		$mock_user->ID = 1;
		$mock_user->caps = [ 'administrator' => true ];
		$mock_user->allcaps = []; // Big array full of junk, hopefully won't need this.
		$mock_user->filter = null;

		return $mock_user;
	}
}
