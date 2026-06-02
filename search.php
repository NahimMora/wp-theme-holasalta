<?php
/**
 * Search results template.
 *
 * Layout:
 *   - Header estilo sección: kicker "BÚSQUEDA" + término + contador de resultados.
 *   - Con resultados: grilla 3 col + sidebar.
 *   - Sin resultados: mensaje + formulario + lista de secciones editoriales.
 *
 * @package HolaSalta_Child
 */

get_header();

$search_query = get_search_query();
$found_posts  = (int) $GLOBALS['wp_query']->found_posts;
?>

<div class="hs-page-shell hs-page-shell--search">
	<div class="hs-container">

		<header class="hs-search-results-header">
			<span class="hs-category-kicker"><?php esc_html_e( 'Búsqueda', 'holasalta-child' ); ?></span>
			<div class="hs-category-header-row">
				<h1 class="hs-category-title">
					<?php echo $search_query ? esc_html( $search_query ) : esc_html__( 'Resultados', 'holasalta-child' ); ?>
				</h1>
				<?php if ( have_posts() ) : ?>
					<span class="hs-category-count">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %d: number of search results. */
								_n( '%d resultado', '%d resultados', $found_posts, 'holasalta-child' ),
								$found_posts
							)
						);
						?>
					</span>
				<?php endif; ?>
			</div>
		</header>

		<?php hs_render_ad_slot( 'category-top', '728x90' ); ?>

		<?php if ( have_posts() ) : ?>

			<div class="hs-archive-layout">
				<div class="hs-archive-content">

					<div class="hs-grid hs-grid-archive">
						<?php
						while ( have_posts() ) :
							the_post();
							hs_render_post_card( array( 'show_excerpt' => true ) );
						endwhile;
						?>
					</div>

					<?php hs_render_pagination(); ?>

				</div>

				<?php
				get_template_part(
					'template-parts/sidebar-news',
					null,
					array( 'context' => 'search' )
				);
				?>
			</div>

		<?php else : ?>

			<div class="hs-no-results">
				<p class="hs-no-results-message">
					<?php
					if ( $search_query ) {
						echo esc_html(
							sprintf(
								/* translators: %s: search query. */
								__( 'No encontramos resultados para "%s". Probá con otras palabras.', 'holasalta-child' ),
								$search_query
							)
						);
					} else {
						esc_html_e( 'Ingresá un término para buscar noticias.', 'holasalta-child' );
					}
					?>
				</p>

				<?php get_search_form(); ?>

				<?php
				$sections = hs_get_editorial_sections();
				if ( $sections ) :
				?>
					<div class="hs-no-results-sections">
						<p class="hs-no-results-hint"><?php esc_html_e( 'Explorá nuestras secciones:', 'holasalta-child' ); ?></p>
						<ul class="hs-no-results-list">
							<?php foreach ( $sections as $slug => $label ) : ?>
								<li>
									<a href="<?php echo esc_url( hs_get_section_url( $slug ) ); ?>">
										<?php echo esc_html( $label ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>

		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>

