<?php
/**
 * Plugin Name: Easy Populate Posts
 * Plugin URI: https://iuliacazan.ro/easy-populate-posts/
 * Description: Populate your site with random generated content. You can configure the post type, description, excerpt, tags, post meta, terms, images, publish date, status, parent, sticky, etc.
 * Text Domain: spp
 * Domain Path: /langs
 * Version: 4.0.0
 * Author: Iulia Cazan
 * Author URI: https://profiles.wordpress.org/iulia-cazan
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ
 * License: GPL2
 *
 * @package spp
 *
 * Copyright (C) 2015-2022 Iulia Cazan
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

declare( strict_types = 1 );

define( 'SPP_PLUGIN_VERSION', 4.00 );
define( 'SPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPP_PLUGIN_SLUG', 'spp' );

/**
 * The main class.
 */
class SISANU_Popupate_Posts {

	const PLUGIN_NAME        = 'Easy Populate Posts';
	const PLUGIN_SUPPORT_URL = 'https://wordpress.org/support/plugin/easy-populate-posts/';
	const PLUGIN_TRANSIENT   = 'spp-plugin-notice';

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	public static $max_random = 30;

	/**
	 * Plugin exclude post types.
	 *
	 * @var array
	 */
	public static $exclude_post_type = [];

	/**
	 * Plugin allowed post types.
	 *
	 * @var array
	 */
	public static $allowed_post_types = [];

	/**
	 * Plugin allowed post statuses.
	 *
	 * @var array
	 */
	public static $allowed_post_statuses = [];

	/**
	 * Plugin allowed taxonomies.
	 *
	 * @var array
	 */
	public static $allowed_taxonomies = [];

	/**
	 * Plugin exclude taxonomies.
	 *
	 * @var array
	 */
	public static $exclude_tax_type = [];

	/**
	 * Plugin admin page URL.
	 *
	 * @var string
	 */
	public static $plugin_url = '';

	/**
	 * Plugin default settings.
	 *
	 * @var array
	 */
	public static $default_settings = [];

	/**
	 * Plugin current settings.
	 *
	 * @var array
	 */
	public static $settings = [];

	/**
	 * Plugin current settings groups.
	 *
	 * @var array
	 */
	public static $settings_groups = [];

	/**
	 * Get active object instance.
	 *
	 * @return pbject
	 */
	public static function get_instance() { // phpcs:ignore
		if ( ! self::$instance ) {
			self::$instance = new SISANU_Popupate_Posts();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Run action and filter hooks.
	 *
	 * @access private
	 * @return void
	 */
	private function init() {
		$class = get_called_class();
		add_action( 'init', [ $class, 'load_plugin_settings' ], 99 );

		// Text domain load.
		add_action( 'plugins_loaded', [ $class, 'load_textdomain' ] );

		if ( is_admin() ) {
			add_action( 'admin_menu', [ $class, 'admin_menu' ] );
			add_action( 'admin_enqueue_scripts', [ $class, 'load_assets' ] );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $class, 'plugin_action_links' ] );
			add_action( 'wp_ajax_spp_save_settings', [ $class, 'spp_save_settings' ] );
			add_action( 'wp_ajax_spp_populate', [ $class, 'spp_populate' ] );
			add_action( 'wp_ajax_spp_pattern_test', [ $class, 'spp_pattern_test' ] );
			add_action( 'wp_ajax_spp_groups_list', [ $class, 'display_groups' ] );
			add_filter( 'spp_filter_acf_fields', [ $class, 'filter_acf_fields' ] );
			add_filter( 'spp_filter_post_meta', [ $class, 'filter_post_meta' ] );
			add_action( 'wp_ajax_spp_max_tax_listing', [ $class, 'spp_max_tax_listing' ] );
			add_action( 'wp_ajax_spp_max_meta_listing', [ $class, 'spp_max_meta_listing' ] );
		}

		add_action( 'admin_notices', [ $class, 'plugin_admin_notices' ] );
		add_action( 'wp_ajax_plugin-deactivate-notice-spp', [ $class, 'plugin_admin_notices_cleanup' ] );
		add_action( 'plugins_loaded', [ $class, 'plugin_ver_check' ] );
		add_action( 'added_post_meta', [ $class, 'reset_spp_meta_list' ] );
		add_action( 'updated_post_meta', [ $class, 'reset_spp_meta_list' ] );
		add_action( 'deleted_post_meta', [ $class, 'reset_spp_meta_list' ] );
	}

	/**
	 * Load the plugin settings.
	 *
	 * @return void
	 */
	public static function load_plugin_settings() {
		self::get_plugin_settings();
		self::get_allowed_post_types();
		self::get_allowed_post_statuses();
		self::get_allowed_taxonomies();
	}

	/**
	 * Prepare the plugin settings.
	 *
	 * @return void
	 */
	public static function get_plugin_settings() {
		self::$plugin_url        = admin_url( 'tools.php?page=populate-posts-settings' );
		self::$exclude_post_type = [
			'nav_menu_item',
			'revision',
			'attachment',
			'custom_css',
			'customize_changeset',
			'oembed_cache',
			'user_request',
			'wp_block',
			'wp_template',
			'wp_global_styles',
			'wp_template_part',
			'wp_navigation',
			'elementor_library',
		];

		self::$exclude_tax_type = [
			'nav_menu',
			'link_category',
			'post_format',
			'post_tag',
			'wp_template_part_area',
			'wp_theme',
			'elementor_library_type',
			'elementor_library_category',
		];

		$upload_dir = wp_upload_dir();
		$initial    = [];
		for ( $i = 1; $i <= 10; $i ++ ) {
			$initial[] = plugins_url( '/assets/images/sample' . $i . '.jpg', __FILE__ );
		}

		$images_initial_string  = implode( chr( 13 ), $initial );
		self::$default_settings = [
			'post_type'             => 'post',
			'content_type'          => 0,
			'excerpt'               => 0,
			'date_type'             => 1,
			'has_sticky'            => 2,
			'max_number'            => 10,
			'content_p'             => 0,
			'tags_list'             => 'Star Wars, Rebel, Force, Obi-Wan, Jedi, Senate, Alderaan, Luke',
			'meta_key'              => '', // phpcs:ignore
			'meta_value'            => '', // phpcs:ignore
			'meta_key2'             => '',
			'meta_value2'           => '',
			'meta_key3'             => '',
			'meta_value3'           => '',
			'meta_key4'             => '',
			'meta_value4'           => '',
			'meta_key5'             => '',
			'meta_value5'           => '',
			'taxonomy'              => '',
			'term_id'               => '',
			'term_slug'             => '',
			'taxonomy2'             => '',
			'term_id2'              => '',
			'term_slug2'            => '',
			'title_prefix'          => '',
			'post_parent'           => '',
			'specific_date'         => '',
			'specific_hour'         => '',
			'specific_status'       => '',
			'initial_images'        => $images_initial_string,
			'images_list'           => '',
			'images_path'           => '',
			'legacy_images_path'    => $upload_dir['basedir'] . '/spp_tmp/',
			'start_counter'         => 0,
			'cleanup_on_deactivate' => 0,
			'gutenberg_block'       => 0,
			'gutenberg_drop_cap'    => 0,
			'gutenberg_template'    => '',
			'all_images'            => [],
		];

		$settings = get_option( 'spp_settings', [] );
		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		$settings['default_images'] = self::$default_settings['initial_images'];
		self::$settings             = wp_parse_args( $settings, self::$default_settings );
		self::$settings_groups      = get_option( 'spp_settings_groups', [] );
	}

	/**
	 * Load scripts and stypes used by the plugin.
	 *
	 * @return void
	 */
	public static function load_assets() {
		$uri = $_SERVER['REQUEST_URI']; //phpcs:ignore
		if ( ! substr_count( $uri, 'tools.php?page=populate-posts-settings' ) ) {
			// Fail-fast, the assets should not be loaded.
			return;
		}

		if ( file_exists( SPP_PLUGIN_DIR . 'build/index.asset.php' ) ) {
			$dependencies = require_once SPP_PLUGIN_DIR . 'build/index.asset.php';
		} else {
			$dependencies = [
				'dependencies' => [],
				'version'      => filemtime( SPP_PLUGIN_DIR . 'build/index.js' ),
			];
		}

		if ( file_exists( SPP_PLUGIN_DIR . 'build/index.js' ) ) {
			wp_register_script(
				'spp-custom',
				SPP_PLUGIN_URL . 'build/index.js',
				$dependencies['dependencies'],
				$dependencies['version'],
				true
			);
			wp_localize_script(
				'spp-custom',
				'sppSettings',
				[
					'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
					'beginImages'  => self::spp_images_mention(),
					'discardGroup' => __( 'Discard this group of settings?', 'spp' ),
					'messages'     => [
						'settings' => [
							'init'  => __( 'Saving settings...', 'spp' ),
							'done'  => __( 'Done!', 'spp' ),
							'ready' => __( 'Save Settings', 'spp' ),
						],
						'populate' => [
							'init'  => __( 'Generating posts...', 'spp' ),
							'done'  => __( 'Done!', 'spp' ),
							'ready' => __( 'Execute Posts Add', 'spp' ),
						],
					],
				]
			);
			wp_enqueue_script( 'spp-custom' );
		}

		if ( file_exists( SPP_PLUGIN_DIR . 'build/style.css' ) ) {
			wp_enqueue_style(
				'spp-custom',
				SPP_PLUGIN_URL . 'build/style.css',
				[],
				filemtime( SPP_PLUGIN_DIR . 'build/style.css' ),
				false
			);
		}
	}

	/**
	 * Load text domain for internalization.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'spp', false, basename( dirname( __FILE__ ) ) . '/langs' );
	}

	/**
	 * Maybe reset cache.
	 *
	 * @return void
	 */
	public static function maybe_reset_cache() {
		$list = [
			'ssp-post-meta-list',
		];
		if ( ! empty( $list ) ) {
			foreach ( $list as $item ) {
				delete_transient( $item );
			}
		}
		self::reset_spp_meta_list();
	}

	/**
	 * Set the class property for all the post types registered in the application.
	 *
	 * @return void
	 */
	public static function get_allowed_post_types() {
		$post_types = get_post_types( [], 'objects' );
		if ( ! empty( $post_types ) && ! empty( self::$exclude_post_type ) ) {
			foreach ( self::$exclude_post_type as $k ) {
				unset( $post_types[ $k ] );
			}
		}
		self::$allowed_post_types = wp_list_pluck( $post_types, 'label', 'name' );
	}

	/**
	 * Set the class property for of all the post statuses registered in the application.
	 *
	 * @return void
	 */
	public static function get_allowed_post_statuses() {
		global $wp_post_statuses;
		$post_status = $wp_post_statuses;
		unset( $post_status['auto-draft'] );
		unset( $post_status['trash'] );
		unset( $post_status['inherit'] );
		unset( $post_status['request-pending'] );
		unset( $post_status['request-confirmed'] );
		unset( $post_status['request-failed'] );
		unset( $post_status['request-completed'] );
		self::$allowed_post_statuses = apply_filters(
			'spp_filter_post_statuses',
			wp_list_pluck( $post_status, 'label', 'name' )
		);
	}

	/**
	 * Set the class property for of all the taxonomies registered in the application.
	 *
	 * @return void
	 */
	public static function get_allowed_taxonomies() {
		$tax = get_taxonomies( [], 'objects' );
		if ( ! empty( $tax ) && ! empty( self::$exclude_tax_type ) ) {
			foreach ( self::$exclude_tax_type as $k ) {
				unset( $tax[ $k ] );
			}
		}
		self::$allowed_taxonomies = apply_filters(
			'spp_filter_post_taxonomies',
			wp_list_pluck( $tax, 'label', 'name' )
		);
	}

	/**
	 * Filter ACF fields.
	 *
	 * @param  array $list The ACF fields list.
	 * @return array
	 */
	public static function filter_acf_fields( array $list = [] ) : array {
		global $wpdb;
		$list = $wpdb->get_results( $wpdb->prepare( // phpcs:ignore
			' SELECT DISTINCT post_title as `name`, post_excerpt as `slug` FROM ' . $wpdb->posts . '
			WHERE 1 = %d AND post_type = %s AND post_status = %s
			AND post_title != %s
			ORDER BY post_title ASC ',
			1,
			'acf-field',
			'publish',
			''
		) );
		if ( ! empty( $list ) ) {
			return $list;
		}

		return [];
	}

	/**
	 * Filter post meta.
	 *
	 * @param  array $list The post meta list.
	 * @return array
	 */
	public static function filter_post_meta( array $list = [] ) : array {
		global $wpdb;
		$list = $wpdb->get_col( $wpdb->prepare( // phpcs:ignore
			' SELECT DISTINCT meta_key FROM ' . $wpdb->postmeta . '
			WHERE 1 = %d AND meta_key NOT BETWEEN %s AND %s HAVING meta_key NOT LIKE %s
			ORDER BY meta_key ASC ',
			1,
			'_',
			'_z',
			$wpdb->esc_like( '_' ) . '%'
		) );
		if ( ! empty( $list ) ) {
			$list = array_diff(
				$list,
				[
					'spp_sample',
					'spp_sample_url',
					'_',
					'_encloseme',
					'_edit_last',
					'_edit_lock',
					'_wp_trash_meta_status',
					'_wp_trash_meta_time',
					'_customize_changeset_uuid',
					'_customize_draft_post_name',
					'_customize_restore_dismissed',
					'_menu_item_classes',
					'_menu_item_menu_item_parent',
					'_menu_item_object',
					'_menu_item_object_id',
					'_menu_item_type',
					'_menu_item_url',
				]
			);

			return $list;
		}

		return [];
	}

	/**
	 * Returns all the meta_keys.
	 *
	 * @return array
	 */
	public static function get_post_meta_keys() { //phpcs:ignore
		global $wpdb;
		$trans_id = 'ssp-post-meta-list';
		$list     = get_transient( $trans_id );
		if ( false === $list ) {
			$list_acf  = apply_filters( 'spp_filter_acf_fields', [] );
			$list_meta = apply_filters( 'spp_filter_post_meta', [] );

			$list = '<select>
			<option value="">' . esc_html__( 'See the list of exisiting post meta', 'spp' ) . '</option>';
			if ( ! empty( $list_acf ) ) {
				$list .= '<optgroup label="' . esc_html__( 'ACF Fields Post Meta', 'spp' ) . '">';
				foreach ( $list_acf as $item ) {
					$list     .= '<option value="' . esc_attr( $item->slug ) . '">' . esc_attr( $item->slug ) . ' (' . esc_attr( $item->name ) . ')</option>';
					$list_meta = array_diff( $list_meta, [ $item->slug, '_' . $item->slug ] );
				}
				$list .= '</optgroup>';
			}
			if ( ! empty( $list_meta ) ) {
				$list .= '<optgroup label="' . esc_html__( 'Other Post Meta', 'spp' ) . '">';
				foreach ( $list_meta as $item ) {
					$list .= '<option value="' . esc_attr( $item ) . '">' . esc_attr( $item ) . '</option>';
				}
				$list .= '</optgroup>';
			}
			$list .= '</select>';

			set_transient( $trans_id, $list, 30 * MINUTE_IN_SECONDS );
		}

		return $list;
	}

	/**
	 * When the post meta are updated, deleted, created, the transient must be refreshed to reflect the new set.
	 *
	 * @return void
	 */
	public static function reset_spp_meta_list() { // phpcs:ignore
		delete_transient( 'ssp-post-meta-list' );
	}

	/** Add the new menu in general options section that allows to configure the plugin settings */
	public static function admin_menu() {
		add_submenu_page(
			'tools.php',
			'<div class="dashicons dashicons-admin-generic"></div> ' . __( 'Easy Populate Posts', 'spp' ),
			'<div class="dashicons dashicons-admin-generic"></div> ' . __( 'Easy Populate Posts', 'spp' ),
			'manage_options',
			'populate-posts-settings',
			[ get_called_class(), 'populate_posts_settings' ]
		);
	}

	/**
	 * Create the plugin images sources from a list of URLs.
	 *
	 * @param  string $images_list List of images separated by new line.
	 * @return array
	 */
	public static function set_local_images_from_options( string $images_list ): array {
		$list = [];
		if ( ! empty( $images_list ) ) {
			if ( substr_count( $images_list, chr( 13 ) ) ) {
				$photos = explode( chr( 13 ), $images_list );
			} elseif ( substr_count( $images_list, chr( 10 ) ) ) {
				$photos = explode( chr( 10 ), $images_list );
			} else {
				$photos = explode( ' ', $images_list );
			}
			if ( ! empty( $photos ) ) {
				foreach ( $photos as $p ) {
					$list[] = self::make_image_from_url( trim( $p ) );
				}
			}
		}

		return array_filter( $list );
	}

	/**
	 * Read the plugin images ids and return the array.
	 *
	 * @return array
	 */
	public static function get_local_images() : array {
		// Identify the attachment already created, so we do not generate the same one.
		$args = [
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => 100,
			'fields'         => 'ids',
			'meta_query'  => [ // phpcs:ignore
				[
					'key'     => 'spp_sample',
					'value'   => 1,
					'compare' => '=',
				],
			],
		];

		$posts = new WP_Query( $args );
		if ( ! empty( $posts->posts ) ) {
			return $posts->posts;
		}
		return [];
	}

	/** Return true if the nonce is posted and is valid */
	public static function spp_validate_nonce() { // phpcs:ignore
		if ( ! empty( $_POST ) ) {
			$nonce = filter_input( INPUT_POST, 'spp_settings_nonce', FILTER_DEFAULT );
			if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'spp_settings_save' ) ) {
				esc_html_e( 'Action not allowed.', 'spp' );
				die();
			}
			return true;
		}
		return false;
	}

	/** Return true if the current user can manage options, hence allowed to use the plugin */
	public static function spp_current_user_can() { // phpcs:ignore
		// Verify user capabilities in order to deny the access if the user does not have the capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			esc_html_e( 'Action not allowed.', 'spp' );
			die();
		}
		return true;
	}

	/**
	 * Remove trailing chars from string.
	 *
	 * @param  string $text String to be trimmed.
	 * @return string
	 */
	public static function trim_endings( string $text ): string {
		$text  = str_replace( '...', '.', $text );
		$text  = str_replace( '?!', '?', $text );
		$text  = str_replace( '!?', '!', $text );
		$text  = str_replace( '--', '-', $text );
		$last  = mb_substr( $text, -1 );
		$check = preg_replace( '/[^a-zA-Z0-9]/', '', $last );
		if ( $check !== $last ) {
			$text = mb_substr( $text, 0, -1 );
		}

		return $text;
	}

	/**
	 * Returns a random string.
	 *
	 * @param  int  $min   Min number of words.
	 * @param  int  $max   Max number of chars.
	 * @param  bool $lower Return lowercase string.
	 * @return string
	 */
	public static function get_random_string( int $min, int $max = 0, bool $lower = false ): string {
		$text_elements = self::get_text_elements( self::$settings['content_type'] );
		shuffle( $text_elements );
		$text_elements = implode( ' ', $text_elements );
		if ( ! empty( $max ) ) {
			$pos = strpos( $text_elements, ' ', (int) $max );
			if ( ! empty( $pos ) ) {
				$text_elements = substr( $text_elements, 0, $pos );
			} else {
				$text_elements = substr( $text_elements, 0, (int) $max );
			}
			$text_elements = self::trim_endings( $text_elements );
		}
		$text = wp_trim_words( $text_elements, (int) $min, '' );
		if ( ! empty( $max ) && strlen( $text ) > (int) $max ) {
			$text = wp_trim_words( $text, (int) $min - 1, '' );
		}
		$text = self::trim_endings( $text );

		if ( true === $lower ) {
			$text = mb_strtolower( $text );
		}

		return $text;
	}

	/**
	 * Returns a random number.
	 *
	 * @param  int $min Min value.
	 * @param  int $max Max value.
	 * @return int
	 */
	public static function get_random_number( int $min = 0, int $max = 0 ): int {
		return wp_rand( (int) $min, (int) $max );
	}

	/**
	 * Returns randomized string.
	 *
	 * @param  string $text Initial text.
	 * @return string
	 */
	public static function replace_text_tags( $text ) { // phpcs:ignore
		if ( empty( $text ) ) {
			return '';
		}

		if ( ! is_scalar( $text ) || is_numeric( $text ) ) {
			return $text;
		}

		if ( substr_count( $text, '#[EMAIL]' ) ) {
			$text = @preg_replace_callback( // phpcs:ignore
				'/\#\[EMAIL\]/i',
				function ( $matches ) { // phpcs:ignore
					$string = self::get_random_string( 2, 15 ) . '-' . self::get_random_string( 2, 15 ) . '@' . self::get_random_string( 2, 15 ) . self::get_random_number( 1, 999 ) . '.com';
					$string = mb_strtolower( preg_replace( '/[^a-zA-Z0-9\-\.\@]/', '', $string ) );
					$string = str_replace( '-i@', '@', $string );
					return $string;
				},
				$text
			);
		}

		if ( substr_count( $text, '#[URL]' ) ) {
			$text = @preg_replace_callback( // phpcs:ignore
				'/\#\[URL\]/i',
				function ( $matches ) { // phpcs:ignore
					$string = self::get_random_string( 2, 15 ) . '-' . str_replace( '', '-', self::get_random_string( 3, 15 ) ) . '.' . chr( 97 + wp_rand( 0, 25 ) ) . chr( 97 + wp_rand( 0, 25 ) );
					$string = 'https://' . mb_strtolower( preg_replace( '/[^a-zA-Z0-9\-\.]/', '', $string ) );
					return $string;
				},
				$text
			);
		}

		if ( substr_count( $text, '#[MOBILE]' ) ) {
			$text = @preg_replace_callback( // phpcs:ignore
				'/\#\[MOBILE\]/i',
				function ( $matches ) { // phpcs:ignore
					$string = '+' . self::get_random_number( 30, 48 ) . ' 0' . self::get_random_number( 700, 724 ) . ' ' . self::get_random_number( 100, 999 ) . ' ' . self::get_random_number( 100, 999 );
					return $string;
				},
				$text
			);
		}

		if ( substr_count( $text, '#[s' ) ) {
			$text = @preg_replace_callback( // phpcs:ignore
				'/\#\[s\-([0-9]+)\:([0-9]+)\]/',
				function ( $matches ) { // phpcs:ignore
					return self::get_random_string( (int) $matches[1], (int) $matches[2], true );
				},
				$text
			);
		}

		if ( substr_count( $text, '#[S' ) ) {
			$text = @preg_replace_callback( // phpcs:ignore
				'/\#\[S\-([0-9]+)\:([0-9]+)\]/',
				function ( $matches ) { // phpcs:ignore
					return self::get_random_string( (int) $matches[1], (int) $matches[2], false );
				},
				$text
			);
		}

		if ( substr_count( $text, '#[N' ) ) {
			$text = preg_replace( '#\#\[N#', '#[123', $text );
			$text = preg_replace( '#:L(.+)#', ':234$1', $text );
			$text = preg_replace( '#:T(.+)#', ':345$1', $text );

			$new_text = @preg_replace_callback( // phpcs:ignore
				'/\#\[123\-([0-9]+)\:([0-9]+)\:234(.+)\]/i',
				function ( $matches ) { // phpcs:ignore
					$max = strlen( $matches[2] );
					$nr  = (string) self::get_random_number( (int) $matches[1], (int) $matches[2] );
					return str_pad( $nr, $max, (string) $matches[3], STR_PAD_LEFT );
				},
				$text
			);

			$text = $new_text ?? $text;

			$new_text = @preg_replace_callback( // phpcs:ignore
				'/\#\[123\-([0-9]+)\:([0-9]+)\:345(.+)\]/i',
				function ( $matches ) { // phpcs:ignore
					$max = strlen( $matches[2] );
					$nr  = (string) self::get_random_number( (int) $matches[1], (int) $matches[2] );
					return str_pad( $nr, $max, (string) $matches[3], STR_PAD_RIGHT );
				},
				$text
			);

			$text = $new_text ?? $text;

			$new_text = @preg_replace_callback( // phpcs:ignore
				'/\#\[123\-([0-9]+)\:([0-9]+)\]/i',
				function ( $matches ) { // phpcs:ignore
					return self::get_random_number( (int) $matches[1], (int) $matches[2] );
				},
				$text
			);

			$text = $new_text ?? $text;
		}

		if ( substr_count( $text, '#[l]' ) ) {
			$text = preg_replace_callback( // phpcs:ignore
				'/\#\[l\]/',
				function ( $matches ) { // phpcs:ignore
					return chr( 97 + wp_rand( 0, 25 ) );
				},
				$text
			);
		}

		if ( substr_count( $text, '#[L]' ) ) {
			$text = preg_replace_callback( // phpcs:ignore
				'/\#\[L\]/',
				function ( $matches ) { // phpcs:ignore
					return mb_strtoupper( chr( 97 + wp_rand( 0, 25 ) ) );
				},
				$text
			);
		}
		return $text;
	}

	/**
	 * Filter the images ids, to result in a uniques, non-empty and exisiting images IDs.
	 *
	 * @param  mixed $list A string of IDs separated by comma, or an array of IDs.
	 * @return mixed
	 */
	public static function filter_images_ids( $list ) { // phpcs:ignore
		$as_string = ! is_array( $list ) ? true : false;
		if ( $as_string ) {
			$list = explode( ',', $list );
		}

		if ( ! empty( $list ) ) {
			$list = array_filter( $list );
			$list = ! empty( $list ) ? array_unique( $list ) : [];
			foreach ( $list as $k => $v ) {
				$url = wp_get_attachment_image_src( $v );
				if ( empty( $url[0] ) ) {
					unset( $list[ $k ] );
				}
			}
			$list = array_filter( $list );
		}

		if ( $as_string ) {
			return implode( ',', $list );
		}

		return $list;
	}

	/**
	 * Return the content generated after an ajax call
	 *
	 * @param boolean $return True if the method returns result.
	 * @return void
	 */
	public static function spp_save_settings( $return = true ) { // phpcs:ignore
		self::maybe_reset_cache();

		if ( self::spp_current_user_can() && self::spp_validate_nonce() ) {
			$spp_del = filter_input( INPUT_POST, 'spp_del', FILTER_VALIDATE_INT );
			if ( ! empty( $spp_del ) ) {
				$all = self::get_local_images();
				$im  = ! empty( self::$settings['images_path'] ) ? explode( ',', self::$settings['images_path'] ) : [];
				$im  = ( ! empty( $im ) ) ? array_diff( $im, [ (int) $spp_del ] ) : [];
				if ( ! empty( $im ) ) {
					$im = self::filter_images_ids( $im );
				}

				self::$settings['all_images']  = $all;
				self::$settings['images_path'] = ! empty( $im ) ? implode( ',', $im ) : '';

				update_option( 'spp_settings', self::$settings );
				if ( false !== $return ) {
					self::load_plugin_settings();
					self::spp_show_plugin_images();
					die();
				}
			}

			self::load_plugin_settings();
			$ds     = self::$settings;
			$ds_new = $ds;
			$ints   = [ 'content_type', 'content_p', 'date_type', 'has_sticky', 'max_number', 'post_parent' ];
			$pspp   = filter_input( INPUT_POST, 'spp', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			// Default resets.
			$ds_new['gutenberg_block']       = 0;
			$ds_new['gutenberg_template']    = '';
			$ds_new['gutenberg_drop_cap']    = 0;
			$ds_new['specific_date']         = '';
			$ds_new['specific_hour']         = '';
			$ds_new['specific_status']       = '';
			$ds_new['cleanup_on_deactivate'] = 0;
			foreach ( $pspp as $key => $value ) {
				switch ( $key ) {
					case 'max_tax':
					case 'max_meta':
						$ds_new[ $key ] = self::sanitize_max_value( (int) $value );
						break;

					case 'title_prefix':
					case 'post_type':
					case 'gutenberg_template':
						$ds_new[ $key ] = trim( $value );
						break;

					case 'start_counter':
						if ( ! substr_count( $pspp['title_prefix'], '#NO' ) ) {
							$ds_new[ $key ] = 0;
						} else {
							$ds_new[ $key ] = (int) $value;
						}
						break;

					case 'content_type':
					case 'excerpt':
					case 'date_type':
					case 'has_sticky':
					case 'max_number':
					case 'post_parent':
						$ds_new[ $key ] = (int) $value;
						break;

					case 'gutenberg_block':
					case 'gutenberg_drop_cap':
					case 'cleanup_on_deactivate':
						$ds_new[ $key ] = 1;
						break;

					case 'specific_date':
					case 'specific_hour':
					case 'specific_status':
						$ds_new[ $key ] = ( 3 === (int) $pspp['date_type'] ) ? $value : '';
						break;

					case 'images_list':
					case 'images_path':
						$ds_new['images_list'] = implode( chr( 13 ), array_map( 'sanitize_text_field', explode( chr( 13 ), $value ) ) );

						$new_ids = self::set_local_images_from_options( $ds_new['images_list'] );
						if ( ! empty( $new_ids ) ) {
							$img = implode( ',', $new_ids ) . ',' . $ds_new['images_path'];
							$img = explode( ',', $img );
							$img = self::filter_images_ids( $img );

							$ds_new['images_path'] = ! empty( $img ) ? implode( ',', $img ) : '';
						}
						break;

					default:
						if ( substr_count( $key, 'term_slug' ) ) {
							$ds_new[ $key ] = implode( ', ', self::spp_cleanup_terms_slugs( $value ) );
						} else {
							$maybe = maybe_unserialize( $value );
							if ( is_array( $maybe ) || is_object( $maybe ) ) {
								$ds_new[ $key ] = $maybe;
							} else {
								$ds_new[ $key ] = sanitize_text_field( $value );
							}
						}
						break;
				}
			}

			if ( 3 !== (int) $ds_new['content_type'] ) {
				$ds_new['gutenberg_template'] = '';
			}

			update_option( 'spp_settings', $ds_new );
			self::load_plugin_settings();

			$groups_save = filter_input( INPUT_POST, 'spp_groups', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			if ( ! empty( $groups_save ) ) {
				$dir      = wp_upload_dir();
				$data_url = trailingslashit( site_url() );
				$data_dir = $dir['basedir'] . '/spp_tmp/';
				if ( ! empty( $groups_save['add_title'] ) ) {
					$hash = md5( $groups_save['add_title'] );
					$data = self::$settings;
					if ( ! empty( $data['initial_images'] ) ) {
						$data['initial_images'] = str_replace( chr( 13 ), '#', $data['initial_images'] );
					}
					if ( ! empty( $data['default_images'] ) ) {
						$data['default_images'] = str_replace( chr( 13 ), '#', $data['default_images'] );
					}
					self::$settings_groups[ $hash ] = [
						'name'    => $groups_save['add_title'],
						'hash'    => $hash,
						'content' => wp_json_encode( [
							'name'    => $groups_save['add_title'],
							'hash'    => $hash,
							'url'     => $data_url,
							'path'    => $data_dir,
							'content' => $data,
						] ),
					];
					update_option( 'spp_settings_groups', self::$settings_groups );
				}

				if ( ! empty( $groups_save['discard'] ) ) {
					unset( self::$settings_groups[ $groups_save['discard'] ] );
					update_option( 'spp_settings_groups', self::$settings_groups );
				}

				if ( ! empty( $groups_save['load'] ) ) {
					$hash = trim( $groups_save['load'] );
					if ( ! empty( self::$settings_groups[ $hash ]['content'] ) ) {
						$array = json_decode( self::$settings_groups[ $hash ]['content'], true );
						if ( ! empty( $array['content'] ) && is_array( $array['content'] ) ) {
							$data = $array['content'];
							if ( ! empty( $data['initial_images'] ) ) {
								$data['initial_images'] = str_replace( '#', chr( 13 ), $data['initial_images'] );
							}
							if ( ! empty( $data['default_images'] ) ) {
								$data['default_images'] = str_replace( '#', chr( 13 ), $data['default_images'] );
							}
							update_option( 'spp_settings', $data );
						}
					}
				}

				if ( ! empty( $groups_save['import'] ) ) {
					$data = json_decode( $groups_save['import'], true );
					if ( ! empty( $data['hash'] ) && ! empty( $data['name'] ) && ! empty( $data['content'] ) ) {
						if ( ! empty( $data['content']['url'] ) && $data['content']['url'] !== $data_url ) {
							$data['content']['url'] = $data_url;

							$data['content']['initial_images'] = str_replace( $data['content']['url'], $data_url, $data['content']['initial_images'] );
							$data['content']['default_images'] = str_replace( $data['content']['url'], $data_url, $data['content']['default_images'] );

							$data['content']['legacy_images_path'] = str_replace( $data['content']['legacy_images_path'], $data_dir, $data['content']['legacy_images_path'] );
							$data['content']['gutenberg_template'] = str_replace( $data['content']['url'], $data_url, $data['content']['gutenberg_template'] );

							$data['content']['images_path'] = self::filter_images_ids( $data['content']['images_path'] );
						}
						self::$settings_groups[ $data['hash'] ] = [
							'name'    => $data['name'],
							'hash'    => $data['hash'],
							'content' => wp_json_encode( [
								'name'    => $data['name'],
								'hash'    => $data['hash'],
								'url'     => $data_url,
								'path'    => $data_dir,
								'content' => $data['content'],
							] ),
						];
					}
					update_option( 'spp_settings_groups', self::$settings_groups );
				}

				self::load_plugin_settings();
			}
		}

		if ( false !== $return ) {
			self::load_plugin_settings();
			self::spp_show_plugin_images();
			die();
		}
	}

	/**
	 * Test pattern AJAX handler.
	 *
	 * @return void
	 */
	public static function spp_pattern_test() {
		if ( self::spp_current_user_can() ) {
			$sample = filter_input( INPUT_POST, 'sample', FILTER_DEFAULT );
			if ( ! empty( $sample ) ) {
				echo '<div class="result">' . esc_html( self::replace_text_tags( $sample ) ) . '</div>';
			}
		}

		die();
	}

	/**
	 * Ajax call handler for populating posts.
	 *
	 * @return void
	 */
	public static function spp_populate() {
		if ( self::spp_current_user_can() && self::spp_validate_nonce() ) {
			self::spp_save_settings( false );
			self::execute_add_random_posts();
		}
		die();
	}

	/**
	 * Default text mentioning how the images work.
	 *
	 * @return string
	 */
	public static function spp_images_mention() : string {
		return '<em>' . esc_html__( 'Images to be set randomly as featured image.', 'spp' ) . '</em>';
	}

	/**
	 * Output the plugin images.
	 *
	 * @return void
	 */
	public static function spp_show_plugin_images() {
		if ( ! empty( self::$settings['images_path'] ) ) {
			$p = explode( ',', self::$settings['images_path'] );
			if ( count( $p ) !== 0 ) :
				?>
				<div class="spp_figures">
					<?php
					foreach ( $p as $id ) :
						$url     = wp_get_attachment_image_src( $id, 'thumbnail' );
						$img_src = ( ! empty( $url[0] ) ) ? $url[0] : '';
						if ( ! empty( $img_src ) ) {
							?>
							<span class="spp_figure"><span class="icon"><span class="dashicons dashicons-no" data-id="<?php echo (int) $id; ?>"></span></span><img src="<?php echo esc_url( $img_src . '?v=' . time() ); ?>"></span>
							<?php
						}
					endforeach;
					?>
				</div>
				<?php
			endif;
		}
	}

	/**
	 * Maybe donate or rate.
	 *
	 * @return void
	 */
	public static function show_donate_text() {
		?>
		<div>
			<?php
			if ( ! apply_filters( 'spp_filter_remove_donate_info', false ) ) {
				echo wp_kses_post(
					sprintf(
						// Translators: %1$s - donate URL, %2$s - rating.
						__( 'If you find the plugin useful and would like to support my work, please consider making a <a href="%1$s" target="_blank" rel="noreferrer">donation</a>.<br>It would make me very happy if you would leave a %2$s rating.', 'spp' ) . ' ' . __( 'A huge thanks in advance!', 'spp' ),
						'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ&item_name=Support+for+development+and+maintenance+(' . rawurlencode( self::PLUGIN_NAME ) . ')', // phpcs:ignore
						'<a href="' . self::PLUGIN_SUPPORT_URL . 'reviews/?rate=5#new-post" class="rating" target="_blank" rel="noreferrer" title="' . esc_attr__( 'A huge thanks in advance!', 'spp' ) . '">★★★★★</a>'
					)
				);
			}
			?>
		</div>
		<img src="<?php echo esc_url( SPP_PLUGIN_URL . 'assets/images/icon-128x128.gif' ); ?>" width="32" height="32" alt="">
		<?php
	}

	/**
	 * Sanitize max value.
	 *
	 * @param  int $value Initial value.
	 * @return int
	 */
	public static function sanitize_max_value( int $value = 0 ) : int {
		$value = abs( (int) $value );
		$value = ( $value >= 20 ) ? 20 : $value;
		$value = ( $value <= 1 ) ? 1 : $value;
		return $value;
	}

	/**
	 * Get current max taxonomies.
	 *
	 * @return int
	 */
	public static function get_max_tax() : int {
		$max_tax = ( isset( self::$settings['max_tax'] ) ) ? (int) self::$settings['max_tax'] : 3;
		$max_tax = apply_filters( 'spp_max_options_tax', $max_tax );

		return $max_tax;
	}

	/**
	 * Get current max meta.
	 *
	 * @return int
	 */
	public static function get_max_meta() : int {
		$max_meta = ( isset( self::$settings['max_meta'] ) ) ? (int) self::$settings['max_meta'] : 5;
		$max_meta = apply_filters( 'spp_max_options_meta', $max_meta );

		return $max_meta;
	}

	/**
	 * List the taxonomies options.
	 *
	 * @param int $max_tax Maximum to show.
	 * @return void
	 */
	public static function spp_max_tax_listing( $max_tax = 0 ) { //phpcs:ignore
		$maybe_ajax = filter_input( INPUT_POST, 'action', FILTER_DEFAULT );
		$maybe_ajax = ( ! empty( $maybe_ajax ) && 'spp_max_tax_listing' === $maybe_ajax ) ? true : false;

		if ( $maybe_ajax ) {
			$post    = filter_input( INPUT_POST, 'spp', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$max_tax = self::sanitize_max_value( (int) $post['max_tax'] );
		}

		for ( $k = 1; $k <= $max_tax; ++ $k ) {
			$mk = ( $k > 1 ) ? $k : '';

			$val_tax  = ( isset( self::$settings[ 'taxonomy' . $mk ] ) )
				? self::$settings[ 'taxonomy' . $mk ] : '';
			$val_slug = ( isset( self::$settings[ 'term_slug' . $mk ] ) )
				? self::$settings[ 'term_slug' . $mk ] : '';
			$val_term = ( isset( self::$settings[ 'term_id' . $mk ] ) )
				? self::$settings[ 'term_id' . $mk ] : '';
			$val_rand = ( isset( self::$settings[ 'term_rand' . $mk ] ) )
				? (int) self::$settings[ 'term_rand' . $mk ] : 0;
			?>

			<h4><?php esc_html_e( 'Taxonomy', 'spp' ); ?> <?php echo esc_attr( $k ); ?></h4>
			<div class="row-span two-one">
				<select name="spp[taxonomy<?php echo esc_attr( $mk ); ?>]"
				id="spp_taxonomy<?php echo esc_attr( $mk ); ?>">
					<?php if ( ! empty( self::$allowed_taxonomies ) ) : ?>
						<?php foreach ( self::$allowed_taxonomies as $kk => $vv ) : ?>
							<option value="<?php echo esc_attr( $kk ); ?>"<?php selected( $kk, $val_tax ); ?>><?php echo esc_attr( $vv ); ?> (<?php echo esc_attr( $kk ); ?>)</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>

				<select name="spp[term_rand<?php echo esc_attr( $mk ); ?>]"
				id="spp_term_rand<?php echo esc_attr( $mk ); ?>">
					<option value="0"<?php selected( 0, $val_rand ); ?>><?php esc_attr_e( 'all', 'spp' ); ?></option>
					<option value="1"<?php selected( 1, $val_rand ); ?>><?php esc_attr_e( 'random', 'spp' ); ?></option>
				</select>

				<input type="text"
					name="spp[term_slug<?php echo esc_attr( $mk ); ?>]"
					id="spp_term_slug<?php echo esc_attr( $mk ); ?>"
					value="<?php echo esc_attr( $val_slug ); ?>"
					size="10" placeholder="<?php esc_attr_e( 'name', 'spp' ); ?>">

				<input type="text"
					name="spp[term_id<?php echo esc_attr( $mk ); ?>]"
					id="spp_term_id<?php echo esc_attr( $mk ); ?>"
					value="<?php echo esc_attr( $val_term ); ?>"
					size="10" placeholder="<?php esc_attr_e( 'term_id', 'spp' ); ?>">
			</div>
			<?php
		}

		if ( $maybe_ajax ) {
			wp_die();
		}
	}

	/**
	 * List the post meta options.
	 *
	 * @param int $max_meta Maximum to show.
	 * @return void
	 */
	public static function spp_max_meta_listing( $max_meta = 0 ) { //phpcs:ignore
		$maybe_ajax = filter_input( INPUT_POST, 'action', FILTER_DEFAULT );
		$maybe_ajax = ( ! empty( $maybe_ajax ) && 'spp_max_meta_listing' === $maybe_ajax ) ? true : false;

		if ( $maybe_ajax ) {
			$post     = filter_input( INPUT_POST, 'spp', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$max_meta = self::sanitize_max_value( (int) $post['max_meta'] );
		}

		for ( $k = 1; $k <= $max_meta; ++ $k ) {
			$mk = ( $k > 1 ) ? $k : '';

			$key_val   = ( isset( self::$settings[ 'meta_key' . $mk ] ) )
				? self::$settings[ 'meta_key' . $mk ] : '';
			$value_val = ( isset( self::$settings[ 'meta_key' . $mk ] ) )
				? maybe_serialize( self::$settings[ 'meta_value' . $mk ] ) : '';
			?>
			<h4><?php esc_html_e( 'Post Meta', 'spp' ); ?> <?php echo (int) $k; ?></h4>
			<div class="row-span one-two">
				<input type="text" size="15"
					name="spp[meta_key<?php echo esc_attr( $mk ); ?>]"
					id="spp_meta_key<?php echo esc_attr( $mk ); ?>"
					value="<?php echo esc_attr( $key_val ); ?>"
					placeholder="<?php esc_attr_e( 'key', 'spp' ); ?>">
				<input type="text" size="10"
					name="spp[meta_value<?php echo esc_attr( $mk ); ?>]"
					id="spp_meta_value<?php echo esc_attr( $mk ); ?>"
					value="<?php echo esc_attr( $value_val ); ?>"
					placeholder="<?php esc_attr_e( 'value', 'spp' ); ?>">
			</div>
			<?php
		}

		if ( $maybe_ajax ) {
			wp_die();
		}
	}

	/**
	 * Assess that the text has one of the patterns.
	 *
	 * @param  string $text Text to be assessed.
	 * @return bool
	 */
	public static function has_pattern( string $text = '' ): bool {
		return substr_count( $text, '#[' ) ? true : false;
	}

	/**
	 * Display settings groups, if any.
	 *
	 * @return void
	 */
	public static function display_groups() {
		$maybe_ajax = filter_input( INPUT_POST, 'action', FILTER_DEFAULT );
		$maybe_ajax = ( ! empty( $maybe_ajax ) && 'spp_groups_list' === $maybe_ajax ) ? true : false;
		$maybe_hash = filter_input( INPUT_POST, 'groupId', FILTER_DEFAULT );
		?>
		<input type="hidden" name="spp_groups[load]" id="spp_groups_load" value="">
		<input type="hidden" name="spp_groups[discard]" id="spp_groups_discard" value="">
		<input type="hidden" name="spp_groups[export]" id="spp_groups_export" value="">
		<?php
		if ( ! empty( self::$settings_groups ) ) {
			$list = self::$settings_groups;
			foreach ( $list as $k => $v ) {
				?>
				<div class="group-row">
					<span class="dashicons dashicons-arrow-left-alt as-icon" onclick="sppGroupAction('<?php echo esc_html( $k ); ?>', 'load')" title="<?php esc_html_e( 'Load', 'spp' ); ?>"></span>
					<span class="dashicons dashicons-arrow-up-alt as-icon" onclick="sppGroupAction('<?php echo esc_html( $k ); ?>', 'export')" title="<?php esc_html_e( 'Export', 'spp' ); ?>"></span>
					<?php echo esc_html( $v['name'] ); ?>
					<span class="dashicons dashicons-trash as-icon" onclick="sppGroupAction('<?php echo esc_html( $k ); ?>', 'discard')" title="<?php esc_html_e( 'Discard', 'spp' ); ?>" data-hash="<?php echo esc_html( $k ); ?>" data-type="discard"></span>
					<?php
					if ( $maybe_hash === $k ) {
						?>
						<div><?php esc_html_e( 'Copy the JSON string', 'spp' ); ?></div>
						<textarea id="content-<?php echo esc_html( $k ); ?>"><?php echo esc_html( $v['content'] ); ?></textarea>
						<script>
						const element = document.getElementById('content-<?php echo esc_html( $k ); ?>');
						if (element) {
							element.scrollIntoView({behavior: 'smooth', inline: 'nearest'});
						}
						</script>
						<?php
					}
					?>
				</div>
				<?php
			}
		} else {
			esc_html_e( 'No groups.', 'spp' );
		}

		if ( $maybe_ajax ) {
			$groups_save = filter_input( INPUT_POST, 'spp_groups', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			if ( ! empty( $groups_save['load'] ) ) {
				?>
				<script>window.reload();</script>
				<?php
			}

			wp_die();
		}
	}

	/** The plugin settings and trigger for the populate of posts */
	public static function populate_posts_settings() {
		?>
		<div class="wrap spp-feature">
			<h1 class="plugin-title">
				<span>
					<span class="dashicons dashicons-admin-generic ic-devops"></span>
					<span class="h1"><?php esc_html_e( 'Easy Populate Posts Settings', 'spp' ); ?></span>
				</span>
				<span><?php self::show_donate_text(); ?></span>
			</h1>

			<p>
				<?php esc_html_e( 'The is a helper plugin that allows developers to populate the sites with random content (posts with random tags, images, date in the past or in the future, sticky flag), but with specific meta values and taxonomy terms associated if the case.', 'spp' ); ?>
			</p>

			<form id="spp_settings_frm" action="" method="post" class="spp">
				<?php wp_nonce_field( 'spp_settings_save', 'spp_settings_nonce' ); ?>
				<input type="hidden" name="spp_del" id="spp_del" value="">

				<div class="options-boxes">
					<div>
						<h3><?php esc_html_e( 'Content', 'spp' ); ?></h3>

						<?php
						$title1 = 'first';
						$title2 = 'first not-visible';
						if ( self::has_pattern( self::$settings['title_prefix'] ) ) {
							$title1 = 'first not-visible';
							$title2 = 'first';
						}
						?>
						<em id="title-prefix-note">(<?php esc_html_e( 'use #NO for counter', 'spp' ); ?>)</em>
						<h4 id="spp_title_prefix_elem" class="<?php echo esc_attr( $title1 ); ?>"><?php esc_html_e( 'Title Prefix', 'spp' ); ?></h4>
						<h4 id="spp_title_elem" class="<?php echo esc_attr( $title2 ); ?>"><?php esc_html_e( 'Title', 'spp' ); ?></h4>
						<input type="text" name="spp[title_prefix]" id="spp_title_prefix" value="<?php echo esc_attr( self::$settings['title_prefix'] ); ?>" size="20">
						<p id="spp_title_prefix_counter" class="row-span three-one hidden">
							<span><em><?php esc_html_e( 'Start the auto-increment prefix number from this', 'spp' ); ?></em></span>

							<span><input type="number" name="spp[start_counter]" id="spp_start_counter" value="<?php echo esc_attr( self::$settings['start_counter'] ); ?>" size="20" disabled="disabled"></span>
						</p>

						<h4><?php esc_html_e( 'Content', 'spp' ); ?></h4>
						<select name="spp[content_type]" id="spp_content_type">
							<option value="0"<?php selected( 0, self::$settings['content_type'] ); ?>><?php esc_attr_e( 'Random', 'spp' ); ?></option>
							<option value="1"<?php selected( 1, self::$settings['content_type'] ); ?>><?php esc_attr_e( 'Star Wars', 'spp' ); ?></option>
							<option value="2"<?php selected( 2, self::$settings['content_type'] ); ?>><?php esc_attr_e( 'Lorem Ipsum', 'spp' ); ?></option>
							<option value="3"<?php selected( 3, self::$settings['content_type'] ); ?>><?php esc_attr_e( 'Gutenberg Template', 'spp' ); ?></option>
						</select>

						<div id="spp-content-g-wrap"
							<?php if ( 3 !== (int) self::$settings['content_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>>
							<h4><?php esc_html_e( 'Gutenberg Template', 'spp' ); ?></h4>
							<textarea name="spp[gutenberg_template]" id="spp_gutenberg_template" rows="6"><?php echo esc_attr( self::$settings['gutenberg_template'] ); ?></textarea>


							<div class="row-span four-one">
								<?php esc_html_e( 'Click the info icon to see the Gutenberg template example', 'spp' ); ?>
								<a onclick="sppToggleHint('#spp_hint_gutenberg_template');" class="a-center"><span class="dashicons dashicons-info as-icon"></span></a>
							</div>

							<div id="spp_hint_gutenberg_template" class="spp_hint not-visible">
								<a onclick="sppToggleHint('#spp_hint_gutenberg_template');" class="a-center"><span class="dashicons dashicons-dismiss as-icon"></span></a>
								<div class="first">
									<?php esc_html_e( 'Use the example below, or add your own post template. For generating random texts, you can use the custom patterns in the template.', 'spp' ); ?>
								</div>
								<hr>
								<pre style="max-width: 100%; overflow-x: scroll"><?php echo esc_html( '<!-- wp:columns {"verticalAlignment":"center","align":"full","style":{"color":{"background":"#f2e9cf"},"spacing":{"padding":{"top":"5vw","right":"5vw","bottom":"5vw","left":"5vw"}}},"className":"is-style-default"} -->' . PHP_EOL . '<div class="wp-block-columns alignfull are-vertically-aligned-center is-style-default has-background" style="background-color:#f2e9cf;padding-top:5vw;padding-right:5vw;padding-bottom:5vw;padding-left:5vw">' . PHP_EOL . '	<!-- wp:column {"verticalAlignment":"center"} -->' . PHP_EOL . '	<div class="wp-block-column is-vertically-aligned-center">' . PHP_EOL . '		<!-- wp:post-featured-image {"height":"80vh"} /-->' . PHP_EOL . '	</div>' . PHP_EOL . '	<!-- /wp:column -->' . PHP_EOL . '	<!-- wp:column {"verticalAlignment":"center"} -->' . PHP_EOL . '	<div class="wp-block-column is-vertically-aligned-center">' . PHP_EOL . '		<!-- wp:post-title /-->' . PHP_EOL . '		<!-- wp:paragraph --><p>#[S-35:220]. #[S-35:220]. #[S-35:220].</p><!-- /wp:paragraph -->' . PHP_EOL . '	</div>' . PHP_EOL . '	<!-- /wp:column -->' . PHP_EOL . '</div>' . PHP_EOL . '<!-- /wp:columns -->' . PHP_EOL . '<!-- wp:paragraph --><p>#[S-35:220]. #[S-35:220]. #[S-35:220].</p><!-- /wp:paragraph -->' . PHP_EOL . '<!-- wp:group {"align":"full","style":{"color":{"background":"#facb35"},"spacing":{"padding":{"top":"10vw","right":"5vw","bottom":"10vw","left":"5vw"}}}} -->' . PHP_EOL . '<div class="wp-block-group alignfull has-background" style="background-color:#facb35;padding-top:10vw;padding-right:5vw;padding-bottom:10vw;padding-left:5vw">' . PHP_EOL . '	<!-- wp:paragraph --><p>#[S-35:220]. #[S-35:220]. #[S-35:220].</p><!-- /wp:paragraph -->' . PHP_EOL . '</div>' . PHP_EOL . '<!-- /wp:group -->' ); ?></pre>
							</div>
						</div>

						<div id="spp-content-p-wrap"
							<?php if ( 3 === (int) self::$settings['content_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>>
							<h4><?php esc_html_e( 'Paragraphs', 'spp' ); ?></h4>
							<select name="spp[content_p]" id="spp_content_p">
								<option value="0"<?php selected( 0, self::$settings['content_p'] ); ?>><?php esc_attr_e( 'Random', 'spp' ); ?></option>
								<option value="1"<?php selected( 1, self::$settings['content_p'] ); ?>>1</option>
								<option value="2"<?php selected( 2, self::$settings['content_p'] ); ?>>2</option>
								<option value="3"<?php selected( 3, self::$settings['content_p'] ); ?>>3</option>
								<option value="4"<?php selected( 4, self::$settings['content_p'] ); ?>>4</option>
								<option value="5"<?php selected( 5, self::$settings['content_p'] ); ?>>5</option>
							</select>
							<p>
								<label>
									<input type="checkbox"
										name="spp[gutenberg_block]"
										id="spp_gutenberg_block"
										<?php checked( self::$settings['gutenberg_block'], 1 ); ?>>
									<?php esc_html_e( 'generate as Gutenberg blocks', 'spp' ); ?>
								</label>
							</p>
							<p id="spp_gutenberg_drop_cap_wrap"
								<?php if ( 1 !== (int) self::$settings['gutenberg_block'] ) : ?>
								style="display:none;"
								<?php endif; ?>>
								<label>
									<input type="checkbox"
										name="spp[gutenberg_drop_cap]"
										id="spp_gutenberg_drop_cap"
										<?php checked( self::$settings['gutenberg_drop_cap'], 1 ); ?>>
									<?php esc_html_e( 'set large initial letter for the first paragraph (drop cap)', 'spp' ); ?>
								</label>
							</p>
						</div>

						<h4><?php esc_html_e( 'Excerpt', 'spp' ); ?></h4>
						<select name="spp[excerpt]" id="spp_excerpt">
							<option value="0"<?php selected( 0, self::$settings['excerpt'] ); ?>><?php esc_attr_e( 'No excerpt', 'spp' ); ?></option>
							<option value="1"<?php selected( 1, self::$settings['excerpt'] ); ?>><?php esc_attr_e( 'Excerpt from content', 'spp' ); ?></option>
							<option value="2"<?php selected( 2, self::$settings['excerpt'] ); ?>><?php esc_attr_e( 'Random excerpt', 'spp' ); ?></option>
						</select>

						<h4><?php esc_html_e( 'Sticky Post', 'spp' ); ?></h4>
						<select name="spp[has_sticky]" id="spp_has_sticky">
							<option value="0"<?php selected( 0, self::$settings['has_sticky'] ); ?>><?php esc_attr_e( 'Random', 'spp' ); ?></option>
							<option value="1"<?php selected( 1, self::$settings['has_sticky'] ); ?>><?php esc_attr_e( 'Yes', 'spp' ); ?></option>
							<option value="2"<?php selected( 2, self::$settings['has_sticky'] ); ?>><?php esc_attr_e( 'No', 'spp' ); ?></option>
						</select>
					</div>

					<div>
						<h3><?php esc_html_e( 'Post', 'spp' ); ?></h3>

						<h4><?php esc_html_e( 'Maximum', 'spp' ); ?></h4>
						<input type="number" name="spp[max_number]" id="spp_max_number" value="<?php echo esc_attr( self::$settings['max_number'] ); ?>" size="15">
						<em><?php esc_html_e( 'how many to generate', 'spp' ); ?></em>

						<h4><?php esc_html_e( 'Type', 'spp' ); ?></h4>
						<select name="spp[post_type]" id="spp_post_type">
							<?php if ( ! empty( self::$allowed_post_types ) ) : ?>
								<?php foreach ( self::$allowed_post_types as $k => $v ) : ?>
									<option value="<?php echo esc_attr( $k ); ?>"<?php selected( $k, self::$settings['post_type'] ); ?>><?php echo esc_attr( $v ); ?> (<?php echo esc_attr( $k ); ?>)</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>

						<h4><?php esc_html_e( 'Parent', 'spp' ); ?></h4>
						<input type="number" name="spp[post_parent]" id="spp_post_parent" value="<?php echo esc_attr( self::$settings['post_parent'] ); ?>" size="15" placeholder="<?php esc_attr_e( 'Parent ID', 'spp' ); ?>">
						<em><?php esc_html_e( 'for hierarchical type only', 'spp' ); ?></em>

						<h4><?php esc_html_e( 'Date', 'spp' ); ?></h4>
						<select name="spp[date_type]" id="spp_date_type">
							<option value="0"<?php selected( 0, self::$settings['date_type'] ); ?>><?php esc_attr_e( 'Random', 'spp' ); ?></option>
							<option value="3"<?php selected( 3, self::$settings['date_type'] ); ?>><?php esc_attr_e( 'Specific Date & Status', 'spp' ); ?></option>
							<option value="1"<?php selected( 1, self::$settings['date_type'] ); ?>><?php esc_attr_e( 'In the past', 'spp' ); ?></option>
							<option value="2"<?php selected( 2, self::$settings['date_type'] ); ?>><?php esc_attr_e( 'In the future', 'spp' ); ?></option>
						</select>
						<div id="spp_specific_date_wrap"
							<?php if ( 3 !== (int) self::$settings['date_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>>
							<div class="row-span two-one medium">
								<em><?php esc_html_e( 'Date', 'spp' ); ?></em>
								<em><?php esc_html_e( 'Hour', 'spp' ); ?></em>
								<input type="date" name="spp[specific_date]" id="spp_specific_date" value="<?php echo esc_attr( self::$settings['specific_date'] ); ?>" pattern="\d{4}-\d{2}-\d{2}" size="15" placeholder="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>">
								<input type="time" name="spp[specific_hour]" id="spp_specific_hour" value="<?php echo esc_attr( self::$settings['specific_hour'] ); ?>" size="6" placeholder="<?php echo esc_attr( gmdate( 'H:i' ) ); ?>">
							</div>
						</div>

						<h4><?php esc_html_e( 'Status', 'spp' ); ?></h4>
						<p id="spp_random_date_text0"<?php if ( 0 !== (int) self::$settings['date_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>>
							<em><?php esc_html_e( 'will set a random status, correlated with the publish date', 'spp' ); ?></em>
						</p>
						<p id="spp_random_date_text3"<?php if ( 3 !== (int) self::$settings['date_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>>
							<select name="spp[specific_status]" id="spp_specific_status">
								<option value=""<?php selected( '', self::$settings['specific_status'] ); ?>><?php esc_attr_e( 'Not Specific', 'spp' ); ?></option>
								<?php if ( ! empty( self::$allowed_post_statuses ) ) : ?>
									<?php foreach ( self::$allowed_post_statuses as $k => $v ) : ?>
										<option value="<?php echo esc_attr( $k ); ?>"<?php selected( $k, self::$settings['specific_status'] ); ?>><?php echo esc_attr( $v ); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</p>

						<p id="spp_random_date_text1"<?php if ( 1 !== (int) self::$settings['date_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>><em><?php esc_html_e( 'defaults to', 'spp' ); ?> <?php esc_html_e( 'published', 'spp' ); ?></em>
						</p>

						<p id="spp_random_date_text2"<?php if ( 2 !== (int) self::$settings['date_type'] ) : ?>
							style="display:none;"
							<?php endif; ?>><em><?php esc_html_e( 'defaults to', 'spp' ); ?> <?php esc_html_e( 'scheduled', 'spp' ); ?></em></p>
						<hr>
						<?php $class = ( ! empty( self::$settings['cleanup_on_deactivate'] ) ) ? 'spp-will-cleanup' : ''; ?>
						<div id="spp-will-cleanup" class="fixed <?php echo esc_attr( $class ); ?>">
							<label><input type="checkbox" name="spp[cleanup_on_deactivate]" id="spp_cleanup_on_deactivate" <?php checked( self::$settings['cleanup_on_deactivate'], 1 ); ?> onclick="toggleCleanup();"> <b><?php esc_html_e( 'CONTENT CLEANUP ON DEACTIVATE', 'spp' ); ?></b> (<?php esc_html_e( 'please be careful, if you enable this option, when you deactivate the plugin, the content populated with this plugin will be removed, including the generated images', 'spp' ); ?>).</label>
						</div>
					</div>
					<div>
						<?php $max_tax = self::get_max_tax(); ?>
						<h3>
							<input type="number" name="spp[max_tax]" id="spp_max_tax" size="2" value="<?php echo esc_attr( $max_tax ); ?>" min="1" max="30">
							<?php esc_html_e( 'Terms', 'spp' ); ?>
						</h3>

						<h4><?php esc_html_e( 'Random Tags', 'spp' ); ?></h4>
						<input type="text" name="spp[tags_list]" id="spp_tags_list" value="<?php echo esc_attr( self::$settings['tags_list'] ); ?>">
						<em><?php esc_html_e( 'separated by comma', 'spp' ); ?></em>

						<div id="spp-max-tax-listing">
							<?php self::spp_max_tax_listing( (int) $max_tax ); ?>
						</div>

						<p><em><?php esc_html_e( 'Separate terms names by comma (these will be created if not found) or the term IDs.', 'spp' ); ?> <?php esc_html_e( 'When using the random tags option, the post will get random terms from the combined list of names and IDs.', 'spp' ); ?></em></p>
					</div>
					<div>
						<?php $max_meta = self::get_max_meta(); ?>
						<h3>
							<input type="number" name="spp[max_meta]" id="spp_max_meta" size="2" value="<?php echo esc_attr( $max_meta ); ?>" min="1" max="30">
							<?php esc_html_e( 'Meta', 'spp' ); ?>
						</h3>
						<div id="spp-max-meta-listing">
							<?php self::spp_max_meta_listing( (int) $max_meta ); ?>
						</div>

						<p><em><?php esc_html_e( 'Each of the specified post meta is a pair of meta_key and meta_value', 'spp' ); ?></em></p>
						<?php echo self::get_post_meta_keys(); // phpcs:ignore ?>
					</div>
					<div>
						<h3><?php esc_html_e( 'Images', 'spp' ); ?></h3>

						<h4><?php esc_html_e( 'Random images', 'spp' ); ?></h4>
						<div class="row-span four-one">
							<?php echo self::spp_images_mention(); // phpcs:ignore ?>
							<a onclick="sppToggleHint('#spp_hint_images');" class="a-center"><span class="dashicons dashicons-info as-icon"></span></a>
						</div>
						<div id="spp_settings_wrap"><?php self::spp_show_plugin_images(); ?></div>

						<h4><?php esc_html_e( 'Add images', 'spp' ); ?></h4>
						<div class="group-row import">
							<textarea name="spp[images_list]" id="spp_images_list"></textarea>
							<span>
								<span class="dashicons dashicons-arrow-up-alt as-icon" onclick="sppCopyImagesList();" title="<?php esc_html_e( 'Use the plugin sample images', 'spp' ); ?>"></span>
								&nbsp;
								<span class="dashicons dashicons-trash as-icon" onclick="document.getElementById('spp_images_list').value = '';" title="<?php esc_html_e( 'Discard', 'spp' ); ?>"></span>
							</span>
						</div>

						<div id="spp_hint_images" class="spp_hint not-visible">
							<a onclick="sppToggleHint('#spp_hint_images');" class="a-center"><span class="dashicons dashicons-dismiss as-icon"></span></a>
							<div class="first">
								<?php esc_html_e( 'You can add your own images URLs and click the "save settings" button (add URLs of images, separated by new line).', 'spp' ); ?>
							</div>
							<p>
								<?php esc_html_e( 'If you want to reuse images you already have in the media library, add the attachments IDs (separated by new line).', 'spp' ); ?>
							</p>
						</div>

						<span id="spp_initial_images"><?php echo self::$settings['default_images']; // phpcs:ignore ?></span>

						<h4><?php esc_html_e( 'Patterns', 'spp' ); ?></h4>
						<div id="spp_pattern_wrap">
							<div class="row-span four-one">
								<div>
									<?php esc_html_e( 'Click the info icon to see more about the custom patterns.', 'spp' ); ?>
								</div>
								<a onclick="sppToggleHint('#spp_hint_patterns');" class="a-center"><span class="dashicons dashicons-info as-icon"></span></a>
							</div>
						</div>

						<div id="spp_hint_patterns" class="spp_hint not-visible">
							<a onclick="sppToggleHint('#spp_hint_patterns');" class="a-center"><span class="dashicons dashicons-dismiss as-icon"></span></a>
							<div class="first">
								<?php
								echo wp_kses_post( sprintf(
									// Translators: %1$s - letter pattern, %2$s - number pattern, %3$s - text pattern.
									__( 'To generate even more random content, you could use the following patterns in the title prefix, random tags, terms names, and meta values. <ol><li>%1$s: generates a random capital <b>letter</b> from A to Z,</li><li>%2$s: generates a random <b>number</b> bettween the `min` and `max` specified values,</li><li>%3$s: generates a random <b>string</b> with minimum `min` words and maximum `max` chars.</li></ol>', 'spp' ),
									'<code>#[L]</code>',
									'<code>#[N-min:max]</code>',
									'<code>#[S-min:max]</code>'
								) );
								?>
							</div>
							<p>
								<?php
								echo sprintf( // phpcs:ignore
									// Translators: %1$s - letter pattern, %2$s - number pattern, %3$s - text pattern.
									esc_html__( 'Pattern examples: %1$s, %2$s, %3$s, %4$s, %5$s, %6$s, %7$s, %8$s, %9$s, %10$s.', 'spp' ),
									'<a onclick="sppApplyPattern(\'#[MOBILE]\');">' . esc_html__( 'mobile number', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[EMAIL]\');">' . esc_html__( 'email address', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[URL]\');">' . esc_html__( 'random URL', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[l]\');">' . esc_html__( 'letter', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[L]\');">' . esc_html__( 'capital letter', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[N-0:100]\');">' . esc_html__( 'number between 0-100', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[N-0:100:L0]\');">' . esc_html__( 'with leading 0', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[N-0:100:T0]\');">' . esc_html__( 'with trailing 0', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[L]#[s-1:16] #[L]#[s-1:16]\');">' . esc_html__( 'random title', 'spp' ) . '</a>',
									'<a onclick="sppApplyPattern(\'#[S-35:220]. #[S-35:220]. #[S-35:220].\');">' . esc_html__( 'random text', 'spp' ) . '</a>'
								);
								?>
							</p>
							<div class="row-span two-one">
								<input type="text" name="spp_pattern_sample" id="spp_pattern_sample">
								<button id="spp_pattern_button" class="button"><?php esc_html_e( 'Test', 'spp' ); ?></button>
							</div>
							<div id="spp_pattern_test"></div>
							<p>
								<?php esc_html_e( 'When one of the patterns is used in the title prefix, the whole pattern will be used for generating the title.', 'spp' ); ?>
							</p>
						</div>

						<h4><?php esc_html_e( 'Import/Export', 'spp' ); ?></h4>
						<div class="row-span four-one">
							<?php esc_html_e( 'Click the info icon to see the Gutenberg template example', 'spp' ); ?>
							<a onclick="sppToggleHint('#spp_hint_import_export');" class="a-center"><span class="dashicons dashicons-info as-icon"></span></a>
						</div>

						<div id="spp_hint_import_export" class="spp_hint not-visible">
							<a onclick="sppToggleHint('#spp_hint_import_export');" class="a-center"><span class="dashicons dashicons-dismiss as-icon"></span></a>
							<div class="first">
								<?php esc_html_e( 'For easily switching/restoring settings, you can save these as groups, each with a name, then export/import the JSON string to use in other instances.', 'spp' ); ?>
							</div>

							<hr>
							<h4><?php esc_html_e( 'Import', 'spp' ); ?></h4>
							<?php esc_html_e( 'Paste the JSON string below, then click the import icon', 'spp' ); ?>
							<div class="group-row import">
								<textarea name="spp_groups[import]" id="spp_groups_import" placeholder="<?php esc_attr_e( 'JSON string to be imported', 'spp' ); ?>"></textarea>
								<span class="dashicons dashicons-arrow-down-alt as-icon" onclick="sppGroupAction('', 'import')" title="<?php esc_html_e( 'Import', 'spp' ); ?>"></span>
							</div>

							<h4><?php esc_html_e( 'Groups', 'spp' ); ?></h3>
							<?php esc_html_e( 'Save the current settings as a group', 'spp' ); ?>
							<div class="row-span four-one">
								<input type="text" name="spp_groups[add_title]" id="spp_groups_add_title" value="" placeholder="<?php esc_attr_e( 'Group name', 'spp' ); ?>" size="20">
								<span class="dashicons dashicons-yes as-icon save-settings-alt" title="<?php esc_html_e( 'Save Settings', 'spp' ); ?>"></span>
							</div>

							<div id="spp_groups_list">
								<?php self::display_groups(); ?>
							</div>
						</div>

						<hr>
						<button id="spp_save" class="button"><?php esc_html_e( 'Save Settings', 'spp' ); ?></button>
						<p>
							<button id="spp_execute" class="button button-primary"><?php esc_html_e( 'Execute Posts Add', 'spp' ); ?></button>
						</p>
					</div>
				</div>
				<div id="spp_populate_wrap"></div>
			</form>
		</div>

		<?php
	}

	/**
	 * Cleanup terms by ids.
	 *
	 * @param string $ids List of terms ids separated by comma.
	 * @return array
	 */
	public static function spp_cleanup_terms_ids( $ids = '' ) { // phpcs:ignore
		$ids = preg_replace( '!\s+!', '', $ids );
		$ids = explode( ',', $ids );
		if ( ! is_array( $ids ) ) {
			$ids = [ (int) $ids ];
		}
		$ids = array_map( 'intval', $ids );
		$ids = array_unique( $ids );
		$ids = array_filter( $ids );
		$ids = array_values( $ids );
		return $ids;
	}

	/**
	 * Cleanup terms by slugs.
	 *
	 * @param string $slugs List of terms names separated by comma.
	 * @return array
	 */
	public static function spp_cleanup_terms_slugs( $slugs = '' ) { // phpcs:ignore
		if ( empty( $slugs ) ) {
			return [];
		}
		$slugs = preg_replace( '!\s+!', ' ', $slugs );
		$terms = explode( ',', $slugs );
		if ( ! is_array( $terms ) ) {
			$terms = [ trim( $slugs ) ];
		}
		$terms = array_map( 'trim', $terms );
		$terms = array_unique( $terms );
		$terms = array_filter( $terms );
		$terms = array_values( $terms );
		return $terms;
	}

	/**
	 * Create a random post title.
	 *
	 * @param array   $text_elements Text elements.
	 * @param integer $min_w         Mimumum words.
	 * @return string
	 */
	public static function get_random_title( $text_elements, $min_w = 4 ) { // phpcs:ignore
		if ( empty( $text_elements ) ) {
			if ( 3 === (int) self::$settings['content_type'] ) {
				$text_elements = self::get_text_elements( 0 );
			} else {
				$text_elements = self::get_text_elements( self::$settings['content_type'] );
			}
		}
		$nn = $text_elements[ wp_rand( 0, count( $text_elements ) - 1 ) ];
		$nn = preg_replace( '[\!\?]', '.', $nn );
		$nn = str_replace( '. ', '.', $nn );
		$n  = explode( '.', $nn );
		$n  = array_filter( $n );
		shuffle( $n );

		$name  = trim( $n[0] ) ?? $text_elements[0];
		$words = explode( ' ', $name );
		$max_w = count( $words ) - 1;
		if ( $max_w <= $min_w ) {
			$min_w = $max_w;
		}
		$name = trim( implode( ' ', array_slice( $words, 0, wp_rand( $min_w, $max_w ) ) ) );
		$name = ucfirst( $name );
		return $name;
	}

	/**
	 * Create a random post content.
	 *
	 * @param array   $text_elements Text elements.
	 * @param integer $max_blocks    Mimumum paragraphs.
	 * @param integer $rand          Start for elements.
	 * @return string
	 */
	public static function get_random_description( $text_elements, $max_blocks = 1, $rand = 0 ) { // phpcs:ignore
		$texts = array_slice( $text_elements, (int) $rand, $max_blocks );

		if ( ! empty( self::$settings['gutenberg_block'] ) ) {
			if ( ! empty( self::$settings['gutenberg_drop_cap'] ) ) {
				$text = '<!-- wp:paragraph {"dropCap":true} --><p class="has-drop-cap">';
			} else {
				$text = '<!-- wp:paragraph --><p>';
			}
			$text .= implode( '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>', $texts ) . '</p><!-- /wp:paragraph -->';
		} else {
			$text = '<p>' . implode( '</p><p>', $texts ) . '</p>';
		}

		return $text;
	}

	/**
	 * Check if date is valid.
	 *
	 * @param string $date Date string.
	 * @return boolean
	 */
	public static function spp_is_valide_date( $date ) { // phpcs:ignore
		$d = DateTime::createFromFormat( 'Y-m-d H:i:s', $date );
		if ( false !== $d ) {
			return ( $d->format( 'Y-m-d H:i:s' ) === $date );
		}
		return false;
	}

	/**
	 * Get text elements, with all their paragraphs.
	 *
	 * @param int $settings_content_type Selected content type.
	 * @return string
	 */
	public static function get_text_elements( int $settings_content_type = 0 ) { // phpcs:ignore
		$text_elements = [
			0 => [
				'And what of the Rebellion? If the Rebels have obtained a complete technical readout of this station, it is possible, however unlikely, that they might find a weakness and exploit it.',
				'The plans you refer to will soon be back in our hands. Any attack made by the Rebels against this station would be a useless gesture, no matter what technical data they\'ve obtained. This station is now the ultimate power in the universe. I suggest we use it!',
				'Someone was in the pod. The tracks go off in this direction. Look, sir -- droids.',
				'Have you been in many battles? Several, I think. Actually, there\'s not much to tell. I\'m not much more than an interpreter, and not very good at telling stories. Well, not at making them interesting, anyways. Well, my little friend, you\'ve got something jammed in here real good. Were you on a cruiser or...',
				'And, now Your Highness, we will discuss the location of your hidden Rebel base.',
				'Help me, Obi-Wan Kenobi. You\'re my only hope.',
				'Oh, he says it\'s nothing, sir. Merely a malfunction. Old data.',
				'Pay it no mind. Who is she? She\'s beautiful. I\'m afraid I\'m not quite sure, sir. Help me, Obi-Wan Kenobi... I think she was a passenger on our last voyage. A person of some importance, sir -- I believe. Our captain was attached to... Is there more to this recording? Behave yourself, Artoo. You\'re going to get us in trouble. It\'s all right, you can trust him. He\'s our new master.',
				'Ready for some power? Okay. Let\'s see now. Put that in there. There you go. Now all I have to do is find this Yoda... if he even exists. Still...there\'s something familiar about this place. I feel like... I don\'t know... Like we\'re being watched!',
				'Away with your weapon! I mean you no harm. I am wondering, why are you here? I\'m looking for someone. Looking? Found someone, you have, I would say, hmmm? Right. Help you I can. Yes, mmmm. don\'t think so. I\'m looking for a great warrior. Ahhh! A great warrior. Wars not make one great.',
				'What is thy bidding, my master? There is a great disturbance in the Force. I have felt it. We have a new enemy - Luke Skywalker. Yes, my master. He could destroy us. He\'s just a boy. Obi-Wan can no longer help him. The Force is strong with him.',
				'The son of Skywalker must not become a Jedi. If he could be turned, he would become a powerful ally. Yes. Yes. He would be a great asset. Can it be done? He will join us or die, my master.',
				'I don\'t know where you get you delusions, laser brain.',
				'Echo Base... I\'ve got something! Not much, but it could be a life form. This is Rouge Two. this is Rouge Two. Captain Solo, so you copy? Commander Skywalker, do you copy? This is Rouge Two. Good morning. Nice of you guys to drop by. Echo Base...this is Rouge Two. I found them. Repeat, I found them. Master Luke, sir, it\'s good to see you fully functional again.',
				'Well, Your Highness, I guess this is it. That\'s right. Well, don\'t get all mushy on me. So long, Princess. Han! Yes, Your Highnessness? I thought you decided to stay.',
				'The bounty hunter we ran into on Ord Mantell changed my mind. Han, we need you! We? Yes. Oh, what about you need? I need? I don\'t know what you\'re talking about. You probably don\'t. And what precisely am I supposed to know?',
				'I don\'t know what you\'re talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan Partially, but it also obeys your commands. Oh God, my uncle. How am I ever gonna explain this? What!? A tremor in the Force. The last time I felt it was in the presence of my old master.',
				'Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. I need your help, Luke. She needs your help. I\'m getting too old for this sort of thing. I need your help, Luke. She needs your help. I\'m getting too old for this sort of thing.',
				'Look, I ain\'t in this for your revolution, and I\'m not in it for you, Princess. I expect to be well paid. I\'m in it for the money.',
				'Red Five standing by. The plans you refer to will soon be back in our hands. Red Five standing by. I\'m surprised you had the courage to take the responsibility yourself. She must have hidden the plans in the escape pod.',
				'Send a detachment down to retrieve them, and see to it personally, Commander. There\'ll be no one to stop us this time! No! Alderaan is peaceful. We have no weapons. You can\'t possibly',
				'What?! Don\'t be too proud of this technological terror you\'ve constructed. The ability to destroy a planet is insignificant next to the power of the Force. I have traced the Rebel spies to her. Now she is my only link to finding their secret base.',
				'Kid, I\'ve flown from one side of this galaxy to the other. I\'ve seen a lot of strange stuff, but I\'ve never seen anything to make me believe there\'s one all-powerful Force controlling everything. There\'s no mystical energy field that controls my destiny.',
				'It\'s all a lot of simple tricks and nonsense. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Leave that to me.',
				'Send a distress signal, and inform the Senate that all on board were killed. Kid, I\'ve flown from one side of this galaxy to the other. I\'ve seen a lot of strange stuff, but I\'ve never seen anything to make me believe there\'s one all-powerful Force controlling everything.',
				'There\'s no mystical energy field that controls my destiny. It\'s all a lot of simple tricks and nonsense. Red Five standing by. Red Five standing by.',
				'The plans you refer to will soon be back in our hands. I have traced the Rebel spies to her. Now she is my only link to finding their secret base. A tremor in the Force.',
				'The last time I felt it was in the presence of my old master. The plans you refer to will soon be back in our hands. You\'re all clear, kid. Let\'s blow this thing and go home!',
				'Obi-Wan is here. The Force is with him. The plans you refer to will soon be back in our hands. What good is a reward if you ain\'t around to use it? Besides, attacking that battle station ain\'t my idea of courage. It\'s more like... suicide.',
				'I have traced the Rebel spies to her. Now she is my only link to finding their secret base.',
				'Oh God, my uncle. How am I ever gonna explain this? I don\'t know what you\'re talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan No! Alderaan is peaceful. We have no weapons. You can\'t possibly...',
				'She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There\'ll be no one to stop us this time! I care. So, what do you think of her, Han? You are a part of the Rebel Alliance and a traitor! Take her away! Hokey religions and ancient weapons are no match for a good blaster at your side, kid.',
				'He is here. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Red Five standing by. Partially, but it also obeys your commands. The more you tighten your grip, Tarkin, the more star systems will slip through your fingers. I\'m surprised you had the courage to take the responsibility yourself.',
				'I call it luck. As you wish. Ye-ha! I can\'t get involved! I\'ve got work to do! It\'s not that I like the Empire, I hate it, but there\'s nothing I can do about it right now. It\'s such a long way from here. I don\'t know what you\'re talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan',
				'Escape is not his plan. I must face him, alone. She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There\'ll be no one to stop us this time! What!? Alderaan? I\'m not going to Alderaan. I\'ve got to go home. It\'s late, I\'m in for it as it is. In my experience, there is no such thing as luck.',
				'Still, she\'s got a lot of spirit. I don\'t know, what do you think? I want to come with you to Alderaan. There\'s nothing for me here now. I want to learn the ways of the Force and be a Jedi, like my father before me.',
				'I need your help, Luke. She needs your help. I\'m getting too old for this sort of thing. Alderaan? I\'m not going to Alderaan. I\'ve got to go home. It\'s late, I\'m in for it as it is. As you wish. Red Five standing by.',
				'I am a member of the Imperial Senate on a diplomatic mission to Alderaan Partially, but it also obeys your commands. Kid, I\'ve flown from one side of this galaxy to the other. I\'ve seen a lot of strange stuff, but I\'ve never seen anything to make me believe there\'s one all-powerful Force controlling everything. There\'s no mystical energy field that controls my destiny. It\'s all a lot of simple tricks and nonsense. A tremor in the Force. The last time I felt it was in the presence of my old master.',
				'I have traced the Rebel spies to her. Now she is my only link to finding their secret base. You\'re all clear, kid. Let\'s blow this thing and go home! Dantooine. They\'re on Dantooine.',
				'I want to come with you to Alderaan. There\'s nothing for me here now. I want to learn the ways of the Force and be a Jedi, like my father before me. I\'m trying not to, kid.',
				'Look, I can take you as far as Anchorhead. You can get a transport there to Mos Eisley or wherever you\'re going. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed.',
				'I have traced the Rebel spies to her. Now she is my only link to finding their secret base. Oh God, my uncle. How am I ever gonna explain this? You mean it controls your actions? I find your lack of faith disturbing.',
				'I am a member of the Imperial Senate on a diplomatic mission to Alderaan What?! Hey, Luke! May the Force be with you.',
				'Don\'t act so surprised, Your Highness. You weren\'t on any mercy mission this time. Several transmissions were beamed to this ship by Rebel spies.',
				'I want to know what happened to the plans they sent you. All right. Well, take care of yourself, Han. I guess that\'s what you\'re best at, ain\'t it? I don\'t know what you\'re talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan',
				'Her resistance to the mind probe is considerable. It will be some time before we can extract any information from her. The final check-out is complete. All systems are operational. What course shall we set?',
				'Perhaps she would respond to an alternative form of persuasion. What do you mean? I think it is time we demonstrate the full power of this station. Set your course for Princess Leia\'s home planet of Alderaan. With pleasure.',
				'Did you hear that? They\'ve shut down the main reactor. We\'ll be destroyed for sure. This is madness! We\'re doomed! There\'ll be no escape for the Princess this time. What\'s that?',
				'Artoo! Artoo-Detoo, where are you? At last! Where have you been? They\'re heading in this direction. What are we going to do? We\'ll be sent to the spice mine of Kessel or smashed into who knows what! Wait a minute, where are you going?',
				'The Death Star plans are not in the main computer. Where are those transmissions you intercepted? What have you done with those plans? We intercepted no transmissions. Aaah... This is a consular ship. Were on a diplomatic mission.',
				'If this is a consular ship... were is the Ambassador? Commander, tear this ship apart until you\'ve found those plans and bring me the Ambassador. I want her alive! There she is! Set for stun! She\'ll be all right. Inform Lord Vader we have a prisoner.',
			],
			1 => [
				'Eaque ipsa quae ab illo inventore veritatis et quasi. Sed ut perspiciatis unde omnis iste natus error sit voluptatem. Totam rem aperiam. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam.',
				'Architecto beatae vitae dicta sunt explicabo. Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Corrupti quos dolores et quas molestias excepturi sint occaecati.',
				'Architecto beatae vitae dicta sunt explicabo. Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae.',
				'Et harum quidem rerum facilis est et expedita distinctio. Quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Do eiusmod tempor incididunt ut labore et dolore magna aliqua. Do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
				'Eaque ipsa quae ab illo inventore veritatis et quasi. Eaque ipsa quae ab illo inventore veritatis et quasi. Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Fugiat quo voluptas nulla pariatur? Corrupti quos dolores et quas molestias excepturi sint occaecati.',
				'Esse cillum dolore eu fugiat nulla pariatur. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. Itaque earum rerum hic tenetur a sapiente delectus. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam. Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. Esse cillum dolore eu fugiat nulla pariatur.',
				'Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo. Fugiat quo voluptas nulla pariatur?',
				'Laboris nisi ut aliquip ex ea commodo consequat. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.',
				'At vero eos et accusamus. At vero eos et accusamus. Itaque earum rerum hic tenetur a sapiente delectus. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.',
				'Itaque earum rerum hic tenetur a sapiente delectus. Et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque. Esse cillum dolore eu fugiat nulla pariatur.',
				'Fugiat quo voluptas nulla pariatur? Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Et harum quidem rerum facilis est et expedita distinctio.',
				'Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Totam rem aperiam. Excepteur sint occaecat cupidatat non proident, sunt in culpa. Do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
				'Eaque ipsa quae ab illo inventore veritatis et quasi. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo. Architecto beatae vitae dicta sunt explicabo. Non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.',
				'Itaque earum rerum hic tenetur a sapiente delectus. Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo.',
				'Qui officia deserunt mollit anim id est laborum. Laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipisicing elit. At vero eos et accusamus. Inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
				'Ut enim ad minim veniam, quis nostrud exercitation ullamco. Animi, id est laborum et dolorum fuga. Fugiat quo voluptas nulla pariatur? Sed ut perspiciatis unde omnis iste natus error sit voluptatem. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam.',
				'Do eiusmod tempor incididunt ut labore et dolore magna aliqua. Itaque earum rerum hic tenetur a sapiente delectus. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.',
				'Nihil molestiae consequatur, vel illum qui dolorem eum. Nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam. Cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia. Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. Ut enim ad minim veniam, quis nostrud exercitation ullamco. Duis aute irure dolor in reprehenderit in voluptate velit.',
				'Totam rem aperiam. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat. Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo. Eaque ipsa quae ab illo inventore veritatis et quasi. At vero eos et accusamus.',
				'Itaque earum rerum hic tenetur a sapiente delectus. Itaque earum rerum hic tenetur a sapiente delectus. Animi, id est laborum et dolorum fuga. Excepteur sint occaecat cupidatat non proident, sunt in culpa. Fugiat quo voluptas nulla pariatur? Architecto beatae vitae dicta sunt explicabo.',
			],
		];

		if ( 0 === (int) $settings_content_type || 3 === (int) $settings_content_type ) {
			$list = array_merge( $text_elements[0], $text_elements[1] );
		} else {
			$list = $text_elements[ (int) $settings_content_type - 1 ];
		}

		shuffle( $list );
		return $list;
	}

	/**
	 * Get random tags.
	 *
	 * @param string $tags_list List of tags.
	 * @return array
	 */
	public static function get_random_tags( $tags_list = '' ) { // phpcs:ignore
		$tags = [];
		if ( ! empty( $tags_list ) ) {
			if ( ! is_array( $tags_list ) ) {
				$list = explode( ',', $tags_list );
			} else {
				$list = $tags_list;
			}
			$list  = array_map( 'trim', $list );
			$list  = array_unique( $list );
			$total = count( $list );
			if ( 1 === $total ) {
				$tags = $list;
			} else {
				shuffle( $list );
				$tags = array_slice( $list, 0, wp_rand( 1, $total - 1 ) );
			}

			if ( ! empty( $tags ) ) {
				foreach ( $tags as $k => $tag ) {
					$tags[ $k ] = self::replace_text_tags( $tag );
				}
			}
		}
		return $tags;
	}

	/**
	 * Assess and maybe create new taxonomy terms, and return the list of ids.
	 *
	 * @param  string       $tax   Taxonomy slug/name.
	 * @param  string|array $names Terms list.
	 * @return array
	 */
	public static function assess_create_taxonomy_terms( $tax, $names ) { // phpcs:ignore
		$ids = [];
		if ( ! empty( $names ) ) {
			if ( is_scalar( $names ) ) {
				$names = explode( ',', $names );
			}
			foreach ( $names as $val ) {
				$val  = self::replace_text_tags( $val );
				$term = term_exists( trim( $val ), $tax );
				if ( 0 !== $term && null !== $term ) {
					if ( ! empty( $term['term_id'] ) && is_numeric( $term['term_id'] ) ) {
						$ids[] = (int) $term['term_id'];
					}
				} else {
					$add = wp_insert_term( $val, $tax );
					if ( ! empty( $add['term_id'] ) && is_numeric( $add['term_id'] ) ) {
						$ids[] = (int) $add['term_id'];
					}
				}
			}
		}
		return $ids;
	}

	/**
	 * Select a random placeholder.
	 *
	 * @param  string $string The list of placeholders separated by comma.
	 * @return string
	 */
	public static function select_random_image( $string = '' ) { // phpcs:ignore
		global $select_random_placeholder;
		$list   = ( ! is_array( $string ) ) ? explode( ',', $string ) : $string;
		$usable = $list;
		if ( empty( $select_random_placeholder ) ) {
			$select_random_placeholder = [];
		} else {
			$diff = array_diff( $list, $select_random_placeholder );
			if ( ! empty( $diff ) ) {
				$list = array_values( $diff );
			} else {
				$list                      = $usable;
				$select_random_placeholder = [];
			}
		}
		$index = array_rand( $list, 1 );
		$item  = ( ! empty( $list[ $index ] ) ) ? $list[ $index ] : $usable[0];

		$select_random_placeholder[] = $item;
		return $item;
	}

	/**
	 * Compute the taxonomies terms from the settings.
	 *
	 * @return array
	 */
	public static function compute_taxonomies_terms() : array {
		$maybe_terms = [];
		$max_tax     = self::get_max_tax();
		for ( $k = 1; $k <= $max_tax; ++ $k ) {
			$mk = ( $k > 1 ) ? $k : '';
			if ( ! empty( self::$settings[ 'taxonomy' . $mk ] ) && ( ! empty( self::$settings[ 'term_id' . $mk ] )
				|| ! empty( self::$settings[ 'term_slug' . $mk ] ) ) ) {
				$terms_ids = self::spp_cleanup_terms_ids( self::$settings[ 'term_id' . $mk ] );
				$match_ids = self::assess_create_taxonomy_terms(
					self::$settings[ 'taxonomy' . $mk ],
					self::$settings[ 'term_slug' . $mk ]
				);
				$terms_ids = array_unique( array_merge( $match_ids, $terms_ids ) );
				if ( ! empty( $terms_ids ) ) {
					$maybe_terms[] = [
						'taxonomy'  => self::$settings[ 'taxonomy' . $mk ],
						'terms_ids' => array_values( $terms_ids ),
						'random'    => (int) self::$settings[ 'term_rand' . $mk ],
					];
				}
			}
		}

		if ( ! empty( $maybe_terms ) ) {
			$all_tax = [];
			foreach ( $maybe_terms as $type ) {
				$tax = $type['taxonomy'];
				if ( empty( $type['terms_ids'] ) ) {
					continue;
				}

				if ( empty( $all_tax[ $tax ] ) ) {
					$all_tax[ $tax ] = [
						'ids'    => [],
						'random' => 0,
					];
				}
				$ids = array_unique( array_merge( $all_tax[ $tax ]['ids'], $type['terms_ids'] ) );

				$all_tax[ $tax ]['ids']    = $ids;
				$all_tax[ $tax ]['random'] = $type['random'];
			}

			$maybe_terms = $all_tax;
		}

		return $maybe_terms;
	}

	/**
	 * Compute the post meta from the settings.
	 *
	 * @return array
	 */
	public static function compute_post_meta() : array {
		$mybe_meta = [];
		$max_meta  = self::get_max_meta();
		for ( $k = 1; $k <= $max_meta; ++ $k ) {
			$mk = ( $k > 1 ) ? $k : '';
			if ( ! empty( self::$settings[ 'meta_key' . $mk ] ) && ! empty( self::$settings[ 'meta_value' . $mk ] ) ) {
				$value       = self::replace_text_tags( self::$settings[ 'meta_value' . $mk ] );
				$mybe_meta[] = [
					'meta_key'   => self::$settings[ 'meta_key' . $mk ], //phpcs:ignore
					'meta_value' => $value, //phpcs:ignore
				];
			}
		}

		return $mybe_meta;
	}

	/**
	 * Execute the content populate and outputs the result.
	 *
	 * @return void
	 */
	public static function execute_add_random_posts() {
		$text_type     = 3 === (int) self::$settings['content_type'] ? 0 : self::$settings['content_type'];
		$text_elements = self::get_text_elements( $text_type );
		$photos        = [];
		if ( ! empty( self::$settings['images_path'] ) ) {
			$photos = explode( ',', self::$settings['images_path'] );
		}

		$now           = current_time( 'timestamp' ); // phpcs:ignore
		$return_result = '<ol>';
		$last          = 0;

		for ( $i = 0; $i < (int) self::$settings['max_number']; $i ++ ) {
			shuffle( $text_elements );

			self::get_plugin_settings();
			$info = self::$settings;

			$maybe_terms = self::compute_taxonomies_terms();
			$maybe_meta  = self::compute_post_meta();
			$skip_prefix = false;
			if ( substr_count( $info['title_prefix'], '#[' ) ) {
				$name        = $info['title_prefix'];
				$skip_prefix = true;
			} else {
				$name = self::get_random_title( $text_elements );
			}

			$diez_no = '';
			if ( ! empty( $info['title_prefix'] ) ) {
				if ( substr_count( $info['title_prefix'], '#NO' ) ) {
					$diez_no = (string) $info['start_counter'];
				}
			}

			if ( ! empty( $diez_no ) ) {
				foreach ( $info as $k => $v ) {
					if ( ! is_numeric( $v ) && 'title_prefix' !== $k ) {
						if ( is_scalar( $v ) ) {
							$info[ $k ] = str_replace( '#NO', $diez_no, $v );
						} else {
							foreach ( $v as $k1 => $v1 ) {
								$info[ $k ][ $k1 ] = str_replace( '#NO', $diez_no, $v1 );
							}
						}
					}
				}
			}

			if ( ! empty( $name ) ) {
				$max_blocks = ( 0 === (int) $info['content_p'] ) ? wp_rand( 1, 6 ) : $info['content_p'];
				if ( 3 === (int) self::$settings['content_type'] ) {
					// Gutenberg template.
					$description = self::$settings['gutenberg_template'];
				} else {
					$description = self::get_random_description( $text_elements, (int) $max_blocks );
				}

				$tags = [];
				if ( ! empty( $info['tags_list'] ) ) {
					$tags = self::get_random_tags( $info['tags_list'] );
				}

				/** Date and status related. */
				if ( ! empty( $info['specific_date'] ) ) {
					// This is the explict date selected by the user.
					$hour = empty( $info['specific_hour'] ) ? '00:00:00' : $info['specific_hour'] . ':00';
					$date = $info['specific_date'] . ' ' . $hour;
					$date = substr( $date, 0, 19 );
					$time = strtotime( $date );
				} else {
					$now_pref = - 1;
					if ( 2 === (int) $info['date_type'] ) {
						$now_pref = 1;
					} elseif ( 0 === (int) $info['date_type'] ) {
						$now_pref = wp_rand( 0, 10 );
						$now_pref = ( $now_pref > 5 ) ? 1 : - 1;
					}
					$time = $now + $now_pref * wp_rand( 1, 60 ) * DAY_IN_SECONDS;
					$date = gmdate( 'Y-m-d H:i:s', $time );
				}

				$status = ( $time > $now ) ? 'future' : 'publish';
				if ( 'future' !== $status && ! empty( $info['specific_status'] ) ) {
					$status = $info['specific_status'];
				}

				$prefix = '';
				if ( ! empty( $info['title_prefix'] ) ) {
					$last = $info['start_counter'];
					$last = (string) $last;
					if ( substr_count( $info['title_prefix'], '#NO' ) ) {
						$prefix = str_replace( '#NO', $last, $info['title_prefix'] ) . ' ';
						self::$settings['start_counter'] ++;
						update_option( 'spp_settings', self::$settings );
						$time += $last;
						$date  = gmdate( 'Y-m-d H:i:s', $time );
					} else {
						if ( ! empty( $last ) ) {
							// Reset the counter.
							self::$settings['start_counter'] = 0;
							update_option( 'spp_settings', self::$settings );
						}
						$prefix = $info['title_prefix'] . ' ';
					}
				}

				if ( true === $skip_prefix ) {
					$name = $prefix;
				} else {
					$name = $prefix . lcfirst( $name );
				}
				$name        = ucfirst( self::replace_text_tags( $name ) );
				$description = self::replace_text_tags( $description );

				$excerpt = '';
				if ( ! empty( $info['excerpt'] ) ) {
					if ( 2 === (int) $info['excerpt'] ) {
						$excerpt = wp_trim_words( self::get_random_description( $text_elements, 1, wp_rand( 0, count( $text_elements ) - 1 ) ), 25, '.' );
					} else {
						$excerpt = wp_trim_words( $description, 25, '.' );
					}
				}

				$cats = [];
				if ( ! empty( $maybe_terms['category']['ids'] ) ) {
					if ( ! empty( $maybe_terms['category']['random'] ) ) {
						$cats = self::get_random_tags( $maybe_terms['category']['ids'] );
					} else {
						$cats = $maybe_terms['category']['ids'];
					}

					if ( ! empty( $cats ) ) {
						// Map as integers, to link as terms ids.
						$cats = array_map( 'intval', $cats );
					}
				}

				$post = [
					'post_content'  => $description,
					'post_excerpt'  => $excerpt,
					'post_name'     => sanitize_title( $name ),
					'post_title'    => $name,
					'post_status'   => $status,
					'post_type'     => $info['post_type'],
					'post_date'     => $date,
					'tags_input'    => $tags,
					'post_parent'   => (int) $info['post_parent'],
					'post_category' => $cats,
				];

				$post    = apply_filters( 'spp_prepare_post_data', $post );
				$post_id = wp_insert_post( $post, true );
				if ( ! empty( $post_id ) ) {
					do_action( 'spp_after_post_inserted', $post_id, $post );
					update_post_meta( $post_id, 'spp_sample', 1 );

					if ( 0 === $info['has_sticky'] ) {
						if ( 1 === wp_rand( 0, 1 ) ) {
							stick_post( $post_id );
						}
					} elseif ( 1 === $info['has_sticky'] ) {
						stick_post( $post_id );
					}

					if ( ! empty( $maybe_terms ) ) {
						foreach ( $maybe_terms as $tax => $terms ) {
							if ( 'category' === $tax ) {
								// This is mapped separately.
								continue;
							}
							if ( ! empty( $terms['ids'] ) ) {
								if ( ! empty( $terms['random'] ) ) {
									$ids = self::get_random_tags( $terms['ids'] );
								} else {
									$ids = $terms['ids'];
								}
							}
							if ( ! empty( $ids ) ) {
								// Map as integers, to link as terms ids.
								$ids = array_map( 'intval', $ids );
								wp_set_object_terms( $post_id, $ids, $tax, true );
							}
						}
					}
					if ( ! empty( $maybe_meta ) ) {
						foreach ( $maybe_meta as $meta ) {
							update_post_meta( $post_id, $meta['meta_key'], $meta['meta_value'] );
						}
					}

					do_action( 'spp_after_post_updated', $post_id, $post );

					$photo_src = '';
					if ( ! empty( $photos ) ) {
						$photo = self::select_random_image( $photos );
						$photo = apply_filters( 'spp_before_post_image_attached', $photo, $post_id );
						if ( ! empty( $photo ) ) {
							// Set the image as post featured image.
							update_post_meta( (int) $post_id, '_thumbnail_id', $photo );
							do_action( 'spp_after_post_image_attached', $post_id, $photo );

							$src       = wp_get_attachment_image_src( $photo, 'thumbnail' );
							$photo_src = ( ! empty( $src[0] ) ) ? $src[0] : '';
						}
					}

					$image_embed    = ( ! empty( $photo_src ) ) ? '<img src="' . esc_url( $photo_src ) . '" width="80" style="max-width: 100%;" loading="lazy">' : '<span></span>';
					$return_result .= '
					<li>
						<div class="row-span one-three">
							' . $image_embed . '
							<div style="text-align: right">
								<a href="' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . '" class="button">' . __( 'Edit post', 'spp' ) . '</a>
								<br>' . __( 'Status', 'spp' ) . ' <em class="tag-preview">' . $status . '</em>
								<br>' . __( 'Date', 'spp' ) . ' <em class="tag-preview">' . $date . '</em>
							</div>
						</div>
						<hr><h2>' . $name . '</h2>';
					if ( count( $tags ) !== 0 ) {
						$return_result .= '<br><b>' . __( 'Tags', 'spp' ) . '</b>: <em class="tag-preview">' . implode( ', ', $tags ) . '</em> ';
					}

					$post_categories = get_the_terms( $post_id, 'category' );
					if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
						$categories     = wp_list_pluck( $post_categories, 'name' );
						$return_result .= '<br><b>' . __( 'Categories', 'spp' ) . '</b>: <em class="tag-preview">' . implode( ', ', $categories ) . '</em>';
					}

					$first = explode( '</p>', $description );
					$first = $first[0];

					$return_result .= '<p>' . wp_trim_words( wp_strip_all_tags( $first ), 20 ) . '</p>
						<div class="clear"></div>
					</li>
					';
				}
			}
		}
		$return_result .= '</ol>';
		echo $return_result; // phpcs:ignore

		++ $last;
		?>
		<input type="hidden" id="spp_latest_counter" value="<?php echo (int) $last; ?>">
		<?php
	}

	/**
	 * Make media image from URL and returns the new ID.
	 *
	 * @param string $file_url An image URL.
	 * @return integer
	 */
	public static function make_image_from_url( $file_url = '' ) { // phpcs:ignore
		$attach_id = 0;
		if ( ! empty( $file_url ) ) {
			if ( ! empty( intval( $file_url ) ) ) {
				update_post_meta( $file_url, 'spp_sample', 1 );
				return (int) $file_url;
			}

			$url_hash = str_replace( 'https:', '', $file_url );
			$url_hash = str_replace( 'http:', '', $url_hash );
			$url_hash = md5( $url_hash );
			// Identify the attachment already created, so we do not generate the same one.
			$args  = [
				'post_type'   => 'attachment',
				'post_status' => 'any',
				'meta_query'  => [ // phpcs:ignore
					'relation' => 'AND',
					[
						'key'     => 'spp_sample',
						'value'   => 1,
						'compare' => '=',
					],
					[
						'key'     => 'spp_sample_url',
						'value'   => $url_hash,
						'compare' => '=',
					],
				],
				'fields'      => 'ids',
			];
			$posts = new WP_Query( $args );
			if ( ! empty( $posts->posts ) ) {
				// This means that this image was already uploaded.
				return (int) reset( $posts->posts );
			}

			// Attempt to create a new image.
			$new_file_content = '';

			// Let's fetch the remote image.
			$response = wp_remote_get( $file_url );
			$code     = wp_remote_retrieve_response_code( $response );
			if ( 200 === $code ) {
				// Seems that we got a successful response from the remore URL.
				$content_type = wp_remote_retrieve_header( $response, 'content-type' );
				if ( ! empty( $content_type ) && substr_count( $content_type, 'image/' ) ) {
					// Seems that the content type is an image, let's get the body as the file content.
					$new_file_content = wp_remote_retrieve_body( $response );
				}
			} else {
				if ( empty( $new_file_content ) && substr_count( $file_url, get_site_url() ) ) {
					$new_file_content = @file_get_contents( $file_url ); // phpcs:ignore
				}

				if ( empty( $new_file_content ) ) {
					// Maybe try the non-https version.
					$file_url = str_replace( 'https', 'http', $file_url );
					$response = wp_remote_get( $file_url );
					$code     = wp_remote_retrieve_response_code( $response );
					if ( 200 === $code ) {
						// Seems that we got a successful response from the remore URL.
						$content_type = wp_remote_retrieve_header( $response, 'content-type' );
						if ( ! empty( $content_type ) && substr_count( $content_type, 'image/' ) ) {
							// Seems that the content type is an image, let's get the body as the file content.
							$new_file_content = wp_remote_retrieve_body( $response );
						}
					}
				}
			}
			if ( empty( $new_file_content ) ) {
				$new_file_content = @file_get_contents( $file_url ); // phpcs:ignore
			}

			if ( ! empty( $new_file_content ) ) {
				$parts        = wp_parse_url( $file_url );
				$new_filename = basename( $parts['path'] );
				$upload       = wp_upload_bits( $new_filename, null, $new_file_content );
				if ( empty( $upload['error'] ) ) {
					// Prepare an array of post data for the attachment.
					$attachment = [
						'guid'           => $upload['url'],
						'post_mime_type' => $upload['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', $new_filename ),
						'post_status'    => 'inherit',
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_type'      => 'attachment',
					];

					// Insert the attachment.
					$attach_id = wp_insert_attachment( $attachment, $upload['file'] );
					if ( ! empty( $attach_id ) ) {
						$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
						wp_update_attachment_metadata( $attach_id, $attach_data );
						update_post_meta( $attach_id, 'spp_sample', 1 );
						update_post_meta( $attach_id, 'spp_sample_url', $url_hash );
					}
				}
			}
		}
		return $attach_id;
	}

	/**
	 * Cleanup populated posts.
	 *
	 * @return void
	 */
	public static function cleanup_plugin_posts() {
		// Identify the plugin populated posts and attempt to remove these.
		$args  = [
			'post_type'      => 'any',
			'post_status'    => 'any',
			'meta_query'     => [ // phpcs:ignore
				[
					'key'     => 'spp_sample',
					'value'   => 1,
					'compare' => '=',
				],
			],
			'fields'         => 'ids',
			'posts_per_page' => -1,
		];
		$posts = new WP_Query( $args );
		if ( ! empty( $posts->posts ) ) {
			foreach ( $posts->posts as $id ) {
				wp_delete_post( $id );
			}
		}
	}

	/**
	 * Append the plugin URL.
	 *
	 * @param array $links The plugin links.
	 * @return array
	 */
	public static function plugin_action_links( array $links ) : array {
		$all   = [];
		$all[] = '<a href="' . esc_url( self::$plugin_url ) . '">' . __( 'Settings', 'ssp' ) . '</a>';
		$all[] = '<a href="https://iuliacazan.ro/easy-populate-posts">' . __( 'Plugin URL', 'ssp' ) . '</a>';
		$all   = array_merge( $all, $links );
		return $all;
	}

	/**
	 * The actions to be executed when the plugin is activated.
	 *
	 * @return void
	 */
	public static function activate_plugin() {
		update_option( 'spp_settings', self::$settings );
		update_option( 'spp_settings_groups', self::$settings_groups );
		set_transient( self::PLUGIN_TRANSIENT, true );
	}

	/**
	 * The actions to be executed when the plugin is deactivated.
	 *
	 * @return void
	 */
	public static function deactivate_plugin() {
		self::plugin_admin_notices_cleanup( false );
		if ( ! empty( self::$settings['cleanup_on_deactivate'] ) ) {
			self::cleanup_plugin_posts();
		}
		delete_option( 'spp_settings' );
		delete_option( 'spp_settings_groups' );
		// Attempt to remove the legacy temporary images, the new version is handling the images differently.
		if ( file_exists( self::$settings['legacy_images_path'] )
			&& is_dir( self::$settings['legacy_images_path'] ) ) {
			$dir = opendir( self::$settings['legacy_images_path'] );
			@rmdir( self::$settings['legacy_images_path'], true ); // phpcs:ignore
			while ( ( false !== ( $file = readdir( $dir ) ) ) ) {  // phpcs:ignore
				if ( '.' !== $file && '..' !== $file ) {
					@unlink( self::$settings['legacy_images_path'] . $file ); // phpcs:ignore
				}
			}
			closedir( $dir );
			@rmdir( self::$settings['legacy_images_path'] ); // phpcs:ignore
		}
	}

	/**
	 * The actions to be executed when the plugin is updated.
	 *
	 * @return void
	 */
	public static function plugin_ver_check() {
		$opt = str_replace( '-', '_', self::PLUGIN_TRANSIENT ) . '_db_ver';
		$dbv = get_option( $opt, 0 );
		if ( SPP_PLUGIN_VERSION !== (float) $dbv ) {
			update_option( $opt, SPP_PLUGIN_VERSION );
			self::activate_plugin();
		}
	}

	/**
	 * Execute notices cleanup.
	 *
	 * @param  boolean $ajax Is AJAX call.
	 * @return void
	 */
	public static function plugin_admin_notices_cleanup( $ajax = true ) { // phpcs:ignore
		// Delete transient, only display this notice once.
		delete_transient( self::PLUGIN_TRANSIENT );

		if ( true === $ajax ) {
			// No need to continue.
			wp_die();
		}
	}

	/**
	 * Admin notices.
	 *
	 * @return void
	 */
	public static function plugin_admin_notices() {
		if ( apply_filters( 'spp_filter_remove_update_info', false ) ) {
			return;
		}

		$maybe_trans = get_transient( self::PLUGIN_TRANSIENT );
		if ( ! empty( $maybe_trans ) ) {
			$slug         = md5( SPP_PLUGIN_SLUG );
			$title        = __( 'Easy Populate Posts', 'spp' );
			$donate       = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ&item_name=Support for development and maintenance (' . rawurlencode( self::PLUGIN_NAME ) . ')';
			$maybe_pro    = '';
			$other_notice = sprintf(
				// Translators: %1$s - extensions URL.
				__( '%5$sCheck out my other <a href="%1$s" target="_blank" rel="noreferrer">%2$s free plugins</a> on WordPress.org and the <a href="%3$s" target="_blank" rel="noreferrer">%4$s other extensions</a> available!', 'spp' ),
				'https://profiles.wordpress.org/iulia-cazan/#content-plugins',
				'<span class="dashicons dashicons-heart"></span>',
				'https://iuliacazan.ro/shop/',
				'<span class="dashicons dashicons-star-filled"></span>',
				$maybe_pro
			);
			?>

			<div id="item-<?php echo esc_attr( $slug ); ?>" class="updated notice">
				<a href="<?php echo esc_url( admin_url( 'tools.php?page=populate-posts-settings' ) ); ?>" class="icon"><img src="<?php echo esc_url( SPP_PLUGIN_URL . 'assets/images/icon-128x128.gif' ); ?>"></a>
				<div class="content">
					<div>
						<h3>
							<?php
							echo wp_kses_post( sprintf(
								// Translators: %1$s - plugin name.
								__( '%1$s plugin was activated!', 'spp' ),
								'<b>' . $title . '</b>'
							) );
							?>
						</h3>
						<div class="notice-other-items"><div><?php echo wp_kses_post( $other_notice ); ?></div></div>
					</div>

					<div>
						<?php
						echo wp_kses_post( sprintf(
							// Translators: %1$s - donate URL, %2$s - rating, %3$s - thanks.
							__( 'This plugin is free to use, but not to operate. Please consider supporting my services by making a <a href="%1$s" target="_blank" rel="noreferrer">donation</a>. It would make me very happy if you would leave a %2$s rating. %3$s', 'spp' ),
							$donate,
							'<a href="' . self::PLUGIN_SUPPORT_URL . 'reviews/?rate=5#new-post" class="rating" target="_blank" rel="noreferrer" title="' . esc_attr__( 'A huge thanks in advance!', 'spp' ) . '">★★★★★</a>',
							__( 'A huge thanks in advance!', 'spp' )
						) );
						?>
						<a class="notice-plugin-donate" href="<?php echo esc_url( $donate ); ?>" target="_blank"><img src="<?php echo esc_url( SPP_PLUGIN_URL . 'assets/images/buy-me-a-coffee.png?v=' . SPP_PLUGIN_VERSION ); ?>" width="280"></a>
					</div>
				</div>
				<span class="dashicons dashicons-no" onclick="dismiss_notice_for_<?php echo esc_attr( $slug ); ?>()"></span>
			</div>
			<?php
			$style = '#trans123super{--color-bg:rgba(250,203,53,0.2);--color-border:rgb(250,203,53);--color-border-left:rgb(250,203,53);align-items:stretch;display:inline-flex;flex-direction:row;flex-wrap:nowrap;flex:0;margin:0;margin-bottom:20px;padding:0;gap:20px;max-width:100%;overflow-x:hidden;width:100%;border-left-color:var(--color-border-left);background:var(--color-bg);border-color:var(--color-border);box-sizing:border-box;padding:0;border-left-width:20px;} #trans123super .dashicons-no{flex:0 0 32px;font-size:32px;cursor:pointer;} #trans123super .icon{position:relative;align-content:stretch;flex:0 0 128px;} #trans123super .icon img{position:absolute;object-fit:cover;object-position:center;height:100%;width:100%;} #trans123super .content{align-items:stretch;align-items:center;display:inline-flex;flex-direction:row;flex-wrap:nowrap;gap:0;max-width:100%;overflow-x:hidden;width:100%;} #trans123super .content .dashicons{color:var(--color-border);} #trans123super .content > *{color:#666; padding:20px;width:50%;}@media screen and (max-width:600px){ #trans123super{flex-wrap:wrap;} #trans123super .icon{flex:0 0 100%; display:none;} #trans123super .content{flex-wrap:wrap;} #trans123super .content > *{width:100%;}} #trans123super h3{margin:0 0 10px 0;color:#666} #trans123super h3 b{color:#000} #trans123super a{color:#000;text-decoration:none;} #trans123super .notice-plugin-donate{display:block;margin-top:10px;text-align:right;}';
			$style = str_replace( '#trans123super', '#item-' . esc_attr( $slug ), $style );
			echo '<style>' . $style . '</style>'; //phpcs:ignore
			?>
			<script>function dismiss_notice_for_<?php echo esc_attr( $slug ); ?>() { document.getElementById( 'item-<?php echo esc_attr( $slug ); ?>' ).style='display:none'; fetch( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=plugin-deactivate-notice-<?php echo esc_attr( SPP_PLUGIN_SLUG ); ?>' ); }</script>
			<?php
		}
	}
}

$spp = SISANU_Popupate_Posts::get_instance();
register_activation_hook( __FILE__, [ $spp, 'activate_plugin' ] );
register_deactivation_hook( __FILE__, [ $spp, 'deactivate_plugin' ] );
