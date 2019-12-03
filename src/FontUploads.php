<?php
/**
 * Allows uploading fonts to WordPress and usage in Customizer Kirki fields.
 * This is based on the fantastic Kirki by Ari Stathopoulos (@aristath)
 *
 * @package   Kirki
 * @category  Modules
 * @author    Philipp Wellmer (@ouun)
 * @copyright Copyright (c) 2019, Philipp Wellmer (@ouun)
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0
 */

namespace Kirki\Module;

/**
 * Class Fonts_Upload
 *
 * @package Kirki\Module
 */
class FontUploads {

	/**
	 * An array containing allowed mime types.
	 *
	 * @see https://stackoverflow.com/questions/2871655/proper-mime-type-for-fonts
	 * @access private
	 * @since 1.0
	 * @var array
	 */
	public $allowed_mimes = array(
		'woff'  => 'application/font-woff',
		'woff2' => 'application/font-woff2',
	);

	/**
	 * The class constructor
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		// Add fonts to WP allowed upload file types
		add_filter( 'upload_mimes', array( $this, 'add_fonts_to_mimes' ), 1, 1 );

		// Add Fonts to Kirki stack of Standard-Fonts
		add_filter( 'kirki_fonts_standard_fonts', array( $this, 'add_uploaded_fonts_to_standard_stack' ), 1, 100 );

		// Add CSS to dynamic Kirki output
		add_action( 'kirki_dynamic_css', array( $this, 'get_uploaded_fonts_css_output' ), 0, 20 );
	}

	/**
	 * Get all uploaded fonts
	 *
	 * @return array \WP_Posts objects of uploaded fonts
	 */
	public function get_uploaded_fonts() {
		$args = array(
			'post_type'      => 'attachment',
			'posts_per_page' => 10,
			'post_mime_type' => apply_filters( 'kirki_upload_allowed_fonts_mimes', $this->allowed_mimes ),
			'post_status'    => array( 'publish', 'inherit' ),
		);

		$attachments = new \WP_Query( $args );

		return $attachments->posts;
	}

	/**
	 * Add fonts to allowed mime types
	 *
	 * @param $mimes
	 *
	 * @return mixed
	 */
	public function add_fonts_to_mimes( $mimes ) {
		$allowed_mimes = apply_filters( 'kirki_upload_allowed_fonts_mimes', $this->allowed_mimes );

		foreach ( $allowed_mimes as $ext => $mime ) {
			$mimes[ $ext ] = $mime;
		}

		// Adds Filter to customize mime types
		return $mimes;
	}

	/**
	 * Adds uploaded fonts to fonts array
	 *
	 * @param $fonts
	 *
	 * @return array
	 */
	public function add_uploaded_fonts_to_standard_stack( $fonts ) {

		foreach ( self::get_uploaded_fonts() as $add_font ) {
			$fonts[ $add_font->post_name ] = array(
				'label' => $add_font->post_title,
				'stack' => $add_font->post_title,
			);
		}

		return $fonts;
	}

	/**
	 * Generate & echo CSS for dynamic Kirki output
	 */
	public function get_uploaded_fonts_css_output() {
		foreach ( self::get_uploaded_fonts() as $add_font ) {
			$family = $add_font->post_name;
			$url    = wp_get_attachment_url( $add_font->ID );
			// $path   = get_attached_file( $add_font->ID );
			$type = wp_check_filetype( $url );

			if ( $url ) {
				echo wp_strip_all_tags( "@font-face{font-display:swap;font-family:\"{$family}\";src:url(\"{$url}\");format(\"{$type['ext']}\");}" );
			}
		}
	}

}