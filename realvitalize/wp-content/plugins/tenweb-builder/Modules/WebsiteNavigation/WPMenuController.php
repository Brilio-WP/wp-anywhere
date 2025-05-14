<?php

namespace Tenweb_Builder\Modules\WebsiteNavigation;

class WPMenuController
{
    protected static $instance = null;

    /**
     * Constructor.
     * Registers AJAX actions for menu changes and fetching available menu items.
     */
    public function __construct()
    {
        add_action('wp_ajax_wn_nav_menu_changes', [$this, 'navMenuChanges']);
        add_action('wp_ajax_wn_get_available_menu_items', [$this, 'getAvailableMenuItems']);
        add_action('wp_ajax_wn_change_item_settings', [$this, 'changeItemSettings']);
    }

    /**
     * Fetches available menu items based on the provided type and post type.
     *
     * Expects:
     * - $_POST['nonce'] (string): Security nonce.
     * - $_POST['type'] (string): Type of items to fetch ('post' or 'taxonomy').
     * - $_POST['post_type'] (string): Post type or taxonomy name.
     * - $_POST['html_title'] (string): HTML title for the tooltip.
     * - $_POST['nav_menu_id'] (int): ID of the navigation menu.
     *
     * Returns:
     * - JSON response with available menu items and HTML content.
     */
    public function getAvailableMenuItems() {
        //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if ( !isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'twbb' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $html_title = isset($_POST['html_title']) ? sanitize_text_field($_POST['html_title']) : '';
        $nav_menu_id = isset($_POST['nav_menu_id']) ? (int) $_POST['nav_menu_id'] : 0;
        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        //check and remove items which are included in nav menu
        $nav_menu_items = [];
        if( !empty($nav_menu_id) ) {
            $nav_menu_items = wp_get_nav_menu_items($nav_menu_id);
            $nav_menu_items = array_map(function ($item) {
                return (int) $item->object_id;
            }, $nav_menu_items);
        }
        if( $type === 'post' ) {
            $args = [
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => 10,
                'paged' => $page,
                'orderby' => 'title',
                'order' => 'ASC',
                'post__not_in' => $nav_menu_items, //phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn
            ];
            $query = new \WP_Query($args);
            $posts = $query->posts;
        } else if( $type === 'taxonomy' ) {
            //get terms of current taxonomy
            $posts = get_terms([
                'taxonomy' => $post_type,
                'hide_empty' => false,
                'exclude' => $nav_menu_items, //phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
            ]);
        }
        if( $page === 1 ) {
            $output = '<div class="wn-action-tooltip-container twbb-wn-secondary-container" data-post-type="' . esc_attr($post_type) . '">';
            $output .= \Tenweb_Builder\Modules\WebsiteNavigation\RenderingHelper::addMenuItemSecondaryTooltip($html_title, $post_type, $type, $posts);
            $output .= '</div>';
        } else {
            $output = \Tenweb_Builder\Modules\WebsiteNavigation\RenderingHelper::addMenuItemSecondaryTooltip($html_title, $post_type, $type, $posts, $page);
        }
        //send json success the output html
        wp_send_json_success([
            'content' => $output,
            'items' => $posts,
        ]);
    }

    /**
     * Handles various navigation menu changes (add, remove, edit, etc.).
     *
     * Expects:
     * - $_POST['nonce'] (string): Security nonce.
     * - $_POST['process'] (string): Action to perform (e.g., 'addNavMenuItem').
     *
     * Returns:
     * - JSON response indicating success or failure.
     */
    public function navMenuChanges() {
        $action_available_values = [
            'addNavMenuItem',
            'removeNavMenuItem',
            'editNavMenuItem',
            'editNavMenuBulkItems',
            'updateNavMenuOrdering',
            'changeNavMenuContent'
        ];

        //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if ( !isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'twbb' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        $action = isset($_POST['process']) ? sanitize_text_field($_POST['process']) : '';
        if( !in_array($action, $action_available_values, true) || $action === 'constructor' ||
            !user_can(get_current_user_id(), 'administrator') || !method_exists($this, $action) ) {
            wp_send_json_error('Invalid action');
        }
        $this->$action($_POST);
    }

    /**
     * Updates the content of a navigation menu widget.
     *
     * Expects:
     * - $_POST['nonce'] (string): Security nonce.
     * - $_POST['postID'] (int): Post ID of the Elementor document.
     * - $_POST['elementID'] (string): ID of the Elementor widget element.
     *
     * Returns:
     * - JSON response with the rendered HTML content or an error.
     */
    public function changeNavMenuContent() {
        //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if ( !isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'twbb' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        $post_id = isset($_POST['postID']) ? absint($_POST['postID']) : '';
        $element_id = isset($_POST['elementID']) ? sanitize_text_field($_POST['elementID']) : '';
        $document = \Elementor\Plugin::$instance->documents->get( $post_id );

        if ( ! $document ) {
            return new \WP_Error(
                'document_not_exist',
                __( 'Document doesn\'t exist', 'tenweb-builder' ),
                [ 'status' => 404 ]
            );
        }

        $element_data = $document->get_elements_data();
        $widget = \Elementor\Utils::find_element_recursive( $element_data, $element_id );

        if ( empty( $widget ) ) {
            return new \WP_Error(
                'Element_not_exist',
                __( 'Posts widget doesn\'t exist', 'tenweb-builder' ),
                [ 'status' => 404 ]
            );
        }

        \Elementor\Plugin::$instance->documents->switch_to_document( $document );
        $html = $document->render_element($widget);
        wp_send_json_success($html);
    }

    /**
     * Adds a new item to the navigation menu.
     *
     * Expects:
     * - $_POST['menu_id'] (int): ID of the navigation menu.
     * - $_POST['item_id'] (int): ID of the item to add.
     * - $_POST['item_title'] (string): Title of the menu item.
     * - $_POST['item_object'] (string): Object type (e.g., 'post', 'taxonomy').
     * - $_POST['item_type'] (string): Type of the menu item.
     * - $_POST['item_position'] (int): Position of the menu item.
     * - $_POST['item_parent_id'] (int): Parent ID of the menu item.
     * - $_POST['item_url'] (string): URL of the menu item.
     * - $_POST['return_last_added_item'] (bool): Whether to return the last added item.
     *
     * Returns:
     * - JSON response with the added menu item or an error.
     */

    public function addNavMenuItem($post) {
        $menu_id = isset($post['menu_id']) ? (int) $post['menu_id'] : 0;
        $item_id = isset($post['item_id']) ? (int) $post['item_id'] : 0;
        $item_title = isset($post['item_title']) ? sanitize_text_field($post['item_title']) : '';
        $item_object = isset($post['item_object']) ? sanitize_text_field($post['item_object']) : '';
        $item_type = isset($post['item_type']) ? sanitize_text_field($post['item_type']) : '';
        $item_position = isset($post['item_position']) ? (int) $post['item_position'] : 0;
        $item_parent_id = isset($post['item_parent_id']) ? (int) $post['item_parent_id'] : 0;
        $item_url = isset($post['item_url']) ? sanitize_url($post['item_url']) : '';
        $return_last_added_item = isset($post['return_last_added_item']) ? sanitize_text_field($post['return_last_added_item']) : false;
        $args = [
            'menu-item-object-id'   => $item_id,
            'menu-item-object'      => $item_object,
            'menu-item-parent-id'   => $item_parent_id,
            'menu-item-position'    => $item_position,
            'menu-item-type'        => $item_type,
            'menu-item-title'       => wp_slash($item_title),
            'menu-item-url'         => $item_url,
            'menu-item-status'      => 'publish',
            'menu-item-target'      => 'blank',
        ];
        //add menu item
        $menu_item = wp_update_nav_menu_item($menu_id, 0, $args);

        if (is_wp_error($menu_item)) {
            wp_send_json_error($menu_item->get_error_message());
        }
        if( $return_last_added_item ) {
            $menu_item = wp_get_nav_menu_items($menu_id);
            //last item of $menu_item
            if( !is_array($menu_item) || empty($menu_item) ) {
                wp_send_json_error('Error getting menu item');
            }
            $last_item = end($menu_item);
            $item_object = \Tenweb_Builder\Modules\WebsiteNavigation\RenderingHelper::twbb_reformattingNavItem($last_item);
            //this returns the html to add to nav_menu sortable
            $menu_item = \Tenweb_Builder\Modules\WebsiteNavigation\RenderingHelper::twbb_renderNavigationItem($item_object, 'nav_menu', $last_item, 0);
        }
        wp_send_json_success($menu_item);
    }

    /**
     * Removes an item from the navigation menu.
     *
     * Expects:
     * - $_POST['menu_item_db_id'] (int): Database ID of the menu item to remove.
     *
     * Returns:
     * - JSON response indicating success or failure.
     */
    public function removeNavMenuItem($post) {
        $menu_item_db_id = isset($post['menu_item_db_id']) ? (int) $post['menu_item_db_id'] : 0;
        $menu_item = wp_delete_post($menu_item_db_id);
        if (is_wp_error($menu_item)) {
            wp_send_json_error('Error deleting menu item');
        }
        wp_send_json_success($menu_item);
    }

    /**
     * Edits an existing navigation menu item.
     *
     * Expects:
     * - $_POST['menu_id'] (int): ID of the navigation menu.
     * - $_POST['item_id'] (int): ID of the item to edit.
     * - $_POST['item_title'] (string): New title of the menu item.
     * - $_POST['item_object'] (string): Object type (e.g., 'post', 'taxonomy').
     * - $_POST['item_type'] (string): Type of the menu item.
     * - $_POST['item_position'] (int): New position of the menu item.
     * - $_POST['item_parent_id'] (int): New parent ID of the menu item.
     * - $_POST['item_url'] (string): New URL of the menu item.
     * - $_POST['menu_item_db_id'] (int): Database ID of the menu item.
     * - $_POST['status'] (string): Status of the menu item.
     *
     * Returns:
     * - JSON response with the updated menu item or an error.
     */
    public function editNavMenuItem($post) {
        $menu_id = isset($post['menu_id']) ? (int) $post['menu_id'] : 0;
        $item_id = isset($post['item_id']) ? (int) $post['item_id'] : 0;
        $item_title = isset($post['item_title']) ? sanitize_text_field($post['item_title']) : '';
        $item_object = isset($post['item_object']) ? sanitize_text_field($post['item_object']) : '';
        $item_type = isset($post['item_type']) ? sanitize_text_field($post['item_type']) : '';
        $item_position = isset($post['item_position']) ? (int) $post['item_position'] : 0;
        $item_parent_id = isset($post['item_parent_id']) ? (int) $post['item_parent_id'] : 0;
        $item_url = isset($post['item_url']) ? sanitize_url($post['item_url']) : '';
        $menu_item_db_id = isset($post['menu_item_db_id']) ? (int) $post['menu_item_db_id'] : 0;
        $status = isset($post['status']) ? sanitize_text_field($post['status']) : '';

        $args = [
            'menu-item-object-id'   => $item_id,
            'menu-item-object'      => $item_object,
            'menu-item-parent-id'   => $item_parent_id,
            'menu-item-position'    => $item_position,
            'menu-item-type'        => $item_type,
            'menu-item-title'       => wp_slash($item_title),
            'menu-item-url'         => $item_url,
            'menu-item-status'      => $status,
            'menu-item-target'      => 'blank',
        ];
        $menu_item = wp_update_nav_menu_item($menu_id, $menu_item_db_id, $args);
        if (is_wp_error($menu_item)) {
            wp_send_json_error($menu_item->get_error_message());
        }
        wp_send_json_success($menu_item);
    }

    /**
     * Edits multiple navigation menu items in bulk.
     *
     * Expects:
     * - $_POST['args'] (array): Array of menu items to update, including:
     *   - 'menu_id' (int): ID of the navigation menu.
     *   - 'items' (array): List of items with their properties to update.
     *
     * Returns:
     * - JSON response indicating success or failure.
     */
    public function editNavMenuBulkItems($post) {
        $args = isset($post['args']) ? $post['args'] : [];
        $menu_id = (int) $args['menu_id'];
        $items = $args['items'];
        foreach ( $items as $item ) {
            $menu_item_db_id = (int) $item['menu_item_db_id'];
            $item_args = [
                'menu-item-object-id'   => (int) $item['item_id'],
                'menu-item-object'      => sanitize_text_field($item['object']),
                'menu-item-parent-id'   => (int) $item['item_parent_id'],
                'menu-item-position'    => (int) $item['item_position'],
                'menu-item-type'        => sanitize_text_field($item['item_type']),
                'menu-item-title'       => wp_slash(sanitize_text_field($item['item_title'])),
                'menu-item-url'         => sanitize_url($item['item_url']),
                'menu-item-status'      => sanitize_text_field($item['status']),
                'menu-item-target'      => 'blank',
            ];
            wp_update_nav_menu_item($menu_id, $menu_item_db_id, $item_args);
        }
        wp_send_json_success();
    }

    /**
     * Updates the ordering of navigation menu items.
     *
     * Expects:
     * - $_POST['args'] (array): Array of menu item positions, including:
     *   - 'menu_item_positions' (array): List of items with 'db_id' and 'position'.
     *
     * Returns:
     * - JSON response indicating success or failure.
     */
    public function updateNavMenuOrdering($post) {
        $args = isset($post['args']) ? $post['args'] : [];
        $menu_item_positions = $args['menu_item_positions'];
        foreach ($menu_item_positions as $item) {
            $id = (int) $item['db_id'];
            $position = (int) $item['position'];
            wp_update_post([
                'ID' => $id,
                'menu_order' => $position
            ]);
        }
        wp_send_json_success();
    }

    /**
     * Updates the settings of a navigation menu item or a taxonomy term.
     *
     * Expects the following `$_POST` parameters:
     * - `nonce` (string): Security nonce for verification.
     * - `element_object_id` (int): ID of the post or term to update.
     * - `element_db_id` (int): ID of the navigation menu item to update.
     * - `type` (string): Type of the element (`post_type` or `taxonomy`).
     * - `object` (string): Object type (e.g., post type or taxonomy name).
     * - `title` (string): New title for the post or term.
     * - `nav_item_title` (string): New title for the navigation menu item.
     * - `slug` (string): New slug for the post or term.
     * - `status` (string): New status for the post (`true` for publish, `false` for draft).
     * - `home_page` (string): Whether to set the post as the home page (`true` or `false`).
     *
     * Performs the following actions:
     * - Updates the post or term title, slug, and status.
     * - Updates the navigation menu item's title.
     * - Optionally sets or unsets the post as the home page.
     *
     * Returns:
     * - JSON success response on success.
     * - JSON error response on failure.
     */
    function changeItemSettings() {
        //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if ( !isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'twbb' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        $element_object_id = isset($_POST['element_object_id']) ? (int) $_POST['element_object_id'] : 0;
        $element_db_id = isset($_POST['element_db_id']) ? (int) $_POST['element_db_id'] : 0;
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : 'post_type';
        $object = isset($_POST['object']) ? sanitize_text_field($_POST['object']) : '';
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $nav_label = isset($_POST['nav_item_title']) ? sanitize_text_field($_POST['nav_item_title']) : '';
        $url_slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';
        $url = sanitize_url($_POST['url'] ?? ''); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $home_page = isset($_POST['home_page']) ? sanitize_text_field($_POST['home_page']) : '';
        if( $type === 'custom' ) {
            $object_args = [
                'ID' => $element_object_id,
                'title' => $title,
            ];
            $post_object = wp_update_post($object_args);
            //update _menu_item_url in meta value in wp_postmeta
            update_post_meta($element_db_id, '_menu_item_url', $url);
            if( is_wp_error($post_object) ) {
                wp_send_json_error($post_object->get_error_message());
            }
            wp_send_json_success($post_object);
        }
        else {
            $object_args = [
                'ID' => $element_object_id,
            ];
            $nav_element_args = [
                'ID' => $element_db_id,
            ];
            if (!empty($title)) {
                $object_args['post_title'] = $title;
            }
            if (!empty($nav_label)) {
                $nav_element_args['post_title'] = $nav_label;
            }
            if (!empty($url_slug)) {
                $object_args['post_name'] = $url_slug;
            }
            if (!empty($status)) {
                if ($status === 'true') {
                    $object_args['post_status'] = 'publish';
                } else {
                    $object_args['post_status'] = 'draft';
                }
            }
            if ($type === 'post_type') {
                $post_object = wp_update_post($object_args);
                if (is_wp_error($post_object)) {
                    wp_send_json_error($post_object->get_error_message());
                }
                //get updated post url
                $url = get_permalink($element_object_id);
            } else if ($type === 'taxonomy') {
                if( empty($title) ) {
                    //get term name by id
                    $title = get_term($element_object_id, $object)->name;
                }
                $term_args = [
                    'term_id' => $element_object_id,
                    'name' => $title,
                ];
                if( !empty($url_slug) ) {
                    $term_args['slug'] = $url_slug;
                }
                $term_object = wp_update_term($element_object_id, $object, $term_args);
                if (is_wp_error($term_object)) {
                    wp_send_json_error($term_object->get_error_message());
                }
                //get update taxonomy url
                $url = get_term_link($element_object_id, $object);
            }

            $post_nav = wp_update_post($nav_element_args);
            if (is_wp_error($post_nav)) {
                wp_send_json_error($post_nav->get_error_message());
            }
            if (!empty($home_page)) {
                //set ad home page or remove from home page
                if ($home_page === 'true') {
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $element_object_id);
                } else if($home_page === 'unset'){
                    update_option('show_on_front', 'posts');
                    update_option('page_on_front', 0);
                }
            }

            wp_send_json_success(['url'=> $url]);
        }
    }

    /**
     * Returns the singleton instance of the class.
     *
     * Returns:
     * - WPMenuController: The singleton instance of the class.
     */
    public static function getInstance(){
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
