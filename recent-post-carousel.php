<?php
/*
 * PlugIn Name: Recent Post Carousel Aye
 * Description: A carousel to display recent posts (newest first by date). Each group is a container for any WPBakery element.
 * Author: Aye Chan Mon
 * Version: 1.3.0
 */

defined('ABSPATH') || exit;
define( 'RPC_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'RPC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'init', 'rpc_load_classes', 5 );
add_action( 'vc_before_init', 'rpc_register_elements' );
add_action( 'wp_enqueue_scripts', 'rpc_enqueue_assets' );
add_action( 'vc_backend_editor_enqueue_js_css',  'rpc_enqueue_assets' );
add_action( 'vc_frontend_editor_enqueue_js_css', 'rpc_enqueue_assets' );
define( 'PRC_VERSION', '1.1.0' );

function rpc_load_classes() {
	if ( ! class_exists( 'WPBakeryShortCodesContainer' ) ) { return; }
	require_once RPC_PLUGIN_PATH . 'includes/class-rpc-carousel.php';
	require_once RPC_PLUGIN_PATH . 'includes/class-rpc-group.php';
}

function rpc_register_elements() {
	if ( ! function_exists( 'vc_map' ) ) { return; }

	vc_map( array(
		'name'  => __( 'Recent Post Carousel Aye (Grouped)', 'rpc' ),
		'base'  => 'rpc_carousel',
		'icon'  => 'icon-wpb-slider',
		'category' => __( 'Content', 'rpc' ),
		'description' => __( 'Carousel of grouped content, newest first', 'rpc' ),
		'is_container'  => true,
		'show_settings_on_create' => true,
		'as_parent' => array( 'only' => 'rpc_group' ),
		'default_content' => '[rpc_group group_date="' . date( 'Y-m-d' ) . '"][/rpc_group]',
		'js_view' => 'VcColumnView',
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Groups Per Page', 'rpc' ),
				'param_name' => 'groups_per_page',
				'value' => '5',
				'description' => __( 'How many groups show per page before paging to the next set.', 'rpc' ),
			),
			array(
				'type'  => 'checkbox',
				'heading'=> __( 'Show Pagination Dots', 'rpc' ),
				'param_name' => 'show_dots',
				'value' => array( __( 'Yes', 'rpc' ) => 'yes' ),
				'std'  => 'yes',
			),
			array(
				'type' => 'checkbox',
				'heading' => __( 'Autoplay', 'rpc' ),
				'param_name' => 'autoplay',
				'value' => array( __( 'Yes', 'rpc' ) => 'yes' ),
			),
			array(
				'type' => 'textfield',
				'heading'=> __( 'Autoplay Delay (ms)', 'rpc' ),
				'param_name' => 'autoplay_delay',
				'value' => '5000',
				'dependency' => array( 'element' => 'autoplay', 'value' => array( 'yes' ) ),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra CSS Class', 'rpc' ),
				'param_name' => 'el_class',
				'value'  => '',
			),
		),
	) );

	vc_map( array(
		'name'  => __( 'Carousel Group', 'rpc' ),
		'base' => 'rpc_group',
		'icon'  => 'icon-wpb-single-image',
		'category'  => __( 'Content', 'rpc' ),
		'description'  => __( 'One slide group. Drop any WPBakery elements inside.', 'rpc' ),
		'is_container'  => true,
		'show_settings_on_create' => true,
		'content_element' => true,
		'as_child'  => array( 'only' => 'rpc_carousel' ),
		'js_view'  => 'VcColumnView',
		'params'   => array(
			array(
				'type'  => 'textfield',
				'heading' => __( 'Group Date', 'rpc' ),
				'param_name'  => 'group_date',
				'value' => date( 'Y-m-d' ),
				'admin_label' => true,
				'description' => __( 'Format YYYY-MM-DD. Newest date shows first.', 'rpc' ),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Sort Priority (optional)', 'rpc' ),
				'param_name'  => 'sort_priority',
				'value' => '',
				'description' => __( 'Number. Higher wins ties on the same date.', 'rpc' ),
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra CSS Class', 'rpc' ),
				'param_name' => 'el_class',
				'value' => '',
			),
		),
	) );
}
if ( ! function_exists( 'rpc_enqueue_assets' ) ):
	function rpc_enqueue_assets() {
		wp_enqueue_style(  'rpc-bootstrap-css', RPC_PLUGIN_URL . 'assets/css/bootstrap.min.css', array(), '4.6.2' );
		wp_enqueue_script( 'rpc-bootstrap-js',  RPC_PLUGIN_URL . 'assets/js/bootstrap.bundle.min.js', array( 'jquery' ), '4.6.2', true );

		wp_enqueue_style(  'rpc-style',  RPC_PLUGIN_URL . 'assets/css/recent-post-carousel.css', array( 'rpc-bootstrap-css' ), PRC_VERSION );
		wp_enqueue_script( 'rpc-script', RPC_PLUGIN_URL . 'assets/js/recent-post-carousel.js',  array( 'jquery', 'rpc-bootstrap-js' ), PRC_VERSION, true );
	}
endif;
