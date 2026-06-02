<?php
/**
 * Posts page: "Últimas noticias".
 *
 * Se usa cuando WP Admin → Ajustes → Lectura asigna una página estática
 * a la portada y otra página para el índice de entradas.
 *
 * Layout:
 *   - Header estilo sección con kicker "NOTICIAS".
 *   - Primera nota como hero full-bleed.
 *   - Resto en grilla 3 col + sidebar.
 *   - Paginación.
 *
 * @package HolaSalta_Child
 */

get_header();
?>

<div class="hs-page-shell hs-page-shell--category">
	<div class="hs-container">

		<header class="hs-category-header">
			<span class="hs-category-kicker"><?php esc_html_e( 'Noticias', 'holasalta-child' ); ?></span>
			<div class="hs-category-header-row">
				<h1 class="hs-category-title"><?php esc_html_e( 'Últimas noticias', 'holasalta-child' ); ?></h1>
			</div>
			<p class="hs-category-desc"><?php esc_html_e( 'La información más reciente de Salta y el país.', 'holasalta-child' ); ?></p>
		</header>

		<?php hs_render_ad_slot( 'category-top', '728x90' ); ?>

		<div class="hs-archive-layout">
			<div class="hs-archive-content">

				<?php if ( have_posts() ) : ?>

					<?php
					// Primera nota → hero editorial full-bleed.
					the_post();
					?>
					<div class="hs-category-hero">
						<?php
						hs_render_post_card(
							array(
								'variant'       => 'hero-main',
								'show_excerpt'  => true,
								'heading_level' => 'h2',
								'loading'       => 'eager',
							)
						);
						?>
					</div>

					<?php if ( have_posts() ) : ?>
						<div class="hs-grid hs-grid-archive">
							<?php
							while ( have_posts() ) :
								the_post();
								hs_render_post_card();
							endwhile;
							?>
						</div>
					<?php endif; ?>

					<?php hs_render_pagination(); ?>

				<?php else : ?>
					<p class="hs-empty"><?php esc_html_e( 'Todavía no hay noticias publicadas.', 'holasalta-child' ); ?></p>
				<?php endif; ?>

			</div>

			<?php
			get_template_part(
				'template-parts/sidebar-news',
				null,
				array( 'context' => 'category' )
			);
			?>
		</div>

	</div>
</div>

<?php get_footer(); ?>

