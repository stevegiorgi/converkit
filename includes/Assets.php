<?php

namespace App;

/**
 * Scripts and Styles Class
 */
class Assets
{

	public function reset()
	{
		$wp_scripts = wp_scripts();
		$wp_styles  = wp_styles();
		$themes_uri = get_theme_root_uri();

		foreach ($wp_scripts->registered as $wp_script) {
			if (strpos($wp_script->src, $themes_uri) !== false) {
				wp_deregister_script($wp_script->handle);
			}
		}

		foreach ($wp_styles->registered as $wp_style) {
			if (strpos($wp_style->src, $themes_uri) !== false) {
				wp_deregister_style($wp_style->handle);
			}
		}
	}

	function __construct()
	{

		if (is_admin()) {
			add_action('admin_enqueue_scripts', [$this, 'register'], 5);
			// add_action('admin_enqueue_scripts', [$this, 'deregister_scripts'], 999);
		} else {
			if (is_page('/dashboard/')) {
				add_action('wp_enqueue_scripts', [$this, 'reset'], 999);
			}
			add_action('wp_enqueue_scripts', [$this, 'register'], 5);
		}
	}

	/**
	 * Register our app scripts and styles
	 *
	 * @return void
	 */
	public function register()
	{
		$this->register_scripts($this->get_scripts());
		$this->register_styles($this->get_styles());
		$this->register_tables($this->register_tables());
	}

	/**
	 * Register scripts
	 *
	 * @param  array $scripts
	 *
	 * @return void
	 */
	private function register_scripts($scripts)
	{
		foreach ($scripts as $handle => $script) {
			$deps      = isset($script['deps']) ? $script['deps'] : false;
			$in_footer = isset($script['in_footer']) ? $script['in_footer'] : false;
			$version   = isset($script['version']) ? $script['version'] : RDASHBOARD_VERSION;

			wp_register_script($handle, $script['src'], $deps, $version, $in_footer);
		}
	}

		/**
     * Deregister scripts
     *
     * @return void
     */
    public function deregister_scripts() {
			// wp_deregister_style('wp-admin');
	}

	/**
	 * Register styles
	 *
	 * @param  array $styles
	 *
	 * @return void
	 */
	public function register_styles($styles)
	{
		foreach ($styles as $handle => $style) {
			$deps = isset($style['deps']) ? $style['deps'] : false;

			wp_register_style($handle, $style['src'], $deps, RDASHBOARD_VERSION);
		}
	}

	/**
	 * Get all registered scripts
	 *
	 * @return array
	 */
	public function get_scripts()
	{
		$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';

		$scripts = [
			'rdashboard-runtime' => [
				'src'       => RDASHBOARD_ASSETS . '/js/runtime.js',
				'version'   => filemtime(RDASHBOARD_PATH . '/assets/js/runtime.js'),
				'in_footer' => true
			],
			'rdashboard-vendor' => [
				'src'       => RDASHBOARD_ASSETS . '/js/vendors.js',
				'version'   => filemtime(RDASHBOARD_PATH . '/assets/js/vendors.js'),
				'in_footer' => true
			],
			'rdashboard-frontend' => [
				'src'       => RDASHBOARD_ASSETS . '/js/frontend.js',
				'deps'      => ['jquery', 'rdashboard-vendor', 'rdashboard-runtime'],
				'version'   => filemtime(RDASHBOARD_PATH . '/assets/js/frontend.js'),
				'in_footer' => true
			],
			'rdashboard-admin' => [
				'src'       => RDASHBOARD_ASSETS . '/js/admin.js',
				'deps'      => ['jquery', 'rdashboard-vendor', 'rdashboard-runtime'],
				'version'   => filemtime(RDASHBOARD_PATH . '/assets/js/admin.js'),
				'in_footer' => true
			],
		];

		return $scripts;
	}

	/**
	 * Get registered styles
	 *
	 * @return array
	 */
	public function get_styles()
	{
		$styles = [
			'rdashboard-style' => [
				'src' =>  RDASHBOARD_ASSETS . '/css/style.css'
			],
			'rdashboard-frontend' => [
				'src' =>  RDASHBOARD_ASSETS . '/css/frontend.css'
			],
			'rdashboard-vendor' => [
				'src' =>  RDASHBOARD_ASSETS . '/css/vendors.css'
			],
			'rdashboard-tailwind' => [
				'src' => 'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css'
			],
			'rdashboard-base-font' => [
				'src' => 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900'
			],
			'rdashboard-title-font' => [
				'src' => 'https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap'
			],
			'rdashboard-title-veutify' => [
				'src' => 'https://cdn.jsdelivr.net/npm/vuetify@1.5.24/dist/vuetify.min.css'
			],
			'rdashboard-icons-md' => [
				'src' => 'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css'
			],
			'rdashboard-icons-mdi' => [
				'src' => 'https://fonts.googleapis.com/css?family=Material+Icons'
			],
			'rdashboard-admin' => [
				'src' =>  RDASHBOARD_ASSETS . '/css/vendors.css'
			],
		];
		return $styles;
	}

	/**
	 * 
	 * Register database tables
	 */

	public function register_tables()
	{
		global $wpdb;
		$table_name1 = $wpdb->prefix . 'zoom_uuids';
		$charset_collate = $wpdb->get_charset_collate();
		$sql1 = "CREATE TABLE $table_name1 (
				uuid varchar(24) NOT NULL,
				PRIMARY KEY (uuid)
			) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql1);

		$table_name2 = $wpdb->prefix . 'ck_data';
		$sql2 = "CREATE TABLE $table_name2 (
			id mediumint(10) NOT NULL AUTO_INCREMENT,
			ck_id int(20) NOT NULL,
			first_name varchar(100) NOT NULL,
			email_address varchar(100) NOT NULL,
			state varchar(60) NOT NULL,
			created_at date NOT NULL,
			fields longtext NOT NULL,
			tags longtext NOT NULL,
			courses longtext NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		dbDelta($sql2);



		// register_activation_hook(__FILE__, 'register_zoom_uuids');

		// $uuids = array('0YeOx6VzQNSpSAH+Tv5Y2A==', 'B+S7tOsqQYG1N1Bvk3etTg==', 'Jw6tDjDqThi7CENyZBu1yg==', 'L8b3ntIgS+W83rCEWJEIUQ==', 'LqihmT2oRGOPUoSjRihvIw==', 'ROysgi6lTf+9BkFSMmflBA==', 'b7Ut3eOdS+iBfaxhGtTDAw==', 'dNv94nbzTKSTKZYxGmkvdg==', 'gz8skvcaQ/6tN6jm5ySqqw==', 'ntcAsOw4Qcu8F9f4mKCGSA==', 'r1Uu62UCQ8ijhLxJFMvUPA==',	'u/eKmahFTGeDr63aj/i44g==', 'ylvcLq+MQ9OJcI+zy9/Apw==', 'zl8rJ2onQhWtm6Vj9Pzrmg=='); // Zoom meeting UUIDs
		$uuids = array('AXs6D7JYS32gPi2JgPSscg==', 'B+S7tOsqQYG1N1Bvk3etTg==', 'Jw6tDjDqThi7CENyZBu1yg==', 'KhwSDCAuSDGAxrtMGoGVOw==', 'L8b3ntIgS+W83rCEWJEIUQ==', 'O4qDZoHDTQa83JaL+LM8NQ==', 'dNv94nbzTKSTKZYxGmkvdg==', 'grYf0HNYS+eshJK3Um2OUQ==', 'gz8skvcaQ/6tN6jm5ySqqw==', 'mudo/ocJQZ+sWy6A98PF4g==', 'ntcAsOw4Qcu8F9f4mKCGSA==', 'shjGKxEES7OaH24FLpm09w=='); // Zoom meeting UUIDs


		foreach ($uuids as $uuid) {
			// $existing_record = $wpdb->get_results("SELECT * FROM $table_name1 WHERE uuid = '" . $uuid . "'");
			// if (count($existing_record) === 0) {
			$wpdb->replace(
				$table_name1,
				array(
					'uuid' => $uuid,
				)
			);
			// }
		}




		// insert test data
		/* $uuid = '1234';
		$wpdb->insert(
			$table_name,
			array(
				'uuid' => $uuid,
			)
		);

		// pull test data
		$prepare = $wpdb->prepare("SELECT uuid FROM {$table_name}");
		$result = $wpdb->get_col($prepare);
		foreach ($result as $uuid) {
			echo $uuid;
		} */
	}
}
