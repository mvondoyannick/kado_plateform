<?php
/**
 * Storefront Powerpack Frontend Layout Class
 *
 * @author   WooThemes
 * @package  Storefront_Powerpack
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SP_Frontend_Layout' ) ) :

	/**
	 * The Frontend class.
	 */
	class SP_Frontend_Layout extends SP_Frontend {
		/**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_filter( 'body_class', array( $this, 'body_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'script' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 999 );
			add_action( 'init', array( $this, 'reregister_theme_support' ) );
		}

		/**
		 * Storefront Powerpack Body Class
		 *
		 * @param array $classes array of classes applied to the body tag.
		 * @see get_theme_mod()
		 */
		public function body_class( $classes ) {
			if ( 'max-width' === get_theme_mod( 'sp_max_width' ) ) {
				$classes[] = 'sp-max-width';
			}

			if ( 'frame' === get_theme_mod( 'sp_content_frame' ) ) {
				$classes[] = 'sp-fixed-width';
			}

			return $classes;
		}

		/**
		 * Enqueue styles and scripts.
		 *
		 * @since   1.0.0
		 * @return  void
		 */
		public function script() {
			if ( 'max-width' === get_theme_mod( 'sp_max_width' ) || 'frame' === get_theme_mod( 'sp_content_frame' ) ) {
				wp_enqueue_style( 'sp-layout', SP_PLUGIN_URL . 'includes/customizer/layout/assets/css/layout.css', '', storefront_powerpack()->version );
			}
		}

		/**
		 * Add CSS in <head> for styles handled by the Customizer
		 *
		 * @return  void
		 * @since   1.0.0
		 */
		public function add_customizer_css() {
			$content_background_color = get_theme_mod( 'sp_content_frame_background' );

			$wc_style = '
				.sp-fixed-width .site {
					background-color:' . $content_background_color . ';
				}
			';

			wp_add_inline_style( 'storefront-style', $wc_style );
		}

		/**
		 * Reregister theme features to remove declared image sizes
		 *
		 * @return void
		 * @since 1.4.12
		 */
		public function reregister_theme_support() {
			if ( 'max-width' !== get_theme_mod( 'sp_max_width' ) ) {
				return;
			}

			$theme_support = get_theme_support( 'woocommerce' );
			$theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

			if ( $theme_support ) {
				$sizes = array(
					'single_image_width',
					'thumbnail_image_width',
					'woocommerce_gallery_thumbnail'
				);

				foreach ( $sizes as $size ) {
					if ( isset( $theme_support[ $size ] ) ) {
						unset( $theme_support[ $size ] );
					}
				}

				add_theme_support( 'woocommerce', $theme_support );
			}
		}

		/**
		 * Change image size when the layout is set to "Max Width"
		 *
		 * @param string $size the image sized to be filtered.
		 * @deprecated 1.4.12
		 * @return void
		 */
		public function change_image_size( $size ) {
			if ( function_exists( 'wc_deprecated_function' ) ) {
				wc_deprecated_function( __FUNCTION__, '1.4.12' );
			} else {
				_deprecated_function( __FUNCTION__, '1.4.12' );
			}
		}
	}

endif;

return new SP_Frontend_Layout();