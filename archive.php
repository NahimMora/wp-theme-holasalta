<?php
/**
 * General archive fallback (fechas, autores, etiquetas).
 *
 * Layout:
 *   - Header estilo sección con kicker dinámico según tipo de archivo.
 *   - Grilla 3 col + sidebar.
 *   - Paginación.
 *
 * @package HolaSalta_Child
 */

get_header();

if ( is_author() ) {
	$kicker = __( 'Periodista', 'holasalta-child' );
} elseif ( is_tag() ) {
	$kicker = __( 'Etiqueta', 'holasalta-child' );
} elseif ( is_year() ) {
	$kicker = __( 'Año', 'holasalta-child' );
} elseif ( is_month() ) {
	$kicker = __( 'Mes', 'holasalta-child' );
} elseif ( is_day() ) {
	$kicker = __( 'Fecha', 'holasalta-child' );
} else {
	$kicker = __( 'Archivo', 'holasalta-child' );
}
?>

<div class="hs-page-shell hs-page-shell--archive">
	<div class="hs-container">

		<header class="hs-archive-header">
			<span class="hs-category-kicker"><?php echo esc_html( $kicker ); ?></span>
			<div class="hs-category-header-row">
				<?php the_archive_title( '<h1 class="hs-category-title">', '</h1>' ); ?>
			</div>
			<?php the_archive_description( '<p class="hs-category-desc">', '</p>' ); ?>
		</header>

		<?php hs_render_ad_slot( 'category-top', '728x90' ); ?>

		<div class="hs-archive-layout">
			<div class="hs-archive-content">

				<?php if ( have_posts() ) : ?>
					<div class="hs-grid hs-grid-archive">
						<?php
						while ( have_posts() ) :
							the_post();
							hs_render_post_card( array( 'show_excerpt' => true ) );
						endwhile;
						?>
					</div>

					<?php hs_render_pagination(); ?>

				<?php else : ?>
					<p class="hs-empty"><?php esc_html_e( 'No se encontraron noticias para este archivo.', 'holasalta-child' ); ?></p>
				<?php endif; ?>

			</div>

			<?php get_template_part( 'template-parts/sidebar-news' ); ?>
		</div>

	</div>
</div>

<?php get_footer(); ?>

