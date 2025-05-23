<?php
namespace Tenweb_Builder\Widgets\Posts_Skins\Widgets;

use Elementor\Controls_Manager;
use Tenweb_Builder\ElementorPro\Modules\QueryControl\Module as Module_Query;
use Tenweb_Builder\ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use Tenweb_Builder\Widget_Slider;
use Tenweb_Builder\Widgets\Posts_Skins\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Posts
 */
class Posts extends Posts_Base {

	public function get_name() {
		return 'tenweb-posts';
	}

	public function get_title() {
		return esc_html__( 'Posts', 'tenweb-builder' );
	}

    public function get_icon(){
        return 'twbb-posts twbb-widget-icon';
    }

    public function get_categories(){
        return array('tenweb-widgets');
    }


    public function get_keywords() {
		return [ 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
	}

	public function on_import( $element ) {
		if ( isset( $element['settings']['tenweb-posts_post_type'] ) && ! get_post_type_object( $element['settings']['tenweb-posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	protected function register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
		$this->add_skin( new Skins\Skin_On_Image( $this ) );
		$this->add_skin( new Skins\Skin_Image_Left( $this ) );
		$this->add_skin( new Skins\Skin_Cards( $this ) );
	}

	protected function register_controls() {
		parent::register_controls();

		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
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
            'of' => 'section_design_content',
        ]);

        $this->update_control('pagination_type', ['condition' => [
            'slider_view!' => 'yes',
        ]]);
	    $this->update_responsive_control('columns', ['condition' => [
		    'slider_view!' => 'yes',
	    ]]);
	    $this->update_responsive_control('slides_per_view', ['label' =>
          __( 'Posts per Slide', 'tenweb-builder' )
	    ]);
        $this->update_responsive_control('arrows_border_radius', [
            'default' => [
                'top' => 50,
                'right' => 50,
                'bottom' => 50,
                'left' => 50,
                'unit' => '%', // Default unit
            ],
            'tablet_default' => [
                'top' => 50,
                'right' => 50,
                'bottom' => 50,
                'left' => 50,
                'unit' => '%',
            ],
            'mobile_default' => [
                'top' => 50,
                'right' => 50,
                'bottom' => 50,
                'left' => 50,
                'unit' => '%',
            ],
	    ]);

        // Add hidden control for kipping first edit after control change
        $this->start_injection([
            'at' => 'after',
            'of' => '_skin',
        ]);
        $this->add_control(
            'slider_view_option_changed',
            [
                'type' => Controls_Manager::HIDDEN,
                'prefix_class' => 'twbb_slider_options_changed-',
                'default' => 'default',
            ]
        );
        $this->end_injection();
    }

	/**
	 * Get Query Name
	 *
	 * Returns the query control name used in the widget's main query.
	 *
	 * @since 3.8.0
	 *
	 * @return string
	 */
	public function get_query_name() {
		return $this->get_name();
	}

	public function query_posts() {
		$query_args = [
			'posts_per_page' => $this->get_posts_per_page_value(),
			'paged' => $this->get_current_page(),
			'has_custom_pagination' => $this->is_allow_to_use_custom_page_option(),
		];

		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$this->query = $elementor_query->get_query( $this, $this->get_query_name(), $query_args, [] );
	}

	/**
	 * Get Posts Per Page Value
	 *
	 * Returns the value of the Posts Per Page control of the widget. This method was created because in some cases,
	 * the control is registered in the widget, and in some cases, it is registered in a widget skin.
	 *
	 * @since 3.8.0
	 * @access protected
	 *
	 * @return mixed
	 */
	protected function get_posts_per_page_value() {
		return $this->get_current_skin()->get_instance_value( 'posts_per_page' );
	}

	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => esc_html__( 'Select Posts', 'tenweb-builder' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name' => $this->get_name(),
				'presets' => [ 'full' ],
				'exclude' => [ //phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
					'posts_per_page', //use the one from Layout section
				],
			]
		);

		$this->end_controls_section();
	}
}

\Elementor\Plugin::instance()->widgets_manager->register(new \Tenweb_Builder\Widgets\Posts_Skins\Widgets\Posts());
