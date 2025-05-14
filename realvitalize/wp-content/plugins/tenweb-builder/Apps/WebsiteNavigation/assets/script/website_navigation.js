class TWBB_WebsiteNavigation {
    constructor() {
        this.init();
    }

    init() {
        this.events();
    }

    events() {
        this.openWebsiteNavigation();
        this.addBlankPage();
        this.removeWNError();
        this.openTooltip();
        this.closeTooltipOnClick();
        this.openSubTooltip();
        this.backToMainTooltip();
        this.addMenuItemFromOptions();
        this.addCustomLinkMenuItem();
        this.searchMenuItem();
        this.clearSearch();
        this.tooltipContent();
        this.removeMenuItem();
        this.closeOnOtherSidebarsOpen();
        this.openSettingsInnerPage();
    }

    openWebsiteNavigation() {
        let self = this;
        jQuery(document).on('click', '.twbb_editor_nav_menu', function () {
            analyticsDataPush('Website structure', 'Manage Navigation button click', 'Top bar');
            self.websiteNavigationOpenFunctions(jQuery(this));
        });
    }

    websiteNavigationOpenFunctions(that) {
        twbb_triggerWebsiteNavigationButton(that);

        this.changePageStatusOnSave();
        jQuery('.twbb-website-nav-sidebar-content').scroll(() => {
            twbb_closeTooltip();
        });
    }

    addBlankPage() {
        jQuery(document).on('click', '.twbb-wn-add-blank-page', () => {
            const template = jQuery('#elementor-preview-iframe').contents().find('#twbb_new_blank_page-template').html();
            jQuery('body').append(template);
        });
    }

    removeWNError() {
        jQuery(document).on('transitionend', '.twbb-navmenu-sidebar-error', function () {
            jQuery(this).remove();
            if (!jQuery('.twbb-navmenu-sidebar-error').length) {
                jQuery('.twbb-website-nav-sidebar-content').removeClass('twbb-navmenu-sidebar-with-error');
            }
        });
    }

    openTooltip() {
        jQuery(document).on('click', '.twbb-wn-tooltip-parent', function (e) {
            e.stopPropagation();
            if (!jQuery(e.target).hasClass('twbb-wn-tooltip-parent')) return;

            twbb_closeTooltip();
            let top = jQuery(this).offset().top + jQuery(this).outerHeight() + 10,
                left = jQuery(this).offset().left;

            if (jQuery(this).hasClass('twbb-empty-nav-tooltip-container')) {
                top = jQuery(this).offset().top;
                left = jQuery(this).offset().left + jQuery(this).outerWidth() + 10;
            }

            if (jQuery(this).hasClass('wn-add-menu-item')) {
                const template = jQuery('#twbb-wn-add-menu-item-action-tooltip').html();
                if (!jQuery(this).find('.wn-add-menu-item-action-tooltip').length) {
                    jQuery(this).append(template);
                }
                jQuery('.twbb-wn-secondary-container').hide();
                jQuery('.wn-action-tooltip-container.twbb-wn-main-container').show();
            }

            const thisTooltip = jQuery(this).find('.wn-action-tooltip');
            jQuery(this).addClass('twbb-opacity-1');
            thisTooltip.css({ top: `${top}px`, left: `${left}px`, display: 'block' });
            jQuery(this).addClass('twbb_active');
        });
    }

    closeTooltipOnClick() {
        jQuery(document).on('click', (e) => {
            if (!jQuery(e.target).closest('.twbb-wn-tooltip-parent').length) {
                twbb_closeTooltip();
            }
        });
    }

    openSubTooltip() {
        jQuery(document).on('click', '.twbb-wn-main-container .twbb-wn-action-tooltip-item', function (e) {
            e.stopPropagation();
            twbb_renderSubActionTooltip(jQuery(this));
        });
    }

    backToMainTooltip() {
        jQuery(document).on('click', '.twbb-wn-back-add-to-menu-button', function (e) {
            e.stopPropagation();
            jQuery(this).closest('.twbb-wn-secondary-container').hide();
            jQuery('.wn-action-tooltip-container.twbb-wn-main-container').show();
        });
        jQuery(document).on('click', '.twbb-wn-action-tooltip-title', function (e) {
            e.stopPropagation();
            jQuery(this).closest('.twbb-wn-secondary-container').hide();
            jQuery('.wn-action-tooltip-container.twbb-wn-main-container').show();
        });
    }

    addMenuItemFromOptions() {
        jQuery(document).on('click', '.twbb-wn-secondary-container .twbb-wn-action-tooltip-item', function (e) {
            e.stopPropagation();
            if (jQuery('.twbb-website-nav-sidebar-container').hasClass('disable-ajax-in-progress')) return;

            const menuId = jQuery('#nav_menu_items').data('nav_id');
            twbb_navMenuActions.addMenuItem(menuId, jQuery(this), twbb_addNavSuccessCallback);
        });
    }

    searchMenuItem() {
        jQuery(document).on('input', '.twbb-wn-search', function () {
            twbb_searchInit(jQuery(this));
        });
    }

    clearSearch() {
        jQuery(document).on('click', '.twbb-wn-clear-search', function () {
            const searchWrapper = jQuery(this).closest('.twbb-wn-search-wrapper');
            searchWrapper.find('.twbb-wn-search').val('');
            const tooltipContainer = jQuery(this).closest('.wn-action-tooltip-container');
            tooltipContainer.find('.twbb-wn-action-tooltip-item').show();
            tooltipContainer.find('.twbb-wn-search-noresult').hide();
        });
    }

    tooltipContent() {
        jQuery(document)
            .on('mouseenter', '.twbb-tooltip-parent-container-item', function (e) {
                if (!jQuery(e.target).hasClass('twbb-tooltip-parent-container-item')) {
                    return;
                }
                const text = jQuery(this).attr('data-tooltip-text'),
                    top = jQuery(this).offset().top + jQuery(this).outerHeight() + 10,
                    left = jQuery(this).offset().left;
                jQuery('.twbb-tooltip-parent-container .twbb-tooltip').text(text);
                jQuery('.twbb-tooltip-parent-container').css({ top: `${top}px`, left: `${left}px`, display: 'block' });
            })
            .on('mouseleave', '.twbb-tooltip-parent-container-item', () => {
                jQuery('.twbb-tooltip-parent-container').hide();
            });
    }

    removeMenuItem() {
        const self = this;
        jQuery(document).on('click', '.twbb-wn-action-remove', function () {
            const item = jQuery(this).closest('.twbb-website-nav-sidebar-item');
            self.removeMenuItemFromList(item);
        });
    }

    removeMenuItemFromList(item, trash = false) {
        if (jQuery('.twbb-website-nav-sidebar-container').hasClass('disable-ajax-in-progress')) return;

        const navMenu = jQuery('#nav_menu_items'),
            children = item.childMenuItems();

        twbb_navMenuActions.removeMenuItem(item, twbb_removeNavMenuItemCallback, trash);

        if (children.length) {
            children.shiftDepthClass(-1);
            twbb_navMenuActions.bulkEditMenu(children, navMenu.attr('data-nav_id'));
        }
    }

    addCustomLinkMenuItem() {
        jQuery(document).on('click', '.twbb-wn-add-custom-menu-item-button', function () {
            if (jQuery(this).hasClass('disabled')) return;

            const parent = jQuery(this).closest('.twbb-wn-secondary-container'),
                urlInput = parent.find('#wn-custom-link-nav-url').val(),
                menuId = jQuery('#nav_menu_items').data('nav_id');

            if (urlInput !== '') {
                twbb_navMenuActions.addMenuItem(menuId, jQuery(this), twbb_addNavSuccessCallback);
            }
            //empty inputs
            parent.find('#wn-custom-link-nav-label').val('');
            parent.find('#wn-custom-link-nav-url').val('');
        });
    }

    closeOnOtherSidebarsOpen() {
        window.$e.commands.on('run:before', function (component, command, args) {
            if ( 'panel/global/open' === command ) {
                twbb_animateWebNavSidebar(0);
            }
        });
        jQuery(document).on('click', '.twbb-theme-customize-close, .twbb-sg-header-button-container, .twbb-customize-button, header button[value="document-settings"]', () => {
            twbb_animateWebNavSidebar(0);
        });
    }

    openSettingsInnerPage() {
        window.twbb_websiteNavigationInnerSettings = new TWBB_WebsiteNavigationInnerSettings();
        jQuery(document).on('click', '.twbb-wn-action-settings' , function() {
            let triggered_form_nav_menu = false;
            if( jQuery(this).parents('.twbb-website-nav-sidebar-nav-menus-items').length > 0 ) {
                triggered_form_nav_menu = true;
            }
            twbb_renderInnerSettings(jQuery(this), triggered_form_nav_menu);
        });
    }

    changePageStatusOnSave() {
        const document_id = elementor.config.document.id;
        const document_status = elementor.config.document.status.value;
        const nav_item = jQuery(`.twbb-wn-item[data-id=${document_id}]`);
        const status = document_status === 'publish';
        this.itemStatusChange(status, nav_item);
        elementor.saver.on('save', (args) => {
            if (args.status === 'publish' ) {
                this.itemStatusChange(true, nav_item);
            }
        });
    }

    itemStatusChange(status, nav_item) {
        let old_status = nav_item.attr('data-status');
        if( (old_status === 'publish' && status) || (old_status === 'draft' && !status) ) {
            return;
        }
        if( status ) {
            nav_item.attr('data-status', 'publish').addClass('twbb-good-for-action');
            let id = nav_item.attr('data-id');
            jQuery(`.twbb-wn-item[data-id=${id}] .twbb-wn-status`).text('');
            let auto_added_menus = twbb_website_nav.auto_added_menus;
            const menu_id = parseInt(jQuery('#nav_menu_items').attr('data-nav_id'));
            if( auto_added_menus && auto_added_menus.includes(menu_id) ) {
                //check if nav_item is not in nav menu
                if( !jQuery(`.twbb-website-nav-sidebar-nav-menus-items .twbb-wn-item[data-id=${id}]`).length ) {
                    jQuery('.twbb-website-nav-sidebar-nav-menus-items').append(nav_item);
                    nav_item.attr('data-nav_item_title', nav_item.attr('data-title'));
                    jQuery("#nav_menu_items, #pages_items").sortable("refresh");
                }
            }
        } else {
            nav_item.attr('data-status', 'draft').removeClass('twbb-good-for-action');
            nav_item.find('.twbb-wn-status').text('Draft');
        }
    }
    itemTitleChange(title, nav_item) {
        nav_item.attr('data-title', title);
        const id = nav_item.attr('data-id');
        jQuery(`.twbb-website-nav-sidebar-pages-items .twbb-wn-item[data-id=${id}] .twbb-wn-title`).text(title);
    }
    itemNavTitleChange(title, nav_item) {
        nav_item.attr('data-nav_item_title', title);
        const id = nav_item.attr('data-id');
        jQuery(`.twbb-website-nav-sidebar-nav-menus-items .twbb-wn-item[data-id=${id}] .twbb-wn-title`).text(title);
    }
    itemUrlSlugChange(slug, url, nav_item) {
        nav_item.attr('data-slug', slug).attr('data-url', url);
    }
    itemCustomUrlChange(url, nav_item) {
        nav_item.attr('data-url', url);
    }
    itemHomePageChange( value, nav_item) {
        jQuery('.twbb-website-nav-sidebar-item__title').removeClass('twbb-wn-home-page');
        if( value === true ) {
            nav_item.find('.twbb-website-nav-sidebar-item__title').addClass('twbb-wn-home-page');
        }
    }
}

jQuery(document).ready(function() {
    window.twbb_websiteNavigation = new TWBB_WebsiteNavigation();
})

function twbb_renderInnerSettings(element, triggered_form_nav_menu = false) {
    let html_content = jQuery('#twbb-wn-inner-setting-page').html();
    const main_element = element.parents('.twbb-wn-item');
    const webNavSettings = window.twbb_websiteNavigationInnerSettings;
    jQuery('.twbb-website-nav-sidebar-container').append(html_content);
    const element_object = main_element.attr('data-object');
    const object_mapping = {
        'page': 'Page',
        'post': 'Post',
        'custom': 'Custom link',
        'category': 'Post category',
        'tag': 'Post tag',
        'product_cat': 'Product category',
        'product_tag': 'Product collection',
        'product_brand': 'Product brand',
    }
    let title_text = object_mapping[element_object] !== undefined ? object_mapping[element_object] : twbb_capitalizeWords(element_object);
    jQuery('.twbb-wn-inner-pages-header-title').text(title_text + ' settings');
    let the_page_content = webNavSettings.renderInnerSettingsPage(main_element);
    jQuery('.twbb-wn-inner-pages-content').append(the_page_content);
    jQuery('.twbb-wn-inner-pages-settings-save').attr('data-element-id', main_element.attr('data-id') )
        .attr('data-element-db-id', main_element.find('.menu-item-data-db-id').val() )
        .attr('data-triggered-from-nav-menu', triggered_form_nav_menu )
        .attr('data-object',  main_element.attr('data-object') );
}

function twbb_triggerWebsiteNavigationButton(element) {
    if (element.hasClass('disabled')) {
        return;
    }
    //close the invisible backdrop from elementor
    jQuery('.MuiBackdrop-invisible').trigger('click');
    element.addClass('selected');
    const header_add_element_button = jQuery('.MuiToolbar-root .MuiBox-root .MuiGrid-root:first-child .MuiStack-root:eq(1) .MuiBox-root:first-child button');
    header_add_element_button.removeClass('Mui-selected');
    //open website navigation
    if ( !jQuery('.twbb-website-nav-sidebar-container').length ) {
        let website_navigation_sidebar = jQuery('#twbb-navmenu-sidebar-template').html();
        jQuery('#elementor-editor-wrapper-v2').append(website_navigation_sidebar);
        twbb_navMenuSortable();
    }
    twbb_animateWebNavSidebar(1);
    //add template for adding pages just for saving time
    let template = jQuery("#twbb-wn-add-menu-item-action-tooltip").html();
    if( !jQuery('.wn-add-menu-item-action-tooltip').length ) {
        jQuery('.twbb-website-nav-sidebar-navigation-header .twbb-wn-add-item.wn-add-menu-item').append(template);
    }
}

function twbb_animateWebNavSidebar(open) {
    if (open) {
        jQuery('.twbb-website-nav-sidebar-container').removeClass('hide').addClass('show');

        setTimeout(function() {
            let windowWidth = jQuery(window).width();
            let iframeWidth = windowWidth - 380;
            let elementor_panel = jQuery("#elementor-panel").width();
            jQuery("#elementor-preview").css({
                "width": `${iframeWidth}px`,
                "margin-left": 380 - `${parseInt(elementor_panel)}px`,
            });
            jQuery("body").addClass('twbb-website-navigation-sidebar-opened');
            if( typeof twbb_options.smart_scale_option !== "undefined" && twbb_options.smart_scale_option === 'active' ) {
                twbbIframeScale(1, 380);
            } else {
                twbbIframeScale(0);
                jQuery('#elementor-preview').css('margin-left','100px');
            }
            jQuery("#elementor-editor-wrapper").addClass('twbb-animate-sidebar-open');
        }, 100);
    } else {
        twbb_closeWebsiteNavigation();
        setTimeout(function() {
            jQuery("#elementor-editor-wrapper").removeClass('twbb-animate-sidebar-open');
        },500);

        jQuery("#elementor-preview").removeAttr("style");
        /* do not scale when option is deactive */
        if( typeof twbb_options.smart_scale_option !== "undefined" && twbb_options.smart_scale_option === 'active' ) {
            twbbIframeScale(1);
        } else {
            twbbIframeScale(0);
        }
        jQuery("body").removeClass('twbb-website-navigation-sidebar-opened');

    }
}

function twbb_closeWebsiteNavigation() {
    jQuery('.MuiButtonBase-root[aria-label="Add Element"]').addClass('Mui-selected');
    jQuery('.twbb-website-nav-sidebar-container').removeClass('show').addClass('hide');
}

function twbb_webNavSidebarErrorClose(error) {
    jQuery(`.${error}`).addClass('remove-animation');
}

/* keeping pagination last loaded page number for every post type */
let twbb_page = [];
/* keeping post types which are fully loaded and has no pagination */
let twbb_finished = [];
let twbb_loading = false;
let twbb_lastScrollTop = 0;

/**
 * Function fire ajax request and get items for post type
 *
 * @param element object
 * @param page integer page number which should be requested
 * @paran scroll bool for checking if function called during the scroll
*/
function twbb_renderSubActionTooltip(element, page = 1, scroll = false) {
    if (twbb_loading) {
        return;
    }
    let post_type = element.data('post-type'),
        type = element.data('type'),
        action_element = element.closest('.wn-add-menu-item-action-tooltip').find(`.wn-action-tooltip-container[data-post-type="${post_type}"]`),
        html_title = element.text();
    if( typeof twbb_finished[post_type] === 'undefined' ) {
        twbb_finished[post_type] = false;
    }

    twbb_attachScroll(element.closest('.wn-add-menu-item-action-tooltip'), element); //  ATTACH SCROLL AFTER FIRST LOAD

    if( post_type !== 'custom' && (!action_element.length || (scroll && !twbb_finished[post_type])) ) {
        twbb_loading = true;
        if( action_element.length > 0 ) {
            action_element.find('.twbb-wn-action-tooltip-items').append('<div class="twbb-wn-item-loading"></div>');
        }
        jQuery.ajax({
            url: twbb_website_nav.ajaxurl,
            type: 'POST',
            data: {
                action: 'wn_get_available_menu_items',
                post_type: post_type,
                type: type,
                html_title: html_title,
                nav_menu_id: jQuery('#nav_menu_items').data('nav_id'),
                nonce: twbb_website_nav.nonce,
                page: page,
            },
        })
        .done( (data) => {
            if (data.success) {
                let container = element.closest('.wn-add-menu-item-action-tooltip');
                let template = data.data.content;
                if ( page === 1 ) {
                    container.append(template);
                } else {
                    if( template === '' ) {
                        twbb_finished[post_type] = true;
                    } else {
                        container.find(".twbb-wn-action-tooltip-items:visible").append(template);
                        if( data.data.items.length < 10 ) {
                            twbb_finished[post_type] = true;
                        }
                    }
                }
            }
        })
        .always(() => {
            jQuery('.twbb-wn-item-loading').remove();
            twbb_loading = false;
        });
    }
    jQuery('.twbb-wn-main-container').css('display', 'none');
    if( action_element.length > 0 ) {
        action_element.css('display', 'block');
    } else {
        jQuery('.wn-action-tooltip.wn-add-menu-item-action-tooltip').prepend(
            '<div class="twbb-wn-item-loading"></div><div class="twbb-wn-item-loading"></div><div class="twbb-wn-item-loading"></div>'
        );
    }
}

function twbb_attachScroll(container, element) {
    container.off('scroll.twbb').on('scroll.twbb', function() {
        let post_type = element.data('post-type');
        if (post_type === 'custom' || twbb_loading || twbb_finished[post_type]) return;

        const scrollTop = container.scrollTop();
        const scrollHeight = container.prop('scrollHeight');
        const containerHeight = container.outerHeight();

        // Check that user is scrolling down
        if (scrollTop > twbb_lastScrollTop) {
            // Check if near the bottom
            if (scrollTop + containerHeight >= scrollHeight - 50) {
                if(typeof twbb_page[post_type] !== 'undefined') {
                    twbb_page[post_type]++;
                } else {
                    twbb_page[post_type] = 2;
                }
                twbb_renderSubActionTooltip(element, twbb_page[post_type], true);
            }
        }
        twbb_lastScrollTop = scrollTop; // update last scroll position
    });
}

/*
* Functionality for different case after Add menu item to nav menu
 */
function twbb_addNavSuccessCallback(data, ui, remove= true) {
    let nav_menu = jQuery("#nav_menu_items");
    ui.find('.twbb-wn-add-item-to-page').css('background-image', 'none');
    ui.find('.twbb-wn-add-item-to-page').append('<i class="fas fa-check"></i>');
    ui.attr('data-nav_item_title', ui.attr('data-title'));
    nav_menu.append(data);
    nav_menu.sortable('refresh');
    //if this item data-object is page remove the item from pages sortable too
    if( ui.data('object') === 'page' ) {
        jQuery(`#pages_items .twbb-wn-item[data-id="${ui.data('id')}"]`).remove();
        jQuery('#pages_items').sortable('refresh');
    } else {
        //check if count only itself
        if( ui.closest('.twbb-wn-action-tooltip-items').children('.twbb-wn-action-tooltip-item').length === 1 ) {
            jQuery(`.twbb-wn-main-container .twbb-wn-action-tooltip-item[data-post-type="${ui.data('post_type')}"]`).addClass('twbb-wn-item-not-available');
        }
    }
    //add the item to nav menu sortable
    if( remove ) {
        //remove item after 1 sec
        setTimeout(function () {
            jQuery('.twbb-wn-action-tooltip-items').find(`.twbb-wn-action-tooltip-item[data-id=${ui.attr('data-id')}]`).remove();
        }, 500);
    }
    jQuery('.twbb-wn-add-menu-item-blue-button').css('display','none');
    jQuery('.twbb-website-nav-sidebar-navigation-header .twbb-wn-add-item.wn-add-menu-item.twbb-wn-tooltip-parent').removeClass('twbb-wn-not-visible');
    twbb_updateMenuItemPositions(nav_menu);
    twbb_navMenuActions.updateOrdering();
    analyticsDataPush('Website structure', 'Navigation menu edit', 'Left menu');
}

function twbb_removeNavMenuItemCallback(ui) {
    let nav_menu = jQuery("#nav_menu_items"), pages = jQuery('#pages_items'), data_object = ui.attr('data-object'),
    specific_type_collections = jQuery(`.twbb-wn-type-${data_object}`);
    ui.removeAttr('data-nav_item_title');
    ui.find('.twbb-wn-title').text(ui.attr('data-title'));
    if( data_object === 'page' ) {
        ui.updateDepthClass(0);
        pages.prepend(ui);
        pages.sortable('refresh');
    } else {
        specific_type_collections = jQuery(`.twbb-wn-action-tooltip-item[data-post-type="${ui.attr('data-object')}"]`);
        let specific_type_collections_secondary_containers = jQuery(`.wn-action-tooltip-container.twbb-wn-secondary-container[data-post-type="${data_object}"]`);
        let tooltip_ui = `<div class="twbb-wn-action-tooltip-item twbb-wn-flex-space-between"
            data-type="${ui.attr('data-type')}" data-post_type="${data_object}"
            data-id="${ui.attr('data-id')}" data-title="${ui.attr('data-title')}"
            data-object="${data_object}" data-url="${ui.attr('data-url')}">
                <span>${ui.attr('data-title')}</span><span class="twbb-wn-add-item-to-page"></span></div>`;
        if( specific_type_collections.length  && specific_type_collections_secondary_containers.length ) {
            specific_type_collections.removeClass('twbb-wn-item-not-available');
            //for each specific_type_collections_secondary_containers we can have two from blue button and from + sign
            specific_type_collections_secondary_containers.each(function() {
                if( !jQuery(this).find('.twbb-wn-action-tooltip-items').find(`.twbb-wn-action-tooltip-item[data-id=${ui.attr('data-id')}]`).length) {
                    jQuery(this).find('.twbb-wn-action-tooltip-items').append(tooltip_ui);
                }
            });

        }
        //after further implementation  ui will go to the specific type collection
        ui.remove();
    }
    //add removed item back to proper place
    nav_menu.sortable('refresh');

    //update menu item positions
    twbb_updateMenuItemPositions(nav_menu);

    if( !jQuery('.twbb-website-nav-sidebar-nav-menus-items > .twbb-wn-item').length ) {
        if( !jQuery('.twbb-wn-add-menu-item-blue-button').length ) {
            let template = jQuery('#twbb-wn-add-menu-item-button').html();
            jQuery('.twbb-website-nav-sidebar-nav-menus-items').append(template);
        } else {
            jQuery('.twbb-wn-add-menu-item-blue-button').css('display','block');
        }

        jQuery('.twbb-website-nav-sidebar-navigation-header .twbb-wn-add-item.wn-add-menu-item.twbb-wn-tooltip-parent').addClass('twbb-wn-not-visible');
    }

    analyticsDataPush('Website structure', 'Navigation menu edit', 'Left menu');
}

function twbb_updateMenuItemPositions(nav_menu) {
    let i = 0;
    nav_menu.children().each(function(){
        var item = jQuery(this),
            input = item.find( '.menu-item-data-position' );
        input.val(i);
        i++;
    });
}

function twbb_closeTooltip() {
    let item_actions = jQuery('.twbb-website-nav-sidebar-item__actions');
    if( item_actions.length ) {
        item_actions.removeClass('twbb-wn-visible-tooltip');
    }
    jQuery('.wn-action-tooltip').css('display', 'none');
    jQuery('.twbb-wn-add-item').removeClass('twbb_active');
    jQuery('.twbb-wn-tooltip-parent').removeClass('twbb-opacity-1');
}

function twbb_customLinkInputFunction(that) {
    let parent = jQuery(that).closest('.twbb-wn-secondary-container'),
        label_input = parent.find('#wn-custom-link-nav-label').val() ? parent.find('#wn-custom-link-nav-label').val() : 'Menu Item',
        nav_url = parent.find('#wn-custom-link-nav-url'),
        url_input = nav_url.val() ? nav_url.val() : '',
        item_button = parent.find('.twbb-wn-add-custom-menu-item-button');
    if( url_input !== '' ) {
        item_button.removeClass('disabled');
    } else {
        item_button.addClass('disabled');
    }
    item_button.attr('data-title', label_input).attr('data-url', url_input);
}

function twbb_searchInit(element) {
    let searchText = element.val().toLowerCase();
    if( searchText !== '' ) {
        element.closest(".twbb-wn-search-wrapper").find(".twbb-wn-clear-search").show();
    } else {
        element.closest(".twbb-wn-search-wrapper").find(".twbb-wn-clear-search").hide();
    }
    let searchResult = 0;
    element.closest(".wn-action-tooltip-container").find('.twbb-wn-action-tooltip-item').each(function() {
        let text = jQuery(this).find('span').first().text().toLowerCase();

        if (text.includes(searchText)) {
            jQuery(this).show();
            searchResult = 1
        } else {
            jQuery(this).hide();
        }
    });
    if( searchResult ) {
        element.closest(".wn-action-tooltip-container").find(".twbb-wn-search-noresult").hide();
    } else {
        element.closest(".wn-action-tooltip-container").find(".twbb-wn-search-noresult").show();
    }
}

function twbb_capitalizeWords(string) {
    return string.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}
