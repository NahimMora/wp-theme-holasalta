<?php
/**
 * Category archive template.
 *
 * Layout editorial:
 *   - Header de sección: kicker, nombre grande, contador de notas y descripción.
 *   - Primera nota como hero full-bleed con texto superpuesto.
 *   - Resto de notas en grilla de 3 columnas.
 *   - Sidebar contextual con notas de la misma sección.
 *
 * @package HolaSalta_Child
 */

get_header();

$current_cat = get_queried_object();
$cat_count   = $current_cat instanceof WP_Term ? (int) $current_cat->count : 0;
?>

<div class="hs-page-shell hs-page-shell--category">
	<div class="hs-container">

		<header class="hs-category-header">
			<span class="hs-category-kicker"><?php esc_html_e( 'Sección', 'holasalta-child' ); ?></span>
			<div class="hs-category-header-row">
				<h1 class="hs-category-title"><?php single_cat_title(); ?></h1>
				<?php if ( $cat_count ) : ?>
					<span class="hs-category-count">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %d: number of posts in category. */
								_n( '%d nota', '%d notas', $cat_count, 'holasalta-child' ),
								$cat_count
							)
						);
						?>
					</span>
				<?php endif; ?>
			</div>
			<?php if ( category_description() ) : ?>
				<p class="hs-category-desc"><?php echo wp_kses_post( category_description() ); ?></p>
			<?php endif; ?>
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
					<p class="hs-empty">
						<?php esc_html_e( 'Todavía no hay noticias publicadas en esta sección.', 'holasalta-child' ); ?>
					</p>
				<?php endif; ?>

			</div>

			<?php
			get_template_part(
				'template-parts/sidebar-news',
				null,
				array(
					'context'  => 'category',
					'category' => $current_cat,
				)
			);
			?>
		</div>

	</div>
</div>

<?php get_footer(); ?>
