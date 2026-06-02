<?php
/**
 * Sidebar editorial de noticias — HolaSalta.
 *
 * Módulos activos (en orden):
 *   1. Último momento / En desarrollo
 *   2. Más de esta sección
 *   3. Publicidad
 *   4. Newsletter / WhatsApp
 *   5. Lo más comentado (solo en contexto single)
 *
 * Módulos preparados para segunda etapa (ver comentarios al final del archivo):
 *   6. Seguí este tema
 *   7. Encuesta rápida
 *
 * Acepta $args:
 *   context  (string)  'single' | 'category' | 'generic'
 *   post_id  (int)     ID del post actual (contexto single).
 *   category (WP_Term) Categoría primaria del post o del archivo.
 *
 * @package HolaSalta_Child
 */

$context  = isset( $args['context'] ) ? $args['context'] : 'generic';
$post_id  = isset( $args['post_id'] ) ? (int) $args['post_id'] : 0;
$category = ( isset( $args['category'] ) && $args['category'] instanceof WP_Term )
	? $args['category'] : null;

/**
 * Devuelve la hora si el post es de hoy, o la fecha corta si es de otro día.
 * Se define como closure para evitar conflictos si el sidebar se carga más de una vez.
 *
 * @param WP_Post|int|null $post
 * @return string
 */
$hs_post_time = static function ( $post = null ) {
	$time  = (int) get_the_time( 'U', $post );
	$today = wp_date( 'Y-m-d' );
	return wp_date( 'Y-m-d', $time ) === $today
		? get_the_time( 'H:i', $post )
		: get_the_date( 'd M', $post );
};
?>

<aside class="hs-sidebar" aria-label="<?php esc_attr_e( 'Contenido adicional', 'holasalta-child' ); ?>">

	<?php
	/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 1 — ÚLTIMO MOMENTO / EN DESARROLLO
	 *
	 * Prioridad:
	 *   a) Posts etiquetados con 'urgente' o 'en-desarrollo' (si existen).
	 *   b) Fallback: las 5 noticias más recientes del sitio.
	 *
	 * Para marcar una nota como urgente: asignarle la etiqueta 'urgente'
	 * o 'en-desarrollo' desde WP Admin → Entradas → Etiquetas.
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

	$breaking_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 5,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'tag'                 => 'urgente,en-desarrollo',
	);

	if ( $post_id ) {
		$breaking_args['post__not_in'] = array( $post_id );
	}

	$breaking = new WP_Query( $breaking_args );
	$is_urgent = $breaking->have_posts();

	if ( ! $is_urgent ) {
		// Fallback: recientes generales.
		$breaking = hs_get_posts_by_category(
			'',
			5,
			$post_id ? array( 'post__not_in' => array( $post_id ) ) : array()
		);
	}

	if ( $breaking->have_posts() ) :
	?>
	<section class="hs-sidebar-block hs-sidebar-breaking">
		<h2 class="hs-sidebar-breaking-title">
			<?php if ( $is_urgent ) : ?>
				<span class="hs-live-dot" aria-hidden="true"></span>
				<?php esc_html_e( 'En desarrollo', 'holasalta-child' ); ?>
			<?php else : ?>
				<?php esc_html_e( 'Último momento', 'holasalta-child' ); ?>
			<?php endif; ?>
		</h2>
		<ul class="hs-breaking-list">
			<?php
			while ( $breaking->have_posts() ) :
				$breaking->the_post();
				$b_cat = hs_get_primary_category();
				?>
				<li class="hs-breaking-item">
					<?php if ( $is_urgent ) : ?>
						<span class="hs-urgent-badge" aria-label="<?php esc_attr_e( 'Urgente', 'holasalta-child' ); ?>">
							<?php esc_html_e( 'URGENTE', 'holasalta-child' ); ?>
						</span>
					<?php endif; ?>
					<a href="<?php echo esc_url( get_permalink() ); ?>">
						<?php the_title(); ?>
					</a>
					<div class="hs-breaking-meta">
						<?php if ( $b_cat ) : ?>
							<span class="hs-breaking-cat"><?php echo esc_html( $b_cat->name ); ?></span>
						<?php endif; ?>
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( $hs_post_time( get_the_ID() ) ); ?>
						</time>
					</div>
				</li>
			<?php endwhile; wp_reset_postdata(); ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php
	/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 2 — MÁS DE ESTA SECCIÓN
	 *
	 * Contextual: muestra notas de la misma sección/categoría que el post
	 * actual. En páginas de categoría muestra las más recientes de esa sección.
	 * En contextos sin categoría, muestra las 4 últimas del sitio.
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

	if ( 'single' === $context && $category ) {
		$section_posts = hs_get_posts_by_category(
			$category->slug,
			4,
			array( 'post__not_in' => array( $post_id ) )
		);
		$section_title = sprintf(
			/* translators: %s: category name. */
			__( 'Más de %s', 'holasalta-child' ),
			$category->name
		);
	} elseif ( 'category' === $context && $category ) {
		$section_posts = hs_get_posts_by_category( $category->slug, 4 );
		$section_title = sprintf(
			/* translators: %s: category name. */
			__( 'Lo más reciente en %s', 'holasalta-child' ),
			$category->name
		);
	} else {
		$section_posts = hs_get_posts_by_category( '', 4 );
		$section_title = __( 'Últimas noticias', 'holasalta-child' );
	}

	if ( $section_posts->have_posts() ) :
	?>
	<section class="hs-sidebar-block">
		<h2><?php echo esc_html( $section_title ); ?></h2>
		<div class="hs-sidebar-compact-list">
			<?php
			while ( $section_posts->have_posts() ) :
				$section_posts->the_post();
				hs_render_post_card( array( 'variant' => 'compact' ) );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php endif; ?>

	<?php
	/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 3 — PUBLICIDAD
	 *
	 * Espacio para banner. Gestionar desde:
	 * WP Admin → Apariencia → Widgets → "Publicidad single arriba".
	 * Tamaño sugerido: 300×250.
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

	hs_render_ad_slot( 'single-top', '300x250' );
	?>

	<?php
	/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 4 — NEWSLETTER / WHATSAPP
	 *
	 * Bloque de conversión para el canal de WhatsApp.
	 * Reemplazar el valor de href con la URL real del canal cuando esté listo.
	 * Formato: https://whatsapp.com/channel/XXXXXXXXXXXXXXX
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
	?>
	<div class="hs-sidebar-wa">
		<svg class="hs-sidebar-wa-icon" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
			<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
		</svg>
		<p class="hs-sidebar-wa-title">
			<?php esc_html_e( 'Recibí las noticias más importantes en tu WhatsApp', 'holasalta-child' ); ?>
		</p>
		<a
			class="hs-sidebar-wa-btn"
			href="https://chat.whatsapp.com/F513H1pRXeHCeztkeY1vYu"
			target="_blank"
			rel="noopener noreferrer nofollow"
			aria-label="<?php esc_attr_e( 'Unirme al canal de WhatsApp de HolaSalta', 'holasalta-child' ); ?>"
		>
			<?php esc_html_e( 'Unirme al canal', 'holasalta-child' ); ?>
		</a>
	</div>

	<?php
	/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 5 — LO MÁS COMENTADO
	 *
	 * Solo se muestra en el contexto single (post individual).
	 * Posts con más comentarios en los últimos 30 días.
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

	if ( 'single' === $context ) :
		$most_commented = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 4,
				'orderby'             => 'comment_count',
				'order'               => 'DESC',
				'date_query'          => array( array( 'after' => '30 days ago' ) ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
				'post__not_in'        => array( $post_id ),
			)
		);

		if ( $most_commented->have_posts() ) :
	?>
	<section class="hs-sidebar-block">
		<h2><?php esc_html_e( 'Lo más comentado', 'holasalta-child' ); ?></h2>
		<div class="hs-sidebar-compact-list">
			<?php
			while ( $most_commented->have_posts() ) :
				$most_commented->the_post();
				hs_render_post_card( array( 'variant' => 'compact' ) );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
		endif;
	endif;
	?>

	<?php
	/*
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 6 — SEGUÍ ESTE TEMA  [SEGUNDA ETAPA — INACTIVO]
	 *
	 * Muestra las etiquetas (tags) asociadas a la noticia actual para que
	 * el lector pueda seguir un tema específico (ej: Elecciones, Seguridad).
	 *
	 * Para activar: descomentar el bloque y ajustar el estilo .hs-sidebar-tags.
	 *
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 *
	 * if ( 'single' === $context && has_tag() ) :
	 * ?>
	 * <section class="hs-sidebar-block hs-sidebar-tags">
	 *     <h2><?php esc_html_e( 'Seguí este tema', 'holasalta-child' ); ?></h2>
	 *     <div class="hs-tags-cloud">
	 *         <?php echo wp_kses_post( get_the_tag_list( '', ' ' ) ); ?>
	 *     </div>
	 * </section>
	 * <?php
	 * endif;
	 *
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 * MÓDULO 7 — ENCUESTA RÁPIDA  [SEGUNDA ETAPA — INACTIVO]
	 *
	 * Encuesta simple de sí/no para el lector dentro del sidebar.
	 * Requiere implementar guardado de votos (transients o tabla custom).
	 *
	 * Para activar: implementar hs_render_sidebar_poll() en template-tags.php
	 * y descomentar la llamada de abajo.
	 *
	 * ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
	 *
	 * if ( function_exists( 'hs_render_sidebar_poll' ) ) :
	 *     hs_render_sidebar_poll();
	 * endif;
	 *
	 */
	?>

</aside>
