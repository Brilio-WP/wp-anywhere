<?php

namespace Tenweb_Builder\Modules\WebsiteNavigation;

class RenderingHelper
{
    public static function addMenuItemSecondaryTooltip($title, $object_type, $type, $items = [], $page = 1) {
        $level = 'twbb-wn-secondary-level';
        ob_start();
        if( $page === 1 ) {
        ?>
        <div class="twbb-wn-action-tooltip-title-container">
            <span class="twbb-wn-back-add-to-menu-button"></span>
            <div class="twbb-wn-action-tooltip-title"><?php esc_html_e($title,'tenweb-builder');?>
            </div>
        </div>
        <div class="twbb-wn-search-wrapper">
            <input type="text" name="twbb_wn_search" class="twbb-wn-search" placeholder="<?php esc_attr_e('Search', 'tenweb-builder');?>">
            <span class="twbb-wn-clear-search"></span>
        </div>
    <div class="twbb-wn-action-tooltip-items <?php esc_attr_e($level);?> twbb-wn-type-<?php esc_attr_e($object_type);?>">
        <div class="twbb-wn-search-noresult"><?php esc_html_e('No results found.','tenweb-builder');?></div>
        <?php
        }
        foreach ($items as $item) {
            $id = '';
            $title = '';
            if( $type === 'post' ) {
                $id = $item->ID;
                $title = $item->post_title;
                $nav_post_type = 'post_type';
                //get post url
                $url = get_permalink($id);
            } else if( $type === 'taxonomy' ) {
                $id = $item->term_id;
                $title = $item->name;
                $nav_post_type = 'taxonomy';
                //get term url
                $url = get_term_link($id);
            }?>
            <div class="twbb-wn-action-tooltip-item twbb-wn-flex-space-between"
            <?php self::dataAttrRenderer('type', $nav_post_type );?>
            <?php self::dataAttrRenderer('post_type', $object_type );?>
            <?php self::dataAttrRenderer('id', $id );?>
            <?php self::dataAttrRenderer('title', $title );?>
            <?php self::dataAttrRenderer('object', $object_type );?>
            <?php self::dataAttrRenderer('url', $url, true );?>>
            <span><?php esc_html_e($title);?></span><span class="twbb-wn-add-item-to-page"></span></div>
        <?php }
        if( $page === 1 ) {
        ?>
        </div>
        <?php
        }
        return ob_get_clean();
    }

    public static function twbb_renderNavigationItem($item, $wn_type, $nav_item = null, $depth = 0) {
        //check if this is the editing page
        $item_class = '';
        $depth_class = '';
        if ( (int) $item['id'] === get_the_ID()) {
            $item_class = 'twbb-wn-item-active';
        }
        if( ( $item['post_type'] === 'page' && $wn_type === 'nav_menu' ) ||
            ( $item['status'] === 'publish' && $wn_type === 'page' ) ) {
            $item_class .= ' twbb-good-for-action';
        }
        if( $wn_type === 'nav_menu' ) {
            $depth_class = 'menu-item-depth-' . $depth;
            $item_type = $nav_item->type;
        } else if ( $wn_type === 'page' ) {
            $depth_class = 'menu-item-depth-0';
            $item_type = 'post_type';
        }

        $home_page = '';
        if ((int)get_option('page_on_front') === $item['id']) {
            $home_page = 'twbb-wn-home-page';
        }
        $wn_type_title = $wn_type === 'nav_menu' ? $item['nav_item_title'] : $item['title'];
        $wn_type_menu_side_label = ucfirst( $item['nav_label'] );
        $wn_type_page_side_label = ucfirst( $item['status'] === 'publish' ? '' : 'draft' );
        ob_start(); ?>
        <div class="twbb-website-nav-sidebar-item twbb-wn-item menu-item <?php echo esc_attr($item_class) . ' ' . esc_attr($depth_class);?> "
        <?php if (isset($item['id'])) self::dataAttrRenderer('id', $item['id']); ?>
        <?php if (isset($item['title'])) self::dataAttrRenderer('title', $item['title']); ?>
        <?php if (isset($item['url'])) self::dataAttrRenderer('url', $item['url'], true); ?>
        <?php if (isset($item['slug'])) self::dataAttrRenderer('slug', $item['slug']); ?>
        <?php if (isset($item['status'])) self::dataAttrRenderer('status', $item['status']); ?>
        <?php if (isset($item['post_type'])) self::dataAttrRenderer('object', $item['post_type']); ?>
        <?php if (isset($item_type)) self::dataAttrRenderer('type', $item_type); ?>
        <?php if (isset($item['type_label'])) self::dataAttrRenderer('type_label', $item['type_label']); ?>
        <?php if (isset($item['nav_label'])) self::dataAttrRenderer('nav_label', $item['nav_label']); ?>
        <?php if (isset($item['nav_item_title'])) self::dataAttrRenderer('nav_item_title', $item['nav_item_title']); ?>
        <?php if (isset($item['template_link'])) self::dataAttrRenderer('template_link', $item['template_link'], true); ?>
        <?php if (isset($item['template_title'])) self::dataAttrRenderer('template_title', $item['template_title']); ?>
        <?php if (isset($item['content_edit_link'])) self::dataAttrRenderer('content_edit_link', $item['content_edit_link']); ?>>
        <div class="menu-item-handle">
            <div class="twbb-website-nav-sidebar-item__title <?php esc_attr_e($home_page);?>">
                <span class="twbb-wn-title"> <?php esc_html_e($wn_type_title);?></span>
                <span class="twbb-wn-status"><?php esc_html_e($wn_type_page_side_label);?></span>
                <span class="twbb-wn-item-info"><?php esc_html_e($wn_type_menu_side_label);?></span>
            </div>
        </div>
        <div class="twbb-website-nav-sidebar-item__actions">
            <?php
            if( !empty($item['content_edit_link']) || !empty($item['template_link'])) { ?>
            <div class="twbb-wn-action-edit twbb-wn-tooltip-parent">
                <div class="wn-action-tooltip">
                    <?php
                    if( !empty($item['template_link']) ) {?>
                        <a class="twbb-wn-tooltip-links twbb-wn-template_link" href="<?php echo esc_url($item['template_link']); ?>"
                           target="_blank"><?php esc_html_e('Edit ' . $item['template_title']  . ' template');?>
                        </a>
                    <?php }
                    if( !empty($item['content_edit_link']) ) { ?>
                        <a class="twbb-wn-tooltip-links twbb-wn-content_edit_link" href="<?php echo esc_url($item['content_edit_link']); ?>"
                           target="_blank"><?php echo $item['post_type'] === 'page' ? esc_html('Edit ' . $item['nav_label']) : esc_html('Edit ' . $item['nav_label'] . ' content'); ?>
                        </a>
                    <?php }
                    ?>
                </div>
            </div>
            <?php } ?>
            <span class="twbb-wn-action-settings twbb-tooltip-parent-container-item" data-tooltip-text="<?php esc_attr_e('Settings', 'tenweb-builder');?>"></span>
        </div><?php
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo self::twbb_renderNavigationItemSettings($nav_item); ?>
        </div>
        <?php return ob_get_clean();
    }

    public static function twbb_reformattingNavItem($nav_item) {
        $nav_label = $nav_item->object;
        $item_title = get_the_title((int) $nav_item->object_id);
        //get slug of page by id
        $item_slug = get_post_field('post_name', (int) $nav_item->object_id);
        $status = get_post_status((int) $nav_item->object_id);
        $template_link = '';
        $template_title = '';
        $content_edit_link = '';
        if( $nav_label === 'product' ) {
            $template_id = \Tenweb_Builder\Condition::get_instance()->get_product_template((int) $nav_item->object_id);
            if( !TENWEB_WHITE_LABEL ) {
                $domain_id = get_option(TENWEB_PREFIX . '_domain_id');
                $content_edit_link = TENWEB_DASHBOARD . '/websites/'. $domain_id . '/ecommerce/products/edit-product/' . $nav_item->object_id;
            } else {
                $content_edit_link = get_edit_post_link($nav_item->object_id);
            }
        } else if( $nav_label === 'post' ) {
            $template_id = \Tenweb_Builder\Condition::get_instance()->get_post_type_template((int) $nav_item->object_id,'singular', 'twbb_single');
            $content_edit_link = get_edit_post_link($nav_item->object_id);
        } else if( $nav_item->type === 'taxonomy') {
            //check if $nav_label contains 'product'
            if( strpos($nav_label, 'product') !== false ) {
                //template_type argument should be changed to twbb_archive_products after
                $template_id = \Tenweb_Builder\Condition::get_instance()->get_post_type_template((int) $nav_item->object_id,'archive', 'twbb_archive_products');
                if ( !$template_id ) {
                    $template_id = \Tenweb_Builder\Condition::get_instance()->get_post_type_template((int) $nav_item->object_id,'archive', 'twbb_archive');
                }
            } else {
                $template_id = \Tenweb_Builder\Condition::get_instance()->get_post_type_template((int) $nav_item->object_id,'archive', 'twbb_archive');
            }

        } else if( $nav_label === 'page' ) {
            // check if page is edited with Elementor
            if( \Elementor\Plugin::instance()->documents->get( (int) $nav_item->object_id )->is_built_with_elementor() ) {
                $content_edit_link = admin_url( 'post.php?post=' . $nav_item->object_id . '&action=elementor' );
            } else {
                $content_edit_link = get_edit_post_link($nav_item->object_id);
            }
        }
        if( !empty( $template_id ) ) {
            $template_link = admin_url( 'post.php?post=' . $template_id . '&action=elementor' );
            $template_title = get_the_title($template_id);
        }

        $id = (int) $nav_item->object_id;
        if( !in_array($nav_label, ['page', 'post', 'product', 'custom'], true) ) {
            $nav_label = $nav_item->type_label;
            //get term name by id
            $item_title = get_term((int) $nav_item->object_id)->name;
            $item_slug = get_term((int) $nav_item->object_id)->slug;
        }
        $item_object = [
            'id' => $id,
            'title' => $item_title,
            'slug' => $item_slug,
            'url' => $nav_item->url,
            'status' => $status,
            'post_type' => $nav_item->object,
            'type_label' => $nav_item->type_label,
            'nav_label' => $nav_label,
            'nav_item_title' => $nav_item->title,
            'template_link' => $template_link,
            'template_title' => $template_title,
            'content_edit_link' => $content_edit_link,
        ];
        return $item_object;
    }

    public static function twbb_renderNavigationItemSettings($item_object)
    {
        if( empty($item_object) ) {
            $item_object = new class {
                public $db_id = '';
                public $object_id = '';
                public $object = '';
                public $menu_item_parent = '';
                public $menu_order = '';
                public $type = '';
            };
        }
        $db_id = self::twbb_checkValue($item_object->db_id);
        ob_start();?>
        <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php esc_attr_e($db_id);?>">
            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php esc_attr_e($db_id);?>]"
            value="<?php esc_attr_e(self::twbb_checkValue($item_object->db_id));?>">
            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php esc_attr_e($db_id);?>]"
            value="<?php esc_attr_e(self::twbb_checkValue($item_object->object_id));?> ">
            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php esc_attr_e($db_id);?>]"
            value="<?php esc_attr_e(self::twbb_checkValue($item_object->object));?>">
            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php esc_attr_e($db_id);?>]"
            value="<?php esc_attr_e(self::twbb_checkValue($item_object->menu_item_parent));?>">
            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php esc_attr_e($db_id);?>]"
            value="<?php esc_attr_e(self::twbb_checkValue($item_object->menu_order));?>">
            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php esc_attr_e($db_id);?>]"
            value="<?php esc_attr_e(self::twbb_checkValue($item_object->type));?>">
        </div>
        <div class="menu-item-transport"></div>
        <?php return ob_get_clean();
    }

//function for checking whether the value is set
    public static function twbb_checkValue($value) {
        return $value ?? '';
    }

    public static function twbb_rederAddMenuItemTooltip($title, $type, $items = []) {
        $level = 'twbb-wn-secondary-level';
        if( $type === 'all_types' ) {
            $level = 'twbb-wn-main-level';
        }
        ob_start(); ?>
        <div class="twbb-wn-action-tooltip-title-container">
            <div class="twbb-wn-action-tooltip-title">
                <?php esc_html_e($title,'tenweb-builder');?>
            </div>
        </div>
        <div class="twbb-wn-action-tooltip-items <?php esc_attr_e($level);?> twbb-wn-type-<?php esc_attr_e($type);?>">
        <?php foreach ($items as $item) {
            $item_availability = '';
            if( !$item['available'] ) {
                $item_availability = 'twbb-wn-item-not-available';
            }
            ?>
            <div class="twbb-wn-action-tooltip-item <?php esc_attr_e($item_availability);?>" data-type="<?php esc_attr_e($item['type']);?>" data-post-type="<?php esc_attr_e($item['post_type']);?>">
                <?php esc_html_e($item['title']);?>
            </div>
        <?php } ?>
        </div>

        <?php return ob_get_clean();
    }

    public static function dataAttrRenderer($data, $value, $url = false) {
        //check if value is not empty
        if( !empty($value) ) {
            if( $url ) {
                $output = ' data-' . $data . '="' . esc_url($value) . '"';
            } else {
                $output = ' data-' . $data . '="' . esc_attr($value) . '"';
            }
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $output;
        }
        echo '';
    }
}
