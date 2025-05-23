<?php

namespace Tenweb_Builder\Widgets\Woocommerce\Products\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use Tenweb_Builder\Widget_Slider;
use WC_Product_Variable;

if ( !defined('ABSPATH') ) {
  exit; // Exit if accessed directly
}

abstract class Products_Base extends Widget_Base {

  /**
   * Get all image sizes.
   *
   * @return array
   */
  public function get_all_image_sizes() {
    global $_wp_additional_image_sizes;
    $default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];
    $image_sizes = [];
    foreach ( $default_image_sizes as $size ) {
      $image_sizes[$size] = [
        'width' => (int) get_option($size . '_size_w'),
        'height' => (int) get_option($size . '_size_h'),
        'crop' => (bool) get_option($size . '_crop'),
      ];
    }
    if ( $_wp_additional_image_sizes ) {
      $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
    }
    $sizes = [];
    if ( !empty($image_sizes) ) {
      foreach ( $image_sizes as $key => $size ) {
        $_key = $key . '%%' . $size['width'];
        $sizes[$_key] = ucwords(str_replace('_', ' ', $key)) . ' - (' . $size['width'] . 'px)';
      }
    }
    $sizes = array_merge($sizes, [ 'custom' => __('Custom', 'tenweb-builder') ]);

    return $sizes;
  }

  	/**
	 * Add To Cart Wrapper
	 *
	 * Add a div wrapper around the Add to Cart & View Cart buttons on the product cards inside the product grid.
	 * The wrapper is used to vertically align the Add to Cart Button and the View Cart link to the bottom of the card.
	 * This wrapper is added when the 'Automatically align buttons' toggle is selected.
	 * Using the 'woocommerce_loop_add_to_cart_link' hook.
	 *
	 * @since 3.7.0
	 *
	 * @param string $string
	 * @return string $string
	 */
	public function add_to_cart_wrapper( $string ) {
		return '<div class="woocommerce-loop-product__buttons">' . $string . '</div>';
	}

	//10web customization
	public function remove_add_to_cart( $string ) {
		return '';
	}
    public function quantity_add_to_cart( $string  ) {
        if( count( $this->getProductVariations() ) > 0 ) {
            /* This fake and hidden html structure need to keep variation product button styles appropriate with simple product */
            $string = '<div class="twbb-product-loop-buttons"><div class="twbb-product-quantity-container twbb-product-quantity-containerHidden">
                        <span class="twbb-minus-quantity twbb-product-quantity-change"></span></div>
                        <div class="twbb_add_to_cart_cont">' . $string . '</div></div>';
        } else {
            $string = '<div class="twbb-product-loop-buttons"><div class="twbb-product-quantity-container"><span class="twbb-minus-quantity twbb-product-quantity-change">-</span>' .
                '<input class="twbb-product-quantity-input" type="number" min="1" value="1">' .
                '<span class="twbb-plus-quantity twbb-product-quantity-change">+</span></div><div class="twbb_add_to_cart_cont">' . $string . '</div></div>';
        }
        return $string;
    }

    protected function register_controls() {
        $this->start_controls_section('section_layout_style', [
            'label' => esc_html__('Layout', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->add_control('products_class', [
            'type' => Controls_Manager::HIDDEN,
            'default' => 'wc-products',
            'prefix_class' => 'elementor-products-grid elementor-',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('column_gap', [
            'label' => esc_html__('Columns Gap', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', 'custom' ],
            'default' => [
                'size' => 20,
            ],
            'tablet_default' => [
                'size' => 20,
            ],
            'mobile_default' => [
                'size' => 20,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.swiper-slide.product' => 'margin-right: {{SIZE}}{{UNIT}}',
            ],
            'frontend_available' => true,
            'render_type' => 'template',
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => esc_html__( 'Rows Gap', 'tenweb-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 40,
                ],
                'tablet_default' => [
                    'size' => 40,
                ],
                'mobile_default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-wc-products  ul.products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_skin' => '',
                ],
            ]);

        $this->end_controls_section();

        $this->start_controls_section('image_style', [
            'label' => __('Image & Gallery', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                '_skin' => '',
                'product_images!' => '',
            ],
        ]);
        $this->add_control(
            'image_aspect_ratio',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Aspect Ratio', 'elementor-pro' ),
                'default' => '1:1',
                'options' => [
                    '1:1' => '1:1',
                    '3:2' => '3:2',
                    '3:4' => '3:4',
                    '4:3' => '4:3',
                    '9:16' => '9:16',
                    '16:9' => '16:9',
                    '21:9' => '21:9',
                ],
                'render_type' => 'template',
                'frontend_available' => true,
                'condition' => [
                    '_skin' => '',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image_resolution',
                'default' => 'woocommerce_thumbnail',
                'condition' => [
                    '_skin' => '',
                ],
            ]
        );
        $this->add_responsive_control('image_spacing', [
            'label' => __('Spacing below', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products .twbb-image-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->add_control('heading_image_style', [
            'label' => esc_html__('Hover Effect', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
                'image_gallery' => '',
            ],
        ]);
        $this->add_control(
            'image_hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'tenweb-builder' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => 'None',
                    'zoom-in' => 'Zoom In',
                    'zoom-out' => 'Zoom Out',
                    'move-left' => 'Move Left',
                    'move-right' => 'Move Right',
                    'move-up' => 'Move Up',
                    'move-down' => 'Move Down',
                ],
                'default' => '',
                'prefix_class' => 'elementor-animated-item--',
                'render_type' => 'template',
                'condition' => [
                    '_skin' => '',
                ],
            ]
        );
        $this->add_control(
            'content_animation_duration',
            [
                'label' => __('Animation Duration', 'tenweb-builder'),
                'type' => Controls_Manager::SLIDER,
                'render_type' => 'ui',
                'default' => [
                    'size' => 1000,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .twbb-image-container' => '--animation-duration: {{SIZE}}ms;',
                ],
                'condition' => [
                    '_skin' => '',
                ],
            ]
        );

        /* Arrows style section */
        $this->add_control('heading_image_gallery_arrows_style', [
            'label' => esc_html__('Arrows', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
                'image_gallery!' => '',
            ],
        ]);


        $this->add_responsive_control(
            'image_gallery_arrows_size',
            [
                'label' => __( 'Arrow icon size', 'tenweb-builder' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 50,
                ],
                'tablet_default' => [
                    'size' => 50,
                ],
                'mobile_default' => [
                    'size' => 50,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-gallery-slider .swiper-gallery-button-next:after, {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-prev:after' => 'height: calc({{SIZE}}{{UNIT}}/3); width: calc({{SIZE}}{{UNIT}}/3);font-size: calc({{SIZE}}{{UNIT}}/3);',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                    'body[data-elementor-device-mode="tablet"] {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                    'body[data-elementor-device-mode="mobile"] {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-prev' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                    'body[data-elementor-device-mode="tablet"] {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-prev' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                    'body[data-elementor-device-mode="mobile"] {{WRAPPER}} .product-gallery-slider .swiper-gallery-button-prev' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}' => ' --twbb-widget-slider-arrows-width: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    '_skin' => '',
                    'image_gallery!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gallery_arrows_color',
            [
                'label' => __( 'Arrows color', 'tenweb-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .product-gallery-slider .twbb-image_gallery-arrows-icon' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    '_skin' => '',
                    'image_gallery!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gallery_arrows_hover_color',
            [
                'label' => __( 'Arrows hover color', 'tenweb-builder' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .product-gallery-slider .twbb-image_gallery-arrows-icon:hover' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    '_skin' => '',
                    'image_gallery!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gallery_arrows_background_color',
            [
                'label' => __( 'Background color', 'tenweb-builder' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-gallery-slider .twbb-image_gallery-arrows-icon' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    '_skin' => '',
                    'image_gallery!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gallery_arrows_background_hover_color',
            [
                'label' => __( 'Background hover color', 'tenweb-builder' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-gallery-slider .twbb-image_gallery-arrows-icon:hover' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    '_skin' => '',
                    'image_gallery!' => '',
                ],
            ]
        );

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'image_gallery_arrows_border',
            'selector' => '{{WRAPPER}} .product-gallery-slider .twbb-image_gallery-arrows-icon',
            'fields_options' => [
                'border' => [
                    'responsive' => true, // Enable responsiveness
                ],
            ],
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
                'image_gallery!' => '',
            ],
        ]);

        $this->add_responsive_control('image_gallery_arrows_border_radius', [
            'label' => __('Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default' => [
                'top' => 25,
                'right' => 25,
                'bottom' => 25,
                'left' => 25,
                'unit' => 'px', // Default unit
            ],
            'tablet_default' => [
                'top' => 25,
                'right' => 25,
                'bottom' => 25,
                'left' => 25,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'top' => 25,
                'right' => 25,
                'bottom' => 25,
                'left' => 25,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .product-gallery-slider .twbb-image_gallery-arrows-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                '_skin' => '',
                'image_gallery!' => '',
            ],
        ]);

        /* End Arrows styles */


        $this->add_control('heading_image_border_style', [
            'label' => esc_html__('Borders', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
                'image_gallery!' => '',
            ],
        ]);
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'image_border',
            'selector' => '{{WRAPPER}}.elementor-wc-products .twbb-image-container',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('image_border_radius', [
            'label' => __('Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products .twbb-image-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_section();

        $this->register_variations_style();


        $this->start_controls_section('section_products_style', [
            'label' => esc_html__('Content', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->add_control('heading_title_style', [
            'label' => __('Title', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
        ]);
        $this->start_controls_tabs('title_color_style_tabs');
        $this->start_controls_tab('title_color_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_control('title_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
            'separator' => 'after',
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('title_color_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_control('title_color_hover', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .woocommerce-loop-product__title' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
            'separator' => 'after',
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_TEXT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title',
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('title_spacing', [
            'label' => __('Spacing below', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-category__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'product_title!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_control('heading_desc_style', [
            'label' => __('Description', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'product_description!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_control('desc_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_TEXT,
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb_woocommerce-loop-product__desc' => 'color: {{VALUE}}',
            ],
            'condition' => [
                'product_description!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'desc_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_TEXT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb_woocommerce-loop-product__desc',
            'condition' => [
                'product_description!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('desc_spacing', [
            'label' => __('Spacing below', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb_woocommerce-loop-product__desc' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
            ],
            'condition' => [
                'product_description!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_control('heading_rating_style', [
            'label' => __('Rating', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('star_color', [
            'label' => __('Star Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('empty_star_color', [
            'label' => __('Empty Star Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating::before' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('star_size', [
            'label' => __('Star Size', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', 'custom' ],
            'default' => [
                'unit' => 'em',
            ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 4,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('rating_spacing', [
            'label' => __('Spacing below', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section('section_price_style', [
            'label' => esc_html__('Price', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->add_control('heading_price_style', [
            'label' => __('Price', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->start_controls_tabs('price_color_style_tabs');
        $this->start_controls_tab('price_color_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('price_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price ins' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price ins .amount' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
            'separator' => 'after',
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('price_color_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('price_color_hover', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .price' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .price ins' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .price ins .amount' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
            'separator' => 'after',
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'price_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_TEXT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .price',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('price_padding', [
            'label' => __('Price Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price bdi:not(.price del bdi)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ]);

        $this->add_control('heading_old_price_style', [
            'label' => __('Regular Price', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->start_controls_tabs('old_price_color_style_tabs');
        $this->start_controls_tab('old_price_color_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('old_price_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price del' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price del .amount' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
            'separator' => 'after',
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('old_price_color_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('old_price_color_hover', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover del .price' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .price del .amount' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
            'separator' => 'after',
        ]);
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'old_price_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_TEXT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .price del .amount',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('regular_price_padding', [
            'label' => __('Regular Price Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price del bdi' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ]);

        $this->add_responsive_control('price_spacing', [
            'label' => __('Spacing below', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'separator' => 'before',
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .price' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_buttons_style', [
            'label' => esc_html__('Buttons & quantity', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'product_buttons!' => '',
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('button_spacing', [
            'label' => __('Spacing above', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product > .twbb-add-to-cart-container,
                {{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-product-loop-buttons' => 'margin-top: {{SIZE}}{{UNIT}} !important',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->add_responsive_control('button_bottom_spacing', [
            'label' => __('Spacing bottom', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product > .twbb-add-to-cart-container,
                {{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-product-loop-buttons' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'button_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .button',
            'condition' => [
                '_skin' => '',
            ],
        ]);

        $this->start_controls_tabs('tabs_button_style');
        $this->start_controls_tab('tab_button_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_text_color', [
            'label' => __('Text Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'color: {{VALUE}};',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_background_color', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_border_color', [
            'label' => __('Border Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                '_skin' => '',
                'button_border_border!' => 'none',
            ],
        ]);
        $this->end_controls_tab();

        $this->start_controls_tab('tab_button_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_hover_color', [
            'label' => __('Text Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .button' => 'color: {{VALUE}};',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_hover_background_color', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .button' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_hover_border_color', [
            'label' => __('Border Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .button' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                '_skin' => '',
                'button_border_border!' => 'none',
            ],
        ]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'button_border',
            'exclude' => [ 'color' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .button',
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_border_radius', [
            'label' => __('Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('button_text_padding', [
            'label' => __('Text Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);

    $this->add_control('heading_products_quantity_style', [
        'label' => esc_html__('Quantity', 'tenweb-builder'),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
        'condition' => [
            '_skin' => '',
            'product_quantity!' => '',
        ],
    ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'products_quantity_typography',
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container input.twbb-product-quantity-input,
                            {{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container span.twbb-minus-quantity,
                            {{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container span.twbb-plus-quantity',
            'condition' => [
                '_skin' => '',
                'product_quantity!' => '',
            ],
        ]);

        $this->start_controls_tabs('products_quantity_style_tabs');
        $this->start_controls_tab('quantity_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
                'product_quantity!' => '',
            ],
        ]);

        $this->add_control('products_quantity_text_color', [
            'label' => __('Text Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container .twbb-product-quantity-input' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container .twbb-minus-quantity' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container .twbb-plus-quantity' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
                'product_quantity!' => '',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'products_quantity_typography',
                'selector' => '{{WRAPPER}} .twbb-product-quantity-container, {{WRAPPER}} .twbb-product-quantity-container input.twbb-product-quantity-input',
                'condition' => [
                    '_skin' => '',
                    'product_quantity!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'products_quantity_border',
                'label' => __('Border', 'tenweb-builder'),
                'selector' => '{{WRAPPER}} .twbb-product-quantity-container',
                'condition' => [
                    'product_quantity!' => '',
                    '_skin' => '',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->start_controls_tab('products_quantity_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
                'product_quantity!' => '',
            ],
        ]);
        $this->add_control('products_quantity_text_color_hover', [
            'label' => __('Text Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .twbb-product-quantity-container .twbb-product-quantity-input' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .twbb-product-quantity-container .twbb-minus-quantity' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .twbb-product-quantity-container .twbb-plus-quantity' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
                'product_quantity!' => '',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'products_quantity_bg_color_hover',
                'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .twbb-product-quantity-container, 
                                {{WRAPPER}}.elementor-wc-products ul.products li.product:hover .twbb-product-quantity-container input.twbb-product-quantity-input',
                'condition' => [
                    '_skin' => '',
                    'product_quantity!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'products_quantity_border_hover',
                'label' => __('Border', 'tenweb-builder'),
                'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover .twbb-product-quantity-container',
                'condition' => [
                    'product_quantity!' => '',
                    '_skin' => '',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('quantity_border_radius', [
            'label' => __('Quantity Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products .twbb-product-quantity-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'product_quantity!' => '',
                '_skin' => '',
            ],
        ]);

        $this->add_responsive_control('quantity_padding', [
            'label' => __('Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-product-quantity-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                'product_quantity!' => '',
                '_skin' => '',
            ],
        ]);

        $this->add_control('heading_view_cart_style', [
            'label' => __('View Cart', 'tenweb-builder'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('view_cart_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products .added_to_cart' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'view_cart_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_ACCENT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products .added_to_cart',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control(
            'view_cart_spacing',
            [
                'label' => esc_html__( 'Spacing', 'tenweb-builder' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 3.5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-wc-products .added_to_cart' => 'margin-inline-start: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_skin' => '',
                ],
            ]);

        $this->end_controls_section();





        $this->start_controls_section('sale_flash_style', [
            'label' => __('Badge', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'show_onsale_flash' => 'yes',
                '_skin' => '',
            ],
        ]);

        $this->start_controls_tabs('onsale_color_style_tabs');
        $this->start_controls_tab('onsale_color_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
        ]);
        $this->add_control('onsale_text_color', [
            'label' => __('Text Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'color: {{VALUE}}',
            ],
        ]);
        $this->add_control('onsale_text_background_color', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'background-color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'background-color: {{VALUE}}',
            ],
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('onsale_color_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
        ]);
        $this->add_control('onsale_text_color_hover', [
            'label' => __('Text Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge:hover' => 'color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale:hover' => 'color: {{VALUE}}',
            ],
        ]);
        $this->add_control('onsale_text_background_color_hover', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge:hover' => 'background-color: {{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale:hover' => 'background-color: {{VALUE}}',
            ],
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'onsale_typography',
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge,{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale',
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'onsale_border',
                'label' => __('Border', 'tenweb-builder'),
                'separator' => 'before',
                'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge,{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale',
            ]
        );


        /* This control hide from css to keep his setting value actual for old users */
        $this->add_control('onsale_border_radius', [
            'label' => __('Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'border-radius: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->add_responsive_control('onsale_border_radius_responsive', [
            'label' => __('Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('onsale_padding', [
            'label' => __('Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
        ]);


        $this->add_control('onsale_width', [
            'label' => __('Width', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'min-width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'min-width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('onsale_height', [
            'label' => __('Height', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('onsale_horizontal_position', [
            'label' => __('Horizontal Position', 'tenweb-builder'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => FALSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'tenweb-builder'),
                    'icon' => 'eicon-h-align-left',
                ],
                'right' => [
                    'title' => __('Right', 'tenweb-builder'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => '{{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => '{{VALUE}}',
            ],
            'selectors_dictionary' => [
                'left' => 'right: auto; left: 0',
                'right' => 'left: auto; right: 0',
            ],
        ]);
        $this->add_responsive_control('onsale_vertical_position', [
            'label' => __('Vertical Position', 'tenweb-builder'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => FALSE,
            'options' => [
                'left' => [
                    'title' => __('Top', 'tenweb-builder'),
                    'icon' => 'eicon-v-align-top',
                ],
                'right' => [
                    'title' => __('Bottom', 'tenweb-builder'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => '{{VALUE}}',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => '{{VALUE}}',
            ],
            'selectors_dictionary' => [
                'top' => 'bottom: auto; top: 0',
                'right' => 'top: auto; bottom: 0',
            ],
        ]);
        $this->add_control('onsale_distance', [
            'label' => __('Distance', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em', 'rem', 'custom' ],
            'range' => [
                'px' => [
                    'min' => -20,
                    'max' => 20,
                ],
                'em' => [
                    'min' => -2,
                    'max' => 2,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.twbb_products_badge' => 'margin: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}}.elementor-wc-products ul.products li.product span.onsale' => 'margin: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section('section_design_box', [
            'label' => __('Box', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('box_border_width', [
            'label' => __('Border Width', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('box_border_radius', [
            'label' => __('Border Radius', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-radius: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('box_padding', [
            'label' => __('Box Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_responsive_control('content_padding', [
            'label' => __('Content Padding', 'tenweb-builder'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .woocommerce-loop-product__title,
		{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating,
		{{WRAPPER}}.elementor-wc-products ul.products li.product .price,
		{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-add-to-cart-container,
		{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-product-loop-buttons,
		{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb_woocommerce-loop-product__desc,
		{{WRAPPER}}.elementor-wc-products ul.products li.product .star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->start_controls_tabs('box_style_tabs');
        $this->start_controls_tab('classic_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('box_bg_color', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('box_border_color', [
            'label' => __('Border Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product' => 'border-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('classic_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow_hover',
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('box_bg_color_hover', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('box_border_color_hover', [
            'label' => __('Border Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product:hover' => 'border-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section('section_pagination_style', [
            'label' => __('Pagination', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'paginate' => 'yes',
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_spacing', [
            'label' => __('Spacing', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('show_pagination_border', [
            'label' => __('Border', 'tenweb-builder'),
            'type' => Controls_Manager::SWITCHER,
            'label_off' => __('Hide', 'tenweb-builder'),
            'label_on' => __('Show', 'tenweb-builder'),
            'default' => 'yes',
            'return_value' => 'yes',
            'prefix_class' => 'elementor-show-pagination-border-',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_border_color', [
            'label' => __('Border Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul' => 'border-color: {{VALUE}}',
                '{{WRAPPER}} nav.woocommerce-pagination ul li' => 'border-right-color: {{VALUE}}; border-left-color: {{VALUE}}',
            ],
            'condition' => [
                'show_pagination_border' => 'yes',
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_padding', [
            'label' => __('Padding', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
            'range' => [
                'em' => [
                    'min' => 0,
                    'max' => 2,
                    'step' => 0.1,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li a, {{WRAPPER}} nav.woocommerce-pagination ul li span' => 'padding: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'pagination_typography',
            'selector' => '{{WRAPPER}} nav.woocommerce-pagination',
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->start_controls_tabs('pagination_style_tabs');
        $this->start_controls_tab('pagination_style_normal', [
            'label' => __('Normal', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_link_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_link_bg_color', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li a' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('pagination_style_hover', [
            'label' => __('Hover', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_link_color_hover', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_link_bg_color_hover', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li a:hover' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab('pagination_style_active', [
            'label' => __('Active', 'tenweb-builder'),
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_link_color_active', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->add_control('pagination_link_bg_color_active', [
            'label' => __('Background Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}}',
            ],
            'condition' => [
                '_skin' => '',
            ],
        ]);
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->inject_slider();
    }

    protected function inject_slider() {
        Widget_Slider::init_slider_option($this, [
            'at' => 'after',
            'of' => '_skin',
        ], '');

        Widget_Slider::add_slider_controls($this, [
            'type' => 'section',
            'at' => 'end',
            'of' => 'section_content',
        ]);

        Widget_Slider::add_slider_style_controls($this, [
            'type' => 'section',
            'at' => 'end',
            'of' => 'section_design_box',
        ]);

        $this->update_control('paginate', ['condition' => [
            'slider_view!' => 'yes',
        ]]);
    }


    public function register_variations_style() {
        $this->start_controls_section('section_variations_style', [
            'label' => __('Variations', 'tenweb-builder'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'variation_images' => 'yes',
                'product_images!' => '',
                '_skin' => '',
            ],
        ]);

        $this->add_responsive_control('variations_gap', [
            'label' => __('Gap Between', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'default' => [
                'size' => 10,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-woocommerce-products-variations' => 'gap: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->add_responsive_control('variation_image_width', [
            'label' => __('Image Width', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'default' => [
                'size' => 40,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-woocommerce-products-variations .variation-image' => 'width: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->add_responsive_control('variation_image_height', [
            'label' => __('Image Height', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'default' => [
                'size' => 40,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-woocommerce-products-variations .variation-image' => 'height: {{SIZE}}{{UNIT}}',
            ],
        ]);

        $this->add_control('variations_number_color', [
            'label' => __('Color', 'tenweb-builder'),
            'type' => Controls_Manager::COLOR,
            'global' => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-woocommerce-products-variations .twbb-additional-variations' => 'color: {{VALUE}}',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'variations_typography',
            'global' => [
                'default' => Global_Typography::TYPOGRAPHY_TEXT,
            ],
            'selector' => '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-woocommerce-products-variations .twbb-additional-variations',
        ]);

        $this->add_responsive_control('variations_spacing_above', [
            'label' => __('Spacing above', 'tenweb-builder'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'selectors' => [
                '{{WRAPPER}}.elementor-wc-products ul.products li.product .twbb-woocommerce-products-variations' => 'margin-top: {{SIZE}}{{UNIT}}',
            ],
            'default' => [
                'size' => 10,
                'unit' => 'px'
            ],
            'tablet_default' => [
                'size' => 10,
                'unit' => 'px'
            ],
            'mobile_default' => [
                'size' => 10,
                'unit' => 'px'
            ],
        ]);


        $this->end_controls_section();
    }

    protected function getProductVariations() {
        global $product;
        $product_variable = new WC_Product_Variable( $product->get_id() );
        $variations_all = $product_variable->get_available_variations();
        return $variations_all;
    }
	//End 10web customization
}
