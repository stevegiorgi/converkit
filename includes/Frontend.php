<?php

namespace App;

/**
 * Frontend Pages Handler
 */
class Frontend
{

	public function __construct()
	{
		add_shortcode('vue-app', [$this, 'render_admin']);
		add_action('wp_enqueue_scripts', [$this, 'nonce_enqueue_scripts']);
	}


	public function nonce_enqueue_scripts()
	{
		if (is_user_logged_in()) {
			$wpApiSettings = json_encode(array(
				'root' => esc_url_raw(rest_url()),
				'nonce' => wp_create_nonce('wp_rest')
			));
			$wpApiSettings = "var wpApiSettings = ${wpApiSettings};";
			wp_register_script('dashboard-wp-api', '');
			wp_enqueue_script('dashboard-wp-api');
			wp_add_inline_script('dashboard-wp-api', $wpApiSettings);
		}
	}


	/**
	 * Render frontend app
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	public function render_frontend($atts, $content = '')
	{
		wp_enqueue_style('rdashboard-frontend');
		wp_enqueue_style('rdashboard-vendor');
		wp_enqueue_style('rdashboard-tailwind');
		wp_enqueue_style('rdashboard-title-font');
		wp_enqueue_style('rdashboard-base-font');
		wp_enqueue_style('rdashboard-icons');
		wp_enqueue_script('rdashboard-frontend');
		wp_enqueue_script('rdashboard-vendor');
		// wp_localize_script( 'wp-api', 'wpApiSettings', array(
		// 	'root'  => esc_url_raw( rest_url() ),
		// 	'nonce' => wp_create_nonce( 'wp_rest' )
		// ) );

		// $wpApiSettings = json_encode( array( 
		// 	'root' => esc_url_raw( rest_url() ),
		// 	'nonce' => wp_create_nonce( 'wp_rest' )
		// ) );
		// $wpApiSettings = "var wpApiSettings = ${wpApiSettings};";
		// wp_register_script( 'dashboard-wp-api', '' );
		// wp_enqueue_script( 'dashboard-wp-api' );
		// wp_add_inline_script( 'dashboard-wp-api', $wpApiSettings );

		$content .= '<div id="vue-frontend-app"></div>';

		return $content;
	}




	/**
	 * Render frontend app
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	public function render_admin($atts, $content = '')
	{
		wp_enqueue_style('rdashboard-admin');
		wp_enqueue_style('rdashboard-vendor');
		wp_enqueue_style('rdashboard-tailwind');
		wp_enqueue_style('rdashboard-title-font');
		wp_enqueue_style('rdashboard-base-font');
		wp_enqueue_style('rdashboard-icons');
		wp_enqueue_script('rdashboard-admin');
		wp_enqueue_script('rdashboard-vendor');
		// wp_localize_script( 'wp-api', 'wpApiSettings', array(
		// 	'root'  => esc_url_raw( rest_url() ),
		// 	'nonce' => wp_create_nonce( 'wp_rest' )
		// ) );

		// $wpApiSettings = json_encode( array( 
		// 	'root' => esc_url_raw( rest_url() ),
		// 	'nonce' => wp_create_nonce( 'wp_rest' )
		// ) );
		// $wpApiSettings = "var wpApiSettings = ${wpApiSettings};";
		// wp_register_script( 'dashboard-wp-api', '' );
		// wp_enqueue_script( 'dashboard-wp-api' );
		// wp_add_inline_script( 'dashboard-wp-api', $wpApiSettings );

		$content .= '<div id="vue-admin-app"></div>';

		return $content;
	}
}
