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

	$qs = '<!-- wp:heading {"level":2} --><h2>Quiénes somos</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>HolaSalta.com es un medio de comunicación digital nacido en Salta, Argentina, con el objetivo de informar a la comunidad salteña de manera clara, rápida y responsable.</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph --><p>Cubrimos las noticias locales, provinciales, nacionales e internacionales que afectan la vida cotidiana de los salteños: seguridad, deportes, espectáculos, política y mucho más.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Nuestra misión</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Creemos en el periodismo como servicio público. Trabajamos para brindar información verificada, contextualizada y accesible para todos los ciudadanos de Salta.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Nuestros valores</h2><!-- /wp:heading -->'
		. '<!-- wp:list --><ul><!-- wp:list-item --><li>Veracidad: publicamos solo lo que podemos verificar.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Independencia: no respondemos a intereses partidarios ni comerciales.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Cercanía: priorizamos las historias que importan a la comunidad salteña.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Responsabilidad: asumimos nuestros errores y los corregimos de forma transparente.</li><!-- /wp:list-item --></ul><!-- /wp:list -->'
		. '<!-- wp:heading {"level":2} --><h2>Contacto</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Para comunicarte con nuestra redacción escribinos a <a href="mailto:contacto@holasalta.com">contacto@holasalta.com</a> o envianos un mensaje por <a href="https://wa.me/5493875230770" target="_blank" rel="noopener noreferrer">WhatsApp</a>.</p><!-- /wp:paragraph -->';

	$contacto = '<!-- wp:heading {"level":2} --><h2>Escribinos</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>¿Tenés una noticia para compartir, una consulta editorial o una sugerencia? Nos podés contactar por cualquiera de estos canales.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":3} --><h3>Redacción</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Correo electrónico: <a href="mailto:contacto@holasalta.com">contacto@holasalta.com</a></p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph --><p>WhatsApp: <a href="https://wa.me/5493875230770" target="_blank" rel="noopener noreferrer">+54 9 387 523-0770</a></p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":3} --><h3>Publicidad</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Para consultas comerciales escribinos a <a href="mailto:publicidad@holasalta.com">publicidad@holasalta.com</a>.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":3} --><h3>Envianos tu noticia</h3><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Si tenés información de interés público podés enviarnos fotos, videos o datos a través de nuestro WhatsApp. Nuestro equipo evaluará el contenido y, si corresponde, lo publicará citando la fuente.</p><!-- /wp:paragraph -->';

	$publicidad = '<!-- wp:heading {"level":2} --><h2>Llegá a la audiencia salteña</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>HolaSalta.com es uno de los medios digitales de referencia en Salta. Ofrecemos espacios publicitarios para marcas, comercios, organismos y organizaciones que quieran llegar a nuestra comunidad.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Formatos disponibles</h2><!-- /wp:heading -->'
		. '<!-- wp:list --><ul><!-- wp:list-item --><li>Banner superior (728×90 px)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Banner rectangular (300×250 px)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Franja intermedia (500×160 px)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Cuadrado (450×450 px)</li><!-- /wp:list-item --><!-- wp:list-item --><li>Banner dentro del contenido de la noticia</li><!-- /wp:list-item --><!-- wp:list-item --><li>Contenido patrocinado</li><!-- /wp:list-item --></ul><!-- /wp:list -->'
		. '<!-- wp:heading {"level":2} --><h2>Ubicaciones</h2><!-- /wp:heading -->'
		. '<!-- wp:list --><ul><!-- wp:list-item --><li>Portada: arriba, medio y abajo</li><!-- /wp:list-item --><!-- wp:list-item --><li>Notas: sidebar superior, sidebar medio y dentro del artículo</li><!-- /wp:list-item --><!-- wp:list-item --><li>Secciones y categorías: banner superior</li><!-- /wp:list-item --></ul><!-- /wp:list -->'
		. '<!-- wp:heading {"level":2} --><h2>Consultanos</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Para conocer tarifas, disponibilidad y formatos personalizados escribinos a <a href="mailto:publicidad@holasalta.com">publicidad@holasalta.com</a> o por <a href="https://wa.me/5493875230770" target="_blank" rel="noopener noreferrer">WhatsApp</a>.</p><!-- /wp:paragraph -->';

	$pol_editorial = '<!-- wp:heading {"level":2} --><h2>Nuestros principios</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>HolaSalta.com se rige por estándares periodísticos de verificación, equidad e independencia. Esta política describe cómo producimos, revisamos y publicamos nuestro contenido.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Verificación y fuentes</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Publicamos únicamente información que fue verificada con al menos una fuente confiable. Cuando la información es preliminar o no confirmada, lo indicamos explícitamente en el texto.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Separación entre información y opinión</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Diferenciamos claramente el contenido informativo del contenido de opinión. Las columnas expresan la perspectiva de sus autores y están etiquetadas como tales.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Correcciones</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Cuando cometemos un error lo corregimos de forma transparente. Las correcciones se publican en el mismo artículo indicando la fecha y el contenido modificado.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Publicidad y contenido editorial</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>El contenido publicitario está claramente diferenciado del contenido editorial. Nuestros anunciantes no influyen en las decisiones informativas del medio.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Contacto editorial</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Para consultas, sugerencias o reclamos editoriales escribinos a <a href="mailto:contacto@holasalta.com">contacto@holasalta.com</a>.</p><!-- /wp:paragraph -->';

	$privacidad = '<!-- wp:heading {"level":2} --><h2>Datos que recopilamos</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>HolaSalta.com puede recopilar los siguientes tipos de datos:</p><!-- /wp:paragraph -->'
		. '<!-- wp:list --><ul><!-- wp:list-item --><li>Datos técnicos de navegación: dirección IP, tipo de navegador, páginas visitadas y tiempo de permanencia.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Datos que el usuario proporciona voluntariamente al enviar comentarios o formularios de contacto.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Cookies propias para el funcionamiento del sitio y cookies de terceros para publicidad y analítica.</li><!-- /wp:list-item --></ul><!-- /wp:list -->'
		. '<!-- wp:heading {"level":2} --><h2>Uso de los datos</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Los datos recopilados se utilizan exclusivamente para mejorar la experiencia de uso del sitio, responder consultas, mostrar publicidad a través de plataformas de terceros y generar estadísticas de audiencia de forma agregada y anónima.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Cookies</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Este sitio utiliza cookies propias y de terceros. Podés configurar tu navegador para rechazarlas, aunque esto puede afectar el funcionamiento de algunas secciones del sitio.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Derechos del usuario</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>De acuerdo con la Ley 25.326 de Protección de Datos Personales de la República Argentina, los usuarios tienen derecho a acceder, rectificar y suprimir sus datos personales. Para ejercer estos derechos escribinos a <a href="mailto:contacto@holasalta.com">contacto@holasalta.com</a>.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Servicios de terceros</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Este sitio puede integrar servicios de terceros como Google Analytics y plataformas publicitarias. El uso de datos por parte de estos servicios se rige por sus propias políticas de privacidad.</p><!-- /wp:paragraph -->';

	$terminos = '<!-- wp:heading {"level":2} --><h2>Uso del sitio</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>El acceso y uso de HolaSalta.com implica la aceptación de estos términos. Si no estás de acuerdo con alguno de ellos, te pedimos que no utilices el sitio.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Propiedad intelectual</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Todo el contenido publicado en HolaSalta.com —textos, fotografías, videos, gráficos y diseño— es propiedad de HolaSalta.com o de sus respectivos autores, y está protegido por las leyes de propiedad intelectual de la República Argentina.</p><!-- /wp:paragraph -->'
		. '<!-- wp:paragraph --><p>Está prohibida la reproducción total o parcial del contenido sin autorización expresa del medio. La cita de fragmentos con fines informativos está permitida siempre que se indique la fuente con enlace al artículo original.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Comentarios</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Los comentarios publicados por los usuarios son responsabilidad de quienes los emiten. HolaSalta.com se reserva el derecho de moderar, editar o eliminar comentarios que contengan insultos, discriminación, información falsa o cualquier contenido que viole la ley argentina.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Contenido de terceros</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Este sitio puede incluir enlaces a sitios externos. HolaSalta.com no es responsable por el contenido, la disponibilidad ni las prácticas de privacidad de esos sitios.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Limitación de responsabilidad</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>HolaSalta.com no garantiza la disponibilidad continua del sitio ni se hace responsable por daños derivados de su uso o de la imposibilidad de acceder a él.</p><!-- /wp:paragraph -->'
		. '<!-- wp:heading {"level":2} --><h2>Ley aplicable</h2><!-- /wp:heading -->'
		. '<!-- wp:paragraph --><p>Estos términos se rigen por las leyes de la República Argentina. Cualquier disputa será resuelta ante los tribunales competentes de la ciudad de Salta.</p><!-- /wp:paragraph -->';

	$page_content = array(
		'inicio'               => '',
		'ultimas-noticias'     => '',
		'quienes-somos'        => $qs,
		'contacto'             => $contacto,
		'publicidad'           => $publicidad,
		'politica-editorial'   => $pol_editorial,
		'politica-privacidad'  => $privacidad,
		'terminos-condiciones' => $terminos,
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
		'salta'           => hs_setup_category( 'Salta', 'salta' ),
		'policiales'      => hs_setup_category( 'Policiales', 'policiales' ),
		'nacionales'      => hs_setup_category( 'Nacionales', 'nacionales' ),
		'deportes'        => hs_setup_category( 'Deportes', 'deportes' ),
		'espectaculos'    => hs_setup_category( 'Espectáculos', 'espectaculos' ),
		'internacionales' => hs_setup_category( 'Internacionales', 'internacionales' ),
		'sabias-que'      => hs_setup_category( '¿Sabías que?', 'sabias-que' ),
		'columnas'        => hs_setup_category( 'Columnas', 'columnas' ),
	);

	$primary_menu = hs_setup_menu( 'HolaSalta Principal' );
	$footer_menu  = hs_setup_menu( 'HolaSalta Footer' );

	hs_setup_menu_item( $primary_menu, 'Inicio', 'page', $pages['inicio'] );
	hs_setup_menu_item( $primary_menu, 'Salta', 'category', $categories['salta'] );
	hs_setup_menu_item( $primary_menu, 'Policiales', 'category', $categories['policiales'] );
	hs_setup_menu_item( $primary_menu, 'Nacionales', 'category', $categories['nacionales'] );
	hs_setup_menu_item( $primary_menu, 'Deportes', 'category', $categories['deportes'] );
	hs_setup_menu_item( $primary_menu, 'Espectáculos', 'category', $categories['espectaculos'] );
	hs_setup_menu_item( $primary_menu, 'Internacionales', 'category', $categories['internacionales'] );
	hs_setup_menu_item( $primary_menu, '¿Sabías que?', 'category', $categories['sabias-que'] );
	hs_setup_menu_item( $primary_menu, 'Columnas', 'category', $categories['columnas'] );

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
