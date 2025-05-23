<?php
namespace Tenweb_Builder;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Login extends Widget_Base {

  public function get_name() {
    return Builder::$prefix . 'login';
  }

  public function get_title() {
    return __( 'Login', 'tenweb-builder' );
  }

  public function get_icon() {
    return 'twbb-login twbb-widget-icon';
  }

  public function get_categories() {
    return [ 'tenweb-widgets' ];
  }

  protected function register_controls() {
    $this->start_controls_section(
      'section_fields_content',
      [
        'label' => __( 'Form Fields', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'show_labels',
      [
        'label' => __( 'Label', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'label_off' => __( 'Hide', 'tenweb-builder' ),
        'label_on' => __( 'Show', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'input_size',
      [
        'label' => __( 'Input Size', 'tenweb-builder' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'xs' => __( 'Extra Small', 'tenweb-builder' ),
          'sm' => __( 'Small', 'tenweb-builder' ),
          'md' => __( 'Medium', 'tenweb-builder' ),
          'lg' => __( 'Large', 'tenweb-builder' ),
          'xl' => __( 'Extra Large', 'tenweb-builder' ),
        ],
        'default' => 'sm',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_button_content',
      [
        'label' => __( 'Button', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'button_text',
      [
        'label' => __( 'Text', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXT,
        'default' => __( 'Log In', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'button_size',
      [
        'label' => __( 'Size', 'tenweb-builder' ),
        'type' => Controls_Manager::SELECT,
        'options' => [
          'xs' => __( 'Extra Small', 'tenweb-builder' ),
          'sm' => __( 'Small', 'tenweb-builder' ),
          'md' => __( 'Medium', 'tenweb-builder' ),
          'lg' => __( 'Large', 'tenweb-builder' ),
          'xl' => __( 'Extra Large', 'tenweb-builder' ),
        ],
        'default' => 'sm',
      ]
    );

    $this->add_responsive_control(
      'align',
      [
        'label' => __( 'Alignment', 'tenweb-builder' ),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'start' => [
            'title' => __( 'Left', 'tenweb-builder' ),
            'icon' => 'fa fa-align-left',
          ],
          'center' => [
            'title' => __( 'Center', 'tenweb-builder' ),
            'icon' => 'fa fa-align-center',
          ],
          'end' => [
            'title' => __( 'Right', 'tenweb-builder' ),
            'icon' => 'fa fa-align-right',
          ],
          'stretch' => [
            'title' => __( 'Justified', 'tenweb-builder' ),
            'icon' => 'fa fa-align-justify',
          ],
        ],
        'prefix_class' => 'elementor%s-button-align-',
        'default' => '',
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_login_content',
      [
        'label' => __( 'Additional Options', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'redirect_after_login',
      [
        'label' => __( 'Redirect After Login', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => '',
        'label_off' => __( 'Off', 'tenweb-builder' ),
        'label_on' => __( 'On', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'redirect_url',
      [
        'type' => Controls_Manager::URL,
        'show_label' => false,
        'show_external' => false,
        'separator' => false,
        'placeholder' => __( 'https://your-link.com', 'tenweb-builder' ),
        'description' => __( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'tenweb-builder' ),
        'condition' => [
          'redirect_after_login' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'show_lost_password',
      [
        'label' => __( 'Lost your password?', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'label_off' => __( 'Hide', 'tenweb-builder' ),
        'label_on' => __( 'Show', 'tenweb-builder' ),
      ]
    );

    if ( get_option( 'users_can_register' ) ) {
      $this->add_control(
        'show_register',
        [
          'label' => __( 'Register', 'tenweb-builder' ),
          'type' => Controls_Manager::SWITCHER,
          'default' => 'yes',
          'label_off' => __( 'Hide', 'tenweb-builder' ),
          'label_on' => __( 'Show', 'tenweb-builder' ),
        ]
      );
    }

    $this->add_control(
      'show_remember_me',
      [
        'label' => __( 'Remember Me', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'label_off' => __( 'Hide', 'tenweb-builder' ),
        'label_on' => __( 'Show', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'show_logged_in_message',
      [
        'label' => __( 'Logged in Message', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'default' => 'yes',
        'label_off' => __( 'Hide', 'tenweb-builder' ),
        'label_on' => __( 'Show', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'custom_labels',
      [
        'label' => __( 'Custom Label', 'tenweb-builder' ),
        'type' => Controls_Manager::SWITCHER,
        'condition' => [
          'show_labels' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'user_label',
      [
        'label' => __( 'Username Label', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXT,
        'default' => __( ' Username or Email Address', 'tenweb-builder' ),
        'condition' => [
          'show_labels' => 'yes',
          'custom_labels' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'user_placeholder',
      [
        'label' => __( 'Username Placeholder', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXT,
        'default' => __( ' Username or Email Address', 'tenweb-builder' ),
        'condition' => [
          'show_labels' => 'yes',
          'custom_labels' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'password_label',
      [
        'label' => __( 'Password Label', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXT,
        'default' => __( 'Password', 'tenweb-builder' ),
        'condition' => [
          'show_labels' => 'yes',
          'custom_labels' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'password_placeholder',
      [
        'label' => __( 'Password Placeholder', 'tenweb-builder' ),
        'type' => Controls_Manager::TEXT,
        'default' => __( 'Password', 'tenweb-builder' ),
        'condition' => [
          'show_labels' => 'yes',
          'custom_labels' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style',
      [
        'label' => __( 'Form', 'tenweb-builder' ),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'row_gap',
      [
        'label' => __( 'Row Gap', 'tenweb-builder' ),
        'type' => Controls_Manager::SLIDER,
        'default' => [
          'size' => '10',
        ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 60,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'links_color',
      [
        'label' => __( 'Link Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group > a' => 'color: {{VALUE}};',
        ],
          'global' => [
              'default' => Global_Colors::COLOR_TEXT,
          ],
      ]
    );

    $this->add_control(
      'links_hover_color',
      [
        'label' => __( 'Link Hover Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group > a:hover' => 'color: {{VALUE}};',
        ],
          'global' => [
              'default' => Global_Colors::COLOR_ACCENT,
          ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style_labels',
      [
        'label' => __( 'Label', 'tenweb-builder' ),
        'tab' => Controls_Manager::TAB_STYLE,
        'condition' => [
          'show_labels!' => '',
        ],
      ]
    );

    $this->add_control(
      'label_spacing',
      [
        'label' => __( 'Spacing', 'tenweb-builder' ),
        'type' => Controls_Manager::SLIDER,
        'default' => [
          'size' => '0',
        ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 60,
          ],
        ],
        'selectors' => [
          'body {{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
          // for the label position = above option
        ],
      ]
    );

    $this->add_control(
      'label_color',
      [
        'label' => __( 'Text Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-form-fields-wrapper' => 'color: {{VALUE}};',
          '{{WRAPPER}} .elementor-form-fields-wrapper label' => 'color: {{VALUE}};',
        ],
          'global' => [
              'default' => Global_Colors::COLOR_TEXT,
          ],
      ]
    );

    $this->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => 'label_typography',
        'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper',
          'global' => [
              'default' => Global_Typography::TYPOGRAPHY_TEXT,
          ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_field_style',
      [
        'label' => __( 'Fields', 'tenweb-builder' ),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'field_text_color',
      [
        'label' => __( 'Text Color', 'tenweb-builder' ),
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
        'label' => __( 'Background Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'default' => '#ffffff',
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'field_border_color',
      [
        'label' => __( 'Border Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
          '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'field_border_width',
      [
        'label' => __( 'Border Width', 'tenweb-builder' ),
        'type' => Controls_Manager::DIMENSIONS,
        'placeholder' => '1',
        'size_units' => [ 'px' ],
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'field_border_radius',
      [
        'label' => __( 'Border Radius', 'tenweb-builder' ),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%' ],
        'selectors' => [
          '{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
      'section_button_style',
      [
        'label' => __( 'Button', 'tenweb-builder' ),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->start_controls_tabs( 'tabs_button_style' );

    $this->start_controls_tab(
      'tab_button_normal',
      [
        'label' => __( 'Normal', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'button_text_color',
      [
        'label' => __( 'Text Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
        ],
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

    $this->add_control(
      'button_background_color',
      [
        'label' => __( 'Background Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
          'global' => [
              'default' => Global_Colors::COLOR_ACCENT,
          ],
        'selectors' => [
          '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      Group_Control_Border::get_type(), [
                                        'name' => 'button_border',
                                        'placeholder' => '1px',
                                        'default' => '1px',
                                        'selector' => '{{WRAPPER}} .elementor-button',
                                        'separator' => 'before',
                                      ]
    );

    $this->add_control(
      'button_border_radius',
      [
        'label' => __( 'Border Radius', 'tenweb-builder' ),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%' ],
        'selectors' => [
          '{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'button_text_padding',
      [
        'label' => __( 'Text Padding', 'tenweb-builder' ),
        'type' => Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors' => [
          '{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_tab();

    $this->start_controls_tab(
      'tab_button_hover',
      [
        'label' => __( 'Hover', 'tenweb-builder' ),
      ]
    );

    $this->add_control(
      'button_hover_color',
      [
        'label' => __( 'Text Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_background_hover_color',
      [
        'label' => __( 'Background Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_hover_border_color',
      [
        'label' => __( 'Border Color', 'tenweb-builder' ),
        'type' => Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
        ],
        'condition' => [
          'button_border_border!' => '',
        ],
      ]
    );

    $this->add_control(
      'button_hover_animation',
      [
        'label' => __( 'Animation', 'tenweb-builder' ),
        'type' => Controls_Manager::HOVER_ANIMATION,
      ]
    );

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->end_controls_section();
  }

  private function form_fields_render_attributes() {
    $settings = $this->get_settings();

    if ( ! empty( $settings['button_size'] ) ) {
      $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
    }

    if ( $settings['button_hover_animation'] ) {
      $this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
    }

    $this->add_render_attribute(
      [
        'wrapper' => [
          'class' => [
            'elementor-form-fields-wrapper',
          ],
        ],
        'field-group' => [
          'class' => [
            'elementor-field-type-text',
            'elementor-field-group',
            'elementor-column',
            'elementor-col-100',
          ],
        ],
        'submit-group' => [
          'class' => [
            'elementor-field-group',
            'elementor-column',
            'elementor-field-type-submit',
            'elementor-col-100',
          ],
        ],

        'button' => [
          'class' => [
            'elementor-button',
          ],
          'name' => 'wp-submit',
        ],
        'user_label' => [
          'for' => 'user',
        ],
        'user_input' => [
          'type' => 'text',
          'name' => 'log',
          'id' => 'user',
          'placeholder' => $settings['user_placeholder'],
          'class' => [
            'elementor-field',
            'elementor-field-textual',
            'elementor-size-' . $settings['input_size'],
          ],
        ],
        'password_input' => [
          'type' => 'password',
          'name' => 'pwd',
          'id' => 'password',
          'placeholder' => $settings['password_placeholder'],
          'class' => [
            'elementor-field',
            'elementor-field-textual',
            'elementor-size-' . $settings['input_size'],
          ],
        ],
        //TODO: add unique ID
        'label_user' => [
          'for' => 'user',
          'class' => 'elementor-field-label',
        ],

        'label_password' => [
          'for' => 'password',
          'class' => 'elementor-field-label',
        ],
      ]
    );

    if ( ! $settings['show_labels'] ) {
      $this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
    }

    $this->add_render_attribute( 'field-group', 'class', 'elementor-field-required' )
      ->add_render_attribute( 'input', 'required', true )
      ->add_render_attribute( 'input', 'aria-required', 'true' );

  }

  protected function render() {
    $settings = $this->get_settings();
    $current_url = remove_query_arg( 'fake_arg' );

    if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
      $redirect_url = $settings['redirect_url']['url'];
    } else {
      $redirect_url = $current_url;
    }

    $editor = \Elementor\Plugin::instance()->editor;

    // Set edit mode as false, so don't render settings and etc.
    $is_edit_mode = $editor->is_edit_mode();

    if ( is_user_logged_in() && ! $is_edit_mode ) {
      if ( 'yes' === $settings['show_logged_in_message'] ) {
        $current_user = wp_get_current_user();

        echo '<div class="elementor-login">' .
          sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'tenweb-builder' ), $current_user->display_name, wp_logout_url( $current_url ) ) . //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
          '</div>';
      }

      return;
    }

    $this->form_fields_render_attributes();
    ?>
    <form class="elementor-login elementor-form" method="post" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>">
      <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url ); ?>">
      <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
        <div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
          <?php
          if ( $settings['show_labels'] ) {
            ?><label <?php $this->print_render_attribute_string( 'user_label' );?>> <?php $this->print_unescaped_setting( 'user_label' ); ?></label>
          <?php } ?>

          <input size="1" <?php $this->print_render_attribute_string( 'user_input' );?> >

        </div>
        <div <?php $this->print_render_attribute_string( 'field-group' ); ?>>
          <?php
          if ( $settings['show_labels'] ) :
              ?><label <?php $this->print_render_attribute_string( 'password_label' );?>> <?php $this->print_unescaped_setting( 'password_label');?> </label>
          <?php endif; ?>
            <input size="1" <?php $this->print_render_attribute_string( 'password_input' );?>>
        </div>

        <?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
          <div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
            <label for="elementor-login-remember-me">
              <input type="checkbox" id="elementor-login-remember-me" name="rememberme" value="forever">
              <?php echo esc_html__( 'Remember Me', 'tenweb-builder' ); ?>
            </label>
          </div>
        <?php endif; ?>

        <div <?php $this->print_render_attribute_string( 'submit-group' ); ?>>
          <button type="submit" <?php $this->print_render_attribute_string( 'button' ); ?>>
            <?php if ( ! empty( $settings['button_text'] ) ) : ?>
              <span class="elementor-button-text"><?php $this->print_unescaped_setting( 'button_text'); ?></span>
            <?php endif; ?>
          </button>
        </div>

        <?php
        $show_lost_password = 'yes' === $settings['show_lost_password'];
        $show_register = get_option( 'users_can_register' ) && 'yes' === $settings['show_register'];

        if ( $show_lost_password || $show_register ) : ?>
          <div class="elementor-field-group elementor-column elementor-col-100">
            <?php if ( $show_lost_password ) : ?>
              <a class="elementor-lost-password" href="<?php echo esc_url(wp_lostpassword_url( $redirect_url )); ?>">
                <?php echo esc_html__( 'Lost your password?', 'tenweb-builder' ); ?>
              </a>
            <?php endif; ?>

            <?php if ( $show_register ) : ?>
              <?php if ( $show_lost_password ) : ?>
                <span class="elementor-login-separator"> | </span>
              <?php endif; ?>
              <a class="elementor-register" href="<?php echo esc_url(wp_registration_url()); ?>">
                <?php echo esc_html__( 'Register', 'tenweb-builder' ); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </form>
    <?php
  }
//phpcs:disable
  protected function content_template() {
    ?>
    <div class="elementor-login elementor-form">
      <div class="elementor-form-fields-wrapper">
        <#
        fieldGroupClasses = 'elementor-field-group elementor-column elementor-col-100 elementor-field-type-text';
        #>
        <div class="{{ fieldGroupClasses }}">
          <# if ( settings.show_labels ) { #>
          <label class="elementor-field-label" for="user" >{{{ settings.user_label }}}</label>
          <# } #>
          <input size="1" type="text" id="user" placeholder="{{ settings.user_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
        </div>
        <div class="{{ fieldGroupClasses }}">
          <# if ( settings.show_labels ) { #>
          <label class="elementor-field-label" for="password" >{{{ settings.password_label }}}</label>
          <# } #>
          <input size="1" type="password" id="password" placeholder="{{ settings.password_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
        </div>

        <# if ( settings.show_remember_me ) { #>
        <div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
          <label for="elementor-login-remember-me">
            <input type="checkbox" id="elementor-login-remember-me" name="rememberme" value="forever">
            <?php echo __( 'Remember Me', 'tenweb-builder' ); ?>
          </label>
        </div>
        <# } #>

        <div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100">
          <button type="submit" class="elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{settings.button_hover_animation}}">
          <# if ( settings.button_text ) { #>
            <span class="elementor-button-text">{{ settings.button_text }}</span>
            <# } #>
          </button>
        </div>

        <# if ( settings.show_lost_password || settings.show_register ) { #>
        <div class="elementor-field-group elementor-column elementor-col-100">
          <# if ( settings.show_lost_password ) { #>
          <a class="elementor-lost-password" href="<?php echo wp_lostpassword_url(); ?>">
            <?php echo __( 'Lost your password?', 'tenweb-builder' ); ?>
          </a>
          <# } #>

          <?php if ( get_option( 'users_can_register' ) ) { ?>
            <# if ( settings.show_register ) { #>
            <# if ( settings.show_lost_password ) { #>
            <span class="elementor-login-separator"> | </span>
            <# } #>
            <a class="elementor-register" href="<?php echo wp_registration_url(); ?>">
              <?php echo __( 'Register', 'tenweb-builder' ); ?>
            </a>
            <# } #>
          <?php } ?>
        </div>
        <# } #>
      </div>
    </div>
    <?php
  }
    //phpcs:enable

  public function render_plain_content() {}
}

\Elementor\Plugin::instance()->widgets_manager->register(new Login());
