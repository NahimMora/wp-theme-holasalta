<?php
/**
 * HolaSalta Child theme bootstrap.
 *
 * @package HolaSalta_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HS_THEME_VERSION', '1.1.0' );

require_once get_stylesheet_directory() . '/inc/template-tags.php';
require_once get_stylesheet_directory() . '/inc/setup-site.php';

/**
 * Registers the theme features used by the news templates.
 */
function hs_theme_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_image_size( 'hs-card', 720, 405, true );
	add_image_size( 'hs-hero', 1280, 720, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menú principal HolaSalta', 'holasalta-child' ),
			'footer'  => __( 'Menú del pie HolaSalta', 'holasalta-child' ),
		)
	);
}
add_action( 'after_setup_theme', 'hs_theme_setup', 20 );

/**
 * Loads the lightweight child theme styles.
 */
function hs_enqueue_assets() {
	$stylesheet_path = get_stylesheet_directory() . '/assets/css/holasalta.css';
	$stylesheet_ver  = file_exists( $stylesheet_path ) ? (string) filemtime( $stylesheet_path ) : HS_THEME_VERSION;
	$script_path     = get_stylesheet_directory() . '/assets/js/holasalta.js';
	$script_ver      = file_exists( $script_path ) ? (string) filemtime( $script_path ) : HS_THEME_VERSION;

	wp_enqueue_style(
		'holasalta-child-style',
		get_stylesheet_uri(),
		array( 'ct-main-styles' ),
		HS_THEME_VERSION
	);

	wp_enqueue_style(
		'holasalta-news',
		get_stylesheet_directory_uri() . '/assets/css/holasalta.css',
		array( 'holasalta-child-style' ),
		$stylesheet_ver
	);

	wp_enqueue_script(
		'holasalta-sections',
		get_stylesheet_directory_uri() . '/assets/js/holasalta.js',
		array(),
		$script_ver,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hs_enqueue_assets', 20 );

/**
 * Registers editorial and advertising widget areas.
 */
function hs_register_widget_areas() {
	$sidebars = array(
		'hs-news-sidebar'     => array( 'Sidebar de noticias', 'Widgets adicionales para el sidebar de las noticias.' ),
		'hs-ad-home-top'      => array( 'Publicidad home arriba', 'Banner superior de la portada. Tamaño sugerido: 728x90.' ),
		'hs-ad-home-middle'   => array( 'Publicidad home medio', 'Banner intermedio de la portada. Tamaño sugerido: 728x90.' ),
		'hs-ad-home-bottom'   => array( 'Publicidad home abajo', 'Banner inferior de la portada. Tamaño sugerido: 728x90.' ),
		'hs-ad-single-top'    => array( 'Publicidad single arriba', 'Banner superior del sidebar de una noticia. Tamaño sugerido: 300x250.' ),
		'hs-ad-single-middle' => array( 'Publicidad single medio', 'Banner intermedio del sidebar de una noticia. Tamaño sugerido: 300x250.' ),
		'hs-ad-single-inline' => array( 'Publicidad dentro de noticia', 'Banner insertado dentro del cuerpo de una noticia. Tamaño sugerido: 728x90.' ),
		'hs-ad-category-top'  => array( 'Publicidad de categorías', 'Banner superior de archivos y categorías. Tamaño sugerido: 728x90.' ),
	);

	foreach ( $sidebars as $id => $sidebar ) {
		register_sidebar(
			array(
				'name'          => __( $sidebar[0], 'holasalta-child' ),
				'id'            => $id,
				'description'   => __( $sidebar[1], 'holasalta-child' ),
				'before_widget' => '<div id="%1$s" class="hs-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="hs-widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}
}
add_action( 'widgets_init', 'hs_register_widget_areas' );

/**
 * Inserts one reusable ad slot after the third paragraph of a news article.
 *
 * @param string $content Filtered post content.
 * @return string
 */
function hs_insert_inline_ad( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || is_feed() ) {
		return $content;
	}

	ob_start();
	hs_render_ad_slot( 'single-inline', '728x90' );
	$ad = ob_get_clean();

	$closing_tag = '</p>';
	$offset      = 0;

	for ( $paragraph = 0; $paragraph < 3; $paragraph++ ) {
		$position = strpos( $content, $closing_tag, $offset );

		if ( false === $position ) {
			return $content . $ad;
		}

		$offset = $position + strlen( $closing_tag );
	}

	return substr( $content, 0, $offset ) . $ad . substr( $content, $offset );
}
add_filter( 'the_content', 'hs_insert_inline_ad', 20 );
