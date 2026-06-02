<?php
/**
 * One-time site scaffold created when the child theme is activated.
 *
 * @package HolaSalta_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Finds an existing page or creates a published one.
 *
 * @param string $title   Page title.
 * @param string $slug    Page slug.
 * @param string $content Initial editable content.
 * @return int Page ID, or zero when creation fails.
 */
function hs_setup_page( $title, $slug, $content = '' ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $page instanceof WP_Post ) {
		return (int) $page->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		),
		true
	);

	return is_wp_error( $page_id ) ? 0 : (int) $page_id;
}

/**
 * Finds an existing category or creates it.
 *
 * @param string $name Category display name.
 * @param string $slug Category slug.
 * @return int Term ID, or zero when creation fails.
 */
function hs_setup_category( $name, $slug ) {
	$term = get_term_by( 'slug', $slug, 'category' );

	if ( $term instanceof WP_Term ) {
		return (int) $term->term_id;
	}

	$result = wp_insert_term(
		$name,
		'category',
		array(
			'slug' => $slug,
		)
	);

	return is_wp_error( $result ) ? 0 : (int) $result['term_id'];
}

/**
 * Finds or creates a navigation menu.
 *
 * @param string $name Menu name.
 * @return int Menu term ID, or zero when creation fails.
 */
function hs_setup_menu( $name ) {
	$menu = wp_get_nav_menu_object( $name );

	if ( $menu ) {
		return (int) $menu->term_id;
	}

	$menu_id = wp_create_nav_menu( $name );

	return is_wp_error( $menu_id ) ? 0 : (int) $menu_id;
}

/**
 * Adds one menu object only if it is not already present.
 *
 * @param int    $menu_id   Menu term ID.
 * @param string $title     Menu label.
 * @param string $object    WordPress object type.
 * @param int    $object_id WordPress object ID.
 */
function hs_setup_menu_item( $menu_id, $title, $object, $object_id ) {
	if ( ! $menu_id || ! $object_id ) {
		return;
	}

	$items = wp_get_nav_menu_items( $menu_id );

	if ( $items ) {
		foreach ( $items as $item ) {
			if ( $object === $item->object && (int) $object_id === (int) $item->object_id ) {
				return;
			}
		}
	}

	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-object-id' => $object_id,
			'menu-item-object'    => $object,
			'menu-item-type'      => 'category' === $object ? 'taxonomy' : 'post_type',
			'menu-item-title'     => $title,
			'menu-item-status'    => 'publish',
		)
	);
}

/**
 * Creates the initial editorial structure once.
 */
function hs_run_initial_setup() {
	if ( get_option( 'hs_initial_setup_done' ) ) {
		return;
	}

	$page_content = array(
		'inicio'                => '',
		'ultimas-noticias'      => '',
		'quienes-somos'         => '<!-- wp:heading --><h2>Quiénes somos</h2><!-- /wp:heading --><!-- wp:paragraph --><p>HolaSalta.com es un medio digital local enfocado en informar con claridad, cercanía y responsabilidad sobre los temas que importan en Salta.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Este contenido es editable. Sumá aquí la historia del medio, su equipo y sus canales de contacto.</p><!-- /wp:paragraph -->',
		'contacto'              => '<!-- wp:heading --><h2>Contacto</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Para consultas generales, comunicados de prensa o sugerencias editoriales, escribinos a contacto@holasalta.com.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Reemplazá este correo y agregá aquí tus canales oficiales antes de publicar el sitio.</p><!-- /wp:paragraph -->',
		'publicidad'            => '<!-- wp:heading --><h2>Publicidad</h2><!-- /wp:heading --><!-- wp:paragraph --><p>HolaSalta.com ofrece espacios publicitarios para marcas, comercios y organizaciones que quieran llegar a una audiencia local.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Consultanos por formatos, ubicaciones y disponibilidad. Reemplazá este texto con tus datos comerciales.</p><!-- /wp:paragraph -->',
		'politica-editorial'    => '<!-- wp:heading --><h2>Política editorial</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Trabajamos para publicar información clara, verificable y de interés público. Diferenciamos el contenido editorial de los espacios publicitarios.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Este texto es una base editable. Completalo con los criterios y procesos definitivos del medio.</p><!-- /wp:paragraph -->',
		'politica-privacidad'   => '<!-- wp:heading --><h2>Política de privacidad</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Este sitio puede recopilar datos técnicos básicos y la información que las personas envíen voluntariamente mediante formularios o comentarios.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Este contenido es orientativo y debe revisarse antes de publicar según las herramientas efectivamente utilizadas en el sitio.</p><!-- /wp:paragraph -->',
		'terminos-condiciones'  => '<!-- wp:heading --><h2>Términos y condiciones</h2><!-- /wp:heading --><!-- wp:paragraph --><p>El contenido de HolaSalta.com se publica con fines informativos. Las condiciones definitivas de uso deben adecuarse a la operación real del medio.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Revisá y completá este texto antes de publicar el sitio.</p><!-- /wp:paragraph -->',
	);

	$pages = array(
		'inicio'               => hs_setup_page( 'Inicio', 'inicio', $page_content['inicio'] ),
		'ultimas-noticias'     => hs_setup_page( 'Últimas noticias', 'ultimas-noticias', $page_content['ultimas-noticias'] ),
		'quienes-somos'        => hs_setup_page( 'Quiénes somos', 'quienes-somos', $page_content['quienes-somos'] ),
		'contacto'             => hs_setup_page( 'Contacto', 'contacto', $page_content['contacto'] ),
		'publicidad'           => hs_setup_page( 'Publicidad', 'publicidad', $page_content['publicidad'] ),
		'politica-editorial'   => hs_setup_page( 'Política editorial', 'politica-editorial', $page_content['politica-editorial'] ),
		'politica-privacidad'  => hs_setup_page( 'Política de privacidad', 'politica-privacidad', $page_content['politica-privacidad'] ),
		'terminos-condiciones' => hs_setup_page( 'Términos y condiciones', 'terminos-condiciones', $page_content['terminos-condiciones'] ),
	);

	$categories = array(
		'destacadas' => hs_setup_category( 'Destacadas', 'destacadas' ),
		'salta'      => hs_setup_category( 'Salta', 'salta' ),
		'policiales' => hs_setup_category( 'Policiales', 'policiales' ),
		'politica'   => hs_setup_category( 'Política', 'politica' ),
		'sociedad'   => hs_setup_category( 'Sociedad', 'sociedad' ),
		'deportes'   => hs_setup_category( 'Deportes', 'deportes' ),
		'nacionales' => hs_setup_category( 'Nacionales', 'nacionales' ),
		'economia'   => hs_setup_category( 'Economía', 'economia' ),
		'cultura'    => hs_setup_category( 'Cultura', 'cultura' ),
	);

	$primary_menu = hs_setup_menu( 'HolaSalta Principal' );
	$footer_menu  = hs_setup_menu( 'HolaSalta Footer' );

	hs_setup_menu_item( $primary_menu, 'Inicio', 'page', $pages['inicio'] );
	hs_setup_menu_item( $primary_menu, 'Salta', 'category', $categories['salta'] );
	hs_setup_menu_item( $primary_menu, 'Policiales', 'category', $categories['policiales'] );
	hs_setup_menu_item( $primary_menu, 'Política', 'category', $categories['politica'] );
	hs_setup_menu_item( $primary_menu, 'Sociedad', 'category', $categories['sociedad'] );
	hs_setup_menu_item( $primary_menu, 'Deportes', 'category', $categories['deportes'] );
	hs_setup_menu_item( $primary_menu, 'Nacionales', 'category', $categories['nacionales'] );
	hs_setup_menu_item( $primary_menu, 'Economía', 'category', $categories['economia'] );
	hs_setup_menu_item( $primary_menu, 'Cultura', 'category', $categories['cultura'] );

	hs_setup_menu_item( $footer_menu, 'Quiénes somos', 'page', $pages['quienes-somos'] );
	hs_setup_menu_item( $footer_menu, 'Contacto', 'page', $pages['contacto'] );
	hs_setup_menu_item( $footer_menu, 'Publicidad', 'page', $pages['publicidad'] );
	hs_setup_menu_item( $footer_menu, 'Política editorial', 'page', $pages['politica-editorial'] );
	hs_setup_menu_item( $footer_menu, 'Política de privacidad', 'page', $pages['politica-privacidad'] );
	hs_setup_menu_item( $footer_menu, 'Términos y condiciones', 'page', $pages['terminos-condiciones'] );

	$locations                = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']     = $primary_menu;
	$locations['menu_1']      = $primary_menu;
	$locations['menu_mobile'] = $primary_menu;
	$locations['footer']      = $footer_menu;
	set_theme_mod( 'nav_menu_locations', $locations );

	if ( $pages['inicio'] && $pages['ultimas-noticias'] ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['inicio'] );
		update_option( 'page_for_posts', $pages['ultimas-noticias'] );
	}

	if ( ! in_array( 0, $pages, true ) && ! in_array( 0, $categories, true ) && $primary_menu && $footer_menu ) {
		update_option( 'hs_initial_setup_done', HS_THEME_VERSION );
	}
}
add_action( 'after_switch_theme', 'hs_run_initial_setup' );

/*
 * Desarrollo local solamente: para volver a ejecutar el scaffold de forma
 * manual, borrar la opción una vez y reactivar el tema. No dejar esto activo.
 *
 * delete_option( 'hs_initial_setup_done' );
 */
