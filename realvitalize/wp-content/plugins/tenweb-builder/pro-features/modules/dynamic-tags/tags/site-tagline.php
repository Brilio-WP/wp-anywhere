<?php
namespace Tenweb_Builder\ElementorPro\Modules\DynamicTags\Tags;

use Tenweb_Builder\ElementorPro\Modules\DynamicTags\Tags\Base\Tag;
use Tenweb_Builder\ElementorPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Tagline extends Tag {
	public function get_name() {
		return 'site-tagline';
	}

	public function get_title() {
		return esc_html__( 'Site Tagline', 'elementor-pro' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_bloginfo( 'description' ) );
	}
}
