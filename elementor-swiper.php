<?php
/**
 * Plugin Name: Elementor Swiper
 * Description: Swiper widgets for Elementor.
 * Version:     1.0.0
 * Author:      Skuzzy Topten
 * Author URI:  https://developers.elementor.com/
 * Text Domain: elementor-addon
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.25.9
 * Elementor Pro tested up to: 3.25.9
 */

/**
 * Register Elementor test widgets.
 */
function register_swiper_post_carousel_widget( $widgets_manager ) {

	require_once( __DIR__ . '/includes/widgets/swiper-post-carousel.php' );

	$widgets_manager->register( new \Elementor_Swiper_Widget() );

}
add_action( 'elementor/widgets/register', 'register_swiper_post_carousel_widget' );

/**
 * Register scripts and styles for Elementor test widgets.
 */
function elementor_test_widgets_dependencies() {

	/* Scripts */
	wp_register_script( 'swiper-post-carousel-widget-script-1', plugins_url( 'assets/js/swiper-bundle.min.js', __FILE__ ) );

	/* Styles */
	wp_register_style( 'swiper-post-carousel-widget-style-1', plugins_url( 'assets/css/swiper-bundle.min.css', __FILE__ ) );

}
add_action( 'wp_enqueue_scripts', 'elementor_test_widgets_dependencies' );
