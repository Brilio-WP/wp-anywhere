<?php
namespace Tenweb_Builder\ElementorPro\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Tenweb_Builder\ElementorPro\Core\Utils;
use Tenweb_Builder\ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use Tenweb_Builder\ElementorPro\Modules\Forms\Classes\Form_Base;
use Tenweb_Builder\ElementorPro\Modules\Forms\Controls\Fields_Repeater;
use Tenweb_Builder\ElementorPro\Modules\Forms\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Form extends Form_Base {

	public function get_name() {
		return 'twbb_form';
	}

	public function get_title() {
		return esc_html__( 'Form', 'elementor-pro' );
	}

	public function get_icon() {
		return 'twbb-form twbb-widget-icon';
	}

	public function get_keywords() {
		return [ 'form', 'forms', 'field', 'button', 'mailchimp', 'drip', 'mailpoet', 'convertkit', 'getresponse', 'recaptcha', 'zapier', 'webhook', 'activecampaign', 'slack', 'discord', 'mailerlite' ];
	}

	protected function register_controls() {
		$repeater = new Repeater();

		$field_types = [
			'text' => esc_html__( 'Text', 'elementor-pro' ),
			'email' => esc_html__( 'Email', 'elementor-pro' ),
			'textarea' => esc_html__( 'Textarea', 'elementor-pro' ),
			'url' => esc_html__( 'URL', 'elementor-pro' ),
			'tel' => esc_html__( 'Tel', 'elementor-pro' ),
			'radio' => esc_html__( 'Radio', 'elementor-pro' ),
			'select' => esc_html__( 'Select', 'elementor-pro' ),
			'checkbox' => esc_html__( 'Checkbox', 'elementor-pro' ),
			'acceptance' => esc_html__( 'Acceptance', 'elementor-pro' ),
			'number' => esc_html__( 'Number', 'elementor-pro' ),
			'date' => esc_html__( 'Date', 'elementor-pro' ),
			'time' => esc_html__( 'Time', 'elementor-pro' ),
			'upload' => esc_html__( 'File Upload', 'elementor-pro' ),
			'password' => esc_html__( 'Password', 'elementor-pro' ),
			'html' => esc_html__( 'HTML', 'elementor-pro' ),
			'hidden' => esc_html__( 'Hidden', 'elementor-pro' ),
			'custom_button' => esc_html__( 'Button', 'elementor-pro' ),
		];

		/**
		 * Forms field types.
		 *
		 * Filters the list of field types displayed in the form `field_type` control.
		 *
		 * @since 1.0.0
		 *
		 * @param array $field_types Field types.
		 */
		$field_types = apply_filters( 'elementor_tenweb/forms/field_types', $field_types );

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab( 'form_fields_content_tab', [
			'label' => esc_html__( 'Content', 'elementor-pro' ),
		] );

		$repeater->add_control(
			'field_type',
			[
				'label' => esc_html__( 'Type', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => esc_html__( 'Label', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => esc_html__( 'Required', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'checkbox',
								'recaptcha',
								'recaptcha_v3',
								'hidden',
								'html',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => esc_html__( 'Options', 'elementor-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => "Option 1|Option 1
Option 2|Option 2
Option 3|Option 3",
				'description' => esc_html__( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'elementor-pro' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => esc_html__( 'Multiple Selection', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => esc_html__( 'Rows', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label' => esc_html__( 'Inline List', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'elementor-subgroup-inline',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_html',
			[
				'label' => esc_html__( 'HTML', 'elementor-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Column Width', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'elementor-pro' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'70' => '70%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'30' => '30%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha_v3',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size', [
				'label' => esc_html__( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'elementor-pro' ),
					'compact' => esc_html__( 'Compact', 'elementor-pro' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => esc_html__( 'Style', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'elementor-pro' ),
					'dark' => esc_html__( 'Dark', 'elementor-pro' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_badge', [
				'label' => esc_html__( 'Badge', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottomright',
				'options' => [
					'bottomright' => esc_html__( 'Bottom Right', 'elementor-pro' ),
					'bottomleft' => esc_html__( 'Bottom Left', 'elementor-pro' ),
					'inline' => esc_html__( 'Inline', 'elementor-pro' ),
				],
				'description' => esc_html__( 'To view the validation badge, switch to preview mode', 'elementor-pro' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha_v3',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'elementor-pro' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor-pro' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => esc_html__( 'Advanced', 'elementor-pro' ),
				'condition' => [
					'field_type!' => 'html',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
								'date',
								'time',
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_id',
			[
				'label' => esc_html__( 'ID', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere in this form. This field allows `A-z 0-9` & underscore chars without spaces.', 'elementor-pro' ),
				'render_type' => 'none',
				'required' => true,
			]
		);

		$shortcode_template = '{{ view.container.settings.get( \'custom_id\' ) }}';
		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'elementor-pro' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" value=\'[field id="' . $shortcode_template . '"]\' readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Form Fields', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => esc_html__( 'Form Name', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Form', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Form Name', 'elementor-pro' ),
			]
		);

    /* 10Web Builder */
    $this->add_control(
      'display_form_title_description',
      [
        'label' => esc_html__( 'Display Title and Description', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => '',
      ]
    );

    $this->add_control(
      'title_size',
      [
        'label' => esc_html__( 'Size', 'elementor' ),
        'type' => Controls_Manager::SELECT,
        'default' => 'large',
        'options' => [
          'default' => esc_html__( 'Default', 'elementor' ),
          'small' => esc_html__( 'Small', 'elementor' ),
          'medium' => esc_html__( 'Medium', 'elementor' ),
          'large' => esc_html__( 'Large', 'elementor' ),
          'xl' => esc_html__( 'XL', 'elementor' ),
          'xxl' => esc_html__( 'XXL', 'elementor' ),
        ],
        'condition' => [
          'display_form_title_description' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'title_tag',
      [
        'label' => esc_html__( 'HTML Tag', 'elementor' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'h1' => 'H1',
          'h2' => 'H2',
          'h3' => 'H3',
          'h4' => 'H4',
          'h5' => 'H5',
          'h6' => 'H6',
          'div' => 'div',
          'span' => 'span',
          'p' => 'p',
        ],
        'default' => 'h2',
        'condition' => [
          'display_form_title_description' => 'yes',
        ],
      ]
    );

    $this->add_responsive_control(
      'title_align',
      [
        'label' => esc_html__( 'Alignment', 'elementor' ),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__( 'Left', 'elementor' ),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__( 'Center', 'elementor' ),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__( 'Right', 'elementor' ),
            'icon' => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__( 'Justified', 'elementor' ),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'default' => 'center',
        'selectors' => [
          '{{WRAPPER}} .elementor-form-title' => 'text-align: {{VALUE}};',
        ],
        'condition' => [
          'display_form_title_description' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'form_description',
      [
        'label' => esc_html__( 'Description', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXTAREA,
        'default' => '',
        'placeholder' => esc_html__( 'Description', 'tenweb-builder' ),
        'condition' => [
          'display_form_title_description' => 'yes',
        ],
      ]
    );

    $this->add_control('separator_form_fields', [
      'type' => Controls_Manager::DIVIDER,
      'style' => 'thick',
    ]);
    /* 10Web End*/

		$this->add_control(
			'form_fields',
			[
				'type' => Fields_Repeater::CONTROL_TYPE,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'custom_id' => 'name',
						'field_type' => 'text',
						'field_label' => esc_html__( 'Name', 'elementor-pro' ),
						'placeholder' => esc_html__( 'Name', 'elementor-pro' ),
						'width' => '100',
					],
					[
						'custom_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => esc_html__( 'Email', 'elementor-pro' ),
						'placeholder' => esc_html__( 'Email', 'elementor-pro' ),
						'width' => '100',
					],
					[
						'custom_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => esc_html__( 'Message', 'elementor-pro' ),
						'placeholder' => esc_html__( 'Message', 'elementor-pro' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => esc_html__( 'Input Size', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'elementor-pro' ),
					'sm' => esc_html__( 'Small', 'elementor-pro' ),
					'md' => esc_html__( 'Medium', 'elementor-pro' ),
					'lg' => esc_html__( 'Large', 'elementor-pro' ),
					'xl' => esc_html__( 'Extra Large', 'elementor-pro' ),
				],
				'default' => 'sm',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Label', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => esc_html__( 'Required Mark', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'elementor-pro' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Label Position', 'elementor-pro' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => esc_html__( 'Above', 'elementor-pro' ),
					'inline' => esc_html__( 'Inline', 'elementor-pro' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
                'condition' => [ 'button_size[value]!' => 'sm' ], // a workaround to hide the control, unless it's in use (not default).
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Column Width', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'elementor-pro' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'70' => '70%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'30' => '30%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-text-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'elementor-pro' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'stretch',
				'prefix_class' => 'elementor%s-button-align-',
			]
		);

		$this->add_control(
			'heading_steps_buttons',
			[
				'label' => esc_html__( 'Step Buttons', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'step_next_label',
			[
				'label' => esc_html__( 'Next', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type' => 'none',
				'default' => esc_html__( 'Next', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Next', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'step_previous_label',
			[
				'label' => esc_html__( 'Previous', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type' => 'none',
				'default' => esc_html__( 'Previous', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Previous', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'heading_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

    /* 10Web Builder */
    $this->add_control(
      'button_show',
      [
        'label' => esc_html__( 'Display Submit Button', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
      ]
    );

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Submit', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Send', 'elementor-pro' ),
        'condition' => [
          'button_show' => 'yes',
        ],
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
        'condition' => [
          'button_show' => 'yes',
        ],
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'elementor-pro' ),
					'right' => esc_html__( 'After', 'elementor-pro' ),
				],
				'condition' => [
          'button_show' => 'yes',
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
          'button_show' => 'yes',
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button[type=submit] .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button[type=submit] .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

    /* 10Web Builder */
    $this->add_control(
      'heading_reset_button',
      [
        'label' => esc_html__( 'Reset Button', 'tenweb-builder' ),
        'type' => Controls_Manager::HEADING,
        'condition' => [
          'button_show' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'reset_button_show',
      [
        'label' => esc_html__( 'Display Reset Button', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => '','condition' => [
        'button_show' => 'yes',
      ],
      ]
    );

    $this->add_control(
      'reset_button_text',
      [
        'label' => esc_html__( 'Reset', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXT,
        'default' => esc_html__( 'Reset', 'tenweb-builder' ),
        'placeholder' => esc_html__( 'Reset', 'tenweb-builder' ),
        'condition' => [
          'button_show' => 'yes',
          'reset_button_show' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'selected_reset_button_icon',
      [
        'label' => esc_html__( 'Icon', 'tenweb-builder' ),
        'type' => Controls_Manager::ICONS,
        'skin' => 'inline',
        'label_block' => false,
        'condition' => [
          'button_show' => 'yes',
          'reset_button_show' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'reset_button_icon_align',
      [
        'label' => esc_html__( 'Icon Position', 'tenweb-builder' ),
        'type' => Controls_Manager::SELECT,
        'default' => 'left',
        'options' => [
          'left' => esc_html__( 'Before', 'tenweb-builder' ),
          'right' => esc_html__( 'After', 'tenweb-builder' ),
        ],
        'condition' => [
          'button_show' => 'yes',
          'reset_button_show' => 'yes',
          'selected_reset_button_icon[value]!' => '',
        ],
      ]
    );

    $this->add_control(
      'reset_button_icon_indent',
      [
        'label' => esc_html__( 'Icon Spacing', 'tenweb-builder' ),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'max' => 50,
          ],
        ],
        'condition' => [
          'button_show' => 'yes',
          'reset_button_show' => 'yes',
          'selected_reset_button_icon[value]!' => '',
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-button[type=reset] .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-button[type=reset] .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor-pro' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows `A-z 0-9` & underscore chars without spaces.', 'elementor-pro' ),
				'separator' => 'before',
        'condition' => [
          'button_show' => 'yes',
        ],
			]
		);

		$this->end_controls_section();

        //10Web Customization
        $this->start_controls_section(
            'section_text_after_buttons',
            [
                'label' => esc_html__( 'Text after buttons', 'tenweb-builder' ),
            ]
        );

        $this->add_control( 'text_after_buttons', [
                'label' => __('', 'tenweb-builder'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'placeholder' => esc_html__( 'Description', 'tenweb-builder' ),
            ]
        );

        $this->end_controls_section();
        //End 10Web Customization

		$this->start_controls_section(
			'section_integration',
			[
				'label' => esc_html__( 'Actions After Submit', 'elementor-pro' ),
			]
		);

		$actions = Module::instance()->actions_registrar->get();

		$actions_options = [];

		foreach ( $actions as $action ) {
			$actions_options[ $action->get_name() ] = $action->get_label();
		}

		$default_submit_actions = [ 'email' ];
		$default_submit_actions = apply_filters( 'elementor_tenweb/forms/default_submit_actions', $default_submit_actions );
		$this->add_control(
			'submit_actions',
			[
				'label' => esc_html__( 'Add Action', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $actions_options,
				'render_type' => 'none',
				'label_block' => true,
				'default' => $default_submit_actions,
				'description' => esc_html__( 'Add actions that will be performed after a visitor submits the form (e.g. send an email notification). Choosing an action will add its setting below.', 'elementor-pro' ),
			]
		);

        /*TODO: dashboard url should be changed when front part be ready */
        $this->add_control(
            'custom_link',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<a href="'.esc_url(TENWEB_DASHBOARD).'" target="_blank" class="twbb-form-dashboard-link">'.esc_html__( 'View Submissions', 'elementor-pro' ).'</a>',
                'content_classes' => 'twbb-submissions-link', // Optional: Add CSS classes to style
            ]
        );

		$this->end_controls_section();

		foreach ( $actions as $action ) {
			$action->register_settings_section( $this );
		}

		// Steps settings.
		$this->start_controls_section(
			'section_steps_settings',
			[
				'label' => esc_html__( 'Steps Settings', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'step_type',
			[
				'label' => esc_html__( 'Type', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'render_type' => 'none',
				'options' => [
					'none' => 'None',
					'text' => 'Text',
					'icon' => 'Icon',
					'number' => 'Number',
					'progress_bar' => 'Progress Bar',
					'number_text' => 'Number & Text',
					'icon_text' => 'Icon & Text',
				],
				'default' => 'number_text',
			]
		);

		$this->add_control(
			'step_icon_shape',
			[
				'label' => esc_html__( 'Shape', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'render_type' => 'none',
				'options' => [
					'circle' => 'Circle',
					'square' => 'Square',
					'rounded' => 'Rounded',
					'none' => 'None',
				],
				'default' => 'circle',
				'conditions' => [
					'terms' => [
						[
							'name' => 'step_type',
							'operator' => '!in',
							'value' => [
								'progress_bar',
								'text',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'display_percentage',
			[
				'label' => esc_html__( 'Display Percentage', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'render_type' => 'none',
				'return_value' => 'true',
				'default' => '',
				'condition' => [
					'step_type' => 'progress_bar',
				],
			]
		);

		// End of steps settings.
		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_options',
			[
				'label' => esc_html__( 'Additional Options', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => esc_html__( 'Form ID', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'new_form_id',
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows `A-z 0-9` & underscore chars without spaces.', 'elementor-pro' ),
				'separator' => 'after',
            ]
        );

		$this->add_control(
			'form_validation',
			[
				'label' => esc_html__( 'Form Validation', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Browser Default', 'elementor-pro' ),
					'custom' => esc_html__( 'Custom', 'elementor-pro' ),
				],
				'default' => '',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'section_custom_message',
            [
                'label' => esc_html__( 'Messages', 'elementor-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'custom_messages',
            [
                'label' => esc_html__( 'Custom Messages', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
                'render_type' => 'none',
            ]
        );

        $default_messages = Ajax_Handler::get_default_messages();

        $this->add_control(
            'success_message',
            [
                'label' => esc_html__( 'Success Message', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => $default_messages[ Ajax_Handler::SUCCESS ],
                'placeholder' => $default_messages[ Ajax_Handler::SUCCESS ],
                'label_block' => true,
                'condition' => [
                    'custom_messages!' => '',
                ],
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label' => esc_html__( 'Error Message', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => $default_messages[ Ajax_Handler::ERROR ],
                'placeholder' => $default_messages[ Ajax_Handler::ERROR ],
                'label_block' => true,
                'condition' => [
                    'custom_messages!' => '',
                ],
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'server_message',
            [
                'label' => esc_html__( 'Server Error', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => $default_messages[ Ajax_Handler::SERVER_ERROR ],
                'placeholder' => $default_messages[ Ajax_Handler::SERVER_ERROR ],
                'label_block' => true,
                'condition' => [
                    'custom_messages!' => '',
                ],
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'invalid_message',
            [
                'label' => esc_html__( 'Invalid Message', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => $default_messages[ Ajax_Handler::INVALID_FORM ],
                'placeholder' => $default_messages[ Ajax_Handler::INVALID_FORM ],
                'label_block' => true,
                'condition' => [
                    'custom_messages!' => '',
                ],
                'render_type' => 'none',
            ]
        );

        $this->add_control(
            'required_field_message',
            [
                'label' => esc_html__( 'Required Field', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => $default_messages[ Ajax_Handler::FIELD_REQUIRED ],
                'placeholder' => $default_messages[ Ajax_Handler::FIELD_REQUIRED ],
                'label_block' => true,
                'condition' => [
                    'custom_messages!' => '',
                    'form_validation' => 'custom',
                ],
                'render_type' => 'none',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Rows Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group.recaptcha_v3-bottomleft, {{WRAPPER}} .elementor-field-group.recaptcha_v3-bottomright' => 'margin-bottom: 0;',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

    /* 10Web Builder */
    $this->add_control(
      'heading_form_name',
      [
        'label' => esc_html__( 'Title', 'tenweb-builder' ),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => esc_html__( 'Text Color', 'elementor' ),
        'type' => Controls_Manager::COLOR,
        'global' => [
          'default' => Global_Colors::COLOR_PRIMARY,
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-heading-title.elementor-form-title' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'typography_title',
        'global' => [
          'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
        ],
        'selector' => '{{WRAPPER}} .elementor-heading-title.elementor-form-title',
      ]
    );

    $this->add_group_control(
      Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'text_shadow',
        'selector' => '{{WRAPPER}} .elementor-heading-title.elementor-form-title',
      ]
    );
        /* 10Web customization */
        $this->add_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Spacing', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    'body.rtl {{WRAPPER}} .elementor-labels-inline .elementor-form-title-description-wrapper > .elementor-form-title' => 'padding-left: {{SIZE}}{{UNIT}};',
                    // for the label position = inline option
                    'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .elementor-form-title-description-wrapper > .elementor-form-title' => 'padding-right: {{SIZE}}{{UNIT}};',
                    // for the label position = inline option
                    'body {{WRAPPER}} .elementor-labels-above .elementor-form-title-description-wrapper > .elementor-form-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    // for the label position = above option
                    'body {{WRAPPER}} .elementor-form-title-description-wrapper > .elementor-form-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',

                ],
            ]
        );
        /* end 10Web customization */
    $this->add_control(
      'heading_form_description',
      [
        'label' => esc_html__( 'Description', 'tenweb-builder' ),
        'type' => Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_responsive_control(
      'align_description',
      [
        'label' => esc_html__( 'Alignment', 'elementor' ),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left' => [
            'title' => esc_html__( 'Left', 'elementor' ),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => __( 'Center', 'elementor' ),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__( 'Right', 'elementor' ),
            'icon' => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__( 'Justified', 'elementor' ),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-form-title-description-wrapper .elementor-text-editor' => 'text-align: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'description_color',
      [
        'label' => esc_html__( 'Text Color', 'elementor' ),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .elementor-form-title-description-wrapper .elementor-text-editor' => 'color: {{VALUE}};',
        ],
        'global' => [
          'default' => Global_Colors::COLOR_TEXT,
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'typography_description',
        'global' => [
          'default' => Global_Typography::TYPOGRAPHY_TEXT,
        ],
        'selector' => '{{WRAPPER}} .elementor-form-title-description-wrapper .elementor-text-editor',
      ]
    );
        /* 10Web customization */
        $this->add_control(
            'description_spacing',
            [
                'label' => esc_html__( 'Spacing', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    'body.rtl {{WRAPPER}} .elementor-labels-inline .elementor-form-title-description-wrapper > .elementor-text-editor[data-elementor-setting-key="form_description"]' => 'padding-left: {{SIZE}}{{UNIT}};',
                    // for the label position = inline option
                    'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .elementor-form-title-description-wrapper > .elementor-text-editor[data-elementor-setting-key="form_description"]' => 'padding-right: {{SIZE}}{{UNIT}};',
                    // for the label position = inline option
                    'body {{WRAPPER}} .elementor-labels-above .elementor-form-title-description-wrapper > .elementor-text-editor[data-elementor-setting-key="form_description"]' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    // for the label position = above option
                    'body {{WRAPPER}} .elementor-form-title-description-wrapper > .elementor-text-editor[data-elementor-setting-key="form_description"]' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        /* end 10Web customization */
		$this->add_control(
			'heading_label',
			[
				'label' => esc_html__( 'Label', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .elementor-labels-above .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group > label, {{WRAPPER}} .elementor-field-subgroup label' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => esc_html__( 'Mark Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-mark-required .elementor-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group > label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'heading_html',
			[
				'label' => esc_html__( 'HTML Field', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'html_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-type-html' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'html_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-type-html' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'html_typography',
				'selector' => '{{WRAPPER}} .elementor-field-type-html',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Field', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload, .elementor-field-type-custom_button) .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
          '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload, .elementor-field-type-custom_button) .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => esc_html__( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
          '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload, .elementor-field-type-custom_button) .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
          '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload, .elementor-field-type-custom_button) .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload, .elementor-field-type-custom_button) .elementor-field:not(.elementor-select-wrapper),
                {{WRAPPER}} .elementor-field-group .elementor-select-wrapper select',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Buttons', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .elementor-button',
				'exclude' => [
					'color',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'heading_next_submit_button',
			[
				'label' => esc_html__( 'Next & Submit Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-next' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"] svg *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-next' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'heading_previous_button',
			[
				'label' => esc_html__( 'Previous & Reset Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'previous_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-previous' => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-button[type="reset"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-previous' => 'color: {{VALUE}};',
          '{{WRAPPER}} .elementor-button[type="reset"]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-previous' => 'border-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-button[type="reset"]' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .e-form__buttons__wrapper__button-next, 
                {{WRAPPER}} .elementor-button[type="submit"], {{WRAPPER}} .e-form__buttons__wrapper__button-previous',

            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'heading_next_submit_button_hover',
			[
				'label' => esc_html__( 'Next & Submit Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-next:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]:hover svg *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-next:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'heading_previous_button_hover',
			[
				'label' => esc_html__( 'Previous & Reset Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'previous_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-previous:hover' => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-button[type="reset"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-previous:hover' => 'color: {{VALUE}};',
          '{{WRAPPER}} .elementor-button[type="reset"]:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'previous_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .e-form__buttons__wrapper__button-previous:hover' => 'border-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-button[type="reset"]:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'elementor-pro' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .e-form__buttons__wrapper__button-next:hover, 
                {{WRAPPER}} .elementor-button[type="submit"]:hover, 
                {{WRAPPER}} .e-form__buttons__wrapper__button-previous:hover',

            ]
        );

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Text Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        //10Web Customization
        $this->start_controls_section(
            'section_text_after_buttons_style',
            [
                'label' => esc_html__( 'Text after buttons', 'tenweb-builder' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'text_after_buttons!' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'align_text_after_buttons',
            [
                'label' => esc_html__( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'elementor' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'elementor' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'elementor' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'elementor' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-form-text-after-description-wrapper .elementor-text-editor' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'text_color_text_after_buttons',
            [
                'label' => esc_html__( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-form-text-after-description-wrapper .elementor-text-editor' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_text_after_buttons',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .elementor-form-text-after-description-wrapper .elementor-text-editor',
            ]
        );
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow_text_after_buttons',
                'selector' => '{{WRAPPER}} .elementor-form-text-after-description-wrapper .elementor-text-editor',
            ]
        );
        $this->add_responsive_control(
            'text_after_buttons_spacing',
            [
                'label' => __('Spacing', 'tenweb-builder'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-form-text-after-description-wrapper .elementor-text-editor' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'text_after_buttons!' => '',
                ],
            ]
        );

        $this->end_controls_section();
        //End 10Web Customization

		$this->start_controls_section(
			'section_messages_style',
			[
				'label' => esc_html__( 'Messages', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-message',
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => esc_html__( 'Success Message Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => esc_html__( 'Error Message Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'inline_message_color',
			[
				'label' => esc_html__( 'Inline Message Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-help-inline' => 'color: {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_steps_style',
			[
				'label' => esc_html__( 'Steps', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'steps_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .e-form__indicators__indicator, {{WRAPPER}} .e-form__indicators__indicator__label',
				'conditions' => [
					'terms' => [
						[
							'name' => 'step_type',
							'operator' => '!in',
							'value' => [
								'icon',
								'progress_bar',
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'steps_gap',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicators-spacing: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'steps_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'step_type',
							'operator' => 'in',
							'value' => [
								'icon',
								'icon_text',
							],
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-icon-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'steps_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-padding: {{SIZE}}{{UNIT}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'step_type',
							'operator' => '!in',
							'value' => [
								'text',
								'progress_bar',
							],
						],
					],
				],
			]
		);

		$this->start_controls_tabs( 'steps_state', [
			'condition' => [
				'step_type!' => 'progress_bar',
			],
		] );

		$this->start_controls_tab(
			'tab_steps_state_inactive',
			[
				'label' => esc_html__( 'Inactive', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'step_inactive_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-inactive-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_inactive_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-inactive-secondary-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_steps_state_active',
			[
				'label' => esc_html__( 'Active', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'step_active_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-active-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_active_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-active-secondary-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_steps_state_completed',
			[
				'label' => esc_html__( 'Completed', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'step_completed_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-completed-primary-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_completed_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'condition' => [
					'step_icon_shape!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-completed-secondary-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'step_divider_width',
			[
				'label' => esc_html__( 'Divider Width', 'elementor-pro' ),
				'separator' => 'before',
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'step_type!' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-divider-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'step_divider_gap',
			[
				'label' => esc_html__( 'Divider Gap', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'step_type!' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-divider-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'step_progress_bar_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'condition' => [
					'step_type' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-progress-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'step_progress_bar_background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [
					'step_type' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-progress-background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'step_progress_bar_height',
			[
				'label' => esc_html__( 'Height', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'step_type' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-progress-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'step_progress_bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'step_type' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-progress-border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'step_progress_bar_percentage_heading',
			[
				'label' => esc_html__( 'Percentage', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'step_type' => 'progress_bar',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'step_progress_bar_percentage__typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .e-form__indicators__indicator__progress__meter',
				'condition' => [
					'step_type' => 'progress_bar',
				],
			]
		);

		$this->add_control(
			'step_progress_bar_percentage_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => [
					'step_type' => 'progress_bar',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--e-form-steps-indicator-progress-meter-color: {{VALUE}};',
				],
			]
		);

		// End of steps style.
		$this->end_controls_section();

	}

	private function render_icon_with_fallback( $settings ) {
		$migrated = isset( $settings['__fa4_migrated']['selected_button_icon'] );
		$is_new = empty( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( $is_new || $migrated ) {
			Icons_Manager::render_icon( $settings['selected_button_icon'], [ 'aria-hidden' => 'true' ] );
		} else {
			?><i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i><?php
		}
	}

	protected function render() {
		$instance = $this->get_settings_for_display();

		if ( ! \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			/**
			 * Elementor form Pre render.
			 *
			 * Fires before the from is rendered in the frontend
			 *
			 * @since 2.4.0
			 *
			 * @param array $instance current form settings
			 * @param Form $this current form widget instance
			 */
			do_action( 'elementor_tenweb/forms/pre_render', $instance, $this );
		  /* 10Web customization */
      foreach ( $instance['form_fields'] as $item_index => $item ) {
        if (empty($item['custom_id'])) {
          $instance['form_fields'][$item_index]['custom_id'] = !empty($item['field_label']) ? strtolower($item['field_label']) : strtolower($item['field_type']);
        }
      }
		  /* end 10Web customization */
		}

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
						'elementor-labels-' . $instance['label_position'],
					],
				],
				'submit-group' => [
					'class' => [
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
					],
				],
				'button' => [
					'class' => 'elementor-button',
				],
        'reset-button' => [
          'class' => 'elementor-button',
        ],
				'icon-align' => [
					'class' => [
						empty( $instance['button_icon_align'] ) ? '' :
							'elementor-align-icon-' . $instance['button_icon_align'],
						'elementor-button-icon',
					],
				],
        'reset-icon-align' => [
          'class' => [
            empty( $instance['reset_button_icon_align'] ) ? '' :
              'elementor-align-icon-' . $instance['reset_button_icon_align'],
            'elementor-button-icon',
          ],
        ],
			]
		);

		if ( empty( $instance['button_width'] ) ) {
			$instance['button_width'] = '100';
		}

		$this->add_render_attribute( 'submit-group', 'class', 'elementor-col-' . $instance['button_width'] . ' e-form__buttons' );

    /* 10Web Builder */
    if ( empty( $instance['button_show'] ) ) {
      $this->add_render_attribute( 'submit-group', 'class', 'elementor-hidden' );
    }

    if ( empty( $instance['reset_button_show'] ) ) {
      $this->add_render_attribute( 'reset-button', 'class', 'elementor-hidden' );
    }
    else {
      $this->add_render_attribute( 'submit-group', 'class', 'elementor-reset-button-active' );
    }

		if ( ! empty( $instance['button_width_tablet'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-md-' . $instance['button_width_tablet'] );
		}

		if ( ! empty( $instance['button_width_mobile'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-sm-' . $instance['button_width_mobile'] );
		}

		if ( ! empty( $instance['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $instance['button_size'] );
      $this->add_render_attribute( 'reset-button', 'class', 'elementor-size-' . $instance['button_size'] );
		}

		if ( ! empty( $instance['button_type'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-button-' . $instance['button_type'] );
      $this->add_render_attribute( 'reset-button', 'class', 'elementor-button-' . $instance['button_type'] );
		}

		if ( $instance['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $instance['button_hover_animation'] );
      $this->add_render_attribute( 'reset-button', 'class', 'elementor-animation-' . $instance['button_hover_animation'] );
		}

		if ( ! empty( $instance['form_id'] ) ) {
			$this->add_render_attribute( 'form', 'id', $instance['form_id'] );
		}

		if ( ! empty( $instance['form_name'] ) ) {
			$this->add_render_attribute( 'form', 'name', $instance['form_name'] );
		}

	  if ( 'custom' === $instance['form_validation'] ) {
		  $this->add_render_attribute( 'form', 'novalidate' );
	  }

		if ( ! empty( $instance['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $instance['button_css_id'] );
		}

		$referer_title = trim( wp_title( '', false ) );

		if ( ! $referer_title && is_home() ) {
			$referer_title = get_option( 'blogname' );
		}

		?>
		<form class="elementor-form" method="post" <?php $this->print_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php echo Utils::get_current_post_id(); ?>"/>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->get_id() ); ?>"/>
			<input type="hidden" name="referer_title" value="<?php echo esc_attr( $referer_title ); ?>" />

			<?php if ( is_singular() ) {
				// `queried_id` may be different from `post_id` on Single theme builder templates.
				?>
				<input type="hidden" name="queried_id" value="<?php echo get_the_ID(); ?>"/>
			<?php } ?>

			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<?php
        /* 10Web Builder */
        if ( !empty( $instance['display_form_title_description'] ) ) {
          ?>
          <div class="elementor-form-title-description-wrapper elementor-field-group elementor-column elementor-col-100 elementor-widget-wrap elementor-widget-heading">
            <?php
            $this->render_form_title();
            ?>
          </div>
          <?php
        }
				foreach ( $instance['form_fields'] as $item_index => $item ) :
					$item['input_size'] = $instance['input_size'];
					$this->form_fields_render_attributes( $item_index, $instance, $item );

					$field_type = $item['field_type'];

					/**
					 * Render form field.
					 *
					 * Filters the field rendered by Elementor Forms.
					 *
					 * @since 1.0.0
					 *
					 * @param array $item       The field value.
					 * @param int   $item_index The field index.
					 * @param Form  $this       An instance of the form.
					 */
					$item = apply_filters( 'elementor_tenweb/forms/render/item', $item, $item_index, $this );

					/**
					 * Render form field.
					 *
					 * Filters the field rendered by Elementor Forms.
					 *
					 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
					 *
					 * @since 1.0.0
					 *
					 * @param array $item       The field value.
					 * @param int   $item_index The field index.
					 * @param Form  $this       An instance of the form.
					 */
					$item = apply_filters( "elementor_tenweb/forms/render/item/{$field_type}", $item, $item_index, $this );

					$print_label = ! in_array( $item['field_type'], [ 'hidden', 'html', 'step' ], true );
					?>
				<div <?php $this->print_render_attribute_string( 'field-group' . $item_index ); ?>>
					<?php
					if ( $print_label && $item['field_label'] ) {
						?>
							<label <?php $this->print_render_attribute_string( 'label' . $item_index ); ?>>
								<?php // PHPCS - the variable $item['field_label'] is safe.
								echo $item['field_label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</label>
						<?php
					}

					switch ( $item['field_type'] ) :
						case 'html':
							echo do_shortcode( $item['field_html'] );
							break;
						case 'textarea':
							echo $this->make_textarea_field( $item, $item_index );
							break;

						case 'select':
							echo $this->make_select_field( $item, $item_index );
							break;

						case 'radio':
						case 'checkbox':
							echo $this->make_radio_checkbox_field( $item, $item_index, $item['field_type'] );
							break;
						case 'text':
						case 'email':
						case 'url':
						case 'password':
						case 'hidden':
						case 'search':
							$this->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual' );
							?>
								<input size="1" <?php $this->print_render_attribute_string( 'input' . $item_index ); ?>>
							<?php
							break;
						default:
							$field_type = $item['field_type'];

							/**
							 * Elementor form field render.
							 *
							 * Fires when a field is rendered.
							 *
							 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
							 *
							 * @since 1.0.0
							 *
							 * @param array $item       The field value.
							 * @param int   $item_index The field index.
							 * @param Form  $this       An instance of the form.
							 */
							do_action( "elementor_tenweb/forms/render_field/{$field_type}", $item, $item_index, $this );
					endswitch;
					?>
				</div>
				<?php endforeach; ?>
				<div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php $this->print_render_attribute_string( 'button' ); ?>>
						<span <?php $this->print_render_attribute_string( 'content-wrapper' ); ?>>
							<?php if ( ! empty( $instance['button_icon'] ) || ! empty( $instance['selected_button_icon'] ) ) : ?>
								<span <?php $this->print_render_attribute_string( 'icon-align' ); ?>>
									<?php $this->render_icon_with_fallback( $instance ); ?>
									<?php if ( empty( $instance['button_text'] ) ) : ?>
										<span class="elementor-screen-only"><?php echo esc_html__( 'Submit', 'elementor-pro' ); ?></span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $instance['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php $this->print_unescaped_setting( 'button_text' ); ?></span>
							<?php endif; ?>
						</span>
					</button>
          <button type="reset" <?php echo $this->get_render_attribute_string( 'reset-button' ); ?>>
						<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
							<?php if ( ! empty( $instance['selected_reset_button_icon'] ) ) : ?>
                <span <?php echo $this->get_render_attribute_string( 'reset-icon-align' ); ?>>
									<?php Icons_Manager::render_icon( $instance['selected_reset_button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                  <?php if ( empty( $instance['reset_button_text'] ) ) : ?>
                    <span class="elementor-screen-only"><?php _e( 'Reset', 'tenweb-builder' ); ?></span>
                  <?php endif; ?>
								</span>
              <?php endif; ?>
              <?php if ( ! empty( $instance['reset_button_text'] ) ) : ?>
                <span class="elementor-button-text"><?php echo $instance['reset_button_text']; ?></span>
              <?php endif; ?>
						</span>
          </button>
				</div>
            <?php
            //10Web Customization
            if ( !empty( $instance['text_after_buttons'] ) ) : ?>
                <div class="elementor-form-text-after-description-wrapper elementor-field-group elementor-column elementor-col-100 elementor-widget-wrap">
                    <?php $this->render_text_after_buttons(); ?>
                </div>
            <?php endif;
            // End 10Web Customization
            ?>
			</div>
		</form>
		<?php
	}

    //10Web Customization
  protected function render_text_after_buttons()  {
      $editor_content = $this->get_settings_for_display( 'text_after_buttons' );

      $editor_content = $this->parse_text_editor( $editor_content );

      $this->add_render_attribute( 'text-after-buttons', 'class', [ 'elementor-text-editor', 'elementor-clearfix', 'elementor-element' ] );

      $this->add_inline_editing_attributes( 'text-after-buttons', 'advanced' );
      ?>
      <div <?php echo $this->get_render_attribute_string( 'text-after-buttons' ); ?>><?php echo $editor_content; ?></div>
      <?php
  }

  protected function content_text_after_buttons() {
        ?>
      <#
      view.addRenderAttribute( 'text_after_buttons', 'class', [ 'elementor-text-editor', 'elementor-clearfix', 'elementor-element' ] );

      view.addInlineEditingAttributes( 'text_after_buttons', 'advanced' );
      #>
      <div {{{ view.getRenderAttributeString( 'text_after_buttons' ) }}}>{{{ settings.text_after_buttons }}}</div>
      <?php
  }
    //End 10Web Customization

  protected function render_form_title() {
    $settings = $this->get_settings_for_display();

    if ( '' === $settings['form_name'] ) {
      return;
    }

    $this->add_render_attribute( 'form_name', 'class', 'elementor-form-title elementor-element elementor-heading-title' );

    if ( ! empty( $settings['title_size'] ) ) {
      $this->add_render_attribute( 'form_name', 'class', 'elementor-size-' . $settings['title_size'] );
    }

    $title = $settings['form_name'];

    $title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', \Elementor\Utils::validate_html_tag( $settings['title_tag'] ), $this->get_render_attribute_string( 'form_name' ), $title );

    echo $title_html;

    $editor_content = $this->get_settings_for_display( 'form_description' );

    $editor_content = $this->parse_text_editor( $editor_content );

    $this->add_render_attribute( 'form_description', 'class', [ 'elementor-text-editor', 'elementor-clearfix', 'elementor-element' ] );

    $this->add_inline_editing_attributes( 'form_description', 'advanced' );
    ?>
    <div <?php echo $this->get_render_attribute_string( 'form_description' ); ?>><?php echo $editor_content; ?></div>
    <?php
  }

  protected function content_template_form_title() {
    ?>
    <#
    var title = settings.form_name;

    view.addRenderAttribute( 'form_name', 'class', [ 'elementor-form-title elementor-element elementor-heading-title', 'elementor-size-' + settings.title_size ] );

    view.addInlineEditingAttributes( 'form_name' );

    var headerSizeTag = elementor.helpers.validateHTMLTag( settings.title_tag ),
    title_html = '<' + headerSizeTag  + ' ' + view.getRenderAttributeString( 'form_name' ) + '>' + title + '</' + headerSizeTag + '>';

    print( title_html );

    view.addRenderAttribute( 'form_description', 'class', [ 'elementor-text-editor', 'elementor-clearfix', 'elementor-element' ] );

    view.addInlineEditingAttributes( 'form_description', 'advanced' );
    #>
    <div {{{ view.getRenderAttributeString( 'form_description' ) }}}>{{{ settings.form_description }}}</div>
    <?php
  }

	/**
	 * Render Form widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		$submit_text = esc_html__( 'Submit', 'elementor-pro' );
    $reset_text = esc_html__( 'Reset', 'tenweb-builder' );
		?>
		<form class="elementor-form" id="{{settings.form_id}}" name="{{settings.form_name}}">
			<div class="elementor-form-fields-wrapper elementor-labels-{{settings.label_position}}">
        <?php
        /**
         * 10Web Builder
         */
        ?>
        <#
        if ( settings.display_form_title_description ) {
        #>
        <div class="elementor-form-title-description-wrapper elementor-field-group elementor-column elementor-col-100 elementor-widget-wrap elementor-widget-heading">
          <?php
          $this->content_template_form_title();
          ?>
        </div>
        <#
        }
					for ( var i in settings.form_fields ) {
						var item = settings.form_fields[ i ];
						item = elementor.hooks.applyFilters( 'elementor_tenweb/forms/content_template/item', item, i, settings );

						var options = item.field_options ? item.field_options.split( '\n' ) : [],
							itemClasses = _.escape( item.css_classes ),
							labelVisibility = '',
							placeholder = '',
							required = '',
							inputField = '',
							multiple = '',
							fieldGroupClasses = 'elementor-field-group elementor-column elementor-field-type-' + item.field_type,
							printLabel = settings.show_labels && ! [ 'hidden', 'html', 'step' ].includes( item.field_type );

						fieldGroupClasses += ' elementor-col-' + ( ( '' !== item.width ) ? item.width : '100' );

						if ( item.width_tablet ) {
							fieldGroupClasses += ' elementor-md-' + item.width_tablet;
						}

						if ( item.width_mobile ) {
							fieldGroupClasses += ' elementor-sm-' + item.width_mobile;
						}

						if ( item.required ) {
							required = 'required';
							fieldGroupClasses += ' elementor-field-required';

							if ( settings.mark_required ) {
								fieldGroupClasses += ' elementor-mark-required';
							}
						}

						if ( item.placeholder ) {
							placeholder = 'placeholder="' + _.escape( item.placeholder ) + '"';
						}

						if ( item.allow_multiple ) {
							multiple = ' multiple';
							fieldGroupClasses += ' elementor-field-type-' + item.field_type + '-multiple';
						}

						switch ( item.field_type ) {
							case 'step':
								inputField = `<div
									class="e-field-step elementor-hidden"
									data-label="${ item.field_label }"
									data-previousButton="${ item.previous_button || '' }"
									data-nextButton="${ item.next_button || '' }"
									data-iconUrl="${ 'svg' === item.selected_icon.library && item.selected_icon.value ? item.selected_icon.value.url : '' }"
									data-iconLibrary="${ 'svg' !== item.selected_icon.library && item.selected_icon.value ? item.selected_icon.value : '' }"></div>`;
								break;
							case 'html':
								inputField = item.field_html;
								break;

							case 'textarea':
								inputField = '<textarea class="elementor-field elementor-field-textual elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" rows="' + item.rows + '" ' + required + ' ' + placeholder + '>' + item.field_value + '</textarea>';
								break;

							case 'select':
								if ( options ) {
									var size = '';
									if ( item.allow_multiple && item.select_size ) {
										size = ' size="' + item.select_size + '"';
									}
									inputField = '<div class="elementor-field elementor-select-wrapper ' + itemClasses + '">';
									inputField += '<select class="elementor-field-textual elementor-size-' + settings.input_size + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + multiple + size + ' >';
									for ( var x in options ) {
										var option_value = options[ x ];
										var option_label = options[ x ];
										var option_id = 'form_field_option' + i + x;

										if ( options[ x ].indexOf( '|' ) > -1 ) {
											var label_value = options[ x ].split( '|' );
											option_label = label_value[0];
											option_value = label_value[1];
										}

										view.addRenderAttribute( option_id, 'value', option_value );
										if ( item.field_value.split( ',' ) .indexOf( option_value ) ) {
											view.addRenderAttribute( option_id, 'selected', 'selected' );
										}
										inputField += '<option ' + view.getRenderAttributeString( option_id ) + '>' + option_label + '</option>';
									}
									inputField += '</select></div>';
								}
								break;

							case 'radio':
							case 'checkbox':
								if ( options ) {
									var multiple = '';

									if ( 'checkbox' === item.field_type && options.length > 1 ) {
										multiple = '[]';
									}

									inputField = '<div class="elementor-field-subgroup ' + itemClasses + ' ' + item.inline_list + '">';

									for ( var x in options ) {
										var option_value = options[ x ];
										var option_label = options[ x ];
										var option_id = 'form_field_' + item.field_type + i + x;
										if ( options[x].indexOf( '|' ) > -1 ) {
											var label_value = options[x].split( '|' );
											option_label = label_value[0];
											option_value = label_value[1];
										}

										view.addRenderAttribute( option_id, {
											value: option_value,
											type: item.field_type,
											id: 'form_field_' + i + '-' + x,
											name: 'form_field_' + i + multiple
										} );

										if ( option_value ===  item.field_value ) {
											view.addRenderAttribute( option_id, 'checked', 'checked' );
										}

										inputField += '<span class="elementor-field-option"><input ' + view.getRenderAttributeString( option_id ) + ' ' + required + '> ';
										inputField += '<label for="form_field_' + i + '-' + x + '">' + option_label + '</label></span>';

									}

									inputField += '</div>';
								}
								break;

							case 'text':
							case 'email':
							case 'url':
							case 'password':
							case 'number':
							case 'search':
								itemClasses = 'elementor-field-textual ' + itemClasses;
								inputField = '<input size="1" type="' + item.field_type + '" value="' + item.field_value + '" class="elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' >';
								break;
							default:
								inputField = elementor.hooks.applyFilters( 'elementor_tenweb/forms/content_template/field/' + item.field_type, '', item, i, settings );
						}

						if ( inputField ) {
							#>
							<div class="{{ fieldGroupClasses }}">

								<# if ( printLabel && item.field_label ) { #>
									<label class="elementor-field-label" for="form_field_{{ i }}" {{{ labelVisibility }}}>{{{ item.field_label }}}</label>
								<# } #>

								{{{ inputField }}}
							</div>
							<#
						}
					}


					var buttonClasses = 'elementor-field-group elementor-column elementor-field-type-submit e-form__buttons';

					buttonClasses += ' elementor-col-' + ( ( '' !== settings.button_width ) ? settings.button_width : '100' );

          /* 10Web Builder */
          buttonClasses += ( ( 'yes' === settings.button_show ) ? ( ( 'yes' === settings.reset_button_show ) ? ' elementor-reset-button-active' : '' ) : ' elementor-hidden' );
          var resetButtonHide = ( ( 'yes' === settings.reset_button_show ) ? '' : ' elementor-hidden' );

					if ( settings.button_width_tablet ) {
						buttonClasses += ' elementor-md-' + settings.button_width_tablet;
					}

					if ( settings.button_width_mobile ) {
						buttonClasses += ' elementor-sm-' + settings.button_width_mobile;
					}

					var iconHTML = elementor.helpers.renderIcon( view, settings.selected_button_icon, { 'aria-hidden': true }, 'i' , 'object' ),
						migrated = elementor.helpers.isIconMigrated( settings, 'selected_button_icon' );
          var iconResetHTML = elementor.helpers.renderIcon( view, settings.selected_reset_button_icon, { 'aria-hidden': true }, 'i' , 'object' );
					#>

					<div class="{{ buttonClasses }}">
						<button id="{{ settings.button_css_id }}" type="submit" class="elementor-button elementor-size-{{ settings.button_size }} elementor-button-{{ settings.button_type }} elementor-animation-{{ settings.button_hover_animation }}">
							<span>
								<# if ( settings.button_icon || settings.selected_button_icon ) { #>
									<span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
										<# if ( iconHTML && iconHTML.rendered && ( ! settings.button_icon || migrated ) ) { #>
											{{{ iconHTML.value }}}
										<# } else { #>
											<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
										<# } #>
										<span class="elementor-screen-only"><?php echo $submit_text; ?></span>
									</span>
								<# } #>

								<# if ( settings.button_text ) { #>
									<span class="elementor-button-text">{{{ settings.button_text }}}</span>
								<# } #>
							</span>
						</button>
            <button type="reset" class="elementor-button elementor-size-{{ settings.button_size }} elementor-button-{{ settings.button_type }} elementor-animation-{{ settings.button_hover_animation }} {{resetButtonHide}}">
							<span>
								<# if ( settings.selected_reset_button_icon ) { #>
									<span class="elementor-button-icon elementor-align-icon-{{ settings.reset_button_icon_align }}">
                    <# if ( iconResetHTML && iconResetHTML.rendered ) { #>
											{{{ iconResetHTML.value }}}
                    <# } #>
										<span class="elementor-screen-only"><?php echo $reset_text; ?></span>
									</span>
								<# } #>

								<# if ( settings.reset_button_text ) { #>
									<span class="elementor-button-text">{{{ settings.reset_button_text }}}</span>
								<# } #>
							</span>
            </button>
					</div>
                <?php //10Web Customization ?>
                <#
                if ( settings.text_after_buttons ) {
                #>
                <div class="elementor-form-text-after-description-wrapper elementor-field-group elementor-column elementor-col-100 elementor-widget-wrap">
                    <?php
                    echo $this->content_text_after_buttons();
                    ?>
                </div>
                <# } #>
                <?php //End 10Web Customization ?>
			</div>
		</form>
		<?php
	}

	public function get_group_name() {
		return 'forms';
	}
}
