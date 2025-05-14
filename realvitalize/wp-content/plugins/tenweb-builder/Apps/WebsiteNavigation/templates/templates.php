<?php
$pages_info = \Tenweb_Builder\Modules\WebsiteNavigation\GetWPData::filteredPagesList();
$nav_menu_info = \Tenweb_Builder\Modules\WebsiteNavigation\GetWPData::getNavMenuItems();

?>
<!--Navigation Template sidebar template-->
<script type="text/template" id="twbb-navmenu-sidebar-template">
    <?php
    $nav_menu_items = $nav_menu_info['nav_menu_items'];
    $nav_menu_id = $nav_menu_info['nav_menu_id'];
    $page_where_is_menu = $nav_menu_info['page_where_is_menu'];
    $nav_widget_id = $nav_menu_info['nav_widget_id'];
    if( !empty($nav_menu_id) ) {
        $sortable_id = 'nav_menu_items';
    } else {
        $sortable_id = '';
    } ?>
    <div class="twbb-website-nav-sidebar-container hide"
         data-page_where_is_menu="<?php esc_attr_e($page_where_is_menu);?>"
         data-nav_widget_id="<?php esc_attr_e($nav_widget_id);?>">
        <span class="twbb-tooltip-parent-container">
            <span class="twbb-tooltip"></span>
         </span>
        <div class="twbb-website-nav-sidebar-header">
            <span class="twbb-website-nav-sidebar-title"><?php esc_html_e( 'Website structure', 'tenweb-builder' ); ?></span>
            <span class="twbb-website-nav-sidebar-desc"><?php esc_html_e( 'All navigation & page changes save automatically.', 'tenweb-builder' ); ?></span>
            <span class="twbb-website-nav-sidebar-header-close twbb-tooltip-parent-container-item"
                  onclick="twbb_animateWebNavSidebar(0)" data-tooltip-text="<?php esc_attr_e('Close', 'tenweb-builder');?>">
            </span>
        </div>
        <div class="twbb-website-nav-sidebar-content">
            <div class="twbb-website-nav-sidebar-navigation-container">
                <div class="twbb-website-nav-sidebar-navigation-header twbb-wn-type-header">
                    <div class="twbb-website-nav-sidebar-navigation-title">
                        <?php esc_html_e( 'Navigation menu', 'tenweb-builder' ); ?>
                        <span class="twbb-saved-label">
                            <i class="fas fa-check"></i>
                            <?php esc_html_e('Saved','tenweb-builder');?>
                        </span>
                    </div>
                    <div class="twbb-wn-add-item wn-add-menu-item twbb-wn-tooltip-parent  twbb-tooltip-parent-container-item <?php empty($nav_menu_items) ? esc_attr_e('twbb-wn-not-visible') : esc_attr_e('');?>"
                         data-tooltip-text="<?php esc_attr_e('Add new item', 'tenweb-builder');?>"></div>
                </div>

                    <?php
                    if( $nav_menu_id && !empty($nav_menu_items) ) {
                        $data_nav_id = 'data-nav_id="' . esc_attr($nav_menu_id) . '"';
                        $class = 'twbb-website-nav-sidebar-nav-menus-items twbb-website-nav-sidebar-items twbb_connectedSortable';
                        $args = [
                            'items_wrap'      => '<div id="'. $sortable_id .'" class="%2$s"' . $data_nav_id . '>%3$s</div>',
                            'container'       => '',
                            'container_id'    => '',
                            'container_class' => '',
                            'menu'         	  => $nav_menu_id,
                            'menu_class'      => $class,
                            'depth'           => 2,
                            'echo'            => true,
                            'fallback_cb'     => 'wp_page_menu',
                            'walker'          => (class_exists('\Tenweb_Builder\Modules\WebsiteNavigation\MenuWalker') ? new \Tenweb_Builder\Modules\WebsiteNavigation\MenuWalker() : '' )
                        ];

                        // WP 6.1 submenu issue
                        if(version_compare(get_bloginfo('version'), '6.1', '>=')){
                            unset($args['depth']);
                        }

                        wp_nav_menu($args);
                    }
                    else if( $nav_menu_id ){  ?>
                    <div class="twbb-website-nav-sidebar-nav-menus-items twbb-website-nav-sidebar-items twbb_connectedSortable"
                             id="<?php esc_attr_e($sortable_id); ?>" data-nav_id="<?php esc_attr_e($nav_menu_id); ?>">
                        <div class="twbb-wn-button twbb-wn-add-menu-item twbb-wn-bordered twbb-wn-add-menu-item-blue-button">
                            <?php esc_html_e('Add Menu Item', 'tenweb-builder'); ?>
                            <div class="wn-add-menu-item twbb-wn-tooltip-parent twbb-empty-nav-tooltip-container"></div>
                        </div>
                    </div>
                    <?php } else {
                        //the case where no Menu is existing in page or in all website ?>
                        <div class="twbb-website-nav-sidebar-nav-menus-items twbb-website-nav-sidebar-items">
                            <a class="twbb-wn-button twbb-wn-add-menu-item twbb-wn-bordered"
                               href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" target="_blank">
                                <?php esc_html_e('Create Menu', 'tenweb-builder'); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>

            </div>
            <div class="twbb-website-nav-sidebar-pages-container">
                <div class="twbb-website-nav-sidebar-pages-header twbb-wn-type-header">
                    <div class="twbb-website-nav-sidebar-pages-title">
                        <?php esc_html_e( 'Pages', 'tenweb-builder' );
                        // phpcs:disable
                        //check if there is no pages
//                        if (!empty($pages_info)) {
//                            ?>
<!--                            <span class="twbb-website-nav-sidebar-pages-trash">-->
<!--                                <span class="twbb-static-tooltip-parent-container">-->
<!--                                    <span class="twbb-static-tooltip">-->
<!--                                        --><?php //esc_html_e('Manage trash', 'tenweb-builder'); ?>
<!--                                    </span>-->
<!--                                </span>-->
<!--                            </span>-->
<!--                            --><?php
//                        }
                        //phpcs:enable
                        ?>
                    </div>
                    <?php if( !empty($pages_info) ) { ?>
                    <div class="twbb-wn-add-item wn-add-page-item twbb-wn-tooltip-parent twbb-tooltip-parent-container-item"
                         data-tooltip-text="<?php esc_attr_e('Add new page', 'tenweb-builder');?>">
                        <div class="wn-action-tooltip">
                            <div class="wn-action-tooltip-container">
                                <div class="twbb-wn-add-blank-page">
                                    <?php esc_html_e('Add a Blank Page', 'tenweb-builder'); ?>
                                    <p class="twbb-wn-button-description"><?php esc_html_e('Start with a blank page', 'tenweb-builder');?></p>
                                </div>
                                <?php if( !TENWEB_WHITE_LABEL ) { ?>
                                <a class="twbb-wn-generate-page"
                                   href="<?php echo esc_url( TENWEB_DASHBOARD . '/websites/' . get_option('tenweb_domain_id') . '/generate-page/'); ?>"
                                   target="_blank"> <?php esc_html_e('Generate a New Page with AI', 'tenweb-builder'); ?>
                                    <p class="twbb-wn-button-description"><?php esc_html_e('Describe your page, and AI will design it', 'tenweb-builder');?></p>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php
                if( !empty($pages_info) ) {
                    $sortable_id = 'pages_items';
                } else {
                    $sortable_id = '';
                }
                ?>
                <div class="twbb-website-nav-sidebar-pages-items twbb-website-nav-sidebar-items twbb_connectedSortable"
                     id="<?php esc_attr_e($sortable_id); ?>">
                    <?php
                    if (!empty($pages_info)) {
                        foreach ($pages_info as $page_info) {
                            $page_info['nav_label'] = 'Page';
                            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                           echo  \Tenweb_Builder\Modules\WebsiteNavigation\RenderingHelper::twbb_renderNavigationItem($page_info, 'page');
                        }
                    }
                    else { ?>
                        <div class="twbb-wn-button twbb-wn-add-blank-page twbb-wn-bordered">
                            <?php esc_html_e('Add a Blank Page', 'tenweb-builder'); ?>
                        </div>
                        <?php if( !TENWEB_WHITE_LABEL ) { ?>
                        <a class="twbb-wn-button twbb-wn-bordered twbb-wn-generate-page"
                           href="<?php echo esc_url( TENWEB_DASHBOARD . '/websites/' . get_option('tenweb_domain_id') . '/generate-page/'); ?>"
                           target="_blank"> <?php esc_html_e('Generate a New Page with AI', 'tenweb-builder'); ?>
                        </a>
                        <?php } ?>
                    <?php }
                    ?>
                </div>
            </div>
            <div class="twbb-website-nav-sidebar-other-container"></div>
        </div>
    </div>
</script>

<!--Navigation Template action tooltip add nav menu item-->
<script type="text/template" id="twbb-wn-add-menu-item-action-tooltip">
    <div class="wn-action-tooltip wn-add-menu-item-action-tooltip">
        <div class="wn-action-tooltip-container twbb-wn-main-container">
            <?php
            $needed_types = \Tenweb_Builder\Modules\WebsiteNavigation\GetWPData::getNeededTypes($nav_menu_info['nav_menu_items']);
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo \Tenweb_Builder\Modules\WebsiteNavigation\RenderingHelper::twbb_rederAddMenuItemTooltip('Add menu items', 'all_types', $needed_types);
            ?>
        </div>
        <div class="wn-action-tooltip-container twbb-wn-secondary-container" data-post-type="custom">
            <div class="twbb-wn-action-tooltip-title-container">
                <span class="twbb-wn-back-add-to-menu-button"></span>
                <div class="twbb-wn-action-tooltip-title"><?php esc_html_e('Custom links', 'tenweb-builder');?></div>
            </div>
            <div class="twbb-wn-add-menu-item-input-container">
                <div class="twbb-wn-add-menu-item-input">
                    <label for="wn-custom-link-nav-url"><?php esc_html_e('URL*', 'tenweb-builder'); ?></label>
                    <input type="text" id="wn-custom-link-nav-url" name="wn-custom-link-nav-url"
                           placeholder="https://example.com"  oninput="twbb_customLinkInputFunction(this)" />
                </div>
                <div class="twbb-wn-add-menu-item-input">
                    <label for="wn-custom-link-nav-label"><?php esc_html_e('Navigation label', 'tenweb-builder'); ?></label>
                    <input type="text" id="wn-custom-link-nav-label" name="wn-custom-link-nav-label"
                           placeholder="Ex: contact us"  oninput="twbb_customLinkInputFunction(this)"/>
                </div>
            </div>
            <div class="twbb-wn-add-custom-menu-item-button disabled" data-type="custom" data-object="custom">
                <span><?php esc_html_e('Add Custom Item', 'tenweb-builder'); ?></span>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="twbb-wn-add-menu-item-button">
    <div class="twbb-wn-button twbb-wn-add-menu-item twbb-wn-bordered twbb-wn-add-menu-item-blue-button">
        <?php esc_html_e('Add Menu Item', 'tenweb-builder'); ?>
        <div class="wn-add-menu-item twbb-wn-tooltip-parent twbb-empty-nav-tooltip-container"></div>
    </div>
</script>

<script type="text/template" id="twbb-wn-inner-setting-page">
    <div class="twbb-website-nav-sidebar-container twbb-wn-inner-settings-page">
        <div class="twbb-wn-inner-pages-header-container">
            <span class="twbb-wn-inner-pages-settings-save">
                <span class="twbb-wn-settings-button-text"><?php esc_html_e('Save', 'tenweb-builder')?></span>
                <span class="twbb-wn-settings-button-spinner"></span>
            </span>
            <div class="twbb-wn-inner-pages-header">
                <span class="twbb-wn-back-to-main-sidebar"></span>
                <div class="twbb-wn-inner-pages-header-title"><?php esc_html_e('Settings', 'tenweb-builder')?></div>
            </div>
        </div>
        <div class="twbb-wn-inner-pages-content">
        </div>
    </div>
</script>
