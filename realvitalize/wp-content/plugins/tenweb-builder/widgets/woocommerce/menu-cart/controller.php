<?php
namespace Tenweb_Builder\Widgets\Woocommerce\Products;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Tenweb_Builder\Classes\Woocommerce\Woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Menu_Cart extends Widget_Base {

	public function get_name() {
		return 'twbb_woocommerce-menu-cart';
	}

	public function get_title() {
		return esc_html__( 'Menu Cart', 'tenweb-builder' );
	}

	public function get_icon() {
		return 'twbb-menu_cart twbb-widget-icon';
	}

	public function get_categories() {
		return [ Woocommerce::WOOCOMMERCE_GROUP ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'menu', 'cart' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => esc_html__( 'Menu Icon', 'tenweb-builder' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cart-light' => esc_html__( 'Cart', 'tenweb-builder' ) . ' ' . esc_html__( 'Light', 'tenweb-builder' ),
					'cart-medium' => esc_html__( 'Cart', 'tenweb-builder' ) . ' ' . esc_html__( 'Medium', 'tenweb-builder' ),
					'cart-solid' => esc_html__( 'Cart', 'tenweb-builder' ) . ' ' . esc_html__( 'Solid', 'tenweb-builder' ),
					'basket-light' => esc_html__( 'Basket', 'tenweb-builder' ) . ' ' . esc_html__( 'Light', 'tenweb-builder' ),
					'basket-medium' => esc_html__( 'Basket', 'tenweb-builder' ) . ' ' .esc_html__( 'Medium', 'tenweb-builder' ),
					'basket-solid' => esc_html__( 'Basket', 'tenweb-builder' ) . ' ' . esc_html__( 'Solid', 'tenweb-builder' ),
					'bag-light' => esc_html__( 'Bag', 'tenweb-builder' ) . ' ' . esc_html__( 'Light', 'tenweb-builder' ),
					'bag-medium' => esc_html__( 'Bag', 'tenweb-builder' ) . ' ' . esc_html__( 'Medium', 'tenweb-builder' ),
					'bag-solid' => esc_html__( 'Bag', 'tenweb-builder' ) . ' ' . esc_html__( 'Solid', 'tenweb-builder' ),
					'custom' => esc_html__( 'Custom', 'tenweb-builder' ),
				],
				'default' => 'cart-medium',
				'prefix_class' => 'toggle-icon--', // Prefix class not used anymore, but kept for BC reasons.
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'menu_icon_svg',
			[
				'label' => esc_html__( 'Custom Icon', 'tenweb-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'default' => [
					'value' => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				],
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'None',
						],
					],
				],
				'recommended' => [
					'fa-solid' => [
						'shopping-bag',
						'shopping-basket',
						'shopping-cart',
						'cart-arrow-down',
						'cart-plus',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'icon' => 'custom',
				],
			]
		);

		$this->add_control(
			'items_indicator',
			[
				'label' => esc_html__( 'Items Indicator', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'tenweb-builder' ),
					'bubble' => esc_html__( 'Bubble', 'tenweb-builder' ),
					'plain' => esc_html__( 'Plain', 'tenweb-builder' ),
				],
				'prefix_class' => 'twbb_menu-cart--items-indicator-',
				'default' => 'bubble',
			]
		);

		$this->add_control(
			'hide_empty_indicator',
			[
				'label' => esc_html__( 'Hide Empty', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'tenweb-builder' ),
				'label_off' => esc_html__( 'No', 'tenweb-builder' ),
				'return_value' => 'hide',
				'prefix_class' => 'twbb_menu-cart--empty-indicator-',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'show_subtotal',
			[
				'label' => esc_html__( 'Subtotal', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tenweb-builder' ),
				'label_off' => esc_html__( 'Hide', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'twbb_menu-cart--show-subtotal-',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--main-alignment: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart',
			[
				'label' => esc_html__( 'Cart', 'tenweb-builder' ),
			]
		);

		$this->add_control(
			'cart_type',
			[
				'label' => esc_html__( 'Cart Type', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'side-cart' => esc_html__( 'Side Cart', 'tenweb-builder' ),
					'mini-cart' => esc_html__( 'Mini Cart', 'tenweb-builder' ),
				],
				'default' => 'side-cart',
				'prefix_class' => 'twbb_menu-cart--cart-type-',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'open_cart',
			[
				'label' => esc_html__( 'Open Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'click' => esc_html__( 'On Click', 'tenweb-builder' ),
					'mouseover' => esc_html__( 'On Hover', 'tenweb-builder' ),
				],
				'default' => 'click',
				'frontend_available' => true,
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'side_cart_alignment',
			[
				'label' => esc_html__( 'Cart Position', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'cart_type' => 'side-cart',
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'start' => '--side-cart-alignment-transform: translateX(-100%); --side-cart-alignment-right: auto; --side-cart-alignment-left: 0;',
					'end' => '--side-cart-alignment-transform: translateX(100%); --side-cart-alignment-left: auto; --side-cart-alignment-right: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'mini_cart_alignment',
			[
				'label' => esc_html__( 'Cart Position', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'cart_type' => 'mini-cart',
				],
				'selectors' => [
					'{{WRAPPER}}.twbb_menu-cart--cart-type-mini-cart .twbb_menu-cart__container' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'start' => 'left: 0; right: auto; transform: none;',
					'center' => 'left: 50%; right: auto; transform: translateX(-50%);',
					'end' => 'right: 0; left: auto; transform: none;',
				],
			]
		);

		$this->add_responsive_control(
			'mini_cart_spacing',
			[
				'label' => esc_html__( 'Distance', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => -300,
						'max' => 300,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'condition' => [
					'cart_type' => 'mini-cart',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--mini-cart-spacing: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_close_cart_button',
			[
				'label' => esc_html__( 'Close Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_cart_button_show',
			[
				'label' => esc_html__( 'Close Icon', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tenweb-builder' ),
				'label_off' => esc_html__( 'Hide', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__close-button, {{WRAPPER}} .twbb_menu-cart__close-button-custom' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'' => 'display: none;',
				],
			]
		);

		$this->add_control(
			'close_cart_icon_svg',
			[
				'label' => esc_html__( 'Custom Icon', 'tenweb-builder' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon' => 'fas fa-times',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'times-circle',
					],
					'fa-solid' => [
						'times',
						'times-circle',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'close_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'close_cart_button_alignment',
			[
				'label' => esc_html__( 'Icon Position', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-left',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'close_cart_button_show!' => '',
				],
				'selectors_dictionary' => [
					'start' => 'margin-right: auto',
					'end' => 'margin-left: auto',
				],
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__close-button, {{WRAPPER}} .twbb_menu-cart__close-button-custom' => '{{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_remove_item_button',
			[
				'label' => esc_html__( 'Remove Item', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_remove_icon',
			[
				'label' => esc_html__( 'Remove Item Icon', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tenweb-builder' ),
				'label_off' => esc_html__( 'Hide', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'twbb_menu-cart--show-remove-button-',
			]
		);

		$this->add_control(
			'remove_item_button_position',
			[
				'label' => esc_html__( 'Icon Position', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => '',
				'prefix_class' => 'remove-item-position--',
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->add_control(
			'heading_price_quantity',
			[
				'label' => esc_html__( 'Price and Quantity', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'price_quantity_position',
			[
				'label' => esc_html__( 'Position', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => '--price-quantity-position--grid-template-rows: auto 75%; --price-quantity-position--align-self: start;',
					'bottom' => '',
				],
			]
		);

		$this->add_control(
			'show_divider',
			[
				'label' => esc_html__( 'Cart Dividers', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'label_on' => esc_html__( 'Show', 'tenweb-builder' ),
				'label_off' => esc_html__( 'Hide', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '--divider-style: {{VALUE}}; --subtotal-divider-style: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'solid',
				],
			]
		);

		$this->add_control(
			'heading_buttons',
			[
				'label' => esc_html__( 'Buttons', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_button_show',
			[
				'label' => esc_html__( 'View Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tenweb-builder' ),
				'label_off' => esc_html__( 'Hide', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'' => '--view-cart-button-display: none; --cart-footer-layout: 1fr;',
				],
			]
		);

		$this->add_control(
			'view_cart_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'condition' => [
					'view_cart_button_show!' => '',
					'checkout_button_show' => '',
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'start' => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: left; --cart-footer-buttons-alignment-button-width: auto;',
					'center' => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: center; --cart-footer-buttons-alignment-button-width: auto;',
					'end' => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: right; --cart-footer-buttons-alignment-button-width: auto;',
					'justify' => '--cart-footer-layout: 1fr;',
				],
			]
		);

		$this->add_control(
			'checkout_button_show',
			[
				'label' => esc_html__( 'Checkout', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'tenweb-builder' ),
				'label_off' => esc_html__( 'Hide', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'' => '--checkout-button-display: none; --cart-footer-layout: 1fr;',
				],
			]
		);

		$this->add_control(
			'checkout_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'condition' => [
					'checkout_button_show!' => '',
					'view_cart_button_show' => '',
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'start' => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: left; --cart-footer-buttons-alignment-button-width: auto;',
					'center' => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: center; --cart-footer-buttons-alignment-button-width: auto;',
					'end' => '--cart-footer-buttons-alignment-display: block; --cart-footer-buttons-alignment-text-align: right; --cart-footer-buttons-alignment-button-width: auto;',
					'justify' => '--cart-footer-layout: 1fr;',
				],
			]
		);

		$this->add_control(
			'checkout_button_display',
			[
				'label' => esc_html__( 'Alignment', 'tenweb-builder' ),
				'type' => Controls_Manager::HIDDEN,
				'condition' => [
					'checkout_button_show' => '',
					'view_cart_button_show' => '',
				],
				'default' => '--cart-footer-buttons-alignment-display: none;',
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'buttons_position',
			[
				'label' => esc_html__( 'Vertical Position', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'tenweb-builder' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => '',
				'condition' => [
					'cart_type' => 'side-cart',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'view_cart_button_show',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'checkout_button_show',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'bottom' => '--cart-buttons-position-margin: auto;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'tenweb-builder' ),
			]
		);

		$this->add_control(
			'heading_additional_options',
			[
				'label' => esc_html__( 'Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'automatically_open_cart',
			[
				'label' => esc_html__( 'Automatically Open Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Open the cart every time an item is added.', 'tenweb-builder' ),
				'label_on' => esc_html__( 'Yes', 'tenweb-builder' ),
				'label_off' => esc_html__( 'No', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'automatically_update_cart',
			[
				'label' => esc_html__( 'Automatically Update Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'tenweb-builder' ),
				'label_off' => esc_html__( 'No', 'tenweb-builder' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => esc_html__( 'Updates to the cart (e.g., a removed item) via Ajax. The cart will update without refreshing the whole page.', 'tenweb-builder' ),
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'yes' => '--elementor-remove-from-cart-button: none; --remove-from-cart-button: block;',
					''    => '--elementor-remove-from-cart-button: block; --remove-from-cart-button: none;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => esc_html__( 'Menu Icon', 'tenweb-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', [ 'label' => esc_html__( 'Normal', 'tenweb-builder' ) ] );

		$this->add_control(
			'toggle_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-text-color: {{VALUE}};',
				],
				'condition' => [
					'show_subtotal!' => '',
				],
			]
		);

		$this->add_control(
			'toggle_button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-icon-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'toggle_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .twbb_menu-cart__toggle .elementor-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', [ 'label' => __( 'Hover', 'tenweb-builder' ) ] );

		$this->add_control(
			'toggle_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-hover-text-color: {{VALUE}};',
				],
				'condition' => [
					'show_subtotal!' => '',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-icon-hover-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-hover-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-hover-border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'toggle_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .twbb_menu-cart__toggle .elementor-button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 20,
					],
					'em' => [
						'max' => 2,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-border-radius: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'toggle_button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .twbb_menu-cart__toggle .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'toggle_icon_size',
			[
				'label' => esc_html__( 'Size', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .twbb_menu-cart__toggle .elementor-button-text' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .twbb_menu-cart__toggle .elementor-button-text' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_subtotal!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_padding',
			[
				'label' => esc_html__( 'Padding', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-icon-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'items_indicator_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Items Indicator', 'tenweb-builder' ),
				'separator' => 'before',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);
		$this->add_control(
			'items_indicator_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--items-indicator-text-color: {{VALUE}};',
				],
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'items_indicator_background_color',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--items-indicator-background-color: {{VALUE}};',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->add_responsive_control(
			'items_indicator_distance',
			[
				'label' => esc_html__( 'Distance', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
                //10Web minor customization
                'selectors' => [
                    'body:not(.rtl) {{WRAPPER}} .twbb_menu-cart__toggle .elementor-button-icon .elementor-button-icon-qty[data-counter]' => 'right: calc(-15px - {{SIZE}}{{UNIT}}); top: calc( -15px - {{SIZE}}{{UNIT}});',
                    'body.rtl {{WRAPPER}} .twbb_menu-cart__toggle .elementor-button-icon .elementor-button-icon-qty[data-counter]' => 'right: calc(15px - {{SIZE}}{{UNIT}}); top: calc( 15px - {{SIZE}}{{UNIT}}); left: auto;',
                ],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => esc_html__( 'Cart', 'tenweb-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--cart-background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_type',
			[
				'label' => esc_html__( 'Border Type', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'tenweb-builder' ),
					'solid' => esc_html__( 'Solid', 'tenweb-builder' ),
					'double' => esc_html__( 'Double', 'tenweb-builder' ),
					'dotted' => esc_html__( 'Dotted', 'tenweb-builder' ),
					'dashed' => esc_html__( 'Dashed', 'tenweb-builder' ),
					'groove' => esc_html__( 'Groove', 'tenweb-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cart-border-style: {{VALUE}};',
				],
				'default' => 'none',
			]
		);

		$this->add_responsive_control(
			'border_width',
			[
				'label' => esc_html__( 'Width', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__main' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--cart-border-color: {{VALUE}};',
				],
				'condition' => [
					'border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--cart-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cart_box_shadow',
				'selector' => '{{WRAPPER}} .twbb_menu-cart__main',
			]
		);

		$this->add_responsive_control(
			'cart_padding',
			[
				'label' => esc_html__( 'Padding', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--cart-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_close',
			[
				'label' => esc_html__( 'Close Cart', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'close_cart_button_show!' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'close_cart_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--cart-close-icon-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'close_cart_button_show!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'cart_icon_style' );

		$this->start_controls_tab(
			'icon_normal',
			[
				'label' => esc_html__( 'Normal', 'tenweb-builder' ),
				'condition' => [
					'close_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'close_cart_icon_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--cart-close-button-color: {{VALUE}};',
				],
				'condition' => [
					'close_cart_button_show!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			[
				'label' => esc_html__( 'Hover', 'tenweb-builder' ),
				'condition' => [
					'close_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'close_cart_icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--cart-close-button-hover-color: {{VALUE}};',
				],
				'condition' => [
					'close_cart_button_show!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_remove_item_button_style',
			[
				'label' => esc_html__( 'Remove Item', 'tenweb-builder' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'remove_item_button_size',
			[
				'label' => esc_html__( 'Icon Size', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--remove-item-button-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->start_controls_tabs(
			'cart_remove_item_button_style',
			[
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->start_controls_tab(
			'remove_item_button_normal',
			[
				'label' => esc_html__( 'Normal', 'tenweb-builder' ),
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->add_control(
			'remove_item_button_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--remove-item-button-color: {{VALUE}}',
				],
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'remove_item_button_hover',
			[
				'label' => esc_html__( 'Hover', 'tenweb-builder' ),
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->add_control(
			'remove_item_button_hover_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--remove-item-button-hover-color: {{VALUE}};',
				],
				'condition' => [
					'show_remove_icon!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_subtotal_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Subtotal', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtotal_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--menu-cart-subtotal-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .twbb_menu-cart__subtotal',
			]
		);

		$this->add_responsive_control(
			'subtotal_alignment',
			[
				'label' => esc_html__( 'Alignment', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--menu-cart-subtotal-text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subtotal_divider_style',
			[
				'label' => esc_html__( 'Divider Style', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'tenweb-builder' ),
					'solid' => esc_html__( 'Solid', 'tenweb-builder' ),
					'double' => esc_html__( 'Double', 'tenweb-builder' ),
					'dotted' => esc_html__( 'Dotted', 'tenweb-builder' ),
					'dashed' => esc_html__( 'Dashed', 'tenweb-builder' ),
					'groove' => esc_html__( 'Groove', 'tenweb-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}} .widget_shopping_cart_content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'' => '--subtotal-divider-left-width: 0; --subtotal-divider-right-width: 0;',
					'solid' => '--subtotal-divider-style: solid;',
					'double' => '--subtotal-divider-style: double;',
					'dotted' => '--subtotal-divider-style: dotted;',
					'dashed' => '--subtotal-divider-style: dashed;',
					'groove' => '--subtotal-divider-style: groove;',
				],
			]
		);

		$this->add_responsive_control(
			'subtotal_divider_width',
			[
				'label' => esc_html__( 'Width', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .widget_shopping_cart_content' => '--subtotal-divider-top-width: {{TOP}}{{UNIT}}; --subtotal-divider-right-width: {{RIGHT}}{{UNIT}}; --subtotal-divider-bottom-width: {{BOTTOM}}{{UNIT}}; --subtotal-divider-left-width: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'subtotal_divider_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .widget_shopping_cart_content' => '--subtotal-divider-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => esc_html__( 'Products', 'tenweb-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_product_title_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Title', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .twbb_menu-cart__product-name a',
			]
		);

		$this->start_controls_tabs( 'product_title_colors' );

		$this->start_controls_tab( 'product_title_normal_colors', [ 'label' => esc_html__( 'Normal', 'tenweb-builder' ) ] );

		$this->add_control(
			'product_title_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__product-name, {{WRAPPER}} .twbb_menu-cart__product-name a' => 'color: {{VALUE}}',

				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_title_hover_colors', [ 'label' => esc_html__( 'Hover', 'tenweb-builder' ) ] );

		$this->add_control(
			'product_title_hover_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__product-name a:hover' => 'color: {{VALUE}};',

				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_product_variations_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Variations', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_variations_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--product-variations-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_variations_typography',
				'selector' => '{{WRAPPER}} .twbb_menu-cart__product .variation',
			]
		);

		$this->add_control(
			'heading_product_price_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Price', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--product-price-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .twbb_menu-cart__product-price',
			]
		);

		$this->add_control(
			'heading_quantity_title_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Quantity', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_quantity_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__product-price .product-quantity' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_quantity_typography',
				'selector' => '{{WRAPPER}} .twbb_menu-cart__product-price .product-quantity',
			]
		);

		$this->add_control(
			'heading_product_divider_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Divider', 'tenweb-builder' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => esc_html__( 'Style', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'tenweb-builder' ),
					'solid' => esc_html__( 'Solid', 'tenweb-builder' ),
					'double' => esc_html__( 'Double', 'tenweb-builder' ),
					'dotted' => esc_html__( 'Dotted', 'tenweb-builder' ),
					'dashed' => esc_html__( 'Dashed', 'tenweb-builder' ),
					'groove' => esc_html__( 'Groove', 'tenweb-builder' ),
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-style: {{VALUE}}; --subtotal-divider-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => esc_html__( 'Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--divider-color: {{VALUE}}; --subtotal-divider-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label' => esc_html__( 'Weight', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--divider-width: {{SIZE}}{{UNIT}}; --subtotal-divider-top-width: {{SIZE}}{{UNIT}}; --subtotal-divider-right-width: {{SIZE}}{{UNIT}}; --subtotal-divider-bottom-width: {{SIZE}}{{UNIT}}; --subtotal-divider-left-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'divider_gap',
			[
				'label' => esc_html__( 'Spacing', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--product-divider-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Buttons', 'tenweb-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'view_cart_button_show',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'checkout_button_show',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'buttons_layout',
			[
				'label' => esc_html__( 'Layout', 'tenweb-builder' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'inline' => esc_html__( 'Inline', 'tenweb-builder' ),
					'stacked' => esc_html__( 'Stacked', 'tenweb-builder' ),
				],
				'default' => 'inline',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'condition' => [
					'view_cart_button_show!' => '',
					'checkout_button_show!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'inline' => '--cart-footer-layout: 1fr 1fr; --products-max-height-sidecart: calc(100vh - 240px); --products-max-height-minicart: calc(100vh - 385px)',
					'stacked' => '--cart-footer-layout: 1fr; --products-max-height-sidecart: calc(100vh - 300px); --products-max-height-minicart: calc(100vh - 450px)',
				],
			]
		);

		$this->add_responsive_control(
			'space_between_buttons',
			[
				'label' => esc_html__( 'Space Between', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--space-between-buttons: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'view_cart_button_show!' => '',
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_buttons_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tenweb-builder' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--cart-footer-buttons-border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'heading_view_cart_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'View Cart', 'tenweb-builder' ),
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'view_cart_buttons_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .twbb_menu-cart__footer-buttons a.elementor-button--view-cart',
				'separator' => 'before',
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->start_controls_tabs(
			'view_cart_button_text_colors',
			[
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->start_controls_tab(
			'heading_view_cart_button_normal_style',
			[
				'label' => esc_html__( 'Normal', 'tenweb-builder' ),
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'view_cart_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--view-cart-button-text-color: {{VALUE}};',
				],
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'view_cart_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--view-cart-button-background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'heading_view_cart_button_hover_style',
			[
				'label' => esc_html__( 'Hover', 'tenweb-builder' ),
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'view_cart_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button--view-cart.elementor-button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'view_cart_button_hover_background',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button--view-cart.elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_button_border_hover_color',
			[
				'label' => esc_html__( 'Border Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button--view-cart:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'view_cart_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_cart_border',
				'selector' => '{{WRAPPER}} .elementor-button--view-cart',
				'separator' => 'before',
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'view_cart_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons a.elementor-button--view-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'view_cart_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button--view-cart',
				'condition' => [
					'view_cart_button_show!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'view_cart_button_padding',
			[
				'label' => esc_html__( 'Padding', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--view-cart-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view_cart_button_show!' => '',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'heading_checkout_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Checkout', 'tenweb-builder' ),
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cart_checkout_button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .twbb_menu-cart__footer-buttons a.elementor-button--checkout',
				'separator' => 'before',
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->start_controls_tabs(
			'cart_checkout_button_text_colors',
			[
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->start_controls_tab(
			'heading_cart_checkout_button_normal_style',
			[
				'label' => esc_html__( 'Normal', 'tenweb-builder' ),
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'checkout_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--checkout-button-text-color: {{VALUE}};',
				],
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'checkout_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--checkout-button-background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'heading_cart_checkout_button_hover_style',
			[
				'label' => esc_html__( 'Hover', 'tenweb-builder' ),
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'checkout_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button--checkout.elementor-button:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_control(
			'checkout_button_hover_background',
			[
				'label' => esc_html__( 'Border Width', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button--checkout.elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_button_border_hover_color',
			[
				'label' => esc_html__( 'Border Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons .elementor-button--checkout:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'checkout_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'selector' => '{{WRAPPER}} .elementor-button--checkout',
				'separator' => 'before',
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'view_checkout_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .twbb_menu-cart__footer-buttons a.elementor-button--checkout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'view_checkout_button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button--checkout',
				'condition' => [
					'checkout_button_show!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'view_checkout_button_padding',
			[
				'label' => esc_html__( 'Padding', 'tenweb-builder' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--checkout-button-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'checkout_button_show!' => '',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_messages',
			[
				'label' => esc_html__( 'Messages', 'tenweb-builder' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cart_empty_message_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__empty-message',
			]
		);

		$this->add_control(
			'empty_message_color',
			[
				'label' => esc_html__( 'Empty Cart Message Color', 'tenweb-builder' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--empty-message-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'empty_message_alignment',
			[
				'label' => esc_html__( 'Border Radius', 'tenweb-builder' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'tenweb-builder' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--empty-message-alignment: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Check if user did not explicitly disabled the use of our mini-cart template and set the option accordingly.
	 * The option value is later used by Module::woocommerce_locate_template().
	 */
	private function maybe_use_mini_cart_template() {
		$option_value = get_option( 'elementor_' . Woocommerce::OPTION_NAME_USE_MINI_CART, '' );
		if ( empty( $option_value ) || 'initial' === $option_value ) {
			update_option( 'elementor_' . Woocommerce::OPTION_NAME_USE_MINI_CART, 'yes' );
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->maybe_use_mini_cart_template();
		Woocommerce::render_menu_cart($settings);
	}

	public function render_plain_content() {}
}
\Elementor\Plugin::instance()->widgets_manager->register( new Menu_Cart() );
