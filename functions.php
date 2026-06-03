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
		'hs-ad-home-top'       => array( 'Publicidad home arriba', 'Banner superior de la portada. Tamaño sugerido: 728x90.' ),
		'hs-ad-home-strip'     => array( 'Publicidad home franja', 'Franja horizontal debajo del banner superior. Tamaño sugerido: 500x160.' ),
		'hs-ad-home-middle'    => array( 'Publicidad home medio', 'Banner intermedio de la portada. Tamaño sugerido: 728x90.' ),
		'hs-ad-home-square-1'  => array( 'Publicidad home cuadrado 1', 'Primer cuadrado publicitario entre secciones. Tamaño sugerido: 450x450.' ),
		'hs-ad-home-square-2'  => array( 'Publicidad home cuadrado 2', 'Segundo cuadrado publicitario entre secciones. Tamaño sugerido: 450x450.' ),
		'hs-ad-home-square-3'  => array( 'Publicidad home cuadrado 3', 'Tercer cuadrado publicitario entre secciones. Tamaño sugerido: 450x450.' ),
		'hs-ad-home-bottom'    => array( 'Publicidad home abajo', 'Banner inferior de la portada. Tamaño sugerido: 728x90.' ),
		'hs-ad-single-top'      => array( 'Publicidad single arriba', 'Banner superior del sidebar de una noticia. Tamaño sugerido: 300x250.' ),
		'hs-ad-single-middle'   => array( 'Publicidad single medio', 'Banner intermedio del sidebar de una noticia. Tamaño sugerido: 300x250.' ),
		'hs-ad-single-inline'   => array( 'Publicidad dentro de noticia', 'Banner insertado dentro del cuerpo de una noticia. Tamaño sugerido: 728x90.' ),
		'hs-ad-single-square-1' => array( 'Publicidad single cuadrado 1', 'Primer cuadrado al pie de cada noticia. Tamaño sugerido: 450x450.' ),
		'hs-ad-single-square-2' => array( 'Publicidad single cuadrado 2', 'Segundo cuadrado al pie de cada noticia. Tamaño sugerido: 450x450.' ),
		'hs-ad-single-square-3' => array( 'Publicidad single cuadrado 3', 'Tercer cuadrado al pie de cada noticia. Tamaño sugerido: 450x450.' ),
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

/**
 * Removes the featured image from the post content on single posts.
 *
 * Prevents duplication when the template already renders the thumbnail
 * via the_post_thumbnail() above the content.
 *
 * @param string $content Filtered post content.
 * @return string
 */
function hs_remove_featured_from_content( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$thumb_id = get_post_thumbnail_id();

	if ( ! $thumb_id ) {
		return $content;
	}

	// Remove Gutenberg image block that wraps the featured image (matched by attachment ID).
	$content = preg_replace(
		'/<!-- wp:image[^>]*"id"\s*:\s*' . intval( $thumb_id ) . '[^>]*-->[\s\S]*?<!-- \/wp:image -->/U',
		'',
		$content
	);

	// Remove rendered <figure> with the wp-image-{id} class (classic editor or rendered block).
	$content = preg_replace(
		'/<figure\b[^>]*\bwp-image-' . intval( $thumb_id ) . '\b[^>]*>[\s\S]*?<\/figure>/U',
		'',
		$content
	);

	return $content;
}
add_filter( 'the_content', 'hs_remove_featured_from_content', 5 );

/**
 * Blocksy inyecta el título de la página via blocksy:hero:output.
 * Como page.php ya tiene su propio header con h1, se suprime el hero
 * de Blocksy en páginas estáticas para evitar que el título aparezca dos veces.
 */
add_action(
	'template_redirect',
	function () {
		if ( is_page() ) {
			remove_all_actions( 'blocksy:hero:output' );
		}
	},
	1
);

/**
 * Configuración automática post-activación:
 *   - Crea categoría "Destacadas" si no existe.
 *   - Crea etiquetas "urgente" y "en-desarrollo" si no existen.
 *   - Asigna el menú de footer si no hay ninguno asignado.
 *   - Limpia los bloques Heading iniciales de las páginas institucionales.
 *   - Agrega descripciones editoriales a las categorías principales.
 *
 * Se ejecuta una sola vez por tarea (cada tarea tiene su propia opción de control).
 */
add_action( 'init', 'hs_ensure_site_config', 20 );

function hs_ensure_site_config() {
	// ── 1. Etiquetas urgente y en-desarrollo ─────────────────────────────────
	if ( ! get_option( 'hs_created_breaking_tags' ) ) {
		foreach ( array( 'urgente', 'en-desarrollo' ) as $tag_slug ) {
			if ( ! get_term_by( 'slug', $tag_slug, 'post_tag' ) ) {
				wp_insert_term(
					str_replace( '-', ' ', ucfirst( $tag_slug ) ),
					'post_tag',
					array( 'slug' => $tag_slug )
				);
			}
		}
		update_option( 'hs_created_breaking_tags', 1 );
	}

	// ── 3. Menú del footer ───────────────────────────────────────────────────
	if ( ! get_option( 'hs_assigned_footer_menu' ) ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		if ( empty( $locations['footer'] ) ) {
			$menu = get_term_by( 'name', 'Pie de HolaSalta', 'nav_menu' );
			if ( ! $menu ) {
				// Buscar cualquier menú que contenga "footer" o "pie" en el nombre.
				$all_menus = wp_get_nav_menus();
				foreach ( $all_menus as $m ) {
					if ( stripos( $m->name, 'pie' ) !== false || stripos( $m->name, 'footer' ) !== false ) {
						$menu = $m;
						break;
					}
				}
			}
			if ( $menu ) {
				$locations['footer'] = $menu->term_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}
		update_option( 'hs_assigned_footer_menu', 1 );
	}

	// ── 4. Limpiar headings duplicados en páginas institucionales ────────────
	if ( ! get_option( 'hs_cleaned_page_headings' ) ) {
		$slugs = array(
			'quienes-somos',
			'contacto',
			'publicidad',
			'politica-editorial',
			'politica-privacidad',
			'terminos-condiciones',
		);
		foreach ( $slugs as $slug ) {
			$page = get_page_by_path( $slug );
			if ( ! $page ) {
				continue;
			}
			$clean = preg_replace(
				'/<!-- wp:heading[^>]*-->[^<]*<h[1-6][^>]*>[^<]*<\/h[1-6]>[^<]*<!-- \/wp:heading -->\s*/i',
				'',
				$page->post_content
			);
			if ( $clean !== $page->post_content ) {
				wp_update_post(
					array(
						'ID'           => $page->ID,
						'post_content' => $clean,
					)
				);
			}
		}
		update_option( 'hs_cleaned_page_headings', 1 );
	}

	// ── 5. Descripciones editoriales de categorías principales ───────────────
	if ( ! get_option( 'hs_added_cat_descriptions' ) ) {
		$descriptions = array(
			'salta'           => 'Noticias locales de la ciudad y provincia de Salta: gestión municipal, barrios, obras y servicios.',
			'policiales'      => 'Información policial y judicial de Salta: hechos, operativos, sentencias y seguridad ciudadana.',
			'nacionales'      => 'Las noticias del país que impactan en Salta: política nacional, economía y sucesos de Argentina.',
			'deportes'        => 'Deporte salteño y nacional: fútbol, básquet, atletismo y los atletas que representan a Salta.',
			'espectaculos'    => 'Espectáculos, entretenimiento y cultura popular: cine, música, televisión y farándula.',
			'internacionales' => 'El mundo desde Salta: noticias internacionales que importan a nuestra comunidad.',
			'sabias-que'      => 'Curiosidades, datos sorprendentes y contenido de interés general para los salteños.',
		);
		foreach ( $descriptions as $slug => $desc ) {
			$term = get_term_by( 'slug', $slug, 'category' );
			if ( $term && empty( $term->description ) ) {
				wp_update_term( $term->term_id, 'category', array( 'description' => $desc ) );
			}
		}
		update_option( 'hs_added_cat_descriptions', 1 );
	}

	// ── 6. Crear categorías editoriales faltantes ────────────────────────────
	if ( ! get_option( 'hs_ensured_editorial_cats' ) ) {
		foreach ( hs_get_editorial_sections() as $slug => $label ) {
			if ( ! get_term_by( 'slug', $slug, 'category' ) ) {
				wp_insert_term( $label, 'category', array( 'slug' => $slug ) );
			}
		}
		update_option( 'hs_ensured_editorial_cats', 1 );
	}
}

/**
 * One-time cleanup: elimina todas las categorías vacías que no sean
 * editoriales ni "Uncategorized". Corre una sola vez y se desactiva solo.
 */
function hs_cleanup_spurious_categories() {
	if ( get_option( 'hs_cleaned_categories' ) ) {
		return;
	}

	$keep = array_merge(
		array( 'uncategorized' ),
		array_keys( hs_get_editorial_sections() )
	);

	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'fields'     => 'all',
			'number'     => 0,
		)
	);

	if ( is_wp_error( $terms ) ) {
		return;
	}

	foreach ( $terms as $term ) {
		if ( in_array( $term->slug, $keep, true ) ) {
			continue;
		}
		if ( (int) $term->count === 0 ) {
			wp_delete_term( $term->term_id, 'category' );
		}
	}

	update_option( 'hs_cleaned_categories', 1 );
}
add_action( 'init', 'hs_cleanup_spurious_categories', 30 );
