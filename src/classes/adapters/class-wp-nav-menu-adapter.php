<?php
/**
 * Nav Menu Adapter
 *
 * @since v2.0.0
 * @package Lift\Playbook
 */

namespace Lift\Playbook\Adapters;
use Lift\Playbook\Interfaces\Adapter;

/**
 * Class: WP_Nav_Menu_Adapter
 *
 * @see Lift\Playbok\Adapters\Base_Adapter
 * @see Lift\Playbook\Interfaces\Adapter
 */
class WP_Nav_Menu_Adapter extends Base_Adapter implements Adapter {
	/**
	 * Source
	 *
	 * @var mixed The source object the Adapter acts upon.
	 */
	public $source;

	/**
	 * Constructor
	 *
	 * @param string $menu The nav menu to provide an adaptation for.
	 */
	public function __construct( $menu ) {
		parent::__construct( wp_get_nav_menu_object( $menu ) );
	}

	/**
	 * Items
	 *
	 * @return Array An array of Nav_Menu_Items.
	 */
	public function items() {
		return wp_get_nav_menu_items( $this->resolve( 'term_id' ) );
	}

	/**
	 * Items Tree
	 *
	 * @return Array An array of navigation items.
	 */
	public function items_tree() : array {
		$tree = array();
		$items = $this->items();
		if ( ! $items ) {
			return $tree;
		}

		foreach ( $items as $item ) {
			if ( ! $item->menu_item_parent ) {
				$tree[ $item->ID ] = $item;
			} else {
				$parent = $tree[ $item->menu_item_parent ];
				if ( isset( $parent->children ) && is_array( $parent->children ) ) {
					$parent->children[ $item->ID ] = $item;
					continue;
				}
				$parent->children = array();
				$parent->children[ $item->ID ] = $item;
			}
		}

		return $tree;
	}
}
